---
name: nukeviet-security
description: Bảo mật & Code Review NukeViet 5.x. Load khi audit code, review MR, tìm lỗ hổng bảo mật, kiểm duyệt convention và chất lượng code.
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

# [5/6] CSRF (Tìm file POST thiếu NV_CHECK_SESSION hoặc checkss)
grep -rl "isset_request(.*'post'" $TARGET --include="*.php" | xargs -r grep -L "checkss\|NV_CHECK_SESSION\|hash_equals"

# [6/6] TÌM MẬT KHẨU / LỘ SECRET KEY
grep -rn "password\|passwd\|secret\|api_key" $TARGET --include="*.php" | grep -v "//\|#\|\$_POST\|\$config\|lang_module\|lang_global\|nv_Lang"
```

---

## Lỗi phổ biến và cách fix

### Input — PHẢI qua $nv_Request
> **Tham khảo mẫu chống XSS/SQLi qua Request:** `view_file` -> `.agent/skills/nukeviet-security/examples/PatternInput.php`

### SQL — PDO prepared statement cho user input
> **Tham khảo mẫu SQL Prepared Statement:** `view_file` -> `.agent/skills/nukeviet-security/examples/PatternSQL.php`

> Hằng hệ thống (`NV_CURRENTTIME`, `$admin_info['admin_id']`...) và số nguyên đã ép kiểu `(int)` nối thẳng vào SQL là an toàn — không cần prepare.

### CSRF — Kiểm tra token trước khi xử lý POST
NukeViet có **2 pattern** CSRF tuỳ ngữ cảnh (Frontend và Admin).
> **Tham khảo mẫu verify CSRF Token:** `view_file` -> `.agent/skills/nukeviet-security/examples/PatternCSRF.php`

> [!IMPORTANT]
> Luôn dùng `hash_equals($expected, $actual)` để so sánh token CSRF. Việc so sánh bằng `!=` hoặc `==` có thể bị khai thác qua timing attacks.
> Cố gắng tạo `$checkss_expected` tại 1 điểm duy nhất trên cùng của file nếu cùng giá trị để tiện bảo trì.

> ⚠ Ở admin, tạo `$checkss_expected` bằng cách truyền toàn bộ context vào chuỗi hash:
> `$checkss_expected = hash_hmac('sha256', NV_CHECK_SESSION . '_' . $module_name . '_' . $op . '_' . $admin_info['admin_id'], NV_CACHE_PREFIX);`

### Các lỗi bảo mật khác (XSS, Path Traversal, Open Redirect, Upload, Object Injection)
> **Tham khảo code mẫu phòng chống các lỗi còn lại:** `view_file` -> `.agent/skills/nukeviet-security/examples/PatternMisc.php`

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
| `NV_CHECK_SESSION` | Hằng CSRF session base |
| `hash_hmac` | Tạo `$checkss_expected` an toàn — Luôn dùng kèm `hash_equals` để kiểm tra biến POST từ JS truyền lên |
| `nv_is_file()` | Kiểm tra file an toàn |
| `nv_redirect_encrypt/decrypt` | Redirect an toàn |
| `nv_check_valid_email()` | Validate email |

---

## Tiêu chuẩn Code Quality & Convention (Khi Review)

### Convention NukeViet 5
- [ ] Có hằng số bảo vệ đầu file không (`NV_IS_FILE_ADMIN` hoặc `NV_SYSTEM`...)?
- [ ] Prefix bảng có dùng `NV_PREFIXLANG` thay vì hardcode `nv4_vi_` không?
- [ ] PSR-4: Namespace phân bổ logic có đúng không?
- [ ] Đọc dữ liệu (SELECT) ưu tiên dùng `$db_slave`, Ghi dữ liệu dùng `$db`.

### Code Quality & Performance
- [ ] Có tuân thủ PSR-12 (4 spaces, thụt lề chuẩn, camelCase vs snake_case...)?
- [ ] Sử dụng `$nv_Cache->db()` cho các truy vấn lấy dữ liệu tĩnh, ít đổi chưa?
- [ ] CÓ đặt câu truy vấn DB (SQL) bên trong vòng lặp hay không (Gây rủi ro N+1 queries)?

---

## Mức độ báo cáo khi review

- 🔴 **CHẶN MERGE** — SQLi, XSS rõ ràng, thiếu CSRF token, thiếu kiểm tra phân quyền, `unserialize` không giới hạn class, vòng lặp chứa câu truy vấn ác ý.
- 🟡 **NÊN FIX** — Lỗi Logic, chưa chuẩn Convention, chưa tối ưu hiệu năng (không check cache), sử dụng sai `$db_slave`.
- 💡 **GỢI Ý** — Cải thiện tính tái sử dụng, tối ưu code thừa, không bắt buộc.
