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
$tpl->assign('CHECKSS', csrf_create($csrf_key));
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

### 3.6 Không dùng hàm trung gian trong admin.functions.php

Một số module cũ có hàm theme (ví dụ `nv_b_list_theme()`) trong `admin.functions.php` để đứng ra khởi tạo XTemplate, loop parse từng item, rồi trả về HTML. Hàm đó tồn tại vì XTemplate **bắt PHP phải điều khiển vòng lặp render** — Smarty không cần điều đó nữa.

**Quy tắc:** Khi migrate, xóa bỏ lớp hàm trung gian này. Toàn bộ xử lý (query, build array, assign, fetch) nằm trực tiếp trong controller PHP. Hàm trung gian chỉ hợp lý nếu nó được gọi từ nhiều nơi — nếu chỉ một nơi gọi thì không cần tách ra.

```php
// Sai — giữ lại pattern hàm trung gian:
$content = call_user_func('nv_b_list_theme', $contents, $array_users);

// Đúng — xử lý trực tiếp trong controller:
$tpl = new \NukeViet\Template\NVSmarty();
$tpl->setTemplateDir(get_module_tpl_dir('b_list.tpl'));
$tpl->assign('LANG', $nv_Lang);
// ... assign các biến ...
$contents = $tpl->fetch('b_list.tpl');
```

**Sau khi migrate xong:** grep kiểm tra hàm trung gian cũ còn được gọi ở đâu không:

```bash
grep -rn "nv_b_list_theme" src/modules/{MODULE}/
```

- Không còn chỗ nào gọi → **xóa hàm** khỏi `admin.functions.php`
- Còn chỗ khác gọi → **báo lại Dev** để quyết định hướng xử lý (không tự xóa)

### 3.7 PHP Array Formatting Rules

**Quy tắc formatting array:**

**A. Numeric array (toàn số):** Format 1 dòng
```php
$array = [1, 2, 3, 4, 5];
$list = ['item1', 'item2', 'item3'];
$options = [0, 1, 2];
```

**B. Associative array (có key cụ thể):** Xuống dòng từng phần tử
```php
$config = [
    'key1' => 'value1',
    'key2' => 'value2',
    'status' => true
];

$item = [
    'id' => $row['id'],
    'title' => $row['title'],
    'created_at' => nv_datetime_format($row['created_time'])
];

// Trong vòng lặp tạo options cho select
$options[] = [
    'value' => $i,
    'label' => $nv_Lang->getModule('option_' . $i),
    'selected' => ($i == $current_value)
];
```

**C. Mixed hoặc phức tạp:** Luôn xuống dòng
```php
$complex = [
    0 => 'first_item',
    'config' => ['nested' => true],
    'data' => $db_result,
    99 => 'last_item'
];
```

### 3.8 Tách biến theo nhóm ngữ nghĩa — không dồn vào $contents[]

**Sai — dồn tất cả vào một mảng hỗn hợp:**
```php
$contents = [];
$contents['keyword']  = '...';   // search
$contents['plans']    = [...];   // Khối quảng cáo
$contents['thead']    = [...];   // language strings
$contents['view']     = '...';   // language string
$contents['rows']     = [...];   // DB data
$tpl->assign('CONTENTS', $contents);
// Template: {$CONTENTS.keyword}, {$CONTENTS.thead}, {$CONTENTS.rows}...
```

**Đúng — tách theo nhóm ngữ nghĩa:**

| Biến | Chứa gì |
|---|---|
| `$array_search` | Dữ liệu form tìm kiếm/lọc: keyword, filter options, selected values |
| `$array_plans` | Dữ liệu đọc từ CSDL hoặc biến global các khối quảng cáo |
| `$array` | Rows đọc từ CSDL, đã xử lý sẵn cho hiển thị (formatted dates, URLs, bool flags...) |
| Language strings | **Không assign** — dùng `$LANG->getModule()` / `$LANG->getGlobal()` trực tiếp trong tpl |

