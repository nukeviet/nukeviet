# Cài đặt PHPCS toàn cục (Global)

## Bước 1: Chạy lệnh cài đặt Mở Terminal (CMD, PowerShell hoặc Git Bash) và chạy lệnh:

```
composer global require "squizlabs/php_codesniffer=*"
```

## Bước 2: Tìm đường dẫn thư mục Bin của Composer Sau khi cài xong

Tắt Terminal cũ đi và mở một cái mới, gõ:
```
phpcs --version
```

Nếu báo tương tự như sau là thành công, chuyển sang bước 3
```
PHP_CodeSniffer version 4.0.1 (stable) by Squiz and PHPCSStandards
````

Còn lỗi cần cài tiếp, bạn cần biết thư mục chứa file thực thi của Composer. Chạy lệnh:

```
composer global config bin-dir --absolute
```

Thông thường đường dẫn sẽ có dạng:
```
C:\Users\Tên_User\AppData\Roaming\Composer\vendor\bin
```

Thêm vào biến môi trường (Environment Variables) của Windows

- Nhấn phím Windows, gõ "env" và chọn "Edit the system environment variables".
- Nhấn nút "Environment Variables...".
- Ở mục "User variables", tìm dòng Path, chọn nó và nhấn "Edit...".
- Nhấn "New" và dán đường dẫn bạn vừa tìm được ở Bước 2 vào.
- Nhấn OK để đóng tất cả các cửa sổ.

Kiểm tra Tắt Terminal cũ đi và mở một cái mới, gõ:
```
phpcs --version
```
nếu báo tương tự như sau là thành công
```
PHP_CodeSniffer version 4.0.1 (stable) by Squiz and PHPCSStandards
````
## Bước 3: Cài đặt pre-commit

Copy file tests/pre-commit vào từng thư mục .git/hooks của  dự án

```bash
cp tests/pre-commit "./.git/hooks/"
```

## Bước 4: Kiểm tra kại

Dùng bình thường, nếu khi commit không đúng chuẩn sẽ báo lỗi

Thử commit file có các lỗi cơ bản sẽ dừng lại
- Tên file có khoảng cách
- Tên file đặt quá dài 50 ký tự
- Lỗi cấu trúc file php
- Vi phạm quy chuẩn PSR12
