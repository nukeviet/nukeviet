# Vòng đời một request

Vòng đời request là chuỗi bước hệ thống thực hiện từ lúc nhận request tới lúc trả kết quả về trình duyệt. Nắm được thứ tự này để biết code của module chạy lúc nào, biến và hằng nào đã có sẵn, hook nào dùng được dữ liệu gì.

Mỗi bước có link tới dòng code tương ứng trên nhánh `nukeviet5.0`.

## Tổng quan

<div style="overflow-x:auto;margin:16px 0">
<svg viewBox="0 0 680 570" width="100%" role="img" aria-label="Sơ đồ vòng đời request: khởi động chung, rồi tách thành luồng ngoài site 7 bước và luồng quản trị" style="max-width:680px;min-width:560px;display:block;margin:0 auto;font-family:var(--vp-font-family-base);font-size:13px">
<defs><marker id="lc-arrow" viewBox="0 0 10 10" refX="9" refY="5" markerWidth="7" markerHeight="7" orient="auto-start-reverse"><path d="M0,0 L10,5 L0,10 z" style="fill:var(--vp-c-text-2)"/></marker></defs>
<rect x="60" y="10" width="300" height="36" rx="18" style="fill:var(--vp-c-brand-soft);stroke:var(--vp-c-brand-1)"/>
<text x="210" y="33" text-anchor="middle" style="fill:var(--vp-c-text-1);font-weight:600">Request từ trình duyệt</text>
<line x1="210" y1="46" x2="210" y2="68" style="stroke:var(--vp-c-text-2)" marker-end="url(#lc-arrow)"/>
<rect x="20" y="70" width="380" height="48" rx="6" style="fill:var(--vp-c-bg-soft);stroke:var(--vp-c-divider)"/>
<text x="36" y="90" style="fill:var(--vp-c-text-1);font-weight:600">1. Khởi động chung</text>
<text x="36" y="108" style="fill:var(--vp-c-text-2);font-family:var(--vp-font-family-mono);font-size:12px">includes/mainfile.php</text>
<line x1="210" y1="118" x2="210" y2="132" style="stroke:var(--vp-c-text-2)" marker-end="url(#lc-arrow)"/>
<text x="218" y="129" style="fill:var(--vp-c-text-2);font-size:11px">index.php</text>
<rect x="20" y="134" width="380" height="48" rx="6" style="fill:var(--vp-c-bg-soft);stroke:var(--vp-c-divider)"/>
<text x="36" y="154" style="fill:var(--vp-c-text-1);font-weight:600">2. Xác định thành viên</text>
<text x="36" y="172" style="fill:var(--vp-c-text-2);font-family:var(--vp-font-family-mono);font-size:12px">core/is_user.php → NV_IS_USER</text>
<line x1="210" y1="182" x2="210" y2="196" style="stroke:var(--vp-c-text-2)" marker-end="url(#lc-arrow)"/>
<rect x="20" y="198" width="380" height="48" rx="6" style="fill:var(--vp-c-bg-soft);stroke:var(--vp-c-divider)"/>
<text x="36" y="218" style="fill:var(--vp-c-text-1);font-weight:600">3. Xác định module, kiểm tra quyền xem</text>
<text x="36" y="236" style="fill:var(--vp-c-text-2);font-family:var(--vp-font-family-mono);font-size:12px">$site_mods → $module_info, $module_file...</text>
<line x1="210" y1="246" x2="210" y2="260" style="stroke:var(--vp-c-text-2)" marker-end="url(#lc-arrow)"/>
<rect x="20" y="262" width="380" height="48" rx="6" style="fill:var(--vp-c-bg-soft);stroke:var(--vp-c-divider)"/>
<text x="36" y="282" style="fill:var(--vp-c-text-1);font-weight:600">4. Xác định function</text>
<text x="36" y="300" style="fill:var(--vp-c-text-2);font-family:var(--vp-font-family-mono);font-size:12px">$op, $array_op</text>
<line x1="210" y1="310" x2="210" y2="324" style="stroke:var(--vp-c-text-2)" marker-end="url(#lc-arrow)"/>
<rect x="20" y="326" width="380" height="48" rx="6" style="fill:var(--vp-c-bg-soft);stroke:var(--vp-c-divider)"/>
<text x="36" y="346" style="fill:var(--vp-c-text-1);font-weight:600">5. Chọn theme, layout</text>
<text x="36" y="364" style="fill:var(--vp-c-text-2);font-family:var(--vp-font-family-mono);font-size:12px">themes/&lt;theme&gt;/theme.php</text>
<line x1="210" y1="374" x2="210" y2="388" style="stroke:var(--vp-c-text-2)" marker-end="url(#lc-arrow)"/>
<rect x="20" y="390" width="380" height="48" rx="6" style="fill:var(--vp-c-bg-soft);stroke:var(--vp-c-divider)"/>
<text x="36" y="410" style="fill:var(--vp-c-text-1);font-weight:600">6. Nạp file của module</text>
<text x="36" y="428" style="fill:var(--vp-c-text-2);font-family:var(--vp-font-family-mono);font-size:12px">functions.php → theme.php → funcs/&lt;op_file&gt;.php</text>
<line x1="210" y1="438" x2="210" y2="452" style="stroke:var(--vp-c-text-2)" marker-end="url(#lc-arrow)"/>
<rect x="20" y="454" width="380" height="48" rx="6" style="fill:var(--vp-c-bg-soft);stroke:var(--vp-c-divider)"/>
<text x="36" y="474" style="fill:var(--vp-c-text-1);font-weight:600">7. Dựng và gửi trang</text>
<text x="36" y="492" style="fill:var(--vp-c-text-2);font-family:var(--vp-font-family-mono);font-size:12px">header.php → nv_site_theme() → footer.php</text>
<line x1="210" y1="502" x2="210" y2="516" style="stroke:var(--vp-c-text-2)" marker-end="url(#lc-arrow)"/>
<rect x="60" y="518" width="300" height="36" rx="18" style="fill:var(--vp-c-brand-soft);stroke:var(--vp-c-brand-1)"/>
<text x="210" y="541" text-anchor="middle" style="fill:var(--vp-c-text-1);font-weight:600">HTML về trình duyệt</text>
<line x1="400" y1="94" x2="438" y2="94" style="stroke:var(--vp-c-text-2);stroke-dasharray:4 3"/>
<rect x="440" y="70" width="220" height="48" rx="6" style="fill:none;stroke:var(--vp-c-text-3);stroke-dasharray:4 3"/>
<text x="452" y="90" style="fill:var(--vp-c-text-2);font-size:12px">Hook: check_server, sector1,</text>
<text x="452" y="108" style="fill:var(--vp-c-text-2);font-size:12px">modify_global_config, sector2</text>
<path d="M400,108 H420 V206 H438" style="fill:none;stroke:var(--vp-c-text-2)" marker-end="url(#lc-arrow)"/>
<rect x="440" y="140" width="220" height="150" rx="6" style="fill:var(--vp-c-bg-soft);stroke:var(--vp-c-divider)"/>
<text x="452" y="162" style="fill:var(--vp-c-text-1);font-weight:600">Quản trị: admin/index.php</text>
<text x="452" y="184" style="fill:var(--vp-c-text-2);font-size:12px">Kiểm tra đăng nhập quản trị</text>
<text x="452" y="204" style="fill:var(--vp-c-text-2);font-size:12px">Xác định module hoặc khu vực</text>
<text x="452" y="224" style="fill:var(--vp-c-text-2);font-family:var(--vp-font-family-mono);font-size:11px">admin.menu.php</text>
<text x="452" y="240" style="fill:var(--vp-c-text-2);font-family:var(--vp-font-family-mono);font-size:11px">admin.functions.php</text>
<text x="452" y="260" style="fill:var(--vp-c-text-2);font-size:12px">Kiểm tra op trong $allow_func</text>
<text x="452" y="280" style="fill:var(--vp-c-text-2);font-family:var(--vp-font-family-mono);font-size:11px">admin/&lt;op&gt;.php → nv_admin_theme()</text>
<line x1="400" y1="414" x2="438" y2="414" style="stroke:var(--vp-c-text-2)" marker-end="url(#lc-arrow)"/>
<rect x="440" y="390" width="220" height="48" rx="6" style="fill:none;stroke:var(--vp-c-text-3);stroke-dasharray:4 3"/>
<text x="452" y="410" style="fill:var(--vp-c-text-2);font-size:12px">Request trả JSON:</text>
<text x="452" y="428" style="fill:var(--vp-c-text-2);font-family:var(--vp-font-family-mono);font-size:12px">nv_jsonOutput() → kết thúc</text>
<line x1="400" y1="478" x2="438" y2="478" style="stroke:var(--vp-c-text-2);stroke-dasharray:4 3"/>
<rect x="440" y="454" width="220" height="48" rx="6" style="fill:none;stroke:var(--vp-c-text-3);stroke-dasharray:4 3"/>
<text x="452" y="474" style="fill:var(--vp-c-text-2);font-size:12px">Hook: change_site_buffer</text>
<text x="452" y="492" style="fill:var(--vp-c-text-2);font-size:12px">sửa HTML, header trước khi gửi</text>
</svg>
</div>

