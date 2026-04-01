# Chiến lược Mở rộng quy mô (Scaling) ứng dụng NukeViet 5.x

Hệ thống NukeViet được thiết kế với kiến trúc module hóa và hỗ trợ đa dạng các driver cache (Files, Memcached, Redis), giúp nó có khả năng mở rộng linh hoạt tùy theo ngân sách và lưu lượng truy cập.

---

## 1. Cấp độ 1: Shared Hosting "Siêu rẻ" ($1/tháng)

Đầu tư thấp nhất dành cho các website mới khởi tạo hoặc các dự án nhỏ (Traffic dưới 1,000 lượt truy cập/ngày).

*   **Đặc điểm:** Tài nguyên CPU/RAM bị giới hạn bởi nhà cung cấp Hosting.
*   **Chiến lược NukeViet:**
    *   **Bật Cache tối đa:** Truy cập Quản trị -> Cấu hình chung -> Thiết lập bộ nhớ đệm. Sử dụng driver `Files`.
    *   **Tối ưu Theme:** Bật tính năng nén (Gzip) và gộp/nén CSS/Javascript trong cấu hình Theme để giảm số lượng request.
    *   **Hạn chế Cron:** Nếu hosting yếu, hãy chuyển cấu hình `run_cron` từ truy cập website sang `Trình lập lịch của hệ thống` (System Cron) để không làm chậm người dùng.
    *   **Dọn dẹp log:** Định kỳ xóa các bản ghi truy cập thừa trong CSDL.

---

## 2. Cấp độ 2: Chuyển lên VPS đơn lẻ (Single VPS)

Khi website đạt khoảng 5,000 - 20,000 lượt truy cập/ngày (hoặc bắt đầu xuất hiện lỗi 503, 504), đã đến lúc bạn cần một môi trường độc lập.

*   **Cấu hình khuyến nghị:** 2 vCPU, 2-4GB RAM.
*   **Môi trường:** LNMP (Nginx + MariaDB + PHP-FPM 8.2+).
*   **Chiến lược NukeViet:**
    *   **Sử dụng Redis:** Cài đặt Redis trên cùng VPS đó. Chuyển cấu hình cache NukeViet sang driver `Redis` để giảm tải I/O cho ổ cứng.
    *   **Opcache:** Đảm bảo PHP Opcache được kích hoạt để tăng tốc biên dịch mã PHP.
    *   **Nginx FastCGI Cache:** Cấu hình Cache ngay tại lớp Nginx cho các trang tin tức tĩnh để phản hồi trong vài mili giây.

---

## 3. Cấp độ 3: Tách Cơ sở dữ liệu & Sử dụng CDN

Dành cho website lớn, nhiều dữ liệu (Tin tức lớn, TMĐT). Traffic khoảng 50,000+ views/ngày.

*   **Hành động:**
    *   **Tách Database:** Thuê một VPS riêng chỉ để chạy MariaDB. Kết nối từ Web VPS sang DB VPS qua mạng nội bộ (Private IP). Điều này giúp CPU của Web VPS tập trung xử lý PHP.
    *   **Sử dụng CDN:** Đẩy toàn bộ thư mục `assets`, `uploads`, `themes` lên CDN (như Cloudflare, BunnyCDN). NukeViet hỗ trợ cấu hình đường dẫn tuyệt đối cho file tĩnh.
*   **Lợi ích:** Website vẫn hoạt động mượt mà dù người dùng truy cập từ nhiều vị trí địa lý khác nhau.

---

## 4. Cấp độ 4: Cụm máy chủ (Load Balancing & High Availability)

Khi website của bạn là "xương sống" của doanh nghiệp, không được phép sập (Downtime = 0).

