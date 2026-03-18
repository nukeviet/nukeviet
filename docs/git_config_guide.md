# Hướng dẫn Cấu hình Git cho Lập trình viên NukeViet 5.0

Tài liệu này hướng dẫn cách cấu hình Git để tránh xung đột các tệp tin cấu hình hệ thống khi làm việc nhóm hoặc triển khai trên nhiều môi trường khác nhau.

## 1. Vấn đề các tệp tin cấu hình cục bộ

Trong NukeViet 5.0, một số tệp tin chứa thông số kết nối cơ sở dữ liệu, cấu hình máy chủ hoặc quy tắc Rewrite riêng biệt cho từng môi trường Linux/Windows (XAMPP). Nếu các tệp này đã được đẩy lên kho mã nguồn (Repository) và sau đó bạn chỉnh sửa cục bộ, chúng sẽ gây xung đột (conflict) mỗi khi bạn `git pull` hoặc `git push`.

Các tệp tin nhạy cảm cần quản lý:
- `src/data/config/config_global.php`: Cấu hình hệ thống (Database, Site URL...).
- `src/data/config/robots.php`: Cấu hình cho robot tìm kiếm.
- `src/.htaccess`: Cấu hình Rewrite URL (thường khác nhau giữa Server thật và Localhost).

> [!NOTE]
> Riêng tệp `src/config.php` đã được liệt kê trong `.gitignore` mặc định của NukeViet 5.0, nên bạn không cần thực hiện hướng dẫn này cho nó.

## 2. Giải pháp: Sử dụng `--assume-unchanged`

Chúng ta sử dụng lệnh của Git để đánh dấu là "không quan tâm đến các thay đổi cục bộ" trên những tệp này.

### Bước 1: Mở Git Bash
Di chuyển vào thư mục gốc của dự án NukeViet (`nukeviet5.0`).

### Bước 2: Thực thi các lệnh bảo vệ
Chạy lần lượt các lệnh này để Git bỏ qua các thay đổi tại máy cục bộ của bạn:

```bash
# Bỏ qua thay đổi cho cấu hình toàn cục
git update-index --assume-unchanged src/data/config/config_global.php

# Bỏ qua thay đổi cho cấu hình robots
git update-index --assume-unchanged src/data/config/robots.php

# Bỏ qua thay đổi cho file .htaccess
git update-index --assume-unchanged src/.htaccess
```

## 3. Cách hoàn tác (Khi muốn đẩy thay đổi lên lại)

Nếu vì lý do nào đó mà bạn muốn Git ghi nhận lại thay đổi của các tệp này để đẩy lên Server chính, hãy dùng lệnh:

```bash
git update-index --no-assume-unchanged src/data/config/config_global.php
git update-index --no-assume-unchanged src/data/config/robots.php
git update-index --no-assume-unchanged src/.htaccess
```

## 4. Kiểm tra danh sách các tệp đang được "bảo vệ"

Để xem danh sách các tệp bạn đang đặt ở chế độ bỏ qua thay đổi cục bộ, sử dụng lệnh:

```bash
git ls-files -v | grep "^h"
```
*(Ký tự `h` ở đầu dòng biểu thị tệp đang được bỏ qua thay đổi)*.
