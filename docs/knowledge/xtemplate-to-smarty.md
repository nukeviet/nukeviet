# Chuyển đổi XTemplate → Smarty (admin_default → admin_future)

## 1. Tổng quan

| | admin_default (cũ) | admin_future (mới) |
|---|---|---|
| Template engine | XTemplate (`$xtpl`) | NVSmarty (`$tpl`) |
| CSS framework | Bootstrap 3 | Bootstrap 5 |
| Template path | `themes/admin_default/modules/{MODULE}/` | `themes/admin_future/modules/{MODULE}/` |
| Render | Parse từng block, lấy text | Fetch một lần |
| Logic vòng lặp | PHP parse từng item | Template tự `{foreach}` |

## 2. Routing theme theo op

File điều khiển:
- `src/includes/plugin/get_global_admin_theme.php`
- `src/includes/plugin/get_module_admin_theme.php`

Thêm op mới vào danh sách `in_array` của module tương ứng:

```php
// Thêm vào danh sách đã có
if (($module_info['module_file'] ?? '') == 'news' and in_array($op, ['drafts', 'main', 'op_moi'])) {
    return $new_theme;
}
// Module chưa có entry — thêm mới trước return cuối
if (($module_info['module_file'] ?? '') == 'banners' and in_array($op, ['main'])) {
    return $new_theme;
}
return 'admin_default';
```

Migration từng `op` độc lập — không ảnh hưởng các op chưa migrate.

## 3. PHP controller

### 3.1 Khởi tạo template

**XTemplate (cũ):**
```php
$xtpl = new XTemplate('main.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
```

**Smarty (mới):**
```php
$tpl = new \NukeViet\Template\NVSmarty();
$tpl->setTemplateDir(get_module_tpl_dir('main.tpl'));
```

### 3.2 Vòng lặp: parse từng item → collect array

**XTemplate:**
```php
foreach ($list as $row) {
    $xtpl->assign('ROW', $row);
    $xtpl->parse('main.loop');
}
$xtpl->parse('main');
$contents = $xtpl->text('main');
```

**Smarty:**
```php
$rows = [];
foreach ($list as $row) {
    $rows[] = $row;  // chỉ thu thập, logic điều kiện chuyển vào template
}
$tpl->assign('ROWS', $rows);
$contents = $tpl->fetch('main.tpl');
```

### 3.3 Biến bắt buộc assign

```php
$tpl->assign('LANG', $nv_Lang);
$tpl->assign('MODULE_NAME', $module_name);
$tpl->assign('OP', $op);
$tpl->assign('CHECKSS', csrf_create($module_name . '_' . $op . '_' . $admin_info['admin_id']));
```

### 3.4 Giá trị mặc định (tránh Undefined array key)

```php
$item = array_merge(['id' => 0, 'title' => '', 'status' => 0], $item ?? []);
$tpl->assign('ITEM', $item);
```

### 3.5 Register modifier (chỉ khi template dùng)

```php
$tpl->registerPlugin('modifier', 'ddatetime', 'nv_datetime_format');
$tpl->registerPlugin('modifier', 'ddate',     'nv_date_format');
$tpl->registerPlugin('modifier', 'dnumber',   'nv_number_format');
$tpl->registerPlugin('modifier', 'clean60',   'nv_clean60');
```

## 4. Template syntax

### 4.1 Bảng so sánh

| | XTemplate | Smarty |
|---|---|---|
| Biến đơn | `{VAR}` | `{$VAR}` |
| Biến mảng | `{ARRAY.key}` | `{$ARRAY.key}` hoặc `{$ARRAY['key']}` |
| Hằng số | `{NV_BASE_ADMINURL}` | `{$smarty.const.NV_BASE_ADMINURL}` |
| Điều kiện | Không có — xử lý trong PHP | `{if $COND}...{else}...{/if}` |
| Vòng lặp | `<!-- BEGIN: block -->...<!-- END: block -->` | `{foreach from=$LIST item=row}...{/foreach}` |
| Modifier | Không có | `{$var\|modifier:param}` |
| Method call | Không có | `{$OBJ->method()}` |
| Gán biến inline | Không có | `{assign var='x' value='y'}` |
| Comment | `<!-- comment -->` | `{* comment *}` |