```php
$array_search = [
    'keyword' => $nv_Request->get_title('q', 'get', ''),
    'pid'     => $nv_Request->get_int('pid', 'get', 0),
];

$array = [];
while ($row = $result->fetch()) {
    $array[] = [
        'id'        => $row['id'],
        'title'     => $row['title'],
        'act'       => (bool) $row['act'],      // bool, không phải chuỗi HTML
        'publ_date' => nv_date_format($row['publ_time']),
        // ...
    ];
}

$tpl->assign('ARRAY_SEARCH', $array_search);
$tpl->assign('ARRAY_PLANS', $array_plans);
$tpl->assign('ARRAY', $array);
// Language strings KHÔNG assign
```

### 3.9 JSON Response và Redirect URLs

Khi trả về JSON response (điển hình trong AJAX form), cần tuân thủ các quy tắc:

**A. Array formatting:** Luôn dùng multi-line cho associative arrays
```php
// ❌ Sai - 1 dòng với associative array
nv_jsonOutput(['status' => 'error', 'mess' => $nv_Lang->getGlobal('error_checkss')]);

// ✅ Đúng - multi-line formatting
nv_jsonOutput([
    'status' => 'error',
    'mess' => $nv_Lang->getGlobal('error_checkss')
]);

nv_jsonOutput([
    'status' => 'OK',
    'mess' => $nv_Lang->getGlobal('save_success'),
    'redirect' => nv_url_rewrite(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op, true)
]);
```

**B. Redirect URLs:** LUÔN dùng `nv_url_rewrite(full_url, true)`
```php
// ❌ Sai - ghép chuỗi thô
'redirect' => NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op

// ✅ Đúng - dùng nv_url_rewrite với TOÀN BỘ URL làm tham số đầu
'redirect' => nv_url_rewrite(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op, true)
```

**Lý do:** `nv_url_rewrite()` xử lý URL theo cấu hình rewrite rules và SEO-friendly URLs của hệ thống. Tham số đầu tiên phải là URL đầy đủ hoàn chỉnh, tham số thứ 2 `true` để force absolute URL.

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

### 4.3 checked / selected — xử lý trong Smarty, không từ PHP

**Sai — PHP tạo chuỗi:**
```php
$checked = $row['active'] ? ' checked="checked"' : '';
$selected = ($row['type'] == 1) ? ' selected="selected"' : '';
```
```smarty
<input type="checkbox"{$CHECKED}>
<option value="1"{$SELECTED}>
```

**Đúng — Smarty xử lý điều kiện:**
```smarty
<input type="checkbox" name="active" value="1"{if $ITEM.active} checked{/if}>
<option value="1"{if $ITEM.type == 1} selected{/if}>Loại 1</option>
```

## 5. Cấu trúc template admin_future chuẩn

**Action URL** — luôn ghép từ constants, không hardcode:
```smarty
{$smarty.const.NV_BASE_ADMINURL}index.php?{$smarty.const.NV_LANG_VARIABLE}={$smarty.const.NV_LANG_DATA}&amp;{$smarty.const.NV_NAME_VARIABLE}={$MODULE_NAME}&amp;{$smarty.const.NV_OP_VARIABLE}={$OP}
```

### 5.1 List page

