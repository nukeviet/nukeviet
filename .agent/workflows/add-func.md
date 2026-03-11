---
description: Thêm function mới cho Module (Frontend / Admin)
---

Lệnh này giúp tự động kiểm tra, khai báo và gen cấu trúc cho một chức năng mới trong một module đang tồn tại trên chuẩn NukeViet 5.

**Yêu cầu:** Gõ lệnh với thư mục cụ thể, VD: `/add-func modules/news/admin/test.php` hoặc `/add-func modules/news/funcs/test.php`

1. Nhận diện Admin thay Frontend:
- Func thuộc `admin/`: Dùng Object `NVSmarty` (`$tpl = new \NukeViet\Template\NVSmarty()`). Bổ sung mảng `submenu` tại file `admin.menu.php`.
- Func thuộc `funcs/`: Dùng `XTemplate` truyền thống. Bổ sung tên vào mảng danh sách chạy định tuyến tại file `version.php`.

2. Kiểm tra file tham số được truyền vào để tránh ghi đè func đang hoạt động.
3. Tạo file language khai báo `$lang_module[]` vào folder language tương ứng.

// turbo
4. Liệt kê lại các file vừa thay đổi hoặc kiểm tra lỗi Syntax ngay lập tức:
```bash
php -l [Đường dẫn file vừa tạo]
```
