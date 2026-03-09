---
description: Khởi tạo cấu trúc Module chuẩn cho NukeViet 5
---

Quy trình này tự động tạo nhánh thư mục chuẩn, file core, PHP 8.2+ methods và khai báo Composer cho module mới.

1. Nhập tên module bạn muốn tạo (chữ thường, viết liền không dấu gạch ngang/dưới):
// turbo
2. Chạy script để tạo thư mục module mới:
```bash
mkdir -p modules/[ten_module]/admin
mkdir -p modules/[ten_module]/funcs
mkdir -p modules/[ten_module]/language
mkdir -p modules/[ten_module]/blocks
mkdir -p themes/default/modules/[ten_module]
mkdir -p themes/admin_future/modules/[ten_module]
```

3. Gợi ý tạo file `$ARGUMENTS` (thay `[ten_module]` bằng tên ở trên):
- `modules/[ten_module]/version.php`: Định nghĩa phiên bản 5.0.00
- `modules/[ten_module]/functions.php`: Auto-loading hooks và init
- `modules/[ten_module]/admin.menu.php`: Mảng `allow_func` và `submenu`
- `modules/[ten_module]/admin.functions.php`: File admin global hook
- `modules/[ten_module]/funcs/main.php`: Frontend xử lý với XTemplate
- `modules/[ten_module]/admin/main.php`: Backend xử lý với NVSmarty

4. Tạo cấu trúc namespace PSR-4 ngoài root module nếu bạn có viết Logic/Data Class phức tạp:
// turbo
```bash
mkdir -p modules/[ten_module]/Shared
mkdir -p modules/[ten_module]/Service
```