Quy tắc bảng danh sách:
- **`<div class="card-body">` KHÔNG dùng class `p-0`** — giữ padding mặc định, bảng tự set padding qua `table-responsive-lg table-card`
- Mọi `<th>` trong thead có class `text-nowrap`; độ rộng cột dùng `style="width:X%"` thay vì px, mọi th trong thead đều phải có width và tổng lại phải bằng 100%
- Không dùng `text-center` ở thead nếu tbody tương ứng không có
- **Icon button chuẩn**: nút Sửa dùng `fa-pencil` (không dùng `fa-pen-to-square`); nút Xóa dùng `fa-trash`; nút Đình chỉ/Kích hoạt dùng `fa-toggle-on`
- Button xóa luôn dùng `btn-danger`; không dùng class `fa-lg`
- **Nguyên tắc button action**: nếu nút **ít và text ngắn** → giữ text (icon + text). Chỉ dùng icon-only khi text quá dài hoặc quá nhiều nút trong một ô. Khi icon-only: bắt buộc có `aria-label` **và** `data-bs-toggle="tooltip" title="..."`
- Select thứ tự trong tbody thêm class `fw-75`, nếu các nút action trong row để `form-control-sm` hoặc `btn-sm` thì select cũng phải thêm class `form-select-sm`
- Nếu có phân trang hoặc công cụ: thêm `card-footer border-top` sau `card-body`

```smarty
<div class="card">
    <div class="card-body">
        <div class="table-responsive-lg table-card pb-1">
            <table class="table table-striped align-middle table-sticky mb-0">
                <thead>
                    <tr>
                        <th class="text-nowrap">{$LANG->getModule('col_title')}</th>
                        <th class="text-center text-nowrap" style="width:10%">{$LANG->getGlobal('action')}</th>
                    </tr>
                </thead>
                <tbody>
                    {foreach from=$LIST item=row}
                    <tr>
                        <td>{$row.title}</td>
                        <td class="text-center text-nowrap">
                            {* Ít nút + text ngắn → giữ text *}
                            <a href="...&amp;{$smarty.const.NV_OP_VARIABLE}=edit&amp;id={$row.id}"
                               class="btn btn-sm btn-secondary">
                                <i class="fa-solid fa-pencil"></i> {$LANG->getGlobal('edit')}
                            </a>
                            {* Hoặc icon-only khi nhiều nút: bắt buộc aria-label + tooltip *}
                            <button type="button" class="btn btn-sm btn-danger"
                                    aria-label="{$LANG->getGlobal('delete')}"
                                    data-bs-toggle="tooltip" title="{$LANG->getGlobal('delete')}"
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
    {* Card-footer khi có phân trang hoặc công cụ *}
    <div class="card-footer border-top">
        <div class="d-flex flex-wrap justify-content-between align-items-center">
            <div class="d-flex flex-wrap flex-sm-nowrap align-items-center">
                {* Công cụ nếu có *}
            </div>
            <div class="pagination-wrap">
                {* Phân trang nếu có — assign $PAGINATION từ PHP *}
                {$PAGINATION}
            </div>
        </div>
    </div>
</div>
```

### 5.2 Form (add/edit) với ajax-submit

**2 pattern layout button submit trong admin_future:**

**A. Grid form (label trái, input phải):** Button align với input fields
```smarty
<form method="post" class="ajax-submit" novalidate
      action="{$smarty.const.NV_BASE_ADMINURL}index.php?...&amp;{$smarty.const.NV_OP_VARIABLE}={$OP}">
    <div class="card">
        <div class="card-body pt-4">
            <div class="row mb-3">
                <label for="title" class="col-sm-3 col-form-label text-sm-end">{$LANG->getModule('field_title')} <span class="text-danger">(*)</span></label>
                <div class="col-sm-8 col-lg-6 col-xxl-5">
                    <input type="text" class="form-control required" id="title" name="title" value="{$ITEM.title}">
                    <div class="invalid-feedback"></div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-8 offset-sm-3">
                    <input type="hidden" name="checkss" value="{$CHECKSS}">
                    <input type="hidden" name="id" value="{$ITEM.id}">
                    <button type="submit" class="btn btn-primary">
                        <i class="fa-solid fa-floppy-disk"></i> {$LANG->getGlobal('save')}
                    </button>
                </div>
            </div>
        </div>
    </div>
</form>
```

