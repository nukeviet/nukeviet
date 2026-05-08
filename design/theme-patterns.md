# Patterns & Traps khi build theme NukeViet 5

> **Knowledge base GENERIC** áp dụng cho mọi theme NV5.
> Đọc 1 lần để hiểu mindset trước khi bắt đầu build, **KHÔNG paste vào Claude Code**.
> Workflow spec để paste: [prompt-claude-code-handoff.md](prompt-claude-code-handoff.md).
> Plan cụ thể từng theme: `design/<theme>/Request.md` (vd [Request.md](Request.md) cho <theme-name>).

---

## Mục lục

- [§1. setblocks — format + serialize đúng](#1-setblocks--format--serialize-đúng)
  - [§1.1 Type của field config](#11-type-của-field-config)
  - [§1.2 ID hardcode workflow seed-first](#12-id-hardcode-workflow-seed-first)
- [§2. setlayout — override layout cho từng module:func](#2-setlayout--override-layout-cho-từng-modulefunc)
- [§3. Smarty vs XTemplate TRAP — block.*.tpl](#3-smarty-vs-xtemplate-trap--blocktpl)
- [§4. Bootstrap-first cho UI tương tác](#4-bootstrap-first-cho-ui-tương-tác)
  - [§4.1 Search modal markup chuẩn](#41-search-modal-markup-chuẩn)
  - [§4.2 Convert mockup HTML → Smarty (case study chrome.js)](#42-convert-mockup-html--smarty-case-study-chromejs)
- [§5. URL ảnh — convention NV5](#5-url-ảnh--convention-nv5)
  - [§5.1 Module news (homeimgfile)](#51-module-news-homeimgfile)
  - [§5.2 Module banners (file_name)](#52-module-banners-file_name)
- [§6. SEO — schema.org + OpenGraph](#6-seo--schemaorg--opengraph)
- [§7. Tích hợp module seeder cho theme](#7-tích-hợp-module-seeder-cho-theme)
- [§8. Verify checklist cuối phase](#8-verify-checklist-cuối-phase)
- [§9. Sub-agents code chi tiết từng block](#9-sub-agents-code-chi-tiết-từng-block)

---

## §1. setblocks — format + serialize đúng

`<setblocks>` cho phép NV5 auto-gắn block vào position khi **cài / kích hoạt** theme. Không cần admin kéo thả thủ công.

> ⚠️ **BẮT BUỘC:** File `config.ini` của bạn phải liệt kê **100% các block** xuất hiện trong thiết kế (menu, logo, footer, tin tức, banner, v.v.). Tham khảo `themes/default/config.ini` để thấy họ set hơn 20 blocks. Bỏ sót block nào = block đó biến mất khỏi UI khi cài theme.

```xml
<setblocks>
    <block>
        <module>theme</module>                <!-- 'theme' hoặc tên module gốc (news/banners/menu/users) -->
        <file_name>global.ticker.php</file_name>
        <title>Tin nóng (Ticker)</title>
        <template>no_title</template>          <!-- xem §3 -->
        <position>[TICKER]</position>          <!-- phải khớp <positions> -->
        <all_func>1</all_func>                  <!-- 1=mọi nơi, 0=chỉ <funcs> -->
        <config/>                               <!-- HOẶC chuỗi PHP serialize -->
    </block>
    <block>
        <module>news</module>
        <file_name>global.block_news_cat.php</file_name>
        <title>Tin tiêu điểm</title>
        <template>no_title</template>
        <position>[HERO]</position>
        <all_func>0</all_func>
        <config>a:6:{s:5:"catid";a:1:{i:0;i:1;}s:6:"numrow";s:1:"5";s:12:"title_length";s:1:"0";s:11:"showtooltip";s:1:"1";s:16:"tooltip_position";s:6:"bottom";s:14:"tooltip_length";s:1:"0";}</config>
        <funcs>news:main</funcs>                <!-- chỉ active ở news/main -->
    </block>
</setblocks>
```

**Quy tắc serialize `<config>`**:

1. **PHẢI có ĐẦY ĐỦ key** trong JSON manifest của block — nếu thiếu key, render sẽ ném `Undefined array key`
2. **Đọc PHP submit handler** của block để biết type chính xác (xem §1.1)
3. **Tính bằng PHP** thay vì viết tay (UTF-8 byte length):
   ```bash
   php -r 'echo serialize(["catid" => [1], "numrow" => "5"]);'
   # → a:2:{s:5:"catid";a:1:{i:0;i:1;}s:6:"numrow";s:1:"5";}
   ```
4. **Escape `&` thành `&amp;` trong XML** — byte length giữ nguyên (PHP unserialize tự decode):
   ```xml
   <!-- ❌ XML parse error -->
   <config>...s:35:"/index.php?a=1&nv=siteterms"...</config>
   <!-- ✅ -->
   <config>...s:35:"/index.php?a=1&amp;nv=siteterms"...</config>
   ```

### §1.1 Type của field config

JSON manifest config thường là placeholder string. **PHP submit handler quyết định type thật**. Đọc `nv_block_config_<NAME>_submit()` trong file block:

| Block module gốc | Field | Type | Lý do |
|---|---|---|---|
| `news/global.block_news_cat.php` | `catid` | **array** | Multi-select cat, render `WHERE catid IN(...)` qua `implode(',', $catid)` |
| `news/global.block_tophits.php` | `nocatid` | **array** | Multi-select cat loại trừ |
| `theme/global.menu_footer.php` (future) | `module_in_menu` | **array** | Multi-select module |
| `banners/global.banners.php` | `idplanbanner` | int | Single plan |
| `menu/global.bootstrap.php` | `menuid`, `title_length`, `show_home`, `show_icon` | string | Form submit `get_string` |
| `news/module.block_headline.php` | `group_id` | int | Single group (bảng `_news_groups` riêng) |

→ Lỗi `implode(): Argument #2 must be of type array, string given` = serialize sai type của `catid`.

### §1.2 ID hardcode workflow seed-first

`<setblocks>` cho block module gốc thường cần ID dynamic (`catid`, `topicid`, `pid`, `menuid`). NV5 không tự lookup → phải hardcode.

**Workflow đúng** (đảm bảo ID deterministic = thứ tự manifest):

```
1. Cài module seeder qua Admin → Module
2. Chạy seed all (categories, menus, banner-positions, ...) trên DB sạch
   → Auto-increment ID = thứ tự trong data/seeder/<theme>/<step>.json (1, 2, 3, ...)
3. Hardcode ID đó vào <setblocks> config (vd menuid=1 cho menu đầu tiên trong manifest)
4. Cài / activate theme → NV5 parse setblocks → INSERT block với ID khớp data đã seed
```

→ Nếu DB đã có data trước, ID lệch → setblocks gắn block trỏ tới ID không tồn tại. Phải reset DB hoặc sửa setblocks.

---

## §2. setlayout — override layout cho từng module:func

Trong `config.ini` (sau `<positions>`, trước `<setblocks>`), cho phép set layout khác mặc định cho từng `module:func`:

```xml
<setlayout>
    <layout>
        <name>left-main</name>
        <funcs>users:editinfo,groups</funcs>
        <funcs>contact:main</funcs>
        <funcs>page:main</funcs>
    </layout>
</setlayout>
```

`<layout><name>` phải khớp file `layout.<name>.tpl`. Mặc định = `<layoutdefault>`.

→ Hữu ích khi muốn trang Liên hệ dùng layout 2 cột thay vì 3 cột của trang chủ.

---

## §3. Smarty vs XTemplate TRAP — block.*.tpl

NV5 có 2 hệ template song song:
- **Smarty** (mới): `themes/future/`, `themes/<theme-mới>/`, …
- **XTemplate** (cũ): `themes/default/`

Khi block dùng `<template>primary</template>` mà theme thiếu file `themes/<theme>/layout/block.primary.tpl`, NV5 fallback về `themes/default/layout/block.primary.tpl` — nhưng file đó **dùng XTemplate** (`{BLOCK_TITLE}` thay vì `{$BLOCK_TITLE}`) → Smarty parse fail:

```
Smarty\CompilerException: Syntax error in template "block.primary.tpl"
unknown tag 'BLOCK_TITLE' in /themes/default/layout/block.primary.tpl
```

**Fix**: Khi dựng skeleton theme, copy đủ **3 file Smarty** từ `themes/future/layout/`:

| Template | File | Dùng làm |
|---|---|---|
| `default` | `block.default.tpl` | panel có title (BẮT BUỘC) màu chủ đạo |
| `simple` | `block.simple.tpl` | panel-body + h3 |
| `no_title` | `block.no_title.tpl` | content thuần |

→ Khi đó `<setblocks>` có thể dùng bất kỳ 3 template trên mà không lo fallback.

⚠️ **TRAP module fallback** (theo Note.md <theme-name>): Theme đang fallback về `themes/default/modules/news/` (XTemplate). Khi build theme MỚI, **PHẢI copy module templates từ future** (Smarty) vào `themes/<theme>/modules/news/` — nếu để fallback default → Smarty parse fail tương tự `block.primary.tpl`.

### §3.1 Engine TPL module gốc — bảng đối chiếu (verify từ source)

⚠️ TPL nào dùng Smarty hay XTemplate **KHÔNG đoán bừa** — phụ thuộc vào file `theme.php` hoặc `funcs/*.php` của module gốc. Verify trước khi viết / override TPL theme.

| File | Engine | Bằng chứng (line trong source) |
|---|---|---|
| `news/viewcat_main_left.tpl` | **NVSmarty** | `src/modules/news/theme.php:511` `viewsubcat_main()` dùng `new \NukeViet\Template\NVSmarty()` |
| `news/viewcat_main_right.tpl` `viewcat_main_bottom.tpl` `viewcat_two_column.tpl` | **NVSmarty** | Cùng `viewsubcat_main()` |
| `news/detail.tpl` | **XTemplate** | `news/theme.php:601` `detail_theme()` dùng `new XTemplate('detail.tpl', $dir)` |
| `news/topic.tpl` | **XTemplate** | `news/theme.php:986`, `:1066` |
| `news/viewcat_grid.tpl` `viewcat_list.tpl` `viewcat_page.tpl` `viewcat_top.tpl` | **XTemplate** | `news/theme.php:80, 194, 275, 424` |
| `news/print.tpl` `sendmail.tpl` `search.tpl` | **XTemplate** | `news/theme.php:1141, 1195, 1253` |
| `news/block_groups.tpl` (cho `global.block_news_cat`) | **XTemplate** | `news/blocks/global.block_news_cat.php:152` `new XTemplate('block_groups.tpl')` |
| `news/block_news.tpl` (cho `module.block_news`) | **XTemplate** | `news/blocks/module.block_news.php` |
| `news/block_tophits.tpl` `block_tags.tpl` `block_category.tpl` `block_headline.tpl` | **NVSmarty** | qua `get_block_tpl_dir` |
| `banners/global.banners.tpl` | **NVSmarty** | `banners/blocks/global.banners.php:131` |
| `menu/global.bootstrap.tpl` | **NVSmarty** | `menu/blocks/global.bootstrap.php` |
| `menu/global.metismenu.tpl` `slimmenu.tpl` `superfish.tpl` `treeview.tpl` `vertmenu.tpl` | **XTemplate** | qua `nv_menu_blocks()` từ `menu_blocks.php` |
| `contact/main.tpl` `form.tpl` | **NVSmarty** | `contact/funcs/main.php` |

⚠️ **Hệ quả khi override TPL ở `themes/<theme>/modules/<m>/`**:
- File override **PHẢI cùng engine** với source. Nhầm engine → render text raw `{$VAR}` (XTemplate dùng `{VAR}`) hoặc Smarty parse fail (`unknown tag 'VAR'`).
- Smarty syntax: `{$VAR}` `{foreach}` `{if}` `{$smarty.const.X}` `{$LANG->getModule('k')}`
- XTemplate syntax: `{VAR}` `<!-- BEGIN: x -->` `<!-- END: x -->` `{LANG.k}` (PHP `assign('LANG', $lang_module)` array, KHÔNG object)

**Quy trình verify nhanh** trước khi viết TPL theme override:
```bash
grep -n "new XTemplate.*<file>.tpl\|NVSmarty.*<file>.tpl" src/modules/<m>/{theme.php,blocks/*.php,funcs/*.php}
```

---

## §4. Bootstrap-first cho UI tương tác

NV5 đã include `bootstrap.bundle.min.js` trong mỗi theme. Tận dụng tối đa — **đừng viết JS riêng**.

| Use case | Bootstrap component | Thay vì JS riêng |
|---|---|---|
| Search overlay popup | **Modal** | ~120 dòng custom JS |
| Mega menu drop xuống | **Collapse** | toggle `hidden` attribute manual |
| Dropdown user menu | **Dropdown** | — |
| Sidebar mobile | **Offcanvas** | — |
| Cookie banner dismiss | **Alert dismissible** | — |

### §4.1 Search modal markup chuẩn

```html
<!-- Trigger ở topbar -->
<button class="topbar-icon-btn" data-bs-toggle="modal" data-bs-target="#nv-search-modal">
    <i class="fa-solid fa-magnifying-glass"></i>
</button>

<!-- Modal placement (cuối header_extended.tpl) -->
<div class="modal fade" id="nv-search-modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{$LANG->getGlobal('search_all')}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form method="get" action="{$smarty.const.NV_BASE_SITEURL}index.php">
                    <input type="hidden" name="{$smarty.const.NV_LANG_VARIABLE}" value="{$smarty.const.NV_LANG_DATA}">
                    <input type="hidden" name="{$smarty.const.NV_NAME_VARIABLE}" value="seek">
                    <input type="search" name="q" class="form-control form-control-lg" required>
                    <button type="submit" class="btn btn-primary">{$LANG->getGlobal('search')}</button>
                </form>
            </div>
        </div>
    </div>
</div>
```

→ Bootstrap tự handle: ESC close, focus trap, click backdrop, ARIA, body scroll lock.

### §4.2 Convert mockup HTML → Smarty (case study chrome.js)

Mockup HTML từ designer thường có file JS chung (vd `chrome.js`) làm 4 việc qua client-side rendering:

1. `renderHeader(active)` — generate HTML topbar + masthead + ticker + mainnav
2. `renderFooter()` — generate HTML footer 6 cột
3. `searchOverlay` — popup search với form + hot keywords
4. `megaMenuToggle` — toggle hamburger menu

**Lý do JS này tồn tại trong mockup**: designer dùng JS để demo nhanh, không phải lặp lại HTML trong N file mockup.

**Khi convert sang NV5 — BỎ HOÀN TOÀN JS render này**:

| Logic JS gốc | Cách thay thế trong NV5 |
|---|---|
| `renderHeader()` | **Smarty** — markup tĩnh trong `layout/header_extended.tpl` (logo + topbar + masthead + ticker + mainnav) |
| `renderFooter()` | **Smarty** — markup tĩnh trong `layout/footer_extended.tpl` (footer 6 cột) |
| `searchOverlay` | **Bootstrap Modal** (`data-bs-toggle="modal"` + `<div class="modal fade">`) — xem §4.1 |
| `megaMenuToggle` | **Bootstrap Collapse** (`data-bs-toggle="collapse"` + `<div class="collapse">`) |
| Dynamic date trong topbar | Inline `<script>` 5–6 dòng trong `header_extended.tpl` (nếu cần — không cache được) |

→ Sau refactor: **0 dòng JS riêng** render header/footer trong theme. JS theme chỉ còn `nv.main.js` + `nv.custom.js` + `bootstrap.bundle.min.js` (đều là file gốc của NV5).

**Quy trình convert chuẩn (4 bước)**:

1. Mở mockup HTML → xác định header/footer markup → copy markup vào `header_extended.tpl` / `footer_extended.tpl`, replace data tĩnh bằng `{$smarty.const.NV_BASE_SITEURL}`, `{$LANG->...}`, position tag `[XXX]`
2. Đọc JS mockup → tìm `data-attribute` driven UI:
   - `addEventListener('click', open<X>)` → tìm Bootstrap component thay thế (Modal/Collapse/Dropdown/Offcanvas)
   - Đặt `data-bs-toggle="..."` + `data-bs-target="#..."` vào trigger button
   - Markup component (Modal/Collapse content) đặt trong `header_extended.tpl` hoặc block riêng
3. Chỉ giữ JS riêng cho 2 case:
   - Render client-side (vd dynamic date — không cache được)
   - AJAX live update (notification badge, online count) — riêng từng feature
4. Không link JS render header/footer trong `theme.php`'s `$html_js`. Nếu thấy `if (theme_file_exists(... 'chrome.js'))` → xóa block đó.

**Test sau convert**:
- DevTools Network → KHÔNG có request `chrome.js` (hoặc tương đương)
- Click search icon topbar → Bootstrap Modal mở (focus auto, ESC đóng)
- Click hamburger → Bootstrap Collapse slide xuống
- Page source → có inline `<script>` ngắn cho dynamic date (nếu có)

---

## §5. URL ảnh — convention NV5

### §5.1 Module news (homeimgfile)

**Lưu DB**: `homeimgfile` chỉ lưu **relative từ `<NV_UPLOADS_DIR>/<module_upload>/`** (vd `2026_05/foo.jpg`), KHÔNG có prefix `uploads/news/`.

**Khi seed/upload**: strip prefix trước khi INSERT:
```php
$relPath = preg_replace('#^uploads/news/#', '', $picsumPath);
```

**Khi render block**: build URL theo `homeimgthumb` (1/2/3):
```php
global $site_mods;
$moduleUpload = $site_mods['news']['module_upload'] ?? 'news';

if ($homeimgthumb == 1) {
    $imgurl = NV_BASE_SITEURL . NV_FILES_DIR . '/' . $moduleUpload . '/' . $homeimgfile;   // thumb
} elseif ($homeimgthumb == 2) {
    $imgurl = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $moduleUpload . '/' . $homeimgfile; // file
} elseif ($homeimgthumb == 3) {
    $imgurl = $homeimgfile; // URL external
}
```

→ Khi SELECT cho block, **luôn lấy `homeimgthumb`** cùng `homeimgfile`. Reference: [src/modules/news/blocks/module.block_news.php:126-141](../src/modules/news/blocks/module.block_news.php).

→ Khi seed `articles`, set `homeimgthumb = 2` (vì lưu vào `uploads/`), KHÔNG `1`.

**Lưu ảnh theo `Y_m`**: `uploads/news/2026_05/` thay vì `<catalias>/` để tiện tạo thumb hàng loạt:

```bash
mogrify -resize 300x200 -path uploads/news/2026_05/_thumbs uploads/news/2026_05/*.jpg
```

### §5.2 Module banners (file_name)

Khác convention với news:
- `file_name` lưu **basename only** (vd `masthead-1_728x80.jpg`)
- URL = `NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . NV_BANNER_DIR . '/' . file_name`

---

## §6. SEO — schema.org + OpenGraph

> **Nguyên tắc bắt buộc**: KHÔNG inject `og:*` từ `theme.php`. Theme [src/themes/future/theme.php](../src/themes/future/theme.php) làm chuẩn — không hề đụng `$meta_property` hay `og:*`. Toàn bộ OG do core + module tự handle.

### §6.1 Bằng chứng "không đụng OG" (đã verify từ source)

| Vai trò | Vị trí | Đã làm gì |
|---|---|---|
| Core khai báo slot | [src/includes/constants.php:339-350](../src/includes/constants.php) | Init `$meta_property` với 11 slot OG (title, type, description, site_name, image, image:url/type/width/height/alt, url) |
| Core auto-fill default | [src/includes/core/user_functions.php:505-528](../src/includes/core/user_functions.php) | Fallback `og:title=$page_title`, `og:type='website'`, `og:image=$global_config['ogp_image']`, `og:site_name`, `og:url`, `og:image:width/height` |
| Module news/detail | [src/modules/news/funcs/detail.php](../src/modules/news/funcs/detail.php) | Override `og:image` từ `homeimgfile`, `og:image:alt`, `og:type='article'` |
| Module news/topic | [src/modules/news/funcs/topic.php](../src/modules/news/funcs/topic.php) | Override `og:image` từ topic image |
| Module news/viewcat | [src/modules/news/funcs/viewcat.php](../src/modules/news/funcs/viewcat.php) | Override `og:image` từ category image |
| Theme future | [src/themes/future/theme.php](../src/themes/future/theme.php) | **0 dòng** đụng `og:*` hoặc `$meta_property` |

→ Nếu theme inject `og:image` cho mọi trang qua `$my_head`, trên `news/detail` sẽ có **2 thẻ `<meta property="og:image">`** (1 từ core/module = ảnh bài, 1 từ theme = logo). FB/Zalo crawler chọn không xác định → preview link có thể hiện logo thay vì ảnh bài → mất view social.

### §6.2 Cách đúng để bổ sung SEO mà NV5 core CHƯA có

Core đã handle xong OG. Phần thiếu là **JSON-LD Organization** và (nếu cần) **Twitter Card**.

| Loại meta | Set bằng cách | Điều kiện |
|---|---|---|
| `og:*` (mọi loại) | **KHÔNG làm** trong theme | — Core/module đã handle |
| `og:image` default | Admin → Cấu hình → Thông tin website → upload ảnh `ogp_image` | — Core fallback tự động |
| Twitter card | Inject `<meta name="twitter:card">` qua `$my_head` ở `if ($home)` | Chỉ homepage |
| JSON-LD Organization | Inject qua `$my_head` ở `if ($home)` | Chỉ homepage — Organization không lặp ở từng article |
| JSON-LD NewsArticle/BlogPosting | **KHÔNG ở theme** — đặt ở module/funcs hoặc viết hook plugin | — Theme không có context bài viết |

### §6.3 Skeleton inject JSON-LD Organization (chỉ homepage)

Đặt ngay sau `nv_apply_hook('', 'sector4');` trong `nv_site_theme()` (theo pattern future):

```php
function nv_site_theme($contents, $full = true) {
    global $my_head, $global_config, $home, $lang_global;
    // ... (giữ nguyên phần đầu của future)

    nv_apply_hook('', 'sector4');

    // ⚠️ KHÔNG inject og:*. Core + module news đã handle (xem §6.1).
    // Chỉ bổ sung những gì core CHƯA có, và CHỈ ở homepage.
    if ($home) {
        $siteName = !empty($lang_global['nv_logo_text']) ? $lang_global['nv_logo_text'] : $global_config['site_name'];
        $siteLogo = NV_MAIN_DOMAIN . NV_BASE_SITEURL . $global_config['site_logo'];

        $ldOrg = [
            '@context' => 'https://schema.org',
            '@type'    => 'NewsMediaOrganization',  // hoặc 'Organization' tùy site
            'name'     => $siteName,
            'url'      => NV_MAIN_DOMAIN . NV_BASE_SITEURL,
            'logo'     => $siteLogo,
        ];
        if (!empty($lang_global['nv_chief_editor'])) {
            $ldOrg['founder'] = ['@type' => 'Person', 'name' => $lang_global['nv_chief_editor']];
        }
        // ... có thể thêm address, telephone, email từ $lang_global['nv_*']

        $my_head = (isset($my_head) ? $my_head : '')
            . "\n" . '<script type="application/ld+json">'
            . json_encode($ldOrg, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)
            . '</script>';
    }

    // ... rest of nv_site_theme (giữ nguyên future)
}
```

`$lang_global['nv_*']` được [`seed_theme_config()`](../src/modules/seeder/tools/seed_functions.php) ghi vào `themes/<theme>/language/vi.php` qua marker:

```php
// === SEEDER-MARKER ===
$lang_global['nv_logo_text']    = '<theme-name>';
$lang_global['nv_chief_editor'] = 'Demo User';
// ...
// === END SEEDER ===
```

### §6.4 Anti-patterns (đừng tái phạm)

```php
// ❌ SAI — duplicate với core, gây 2 thẻ og:image trên news/detail
$my_head .= '<meta property="og:image" content="' . $siteLogo . '">';

// ❌ SAI — duplicate với core
$my_head .= '<meta property="og:title" content="' . $siteName . '">';

// ❌ SAI — JSON-LD NewsArticle không nên ở theme (thiếu context bài viết)
if (!$home) {
    $my_head .= '<script type="application/ld+json">{"@type":"NewsArticle"...}</script>';
}

// ✅ ĐÚNG — chỉ Organization, chỉ homepage
if ($home) {
    $my_head .= '<script type="application/ld+json">' . json_encode($ldOrg) . '</script>';
}

// ✅ ĐÚNG — nếu cần override OG, set qua biến core (nó tự dedupe khi render)
global $meta_property;
$meta_property['og:image'] = NV_MAIN_DOMAIN . NV_BASE_SITEURL . $custom_image;
```

### §6.5 Verify sau khi áp dụng

- View source `news/detail` → đếm chính xác **1** thẻ `<meta property="og:image">` (không 2)
- View source homepage → có **1** `<script type="application/ld+json">` chứa Organization
- View source `news/detail` → KHÔNG có JSON-LD Organization (không lặp)
- [Schema.org validator](https://validator.schema.org/) → JSON-LD valid
- [Facebook Sharing Debugger](https://developers.facebook.com/tools/debug/) → preview hiển thị đúng (homepage = logo, detail = ảnh bài)

---

## §7. Tích hợp module seeder cho theme

Module `seeder` ([src/modules/seeder/](../src/modules/seeder/)) là tool **generic** seed dữ liệu demo cho mọi theme.

### §7.1 Tạo manifest

```bash
mkdir -p src/data/seeder/<theme-name>
# Tạo các file JSON theo template:
# - categories.json       (chuyên mục news)
# - topics.json           (chủ đề news)
# - departments.json      (phòng ban contact)
# - menus.json            (bộ menu module menu)
# - banner-positions.json (vị trí banner — banners_plans)
# - users.json            (user demo, password trong _default_password)
# - theme-config.json     (logo, hotline, schema.org info — _target_theme: '<theme>')
# - articles.sample.json  (bài viết mẫu)
# - banners.sample.json   (banner mẫu, plan_title trỏ tới banner-positions)
```

### §7.2 Chạy seed

Vào **Admin → Seeder → Chạy seed**:

1. Dropdown **Theme target**: chọn `<theme-name>`
2. Tick các step cần chạy (badge ⚠️ `internet` ở `articles`/`banners` cảnh báo cần internet để tải ảnh từ `picsum.photos`)
3. Bấm **"Chạy các bước đã chọn"** hoặc **"Chạy tất cả"** (chạy 9 step theo thứ tự dependencies)
4. Output hiện trong khung `<pre>` ngay dưới form, kèm thời gian thực thi (ms)

> Seeder **không có CLI** — file [tools/seed.php](../src/modules/seeder/tools/seed.php) chỉ còn stub deprecation. Toàn bộ chạy qua Admin UI vì cần context `$crypt`, `$db`, `$global_config` của NV5 sẵn có trong admin bootstrap.

### §7.3 Hash password

Module gốc users không nên đụng. Hàm `seed_users()` đã dùng đúng:

```php
global $crypt, $global_config;
$hash = $crypt->hash_password($password, $global_config['hashprefix']);
// KHÔNG dùng password_hash(PASSWORD_DEFAULT) thuần
```

`$crypt` có sẵn trong context bootstrap NV5 ([src/includes/mainfile.php:225](../src/includes/mainfile.php)).

---

## §8. Verify checklist cuối phase

```bash
# PHP syntax (tất cả) — verify pattern không cần permission prompt
find src/themes/<theme> -name "*.php" -exec php -l {} \;
find src/modules/seeder -name "*.php" -exec php -l {} \;

# JSON parse (manifest)
find src/data/seeder/<theme> -name "*.json" -exec python -m json.tool {} \;
find src/themes/<theme>/blocks -name "*.json" -exec python -m json.tool {} \;

# XML config.ini
php -r "var_dump(simplexml_load_file('src/themes/<theme>/config.ini') !== false);"

# Clear cache sau khi đổi config.ini
find src/data/cache -name "*.cache" -delete
find src/data/cache/smarty-compile -name "*.php" -delete
```

Browser checks:
- Admin → Giao diện → bật theme → trang chủ render OK, không lỗi PHP/Smarty
- DevTools Network: `theme.css` load OK, KHÔNG request `chrome.js`
- Admin → Khối → đếm số block đã auto-gắn (≥ số entry trong `<setblocks>`)
- `data/logs/error_logs/<today>_notice_log.log` rỗng (không notice mới)
- Mobile responsive (≤480px) + tablet (≤768px)
- Schema.org validator: paste URL → JSON-LD valid

**Pattern lỗi thường gặp** (debug nhanh):

| Lỗi log | Nguyên nhân | Fix ở phase |
|---|---|---|
| `Undefined array key "X"` khi render block | serialize `<config>` thiếu key X | Phase 6 — bổ sung key + tính lại serialize |
| `implode(): Argument #2 must be of type array, string given` | type field config sai (vd `catid` viết string) | Phase 6 — đọc submit handler, sửa type (xem §1.1) |
| `Smarty\CompilerException: unknown tag '<X>'` | Block dùng template fallback default theme XTemplate | Phase 2 — copy đủ 3 file `block.{default,no_title,simple}.tpl` từ future (xem §3) |
| Block trỏ ID không tồn tại (vd catid=99) | DB không sạch trước seed → ID lệch | Phase 5 — reset DB + re-seed |
| 2 thẻ `<meta property="og:image">` trên detail | Theme inject `og:*` (vi phạm §6) | Sửa `theme.php` — bỏ inject `og:*` |

---

## §9. Sub-agents code chi tiết từng block

Khi block phức tạp (>100 dòng PHP, query DB nhiều join, hoặc cần phân tích mockup HTML kỹ), **delegate cho sub-agent** thay vì làm trong main session. Lý do:
- Tách context — không làm "phình" main conversation
- Sub-agent đọc file references chuyên sâu, không tốn context của main
- Có thể chạy song song nhiều block independent

### §9.1 Chọn loại agent + model

| Tình huống | Agent type | Lý do |
|---|---|---|
| Code 1 block đơn giản (1 query, render thẳng) | `general-purpose` | Brief + code 1 lèo |
| Block phức tạp (multi-query, transaction, custom logic) | `Plan` trước → `general-purpose` sau | Plan thiết kế kiến trúc, general-purpose implement |
| Khảo sát code module gốc (vd block_headline dùng group_id thế nào) | `Explore` | Read-only, nhanh |
| Convert mockup HTML cụ thể → Smarty TPL | `general-purpose` | Có cả phân tích lẫn code |

**Model**: luôn truyền `model: "sonnet"` khi spawn sub-agent code/khảo sát block. Lý do:
- Code NV5 PHP + Smarty không cần Opus
- Sonnet đủ chính xác + tiết kiệm cost so với Opus
- Khi Dev không chỉ định khác → mặc định `sonnet`

```
Agent({
  description: "Code block ticker",
  subagent_type: "general-purpose",
  model: "sonnet",                    // ← BẮT BUỘC khi không có override
  prompt: "..."
})
```

### §9.2 Template prompt chuẩn cho sub-agent code 1 block

Sub-agent **không thấy conversation lịch sử**. Prompt phải **self-contained** — đầy đủ context, ref file, anti-patterns.

```
## Goal
Code block `global.<X>` cho theme `<theme-name>` — 4 file hoàn chỉnh, syntax pass.

## Context
- Theme path: src/themes/<theme-name>/
- Position trong layout: [<POSITION_TAG>]
- Active funcs: <module:func1,func2> (hoặc 'all' nếu render mọi nơi)
- Mockup HTML reference: design/<theme>/<file>.html — section "<tên section>"
- Module data source: <module_gốc> (bảng nv5_<lang>_<table>)

## Logic
- Query DB: <SQL pattern hoặc mô tả>
- Config admin (form trong Quản trị → Khối → cấu hình block):
  - field1: <type, default value, mô tả>
  - field2: ...

## Files cần tạo (4 file)
1. src/themes/<theme-name>/blocks/global.<X>.php       — logic
2. src/themes/<theme-name>/blocks/global.<X>.tpl       — template Smarty
3. src/themes/<theme-name>/blocks/global.<X>.json      — manifest (info + default config)
4. src/themes/<theme-name>/blocks/global.<X>.config.tpl — form admin config

## Convention BẮT BUỘC

Đọc và áp dụng các phần sau của design/theme-patterns.md:
- §1 — Format setblocks (default config phải khớp JSON manifest)
- §1.1 — Type field config (nếu block đọc list, dùng array)
- §5.1 — URL ảnh news (homeimgthumb 1/2/3) NẾU block có ảnh từ news
- §7.3 — Hash password NẾU block đụng users (hiếm)

PHP block:
- Guard: `if (!defined('NV_MAINFILE')) exit('Stop!!!');`
- Wrap trong `if (!nv_function_exists('nv_<X>')) { ... }`
- 3 hàm: `nv_<X>_config()`, `nv_<X>_submit()`, `nv_<X>($block_config)`
- Cuối file: `if (defined('NV_SYSTEM')) { $content = nv_<X>($block_config); }`
- Khởi tạo Smarty: `$tpl = new \NukeViet\Template\NVSmarty(); $tpl->setTemplateDir($block_config['real_path']);`
- Sanitize output qua Smarty modifier `|escape:'html'`, `|escape:'url'`

JSON manifest: phải có ĐẦY ĐỦ key default config — sau này serialize vào setblocks dễ.

Config form (.config.tpl): label rõ, đầy đủ field manifest, có hint cho field dynamic (vd "lookup từ <bảng>").

## Anti-patterns (lỗi đã gặp — TRÁNH)

1. KHÔNG hardcode path `themes/<theme>/` — luôn dùng `$block_config['real_path']`
2. KHÔNG dùng `password_hash()` thuần — dùng `$crypt->hash_password()` của NV5
3. KHÔNG quên `homeimgthumb` khi build URL ảnh news (sẽ ra URL sai)
4. KHÔNG dùng template `<primary>` / `<border>` nếu theme chưa có file `block.primary.tpl` Smarty (sẽ fallback default theme XTemplate cũ → parse fail)
5. KHÔNG đọc `homeimgfile` thẳng làm URL — phải có prefix `NV_UPLOADS_DIR/<module_upload>/`
6. KHÔNG hardcode catid/topicid/menuid trong PHP block — đọc từ `$block_config['key']`
7. KHÔNG echo trực tiếp trong block — return string từ `$tpl->fetch()`

## Reference files (đọc để học pattern)

- src/themes/future/blocks/global.company_info.php — pattern block của theme chuẩn (3 hàm + Smarty)
- src/modules/news/blocks/module.block_news.php:126-141 — pattern build URL ảnh theo homeimgthumb

## Output expected

1. 4 file source code đầy đủ
2. Verify syntax pass:
   - find src/themes/<theme-name>/blocks -name "global.<X>.*.php" -exec php -l {} \;
   - python -m json.tool src/themes/<theme-name>/blocks/global.<X>.json
3. Note serialize default config dùng cho <setblocks> (tính bằng `php -r 'echo serialize([...]);'`)
4. Báo cáo 5 dòng tóm tắt: file đã tạo, query DB, template render, config admin
```

### §9.3 Sub-agent đa block song song

Khi cần code 5+ block independent (không depend nhau), spawn nhiều agent **trong cùng 1 message**:

```
[message gửi nhiều Agent calls cùng lúc]
- Agent 1: code block ticker
- Agent 2: code block hotline
- Agent 3: code block office_cards
- Agent 4: code block channel_grid
- Agent 5: code block spotlight
```

→ Chạy song song, tiết kiệm thời gian. Mỗi agent self-contained nên không xung đột.

### §9.4 Sau khi agent xong

Main session (orchestrator) phải:

1. **Verify trust but verify**: agent báo "đã code 4 file" — kiểm thực tế bằng `ls`, `php -l`, `python -m json.tool`
2. **Test integration**: copy serialize config agent đã tính vào `<setblocks>` của `config.ini`
3. **Verify XML**: `php -r "simplexml_load_file(...)"`
4. **Clear cache + active theme** → block tự gắn → kiểm Quản trị → Khối

---

## §10. Smarty syntax traps trên PHP 8+

NukeViet 5 chạy PHP 8.x (8.2-8.5). 2 trap khi viết Smarty TPL gây runtime error / warning rác log mà compile-time KHÔNG báo:

### §10.1 BẮT BUỘC dùng `!empty()` cho biến/property mảng — tránh `Undefined array key`

**Quy luật**: KHÔNG dùng `{if $var}` hoặc `{if $obj.prop}` trực tiếp khi biến / khóa mảng có thể chưa được khởi tạo. PHẢI dùng `{if !empty(...)}`.

| Sai (PHP 8+ ném Warning) | Đúng |
|---|---|
| `{if $TABS}` | `{if !empty($TABS)}` |
| `{if $HERO.imgurl}` | `{if !empty($HERO.imgurl)}` |
| `{if $row.external_link}` | `{if !empty($row.external_link)}` |
| `{if $node.subs}` | `{if !empty($node.subs)}` |
| `{if $DATA.label}` | `{if !empty($DATA.label)}` |

**Lý do**: Smarty compile `{if $var}` → PHP `if ($_smarty_tpl->tpl_vars['var']->value)`. Trên PHP 8+, nếu khóa không tồn tại trong mảng → **`Warning: Undefined array key`** → write rác file `data/logs/error_logs/<today>_notice_log.log` + có thể display_errors trong dev. `!empty()` xử lý triệt để.

**Khi nào CÓ THỂ bỏ `!empty()` (an toàn không cần wrap):**
- Biến boolean global của core đảm bảo set: `{if $HOME}`, `{if $OUTDATED_BROWSER}`, `{if $COOKIE_NOTICE}`, `{if $MODULE_CONTENT}` (assigned bởi `theme.php`)
- Smarty constant: `{if $smarty.const.NV_IS_USER}`, `{if $smarty.const.NV_IS_MODADMIN}` (constant luôn defined)
- Smarty special property: `{if $item@last}`, `{if $item@first}` (loop iterator)
- Biến vừa được `{assign}` ngay trong scope: `{assign var="x" value=...}{if $x}`

**Pattern aliases cũng đúng**: Smarty hỗ trợ `{if not empty($x)}` ↔ `{if !empty($x)}` ↔ `{if isset($x) and $x}`. Khuyến nghị unify dùng `!empty(...)` cho ngắn gọn và rõ.

### §10.2 BẮT BUỘC dùng format `date()` cho `date_format` — tránh `strftime() deprecated`

**Quy luật**: Modifier `date_format` KHÔNG được chứa ký tự `%` trong format string. Phải dùng ký tự format chuẩn của hàm PHP `date()`.

| Sai (PHP 8.1+ Deprecated) | Đúng |
|---|---|
| `\|date_format:"%d/%m/%Y"` | `\|date_format:"d/m/Y"` |
| `\|date_format:"%H:%M %d/%m/%Y"` | `\|date_format:"H:i d/m/Y"` |
| `\|date_format:"%Y-%m-%d"` | `\|date_format:"Y-m-d"` |

**Lý do**: Smarty modifier `date_format` detect ký tự `%` → fallback `strftime()`. PHP 8.1 đánh dấu `strftime()` là **Deprecated**, sẽ remove tương lai. Nếu KHÔNG có `%`, Smarty dùng `date()` chuẩn (`d`=ngày, `m`=tháng, `Y`=năm 4 chữ số, `H`=giờ 24h, `i`=phút...).

**Mapping nhanh strftime → date**:

| `strftime` | `date()` | Ý nghĩa |
|---|---|---|
| `%d` | `d` | Ngày 2 chữ số |
| `%m` | `m` | Tháng 2 chữ số |
| `%Y` | `Y` | Năm 4 chữ số |
| `%y` | `y` | Năm 2 chữ số |
| `%H` | `H` | Giờ 24h |
| `%M` | `i` | ⚠️ Phút (không phải `M`) |
| `%S` | `s` | Giây |
| `%A` | `l` | Tên thứ đầy đủ |

⚠️ Trap đặc biệt: `%M` (strftime = phút) ≠ `M` (date = tên tháng viết tắt). Khi convert phải đổi `%M` → `i`.

### §10.3 Audit nhanh khi build / fix theme

```bash
# Tìm if không dùng !empty (cần review thủ công - một số case OK như $HOME)
grep -rn "{if \$[A-Za-z_][A-Za-z0-9_.]*}" src/themes/<theme>/blocks src/themes/<theme>/modules

# Tìm date_format dùng strftime syntax (PHẢI fix hết)
grep -rn 'date_format:"%' src/themes/<theme>
```

→ Sau khi fix, clear cache Smarty: `find src/data/cache/smarty-compile -name "*.php" -delete`.

---

*Tài liệu này là knowledge base cập nhật khi gặp trap mới. Workflow để paste vào Claude Code: [prompt-claude-code-handoff.md](prompt-claude-code-handoff.md). Plan cụ thể từng theme: `design/<theme>/Request.md`.*
