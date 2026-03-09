---
name: nukeviet-block
description: Tạo và cấu hình Block NukeViet 5.x. Load khi dev yêu cầu tạo block cho module hoặc theme, cần file config JSON, hoặc hỏi về cách truyền data ra tpl.
allowed-tools: Read, Write, Bash
---

# Hướng Dẫn NukeViet Block (NukeViet 5.x)

Hệ thống Block của NukeViet 5 đóng vai trò mảng ghép giao diện, được quản lý dựa trên **chuẩn cấu trúc đa tệp**(`.php` xử lý logic, `.json` chứa config/đa ngôn ngữ, `.tpl` hoặc `XTemplate` hiển thị). 
Có 2 loại Block chính: **Module Block** và **Theme Block**.

---

## 1. Module Block
Block trực thuộc một module, chỉ được quản lý cài đặt khi module đó được cài đặt. Khi gọi, **Block Module** ưu tiên dùng `XTemplate` lấy file `.tpl` nằm ở thư mục `themes/`.

**Vị trí file:**
- Logic: `modules/[module_name]/blocks/global.[block_name].php`
- Cấu hình: `modules/[module_name]/blocks/global.[block_name].json`
- Giao diện: Tại thư mục theme kích hoạt `themes/[theme_name]/modules/[module_name]/block.[block_name].tpl`

> **Lưu ý tên file:** file php bắt đầu bằng `global.` hoặc `module.` (Ví dụ `global.about.php`), nhưng tpl tương ứng thường đặt tên là `block.about.tpl`.

### 1.1 Nội dung file `.json` mẫu
Cung cấp thông tin hiển thị định danh cho Block trong màn hình cài đặt Admin.
```json
{
    "info": {
        "name": "Tên Block VD",
        "author": "VINADES.,JSC",
        "website": "https://vinades.vn",
        "description": "Mô tả block"
    },
    "i18n": {
        "en": {
            "info": {
                "name": "Display name in EN"
            }
        },
        "vi": {
            "info": {
                "name": "Tên hiển thị tiếng Việt"
            }
        }
    }
}
```

### 1.2 Nội dung file `.php` mẫu
```php
<?php

/**
 * @Project NukeViet
 * @Author VN
 * @Copyright (C) 2025 VN. All rights reserved
 * @License GNU/GPL version 2 or any later version
 */

if (!defined('NV_SYSTEM')) {
    exit('Stop!!!');
}

if (!nv_function_exists('nv_block_about_example')) {
    /**
     * nv_block_about_example()
     *
     * @param array $block_config
     * @return string
     */
    function nv_block_about_example($block_config)
    {
        global $global_config, $db_slave, $module_name;

        // Logic của bạn
        $title = "Tiêu đề mẫu";
        $link = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA;

        // XPATH tìm block.about.tpl trong hệ thống theme 
        $block_theme = get_tpl_dir([$global_config['module_theme'], $global_config['site_theme']], 'default', '/modules/' . $block_config['module'] . '/block.about.tpl');
        
        $xtpl = new XTemplate('block.about.tpl', NV_ROOTDIR . '/themes/' . $block_theme . '/modules/' . $block_config['module']);
        $xtpl->assign('TITLE', $title);
        $xtpl->assign('LINK', $link);

        $xtpl->parse('main');
        return $xtpl->text('main');
    }
}

// Bắt buộc bước gọi hàm cuối cùng này
if (defined('NV_SYSTEM')) {
    $content = nv_block_about_example($block_config);
}
```

---

## 2. Theme Block
Block trực thuộc Theme, sử dụng cho các tính năng hệ thống/layout (như Menu Footer, QRCode, Custom HTML). Khi gọi hiển thị, **Block Theme** dùng `NVSmarty` trỏ thẳng tới file `.tpl` nằm ở thư mục con `smarty/`.

**Vị trí file:**
- Logic: `themes/[theme_name]/blocks/global.[block_name].php`
- Cấu hình: `themes/[theme_name]/blocks/global.[block_name].json`
- Giao diện: `themes/[theme_name]/blocks/smarty/global.[block_name].tpl`

### 2.1 File `.php` kết hợp NVSmarty mẫu
Theme Blocks sử dụng \NukeViet\Template\NVSmarty để parse.

