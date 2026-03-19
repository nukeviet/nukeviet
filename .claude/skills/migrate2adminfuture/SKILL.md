---
name: migrate2adminfuture
description: Chuyển giao diện một khu vực admin của module từ admin_default (XTemplate) sang admin_future (Smarty/Bootstrap 5).
argument-hint: <module/filename>
disable-model-invocation: false
allowed-tools: Read, Grep, Glob, Edit, Write, Bash
---

Chuyển giao diện admin_default → admin_future cho một khu vực trong module NukeViet 5.

**Mục tiêu:** `$ARGUMENTS` (format: `{module}/{filename}`)

Ví dụ: `/migrate2adminfuture news/authors` → migrate:
- `src/modules/news/admin/authors.php`
- Các template được khai báo trong PHP đó tại `src/themes/admin_default/modules/news/`
- Js nếu có tại `src/themes/admin_default/js/news.js` liên quan đến các chức năng trong tpl đó.

## Bước 1 — Xác định mục tiêu

Từ `$ARGUMENTS` split theo `/` lấy:
- `MODULE` = phần trước `/` đầu tiên (VD: `news`)
- `FILE` = phần sau `/` đầu tiên (VD: `authors`)

Nếu `$ARGUMENTS` trống, không có `/` hoặc sai cú pháp, hỏi lại user theo format `{module}/{filename}`.

## Bước 2 — Đọc tài liệu bắt buộc

Đọc các tài liệu sau trước khi bắt đầu:
- `docs/knowledge/xtemplate-to-smarty.md` — **BẮT BUỘC**: toàn bộ pattern migration, syntax, JS, ajax-submit
- `docs/knowledge/module.md`
- `docs/knowledge/theme.md`
- `docs/knowledge/security.md`

## Bước 3 — Thu thập & phân tích hiện trạng

Đọc theo thứ tự, **BẮT BUỘC đọc hết trước khi viết bất kỳ code nào**:

1. **PHP controller:** `src/modules/{MODULE}/admin/{FILE}.php`

2. **Xác định danh sách TPL từ PHP** — Sau khi đọc PHP, grep tìm tất cả khai báo `new XTemplate(`:
   ```
   pattern: new XTemplate\s*\(\s*['"]([^'"]+\.tpl)['"]
   ```
   - Nếu tìm được **một hoặc nhiều tpl** → đọc tất cả, migrate toàn bộ.
   - Nếu **không tìm được tpl nào** → **DỪNG LẠI**, thông báo cho Dev:
     > "File `{FILE}.php` không sử dụng XTemplate. Mục tiêu chính của skill này là chuyển XTemplate → Smarty. Bạn có muốn tiếp tục không? (Nếu có, hãy cho biết lý do hoặc xác nhận tên tpl cần tạo.)"
     Chờ Dev phản hồi trước khi làm bất kỳ thao tác nào tiếp theo.

3. **Đọc từng template cũ** được xác định ở bước trên:
   `src/themes/admin_default/modules/{MODULE}/{tpl_name}`

4. **Template mới (nếu đã có):** `src/themes/admin_future/modules/{MODULE}/{tpl_name}`

5. **Plugin routing:**
   - `src/includes/plugin/get_module_admin_theme.php`
   - `src/includes/plugin/get_global_admin_theme.php`

6. **Language file module:** `src/modules/{MODULE}/language/vi.php`

7. **Language file global:** `src/includes/language/vi/global.php`

8. **Admin functions:** `src/modules/{MODULE}/admin.functions.php` (nếu tồn tại)

10. **Module js admin_default:** `src/themes/admin_default/js/{MODULE}.js` (nếu tồn tại)

11. **Module js admin_future:** `src/themes/admin_future/js/{MODULE}.js` (nếu tồn tại)
    - **BẮT BUỘC**: đọc toàn bộ file, xác định cấu trúc các block `if (nv_func_name === '...')` hiện có
    - Lập danh sách: handler nào top-level (dùng chung) vs handler nào nằm trong block op cụ thể
    - Các handler thường bị nhốt trong block op khác: `datepicker`, `genpass`, `ajax upload`, `cleargdefault`...
    - Nếu tpl mới cần handler đó: **đánh giá phạm vi sử dụng**:
      - Dùng ở nhiều op → **chuyển ra top-level** (ngoài mọi `if`)
      - Chỉ dùng ở 1-2 op cụ thể → giữ trong block op, **không duplicate**

