---
name: migrate2future-block
description: Chuyển một block NukeViet 5 từ XTemplate sang NVSmarty/Bootstrap 5 cho theme future. Hỗ trợ cả block `global.*` và `module.*`. Sửa PHP shared block + tạo template future, không đụng đến override của default/mobile_default.
argument-hint: <module/block_name>
disable-model-invocation: false
allowed-tools: Read, Grep, Glob, Edit, Write, Bash
---

Chuyển block NukeViet 5 từ XTemplate → NVSmarty cho theme future.

**Mục tiêu:** `$ARGUMENTS` (format: `{module}/{block_name}`)

Ví dụ: `/migrate2future-block contact/contact_list` → tự phát hiện prefix (`global` hoặc `module`), rồi migrate:
- PHP shared: `src/modules/contact/blocks/{PREFIX}.contact_list.php`
- Template future (tạo mới): `src/themes/future/modules/contact/{PREFIX}.contact_list.tpl`

## Quy tắc chung áp dụng xuyên suốt

### Phạm vi thay đổi

- **Được sửa:** `src/modules/{MODULE}/blocks/{PREFIX}.{BLOCK}.php`
- **Được tạo/sửa:** `src/themes/future/modules/{MODULE}/{PREFIX}.{BLOCK}.tpl`
- **Được tạo/sửa (nếu có config function):** `src/themes/future/modules/{MODULE}/{PREFIX}.{BLOCK}.config.tpl`
- **KHÔNG ĐƯỢC đụng vào:** bất kỳ file nào trong `src/themes/default/` hoặc `src/themes/mobile_default/` — các theme đó có override riêng.

### Code style

- **Comment code**: Dùng `// Comment` — sau `//` có một dấu cách, chữ cái đầu viết hoa.

## Bước 1 — Xác định mục tiêu

**Kiểm tra `$ARGUMENTS`:**
- Nếu trống, không có `/`, hoặc sai cú pháp → hỏi lại user theo format `{module}/{block_name}` rồi dừng.
- Nếu hợp lệ → tách:
  - `MODULE` = phần trước `/` đầu tiên (VD: `contact`)
  - `BLOCK` = phần sau `/` đầu tiên (VD: `contact_list`)

**Phát hiện PREFIX (`global` hoặc `module`):**
- Kiểm tra sự tồn tại của cả hai file:
  - `src/modules/{MODULE}/blocks/global.{BLOCK}.php`
  - `src/modules/{MODULE}/blocks/module.{BLOCK}.php`
- Nếu chỉ có `global.{BLOCK}.php` → `PREFIX = global`
- Nếu chỉ có `module.{BLOCK}.php` → `PREFIX = module`
- Nếu cả hai cùng tồn tại → hỏi Dev muốn migrate file nào rồi dừng chờ.
- Nếu không tìm thấy file nào → báo lỗi và dừng.

**Tên file xác định (sau khi biết PREFIX):**
- PHP shared: `{PREFIX}.{BLOCK}.php`
- Template future: `{PREFIX}.{BLOCK}.tpl`
- Config template (nếu có): `{PREFIX}.{BLOCK}.config.tpl`
- Quy tắc: tên tpl **khớp với tên PHP** — giúp các file nằm gần nhau khi sắp xếp theo tên, dễ quản lý sau khi copy sang theme mới.

## Bước 2 — Đọc tài liệu bắt buộc

- `docs/knowledge/xtemplate-to-smarty.md` — toàn bộ pattern migration, syntax
- `docs/knowledge/module.md`

## Bước 3 — Thu thập & phân tích hiện trạng

Đọc theo thứ tự, **BẮT BUỘC đọc hết trước khi viết bất kỳ code nào**:

1. **PHP shared block:** `src/modules/{MODULE}/blocks/{PREFIX}.{BLOCK}.php`
   - Xác định tên hàm chính (VD: `nv_{BLOCK}_info()`), signature, và biến `$block_config`.
   - Grep tìm tất cả `new XTemplate(` trong file.
   - Nếu không có XTemplate → **DỪNG LẠI**, hỏi Dev có muốn tiếp tục không.

2. **PHP override theo theme (chỉ ĐỌC, không sửa):**
   - `src/themes/default/modules/{MODULE}/{PREFIX}.{BLOCK}.php` (nếu tồn tại)
   - `src/themes/mobile_default/modules/{MODULE}/{PREFIX}.{BLOCK}.php` (nếu tồn tại)
   - Mục đích: hiểu cấu trúc dữ liệu, icon class, các assign `CD`/`OTHER` đang dùng.

3. **Template cũ (chỉ ĐỌC):**
   - `src/themes/default/modules/{MODULE}/block.{BLOCK}.tpl`
   - Mục đích: hiểu cấu trúc loop, section, dữ liệu cần render.

4. **Template future hiện có (nếu đã có):**
   `src/themes/future/modules/{MODULE}/{PREFIX}.{BLOCK}.tpl`

5. **Một vài block future đã migrate** trong cùng module để học pattern UI:
   - Glob: `src/themes/future/modules/{MODULE}/*.tpl` (trừ `*.config.tpl`)
   - Ưu tiên block cùng module vì dùng chung UI component.
   - Nếu tồn tại `*.config.tpl` → đọc để học pattern config template.

6. **Language file:** `src/modules/{MODULE}/language/vi.php` (nếu block dùng lang key)

7. **Kiểm tra config function:** trong PHP shared block, tìm hàm `nv_block_config_{MODULE}_{BLOCK}()`.
   - Nếu hàm đó **hardcode HTML** (nối chuỗi `$html .= ...`) → cần migrate sang NVSmarty (xem Bước 5C).
   - Nếu đã dùng NVSmarty → bỏ qua bước 5C.

## Bước 4 — Lập kế hoạch & xác nhận

Trình bày ngắn gọn:
- **Tên hàm** sẽ sửa trong shared PHP (info + config nếu có)
- **Cấu trúc dữ liệu** sẽ build (array nào, field nào)
- **Template future**: layout chính, icon map nếu có
- **Config template** (nếu có config function hardcode HTML): cấu trúc form config
- **Rủi ro**: encoding, icon, theme override...

**Dừng lại và chờ Dev phản hồi "OK" trước khi viết code.**

## Bước 5 — Thực thi migration

### 5A. Sửa PHP shared block

**File:** `src/modules/{MODULE}/blocks/{PREFIX}.{BLOCK}.php`

**Pattern chuẩn** thay thế XTemplate:

```php
function nv_{BLOCK}_info($block_config)
{
    global $nv_Cache, $site_mods, $nv_Lang;

    $module = $block_config['module'];
    if (!isset($site_mods[$module])) {
        return '';
    }

    [$block_theme, $dir] = get_block_tpl_dir('{PREFIX}.{BLOCK}.tpl', true, $module);
    if (empty($dir)) {
        return '';
    }

    // Collect dữ liệu từ DB
    $rows = $nv_Cache->db('SELECT * FROM ...', 'id', $module);
    if (empty($rows)) {
        return '';
    }

    // Nếu block cần lang string của module:
    $nv_Lang->loadModule($site_mods[$module]['module_file'], loadtmp: true);

    // Build structured array — không assign từng phần tử rời
    $items = [];
    foreach ($rows as $row) {
        // ... xử lý $row, thêm vào $items
    }

    $tpl = new \NukeViet\Template\NVSmarty();
    $tpl->setTemplateDir($dir);
    $tpl->assign('LANG', $nv_Lang);
    $tpl->assign('TEMPLATE', $block_theme);
    $tpl->assign('MODULE', $module);
    $tpl->assign('ITEMS', $items);  // hoặc tên phù hợp

    $content = $tpl->fetch('{PREFIX}.{BLOCK}.tpl');
    $nv_Lang->changeLang();  // chỉ gọi khi đã gọi loadModule() ở trên
    return $content;
}
```

