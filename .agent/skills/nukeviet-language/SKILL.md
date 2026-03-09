---
name: nukeviet-language
description: Hệ thống đa ngôn ngữ NukeViet 5.x. Load khi làm việc với file ngôn ngữ, dịch thuật, tích hợp i18n, hoặc hỏi về $lang_module/$lang_global/$nv_Lang.
allowed-tools: Read, Write, Glob, Grep
---

# Hệ Thống Đa Ngôn Ngữ NukeViet 5.x

## 1. Hai tầng ngôn ngữ

NukeViet tách biệt rõ ràng 2 tầng ngôn ngữ:

| Biến | Nguồn | Phạm vi |
|---|---|---|
| `$lang_global` | `src/includes/language/{locale}/global.php` | Toàn hệ thống (nút, thông báo, quốc gia...) |
| `$lang_module` | `modules/{module}/language/{locale}.php` | Riêng module đó |

> `$lang_translator` là metadata dịch thuật, không dùng trong code logic.

---

## 2. Hai hằng số ngôn ngữ

| Hằng | Ý nghĩa | Ví dụ |
|---|---|---|
| `NV_LANG_DATA` | Ngôn ngữ **dữ liệu** — xác định prefix bảng DB, file language module | `vi`, `en` |
| `NV_LANG_INTERFACE` | Ngôn ngữ **giao diện** — xác định file `includes/language/{locale}/global.php` | `vi`, `en` |

> Trong đa ngôn ngữ, admin có thể chọn **NV_LANG_INTERFACE** khác **NV_LANG_DATA** (dữ liệu tiếng Anh nhưng giao diện tiếng Việt — hoặc ngược lại).

Các hằng tương ứng thường dùng:

```php
NV_PREFIXLANG   // = db_prefix + '_' + NV_LANG_DATA  → vd: nv5_vi
NV_LANG_DATA    // = 'vi' | 'en' | ...
NV_LANG_INTERFACE // = 'vi' | 'en' | ...
NV_LANG_VARIABLE  // = tên query param ngôn ngữ để chuyển giữa các ngôn ngữ (vd: 'lang')
```

---

## 3. Cấu trúc thư mục ngôn ngữ

### Ngôn ngữ hệ thống (Core)
```
src/includes/language/
├── vi/
│   ├── global.php          # $lang_global — chuỗi toàn hệ thống
│   ├── admin_global.php    # $lang_global thêm (admin)
│   ├── admin_modules.php   # $lang_global cho trang quản lý module
│   ├── admin_themes.php    # $lang_global cho trang quản lý theme
│   └── functions.php       # hàm ngôn ngữ tiện ích
├── en/
└── fr/
```

### Ngôn ngữ module
```
modules/ten-module/language/
├── vi.php           # $lang_module (Frontend + Admin gộp chung)
├── en.php
├── email_vi.php     # $module_emails[] — mẫu email
└── data_vi.php      # dữ liệu ngôn ngữ cho data layer (ít dùng)
```

> **NukeViet 5:** Frontend và Admin dùng chung **một file `vi.php`** — không có `admin_vi.php` riêng.

### Ngôn ngữ theme
```
themes/ten-theme/language/
├── vi.php           # $lang_global thêm riêng cho theme
└── en.php
```

---

## 4. Template file ngôn ngữ module (`vi.php`)

```php
<?php

if (!defined('NV_MAINFILE')) {
    exit('Stop!!!');
}

// ── Metadata bắt buộc ──────────────────────────────────────────────────────
$lang_translator['author']     = 'Tên tác giả <email>';
$lang_translator['createdate'] = 'dd/mm/yyyy, HH:MM';
$lang_translator['copyright']  = '@Copyright (C) 2025 ... All rights reserved';
$lang_translator['info']       = '';
$lang_translator['langtype']   = 'lang_module'; // giá trị cố định

// ── Chuỗi giao diện ───────────────────────────────────────────────────────
$lang_module['hello']       = 'Xin chào';
$lang_module['error_msg']   = 'Lỗi: %s';            // dùng với sprintf()
$lang_module['item_count']  = 'Tổng cộng %d mục';   // dùng với sprintf()

// ── Menu admin ─────────────────────────────────────────────────────────────
$lang_module['menu_content'] = 'Quản lý nội dung';
$lang_module['menu_config']  = 'Cấu hình';
```

> `$lang_translator['langtype']` của file module **luôn** là `'lang_module'`.
> File `global.php` hệ thống dùng `'lang_global'`.

---

## 5. Nạp ngôn ngữ — cơ chế tự động & thủ công

### Tự động (Core xử lý)
Core NukeViet tự nạp `$lang_module` khi module được gọi qua URL bình thường (`index.php`).
Không cần gọi thêm gì trong `funcs/main.php` hay `admin/main.php`.

### Thủ công (khi cần trong context đặc biệt)

```php
global $nv_Lang;

// Nạp ngôn ngữ của một module bất kỳ
$nv_Lang->loadModule('ten-module');  // → kết quả vào $lang_module

// Sau đó dùng như bình thường
echo $lang_module['hello'];
```

**Khi nào cần load thủ công?**
- Trong **Block** → khi block của module A cần nạp ngôn ngữ module B
- Trong **API endpoint** (`Api/` hoặc `Uapi/`) → nếu API system không gắn với module
- Trong **global.functions.php** dùng ở nhiều context khác nhau

