# Hướng Dẫn Module NukeViet 5.x

## Cấu trúc file thuộc module

```text
src/
├── modules/
│   └── ten-module/
│       ├── version.php           # BẮT BUỘC — phiên bản dạng X.Y.ZZ (vd: 5.0.00)
│       ├── functions.php         # BẮT BUỘC — không xóa dù rỗng; define NV_IS_MOD_*
│       ├── admin.functions.php   # BẮT BUỘC
│       ├── admin.menu.php        # BẮT BUỘC — $submenu (và $allow_func nếu module phức tạp)
│       ├── action_mysql.php      # $sql_create_module + $sql_drop_module
│       ├── global.functions.php  # tùy chọn — hàm dùng chung cả frontend lẫn admin
│       ├── theme.php             # hàm giao diện ngoài site
│       ├── funcs/main.php        # func mặc định ngoài site
│       ├── admin/main.php        # func mặc định admin
│       ├── Shared/               # PSR-4 classes (namespace NukeViet\Module\{name}\Shared\)
│       └── language/
│           └── vi.php · en.php · fr.php
└── themes/
    ├── default/                  # Giao diện mặc định ngoài site
    │   ├── css/ten-module.css
    │   ├── images/ten-module/
    │   ├── js/ten-module.js
    │   └── modules/ten-module/   # Chứa các file .tpl
    ├── mobile_default/           # Giao diện mobile mặc định ngoài site
    │   ├── css/ten-module.css
    │   ├── images/ten-module/
    │   ├── js/ten-module.js
    │   └── modules/ten-module/   # Chứa các file .tpl
    ├── admin_default/            # Giao diện Admin mặc định
    │   ├── css/ten-module.css
    │   ├── images/ten-module/
    │   ├── js/
    │   │   ├── ten-module.js
    │   │   └── ten-module_*.js
    │   └── modules/ten-module/   # Chứa các file .tpl
    └── admin_future/             # Giao diện Admin mới (Future)
        ├── css/ten-module.css
        ├── images/ten-module/
        ├── js/
        │   ├── ten-module.js
        │   └── ten-module_*.js
        └── modules/ten-module/   # Chứa các file .tpl
```

*(Đối với các giao diện khác, cấu trúc giữ nguyên nhưng thay thư mục theme tương ứng trong `src/themes/`)*

---

## Template code

### version.php
- **Yêu cầu:** Bắt buộc phải có, dùng để khai báo thông tin module.
- **Biến quan trọng:** `$module_version` (array chứa `name`, `modfuncs`, `version`, `author`...)
- **Mẫu tham khảo:** `docs/knowledge/examples/module/version.php`

### functions.php
- **Yêu cầu:** Bắt buộc có (dù rỗng cũng không được xóa). Define hằng số `NV_IS_MOD_TENMODULE`.
- **Mẫu tham khảo:** `docs/knowledge/examples/module/functions.php`

### global.functions.php (Tùy chọn)
- **Mục đích:** Chứa các class hoặc hàm Helper (tiền tố `nv_tenmodule_*`) dùng chung cho cả Frontend và Admin.
- **Guard:** `defined('NV_MAINFILE')`
- **Mẫu tham khảo:** `docs/knowledge/examples/module/global.functions.php`

### admin.functions.php
- **Yêu cầu:** define `NV_IS_FILE_ADMIN`.
- **Module đơn giản:** Khai báo danh sách các chức năng admin được phép tại mảng `$allow_func = ['main', 'content'...];`.
- **Module phức tạp:** Chứa hàm helper riêng cho admin (`nv_tenmodule_show_cat_list()`), mảng `$allow_func` được chuyển sang `admin.menu.php`.
- **Mẫu tham khảo:** `docs/knowledge/examples/module/admin.functions.php`

### admin.menu.php
- **Yêu cầu:** Khai báo cấu trúc menu bên trái trong admin thông qua array `$submenu['func_name'] = ...`.
- **Lưu ý:** Nếu là module phức tạp, phải khai báo luôn array `$allow_func` ở đây.
- **Mẫu tham khảo:** `docs/knowledge/examples/module/admin.menu.php`