**Các lưu ý quan trọng:**

- Dùng `get_block_tpl_dir()` — **không** dùng `get_tpl_dir()` hay `get_module_tpl_dir()`
- Dùng `$nv_Lang` — **không** dùng `\NukeViet\Core\Language::$lang_global` (cú pháp cũ NV4.5)
- Chữ ký hàm (`nv_{BLOCK}_info`) được gọi từ dòng cuối file: `$content = nv_{BLOCK}_info($block_config['module'])` hoặc `nv_{BLOCK}_info($block_config)` — kiểm tra lời gọi hiện tại và giữ nguyên cú pháp đó.
- Build array đầy đủ rồi mới `assign` — không parse từng item theo kiểu XTemplate.
- Gọi `$nv_Lang->changeLang()` ngay sau `fetch()` nếu đã gọi `loadModule()`.

**Xử lý encoding — bẫy hay gặp:**

| Nguồn giá trị | Trạng thái | Xử lý trong PHP | Trong tpl |
|---|---|---|---|
| `nv_parse_phone()` — `$num[0]` | Đã `nv_htmlspecialchars()` | Không encode thêm | Không dùng `\|escape` |
| Email/tên từ DB split | Raw | `nv_htmlspecialchars($val)` | Không dùng `\|escape` |
| Username mạng xã hội (skype, zalo…) | Raw | `nv_htmlspecialchars($val)` | Không dùng `\|escape` |
| `full_name`, `title` từ DB row | Thường đã pre-encoded | Không encode thêm | Không dùng `\|escape` |
| Tên key unknown (json_decode) | Raw | `nv_htmlspecialchars($key)` | Không dùng `\|escape` |

**Quy tắc tổng quát:** Normalize hết về pre-encoded trong PHP, template không dùng `|escape` cho các biến đó. Dùng `|escape` chỉ khi chắc chắn giá trị là raw và chưa được encode ở bất kỳ đâu.

**Không để `icon` trong PHP array** — icon là quyết định trình bày, thuộc về template. Chỉ để `type` (VD: `'phone'`, `'email'`, `'skype'`) để template tự lookup.

### 5B. Tạo template future

**File:** `src/themes/future/modules/{MODULE}/{PREFIX}.{BLOCK}.tpl`

Tuân thủ Smarty/Bootstrap 5. Các điểm cụ thể cho block:

- Biến assign từ PHP viết **HOA**: `{$DEPARTMENTS}`, `{$ITEMS}`, `{$LANG}`, `{$TEMPLATE}`
- Biến nội bộ tpl viết **thường**: `{$item}`, `{$contact}`, `{$icon}`
- Language: `{$LANG->getModule('key')}` / `{$LANG->getGlobal('key')}`
- Bootstrap 5: `list-unstyled`, `d-flex`, `gap-2`, `fw-semibold`, `mb-1`, `flex-shrink-0`
- **Không dùng thẻ heading `<h1>`–`<h6>` trong block** — block được nhúng vào sidebar/footer, heading làm rối cấu trúc SEO của trang. Thay bằng class tương đương: `<div class="h6 ...">`, `<p class="h5 ...">`, v.v.
- **Không viết `<script>` inline trong template** — nếu block cần JS tương tác, gọi `addition_module_assets($module, 'js')` trong PHP shared block rồi viết JS vào `src/themes/future/js/{module_file}.js`. Dùng `data-*` attribute trên HTML element làm hook để JS ngoài bắt sự kiện (không dùng ID hardcode phụ thuộc vào `{$MODULE}`).

**Icon map — định nghĩa trong tpl, không trong PHP:**