*   **Mô hình kiến trúc:**
    1.  **Load Balancer:** Một VPS chạy Nginx hoặc HAProxy để điều phối traffic.
    2.  **Web Nodes:** 2-3 VPS chạy cùng code NukeViet 5.
    3.  **Shared Storage:** Sử dụng NFS hoặc S3-compatible (S3FS) để đồng bộ hóa thư mục `uploads` và `data` giữa các Web Nodes.
    4.  **Database Strategy:** Sử dụng mô hình Master-Slave hoặc Cluster (Galera). Lưu ý NukeViet 5 khuyên dùng Database Proxy (như ProxySQL) thay vì cấu hình trực tiếp trong code.
    5.  **External Redis:** Một máy chủ cache dùng chung cho tất cả Web Nodes.

    *   Đồng bộ `data/config/config_global.php` cho tất cả node, riêng máy chủ DB và Redis trỏ về IP của cụm máy chủ tương ứng.

---

## 5. Lưu ý quan trọng về MySQL Master-Slave trong NukeViet 5

Một thay đổi lớn trong kiến trúc NukeViet 5 là việc **loại bỏ hỗ trợ điều hướng truy vấn trực tiếp qua biến `$db_slave`** vốn có từ các phiên bản trước.

### Tại sao thay đổi?
Việc điều hướng ở tầng Application (mã nguồn PHP) thông qua `$db_slave` có nhiều nhược điểm:
*   **Không có cơ chế Failover:** Nếu Node Slave chết, ứng dụng sẽ gặp lỗi ngay lập tức vì code không tự biết chuyển hướng sang Node khác hoặc quay lại Master.
*   **Khó quản lý:** Khi số lượng Slave tăng lên, việc quản lý danh sách IP trong cấu hình PHP trở nên cồng kềnh.
*   **Mất cân bằng tải:** PHP không thể biết tải thực tế của từng server MySQL để phân phối truy vấn hiệu quả.

### Giải pháp thay thế (Recommended)
Để triển khai Master-Slave cho NukeViet 5, bạn cần sử dụng một lớp trung gian (**Database Proxy** hoặc **Load Balancer**) đứng trước các máy chủ MySQL:

1.  **ProxySQL:** Tự động phân biệt câu lệnh `SELECT` để đẩy vào Slave và `INSERT/UPDATE` để đẩy vào Master. Ứng dụng NukeViet chỉ cần kết nối đến 1 IP duy nhất của ProxySQL.
2.  **MariaDB MaxScale:** Tương tự ProxySQL, được tối ưu riêng cho hệ sinh thái MariaDB.
3.  **HAProxy:** Có thể dùng để cân bằng tải tầng TCP cho các node Read-only.
4.  **Sử dụng Load Balancer**: Tầng hạ tầng

**Cấu hình trong NukeViet:**
Bạn chỉ cần khai báo thông tin kết nối đến **Proxy Endpoint** trong `config.php`. NukeViet sẽ coi đó là một server duy nhất, việc điều phối sẽ do Proxy đảm nhận.

---

## Bảng so sánh tổng quan

| Thành phần | Hosting ($1/mo) | Single VPS (~$5-10) | Optimized VPS (~$30) | Cluster (Scale Out) |
| :--- | :--- | :--- | :--- | :--- |
| **Traffic/Ngày** | < 1,000 | 5,000 - 20,000 | 50,000 - 100,000 | Vô hạn (Theo số node) |
| **DB Engine** | Shared MySQL | Local MariaDB | Dedicated MariaDB | DB Cluster |
| **Cache Driver** | Files | Redis (Local) | Redis (Dedicated) | Redis Cluster |
| **Storage** | Local SSD | Local NVMe | NVMe + CDN | Distributed Storage |
| **Khả dụng** | Trung bình | Tốt | Rất tốt | Tuyệt đối (HA) |

> [!TIP]
> **Quy tắc vàng:** Đừng nâng cấp khi chưa tối ưu code. Luôn bật `Debug Mode` trong NukeViet để kiểm tra số lượng query SQL trên mỗi trang. Nếu 1 trang tốn > 50 query, hãy tối ưu mã nguồn (Index, Cache logic) trước khi tốn thêm tiền mua tài nguyên phần cứng.
