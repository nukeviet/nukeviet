# Các khái niệm

Các thuật ngữ dùng xuyên suốt tài liệu kỹ thuật NukeViet 5. Mỗi mục gồm: định nghĩa, cơ chế hoạt động, quy tắc khi viết code.

Một trang ngoài site được dựng như sau:

1. **Module** nhận request, **function** (op) tương ứng dựng HTML phần nội dung
2. **Theme** bọc phần nội dung vào **layout** đã gán cho function đó
3. Mỗi **vị trí block** trong layout được thay bằng HTML của các **block** xếp vào đó
4. **Plugin** chen vào tại các **hook** đặt sẵn trong suốt các bước trên

**Cronjob** chạy định kỳ, tách khỏi luồng dựng trang.

## Module

Module (đọc là mô-đun) là phần mở rộng đảm nhiệm một chức năng riêng của hệ thống: tin tức (`news`), thành viên (`users`), liên hệ (`contact`)... Mỗi module có trang ngoài site, trang quản trị, bảng CSDL và file ngôn ngữ riêng, hoạt động tương đối độc lập nhưng chạy trong khuôn khổ của hệ thống: dùng chung cơ chế định tuyến, phân quyền, giao diện, cấu hình.

NukeViet 5 có sẵn một số module. Module khác cài thêm từ kho mở rộng tại Quản trị → Mở rộng, hoặc gỡ bỏ khỏi hệ thống.

- Code: `modules/<tên>/`. Riêng template giao diện của module đặt trong theme: `themes/<theme>/modules/<tên>/`
- Khai báo: `version.php`, mảng `$module_version`
- Quản lý, bật tắt: Quản trị → Quản lý Modules

### Module gốc và module ảo

Module được cài bằng code đặt vào `modules/` gọi là **module gốc**. Nếu module gốc cho phép, hệ thống nhân bản nó thành nhiều module khác cùng chức năng, gọi là **module ảo**. Mỗi module ảo có tên, URL, bảng dữ liệu và thư mục upload riêng nhưng dùng chung code với module gốc, không cần viết thêm dòng code nào.

::: info Ví dụ
Bản cài mặc định có `about` (Giới thiệu) và `siteterms` (Điều khoản) là hai module ảo của module gốc `page`.
:::

- Điều kiện: `version.php` của module gốc có `'virtual' => 1`
- Tạo: Quản trị → Quản lý Modules → Thêm module ảo, chọn Module gốc và nhập Tên module mới
- Mọi module, gốc hay ảo, đều được ghi vào bảng `<prefix>_<lang>_modules`