```smarty
{assign var="icons" value=[
    'phone'    => 'fa-solid fa-phone-volume',
    'email'    => 'fa-solid fa-envelope',
    'fax'      => 'fa-solid fa-fax',
    'skype'    => 'fa-brands fa-skype',
    'viber'    => 'fa-brands fa-viber',
    'whatsapp' => 'fa-brands fa-whatsapp',
    'zalo'     => 'fa-solid fa-comments',
]}
```

Lookup với fallback về icon mặc định:
```smarty
{assign var="icon" value=$icons[$contact.type]|default:'fa-solid fa-address-book'}
<i class="{$icon} fa-fw flex-shrink-0"></i>
```

**Cú pháp hay dùng:**

```smarty
{* Loop với separator cuối *}
{foreach from=$DEPARTMENTS item=dept}
    ...
    {if not $dept@last}<hr />{/if}
{/foreach}

{* Điều kiện rỗng *}
{if not empty($dept.contacts) or not empty($dept.others)}

{* Link có điều kiện *}
{if not empty($contact.link)}
    <a href="{$contact.link}" class="text-break">{$contact.display}</a>
{else}
    <span class="text-break">{$contact.display}</span>
{/if}
```

**Icons FA6 Free thường dùng cho block liên hệ:**

| Type | Class |
|---|---|
| phone | `fa-solid fa-phone-volume` |
| email | `fa-solid fa-envelope` |
| fax | `fa-solid fa-fax` |
| address | `fa-solid fa-map-location-dot` |
| skype | `fa-brands fa-skype` |
| viber | `fa-brands fa-viber` |
| whatsapp | `fa-brands fa-whatsapp` |
| zalo | `fa-solid fa-comments` *(không có trong FA6 Free)* |
| facebook | `fa-brands fa-facebook` |
| youtube | `fa-brands fa-youtube` |
| unknown/default | `fa-solid fa-address-book` |

### 5C. Migrate config function (nếu có)

Áp dụng khi hàm `nv_block_config_{MODULE}_{BLOCK}()` đang hardcode HTML thay vì dùng NVSmarty.

**Pattern chuẩn:**

```php
function nv_block_config_{MODULE}_{BLOCK}($module, $data_block)
{
    global $site_mods, $nv_Cache, $nv_Lang;

    [$block_theme, $dir] = get_block_tpl_dir('{PREFIX}.{BLOCK}.config.tpl', true, $module);
    $tpl = new \NukeViet\Template\NVSmarty();
    $tpl->setTemplateDir($dir);
    $tpl->assign('LANG', $nv_Lang);
    $tpl->assign('TEMPLATE', $block_theme);
    $tpl->assign('CONFIG', $data_block);

    // Assign thêm data cần thiết cho form config (VD: danh sách phòng ban)
    $rows = $nv_Cache->db('SELECT * FROM ...', 'id', $module);
    $tpl->assign('DEPARTMENTS', array_filter($rows, fn($d) => $d['act']));

    return $tpl->fetch('{PREFIX}.{BLOCK}.config.tpl');
}
```

**File template config:** `src/themes/future/modules/{MODULE}/{PREFIX}.{BLOCK}.config.tpl`

- Là HTML fragment (không có thẻ wrapper ngoài cùng) — được inject vào form cấu hình block của admin.
- Dùng `name="config_{field}"` cho mọi input — hệ thống đọc qua `nv_block_config_{MODULE}_{BLOCK}_submit()`.
- `{$CONFIG.field}` để pre-fill giá trị đã lưu.
- Cú pháp `selected` / `checked` dùng `{if}` trong tpl, không từ PHP.

**Ví dụ config template đơn giản (chọn phòng ban):**

```smarty
<div class="row mb-3">
    <label for="config_departmentid" class="col-sm-3 col-form-label text-sm-end text-truncate fw-medium">{$LANG->getModule('departmentid')}:</label>
    <div class="col-sm-5">
        <select name="config_departmentid" id="config_departmentid" class="form-select">
            {foreach from=$DEPARTMENTS item=dept}
            <option value="{$dept.id}"{if $dept.id eq $CONFIG.departmentid} selected{/if}>{$dept.full_name}</option>
            {/foreach}
        </select>
    </div>
</div>
```

