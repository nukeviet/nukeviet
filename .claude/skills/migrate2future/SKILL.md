---
name: migrate2future
description: Chuyển giao diện một khu vực ngoài site của module từ default (XTemplate) sang future (Smarty/Bootstrap 5).
argument-hint: <module/filename>
disable-model-invocation: false
allowed-tools: Read, Grep, Glob, Edit, Write, Bash
---

Chuyển giao diện default → future cho một khu vực ngoài site của module NukeViet 5.

**Mục tiêu:** `$ARGUMENTS` (format: `{module}/{filename}`)

Ví dụ: `/migrate2future news/detail` → migrate:
- Hàm theme được gọi từ `src/modules/news/funcs/detail.php`
- Hàm theme đó nằm trong `src/modules/news/theme.php`
- Các template được khai báo trong hàm theme tại `src/themes/default/modules/news/`

## Quy tắc chung áp dụng xuyên suốt

- **Comment code**: Dùng `// Comment` — sau `//` có một dấu cách, chữ cái đầu viết hoa. Không dùng `//====` hay `//----` kiểu separator.
- **Tương thích ngược với theme khác**: Chữ ký hàm trong `theme.php` **KHÔNG ĐƯỢC THAY ĐỔI** — các theme bên ngoài (mobile, custom) gọi cùng hàm đó.

## Bước 1 — Xác định mục tiêu

Từ `$ARGUMENTS` split theo `/` lấy:
- `MODULE` = phần trước `/` đầu tiên (VD: `news`)
- `FILE` = phần sau `/` đầu tiên (VD: `detail`)

Nếu `$ARGUMENTS` trống, không có `/` hoặc sai cú pháp, hỏi lại user theo format `{module}/{filename}`.

## Bước 2 — Đọc tài liệu bắt buộc

Đọc các tài liệu sau trước khi bắt đầu:
- `docs/knowledge/xtemplate-to-smarty.md` — **BẮT BUỘC**: toàn bộ pattern migration, syntax, JS
- `docs/knowledge/module.md`
- `docs/knowledge/theme.md`
- `docs/knowledge/security.md`

## Bước 3 — Thu thập & phân tích hiện trạng

Đọc theo thứ tự, **BẮT BUỘC đọc hết trước khi viết bất kỳ code nào**:

1. **PHP controller:** `src/modules/{MODULE}/funcs/{FILE}.php`
   - Tìm lời gọi hàm theme — thường là dòng cuối trước `echo nv_site_theme(...)`.
   - Lấy **tên hàm** (VD: `detail_theme(...)`) và **danh sách tham số** thực tế được truyền.

2. **Hàm theme trong `theme.php`:** `src/modules/{MODULE}/theme.php`
   - Chỉ đọc và sửa **đúng hàm** xác định ở bước trên — không đụng đến các hàm khác.
   - Grep tìm tất cả khai báo `new XTemplate(` bên trong hàm đó:
     ```
     pattern: new XTemplate\s*\(\s*['"]([^'"]+\.tpl)['"]
     ```
   - Nếu tìm được **một hoặc nhiều tpl** → đọc tất cả, migrate toàn bộ.
   - Nếu **không tìm được tpl nào** → **DỪNG LẠI**, thông báo cho Dev:
     > "Hàm `{function_name}` trong `theme.php` không sử dụng XTemplate. Mục tiêu chính của skill này là chuyển XTemplate → Smarty. Bạn có muốn tiếp tục không?"
     Chờ Dev phản hồi trước khi làm bất kỳ thao tác nào tiếp theo.

3. **Đọc từng template cũ** được xác định ở bước trên:
   `src/themes/default/modules/{MODULE}/{tpl_name}`

4. **Template mới (nếu đã có):** `src/themes/future/modules/{MODULE}/{tpl_name}`

5. **Language file module:** `src/modules/{MODULE}/language/vi.php`

6. **Language file global:** `src/includes/language/vi/global.php`

7. **Module js default:** `src/themes/default/js/{MODULE}.js` (nếu tồn tại)

8. **Module js future:** `src/themes/future/js/{MODULE}.js` (nếu tồn tại)
   - Đọc toàn bộ file, xác định cấu trúc và các handler hiện có.