Khi xử lý request, router đọc bản ghi của module và gán 4 biến, không suy ra biến này từ biến kia ([src/index.php#L177](https://github.com/nukeviet/nukeviet/blob/nukeviet5.0/src/index.php#L177)):

| Biến | Dùng cho | Module ảo `about` |
|---|---|---|
| `$module_name` | URL, cấu hình `$module_config[$module_name]` | `about` |
| `$module_file` | Đường dẫn code `modules/$module_file/` | `page` |
| `$module_data` | Tên bảng `<prefix>_<lang>_$module_data` | `about` |
| `$module_upload` | Thư mục `uploads/$module_upload/` | `about` |

::: warning
Không viết cứng tên module trong tên bảng hay đường dẫn. `NV_PREFIXLANG . '_page'` chạy đúng với module gốc nhưng làm module ảo đọc nhầm bảng của module gốc.
:::

### Module hệ thống

Module có `'is_sysmod' => 1` trong `version.php`, không xóa được.

::: info Ví dụ
Module hệ thống trong bản cài mặc định: `users`, `page`, `menu`, `banners`, `feeds`, `seek`, `inform`, `myapi`, `two-step-verification`. `news` và `contact` không phải module hệ thống.
:::

## Function (op)

Function là một chức năng của module, tương ứng một trang hoặc một thao tác: module `news` có các function xem danh sách tin, xem chuyên mục, xem bài viết, xuất RSS... Trên URL và trong code, function được gọi là `op`. Không ghi op thì mặc định là `main`.

- Ngoài site: mỗi function là một file `modules/<module>/funcs/<op>.php`
- Quản trị: mỗi trang là một file `modules/<module>/admin/<op>.php`, menu khai báo trong `admin.menu.php`

Function ngoài site phải khai báo trong khóa `modfuncs` của `version.php`. Danh sách này được ghi vào bảng `<prefix>_<lang>_modfuncs` khi cài module. Mỗi function có layout riêng và tập block riêng.

::: info Ví dụ
Khai báo của `news` ([src/modules/news/version.php](https://github.com/nukeviet/nukeviet/blob/nukeviet5.0/src/modules/news/version.php)):

```php
'modfuncs' => 'main,viewcat,topic,groups,author,detail,search,content,tag,rss',
'change_alias' => 'topic,groups,content,rss',
```
:::

### Alias

Op trên URL là alias của function. Function có trong khóa `change_alias` của `version.php` thì quản trị viên đổi được alias, file giữ nguyên. Router tra alias ra tên file, lưu vào `$op_file`.

Dựng link tới function: lấy alias qua `$module_info['alias']['<tên-function>']`, không viết cứng tên function.

### `$array_op`

Op không khớp alias nào thì router tách theo `/` ([src/index.php#L188](https://github.com/nukeviet/nukeviet/blob/nukeviet5.0/src/index.php#L188)):

```php
$array_op = [];
if ($op != 'main' and !isset($module_info['funcs'][$op])) {
    $array_op = explode('/', $op);
    $op = (isset($module_info['funcs'][$array_op[0]])) ? $array_op[0] : 'main';
}
```

| URL | `$op` | `$array_op` |
|---|---|---|
| `/vi/news/` | `main` | `[]` |
| `/vi/news/rss/` | `rss` | `[]` |
| `/vi/news/rss/the-thao/` | `rss` | `['rss', 'the-thao']` |
| `/vi/news/the-thao/bai-viet-123.html` | `main` | `['the-thao', 'bai-viet-123.html']` |

Module tự giải `$array_op` trong `functions.php` và được đổi `$op`, vì router chỉ tra `$op_file` sau khi nạp file này.

::: info Ví dụ
`news` dùng cách này cho URL chuyên mục và bài viết: `functions.php` đọc `$array_op`, đổi `$op` từ `main` thành `viewcat` hoặc `detail`.
:::

## Theme

Theme (còn gọi là giao diện) quyết định cách trình bày website. Hệ thống dùng được nhiều theme, quản trị viên thay đổi theme mà không ảnh hưởng dữ liệu và chức năng.

NukeViet 5 có sẵn hai theme hệ thống:

| Theme | Dùng cho |
|---|---|
| `default` | Ngoài site, tự co giãn theo màn hình máy tính và thiết bị di động |
| `admin_default` | Khu vực quản trị |

Theme khác thiếu thành phần nào thì thành phần đó được lấy từ theme hệ thống tương ứng. Vì vậy không được đổi tên hay sửa hai theme này; chúng cũng bị ghi đè khi nâng cấp. Đổi giao diện bằng theme mới, chỉ chứa những file khác với `default`.

Theme được chọn ở ba mức:

1. Toàn site: theme mặc định
2. Từng module: mỗi module có thể dùng một theme riêng, kể cả theme riêng cho thiết bị di động
3. Từng function: mỗi function dùng một layout riêng trong theme

Cấu trúc:

- Code: `themes/<tên>/`, thông tin và danh sách vị trí block khai báo trong `config.ini`
- Template: file `.tpl` viết bằng Smarty, render qua `NukeViet\Template\NVSmarty`. Theme hệ thống dựng trên Bootstrap 5
- Template của module: `themes/<theme>/modules/<module>/`. Hàm `get_module_tpl_dir()` tìm trong theme đang dùng trước, không có thì tìm trong theme hệ thống ([src/includes/core/filesystem_functions.php](https://github.com/nukeviet/nukeviet/blob/nukeviet5.0/src/includes/core/filesystem_functions.php))

## Layout và vị trí block

Layout là các kiểu bố trí khác nhau của cùng một theme: chỉ nội dung, nội dung kèm một cột bên, nội dung giữa hai cột... Người thiết kế theme quyết định theme có những layout nào.

- File: `themes/<theme>/layout/layout.*.tpl`. Theme `default` có `layout.content.tpl`, `layout.content-esbar.tpl`, `layout.ssbar-content-esbar.tpl`...
- Layout gán theo từng function của từng module, tại Quản trị → Quản lý giao diện → Thiết lập layout. Nên thiết lập ngay sau khi cài theme, trước khi kích hoạt

Vị trí block là chỗ trong layout được chừa để đặt block, viết dạng thẻ `[TÊN]`: `[HEADER]`, `[START_SIDEBAR]`, `[END_SIDEBAR]`...

- Khai báo trong mục `<positions>` của `config.ini`
- Khi xuất trang, mỗi thẻ được thay bằng HTML của các block xếp vào vị trí đó

## Block

Block (khối) là các khối nội dung nhỏ đặt quanh phần nội dung chính: tin mới, menu, thông tin liên hệ, mã QR... Block giúp một trang hiển thị thêm dữ liệu từ nhiều module và có thể bật, tắt, di chuyển giữa các vị trí mà không sửa code.

Block chia hai nhóm theo nơi đặt file:

| Nhóm | Thư mục | Phạm vi |
|---|---|---|
| Block của module | `modules/<module>/blocks/` | Theo tiền tố tên file, xem bảng dưới |
| Block của theme | `themes/<theme>/blocks/` | Mọi vị trí của theme đó |

Tiền tố tên file quyết định block hiện được ở đâu:

| Tên file | Hiển thị ở |
|---|---|
| `global.<tên>.php` | Mọi trang của mọi module |
| `module.<tên>.php` | Chỉ các trang của module sở hữu |

Mỗi block thêm vào site là một bản ghi trong bảng `<prefix>_<lang>_blocks_groups`, gồm theme, vị trí, nhóm được xem, có hiện với bot không, thời gian hiển thị (thường xuyên, khoảng thời gian cụ thể, hàng ngày, hàng tuần...). Cùng một file block có thể thêm nhiều lần với cấu hình khác nhau.

Cơ chế:

- File `.json` cùng tên: tên hiển thị theo ngôn ngữ, cấu hình mặc định, tên hàm dựng form cấu hình (`datafunction`) và hàm lưu cấu hình (`submitfunction`)
- Hệ thống `include` file block rồi đọc biến `$content`
- Template của block module tìm theo theme đang dùng như template module, nên mỗi theme trình bày cùng một block theo cách riêng
- Quản lý: Quản trị → Quản lý giao diện → Quản lý block

::: info Ví dụ
Cuối file [src/modules/page/blocks/global.page_list.php](https://github.com/nukeviet/nukeviet/blob/nukeviet5.0/src/modules/page/blocks/global.page_list.php):

```php
if (defined('NV_SYSTEM')) {
    $content = nv_page_list($block_config);
}
```
:::

## Plugin và hook

Hook là điểm mở rộng mà core hoặc module đặt sẵn trong code, tại đó code bên ngoài được phép can thiệp vào dữ liệu hoặc nhận thông báo khi có sự kiện. Plugin là file PHP đăng ký xử lý tại một hook. Đây là cách để thay đổi hành vi của core và module khác mà không sửa code của chúng.

- Đặt hook: `nv_apply_hook($module, $tag, $args, $default)`. `$module` rỗng là hook hệ thống, khác rỗng là hook của module đó và chỉ chạy khi module đang hoạt động
- Đăng ký: `nv_add_hook()` trong file plugin

::: info Ví dụ
Hook hệ thống trong [src/includes/footer.php#L39](https://github.com/nukeviet/nukeviet/blob/nukeviet5.0/src/includes/footer.php#L39), cho phép sửa HTML trước khi gửi:

```php
[$contents, $headers] = nv_apply_hook('', 'change_site_buffer', [$global_config, [$contents, $headers]], [$contents, $headers]);
```

Plugin xử lý hook này:

```php
$callback = function ($vars, $from_data, $receive_data) {
    [$contents, $headers] = $vars[1];
    // sửa $contents
    return [$contents, $headers];
};
nv_add_hook($module_name, 'change_site_buffer', $priority, $callback, $hook_module, $pid);
```
:::

- Callback nhận 3 tham số: mảng `$args` của hook (kèm `pid`), thông tin module phát, thông tin module nhận
- `$module_name`, `$priority`, `$hook_module`, `$pid` do hệ thống cấp khi nạp file, lấy từ bản ghi trong bảng `<prefix>_plugins`
- Kiểu **lọc**: trả giá trị thay cho mặc định, trả `null` để bỏ qua. Kiểu **sự kiện**: chỉ báo việc vừa xảy ra, không dùng giá trị trả về

| Vị trí file | Đăng ký |
|---|---|
| `includes/plugin/<tên>.php` | Kích hoạt tay tại Quản trị → Cấu hình → Thiết lập Plugin |
| `modules/<module>/hooks/<tên>.php` | Tự đăng ký khi cài module ([src/admin/modules/setup.php#L62](https://github.com/nukeviet/nukeviet/blob/nukeviet5.0/src/admin/modules/setup.php#L62)) |

::: info Ví dụ
Hook sự kiện `user_delete`: module `users` phát khi xóa thành viên ([src/modules/users/functions.php#L412](https://github.com/nukeviet/nukeviet/blob/nukeviet5.0/src/modules/users/functions.php#L412)). Module khác đăng ký plugin tại hook này để xóa dữ liệu của thành viên đó.
:::

::: warning
Plugin được nạp trước khi kết nối CSDL. Callback chỉ dùng được dữ liệu đã có tại thời điểm hook được gọi. Xem [Vòng đời một request](./request-lifecycle.md).
:::

Chi tiết: [Phần 5: Hook](../hooks/index.md). Danh sách: [Danh sách hook](../hooks/list.md).

## Cronjob

Cronjob (tiến trình tự động) là tác vụ hệ thống tự chạy theo lịch, không cần người thao tác: xóa cache, xóa log cũ, sao lưu CSDL, xóa tài khoản theo yêu cầu...

- Code: hàm PHP trong `includes/cronjobs/<tên>.php`
- Quản lý: Quản trị → Cấu hình → Tiến trình tự động (bảng `<prefix>_cronjobs`), đặt thời điểm bắt đầu, chu kỳ lặp, tham số
- Hàm phải trả `true` khi chạy xong, kể cả khi không có việc. Trả `false` thì cronjob **bị tắt tự động** ([src/includes/core/cronjobs.php](https://github.com/nukeviet/nukeviet/blob/nukeviet5.0/src/includes/core/cronjobs.php))

Hai cách kích hoạt (cấu hình `cronjobs_launcher`):

| Giá trị | Cơ chế | Dùng khi |
|---|---|---|
| `system` | Lượt truy cập kích hoạt chạy nền qua `sload.php` | Mặc định; site vắng khách thì chạy trễ |
| `server` | Cron máy chủ gọi URL có tham số `loadcron` chứa khóa bí mật | Site thật, cần đúng giờ |

::: tip
Cronjob chạy trước khi nạp module, không gọi được hàm trong `functions.php` của module. Đặt logic vào class trong `Shared/` (namespace `NukeViet\Module\<module>\Shared`, autoload), file cronjob chỉ gọi class.
:::

## Ngôn ngữ

Site NukeViet có thể chạy nhiều ngôn ngữ, mỗi ngôn ngữ có dữ liệu riêng: module, bài viết, block, cấu hình. Hệ thống tách hai khái niệm:

| Hằng | Quyết định |
|---|---|
| `NV_LANG_DATA` | Dữ liệu đọc từ bảng nào: `<prefix>_vi_...` hay `<prefix>_en_...` |
| `NV_LANG_INTERFACE` | Ngôn ngữ của nhãn, nút, thông báo |

Hai hằng khác nhau khi thành viên chọn ngôn ngữ giao diện riêng trong tài khoản, hoặc khi người quản trị dùng giao diện tiếng Anh để sửa dữ liệu tiếng Việt ([src/includes/language.php](https://github.com/nukeviet/nukeviet/blob/nukeviet5.0/src/includes/language.php)).

- Bảng theo ngôn ngữ: `NV_PREFIXLANG`. Bảng dùng chung: `$db_config['prefix']`
- File ngôn ngữ: `includes/language/<lang>/` (chung), `modules/<module>/language/<lang>.php` (module). File khai báo mảng `$lang_module[...]`
- Đọc trong code: `$nv_Lang->getModule('khoa')`, `$nv_Lang->getGlobal('khoa')`

## Nhóm và quyền

Nhóm thành viên dùng để phân quyền xem: module, block, chuyên mục tin... được gán cho một hoặc nhiều nhóm, chỉ thành viên thuộc nhóm đó mới thấy. Quyền quản trị là hệ riêng, gán cho người quản trị theo cấp.

Nhóm hệ thống có ID cố định ([src/modules/users/action_mysql.php](https://github.com/nukeviet/nukeviet/blob/nukeviet5.0/src/modules/users/action_mysql.php)):

| ID | Nhóm |
|---|---|
| 1 | Super-Admin |
| 2 | General-Admin |
| 3 | Module-Admin |
| 4 | Users: thành viên chính thức |
| 5 | Guest: khách |
| 6 | All: tất cả |
| 7 | New-Users: thành viên mới |

Kiểm tra thuộc nhóm: `nv_user_in_groups('4,10')`.

Quyền quản trị tính theo **cấp**, tách biệt với ID nhóm: 1 quản trị tối cao, 2 điều hành chung, 3 quản trị module được giao.

Hằng xác định danh tính, đặt sẵn sau khi kiểm tra phiên ([src/includes/core/is_admin.php#L58](https://github.com/nukeviet/nukeviet/blob/nukeviet5.0/src/includes/core/is_admin.php#L58)):

| Hằng | Có khi |
|---|---|
| `NV_IS_USER` | Thành viên đã đăng nhập, phiên hợp lệ |
| `NV_IS_1STEP_USER` | Đã đăng nhập nhưng chưa bật xác thực hai bước trong khi site hoặc tài khoản bắt buộc |
| `NV_IS_ADMIN` | Người quản trị đã đăng nhập |
| `NV_IS_SPADMIN` | Quản trị cấp 1 hoặc 2 |
| `NV_IS_GODADMIN` | Quản trị cấp 1 của site chính |
| `NV_IS_MODADMIN` | Có quyền quản trị module đang xem |

::: warning
Kiểm tra đăng nhập bằng `defined('NV_IS_USER')`, không bằng `!empty($user_info)`. Ở trạng thái `NV_IS_1STEP_USER`, `$user_info` đã có dữ liệu ([src/includes/core/is_user.php](https://github.com/nukeviet/nukeviet/blob/nukeviet5.0/src/includes/core/is_user.php)).
:::

## Khác với NukeViet 4

Tài liệu và code mẫu NukeViet 4 không áp dụng được cho các điểm sau:

| NukeViet 4 | NukeViet 5 |
|---|---|
| XTemplate: `new XTemplate(...)`, khối `<!-- BEGIN: ... -->` | Smarty qua `NVSmarty`. Core không còn dùng XTemplate |
| Đọc ngôn ngữ qua `$lang_module['khoa']`, `$lang_global['khoa']` | Đọc qua `$nv_Lang->getModule()`, `$nv_Lang->getGlobal()` |
| Bootstrap 3 | Bootstrap 5 |
| Kèm theme `mobile_default` riêng cho di động, kiểu giao diện mặc định `r,d,m` | Không kèm theme di động, `default` tự co giãn; kiểu giao diện mặc định `r,d` |