12. **Một vài template admin_future đã migrate** trong cùng module hoặc module khác để học pattern
    - Ưu tiên đọc template admin_future trong **cùng module** vì chúng dùng chung UI component (group selection, field config, v.v.)
    - Phải follow **đúng** pattern của template đã có — không tự sáng tạo show/hide logic riêng

## Bước 4 — Lập kế hoạch & xác nhận

Trình bày ngắn gọn:
- **Các file sẽ thay đổi** (tạo mới / sửa)
- **Những thay đổi chính** trong PHP (nếu cần)
- **Cấu trúc template mới** (section/block chính)
- **Rủi ro / lưu ý đặc biệt**

**Dừng lại và chờ Dev phản hồi "OK" trước khi viết code.**

## Bước 5 — Thực thi migration

### 5A. Tạo template admin_future

**File:** `src/themes/admin_future/modules/{MODULE}/{tpl_name}.tpl`

Tuân thủ toàn bộ quy tắc trong `docs/knowledge/xtemplate-to-smarty.md` (mục 4, 5, 6). Các điểm hay bị bỏ sót:

- Hằng số: `{$smarty.const.NV_BASE_ADMINURL}` — không dùng `{NV_BASE_ADMINURL}`
- Biến assign từ PHP: viết **HOA** (`{$MODULE_NAME}`, `{$OP}`, `{$CHECKSS}`); biến nội bộ tpl: viết **thường**
- Language: `{$LANG->getModule('key')}` / `{$LANG->getGlobal('key')}`
- Action URL ghép trong tpl — **không** assign URL từ PHP
- CSRF: `<input type="hidden" name="checkss" value="{$CHECKSS}">` trong mọi form POST
- JS: `data-toggle`, `data-tokend="{$CHECKSS}"`, `data-msgconfirm="{$LANG->getModule(...)}"` — không `onclick=`
- Icons: `fa-solid fa-*` (Font Awesome 6), không `fa fa-*`
- Bootstrap 5: `card`, `float-end`, `d-none`, `btn-secondary` — không dùng class BS3


### 5B. Cập nhật PHP controller

Xóa toàn bộ `$xtpl->*`. Pattern chuẩn (xem chi tiết `docs/knowledge/xtemplate-to-smarty.md` mục 3):

```php
$tpl = new \NukeViet\Template\NVSmarty();
$tpl->setTemplateDir(get_module_tpl_dir(basename(__FILE__, '.php') . '.tpl'));

$tpl->assign('LANG', $nv_Lang);
$tpl->assign('MODULE_NAME', $module_name);
$tpl->assign('OP', $op);
$tpl->assign('CHECKSS', csrf_create($module_name . '_' . $op . '_' . $admin_info['admin_id']));
// ... assign thêm data

$contents = $tpl->fetch(basename(__FILE__, '.php') . '.tpl');
```

Lưu ý: vòng lặp collect array assign một lần — không `parse()` từng item. Tất cả biến assign cần có giá trị mặc định.

**Quy tắc nv_jsonOutput:**
- Chỉ dùng các key chuẩn: `status`, `mess`, `redirect`, `refresh`, `input`, `tab`, `warning`, `timeout` — không copy key cũ từ XTemplate code
- Mọi response thành công **phải** có `redirect` (URL không rỗng) hoặc `refresh: true` — không để trống khiến form đứng im
- Khi build URL redirect về trang chính module: **bỏ `op` đi** (op=main là mặc định, không cần khai báo):
  ```php
  // Đúng:
  NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name
  // Sai:
  NV_BASE_ADMINURL . 'index.php?...' . NV_OP_VARIABLE . '=main'
  ```

---

### 5C. Xử lý JavaScript

- **Không** `onclick=`, `onchange=`, `javascript:` trong tpl — sự kiện đặt trong `src/themes/admin_future/js/{MODULE}.js` (tạo nếu chưa có)
- Tham số truyền qua `data-*`; dùng `nvToast()`, `nvConfirm()`, `nvAlert()`
- Form submit: thêm `class="ajax-submit" novalidate` + `<div class="invalid-feedback"></div>` cạnh input
- PHP trả: `nv_jsonOutput(['status' => 'OK'|'error', 'mess' => '...'])` — không `echo json_encode`
- Nếu tpl cũ có JS inline → kiểm tra `src/themes/admin_default/js/{MODULE}.js`, port sang `data-toggle` pattern

**Quy trình chèn code vào module JS admin_future:**

