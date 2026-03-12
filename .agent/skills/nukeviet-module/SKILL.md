---
name: nukeviet-module
description: Tạo module NukeViet 5.x. Load khi tạo module mới, scaffold CRUD, hỏi về cấu trúc file module.
allowed-tools: Read, Write, Glob, Grep, Bash
---


# Hướng Dẫn Module NukeViet 5.x

## Cấu trúc file bắt buộc

```
src/modules/ten-module/
├── version.php           # BẮT BUỘC — phiên bản dạng X.Y.ZZ (vd: 5.0.00)
├── functions.php         # BẮT BUỘC — không xóa dù rỗng; define NV_IS_MOD_*
├── admin.functions.php   # BẮT BUỘC
├── admin.menu.php        # BẮT BUỘC — $submenu (và $allow_func nếu module phức tạp)
├── action_mysql.php      # $sql_create_module + $sql_drop_module
├── global.functions.php  # tùy chọn — hàm dùng chung cả frontend lẫn admin
├── theme.php             # hàm giao diện ngoài site
├── funcs/main.php        # func mặc định ngoài site
├── admin/main.php        # func mặc định admin
└── language/vi.php · en.php · fr.php · data_vi.php · data_en.php · data_fr.php
```

Template `.tpl` → `src/themes/[theme]/modules/[module]/` — **KHÔNG** trong `modules/`

---

## Template code

### version.php
- **Yêu cầu:** Bắt buộc phải có, dùng để khai báo thông tin module.
- **Biến quan trọng:** `$module_version` (array chứa `name`, `modfuncs`, `version`, `author`...)
- **Mẫu tham khảo:** `view_file` -> `.agent/skills/nukeviet-module/examples/version.php`

### functions.php
- **Yêu cầu:** Bắt buộc có (dù rỗng cũng không được xóa). Define hằng số `NV_IS_MOD_TENMODULE`.
- **Mẫu tham khảo:** `view_file` -> `.agent/skills/nukeviet-module/examples/functions.php`

### global.functions.php (Tùy chọn)
- **Mục đích:** Chứa các class hoặc hàm Helper (tiền tố `nv_tenmodule_*`) dùng chung cho cả Frontend và Admin.
- **Guard:** `defined('NV_MAINFILE')`
- **Mẫu tham khảo:** `view_file` -> `.agent/skills/nukeviet-module/examples/global.functions.php`

### admin.functions.php
- **Yêu cầu:** define `NV_IS_FILE_ADMIN`.
- **Module đơn giản:** Khai báo danh sách các chức năng admin được phép tại mảng `$allow_func = ['main', 'content'...];`.
- **Module phức tạp:** Chứa hàm helper riêng cho admin (`nv_tenmodule_show_cat_list()`), mảng `$allow_func` được chuyển sang `admin.menu.php`.
- **Mẫu tham khảo:** `view_file` -> `.agent/skills/nukeviet-module/examples/admin.functions.php`

### admin.menu.php
- **Yêu cầu:** Khai báo cấu trúc menu bên trái trong admin thông qua array `$submenu['func_name'] = ...`.
- **Lưu ý:** Nếu là module phức tạp, phải khai báo luôn array `$allow_func` ở đây.
- **Mẫu tham khảo:** `view_file` -> `.agent/skills/nukeviet-module/examples/admin.menu.php`

### action_mysql.php
- **Yêu cầu:** Khai báo mảng lệnh SQL để tạo cấu trúc cơ sở dữ liệu (`$sql_create_module`) và xoá cơ sở dữ liệu (`$sql_drop_module`) khi setup ứng dụng.
- **Mẫu tham khảo:** `view_file` -> `.agent/skills/nukeviet-module/examples/action_mysql.php`

### funcs/main.php
- **Mục đích:** Xử lý endpoint cụ thể ngoài website.
- **Lưu ý:** Cần bao gồm `includes/header.php` -> dùng `nv_site_theme()` bao nội dung -> include `includes/footer.php`.
- **Mẫu tham khảo:** `view_file` -> `.agent/skills/nukeviet-module/examples/funcs.main.php`

### theme.php
- **Yêu cầu:** Chứa các hàm Render giao diện ngoài website tương ứng với file `/funcs/*.php`.
- **Cơ chế gọi TPL:** Dùng class `XTemplate` khởi tạo bằng đường dẫn template của module (`$module_info['module_theme']`).
- **Mẫu tham khảo:** `view_file` -> `.agent/skills/nukeviet-module/examples/theme.php`

