---
name: nukeviet-security
description: Bảo mật NukeViet 5.x. Load khi review bảo mật, audit code, tìm lỗ hổng XSS/SQLi/Open Redirect/upload.
allowed-tools: Read, Grep, Glob, Bash
---

# Hướng Dẫn Bảo Mật NukeViet 5.x

## Quy trình audit bảo mật

**Kết hợp 2 bước để có kết quả tốt nhất:**
```bash
# Bước 1 — Code Security audit hệ thống (Lỗi XSS & Injection)
/security-audit modules/ten-module/

# Bước 2 — Rà soát thủ công theo checklist bên dưới
```

---

## Scan nhanh
```bash
# Input không qua $nv_Request
grep -rn "\$_GET\|\$_POST\|\$_REQUEST" . --include="*.php" | grep -v "nv_Request\|(int)\|(float)"

# Output không escape
grep -rn "echo \$\|print \$" . --include="*.php" | grep -v "htmlspecialchars\|nv_html\|intval"

# is_file với path từ user
grep -rn "is_file\|file_exists" . --include="*.php" | grep -v "nv_is_file\|NV_ROOTDIR"

# Open Redirect qua selfurl
grep -rn "client_info\['selfurl'\]" . --include="*.php" | grep "redirect\|location\|header"

# Nối chuỗi input trực tiếp vào SQL (không qua prepare)
grep -rn "\$_POST\|\$_GET\|\$_REQUEST" . --include="*.php" | grep "query\|WHERE\|INSERT\|UPDATE"

# Form thiếu CSRF token
grep -rn "isset.*submit\|submit.*isset" . --include="*.php" | grep -v "nv_check_formtoken"
```

---

## Lỗi phổ biến và cách fix

### Input — PHẢI qua $nv_Request
```php
// ❌ Sai
$id = $_GET['id'];

// ✅ Đúng
$id    = $nv_Request->get_int('id', 'get', 0);
$title = $nv_Request->get_title('title', 'post', '');
$title = nv_substr($title, 0, 255);
$body  = $nv_Request->get_editor('body', '', NV_ALLOWED_HTML_TAGS);
$desc  = $nv_Request->get_textarea('desc', '', NV_ALLOWED_HTML_TAGS);
```

### SQL — PDO prepared statement cho user input
```php
// ❌ Sai — nối chuỗi input trực tiếp
$sql = "WHERE title = '" . $_POST['title'] . "'";

// ✅ Đúng — số nguyên dùng (int)
$sql = '... WHERE id = ' . (int) $id;

// ✅ Đúng — chuỗi từ user dùng prepared statement
$sql = 'SELECT * FROM ' . NV_PREFIXLANG . '_items WHERE title = :title';
$sth = $db->prepare($sql);
$sth->bindParam(':title', $title, PDO::PARAM_STR);
$sth->execute();
$row = $sth->fetch();
```

> Hằng hệ thống (`NV_CURRENTTIME`, `$admin_info['admin_id']`...) và số nguyên đã ép kiểu `(int)` nối thẳng vào SQL là an toàn — không cần prepare.

### CSRF — PHẢI kiểm tra formtoken
```php
// ❌ Sai — xử lý POST không kiểm tra CSRF
if ($nv_Request->isset_request('submit', 'post')) {
    // xử lý...
}

// ✅ Đúng
if ($nv_Request->isset_request('submit', 'post')) {
    if (!nv_check_formtoken()) {
        nv_redirect_location($page_url);
    }
    // xử lý...
}
```

> Trong form HTML phải có: `nv_form_token()` để sinh token.

### XSS output
```php
// ❌ Sai
echo $row['title'];

// ✅ Đúng
echo nv_htmlspecialchars($row['title']);
```

### Kiểm tra file — dùng nv_is_file
```php
// ❌ Sai — path từ user, không kiểm soát
is_file(NV_DOCUMENT_ROOT . $path_from_user);

// ✅ Đúng
nv_is_file($path_from_user, $uploads_dir_user);
```

