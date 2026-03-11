---
description: Nâng cấp Module lên chuẩn NukeViet 5
---

Lệnh này dùng cấu trúc quy trình nâng cấp file để scan và thay thế các hàm cũ sang các hàm mới. Đảm bảo hỗ trợ Composer PSR-4, NVSmarty, và PHP 8.2+.

Yêu cầu tham số: Tên file module hoặc thư mục nâng cấp `$ARGUMENTS`

## 1. Xác định target
Nhận diện đường dẫn module cần nâng cấp (Ví dụ: `modules/news`). Tìm và đọc phiên bản trong `version.php`.

## 2. Tìm kiếm hàm cũ cần thay thế
// turbo
```bash
# LƯU Ý CHO AI: Sửa biến TARGET theo yêu cầu user
TARGET="modules/news"
grep -rn "XTemplate\|NV_IS_FILE_ADMIN\|db_slave" $TARGET --include="*.php"
```

## 3. Tự động thay thế
AI tự động phân tích log và thay thế:
- Chuyển `admin/main.php` sang `NVSmarty`.
- Bổ sung `composer.json` nếu module nhiều vendor.

## 4. Check syntax
// turbo
```bash
find $TARGET -name "*.php" -type f -exec php -l {} \; | grep "Errors parsing"
```