### action_mysql.php
- **Yêu cầu:** Khai báo mảng lệnh SQL để tạo cấu trúc cơ sở dữ liệu (`$sql_create_module`) và xoá cơ sở dữ liệu (`$sql_drop_module`) khi setup ứng dụng.
- **Mẫu tham khảo:** `docs/knowledge/examples/module/action_mysql.php`

### funcs/main.php
- **Mục đích:** Xử lý endpoint cụ thể ngoài website.
- **Lưu ý:** Cần bao gồm `includes/header.php` -> dùng `nv_site_theme()` bao nội dung -> include `includes/footer.php`.
- **Mẫu tham khảo:** `docs/knowledge/examples/module/funcs.main.php`

### theme.php
- **Yêu cầu:** Chứa các hàm Render giao diện ngoài website tương ứng với file `/funcs/*.php`.
- **Cơ chế gọi TPL:** Dùng class `XTemplate` khởi tạo bằng đường dẫn template của module (`$module_info['module_theme']`).
- **Mẫu tham khảo:** `docs/knowledge/examples/module/theme.php`

### admin/main.php
- **Mục đích:** Xử lý endpoint cho một trang trong Admin.
- **Lưu ý:** Khác với frontend theme, phần Template Admin sử dụng lớp `\NukeViet\Template\NVSmarty()` (Smarty). Đóng gói và return code bằng `nv_admin_theme()`.
- **Mẫu tham khảo:** `docs/knowledge/examples/module/admin.main.php`

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

### Lấy giá trị biến khi submit form — PHẢI qua $nv_Request

#### Lấy giá trị biến số nguyên

Lấy từ POST
```php
$id = $nv_Request->get_int('id', 'post');
```
Nếu không tồn tại sẽ trả về `0`.

Lấy từ GET
```php
$default = 10;
$id = $nv_Request->get_int('id', 'get', $default);
```

Lấy từ POST hoặc GET
```php
$default = 10;
$id = $nv_Request->get_int('id', 'post,get', $default);
```

Lấy Số nguyên không âm (≥0)
$array['num']   = $nv_Request->get_absint('num', 'get', 0);

#### Lấy giá trị biến số thực

Lấy từ POST
```php
$id = $nv_Request->get_float('id', 'post');
```

Lấy từ GET
```php
$default = 10.5;
$id = $nv_Request->get_float('id', 'get', $default);
```

Lấy từ POST hoặc GET
```php
$default = 10.5;
$id = $nv_Request->get_float('id', 'post,get', $default);
```

### Lấy giá trị biến từ input

Lấy từ POST
```php
$value = $nv_Request->get_title('input_name', 'post', '');
```

Lấy từ POST hoặc GET và lọc HTML
```php
$value = $nv_Request->get_title('input_name', 'post,get', "", 1);
```
Giá trị sẽ được lọc bởi `nv_htmlspecialchars()`.


Lấy từ REQUEST và thay thế ký tự
```php
$default = "default";

$preg_replace = array(
    'pattern' => "/[^a-zA-Z0-9]/",
    'replacement' => "_"
);
$value = $nv_Request->get_title('input_name', 'request', $default, 0, $preg_replace);
```

#### Lấy giá trị biến từ textarea

Đối với editor

Phương thức `get_editor()` chỉ dùng POST.
```php
$content = $nv_Request->get_editor('content', '', NV_ALLOWED_HTML_TAGS);
```
Chỉ các HTML tag nằm trong `NV_ALLOWED_HTML_TAGS` mới được giữ lại.


Không lọc HTML:
```php
$content = $nv_Request->get_editor('content', '');
```

Chuyển xuống dòng khi lưu cần dùng thêm hàm:
```php
$content = nv_editor_nl2br($content);
```

Hoặc:
```php
$content = $nv_Request->get_editor('content', '', NV_ALLOWED_HTML_TAGS, 1);
```

Lấy dữ liệu từ database đưa vào editor
```php
$content = nv_htmlspecialchars(nv_editor_br2nl($row['content']));
```


#### Đối với textarea thường
```php
$content = $nv_Request->get_textarea('content', '', NV_ALLOWED_HTML_TAGS);
```

