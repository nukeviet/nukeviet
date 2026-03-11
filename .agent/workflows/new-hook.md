---
description: Khởi tạo tệp cơ sở (scaffold) cho một NukeViet Hook (Plugin) trong module
---

# Workflow: `/new-hook`

Tạo nhanh file hook chuẩn cho một module NukeViet 5. Hook cho phép module lắng nghe sự kiện từ module khác hoặc hệ thống mà không cần sửa code core.

## 1. Thu thập thông tin từ user
Thu thập 4 thông tin cơ bản:
1. **Module nhận sự kiện** (module tạo hook): Ví dụ `news`, `ten_module`
2. **Module phát sự kiện** (module gọi `nv_apply_hook`): Ví dụ `users`, `page`, hoặc để trống `''` (hệ thống)
3. **Tên Tag (event)**: Ví dụ `user_delete`, `before_detail_theme`
4. **Tên file hook** (không đuôi `.php`): Ví dụ `handle_user_delete`

## 2. Tạo thư mục và file rỗng

// turbo
```bash
# LƯU Ý CHO AI: Sửa biến RECEIVE_MODULE và HOOK_FILE theo thông tin User nhập trước khi chạy!
RECEIVE_MODULE="ten_module"
HOOK_FILE="handle_user_delete"

mkdir -p "modules/$RECEIVE_MODULE/hooks"
touch "modules/$RECEIVE_MODULE/hooks/$HOOK_FILE.php"
```

## 3. Tạo nội dung file hook
Tải mẫu code chuẩn từ `.agent/skills/nukeviet-hook/examples/TemplateHook.php`.
Sử dụng công cụ `write_to_file` để đắp nội dung vào file vừa tạo:
- Khai báo logic xử lý với `$tag` tương ứng.
- Phải gọi `nv_add_hook()` ở cuối file.

## 4. Nhắc nhở sau khi tạo
- Báo cáo đường dẫn file: `modules/{RECEIVE_MODULE}/hooks/{HOOK_FILE}.php`.
- Nhắc user đăng ký hook trong Admin: **Admin → Công cụ web → Plugin → Thêm plugin mới**. Cảnh báo phải xóa cache sau khi làm.