1. Tạo block riêng cho op mới: `if (nv_func_name === '{FILE}') { ... }`
2. Đặt block này **ngang hàng** với các block op khác — không được chèn bên trong block op khác
3. Trước khi chèn, đọc **ít nhất 20 dòng xung quanh** vị trí chèn để xác nhận đang ở đúng cấp
4. Handler đang nằm trong block op khác mà tpl mới cũng cần → **đánh giá**:
   - Nhiều op cùng dùng → **chuyển ra top-level** (trước mọi `if (nv_func_name)`)
   - Chỉ 2 op dùng → giữ nguyên trong block cũ, **thêm logic tương tự vào block `{FILE}`** mà không copy nguyên
   - Không bao giờ duplicate nguyên xi cùng một đoạn code vào nhiều block

> Chi tiết: `docs/knowledge/xtemplate-to-smarty.md` mục 7.

---

### 5D. Cập nhật plugin routing

**Bắt buộc cập nhật CẢ HAI file — không được thiếu file nào:**
- `src/includes/plugin/get_module_admin_theme.php`
- `src/includes/plugin/get_global_admin_theme.php`

Thêm `{FILE}` vào danh sách `op` của module `{MODULE}` trong **từng file**:

```php
// Trước:
if (($module_info['module_file'] ?? '') == '{MODULE}' and in_array($op, ['existing_op1', 'existing_op2'])) {

// Sau:
if (($module_info['module_file'] ?? '') == '{MODULE}' and in_array($op, ['existing_op1', 'existing_op2', '{FILE}'])) {
```

Nếu module chưa có entry, thêm mới trước dòng `return 'admin_default';`:
```php
if (($module_info['module_file'] ?? '') == '{MODULE}' and in_array($op, ['{FILE}'])) {
    return $new_theme;
}
```

---

## Bước 6 — Xóa cache & kiểm tra

```bash
rm -rf src/data/cache/*/*.cache && rm -rf src/data/cache/smarty-compile/*.php
```

Sau đó báo cáo:
- Các file đã tạo/sửa
- URL để kiểm tra: `?lang=vi&{NV_NAME_VARIABLE}={MODULE}&{NV_OP_VARIABLE}={FILE}`
- Những điểm cần Dev kiểm tra thủ công (JS, form submit, phân trang...)

---

## Checklist tự kiểm tra trước khi hoàn thành

**PHP:**
- [ ] Xóa toàn bộ `$xtpl->*`, thay bằng `new NVSmarty()` + `setTemplateDir()` + `fetch()`
- [ ] Vòng lặp collect array, không parse từng item
- [ ] Assign đủ: `LANG`, `MODULE_NAME`, `OP`, `CHECKSS`
- [ ] Tất cả biến assign có giá trị mặc định (tránh undefined key)

**Template:**
- [ ] Không còn XTemplate syntax (`<!-- BEGIN: -->`, `{VAR}` không có `$`)
- [ ] Không còn class Bootstrap 3 (`col-xs-*`, `pull-right`, `panel`, ...)
- [ ] Tất cả hằng dùng `{$smarty.const.*}`
- [ ] Action form ghép trong tpl (không hardcode URL, không assign URL từ PHP)
- [ ] CSRF: `<input type="hidden" name="checkss" value="{$CHECKSS}">` trong mọi form POST
- [ ] Icons dùng Font Awesome 6 (`fa-solid fa-*`)

**PHP — nv_jsonOutput:**
- [ ] Tất cả key dùng đúng chuẩn: `status`, `mess`, `redirect`, `refresh`... (không copy key cũ từ XTemplate)
- [ ] Mọi response thành công có `redirect` (không rỗng) hoặc `refresh: true`
- [ ] URL redirect về trang chính module không có `op=main`

**JavaScript:**
- [ ] Không có JS inline (`onclick=`, `onchange=`, `javascript:`)
- [ ] Sự kiện bắt qua `data-toggle` trong `src/themes/admin_future/js/{MODULE}.js`
- [ ] Handler mới nằm trong block `if (nv_func_name === '{FILE}')` riêng, không lọt vào block op khác
- [ ] Handler cần thiết (datepicker, genpass...) đã được duplicate vào block `{FILE}` nếu chúng đang nằm trong block op khác
- [ ] Form submit dùng `class="ajax-submit"` + PHP trả `nv_jsonOutput([...])`

**Routing & cache:**
- [ ] Đã cập nhật `get_module_admin_theme.php` **VÀ** `get_global_admin_theme.php`
- [ ] Cache đã xóa: `rm -rf src/data/cache/*/*.cache && rm -rf src/data/cache/smarty-compile/*.php`