Không lọc HTML:
```php
$content = $nv_Request->get_textarea('content', '');
```

Lưu vào CSDL hoặc file
```php
$content = $nv_Request->get_textarea('content', '', NV_ALLOWED_HTML_TAGS, 1);
```

Hiển thị lại trong textarea

```php
$content = nv_htmlspecialchars(nv_br2nl($row['content']));
```

#### Lấy dữ liệu editor bằng JavaScript

Nếu dùng **CKEditor**:

```javascript
value = CKEDITOR.instances['DOM-ID-HERE'].getData()
```

#### Mảng ID (ví dụ checkbox nhiều lựa chọn)
```php
$array['ids']   = $nv_Request->get_array('ids', 'post', []);
```

#### Ghi session (ví dụ: đếm view không trùng lặp)
```php
$nv_Request->set_Session('key_name', NV_CURRENTTIME);
$time_set = $nv_Request->get_int('key_name', 'session');
```

### Bảo vệ bằng CSRF Token (Admin & Frontend)

`$csrf_key` đã được tạo mức độ hệ thống

**1. Tạo Token:**
```php
$csrf_create = csrf_create($csrf_key);
```
Nếu `$csrf_create` chỉ dùng 1 lần (gán vào template), KHÔNG cần tạo biến phụ

*(Trong giao diện `.tpl`, gán biến `$csrf_create` thành `{CHECKSS}` và đặt trong input hidden `name="checkss"`).*

**2. So khớp Token:**
```php
if ($nv_Request->isset_request('save', 'post') and csrf_check($nv_Request->get_string('checkss', 'post'), $csrf_key)) {
    // ...
}
```

### Chống Path Traversal (Kiểm tra file hợp lệ)

**KHÔNG** dùng trực tiếp `file_exists()` thao tác nội bộ/tĩnh. Dù bạn truyền một chuỗi tĩnh định sẵn từ hằng số (vd: `NV_ROOTDIR . '/' . $row['image']` không bị Path Traversal) nhưng để đảm bảo độ chuẩn mực cho tương lai, **luôn dùng `nv_is_file()`**:
```php
if (nv_is_file(NV_ROOTDIR . '/uploads/' . $module_name . '/image.jpg')) {
    // Xử lý file
}
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

> **Tham khảo code mẫu hoàn chỉnh:** `docs/knowledge/examples/module/block.global.php`

**Quy tắc khai báo hàm:**
- Config block: `nv_block_config_{TEN}` và `nv_block_config_{TEN}_submit`.
- Render nội dung: bất kỳ — thường dùng `nv_{TEN}`.

---

## Checklist tạo module mới

- [ ] 4 file bắt buộc có đủ
- [ ] `functions.php` tồn tại — không xóa dù rỗng
- [ ] Phiên bản dạng `X.Y.ZZ`
- [ ] Template `.tpl` đặt trong `themes/` không phải `modules/`
- [ ] Language có đủ `vi.php` (NV5 gộp frontend + admin chung)
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
> **Tham khảo mẫu khai báo:** `docs/knowledge/examples/module/language.vi.php`

### Sử dụng ngôn ngữ trong Code PHP
```php
global $nv_Lang;

// Load thủ công nếu cần (vd: trong API hay block)
$nv_Lang->loadModule($module_info['module_file']);

// Truy cập chuỗi
echo $nv_Lang->getModule('hello');

// Chuỗi có tham số
$msg = $nv_Lang->getModule('error_msg', 'Tên lỗi');
```

### Sử dụng trong Smarty (.tpl)
```php
// Assign toàn bộ mảng (khuyên dùng)
$tpl->assign('LANG', $nv_Lang);
// Trong .tpl: {$LANG->getModule('hello')}

// Assign từng chuỗi (không khuyến khích)
$tpl->assign('HELLO', $nv_Lang->getModule('hello'));
// Trong .tpl: {$HELLO}
```

### Email Template (email_vi.php)
> **Tham khảo cấu trúc khai báo:** `docs/knowledge/examples/module/email_vi.php`

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

> Xem chi tiết tại `docs/knowledge/hook.md`.
