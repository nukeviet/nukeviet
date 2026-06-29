# Các thay đổi lớn trong NukeViet 5.0

## Tháng 6 năm 2026
ALTER TABLE `nv5_users` CHANGE `birthday` `birthday` BIGINT NOT NULL DEFAULT '0';

## Tháng 5 năm 2026

### Refactor gọi nv_local_api
nv_local_api giờ trả về array  (đã json_decode sẵn). Nên cần loại bỏ đoạn json_decode sau khi gọi nv_local_api

Ví dụ
```php
// Trước khi refactor
$result = json_decode(nv_local_api('ClearCache', null, 'vuthao27'), true);

// Sau khi refactor
$result = nv_local_api('ClearCache', null, 'vuthao27');
```

## Tháng 4 năm 2026

- Block của module có thể đặt ở giao diện ví dụ themes/ten-theme/modules/news/global.block_category.(php|json|ini). Điều kiện là tệp global.block_category.php phải tồn tại ở modules/news/blocks/. Việc này phục vụ giai đoạn phát triển, không nên làm cho giao diện production của bạn.
- Để định dạng ngày tháng trong js dùng hàm `nv_format_date`.
- Xóa bỏ hàm js `nv_DigitalClock`
- Để xác định tpl của block dùng hàm `get_block_tpl_dir`, tương tự như `get_module_tpl_dir` thường dùng trong theme.php
- Khi muốn gọi js, css của 1 module A đó khi đang đứng ở module B chỉ cần dùng hàm `addition_module_assets` thay vì phải viết tệp js, css vào trong tpl qua thẻ script hay là link
- Trước đây 1 form có captcha phải if/ else nhiều lần để parse ra tpl các attrs thì bây giờ chỉ cần dùng `nv_captcha_form_attrs`
- Để phát hiện trình duyệt lỗi thời, không còn chạy được website một cách bình thường thì dùng hàm `nv_outdated_browser`

Xóa mt_srand() thừa:
- trước random_int() (dùng CSPRNG, không liên quan mt_srand)
- trước array_rand() (PHP tự seed tốt hơn từ 7.1+

## Tháng 3 năm 2026

### db-refactor
- Bỏ ->sqlreset khỏi codebase
- Bỏ ->insert_id khỏi codebase
- Bỏ ->affected_rows_count
- Bỏ $db_slave
- Chuyển $nv_Request->get_title('checkss' -> $nv_Request->get_string('checkss'
- Chuyển ->bindParam ->bindValue
- Tối ưu biến tạm khi dùng ->fetch(3)
- Tối ưu code theo Skill db-refactor

### Refactor Request::get_title (Không bắt buộc)
bỏ tham số thứ 4 (specialchars) và chuyển sang tham số thứ 4 (maxlength)

dùng tools\refactor_get_title.php để thực hiện

### Thống nhất dùng try catch, json_encode, unserialize
Chạy tool tools\try_catch_audit.php để quét tất cả các file và sửa lại, sau đó nhờ AI sửa dựa trên file report
```
Dựa vào danh sách cần sửa tools\try_catch_audit_report.md bạn hãy mở cửa sổ ra sửa, không dùng cách viết file thay thế do đã làm nhưng ko được

## Hàm unserialize, thêm đối số NV_UNSERIALIZE_SAFE nếu chưa có đối số

## Hàm json_encode, thêm hoặc thay đối số $flags (json_encode(mixed $value, int $flags = 0, int $depth = 512)) bằng:
- NV_JSON_ENCODE_SCRIPT, nếu JSON encode nhúng trong <script> tag HTML
- Còn lại dùng biến NV_JSON_ENCODE

## Sửa lại try catch thống nhất dùng
try {
 // code ...
} catch (Throwable $e) {
    trigger_error($e);
}

Chú ý nếu Throwable
- Có logic khác dữ nguyên
- Nếu trigger_error('....', 256) hoặc trigger_error('....', E_USER_ERROR) thì dùng throw new \NukeViet\Core\HttpException('error checksess', 403); Số 403 thay tùy ngữ cảnh
- Các loại khác trigger_error thống nhất dùng trigger_error($e);
- Các chỗ ->setMessage(print_r($e, true) ) thì sửa lại thành ->setMessage($e->getMessage())
- Chú ý Nếu thừa use Exception; use PDOException; bỏ đi
- Chú ý formatcode trong đoạn catch
```

Sau đó kiểm tra lại từng đoạn có thể AI xác định sai

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
