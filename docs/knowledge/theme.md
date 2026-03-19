# Hướng Dẫn Theme NukeViet 5.x

## Cấu trúc thư mục

```
themes/ten-theme/
├── config.ini            # BẮT BUỘC — tên theme, layoutdefault, positions, setlayout, setblocks (Dùng ngoài site)
├── config.json           # cấu hình biến LESS/SCSS và js để NPM/Grunt build assets
├── config_default.php    # giá trị CSS mặc định cho trang tùy biến theme admin
├── config.php            # logic xử lý form tùy biến CSS (guard: NV_IS_FILE_THEMES)
├── theme.php             # BẮT BUỘC — hàm PHP của theme (guard: NV_SYSTEM + NV_MAINFILE)
├── default.jpg           # ảnh mô tả theme
├── css/
│   ├── bootstrap.min.css           # responsive mode
│   ├── bootstrap.non-responsive.css# non-responsive mode
│   ├── bootstrap-theme.min.css
│   ├── style.css                   # style chính (dùng cả 2 mode)
│   ├── style.responsive.css        # responsive-only styles
│   ├── style.non-responsive.css    # non-responsive-only styles
│   ├── custom.css            ← viết CSS tùy chỉnh vào đây (load sau cùng)
│   ├── admin.css             ← load thêm khi user là admin
│   └── ten-module.css        ← tự load khi module chạy
├── js/
│   ├── main.js · bootstrap.min.js
│   └── custom.js             ← viết JS tùy chỉnh vào đây
├── fonts/                    # icon font (NukeVietIcons.*)
├── images/
│   ├── icons/
│   ├── no_image.gif
│   └── header.png            # banner mặc định (dùng khi site chưa cấu hình banner)
├── language/
│   ├── vi.php · en.php       # ngôn ngữ ngoài site
│   └──
├── layout/
│   ├── layout.TENL.tpl       # layout file chính
│   ├── header_only.tpl       # phần <html>...<body> — include vào layout qua {FILE}
│   ├── header_extended.tpl   # header mở rộng (logo, menu...) — include vào layout
│   ├── footer_extended.tpl   # footer mở rộng — include vào layout
│   ├── footer_only.tpl       # đóng </body></html> — include vào layout
│   ├── simple.tpl            # layout tối giản — chỉ {MODULE_CONTENT}, không block
│   ├── block.default.tpl     # BẮT BUỘC — không xóa
│   ├── block.border.tpl      # block dạng well (không có title)
│   ├── block.no_title.tpl    # block chỉ có content (không title, không wrapper)
│   ├── block.primary.tpl     # block dạng panel-primary
│   └── block.simple.tpl      # block dạng panel-body với h3 title
├── blocks/
│   ├── global.TEN.php        # logic block của theme
│   ├── global.TEN.tpl        # template cho block (đi kèm .php)
│   └── global.TEN.ini        # cấu hình mặc định block (tùy chọn)
├── system/
│   ├── config.tpl            # template form tùy biến CSS admin
│   ├── mail.tpl              # template email hệ thống
│   ├── admin_toolbar.tpl     # toolbar admin ngoài site
│   ├── alert.tpl             # thông báo hệ thống
│   ├── error_info.tpl        # trang lỗi
│   └── info_die.tpl          # trang lỗi nghiêm trọng
└── modules/ten-module/       # override tpl module — chỉ copy khi thực sự cần sửa
```

---

## Layout Bootstrap 24 cột

NukeViet dùng **24 cột** (không phải 12 cột chuẩn Bootstrap).

| Layout name | Cột |
|---|---|
| `main` | 24 |
| `main-right` | 18-6 |
| `left-main` | 6-18 |
| `left-main-right` | 5-13-6 |
| `main-left-right` | 13-6-5 |
| `left-right-main` | 5-6-13 |

