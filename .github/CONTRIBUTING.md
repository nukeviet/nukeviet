# Quy định thông báo lỗi

Khi báo lỗi bạn vui lòng cung cấp các thông tin sau để chúng tôi có thể xây dựng môi trường thử nghiệm giống của bạn để kiểm tra. Các thông tin này rất cần thiết trong quá trình đội code kiểm tra và sửa lỗi, đề nghị thành viên cung cấp đầy đủ thông tin để việc sửa lỗi được nhanh chóng.

1. Tiêu đề thông báo lỗi ngắn gọn, xúc tích, đảm bảo tóm tắt được nội dung.

2. Mô tả chi tiết lỗi gặp phải, quá trình hoặc thao tác dẫn đến lỗi.Việc mô tả quá trình dẫn đến lỗi đặc biệt quan trọng vì nhiều khi lỗi chỉ xảy ra đúng với một số thao tác nhất định màvới các thao tác khác thì hệ thống không bị lỗi. Ví dụ trường hợp này: https://github.com/nukeviet/nukeviet/issues/1178

3. Cung cấp thông tin máy chủ

* Nếu xác định lỗi từ code, vui lòng cung cấp thêm các thông tin về WebServer và trình duyệt

> - Phiên bản NukeViet: (Ví dụ NukeViet 4.0.21 hoặc NukeViet 2574f2bc8b6f23f9fcfefc021554034e33fbc695 - là chuỗi xác định commit trên kho code)
> - Môi trường thử Nghiệm: Localhost/ hosting
> - Hệ điều hành: (Ví dụ: Ubuntu 13.10 32-bit)
> - Trình duyệt web: (Ví dụ: Mozilla Firefox 27.0)
> - Máy chủ web: (Ví dụ: XAMPP for Linux 1.8.1 )

Nếu NukeViet cài trên hosting (website đang chạy thực tế) cần có thêm thông tin
> + Công cụ quản lý hosting: (Ví dụ: Cpanel/ Vista Panel/ Parallels Plesk/ DirectAdmin/ Kloxo...)
> + Các thông tin khác về phần mềm webserver, PHP và cơ sở dữ liệu: (Ví dụ: Apache 2.4.7, MySQL 5.5.36, PHP 5.4.25)

 
* Nếu xác định lỗi từ giao diện, vui lòng cung cấp thêm thông tin về  Tên gói giao diện (Chỉ kiểm tra đối với giao diện tích hợp sẵn trong source NukeViet)
 
* Nếu là các lỗi nhỏ, cơ bản, dễ xác định lỗi... hay các câu hỏi, đề xuất, góp ý... thì không cần cung cấp thông tin về máy chủ, giao diện tại phần 2, 3.
