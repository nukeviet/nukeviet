# Các thay đổi lớn trong NukeViet 5.0

## Tháng 3 năm 2026

### db-refactor
- Bỏ ->sqlreset khỏi codebase
- Bỏ ->insert_id khỏi codebase
- Bỏ ->affected_rows_count
- Tối ưu biến tạm khi dùng ->fetch(3)
- Tối ưu code theo Skill db-refactor


### Thống nhất dùng try catch
```php
try {

} catch (Throwable $e) {
    trigger_error($e);
}
```
Chạy tool tools\try_catch_audit.php để quét tất cả các file và sửa lại, sau đó nhờ AI sửa dựa trên file report

### Thêm đối số NV_JSON_ENCODE cho hàm json_encode

Đã định nghĩa trong src/includes/constants.php

```php
// JSON encode cho API response và lưu DB
define('NV_JSON_ENCODE', JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);

// JSON encode nhúng trong <script> tag HTML
define('NV_JSON_ENCODE_SCRIPT', JSON_HEX_TAG | JSON_HEX_AMP | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
```

Ví dụ
```php
// JSON cho API response
header('Content-Type: application/json; charset=utf-8');
echo json_encode($data, NV_JSON_ENCODE);

// JSON lưu DB / Cache
$cache = json_encode($data, NV_JSON_ENCODE);

// JSON nhúng trong <script> tag HTML
echo json_encode($data, NV_JSON_ENCODE_SCRIPT);
```

Lệnh yêu cầu AI thực hiện
```
Tìm tất cả các file php dùng json_encode, thêm hoặc thay đối số $flags (json_encode(mixed $value, int $flags = 0, int $depth = 512)) bằng:
- NV_JSON_ENCODE_SCRIPT, nếu JSON encode nhúng trong <script> tag HTML
- Còn lại dùng biến NV_JSON_ENCODE

Loại trừ thư mục src/includes/vendor
nhưng vẫn thực hiện cho thư mục: src/includes/vendor/vinades
```

### Thêm đối số NV_UNSERIALIZE_SAFE cho hàm unserialize

Đã định nghĩa trong src/includes/constants.php

```php
// Option an toàn cho unserialize — chỉ cho phép array/scalar, không cho phép object
define('NV_UNSERIALIZE_SAFE', ['allowed_classes' => false]);
```

Ví dụ
```php
// Unserialize an toàn
$data = unserialize($cache, NV_UNSERIALIZE_SAFE);
```

Lệnh yêu cầu AI thực hiện
```
Tìm tất cả các file php cò hàm unserialize, thêm đối số NV_UNSERIALIZE_SAFE nếu chưa có đối số
Loại trừ thư mục src/includes/vendor
nhưng vẫn thực hiện cho thư mục: src/includes/vendor/vinades
```

### CSRF — Kiểm tra token trước khi xử lý POST

- `$csrf_key` đã được tạo mức độ hệ thống

- Tạo token: `$csrf_create = csrf_create($csrf_key);` Nếu `$csrf_create` chỉ dùng 1 lần (gán vào template), KHÔNG cần tạo biến phụ

- Kiểm tra: `if (csrf_check($nv_Request->get_string('checkss', 'post'), $csrf_key))`

- Nếu cần tạo csrf key khác hày dùng `$_csrf_key` để không ghi đè, chẳng may dùng nhiều chỗ

Ví dụ
```php
if ($nv_Request->isset_request('save', 'post')) {
    if (!csrf_check($nv_Request->get_string('checkss', 'post'), $csrf_key)) {
        // Không hợp lệ → báo lỗi hoặc redirect (tùy định dạng trả về)
        nv_jsonOutput([
            'status' => 'error',
            'mess' => $nv_Lang->getGlobal('error_checkss')
        ]);
    }
    // Thực hiện xử lý dữ liệu tiếp theo...
}

$xtpl->assign('CHECKSS', csrf_create($csrf_key));

// Tpl: <input type="hidden" name="checkss" value="{CHECKSS}" />
```