Layout `simple.tpl` là trường hợp đặc biệt: không có block positions, chỉ render `{MODULE_CONTENT}`. Dùng cho module cần giao diện tối giản (ví dụ: error page, modal).

---

## Tạo theme mới từ default

```bash
cp -r themes/default themes/ten-theme-moi
```

**Dọn dẹp sau khi copy:**
- `blocks/` → xóa hết, giữ `index.html`
- `css/` → xóa module css thừa; giữ `admin.css, bootstrap*.css, custom.css, style*.css`
- `fonts/` → giữ nguyên
- `images/` → giữ `icons/, index.html, no_image.gif, header.png`
- `js/` → giữ `bootstrap.min.js, custom.js, main.js`
- `language/` → giữ nguyên (sửa nội dung nếu cần)
- `layout/` → giữ toàn bộ `block.*.tpl`, `header_*.tpl`, `footer_*.tpl`, `simple.tpl`; xóa layout không dùng
- `modules/` → xóa hết (copy lại từng module khi cần sửa)
- `system/` → giữ nguyên

---

## config.ini — cấu hình đầy đủ

> **Tham khảo file config.ini đầy đủ:** `docs/knowledge/examples/theme/config.ini`

> Sau khi sửa `config.ini`: **Admin → Công cụ web → Làm sạch cache**

**Lưu ý:** Giao diện quản trị Admin (ví dụ: `admin_future`) không sử dụng `config.ini` cho layout/block positions như frontend. Admin sử dụng `NVSmarty` và cấu hình qua CSDL (`NV_AUTHORS_GLOBALTABLE_vars`). Tuy nhiên theme-guide này chủ yếu áp dụng cho **Frontend Site Theme**.

**Lưu ý các trường đúng:**
- `<info>` dùng `<name>`, không phải `<n>`
- `<position>` dùng `<name>` và `<tag>`, không phải `<n>`
- `<info>` không có trường `<version>`

---

## Thêm block position mới

**Bước 1** — Khai báo trong `config.ini`:
```xml
<position>
    <name>TEN_KHOI</name>
    <tag>[TEN_KHOI]</tag>
</position>
```
Tag: **in hoa**, chỉ dùng chữ/số/gạch dưới — vd: `[BOTTOM_CONTENT]`, `[BANNER_TOP]`

**Bước 2** — Đặt tag vào file layout `.tpl`:
```html
<div class="container">[BOTTOM_CONTENT]</div>
```

**Bước 3** — Xóa cache → Admin → kéo thả block vào position mới để kiểm tra.

---

## Layout files — cấu trúc split

Layout file được tách thành nhiều phần include lẫn nhau qua cú pháp `{FILE "filename.tpl"}`:

> **Tham khảo cấu trúc file layout chính:** `docs/knowledge/examples/theme/layout.main.tpl`

- `header_only.tpl` — `<!DOCTYPE html>...<head>...</head><body>` — chứa CSS/JS links
- `header_extended.tpl` — logo, search form, menu site...
- `footer_extended.tpl` — footer links, copyright, address...
- `footer_only.tpl` — đóng `</body></html>`
- `simple.tpl` — layout đặc biệt: chỉ include `header_only.tpl` + `{MODULE_CONTENT}` + `footer_only.tpl`

---

## Block templates — các style có sẵn

Khi kéo block vào position, admin chọn template bằng key (tên file bỏ `block.` và `.tpl`):

| Template key | File | Kết quả |
|---|---|---|
| `default` | `block.default.tpl` | **BẮT BUỘC** — panel-default có tiêu đề |
| `primary` | `block.primary.tpl` | panel-primary có tiêu đề (màu chủ đạo) |
| `simple` | `block.simple.tpl` | panel-body + tiêu đề dạng `<h3>` |
| `border` | `block.border.tpl` | well Bootstrap — không có tiêu đề |
| `no_title` | `block.no_title.tpl` | chỉ content, không tiêu đề, không wrapper |