9. **Một vài template future đã migrate** trong cùng module hoặc module khác để học pattern
   - Ưu tiên đọc template future trong **cùng module** vì chúng dùng chung UI component.
   - Phải follow **đúng** pattern của template đã có — không tự sáng tạo logic riêng.

## Bước 4 — Lập kế hoạch & xác nhận

Trình bày ngắn gọn:
- **Tên hàm** trong `theme.php` sẽ sửa
- **Các file sẽ thay đổi** (tạo mới / sửa)
- **Cấu trúc template mới** (section/block chính)
- **Rủi ro / lưu ý đặc biệt**

**Dừng lại và chờ Dev phản hồi "OK" trước khi viết code.**

## Bước 5 — Thực thi migration

### 5A. Tạo template future

**File:** `src/themes/future/modules/{MODULE}/{tpl_name}.tpl`

> Routing tự động qua `get_module_tpl_dir()`: hàm này tìm template theo thứ tự ưu tiên từ theme hiện tại → theme default → `NV_DEFAULT_SITE_THEME`. **Không cần thêm plugin routing.**

Tuân thủ toàn bộ quy tắc trong `docs/knowledge/xtemplate-to-smarty.md` (mục 4, 5, 6). Các điểm hay bị bỏ sót:

- Hằng số: `{$smarty.const.NV_BASE_SITEURL}`, `{$smarty.const.NV_BASE_ADMINURL}` — không dùng dạng không có `$`
- Biến assign từ PHP: viết **HOA** (`{$MODULE_NAME}`, `{$LANG}`, `{$DATA}`); biến nội bộ tpl: viết **thường**
- Language: `{$LANG->getModule('key')}` / `{$LANG->getGlobal('key')}`
- Icons: `fa-solid fa-*` (Font Awesome 6), không `fa fa-*`
- Bootstrap 5: `float-end`, `d-none`, `ms-auto` — không dùng class BS3 (`pull-right`, `hidden`, `col-xs-*`)
- **Modifier Smarty** thường dùng trong frontend (cần `registerPlugin` trong PHP):
  - `{$timestamp|ddate}` → `nv_date_format()`
  - `{$timestamp|ddatetime}` → `nv_datetime_format()`
  - `{$number|dnumber}` → `nv_number_format()`
- **CSRF** chỉ dùng khi tpl có form POST thực sự (VD: bình luận, đăng ký). Không thêm CSRF vào tpl chỉ hiển thị nội dung.

### 5B. Cập nhật hàm theme trong `theme.php`

**Chỉ sửa bên trong thân hàm — KHÔNG ĐƯỢC đổi tên hàm hoặc danh sách tham số.**

Xóa toàn bộ `$xtpl->*`. Pattern chuẩn:

```php
function ten_ham_cu($param1, $param2, ...)  // <-- GIỮ NGUYÊN chữ ký hàm
{
   global $module_name, $nv_Lang, /* ... */;

   $tpl = new \NukeViet\Template\NVSmarty();
   $tpl->setTemplateDir(get_module_tpl_dir('tpl_name.tpl'));
   // Đăng ký modifier nếu tpl dùng filter date/number:
   $tpl->registerPlugin('modifier', 'ddate', 'nv_date_format');
   $tpl->registerPlugin('modifier', 'ddatetime', 'nv_datetime_format');
   $tpl->registerPlugin('modifier', 'dnumber', 'nv_number_format');

   $tpl->assign('LANG', $nv_Lang);
   $tpl->assign('MODULE_NAME', $module_name);
   // ... assign thêm data

   return $tpl->fetch('tpl_name.tpl');
}
```

Lưu ý: collect array rồi assign một lần — không `parse()` từng item. Tất cả biến assign cần có giá trị mặc định.

**Nguyên tắc assign mảng — không assign từng phần tử rời:**

Khi cần truyền nhiều giá trị từ cùng một mảng PHP, assign **nguyên mảng** thay vì từng phần tử rời. Template truy cập qua `{$VARNAME.key}`.