### 4.2 Các pattern quan trọng trong template

**Foreach với index và fallback:**
```smarty
{foreach from=$LIST item=row key=idx}
<tr>
    <td>{$idx + 1}</td>
    <td>{$row.title}</td>
</tr>
{foreachelse}
<tr><td colspan="5">{$LANG->getModule('no_data')}</td></tr>
{/foreach}
```

**Điều kiện thay PHP preprocessing** — không assign HTML từ PHP, viết if trong tpl:
```smarty
{if $row.status}
    <i class="fa-solid fa-check text-success"></i>
{else}
    <i class="fa-solid fa-xmark text-danger"></i>
{/if}
```

**Ngôn ngữ** — gọi method trực tiếp thay vì `{LANG.key}`:
```smarty
{$LANG->getModule('some_key')}
{$LANG->getGlobal('save')}
{$LANG->getModule('count_items', $TOTAL|dnumber)}  {* với placeholder *}
```

## 5. Cấu trúc template admin_future chuẩn

**Action URL** — luôn ghép từ constants, không hardcode:
```smarty
{$smarty.const.NV_BASE_ADMINURL}index.php?{$smarty.const.NV_LANG_VARIABLE}={$smarty.const.NV_LANG_DATA}&amp;{$smarty.const.NV_NAME_VARIABLE}={$MODULE_NAME}&amp;{$smarty.const.NV_OP_VARIABLE}={$OP}
```

### 5.1 List page

```smarty
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="card-title mb-0">{$LANG->getModule('list_title')}</h5>
        <a href="...&amp;{$smarty.const.NV_OP_VARIABLE}=add" class="btn btn-sm btn-primary">
            <i class="fa-solid fa-plus-circle"></i> {$LANG->getGlobal('add')}
        </a>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive-lg table-card pb-1">
            <table class="table table-striped align-middle table-sticky mb-0">
                <thead><tr>
                    <th>{$LANG->getModule('col_title')}</th>
                    <th class="text-center" style="width:80px">{$LANG->getGlobal('action')}</th>
                </tr></thead>
                <tbody>
                    {foreach from=$LIST item=row}
                    <tr>
                        <td>{$row.title}</td>
                        <td class="text-center text-nowrap">
                            <a href="...&amp;{$smarty.const.NV_OP_VARIABLE}=edit&amp;id={$row.id}" class="btn btn-sm btn-secondary">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </a>
                            <button type="button" class="btn btn-sm btn-danger"
                                    data-toggle="confirm-delete"
                                    data-id="{$row.id}"
                                    data-tokend="{$CHECKSS}"
                                    data-msgconfirm="{$LANG->getModule('confirm_delete')}">
                                <i class="fa-solid fa-trash" data-icon="fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                    {foreachelse}
                    <tr><td colspan="2" class="text-center text-muted py-4">{$LANG->getModule('no_data')}</td></tr>
                    {/foreach}
                </tbody>
            </table>
        </div>
    </div>
</div>
```

### 5.2 Form (add/edit) với ajax-submit

```smarty
<form method="post" class="ajax-submit" novalidate
      action="{$smarty.const.NV_BASE_ADMINURL}index.php?...&amp;{$smarty.const.NV_OP_VARIABLE}={$OP}">
    <div class="card">
        <div class="card-header"><h5 class="card-title mb-0">{$LANG->getModule('form_title')}</h5></div>
        <div class="card-body">
            <div class="mb-3">
                <label class="form-label">{$LANG->getModule('field_title')} <span class="text-danger">*</span></label>
                <input type="text" class="form-control required" name="title" value="{$ITEM.title}">
                <div class="invalid-feedback"></div>  {* nv.core.js tự điền mess lỗi vào đây *}
            </div>
        </div>
        <div class="card-footer text-end">
            <input type="hidden" name="checkss" value="{$CHECKSS}">
            <input type="hidden" name="id" value="{$ITEM.id}">
            <button type="submit" class="btn btn-primary">
                <i class="fa-solid fa-floppy-disk"></i> {$LANG->getGlobal('save')}
            </button>
        </div>
    </div>
</form>
```

## 6. Chuyển đổi Bootstrap 3 → Bootstrap 5