Tất cả đều dùng `{BLOCK_TITLE}` và `{BLOCK_CONTENT}` làm biến nội dung.

---

## XTemplate — cú pháp .tpl

```html
{BIEN_DON}              <!-- biến đơn -->
{MANG.key}              <!-- mảng -->

<!-- BEGIN: main.loop -->
  <li>{ITEM.title}</li>
<!-- END: main.loop -->

<!-- BEGIN: main.co_anh -->
  <img src="{ROW.image}">
<!-- END: main.co_anh -->

{FILE "ten-file.tpl"}   <!-- include file tpl khác (dùng trong layout) -->
```

```php
$xtpl->assign('BIEN', $value);          // biến đơn
$xtpl->assign('ROW', $row);             // mảng → {ROW.field}
foreach ($items as $item) {
    $xtpl->assign('ITEM', $item);
    $xtpl->parse('main.loop');          // lặp
}
if (!empty($row['image'])) {
    $xtpl->parse('main.co_anh');        // điều kiện
}
$xtpl->parse('main');
return $xtpl->text('main');
```

---

## theme.php — cấu trúc file

Guard: `NV_SYSTEM + NV_MAINFILE`

> **Tham khảo code file theme.php chuẩn:** `docs/knowledge/examples/theme/theme.php`

**CSS loading order** trong `nv_site_theme()`:
```
font-awesome.min.css        (system assets)
bootstrap.min.css           (theme css/ — responsive mode 'r')
  hoặc
bootstrap.non-responsive.css (theme css/ — non-responsive mode 'd')
style.css                   (theme css/)
style.responsive.css        (theme css/ — chỉ mode 'r')
  hoặc
style.non-responsive.css    (theme css/ — chỉ mode 'd')
admin.css                   (theme css/ — chỉ khi user là admin)
[module css files]          (qua nv_html_links)
custom.css                  (theme css/ — LUÔN LOAD SAU CÙNG → override được tất cả)
```

---

## Block global của theme

Block của **theme** đặt trong `themes/ten-theme/blocks/global.TEN.php`. Mỗi block gồm 3 file:
- `global.TEN.php` — logic PHP
- `global.TEN.tpl` — template HTML
- `global.TEN.ini` — cấu hình mặc định (tùy chọn)

> **Tham khảo Block Global template đầy đủ:** `docs/knowledge/examples/theme/block.global.php`

**Quy tắc đặt tên hàm — phân biệt theme block vs module block:**

| Loại | Config func | Submit func | Render func |
|---|---|---|---|
| Block của **theme** | `nv_{TEN}_config()` | `nv_{TEN}_submit()` | `nv_{TEN}($block_config)` |
| Block của **module** | `nv_block_config_{TEN}()` | `nv_block_config_{TEN}_submit()` | `nv_{TEN}($block_config)` |

**Phân biệt guard constant:**

| Loại block | Vị trí file | Guard constant |
|---|---|---|
| Block của theme | `themes/ten-theme/blocks/global.*.php` | `NV_MAINFILE` |
| Block của module | `modules/ten-module/blocks/global.*.php` | `NV_MAINFILE` |
| Block của module (context) | `modules/ten-module/blocks/module.*.php` | `NV_MAINFILE` |

> Cả 3 loại đều dùng `NV_MAINFILE` — không có `NV_IS_BLOCK_THEME` trong codebase thực tế.

---

## config.php và config_default.php

`config_default.php` — định nghĩa giá trị CSS mặc định cho giao diện tùy biến admin. Guard: `NV_MAINFILE`.

> **Tham khảo form cài đặt CSS admin theme (`config_default.php`):** `docs/knowledge/examples/theme/config_default.php`

`config.php` — form xử lý tùy biến CSS admin (lưu vào `NV_CONFIG_GLOBALTABLE`). Guard: `NV_IS_FILE_THEMES`. Dùng `system/config.tpl` làm template.

