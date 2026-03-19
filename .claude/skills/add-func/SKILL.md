---
name: add-func
description: Thêm function mới cho Module NukeViet 5 (Frontend hoặc Admin). Dùng khi cần scaffold một op/func mới trong module đang tồn tại.
argument-hint: <modules/ten-module/admin/func.php|modules/ten-module/funcs/func.php>
disable-model-invocation: true
allowed-tools: Read, Write, Edit, Bash, Glob, Grep
---

Thêm một function mới cho Module NukeViet 5 (Frontend hoặc Admin).

**Đường dẫn file đích:** $ARGUMENTS

Ví dụ: `/add-func modules/news/admin/statistic.php` hoặc `/add-func modules/news/funcs/category.php`

## Các bước thực hiện

### 1. Xác định loại function
Từ đường dẫn `$ARGUMENTS`:
- Chứa `admin/` → **Admin function** (NVSmarty, cập nhật `admin.menu.php`)
- Chứa `funcs/` → **Frontend function** (XTemplate, khai báo trong `version.php`)

Nếu chưa có đường dẫn, hỏi user.

### 2. Kiểm tra file đã tồn tại
Nếu file đã tồn tại và đang hoạt động → cảnh báo, hỏi xác nhận trước khi ghi đè.

### 3. Đọc skill reference
Đọc `docs/knowledge/module.md` và examples tương ứng:
- Admin func: `admin.main.php`, `admin.functions.php`, `admin.menu.php`
- Frontend func: `funcs.main.php`, `functions.php`

### 4. Tạo file function
Tạo file tại `$ARGUMENTS` với:
- Security guard đúng loại (`NV_IS_FILE_ADMIN` hoặc `NV_IS_MOD_*`)
- Logic placeholder có comment rõ ràng

### 5. Cập nhật khai báo
- **Admin:** Bổ sung entry vào `$submenu` trong `admin.menu.php`
- **Frontend:** Bổ sung tên op vào mảng routing trong `version.php`

### 6. Tạo/cập nhật file language
Thêm key `$lang_module[]` cần thiết vào `modules/{module}/language/vi.php`.

### 7. Kiểm tra syntax
```bash
# Thêm prefix src/ nếu $ARGUMENTS không bắt đầu bằng src/
php -l src/$ARGUMENTS
```

### 8. Xóa cache
```bash
rm -rf src/data/cache/*/*.cache
rm -rf src/data/cache/smarty-compile/*.php
```

### 9. Báo cáo kết quả
- File đã tạo: `src/$ARGUMENTS`
- Các file đã cập nhật: `admin.menu.php` hoặc `version.php`, `language/vi.php`
- Hướng dẫn bước tiếp theo: URL test (`?lang=vi&nv={module}&op={func}`) hoặc Admin menu
