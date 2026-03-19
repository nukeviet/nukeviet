---
name: upgrade-module
description: Nâng cấp Module NukeViet lên chuẩn NukeViet 5 (PHP 8.2+, NVSmarty, PSR-4). Dùng khi migrate module cũ.
argument-hint: <modules/ten-module>
disable-model-invocation: true
allowed-tools: Read, Write, Edit, Bash, Glob, Grep
---

Nâng cấp Module NukeViet lên chuẩn NukeViet 5 (PHP 8.2+, NVSmarty, Composer PSR-4).

**Module cần nâng cấp:** $ARGUMENTS

## Các bước thực hiện

### 1. Xác định target
Nếu không có `$ARGUMENTS`, hỏi user. Đọc `version.php` để xác định phiên bản hiện tại.

### 2. Đọc tài liệu nâng cấp
Đọc `docs/knowledge/upgrade.md`.

### 3. Quét các pattern cũ
```bash
grep -rn "XTemplate\|NV_IS_FILE_ADMIN\|mysql_query\|mysql_real_escape_string\|addslashes\|ereg\b\|split\b\|create_function\|\$_GET\b\|\$_POST\b\|\$_REQUEST\b" src/$ARGUMENTS --include="*.php"
```

### 4. Phân tích — lập danh sách thay thế
Liệt kê từng file và từng dòng cần sửa trước khi thực hiện.

### 5. Thực hiện thay thế
- `admin/main.php` → chuyển sang NVSmarty (`$tpl = new \NukeViet\Template\NVSmarty()`)
- Cập nhật security guards theo chuẩn NV5
- Thêm `declare(strict_types=1)` cho class mới
- Cập nhật `version.php`

### 6. Kiểm tra syntax
```bash
find $ARGUMENTS -name "*.php" -type f | xargs -n1 php -l | grep -v "No syntax errors"
```

### 7. Xóa cache
```bash
rm -rf src/data/cache/*/*.cache
rm -rf src/data/cache/smarty-compile/*.php
```

### 8. Báo cáo
Tóm tắt thay đổi và các mục còn cần kiểm tra thủ công.