### admin/main.php
- **Mục đích:** Xử lý endpoint cho một trang trong Admin.
- **Lưu ý:** Khác với frontend theme, phần Template Admin sử dụng lớp `\NukeViet\Template\NVSmarty()` (Smarty). Đóng gói và return code bằng `nv_admin_theme()`.
- **Mẫu tham khảo:** `view_file` -> `.agent/skills/nukeviet-module/examples/admin.main.php`

### PSR-4 Classes trong module

NukeViet 5 hỗ trợ PSR-4 autoloading cho tất cả class nằm ở root của module. Bạn có thể tự do đặt tên thư mục như `Shared/`, `Log/`, `Service/`, hoặc thư mục bất kỳ.

```
src/modules/ten-module/
├── Shared/
│   ├── Posts.php     → namespace NukeViet\Module\tenmodule\Shared;
│   └── Helper.php    → namespace NukeViet\Module\tenmodule\Shared;
└── Log/
    └── Writer.php    → namespace NukeViet\Module\tenmodule\Log;
```

```php
<?php
// modules/ten-module/Shared/Posts.php
namespace NukeViet\Module\tenmodule\Shared;

if (!defined('NV_MAINFILE')) {
    exit('Stop!!!');
}

class Posts
{
    const STATUS_DEACTIVE = 0;
    const STATUS_PUBLISH  = 1;
    const STATUS_PENDING  = 2;

    // ...
}
```

Dùng trong code:
```php
use NukeViet\Module\tenmodule\Shared\Posts;

if ($row['status'] === Posts::STATUS_PUBLISH) {
    // ...
}
```

### Lấy input — PHẢI qua $nv_Request

```php
$array = [];

// Số nguyên
$array['id']    = $nv_Request->get_int('id', 'get', 0);
$array['page']  = $nv_Request->get_int('page', 'get', 1);

// Số nguyên không âm (≥0)
$array['num']   = $nv_Request->get_absint('num', 'get', 0);

// Boolean
$array['active'] = $nv_Request->get_bool('active', 'post', false);

// Chuỗi ngắn (text field, tên, tiêu đề)
$array['title'] = $nv_Request->get_title('title', 'post', '');
$array['title'] = nv_substr($array['title'], 0, 255);

// Chuỗi đã lọc bảo mật — HTML bị strip/escape (dùng cho slug, alias, search keyword...)
// Lưu ý: get_string() KHÔNG phải raw — vẫn chạy qua security filter
$array['alias'] = $nv_Request->get_string('alias', 'post', '');

// Nội dung rich editor (WYSIWYG) — chỉ đọc từ POST, không có param $mode
$array['body']  = $nv_Request->get_editor('body', '', NV_ALLOWED_HTML_TAGS);

// Nội dung textarea — chỉ đọc từ POST; $save=true chuyển newline → <br />
$array['desc']  = $nv_Request->get_textarea('desc', '', NV_ALLOWED_HTML_TAGS);
$array['desc_save'] = $nv_Request->get_textarea('desc', '', '', true); // newline → <br />

// Mảng ID (ví dụ checkbox nhiều lựa chọn)
$array['ids']   = $nv_Request->get_array('ids', 'post', []);

// Ghi session (ví dụ: đếm view không trùng lặp)
$nv_Request->set_Session('key_name', NV_CURRENTTIME);
$time_set = $nv_Request->get_int('key_name', 'session');

// Đưa lại vào editor/textarea sau khi lấy từ DB:
$array['body']  = nv_htmlspecialchars(nv_editor_br2nl($row['body']));
$array['desc']  = nv_htmlspecialchars(nv_br2nl($row['description']));
```

---

## Block trong module

Có hai loại block:

| Loại | File | Guard |
|---|---|---|
| Block toàn site (`global.*`) | `modules/ten-module/blocks/global.TEN.php` | `NV_MAINFILE` |
| Block theo context module (`module.*`) | `modules/ten-module/blocks/module.TEN.php` | `NV_MAINFILE` |

`blocks/global.*` — hiển thị mọi nơi trên site.
`blocks/module.*` — chỉ hiển thị khi module đó đang active (dùng trong sidebar của module).

> **Tham khảo code mẫu hoàn chỉnh:** `view_file` -> `.agent/skills/nukeviet-module/examples/block.global.php`

**Quy tắc khai báo hàm:**
- Config block: `nv_block_config_{TEN}` và `nv_block_config_{TEN}_submit`.
- Render nội dung: bất kỳ — thường dùng `nv_{TEN}`.

