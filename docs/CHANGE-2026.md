# Các thay đổi lớn trong NukeViet 5.0

## 10/10/2026 Xóa giao diện admin_default và default cũ

Sau cập nhật, thư mục `themes/admin_default` và `themes/default` vẫn còn nhưng nội dung đã là `admin_future` và `future` chuyển sang. Giao diện cũ (XTemplate, Bootstrap 3) bị xóa.

Site nào còn module hoặc giao diện viết theo kiểu cũ thì làm theo các bước dưới đây. Ví dụ dùng tên `admin_dauthau` và `dauthau`, mỗi site tự đặt tên riêng.

Trước khi làm, lấy tệp `tools/default-to-other-theme/check-old-theme.php` trong repo NukeViet trên Github chép vào thư mục `tools/default-to-other-theme/` của site rồi chạy ở thư mục gốc của repo để biết site cần làm phần nào (công cụ chỉ đọc, không sửa gì):
```bash
php tools/default-to-other-theme/check-old-theme.php
```
Mã nguồn site không nằm trong thư mục `src` thì thêm `--root=duong-dan-thu-muc-goc`.

### Bước 1: Làm trước khi MR core

Các bước này lấy tệp từ giao diện cũ nên phải làm trước khi MR.

**Giao diện quản trị**

1. Chép thư mục `themes/admin_default` thành `themes/admin_dauthau`
2. Chép 3 tệp sau từ giao diện default cũ sang:
    - `themes/default/css/bootstrap.min.css` sang `themes/admin_dauthau/css/bootstrap.min.css`
    - `themes/default/js/bootstrap.min.js` sang `themes/admin_dauthau/js/bootstrap.min.js`
    - `themes/default/images/users/no_avatar.png` sang `themes/admin_dauthau/images/users/no_avatar.png`

Site không cần giữ giao diện quản trị cũ thì bỏ qua 2 bước trên, chỉ làm bước sau: vào Quản trị > Cấu hình > Thiết lập Plugin, xóa 2 plugin `get_global_admin_theme` và `get_module_admin_theme`. Bước này phải làm trước khi MR, vì MR xóa 2 tệp plugin, nếu plugin còn khai báo thì toàn site báo lỗi và không vào được quản trị để xóa.

**Giao diện ngoài site**

Site đang dùng giao diện riêng (ví dụ `dauthau`) làm như sau:

1. Lấy tệp `tools/default-to-other-theme/update-theme.php` trong repo core, chép vào thư mục gốc của site (cùng chỗ với `index.php`)
2. Đăng nhập quản trị tối cao, mở `https://ten-mien/update-theme.php`, chờ đến khi hiện chữ `Success!`
3. Xóa tệp `update-theme.php` khỏi thư mục gốc

Công cụ này chép những gì giao diện riêng đang mượn của default cũ vào giao diện riêng, để sau khi MR giao diện riêng vẫn chạy độc lập.

Site đang dùng thẳng giao diện `default` thì bắt buộc chuyển sang giao diện riêng để giữ giao diện cũ:

1. Chép thư mục `themes/default` thành `themes/dauthau`
2. Chạy SQL sau, lặp lại cho từng ngôn ngữ đã cài (thay `vi`), và thay `nv5` bằng tiền tố CSDL của site:
    ```sql
    UPDATE nv5_vi_blocks_groups SET theme='dauthau' WHERE theme='default';
    UPDATE nv5_vi_modthemes SET theme='dauthau' WHERE theme='default';
    ```
3. Chạy công cụ `update-theme.php` như hướng dẫn ở trên
4. Vào Quản trị > Giao diện, kích hoạt giao diện `dauthau`

Muốn dùng giao diện default mới thì làm theo mục "Về sau: chuyển sang giao diện default mới" ở cuối.

### Bước 2: MR core

### Bước 3: Làm sau khi MR core

**Cập nhật CSDL**

Chạy SQL sau, thay `nv5` bằng tiền tố CSDL của site:
```sql
-- Giao diện quản trị chung
UPDATE nv5_config SET config_value = 'admin_default' WHERE lang = 'sys' AND module = 'site' AND config_name = 'admin_theme' AND config_value = 'admin_future';

-- Giao diện quản trị riêng của từng tài khoản quản trị
UPDATE nv5_authors SET admin_theme = 'admin_default' WHERE admin_theme = 'admin_future';

-- Cấu hình bảng điều khiển của quản trị: bỏ cấu hình của admin_default cũ, chuyển cấu hình admin_future sang
DELETE FROM nv5_authors_vars WHERE theme = 'admin_default';
UPDATE nv5_authors_vars SET theme = 'admin_default' WHERE theme = 'admin_future';
```

**Giao diện quản trị**

Phần này chỉ dành cho site giữ giao diện quản trị cũ `admin_dauthau`.

