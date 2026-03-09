---
description: Khởi tạo tệp cơ sở (scaffold) cho một NukeViet Hook (Plugin) trong module
---

# Workflow: `/new-hook`

Tạo nhanh file hook chuẩn cho một module NukeViet 5. Hook cho phép module lắng nghe sự kiện từ module khác hoặc hệ thống mà không cần sửa code core.

## 1. Yêu cầu nhập liệu

Hỏi người dùng:
1. **Module nhận sự kiện** (module của bạn — module chứa file hook): Ví dụ `news`, `tenmodule`
2. **Module phát sự kiện** (module gọi `nv_apply_hook()`): Ví dụ `users`, `page`, hoặc để trống `''` = hệ thống
3. **Tên Tag (event)**: Ví dụ `user_delete`, `before_detail_theme`, `change_site_buffer`
4. **Tên file hook** (không có đuôi `.php`): Ví dụ `handle_user_delete`, `modify_page_detail`

## 2. Tạo thư mục và file rỗng

// turbo
```bash
# Sửa các biến này theo thông tin nhập
RECEIVE_MODULE="tenmodule"
HOOK_FILE="handle_user_delete"

mkdir -p "modules/$RECEIVE_MODULE/hooks"
touch "modules/$RECEIVE_MODULE/hooks/$HOOK_FILE.php"
echo "" > "modules/$RECEIVE_MODULE/hooks/$HOOK_FILE.php"
```

## 3. Tạo nội dung file hook

Ngay sau khi bash chạy xong, Agent load `.agent/skills/nukeviet-hook/SKILL.md` để lấy template chuẩn và điền vào file vừa tạo với:

- Guard `if (!defined('NV_MAINFILE'))`
- `$callback` closure với signature đúng `($args, $from_data, $receive_data)`
- Logic xử lý phù hợp với `$tag` được yêu cầu (user_delete → xóa dữ liệu liên quan, before_detail_theme → chỉnh sửa content array, v.v.)
- Gọi `nv_add_hook($module_name, 'tag', $priority, $callback, $hook_module, $pid)` ở **cuối file**

## 4. Nhắc nhở sau khi tạo

- File đã tạo tại `modules/{RECEIVE_MODULE}/hooks/{HOOK_FILE}.php`
- Phải đăng ký hook trong Admin: **Admin → Công cụ web → Plugin → Thêm plugin mới**
  - Module nhận sự kiện từ: chọn module phát (`$module_name` / hệ thống)
  - Khu vực (tag): nhập đúng tên tag
  - File plugin: nhập tên file (không có đuôi `.php`)
  - Module nhận dữ liệu: chọn module của bạn
- Sau khi lưu → **Làm sạch cache** để `config_global.php` được cập nhật
