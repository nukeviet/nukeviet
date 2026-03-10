---
name: nukeviet-security
description: Bảo mật NukeViet 5.x. Load khi review bảo mật, audit code, tìm lỗ hổng XSS/SQLi/Open Redirect/upload.
allowed-tools: Read, Grep, Glob, Bash
---

# Hướng Dẫn Bảo Mật NukeViet 5.x

## Quy trình audit bảo mật

**Kết hợp 2 bước để có kết quả tốt nhất:**
```bash
# Bước 1 — Code Security audit 1 mục tiêu (Lập list lỗi tiềm ẩn)
/security-audit news        # Module frontend
/security-audit authors     # Module hệ thống (admin)
/security-audit src/modules/news/blocks/global.block_news.php # File cụ thể

# Bước 2 — Rà soát thủ công theo checklist bên dưới
```

---

## Scan nhanh thủ công (dành cho AI)
```bash
TARGET="src/modules/ten-module/"  # Hoặc src/admin/modules/ten-module/ hoặc src/admin/ten-module/

# [1/6] Input không qua $nv_Request
grep -rn "\$_GET\|\$_POST\|\$_REQUEST" $TARGET --include="*.php" | grep -v "nv_Request\|(int)\|(float)"

# [2/6] Output không escape (XSS)
grep -rn "echo \$\|print \$" $TARGET --include="*.php" | grep -v "htmlspecialchars\|nv_html\|intval\|NVSmarty"

# [3/6] Path Traversal (is_file/file_exists/unlink không qua nv_is_file)
grep -rn "is_file\|file_exists\|unlink" $TARGET --include="*.php" | grep -v "nv_is_file\|NV_ROOTDIR"

# [4/6] unserialize() — Object Injection
grep -rn "unserialize(" $TARGET --include="*.php"

# [5/6] CSRF (Tìm file POST thiếu nv_check_formtoken hoặc checkss)
grep -rl "isset_request(.*'post'" $TARGET --include="*.php" | xargs -r grep -L "nv_check_formtoken\|checkss\|NV_CHECK_SESSION"

# [6/6] TÌM MẬT KHẨU / LỘ SECRET KEY
grep -rn "password\|passwd\|secret\|api_key" $TARGET --include="*.php" | grep -v "//\|#\|\$_POST\|\$config\|lang_module\|lang_global\|nv_Lang"
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
// ❌ Sai — Nối chuỗi mảng ID gây SQLi
$sql = "WHERE id IN (" . implode(',', $_POST['ids']) . ")";

// ✅ Đúng — Ép kiểu nguyên cho toàn bộ mảng trước khi implode
$ids = array_map('intval', $nv_Request->get_typed_array('ids', 'post', 'int', []));
$sql = "WHERE id IN (" . implode(',', $ids) . ")";

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

### CSRF — Kiểm tra token trước khi xử lý POST

NukeViet có **2 pattern** CSRF tuỳ ngữ cảnh:

**Pattern 1 — Frontend (form ngoài site):**
```php
// Trong form HTML:
<?php echo nv_form_token(); ?>

// Khi xử lý POST:
if (!nv_check_formtoken()) {
    nv_redirect_location($page_url);
}
```

**Pattern 2 — Admin (AJAX/JSON response):**
```php
// Trong template: gán biến CHECKSS
$tpl->assign('CHECKSS', md5(NV_CHECK_SESSION . '_' . $module_name . '_' . $admin_info['userid']));

// Khi xử lý POST:
$checkss = $nv_Request->get_title('checkss', 'post', '');
if ($checkss != md5(NV_CHECK_SESSION . '_' . $module_name . '_' . $admin_info['userid'])) {
    nv_jsonOutput(['status' => 'error', 'mess' => $nv_Lang->getGlobal('error_code_11')]);
}
```

> ⚠ Ở admin, có module nối thêm `$op` vào chuỗi hash: `md5(NV_CHECK_SESSION . '_' . $module_name . '_' . $op . '_' . $admin_info['userid'])`.

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
// ❌ Sai — Xử lý $_FILES thủ công
$finfo = finfo_open(FILEINFO_MIME_TYPE); // ...

// ✅ Đúng — Dùng class Upload của NukeViet
require_once NV_ROOTDIR . '/includes/class/upload.class.php';
$upload = new \NukeViet\Files\Upload($allow_exts, $global_config['forbid_extensions'], $global_config['forbid_mimes'], NV_UPLOAD_MAX_FILESIZE, NV_DIR_USER, $nv_is_admin);
$upload_info = $upload->save_file($_FILES['f'], $upload_dir, $replace_if_exists);
```

### Phân quyền admin
```php
// ❌ Chưa đủ an toàn cho các tác vụ nhạy cảm
if (!defined('NV_IS_ADMIN')) { exit('Stop!!!'); }

// ✅ Đúng — File admin phải check NV_IS_FILE_ADMIN (đã bao gồm NV_IS_MODADMIN)
if (!defined('NV_IS_FILE_ADMIN')) { exit('Stop!!!'); }

// ✅ Chức năng đặc quyền — thêm check NV_IS_SPADMIN
if (defined('NV_IS_SPADMIN')) {
    // Chỉ super admin mới được config, xóa toàn bộ...
}
```

### unserialize() — Nguy cơ Object Injection
```php
// ❌ Sai — unserialize dữ liệu từ DB không giới hạn class
$data = unserialize($row['others']);

// ✅ Đúng — Chặn instantiate class bất kỳ
$data = unserialize($row['others'], ['allowed_classes' => false]);

// ✅ Tốt nhất — Migrate sang JSON, bỏ hẳn unserialize
$data = json_decode($row['others'], true);
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
| `nv_check_formtoken()` | Xác minh CSRF token (frontend form) |
| `nv_form_token()` | Sinh CSRF token trong form HTML (frontend) |
| `md5(NV_CHECK_SESSION . '_' . ...)` | Xác minh CSRF token (admin AJAX) |
| `nv_is_file()` | Kiểm tra file an toàn |
| `nv_redirect_encrypt/decrypt` | Redirect an toàn |
| `nv_check_valid_email()` | Validate email |

---

## Mức độ báo cáo khi review

- 🔴 **CHẶN MERGE** — SQLi, XSS rõ ràng, thiếu CSRF token, thiếu kiểm tra phân quyền, `unserialize` không giới hạn class
- 🟡 **NÊN FIX** — Open Redirect, dùng `is_file` với path từ user, `get_string` thay vì `get_title`
- 💡 **GỢI Ý** — cải thiện thêm, không bắt buộc