```php
<?php

/**
 * @Project NukeViet
 * @Author VN
 * @Copyright (C) 2025 VN. All rights reserved
 * @License GNU/GPL version 2 or any later version
 */

if (!defined('NV_MAINFILE')) {
    exit('Stop!!!');
}

if (!nv_function_exists('nv_block_theme_example')) {
    /**
     * nv_block_theme_example()
     *
     * @param array $block_config
     * @return string
     */
    function nv_block_theme_example($block_config)
    {
        global $global_config, $page_title;

        // Thêm trường title vào config truyền ra ngoài nếu cần
        $block_config['title'] = "Custom Title";

        // Khởi tạo Smarty template engine cho khối Theme
        $stpl = new \NukeViet\Template\NVSmarty();
        $stpl->setTemplateDir($block_config['real_path'] . '/smarty');
        
        // Gán mảng dữ liệu vào biến SMARTY
        $stpl->assign('DATA', $block_config);

        return $stpl->fetch('global.theme_example.tpl');
    }
}

if (defined('NV_SYSTEM')) {
    $content = nv_block_theme_example($block_config);
}
```

### 2.2 File `smarty/global.theme_example.tpl` mẫu
Sử dụng cú pháp của Smarty `{...}` thay vì XTemplate `{...}`

```html
<!-- Cú pháp gọi mảng được assign từ Smarty -->
<div class="theme-block-wrapper">
    <h3>{$DATA.title}</h3>
    <p>Thuộc theme: {$DATA.theme_name}</p>
</div>
```

---

## Quy tắc Bắt Buộc
- **Security Check**: Tất cả file php của Block phải có `if (!defined('NV_SYSTEM'))` (hoặc NV_MAINFILE) ở trên cùng.
- **Biến trả về**: Biến để Core nhận là `$content`, luôn luôn phải gán `$content = function_block($block_config);` ở cuối file PHP.
- **Block Config**: Biến toàn cục từ hệ thống cấp phát cho block luôn mang tên `$block_config`. Hàm của bạn BẮT BUỘC nhận tham số đầu vào này.

---

## Ngôn Ngữ trong Block

### Các biến ngôn ngữ có sẵn trong Block

| Biến | Nguồn | Phạm vi |
|---|---|---|
| `$lang_global` | `includes/language/{locale}/global.php` | Sẵn có — chuỗi toàn hệ thống |
| `$lang_module` | `modules/{module}/language/{locale}.php` | Chỉ có khi block thuộc module đang active |
| `$lang_block` | File `.json` của block (section `i18n`) | Chuỗi config UI của block cụ thể |

> **Module Block** (`global.TEN.php`, `module.TEN.php`): `$lang_global` luôn có. `$lang_module` có nếu module đang được load trên trang đó.

### Khai báo i18n trong file `.json` của block

```json
{
    "info": {
        "name": "Block Demo"
    },
    "i18n": {
        "vi": {
            "info": { "name": "Block Demo (VI)" },
            "config": {
                "numrow": "Số dòng hiển thị",
                "show_title": "Hiển thị tiêu đề"
            }
        },
        "en": {
            "info": { "name": "Block Demo (EN)" },
            "config": {
                "numrow": "Number of rows",
                "show_title": "Show title"
            }
        }
    }
}
```

`$lang_block` sẽ chứa section `config` của ngôn ngữ hiện tại — dùng trong hàm config block.

### Dùng ngôn ngữ trong hàm config và render

```php
// Hàm config block của Module — nhận $lang_block từ tham số
function nv_block_config_tenblock($module, $data_block, $lang_block)
{
    // $lang_block['numrow'] → "Số dòng hiển thị" (từ JSON i18n)
    $html  = '<div class="form-group">';
    $html .= '<label>' . $lang_block['numrow'] . '</label>';
    $html .= '<input type="text" name="config_numrow" value="' . $data_block['numrow'] . '">';
    $html .= '</div>';
    return $html;
}

// Hàm render block — dùng $lang_global và $lang_module
function nv_tenblock($block_config)
{
    global $lang_global, $lang_module, $nv_Lang;

    // Nếu cần ngôn ngữ của module khác — load thủ công
    // $nv_Lang->loadModule('ten-module-khac');

    $xtpl = new XTemplate('block.tenblock.tpl', ...);
    $xtpl->assign('LANG', $lang_module);    // {LANG.key}
    $xtpl->assign('GLANG', $lang_global);   // {GLANG.save}
    $xtpl->parse('main');
    return $xtpl->text('main');
}
```

### Theme Block — ngôn ngữ với NVSmarty

```php
function nv_block_theme_example($block_config)
{
    global $lang_global;

    $stpl = new \NukeViet\Template\NVSmarty();
    $stpl->setTemplateDir($block_config['real_path'] . '/smarty');
    $stpl->assign('GLANG', $lang_global);  // {$GLANG.save}
    $stpl->assign('DATA', $block_config);

    return $stpl->fetch('global.theme_example.tpl');
}
```