| Bootstrap 3 (admin_default) | Bootstrap 5 (admin_future) |
|---|---|
| `col-xs-12` | `col-12` |
| `pull-right` / `pull-left` | `float-end` / `float-start` |
| `hidden` | `d-none` |
| `btn-default` | `btn-secondary` |
| `panel panel-default` | `card` |
| `panel-heading` / `panel-body` / `panel-footer` | `card-header` / `card-body` / `card-footer` |
| `form-group` | `mb-3` |
| `control-label` / `help-block` | `form-label` / `form-text` |
| `well` | `card` hoặc `alert` |
| `label label-primary` | `badge bg-primary` |
| `fa fa-*` / `glyphicon glyphicon-*` | `fa-solid fa-*` (Font Awesome 6) |

## 7. Nguyên tắc JavaScript

### 7.1 Không dùng JS inline — dùng data-toggle

**Sai:**
```smarty
<button onclick="deleteItem({$row.id}, '{$CHECKSS}')">Xóa</button>
<select onchange="filterList(this.value)">
```

**Đúng — template đặt data-*, JS bắt sự kiện:**
```smarty
<button type="button"
        data-toggle="confirm-delete"
        data-id="{$row.id}"
        data-tokend="{$CHECKSS}"
        data-msgconfirm="{$LANG->getModule('confirm_delete')}">
    <i class="fa-solid fa-trash" data-icon="fa-trash"></i>
</button>
```
```js
// src/themes/admin_future/js/{module}.js
$(function() {
    $('[data-toggle="confirm-delete"]').on('click', function(e) {
        e.preventDefault();
        let btn = $(this), icon = $('i', btn);
        if (icon.is('.fa-spinner')) return;
        nvConfirm(btn.data('msgconfirm'), () => {
            let orig = icon.data('icon');
            icon.removeClass(orig).addClass('fa-spinner fa-spin-pulse');
            $.ajax({
                type: 'POST',
                url: script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=' + nv_func_name,
                data: { id: btn.data('id'), checkss: btn.data('tokend'), delete: 1 },
                dataType: 'json',
                success: (data) => {
                    icon.removeClass('fa-spinner fa-spin-pulse').addClass(orig);
                    data.error ? nvToast(data.message, 'error') : location.reload();
                },
                error: (xhr, text) => { icon.removeClass('fa-spinner fa-spin-pulse').addClass(orig); nvToast(text, 'error'); }
            });
        });
    });
});
```

### 7.2 Truyền tham số qua data-*

URL/checkss tốn kém — đặt trên container cha, item chỉ giữ `data-id`:

```smarty
<div class="list"
     data-url="{$smarty.const.NV_BASE_ADMINURL}index.php?...&amp;{$smarty.const.NV_OP_VARIABLE}={$OP}"
     data-checkss="{$CHECKSS}">
    {foreach from=$LIST item=row}
    <div class="item" data-id="{$row.id}">...</div>
    {/foreach}
</div>
```
```js
let id      = $(this).closest('.item').data('id');
let url     = $(this).closest('.list').data('url');
let checkss = $(this).closest('.list').data('checkss');
```

**Các `data-*` thông dụng:**

| Attribute | Mục đích |
|---|---|
| `data-toggle` | Định danh hành động để JS bắt sự kiện |
| `data-id` | ID record |
| `data-tokend` | CSRF token (`{$CHECKSS}`) |
| `data-url` | AJAX endpoint |
| `data-msgconfirm` / `data-msgerror` | Chuỗi ngôn ngữ cho confirm/toast |
| `data-current` | Giá trị hiện tại (rollback khi lỗi) |
| `data-icon` | Class icon gốc (restore sau spinner) |

### 7.3 Biến JS toàn cục (đã có từ header.tpl, không khai báo lại)

```js
script_name      // NV_BASE_ADMINURL + 'index.php'
nv_lang_variable / nv_lang_data / nv_name_variable / nv_fc_variable
nv_module_name   // tên module hiện tại
nv_func_name     // op hiện tại
```

### 7.4 Feedback + Loading indicator

```js
nvToast('message', 'success' | 'error' | 'warning');
nvConfirm('message', () => { /* callback OK */ });
nvAlert('message');

// Spinner pattern — data-icon lưu class gốc để restore
let icon = $('i', btn), orig = icon.data('icon');
if (icon.is('.fa-spinner')) return;  // chặn double-click
icon.removeClass(orig).addClass('fa-spinner fa-spin-pulse');
$.ajax({...}).always(() => icon.removeClass('fa-spinner fa-spin-pulse').addClass(orig));
```