> **Lưu ý `api.php`:** Khi request API có `module` tham số, Core đã tự gọi `$nv_Lang->loadModule()` — không cần load lại.

---

## 6. Sử dụng chuỗi ngôn ngữ trong PHP

```php
// Truy cập đơn giản
echo $lang_module['menu_content'];

// Chuỗi có tham số (sprintf)
$msg = sprintf($lang_module['error_msg'], 'Tên lỗi');
$msg = sprintf($lang_module['item_count'], $total);

// Chuỗi hệ thống ($lang_global)
echo $lang_global['save'];      // 'Lưu thay đổi'
echo $lang_global['cancel'];    // 'Hủy bỏ'
echo $lang_global['edit'];      // 'Sửa'
echo $lang_global['delete'];    // 'Xóa'
```

---

## 7. Truyền ngôn ngữ vào template

### XTemplate (frontend module block)
```php
// Assign toàn bộ mảng — truy cập trong .tpl bằng {LANG.key}
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('GLANG', $lang_global);

// Trong .tpl:
// {LANG.hello}
// {GLANG.save}
```

### NVSmarty (admin / theme block)
```php
$tpl = new \NukeViet\Template\NVSmarty();
$tpl->assign('LANG', $lang_module);
$tpl->assign('GLANG', $lang_global);

// Trong .tpl Smarty:
// {$LANG.hello}
// {$GLANG.save}
```

---

## 8. Email Template (`email_vi.php`)

```php
<?php

if (!defined('NV_MAINFILE')) {
    exit('Stop!!!');
}

use NukeViet\Module\TenModule\Shared\Emails;
use NukeViet\Template\Email\Emf;

$module_emails[Emails::WELCOME] = [
    'pids' => Emf::P_ALL,               // loại người nhận
    't'    => 'Mô tả loại email này',
    's'    => 'Tiêu đề: {$ten_bien}',   // cú pháp Smarty
    'c'    => 'Nội dung HTML: {$site_name} chào mừng {$username}'
];
```

Hằng `Emails::WELCOME` khai báo trong class `Shared/Emails.php` của module:
```php
namespace NukeViet\Module\TenModule\Shared;

class Emails
{
    const WELCOME       = 'welcome';
    const RESET_PASS    = 'reset_password';
    const NOTIFY_ADMIN  = 'notify_admin';
}
```

---

## 9. Ngôn ngữ URL — NV_LANG_VARIABLE

Để tạo link có ngôn ngữ chuẩn:
```php
// Link nội bộ có ngôn ngữ
$link = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&mid=' . $module_id;

// Chuyển sang ngôn ngữ khác
$lang_switch_url = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=en';
```

---

## 10. Block Language (`$lang_block`)

Khi block được gọi, ngoài `$lang_module` và `$lang_global`, một số block nhận thêm `$lang_block` (mảng cấu hình UI của block đó, được định nghĩa trong `.json` của block):

```json
{
    "i18n": {
        "vi": { "config": { "numrow": "Số dòng hiển thị" } },
        "en": { "config": { "numrow": "Number of rows" } }
    }
}
```

Trong hàm config block:
```php
function nv_block_config_tenblock($module, $data_block, $lang_block)
{
    // $lang_block chứa chuỗi UI của form config block (từ JSON i18n)
    $html = '<label>' . $lang_block['numrow'] . '</label>';
    //...
}
```

---

## 11. Checklist khi tạo file ngôn ngữ

- [ ] Guard `if (!defined('NV_MAINFILE'))` ở đầu file
- [ ] `$lang_translator['langtype'] = 'lang_module'`
- [ ] Metadata đủ 4 field: `author`, `createdate`, `copyright`, `info`
- [ ] Key tên theo snake_case: `error_title`, `menu_config`
- [ ] Không hardcode HTML phức tạp trong chuỗi ngôn ngữ (chỉ `<br />`, `<strong>` là OK)
- [ ] Chuỗi có biến dùng `%s`, `%d`, `%1$s` (không dùng `{var}` — chỉ template Smarty dùng)
- [ ] Tạo cả `vi.php` và `en.php` tối thiểu

---

## 12. Các chuỗi $lang_global hay dùng

| Key | Giá trị (vi) | Dùng khi |
|---|---|---|
| `$lang_global['save']` | Lưu thay đổi | Nút submit form |
| `$lang_global['cancel']` | Hủy bỏ | Nút hủy |
| `$lang_global['edit']` | Sửa | Nút sửa |
| `$lang_global['delete']` | Xóa | Nút xóa |
| `$lang_global['add']` | Thêm | Nút thêm mới |
| `$lang_global['yes']` | Có | Confirm dialog |
| `$lang_global['no']` | Không | Confirm dialog |
| `$lang_global['ok']` | OK | Thông báo |
| `$lang_global['confirm']` | Xác nhận | Nút confirm |
| `$lang_global['required']` | Chú ý... (*) | Ghi chú form |
| `$lang_global['status']` | Trạng thái | Header cột |
| `$lang_global['actions']` | Thao tác | Header cột |
| `$lang_global['search']` | Tìm kiếm | Label tìm kiếm |
| `$lang_global['save_success']` | Các thay đổi đã được ghi nhận | Toast thành công |