Khung nét đứt là hook và nhánh rẽ. Request ngoài site đi qua các bước:

| Bước | Việc | Nơi xử lý |
|---|---|---|
| 1 | Khởi động chung | `includes/mainfile.php` |
| 2 | Xác định thành viên | `includes/core/is_user.php` |
| 3 | Xác định module, kiểm tra quyền xem | `index.php` |
| 4 | Xác định function: `$op`, `$array_op` | `index.php` |
| 5 | Chọn theme, layout | `index.php` |
| 6 | Nạp file của module | `functions.php`, `theme.php`, `funcs/<op>.php` của module |
| 7 | Dựng và gửi trang | File function, `nv_site_theme()`, `includes/footer.php` |

Khu vực quản trị dùng chung bước 1, các bước sau đi theo luồng riêng, xem [Khu vực quản trị](#khu-vuc-quan-tri).

## 1. Khởi động chung

Mọi file điểm vào (`index.php`, `admin/index.php`, `api.php`, `sload.php`, `mload.php`...) đặt hằng đánh dấu loại request rồi nạp `includes/mainfile.php` ([src/index.php#L12](https://github.com/nukeviet/nukeviet/blob/nukeviet5.0/src/index.php#L12)). File này dựng môi trường dùng chung theo thứ tự cố định:

| Thứ tự | Việc | Có sau bước này | Dòng code |
|---|---|---|---|
| 1 | Nạp hằng, cấu hình sinh sẵn, Composer autoload | Hằng hệ thống, `$global_config` từ file | [L30](https://github.com/nukeviet/nukeviet/blob/nukeviet5.0/src/includes/mainfile.php#L30) |
| 2 | Nạp `config.php`. Chưa có thì chuyển sang trình cài đặt | `$db_config` | [L57](https://github.com/nukeviet/nukeviet/blob/nukeviet5.0/src/includes/mainfile.php#L57) |
| 3 | Nạp các file hàm lõi | Hàm dùng chung | [L139](https://github.com/nukeviet/nukeviet/blob/nukeviet5.0/src/includes/mainfile.php#L139) |
| 4 | Phân tích URL thân thiện vào `$_GET`, tạo đối tượng request | `$nv_Request` | [L151](https://github.com/nukeviet/nukeviet/blob/nukeviet5.0/src/includes/mainfile.php#L151) |
| 5 | Xác định ngôn ngữ | `NV_LANG_DATA`, `NV_LANG_INTERFACE`, `$nv_Lang` | [L194](https://github.com/nukeviet/nukeviet/blob/nukeviet5.0/src/includes/mainfile.php#L194) |
| 6 | Nạp plugin đã đăng ký, gọi hook `check_server` | Plugin | [L218](https://github.com/nukeviet/nukeviet/blob/nukeviet5.0/src/includes/mainfile.php#L218) |
| 7 | Cache, nhận diện thiết bị, captcha, chống flood, hook `sector1` | `$nv_Cache`, `$client_info` | [L257](https://github.com/nukeviet/nukeviet/blob/nukeviet5.0/src/includes/mainfile.php#L257) |
| 8 | Kết nối CSDL, đọc cấu hình | `$db`, `$global_config` đầy đủ, `$module_config` | [L329](https://github.com/nukeviet/nukeviet/blob/nukeviet5.0/src/includes/mainfile.php#L329) |
| 9 | Chạy cronjob nếu đến hạn | | [L458](https://github.com/nukeviet/nukeviet/blob/nukeviet5.0/src/includes/mainfile.php#L458) |
| 10 | Kiểm tra phiên quản trị, đóng site nếu đang bảo trì hoặc nâng cấp | `$admin_info`, `NV_IS_ADMIN`... | [L501](https://github.com/nukeviet/nukeviet/blob/nukeviet5.0/src/includes/mainfile.php#L501) |
| 11 | Hook `modify_global_config`, đọc danh sách module, hook `sector2` | `$sys_mods` | [L536](https://github.com/nukeviet/nukeviet/blob/nukeviet5.0/src/includes/mainfile.php#L536) |

::: warning
Plugin được nạp ở thứ tự 6, trước khi có CSDL. Callback chỉ dùng được những gì đã có tại thời điểm hook được gọi:

| Hook | Đã có | Chưa có |
|---|---|---|
| `check_server` | Request, ngôn ngữ | Cache, CSDL |
| `sector1` | Cache, thông tin thiết bị | CSDL |
| `modify_global_config` | CSDL, cấu hình, phiên quản trị | `$sys_mods` |
| `sector2` | `$sys_mods` | Module đang truy cập |
:::

## 2. Xác định thành viên

Hệ thống kiểm tra cookie đăng nhập với phiên lưu trong CSDL. Chỉ khi hợp lệ mới có `$user_info` và hằng `NV_IS_USER` ([src/index.php#L41](https://github.com/nukeviet/nukeviet/blob/nukeviet5.0/src/index.php#L41)).

Người quản trị đã đăng nhập cũng được coi là thành viên ngoài site, `$user_info` lấy từ `$admin_info` ([src/includes/core/is_user.php](https://github.com/nukeviet/nukeviet/blob/nukeviet5.0/src/includes/core/is_user.php)).

## 3. Xác định module và kiểm tra quyền xem

1. Lấy tên module từ tham số `nv`. Không có thì dùng module trang chủ trong cấu hình ([L86](https://github.com/nukeviet/nukeviet/blob/nukeviet5.0/src/index.php#L86))
2. Dựng `$site_mods`: các module người đang xem được phép truy cập, theo nhóm trong cấu hình quyền xem của từng module ([L138](https://github.com/nukeviet/nukeviet/blob/nukeviet5.0/src/index.php#L138))
3. Module không có trong `$site_mods` ([L141](https://github.com/nukeviet/nukeviet/blob/nukeviet5.0/src/index.php#L141)):
    - Chỉ dành cho thành viên (nhóm 4) mà người xem là khách: chuyển tới trang đăng nhập
    - Chỉ dành cho quản trị (nhóm 1, 2) mà người xem không phải quản trị: báo lỗi
    - Còn lại: lỗi 404
4. Gán biến của module: `$module_info`, `$module_file`, `$module_data`, `$module_upload` ([L177](https://github.com/nukeviet/nukeviet/blob/nukeviet5.0/src/index.php#L177))

## 4. Xác định function

Lấy op từ tham số `op`, mặc định `main`. Op không khớp alias nào của module thì tách theo `/` vào `$array_op` ([L188](https://github.com/nukeviet/nukeviet/blob/nukeviet5.0/src/index.php#L188)). Cơ chế chi tiết: [Các khái niệm → `$array_op`](./concepts.md#array-op).

Người có quyền quản trị module đang xem có thêm hằng `NV_IS_MODADMIN` ([L209](https://github.com/nukeviet/nukeviet/blob/nukeviet5.0/src/index.php#L209)).

## 5. Chọn theme và layout

1. Nạp file ngôn ngữ của module ([L232](https://github.com/nukeviet/nukeviet/blob/nukeviet5.0/src/index.php#L232))
2. Chọn theme theo thứ tự: theme người xem tự chọn (nếu được phép), theme riêng của module, theme của site. Thiết bị di động dùng theme di động nếu có cấu hình ([src/includes/core/user_functions.php#L1082](https://github.com/nukeviet/nukeviet/blob/nukeviet5.0/src/includes/core/user_functions.php#L1082))
3. Đọc layout đã gán cho từng function của module
4. Tạo `$csrf_key` dùng cho token chống CSRF của module
5. Đọc vị trí block, nạp `themes/<theme>/theme.php` và ngôn ngữ của theme ([L279](https://github.com/nukeviet/nukeviet/blob/nukeviet5.0/src/index.php#L279))

## 6. Nạp file của module

Thứ tự cố định ([L295](https://github.com/nukeviet/nukeviet/blob/nukeviet5.0/src/index.php#L295)):

1. `modules/<module_file>/functions.php`
2. Tra `$op_file` từ `$op`. Bước này chạy sau `functions.php`, nên module đổi được `$op` ở bước 1
3. `theme.php`: ưu tiên `themes/<theme>/modules/<module>/theme.php`, không có thì dùng `modules/<module_file>/theme.php`
4. `modules/<module_file>/funcs/<op_file>.php`, hoặc `mobile/<op_file>.php` nếu đang dùng theme di động và file này tồn tại

File function chạy ở phạm vi toàn cục nên dùng trực tiếp được `$db`, `$nv_Request`, `$module_info`, `$op`... Trong hàm tự viết thì phải khai báo `global`.

## 7. Dựng và gửi trang

File function tự gọi ba bước cuối:

1. `includes/header.php`: bắt đầu bộ đệm đầu ra
2. `nv_site_theme($contents)`: bọc `$contents`, phần HTML của module, vào layout của function; thay các thẻ vị trí bằng HTML của block; thêm thẻ meta, CSS, JS
3. `includes/footer.php`: viết lại link sang URL thân thiện, gọi hook `change_site_buffer` ([src/includes/footer.php#L39](https://github.com/nukeviet/nukeviet/blob/nukeviet5.0/src/includes/footer.php#L39)), gửi header và nội dung, kết thúc request

::: info Ví dụ
Cuối file [src/modules/page/funcs/main.php#L240](https://github.com/nukeviet/nukeviet/blob/nukeviet5.0/src/modules/page/funcs/main.php#L240):

```php
include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
```
:::

Request trả JSON không qua ba bước này: file function gọi `nv_jsonOutput($data)` rồi kết thúc.

## Khu vực quản trị

Request vào `admin/index.php` dùng chung bước khởi động, sau đó đi theo luồng riêng ([src/admin/index.php](https://github.com/nukeviet/nukeviet/blob/nukeviet5.0/src/admin/index.php)):

1. Chưa đăng nhập quản trị: hiện form đăng nhập rồi dừng ([L19](https://github.com/nukeviet/nukeviet/blob/nukeviet5.0/src/admin/index.php#L19))
2. Đọc danh sách khu vực quản trị hệ thống được phép theo cấp quản trị, và danh sách module người quản trị được giao
3. Lấy tên module hoặc khu vực từ tham số `nv`, mặc định là module chính của người quản trị. Lấy op từ tham số `op`, mặc định `main`
4. Xác định file cần nạp ([L74](https://github.com/nukeviet/nukeviet/blob/nukeviet5.0/src/admin/index.php#L74)):
    - Module: `modules/<module_file>/admin.functions.php`, `admin.menu.php` và `admin/<op>.php`
    - Khu vực hệ thống: `admin/<khu-vực>/functions.php`, `admin.menu.php` và `admin/<khu-vực>/<op>.php`
5. Cả hai file đều tồn tại thì đặt `NV_IS_MODADMIN` ([L100](https://github.com/nukeviet/nukeviet/blob/nukeviet5.0/src/admin/index.php#L100))
6. Nạp theme quản trị, `admin.menu.php`, `admin.functions.php` ([L128](https://github.com/nukeviet/nukeviet/blob/nukeviet5.0/src/admin/index.php#L128))
7. Op không có trong `$allow_func` do `admin.menu.php` khai báo thì báo lỗi ([L173](https://github.com/nukeviet/nukeviet/blob/nukeviet5.0/src/admin/index.php#L173))
8. Nạp file của op. File này kết thúc bằng `header.php`, `nv_admin_theme($contents)`, `footer.php`

::: tip `NV_IS_FILE_ADMIN`
Hằng này không do hệ thống đặt. Mỗi module tự định nghĩa trong `admin.functions.php`, và mọi file trong `admin/` của module kiểm tra hằng này ở đầu file để chặn truy cập trực tiếp.
:::

::: info Ví dụ
Module `page` định nghĩa hằng ở [src/modules/page/admin.functions.php#L26](https://github.com/nukeviet/nukeviet/blob/nukeviet5.0/src/modules/page/admin.functions.php#L26), file [src/modules/page/admin/main.php#L12](https://github.com/nukeviet/nukeviet/blob/nukeviet5.0/src/modules/page/admin/main.php#L12) kiểm tra:

```php
if (!defined('NV_IS_FILE_ADMIN')) {
    exit('Stop!!!');
}
```
:::

