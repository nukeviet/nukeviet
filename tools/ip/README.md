# Công cụ tạo thư viện IP cho NukeViet

**Yêu cầu:**

Cài đặt phpspreadsheet:

```
composer require phpoffice/phpspreadsheet
```

**Sử dụng:**

Xóa thư mục release nếu muốn tạo lại dữ liệu. Cập nhật dữ liệu mới nhất vào `libs/ip` và chạy:

`php ipv4.php` => Tạo thư viện IPv4.

`php ipv6.php` => Tạo thư viện IPv6.
