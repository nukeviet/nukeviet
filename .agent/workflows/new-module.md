---
description: Khởi tạo cấu trúc Module chuẩn cho NukeViet 5
---

Quy trình này tự động tạo nhánh thư mục chuẩn, file core, PHP 8.2+ methods và khai báo Composer cho module mới.

## 1. Thu thập tên module
Yêu cầu user cung cấp tên module mong muốn (ví dụ: `my_module`).

## 2. Tạo cấu trúc thư mục module
// turbo
```bash
# LƯU Ý CHO AI: Sửa biến MODULE_NAME theo user nhập trước khi chạy command
MODULE_NAME="my_module"

mkdir -p "modules/$MODULE_NAME/admin"
mkdir -p "modules/$MODULE_NAME/funcs"
mkdir -p "modules/$MODULE_NAME/language"
mkdir -p "modules/$MODULE_NAME/blocks"
mkdir -p "themes/default/modules/$MODULE_NAME"
mkdir -p "themes/admin_future/modules/$MODULE_NAME"
mkdir -p "modules/$MODULE_NAME/Shared"
mkdir -p "modules/$MODULE_NAME/Service"
```

## 3. Tạo các file cơ bản
Đọc cấu trúc và mẫu file từ skill `.agent/skills/nukeviet-module/SKILL.md` và thư mục `examples/` để dùng `write_to_file` sinh nội dung ban đầu cho:
- `modules/$MODULE_NAME/version.php`
- `modules/$MODULE_NAME/functions.php`
- `modules/$MODULE_NAME/admin.menu.php`
- `modules/$MODULE_NAME/admin.functions.php`
- `modules/$MODULE_NAME/funcs/main.php` (Frontend xử lý với XTemplate)
- `modules/$MODULE_NAME/admin/main.php` (Backend xử lý với NVSmarty)
