# Cấu trúc file và thư mục

Mã nguồn NukeViet 5 nằm trong thư mục `src/` của repo. Khi cài lên máy chủ, nội dung `src/` là thư mục gốc của website. Mọi đường dẫn trong tài liệu tính từ thư mục gốc này.

```text
src/
├── index.php                 Điểm vào ngoài site
├── api.php                   Điểm vào API
├── sload.php                 Tác vụ nền: cronjob, email, captcha
├── mload.php                 AJAX tới function của module
├── robots.php, error.php
├── config.php                Kết nối CSDL, sinh ra khi cài
├── admin/
│   ├── index.php             Điểm vào quản trị
│   └── <khu-vực>/            settings, modules, themes, authors...
├── includes/
│   ├── mainfile.php          Khởi động hệ thống
│   ├── constants.php
│   ├── functions.php
│   ├── header.php, footer.php
│   ├── core/                 Hàm theo khu vực, xác thực, cronjob
│   ├── language/<lang>/
│   ├── plugin/               Plugin hệ thống
│   ├── cronjobs/
│   ├── api/, uapi/
│   └── vendor/
│       └── vinades/nukeviet/ Class lõi NukeViet\...
├── modules/
│   └── <module>/
│       ├── version.php
│       ├── functions.php
│       ├── admin.functions.php
│       ├── admin.menu.php
│       ├── action_mysql.php
│       ├── funcs/, admin/
│       ├── blocks/, language/
│       ├── Shared/, hooks/
│       └── ...
├── themes/
│   ├── default/              Theme ngoài site
│   │   ├── config.ini
│   │   ├── theme.php
│   │   ├── layout/, blocks/, system/
│   │   ├── modules/<module>/ Template của module
│   │   └── css/, js/, images/
│   └── admin_default/        Theme quản trị
├── assets/                   JS, CSS, thư viện dùng chung
├── data/                     config/, cache/, logs/, tmp/...
├── uploads/<module>/
└── install/
```

Các phần dưới mô tả từng thư mục.

## Thư mục gốc

Thư mục gốc gồm các file điểm vào, nơi request từ trình duyệt bắt đầu, và các thư mục chứa code, giao diện, dữ liệu.

### File điểm vào

Mỗi file điểm vào đặt một hằng đánh dấu loại request rồi nạp `includes/mainfile.php` để khởi động hệ thống.

| File | Hằng | Xử lý |
|---|---|---|
| `index.php` | `NV_SYSTEM` | Trang ngoài site |
| `admin/index.php` | `NV_ADMIN` | Khu vực quản trị |
| `api.php` | `NV_SYSTEM` | API cho ứng dụng bên ngoài (Api) và cho thành viên (Uapi) |
| `sload.php` | `NV_SYS_LOAD` | Tác vụ nền của hệ thống: chạy cronjob, gửi email bất đồng bộ, ảnh captcha, kiểm tra thông báo |
| `mload.php` | `NV_MOD_LOAD` | Request AJAX tới function của module, không dựng giao diện. URL phải có `mhash` hợp lệ, tạo bằng `mload_url_generate()` |
| `robots.php` | `NV_SYSTEM` | Sinh nội dung `robots.txt` |
| `error.php` | `NV_SYSTEM` | Trang báo lỗi HTTP |

### Thư mục

| Thư mục, file | Nội dung |
|---|---|
| `includes/` | Lõi hệ thống: khởi động, hàm dùng chung, class lõi, thư viện bên thứ ba |
| `admin/` | Các khu vực quản trị hệ thống |
| `modules/` | Các module |
| `themes/` | Các theme ngoài site và quản trị |
| `assets/` | JS, CSS, font, ảnh và thư viện dùng chung, không phụ thuộc theme |
| `data/` | Dữ liệu hệ thống sinh ra khi chạy: cấu hình, cache, log |
| `uploads/` | File tải lên, mỗi module một thư mục con |
| `install/` | Trình cài đặt và dữ liệu nâng cấp |
| `config.php` | Thông tin kết nối CSDL, sinh ra khi cài đặt |