Các CSS token tương ứng khi ghi file `custom_{theme}.css`:
- `[body]` → `body`
- `[a_link]` → `a, a:link, a:active, a:visited`
- `[a_link_hover]` → `a:hover`
- `[content]` → `.wraper`
- `[header]` → `#header`
- `[footer]` → `#footer`
- `[block]` → `.panel, .well, .nv-block-banners`
- `[block_heading]` → `.panel-default > .panel-heading`

---

## Quy tắc tùy biến — ưu tiên theo thứ tự

1. **CSS thuần** vào `custom.css` — nhanh nhất, ít rủi ro nhất
2. **CSS pseudo-elements** (`:before`, `:after`, `:first-child`)
3. **Copy `.tpl`** vào `modules/ten-module/` — chỉ khi CSS không đủ

> Không copy `.tpl` chỉ để đổi màu hay khoảng cách — dùng CSS trước.

---

## Checklist theme mới

- [ ] `block.default.tpl` tồn tại — không xóa
- [ ] `config.ini` có `<layoutdefault>` hợp lệ
- [ ] `config.ini` dùng `<name>` (không phải `<n>`) trong `<info>` và `<position>`
- [ ] `theme.php` có `$theme_config['pagination']` đúng với Bootstrap version đang dùng
- [ ] CSS tùy chỉnh → `custom.css` | JS tùy chỉnh → `custom.js`
- [ ] Xóa layout/template không dùng
- [ ] Xóa cache sau khi thay đổi `config.ini`
- [ ] Test giao diện desktop + mobile

---

## Ngôn Ngữ trong Theme

### Cấu trúc thư mục `language/`

```
themes/ten-theme/language/
├── vi.php   # $lang_global mở rộng riêng cho theme
└── en.php
```

File `vi.php` của theme bổ sung thêm chuỗi vào `$lang_global` (cùng mảng với hệ thống):

```php
<?php
if (!defined('NV_MAINFILE')) {
    exit('Stop!!!');
}

$lang_translator['author']     = 'Tên tác giả';
$lang_translator['createdate'] = 'dd/mm/yyyy';
$lang_translator['copyright']  = '...';
$lang_translator['info']       = '';
$lang_translator['langtype']   = 'lang_global';  // Khác với lang module — ở đây là 'lang_global'

// Các chuỗi riêng của theme
$lang_global['theme_slogan']        = 'Slogan của site';
$lang_global['theme_readmore']      = 'Đọc thêm';
$lang_global['theme_latest_news']   = 'Tin tức mới nhất';
```

> Dùng `$lang_translator['langtype'] = 'lang_global'` — khác với file ngôn ngữ module (`'lang_module'`).

### $lang_global trong theme.php và block của theme

`$lang_global` **luôn có sẵn** trong context theme (tự động nạp bởi Core). Không cần gọi `$nv_Lang->loadModule()`.

```php
// Trong theme.php hoặc block của theme
function nv_site_theme($contents, $full = true)
{
    global $lang_global, $global_config;

    // Dùng trực tiếp
    $footer_text = $lang_global['copyright'];
    // ...
}

// Trong block của theme
function nv_tenblock($block_config)
{
    global $lang_global;

    $xtpl = new XTemplate('global.tenblock.tpl', ...);
    $xtpl->assign('LANG', $lang_global); // {LANG.theme_readmore}
    $xtpl->parse('main');
    return $xtpl->text('main');
}
```

### Khi nào cần file `language/vi.php` của theme?

- Khi theme có **block riêng** dùng chuỗi UI đặc thù
- Khi theme muốn **override** chuỗi hệ thống (ít dùng, chú ý không xung đột)
- Khi copy từ theme `default` → giữ nguyên file `language/`, sửa nội dung nếu cần

> Cấu trúc file tương tự `global.php` hệ thống nhưng chỉ khai báo thêm/override `$lang_global` key.