### Open Redirect — không dùng selfurl trực tiếp
```php
// ❌ Sai
nv_redirect_location($client_info['selfurl']);

// ✅ Đúng
nv_redirect_location($page_url);

// Nếu cần truyền URL qua form: nv_redirect_encrypt() / nv_get_redirect()
// Nếu hiển thị ra HTML: nv_htmlspecialchars($client_info['selfurl'])
```

### Upload file
```php
$finfo = finfo_open(FILEINFO_MIME_TYPE);
$mime  = finfo_file($finfo, $_FILES['f']['tmp_name']);
finfo_close($finfo);
if (!in_array($mime, ['image/jpeg', 'image/png', 'image/gif', 'image/webp'], true)) {
    // từ chối upload
}
$ext      = nv_getextension($_FILES['f']['name']);
$new_name = md5(uniqid(mt_rand(), true)) . '.' . strtolower($ext);
```

### Phân quyền admin
```php
function xoaItem($id) {
    if (!defined('NV_IS_ADMIN')) {
        exit('Stop!!!');
    }
    global $db;
    $db->query('DELETE FROM ' . NV_PREFIXLANG . '_items WHERE id = ' . (int) $id);
}
```

---

## Bảng tra nhanh — hàm bảo mật

### $nv_Request — đầy đủ method

**Tham số `$mode`** nhận: `'get'`, `'post'`, `'session'`, `'cookie'`, `'request'`, `'env'`, `'server'`.
Có thể dùng nhiều mode cách nhau dấu phẩy — lấy từ mode đầu tiên tìm thấy: `'get,post'`.

| Method | Dùng khi | Ghi chú |
|---|---|---|
| `get_int($name, $mode, $default)` | Input số nguyên | |
| `get_absint($name, $mode, $default)` | Số nguyên tuyệt đối (luôn ≥0) | `abs((int) $value)` |
| `get_float($name, $mode, $default)` | Input số thực | |
| `get_bool($name, $mode, $default)` | Input boolean | |
| `get_title($name, $mode, $default)` | Text ngắn — strip HTML, giữ text | |
| `get_string($name, $mode, $default)` | Chuỗi đã lọc bảo mật — HTML bị strip/escape | **Không phải raw** — vẫn qua security filter |
| `get_editor($name, $default, $allowed_tags)` | Nội dung WYSIWYG | **Chỉ đọc từ POST** — không có param `$mode` |
| `get_textarea($name, $default, $allowed_tags, $save)` | Nội dung textarea | **Chỉ đọc từ POST** — `$save=true` chuyển newline → `<br />` |
| `get_array($name, $mode, $default)` | Mảng từ GET/POST | vd: checkbox group |
| `get_typed_array($name, $mode, $type, ...)` | Mảng ép kiểu | `$type`: 'int','bool','float','string','title','textarea','editor' |
| `set_Session($name, $value)` | Ghi vào session (encode AES) | Đọc lại bằng `get_int/get_string(..., 'session')` |
| `set_Cookie($name, $value, $expire)` | Ghi cookie an toàn (encode AES) | `$expire` = số giây kể từ bây giờ |
| `isset_request($names, $mode, $all)` | Kiểm tra key tồn tại | `$all=true`: tất cả phải có; `$all=false`: ít nhất 1 |
| `unset_request($names, $mode)` | Xóa key khỏi superglobal | |

### Các hàm bảo mật khác

| Hàm | Dùng khi |
|---|---|
| `nv_htmlspecialchars()` | Escape HTML output |
| `$db->prepare()` + `bindParam()` | Chuỗi từ user vào SQL |
| `$db->dblikeescape($value)` | Escape ký tự đặc biệt trong LIKE |
| `nv_check_formtoken()` | Xác minh CSRF token khi xử lý POST |
| `nv_form_token()` | Sinh CSRF token trong form HTML |
| `nv_is_file()` | Kiểm tra file an toàn |
| `nv_redirect_encrypt/decrypt` | Redirect an toàn |
| `nv_check_valid_email()` | Validate email |

---

## Mức độ báo cáo khi review

- 🔴 **CHẶN MERGE** — SQLi, XSS rõ ràng, thiếu CSRF token, thiếu kiểm tra phân quyền
- 🟡 **NÊN FIX** — Open Redirect, dùng `is_file` với path từ user
- 💡 **GỢI Ý** — cải thiện thêm, không bắt buộc
