# Ghi chú quá trình chuyển đổi giao diện ngoài site sang future

Tài liệu này hướng dẫn để có thể chạy song song giao diện future, default và các giao diện tùy biến khác song song nhau trong quá trình chuyển đổi trên nguyên tắc:
- theme.php các module ưu tiên load ở giao diện, do vậy cần chép của các module của hệ thống sang các giao diện tùy biến trước khi trộn code mới vào. Vì các module này sẽ bị ghi đè mất bằng file xử lý future dẫn đến lỗi. Các module không phải hệ thống thì không bị ghi đè nên không cần xử lý.
- Toàn bộ js, css, modules, images trong theme default trước thời điểm trộn cần chép hết sang giao diện đích theo nguyên tắc tệp nào có ở đích thì bỏ qua, tệp nào không có thì chép sang. Làm bước này mục đích để giao diện tùy biến là 1 giao diện độc lập hoàn toàn không còn cần gì ở default cũ nữa. Bước này cần làm trước khi trộn NukeViet mới vào.
- Các block nằm trong các module mặc định sẽ bị thay bằng block smarty, do đó tương tự theme.php cũng cần chép nó sang giao diện riêng trước khi trộn code. Các block không nằm trong module mặc định hoặc không có trong gói NukeViet CMS sẽ không bị ảnh hưởng. Chép toàn bộ php, json, ini nếu có.

Các bước trên nguyên tắc trên có thể làm ngay, không cần đợi đến khi phát triển theme future

## Chuẩn bị trước khi trộn code

- Nếu bạn trộn code trên local, hãy đảm bảo CSDL đủ dữ liệu các bảng sau so với trên site: nv5_*_blocks_groups, nv5_setup_language
- Chép tools/default-to-other-theme/update-theme.php vào thư mục gốc của site, chạy https://domain.com/update-theme.php và kiểm tra output không báo lỗi gì.