### 7.5 Submit form qua ajax-submit

Dùng `class="ajax-submit"` — `nv.core.js` tự xử lý, không cần viết AJAX thủ công (xem mục 5.2).

**PHP trả JSON qua `nv_jsonOutput()` — bắt buộc có `status` và `mess`:**

```php
// Thành công
nv_jsonOutput(['status' => 'OK',    'mess' => $nv_Lang->getModule('save_success'), 'redirect' => '...']);
nv_jsonOutput(['status' => 'OK',    'mess' => $nv_Lang->getModule('save_success'), 'refresh'  => true]);
// Lỗi — highlight input
nv_jsonOutput(['status' => 'error', 'mess' => $nv_Lang->getModule('title_required'), 'input' => 'title']);
// Lỗi chung
nv_jsonOutput(['status' => 'error', 'mess' => $nv_Lang->getModule('save_failed')]);
```

**Tất cả trường JSON hỗ trợ:**

| Trường | Mô tả |
|---|---|
| `status` | **Bắt buộc.** `'OK'`/`'ok'`/`'success'` hoặc `'error'`/`'NO'`/`'no'` |
| `mess` | **Bắt buộc.** Nội dung thông báo (có thể rỗng `''`) |
| `redirect` | URL chuyển hướng sau thành công |
| `refresh` | `true` = reload trang sau thành công |
| `timeout` | ms chờ trước redirect/refresh (mặc định 2000) |
| `warning` | `true` = toast màu warning thay vì success |
| `input` | `name` input bị lỗi → highlight `is-invalid` + focus |
| `input_parent` | CSS selector thẻ cha (khi nhiều input cùng name) |
| `tab` | ID Bootstrap 5 tab cần mở khi lỗi |

Nếu form có `data-callback="functionName"` → gọi hàm đó trước khi redirect/refresh.

### 7.6 File module JS

Tạo mới nếu chưa có: `src/themes/admin_future/js/{module_file}.js`

```js
/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 */

$(function() {
    $('[data-toggle="some-action"]').on('click', function(e) {
        e.preventDefault();
        // ...
    });
});
```

## 8. Checklist migration

### PHP controller
- [ ] Xóa `new XTemplate(...)` và toàn bộ `$xtpl->*`
- [ ] Chuyển loop parse thành collect array
- [ ] Assign đủ: `LANG`, `MODULE_NAME`, `OP`, `CHECKSS`
- [ ] Tất cả biến assign có giá trị mặc định (tránh undefined key)
- [ ] Register modifier nếu template dùng
- [ ] `$tpl->fetch('filename.tpl')` (setTemplateDir đã trỏ đúng thư mục)

### JavaScript
- [ ] Không có JS inline (`onclick=`, `onchange=`, `javascript:`)
- [ ] Sự kiện bắt qua `data-toggle` trong module JS
- [ ] Tham số truyền qua `data-*` (không hardcode trong JS)
- [ ] Dùng `nvToast`, `nvConfirm`, `nvAlert` — không dùng native `alert()`/`confirm()`
- [ ] Form submit dùng `class="ajax-submit"` + PHP trả `nv_jsonOutput([...])`
- [ ] Nếu chưa có `{module}.js` → tạo mới tại `src/themes/admin_future/js/`

### Template
- [ ] Không còn `<!-- BEGIN: -->` / `<!-- END: -->`
- [ ] Tất cả biến có prefix `$`, hằng dùng `{$smarty.const.*}`
- [ ] Không còn class Bootstrap 3 (`col-xs-*`, `pull-right`, `panel`, ...)
- [ ] Action URL ghép từ constants (không hardcode)
- [ ] CSRF: `<input type="hidden" name="checkss" value="{$CHECKSS}">`
- [ ] Icons dùng Font Awesome 6 (`fa-solid fa-*`)

### Routing & cache
- [ ] Thêm op vào `src/includes/plugin/get_module_admin_theme.php`
- [ ] Xóa cache: `rm -rf src/data/cache/*/*.cache && rm -rf src/data/cache/smarty-compile/*.php`
