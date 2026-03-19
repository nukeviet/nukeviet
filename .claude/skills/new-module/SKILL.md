---
name: new-module
description: Khởi tạo cấu trúc Module chuẩn NukeViet 5. Dùng khi tạo module mới từ đầu.
argument-hint: <tên-module>
disable-model-invocation: true
allowed-tools: Read, Write, Bash, Glob, Grep
---

Khởi tạo cấu trúc Module chuẩn NukeViet 5.

**Tên module:** $ARGUMENTS

Nếu không có tên module, hỏi user trước khi tiếp tục.

## Các bước thực hiện

### 1. Xác nhận tên module
Tên module phải là snake_case (VD: `my_module`, `online_shop`). Nếu user chưa cung cấp hoặc sai format, hỏi lại.

### 2. Đọc skill reference
Đọc `docs/knowledge/module.md` và các file trong `docs/knowledge/examples/module/` để nắm cấu trúc chuẩn trước khi tạo file.

### 3. Tạo cấu trúc thư mục
```bash
MODULE_NAME="$ARGUMENTS"
mkdir -p "src/modules/$MODULE_NAME/admin"
mkdir -p "src/modules/$MODULE_NAME/funcs"
mkdir -p "src/modules/$MODULE_NAME/language"
mkdir -p "src/modules/$MODULE_NAME/blocks"
mkdir -p "src/modules/$MODULE_NAME/Shared"
mkdir -p "src/modules/$MODULE_NAME/Service"
mkdir -p "src/themes/default/modules/$MODULE_NAME"
mkdir -p "src/themes/admin_future/modules/$MODULE_NAME"
```

### 4. Tạo các file cơ bản
Dựa trên examples đã đọc, tạo nội dung cho:
- `modules/$MODULE_NAME/version.php`
- `modules/$MODULE_NAME/functions.php`
- `modules/$MODULE_NAME/global.functions.php`
- `modules/$MODULE_NAME/admin.functions.php`
- `modules/$MODULE_NAME/admin.menu.php`
- `modules/$MODULE_NAME/funcs/main.php` (Frontend — XTemplate)
- `modules/$MODULE_NAME/admin/main.php` (Backend — NVSmarty)
- `modules/$MODULE_NAME/language/vi.php`

### 5. Kiểm tra syntax
```bash
find src/modules/$ARGUMENTS -name "*.php" -type f | xargs -n1 php -l | grep -v "No syntax errors"
```

### 6. Xóa cache
```bash
rm -rf src/data/cache/*/*.cache
rm -rf src/data/cache/smarty-compile/*.php
```

### 7. Báo cáo kết quả
Liệt kê tất cả file đã tạo và hướng dẫn bước tiếp theo (cài module qua Admin > Modules).
