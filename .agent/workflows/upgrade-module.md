---
description: Nâng cấp Module lên chuẩn NukeViet 5
---

Lệnh này dùng cấu trúc quy trình nâng cấp file để scan và thay thế các hàm cũ sang các hàm mới. Đảm bảo hỗ trợ Composer PSR-4, NVSmarty, và PHP 8.2+.

Yêu cầu tham số: Tên file module hoặc thư mục nâng cấp `$ARGUMENTS`

1. Nhận diện phiên bản cũ: Tìm phiên bản trong `$ARGUMENTS/version.php`
2. Kích hoạt tìm kiếm các mẫu cũ (NV4) cần loại bỏ:
// turbo
```bash
grep -rn "XTemplate\|NV_IS_FILE_ADMIN\|db_slave" $ARGUMENTS --include="*.php"
```

3. Yêu cầu tôi (AI) tự động thay thế dựa trên log:
   - Các file `admin/main.php` (tâm điểm cần sửa thành `NVSmarty`)
   - Bổ sung `composer.json` nếu module cực kỳ phức tạp.

4. Check lỗi syntax cuối:
// turbo
```bash
find $ARGUMENTS -name "*.php" -type f -exec php -l {} \; | grep "Errors parsing"
```