**B. Simple form (không có grid):** Button trong `card-footer text-center`
```smarty
<form method="post" class="ajax-submit" novalidate
      action="{$smarty.const.NV_BASE_ADMINURL}index.php?...&amp;{$smarty.const.NV_OP_VARIABLE}={$OP}">
    <div class="card">
        <div class="card-body">
            <div class="mb-3">
                <label class="form-label">{$LANG->getModule('field_title')} <span class="text-danger">(*)</span></label>
                <input type="text" class="form-control required" name="title" value="{$ITEM.title}">
                <div class="invalid-feedback"></div>  {* nv.core.js tự điền mess lỗi vào đây *}
            </div>
        </div>
        <div class="card-footer text-center">
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

**Ajax inject HTML có form vào DOM:**

Nếu response ajax trả về HTML chứa element `.ajax-submit`, cần gọi `initFormAjKeyboard()` để khởi tạo lại xử lý phím validate:

```js
$.ajax({
    // ...
    success: function(html) {
        $('#target').html(html);
        if ($('#target').find('.ajax-submit').length) {
            initFormAjKeyboard();
        }
    }
});
```

### 7.6 File module JS

Tạo mới nếu chưa có: `src/themes/admin_future/js/{module_file}.js`

```js
/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
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
- [ ] Assign đủ: `LANG` (dùng `$nv_Lang`), `MODULE_NAME`, `OP`, `CHECKSS`
- [ ] Tất cả biến assign có giá trị mặc định (tránh undefined key)
- [ ] Register modifier nếu template dùng
- [ ] `$tpl->fetch('filename.tpl')` (setTemplateDir đã trỏ đúng thư mục)
- [ ] Không tạo chuỗi `checked="checked"` / `selected="selected"` từ PHP
- [ ] Không dùng hàm trung gian trong `admin.functions.php` — xử lý trực tiếp trong controller
- [ ] Grep kiểm tra hàm trung gian cũ còn được gọi ở đâu không: còn → báo Dev; không còn → xóa hàm
- [ ] Tách biến: `$array_search` (tìm kiếm/lọc), `$array` (rows DB) — không dồn vào `$contents[]` hỗn hợp; language strings dùng trực tiếp trong tpl
- [ ] `nv_insert_logs()` đã bổ sung cho thao tác thêm/sửa/xóa CSDL

### JavaScript
- [ ] Không có JS inline (`onclick=`, `onchange=`, `javascript:`)
- [ ] Sự kiện bắt qua `data-toggle` trong module JS
- [ ] Tham số truyền qua `data-*` (không hardcode trong JS)
- [ ] Dùng `nvToast`, `nvConfirm`, `nvAlert` — không dùng native `alert()`/`confirm()`
- [ ] Dùng `let`/`const`, không dùng `var`
- [ ] Form submit dùng `class="ajax-submit"` + PHP trả `nv_jsonOutput([...])`
- [ ] Nếu chưa có `{module}.js` → tạo mới tại `src/themes/admin_future/js/`
- [ ] Ajax inject HTML có `.ajax-submit` → gọi `initFormAjKeyboard()`

### Template
- [ ] Không còn `<!-- BEGIN: -->` / `<!-- END: -->`
- [ ] Tất cả biến có prefix `$`, hằng dùng `{$smarty.const.*}`
- [ ] Không còn class Bootstrap 3 (`col-xs-*`, `pull-right`, `panel`, ...)
- [ ] Action URL ghép từ constants (không hardcode)
- [ ] CSRF: `<input type="hidden" name="checkss" value="{$CHECKSS}">`
- [ ] Icons dùng Font Awesome 6 (`fa-solid fa-*`); không còn class `fa-lg`
- [ ] Nút ít + text ngắn → giữ text; nút icon-only → có `aria-label` + `data-bs-toggle="tooltip"`; button xóa dùng `btn-danger`
- [ ] Mọi form element có `name`; `checked`/`selected` dùng `{if}` trong tpl
- [ ] Thead có `text-nowrap`; độ rộng cột dùng `%`

### Routing
- [ ] Thêm op vào `src/includes/plugin/get_module_admin_theme.php` **VÀ** `get_global_admin_theme.php`