Khi chưa có `config.php`, hệ thống chuyển sang trình cài đặt trong `install/` ([src/includes/mainfile.php#L57](https://github.com/nukeviet/nukeviet/blob/nukeviet5.0/src/includes/mainfile.php#L57)).

## `includes/` - lõi hệ thống

Chứa toàn bộ code dùng chung của hệ thống. Module và theme gọi hàm, class trong thư mục này, không sửa nó.

| Đường dẫn | Nội dung |
|---|---|
| `mainfile.php` | Khởi động hệ thống, mọi điểm vào đều nạp file này |
| `constants.php` | Các hằng cố định: tên thư mục, tên biến trên URL, theme hệ thống |
| `functions.php` | Hàm dùng chung toàn hệ thống |
| `core/` | Hàm và xử lý theo khu vực: `admin_functions.php`, `user_functions.php`, `theme_functions.php`, `filesystem_functions.php`, xác thực `is_user.php`, `is_admin.php`, chạy cronjob `cronjobs.php`... |
| `header.php`, `footer.php` | Mở đầu và kết thúc việc xuất trang. `footer.php` gọi hook cuối, gửi header và nội dung ra trình duyệt |
| `language/<lang>/` | File ngôn ngữ chung và của các khu vực quản trị hệ thống |
| `plugin/` | Plugin hệ thống |
| `cronjobs/` | File chứa hàm của các cronjob |
| `api/`, `uapi/` | Lệnh API của hệ thống |
| `vendor/vinades/nukeviet/` | Class lõi, namespace `NukeViet\`: `Core`, `Cache`, `Http`, `Template`, `Files`, `Seo`, `Api`, `Uapi`... |
| `vendor/` | Thư viện bên thứ ba, quản lý bằng Composer qua `includes/composer.json` |

Autoload của Composer khai báo sẵn namespace cho cả module ([src/includes/composer.json](https://github.com/nukeviet/nukeviet/blob/nukeviet5.0/src/includes/composer.json)):

| Namespace | Thư mục |
|---|---|
| `NukeViet\` | `includes/vendor/vinades/nukeviet/` |
| `NukeViet\Api\` | `includes/api/` |
| `NukeViet\Uapi\` | `includes/uapi/` |
| `NukeViet\Module\` | `modules/` |

::: info Ví dụ
Class `NukeViet\Template\NVSmarty` nằm ở `includes/vendor/vinades/nukeviet/Template/NVSmarty.php`. Class `NukeViet\Module\news\Shared\Posts` nằm ở `modules/news/Shared/Posts.php`.
:::

Tham chiếu hàm: [Phần 7: Tham chiếu](../reference/index.md).

## `admin/` - khu vực quản trị hệ thống

Mỗi thư mục con là một khu vực quản trị của hệ thống. Đây không phải module: không cài, gỡ, nhân bản được, và không có trang ngoài site.

| Thư mục | Tên trên menu quản trị |
|---|---|
| `authors` | Quản trị |
| `database` | CSDL |
| `emailtemplates` | Mẫu email |
| `extensions` | Mở rộng |
| `language` | Ngôn ngữ, bản địa hóa |
| `modules` | Quản lý Modules |
| `seotools` | Công cụ SEO |
| `settings` | Cấu hình |
| `siteinfo` | Thông tin |
| `themes` | Quản lý giao diện |
| `upload` | Quản lý File |
| `webtools` | Công cụ web |

Mỗi khu vực có `admin.menu.php`, `functions.php` và mỗi trang một file `<op>.php`, tương tự phần quản trị của module ([src/admin/index.php#L94](https://github.com/nukeviet/nukeviet/blob/nukeviet5.0/src/admin/index.php#L94)).

## `modules/` - các module

Mỗi thư mục con là code của một module gốc. Module ảo không có thư mục riêng.

Module có sẵn trong NukeViet 5: `banners`, `comment`, `contact`, `feeds`, `inform`, `menu`, `myapi`, `news`, `page`, `seek`, `statistics`, `two-step-verification`, `users`, `voting`.

### Cấu trúc một module

File bắt buộc:

| File | Nội dung |
|---|---|
| `version.php` | Thông tin module: tên, phiên bản, danh sách function, có cho tạo module ảo không |
| `functions.php` | Nạp khi chạy ngoài site. Định nghĩa hằng `NV_IS_MOD_<TÊN>`, hàm dùng chung, xử lý `$array_op`. Không được xóa, kể cả khi rỗng |
| `admin.functions.php` | Nạp khi chạy trong quản trị. Định nghĩa hằng `NV_IS_FILE_ADMIN` |
| `admin.menu.php` | Menu quản trị và danh sách trang quản trị được phép (`$allow_func`) |

File và thư mục thường dùng:

| File, thư mục | Nội dung |
|---|---|
| `action_mysql.php` | SQL tạo bảng khi cài (`$sql_create_module`) và xóa bảng khi gỡ (`$sql_drop_module`) |
| `funcs/` | Mỗi function ngoài site một file `<op>.php` |
| `admin/` | Mỗi trang quản trị một file `<op>.php` |
| `theme.php` | Hàm dựng HTML ngoài site |
| `global.functions.php` | Hàm dùng chung ngoài site và quản trị. Không tự nạp: module tự `require` trong `functions.php` và `admin.functions.php` |
| `language/` | `vi.php`, `en.php`... dùng chung cho ngoài site và quản trị |
| `blocks/` | Block của module: `global.*.php`, `module.*.php`, kèm `.json` cùng tên |
| `Shared/` | Class PHP, namespace `NukeViet\Module\<module>\Shared`, tự autoload |

File tích hợp, chỉ thêm khi module cần tính năng tương ứng:

| File, thư mục | Nội dung |
|---|---|
| `hooks/` | Plugin của module, tự đăng ký khi cài module |
| `Api/`, `Uapi/` | Lệnh API cho ứng dụng ngoài và cho thành viên |
| `widgets/` | Widget trên trang Thông tin của quản trị |
| `siteinfo.php` | Số liệu thống kê trên trang Thông tin của quản trị |
| `notification.php` | Hiển thị thông báo quản trị do module tạo |
| `comment.php` | Xử lý sau khi có bình luận mới cho nội dung của module |
| `search.php` | Tham gia tìm kiếm toàn site của module `seek` |
| `rssdata.php` | Cung cấp nguồn tin cho module `feeds` |
| `menu.php` | Cung cấp mục để chọn khi tạo menu ở module `menu` |
| `mobile/` | Bản thay thế file trong `funcs/` khi dùng theme di động |

::: warning
`search.php`, `rssdata.php`, `menu.php`, `notification.php`, `siteinfo.php` không chạy độc lập. Module khác hoặc khu vực quản trị `include` chúng và truyền biến vào. Biến có sẵn khác nhau theo từng file, xem [Phần 4: Lập trình](../programming/index.md) trước khi viết.
:::

::: info Ví dụ
Module `page` chỉ có các file bắt buộc, `action_mysql.php`, `funcs/`, `admin/`, `theme.php`, `global.functions.php`, `language/`, `blocks/`, `Shared/`, `search.php`, `menu.php`. Module `news` dùng gần như mọi file trong các bảng trên.
:::

## `themes/` - giao diện

Mỗi thư mục con là một theme. NukeViet 5 có sẵn `default` (ngoài site) và `admin_default` (quản trị).

| File, thư mục | Nội dung |
|---|---|
| `config.ini` | Thông tin theme, layout mặc định, danh sách vị trí block |
| `config_default.php`, `config.php` | Giá trị mặc định và xử lý phần tùy biến giao diện trong quản trị |
| `theme.php` | Hàm `nv_site_theme()` bọc HTML của module vào layout, `nv_block_theme()` bọc block, `nv_error_theme()` trang lỗi |
| `layout/` | `layout.*.tpl`, phần đầu và cuối trang, khung bao block `block.*.tpl` |
| `blocks/` | Block của theme |
| `modules/<module>/` | Template `.tpl` của từng module |
| `system/` | Template dùng chung: thông báo, trang lỗi, email, thanh công cụ quản trị |
| `css/`, `js/`, `images/`, `webfonts/` | Tài nguyên của theme |
| `language/` | Chuỗi ngôn ngữ riêng của theme |

Theme `admin_default` có cấu trúc tương tự, dùng `nv_admin_theme()` thay cho `nv_site_theme()`.

::: info Ví dụ
CSS của module trong theme `default` tách theo kiểu giao diện: `news.d.css` cho giao diện máy tính cố định (`d`), `news.r.css` cho giao diện co giãn (`r`), kèm bản `.rtl.css` cho ngôn ngữ viết từ phải sang trái.
:::

## `data/` - dữ liệu sinh ra khi chạy

Hệ thống phải ghi được vào thư mục này. Không đưa nội dung thư mục này vào quản lý mã nguồn.

| Đường dẫn | Nội dung |
|---|---|
| `data/config/` | Cấu hình sinh từ CSDL, ví dụ `config_global.php`. Không sửa tay, hệ thống ghi lại khi lưu cấu hình |
| `data/cache/` | Cache. Xóa được bất cứ lúc nào |
| `data/logs/` | Nhật ký lỗi, nhật ký hệ thống |
| `data/tmp/` | File tạm |
| `data/ip/`, `data/ip6/` | Dữ liệu tra quốc gia theo IP (GeoLite2) |
| `data/certs/` | Chứng chỉ gốc dùng khi gọi HTTPS ra ngoài |

## Đặt code của dự án ở đâu

Dự án xây trên NukeViet chỉ thêm file mới, không sửa file có sẵn của core, để còn nâng cấp được NukeViet về sau.

| Cần làm | Đặt ở |
|---|---|
| Chức năng mới | Module mới trong `modules/<module-dự-án>/` |
| Thêm một bản nữa của chức năng có sẵn | Module ảo, không cần code |
| Đổi hành vi của core hoặc module khác | Plugin tại hook có sẵn, trong `modules/<module-dự-án>/hooks/` |
| Đổi giao diện | Theme mới, chỉ chứa file khác với `default` |
| Tác vụ định kỳ | File mới trong `includes/cronjobs/`, gọi class trong `Shared/` của module |