---

## Checklist tạo module mới

- [ ] 4 file bắt buộc có đủ
- [ ] `functions.php` tồn tại — không xóa dù rỗng
- [ ] Phiên bản dạng `X.Y.ZZ`
- [ ] Template `.tpl` đặt trong `themes/` không phải `modules/`
- [ ] Language có đủ `vi.php` và `admin_vi.php`
- [ ] `action_mysql.php` có cả `$sql_drop_module` và `$sql_create_module`
- [ ] `admin.functions.php` có `define('NV_IS_FILE_ADMIN', true)`
- [ ] `$allow_func` khai báo trong `admin.functions.php` (module đơn giản) hoặc `admin.menu.php` (module phức tạp)
- [ ] Input qua `$nv_Request`, output qua `nv_htmlspecialchars()`
- [ ] theme.php dùng `$module_info['module_theme']` cho đường dẫn tpl ngoài site
- [ ] admin/*.php dùng `$module_file` cho đường dẫn tpl admin

---

## Đa Ngôn Ngữ (Multi-language)

NukeViet 5 hỗ trợ đa ngôn ngữ bằng cách nạp file ngôn ngữ theo từng locale được chọn. Trong NukeViet 5, các chuỗi ngôn ngữ của **Frontend và Admin được gộp thành một file duy nhất** (`vi.php`, `en.php`). Không tồn tại `admin_vi.php` riêng biệt.

### Quy tắc tên file ngôn ngữ

| Tệp | Mục đích | Guard |
|---|---|---|
| `vi.php` | Frontend + Admin gộp chung — **đây là chuẩn NukeViet 5** | `NV_MAINFILE` |
| `en.php` | Tiếng Anh | `NV_MAINFILE` |
| `email_vi.php` | Mẫu email — dùng `$module_emails[]` | `NV_MAINFILE` |
| `data_vi.php` | Ngôn ngữ cho data layer (ít dùng) | `NV_MAINFILE` |

### Template file vi.php / en.php
### Template file vi.php / en.php
> **Tham khảo mẫu khai báo:** `view_file` -> `.agent/skills/nukeviet-module/examples/language.vi.php`

### Sử dụng ngôn ngữ trong Code PHP
```php
global $nv_Lang;

// Load thủ công nếu cần (vd: trong API hay block)
$nv_Lang->loadModule($module_info['module_file'], false, true);

// Truy cập chuỗi
echo $nv_Lang->getModule('hello');

// Chuỗi có tham số
$msg = $nv_Lang->getModule('error_msg', 'Tên lỗi');
```

### Sử dụng trong Smarty (.tpl)
```php
// Assign toàn bộ mảng (khuyến khích)
$xtpl->assign('LANG', $nv_Lang);
// Trong .tpl: {$LANG->getModule('hello')}

// Assign từng chuỗi (không khuyến khích)
$xtpl->assign('HELLO', $nv_Lang->getModule('hello'));
// Trong .tpl: {$HELLO}

// Dùng xong nhớ xóa lang tạm
$nv_Lang->changeLang();
```

### Email Template (email_vi.php)
> **Tham khảo cấu trúc khai báo:** `view_file` -> `.agent/skills/nukeviet-module/examples/email_vi.php`
---

## Hệ thống Hook trong Module

Hệ thống Hook cho phép các module tương tác với nhau mà không cần sửa code core.

### 1. Module nhận sự kiện (Plugin)

Nếu module của bạn muốn lắng nghe sự kiện từ module khác (vd: `users`), hãy tạo file trong thư mục `hooks/`.

- **Vị trí**: `modules/ten-module/hooks/ten_file.php`
- **Nội dung**: Phải dùng `$callback` closure và gọi `nv_add_hook()` ở cuối file.

```php
<?php
if (!defined('NV_MAINFILE')) die('Stop!!!');

$callback = function ($args, $from_data, $receive_data) {
    // Logic xử lý
    return $result;
};

nv_add_hook($module_name, 'tag_name', $priority, $callback, $hook_module, $pid);
```

### 2. Module phát sự kiện (Event)

Nếu module của bạn muốn cho phép các module khác can thiệp vào logic của mình, hãy gọi `nv_apply_hook()`.

```php
// Ví dụ phát sự kiện trước khi lưu dữ liệu
$data = nv_apply_hook($module_name, 'before_save_data', [$data], $data);
```

> Xem chi tiết tại [nukeviet-hook SKILL.md](../nukeviet-hook/SKILL.md).
