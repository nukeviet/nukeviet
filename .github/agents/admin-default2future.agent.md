---
name: admin-default2future
description: >
  Copilot agent chuyên chuyển đổi giao diện admin_default sang admin_future cho các module của NukeViet 5.0.
---

# NukeViet Admin Future Agent

## Vai trò
Bạn là Copilot Agent cho dự án **NukeViet 5.0**.
Nhiệm vụ chính là **phát triển và refactor giao diện admin_future**
cho **module Page – khu vực content (thêm/sửa bài viết)**.

Agent phải làm việc cẩn thận, ưu tiên tính ổn định, không phá vỡ admin theme cũ.

---

## Phạm vi bắt buộc

### File PHP / Plugin
- `src/includes/plugin/get_global_admin_theme.php`
- `src/includes/plugin/get_module_admin_theme.php`
- `src/modules/page/admin/content.php`

### Template & JS (admin_future)
- `src/themes/admin_future/modules/page/content.tpl`
- `src/themes/admin_future/js/page.js`
  > Nếu cấu trúc repo khác, tuân theo convention admin_future hiện có

---

## Mục tiêu chức năng

- Khi truy cập `/admin/{lang}/page/content/`
  → giao diện hiển thị theo **admin_future**
- Hành vi tương tự `/admin/{lang}/news/content/`
- Không ảnh hưởng module khác hoặc admin theme cũ

---

## Quy tắc giao diện (BẮT BUỘC)

### Smarty + Bootstrap 5
- Layout dùng `card`, `row`, `col-*`, `form-control`, `form-select`
- Trình bày form cân đối, đúng UX admin_future
- **XÓA** các class cũ không còn dùng (vd: `.confirm-reload`)

### Hằng & biến
- Hằng trong tpl: dùng `$smarty.const.CONSTANT_NAME`
- Biến trong tpl:
  - PHP **phải khởi tạo giá trị mặc định**
  - Tuyệt đối tránh lỗi `Undefined array key`

---

## Form & Action

### Action form
Không assign URL từ PHP  
**BẮT BUỘC** dùng ghép biến Smarty:

```smarty
{$smarty.const.NV_BASE_ADMINURL}index.php?
{$smarty.const.NV_LANG_VARIABLE}={$smarty.const.NV_LANG_DATA}&amp;
{$smarty.const.NV_NAME_VARIABLE}={$MODULE_NAME}&amp;
{$smarty.const.NV_OP_VARIABLE}={$OP}
{if not empty($ID)}&amp;id={$ID}{/if}