**Lưu ý:**
- Hàm `nv_block_config_{MODULE}_{BLOCK}_submit()` giữ nguyên — không cần sửa.
- Không gọi `loadModule()` trong config function trừ khi cần lang key của module mà `$nv_Lang` chưa load.
- Tham chiếu thực tế: `src/modules/contact/blocks/global.department.php` + `src/themes/future/modules/contact/global.department.config.tpl`.

---

## Bước 6 — Kiểm tra

Báo cáo:
- Các file đã tạo/sửa
- Những điểm cần Dev kiểm tra thủ công (hiển thị icon, link tel:, link email...)

---

## Checklist tự kiểm tra trước khi hoàn thành

**Phạm vi:**
- [ ] **Không** sửa bất kỳ file nào trong `src/themes/default/` hoặc `src/themes/mobile_default/`

**PHP shared block:**
- [ ] Dùng `get_block_tpl_dir()`, không dùng `get_tpl_dir()` hay `get_module_tpl_dir()`
- [ ] Dùng `$nv_Lang`, không dùng `\NukeViet\Core\Language::$lang_global`
- [ ] Xóa toàn bộ `$xtpl->*`, thay bằng `NVSmarty` + `setTemplateDir()` + `fetch()`
- [ ] Collect array, không parse từng item
- [ ] Assign nguyên mảng thay vì từng phần tử rời
- [ ] `nv_htmlspecialchars()` đã áp dụng cho các giá trị raw (email, username mạng xã hội, json key/value)
- [ ] Giá trị từ `nv_parse_phone()` (`$num[0]`) **không** encode thêm
- [ ] Không có field `icon` trong array data — chỉ có `type`
- [ ] Gọi `$nv_Lang->changeLang()` sau `fetch()` nếu đã gọi `loadModule()`
- [ ] Lời gọi hàm ở cuối file (`nv_{BLOCK}_info(...)`) giữ nguyên cú pháp cũ

**Template future:**
- [ ] Không còn XTemplate syntax (`<!-- BEGIN: -->`, `{VAR}` không có `$`)
- [ ] Icon map khai báo bằng `{assign var="icons" value=[...]}` trong tpl
- [ ] Lookup icon dùng `$icons[$contact.type]|default:'...'`
- [ ] Icons dùng Font Awesome 6 (`fa-solid`, `fa-brands`, `fa-regular`)
- [ ] Không dùng `|escape` cho giá trị đã pre-encoded từ PHP
- [ ] Bootstrap 5: `list-unstyled`, `d-flex`, `gap-2`, `flex-shrink-0`, `fa-fw`
- [ ] Không có thẻ `<h1>`–`<h6>` — thay bằng `<div class="h1">` … `<div class="h6">`
- [ ] Không còn class Bootstrap 3 (`col-xs-*`, `pull-right`, `hidden`, ...)
- [ ] Không có `<script>` inline — JS tương tác nằm trong `src/themes/future/js/{module_file}.js`, hook qua `data-*` attribute; PHP gọi `addition_module_assets($module, 'js')`

**Config function (nếu có):**
- [ ] Hàm `nv_block_config_*` đã chuyển sang NVSmarty, không còn hardcode `$html .= ...`
- [ ] File `{PREFIX}.{BLOCK}.config.tpl` đã tạo tại `src/themes/future/modules/{MODULE}/`
- [ ] `{$CONFIG.field}` pre-fill đúng giá trị đã lưu
- [ ] `selected` / `checked` dùng `{if}` trong tpl
- [ ] Hàm `nv_block_config_{MODULE}_{BLOCK}_submit()` giữ nguyên, không sửa
