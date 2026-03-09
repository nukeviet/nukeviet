---
description: Chạy Audit MR / Lint Code NukeViet 5
---

Review Code, chạy Codeception và kiểm tra chuẩn Coding NukeViet.
Bạn sử dụng `$ARGUMENTS` để trỏ vào tên file/folder (VD: `/review-mr modules/news/`).

1. Quét PHP Syntax Errors:
// turbo
```bash
find $ARGUMENTS -name "*.php" -type f -exec php -l {} \; | grep "Errors parsing"
```

2. Kiểm tra lại chuẩn Coding Standard NukeViet bằng PHP CodeSniffer:
// turbo
```bash
php vendor/bin/phpcs --standard=PSR12 $ARGUMENTS
```

3. Tiến hành kiểm tra PSR-4 và NukeViet Namespace Conventions như `NukeViet\Module\...` trong cấu trúc.
4. AI tạo ra kết quả kiểm tra Audit tóm tắt vào Artifact, chặn Merge nếu không đạt.