1. Sinh lại 2 plugin chọn giao diện quản trị. Ở thư mục gốc của repo, chạy thử để xem danh sách trước:
    ```bash
    php tools/default-to-other-theme/make-admin-theme-plugin.php admin_dauthau --dry-run
    ```
    Danh sách đúng thì chạy lại không có `--dry-run` để ghi tệp:
    ```bash
    php tools/default-to-other-theme/make-admin-theme-plugin.php admin_dauthau
    ```
    Công cụ quét toàn bộ trang quản trị, trang nào còn dùng XTemplate hoặc thiếu tpl ở giao diện quản trị mới thì cho dùng `admin_dauthau`, còn lại dùng giao diện mặc định. Kết quả ghi đè vào `includes/plugin/get_global_admin_theme.php` và `includes/plugin/get_module_admin_theme.php`. Mục "Cần xem lại thủ công" (nếu có) thì tự kiểm tra các tệp được liệt kê.

    Mã nguồn site không nằm trong thư mục `src` thì thêm `--root=duong-dan-thu-muc-goc`.
2. Vào Quản trị > Cấu hình > Thiết lập Plugin, kiểm tra 2 plugin `get_global_admin_theme` và `get_module_admin_theme` vẫn đang bật. Nếu mất thì thêm lại.
3. Mở `themes/admin_dauthau/system/header.tpl` tìm dòng
    ```html
            <link rel="stylesheet" href="{NV_BASE_SITEURL}themes/default/css/bootstrap.min.css">
    ```
    Sửa thành
    ```html
            <link rel="stylesheet" href="{NV_BASE_SITEURL}themes/{NV_ADMIN_THEME}/css/bootstrap.min.css">
    ```
4. Mở `themes/admin_dauthau/system/login.tpl` tìm dòng
    ```html
        <link rel="stylesheet" href="{NV_BASE_SITEURL}themes/default/css/bootstrap.min.css">
    ```
    Sửa thành
    ```html
        <link rel="stylesheet" href="{NV_BASE_SITEURL}themes/{ADMIN_THEME}/css/bootstrap.min.css">
    ```
5. Mở `themes/admin_dauthau/system/footer.tpl` tìm dòng
    ```html
    <script type="text/javascript" src="{NV_BASE_SITEURL}themes/default/js/bootstrap.min.js"></script>
    ```
    Sửa thành
    ```html
    <script type="text/javascript" src="{NV_BASE_SITEURL}themes/{NV_ADMIN_THEME}/js/bootstrap.min.js"></script>
    ```
6. Mở `themes/admin_dauthau/theme.php` tìm dòng
    ```php
                $xtpl->assign('ADMIN_PHOTO', NV_STATIC_URL . 'themes/default/images/users/no_avatar.png');
    ```
    Sửa thành
    ```php
                $xtpl->assign('ADMIN_PHOTO', NV_STATIC_URL . 'themes/' . $admin_info['admin_theme'] . '/images/users/no_avatar.png');
    ```

**Cuối cùng**

1. Khởi động lại PHP-FPM (hoặc Apache) để xóa OPcache. Nếu không, PHP có thể vẫn chạy tệp cũ đã bị thay và báo lỗi XTemplate.
2. Vào Quản trị > Công cụ web > Dọn dẹp hệ thống, xóa cache.
3. Mở thử vài trang ngoài site và trang quản trị của các module trong danh sách để kiểm tra.

### Về sau: chuyển sang giao diện default mới

Làm lúc nào cũng được sau khi MR, site vẫn chạy bằng giao diện riêng trong lúc chuyển.

1. Lấy tệp `tools/default-to-other-theme/check-convert-default.php` trong repo NukeViet trên Github chép vào thư mục `tools/default-to-other-theme/` của site, chạy ở thư mục gốc của repo (công cụ chỉ đọc, không sửa gì):
    ```bash
    php tools/default-to-other-theme/check-convert-default.php
    ```
    Mã nguồn site không nằm trong thư mục `src` thì thêm `--root=duong-dan-thu-muc-goc`.
2. Xử lý từng mục công cụ liệt kê:
    - Code ngoài site còn dùng XTemplate: chuyển sang Smarty, tpl đặt trong `themes/default/modules/ten-module/`
    - Thiếu tpl: tạo tpl Smarty trong `themes/default/modules/ten-module/`
    - Tệp còn sót trong `themes/default/modules/ten-module/` (tệp php hoặc tpl viết kiểu XTemplate): xóa hoặc viết lại theo Smarty
3. Chạy lại công cụ đến khi báo "Có thể chuyển sang giao diện default"
4. Vào Quản trị > Giao diện:
    - Kích hoạt giao diện `default`
    - Thiết lập layout, chọn lại layout cho các function
    - Quản lý block, xếp lại các block vào vị trí mới

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