```php
// Sai — assign từng phần tử rời:
$tpl->assign('IMGWIDTH', $module_config[$module_name]['homewidth']);
$tpl->assign('IMGHEIGHT', $module_config[$module_name]['homeheight']);

// Đúng — assign nguyên mảng:
$tpl->assign('MCONFIG', $module_config[$module_name]);
// Template dùng: {$MCONFIG.homewidth}, {$MCONFIG.homeheight}
```

**Bảng mapping tên biến assign chuẩn:**

| Biến PHP | Tên assign | Dùng trong tpl |
|---|---|---|
| `$global_config` | `GCONFIG` | `{$GCONFIG.key}` |
| `$module_config[$module_name]` | `MCONFIG` | `{$MCONFIG.key}` |

**Các quy tắc PHP bổ sung khi migrate:**
- **`checked`/`selected`**: không tạo chuỗi ` checked="checked"` hay ` selected="selected"` từ PHP — chuyển logic sang `{if}` trong tpl
- **`PDOException`**: đổi thành `Throwable` trong catch nếu gặp; không tự thêm try-catch mới
- **`nv_date()`**: đổi thành `nv_datetime_format()` (có giờ) hoặc `nv_date_format()` (chỉ ngày) — hoặc dùng modifier trong tpl

---

### 5C. Xử lý JavaScript

- **Không** `onclick=`, `onchange=`, `javascript:` trong tpl — sự kiện đặt trong `src/themes/future/js/{MODULE}.js` (tạo nếu chưa có)
- Tham số truyền qua `data-*`
- Nếu tpl cũ có JS inline → port sang `data-toggle` / event delegation trong `src/themes/future/js/{MODULE}.js`
- Dùng `let`/`const` thay vì `var`
- Nếu không có thay đổi gì JS: **không** thêm comment vào file
- Kiểm tra cú pháp khi port JS từ tpl: tpl có thể chứa thẻ HTML không hợp lệ gây lỗi JS

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

**PHP (theme.php):**
- [ ] Chữ ký hàm (tên + tham số) **không thay đổi** so với bản gốc
- [ ] Xóa toàn bộ `$xtpl->*`, thay bằng `new NVSmarty()` + `setTemplateDir()` + `fetch()`
- [ ] `registerPlugin` đã thêm cho modifier `ddate`, `ddatetime`, `dnumber` nếu tpl dùng
- [ ] Collect array, không parse từng item
- [ ] Assign đủ: `LANG` (dùng `$nv_Lang`), `MODULE_NAME`
- [ ] Assign nguyên mảng thay vì từng phần tử rời: `$module_config[$module_name]` → `MCONFIG`, `$global_config` → `GCONFIG`
- [ ] Tất cả biến assign có giá trị mặc định (tránh undefined key)
- [ ] Không tạo chuỗi `checked="checked"` / `selected="selected"` từ PHP
- [ ] `PDOException` đã đổi thành `Throwable` (nếu có)
- [ ] `nv_date()` đã đổi thành `nv_datetime_format()` / `nv_date_format()` (nếu có)

**Template:**
- [ ] Không còn XTemplate syntax (`<!-- BEGIN: -->`, `{VAR}` không có `$`)
- [ ] Không còn class Bootstrap 3 (`col-xs-*`, `pull-right`, `panel`, `hidden`, ...)
- [ ] Tất cả hằng dùng `{$smarty.const.*}`
- [ ] Icons dùng Font Awesome 6 (`fa-solid fa-*`)
- [ ] CSRF chỉ có trong tpl có form POST thực sự; không thêm thừa vào tpl chỉ hiển thị

**JavaScript:**
- [ ] Không có JS inline (`onclick=`, `onchange=`, `javascript:`)
- [ ] Sự kiện bắt qua `data-toggle` / event delegation trong `src/themes/future/js/{MODULE}.js`
- [ ] Dùng `let`/`const`, không dùng `var`; cú pháp JS hợp lệ sau khi port từ tpl

**Routing & cache:**
- [ ] **Không** cần sửa plugin routing — `get_module_tpl_dir()` tự xử lý
- [ ] Cache đã xóa: `rm -rf src/data/cache/*/*.cache && rm -rf src/data/cache/smarty-compile/*.php`
