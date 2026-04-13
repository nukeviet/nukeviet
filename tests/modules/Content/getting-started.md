# Hướng dẫn Kiểm thử Module `Content` (NukeViet 5)

Tài liệu này hướng dẫn cách chạy bộ kiểm thử (Test Suite) đã được quy hoạch cho module `Content`.

---

## 1. Chuẩn bị môi trường

Trước khi chạy, hãy đảm bảo:
1.  **Composer dependencies**: Đã cài đặt đủ `codeception`.
    ```bash
    composer install
    ```
2.  **Cấu hình `.env`**: File `.env` ở thư mục gốc phải trỏ đúng `BASE_URL` và thông tin Database của môi trường test.
3.  **Selenium Server**: (Bắt buộc cho Acceptance Test)
    - Tải và chạy Selenium Standalone hoặc dùng driver tương ứng (Chrome/Firefox).
    ```bash
    selenium-standalone start
    ```

---

## 2. Các lệnh chạy Kiểm thử (Tập trung cho module Content)

Để đảm bảo hiệu năng và tính cô lập, chúng ta chỉ chạy các file trong thư mục của module:

### 2.1. Unit Test (Kiểm thử đơn vị)
Đây là tầng kiểm thử nhanh nhất, dùng để kiểm tra logic của từng hàm PHP (Service, Validator) mà không cần chạy website hay kết nối database.

**Cách chạy:**
```bash
php vendor/bin/codecept run Unit tests/modules/Content/Unit/ --steps
```

**Cách đọc kết quả (Quan trọng):**
Khi chạy xong, bạn nhìn vào dòng ký hiệu ở đầu hoặc bảng tổng kết:
- **Dấu chấm (`.`) màu xanh**: Bài test **ĐẠT**. Mọi khẳng định (assertion) trong code đều đúng.
- **Chữ `F` (Fail) màu đỏ**: Bài test **THẤT BẠI**. Kết quả hàm trả về không đúng như mong đợi (Ví dụ: Ta mong đợi hàm trả về `true` nhưng nó lại trả về `false`).
- **Chữ `E` (Error) màu đỏ**: Bài test bị **LỖI**. Code PHP của bạn bị lỗi cú pháp hoặc Runtime error (Ví dụ: Gọi vào một hàm không tồn tại).

**Ví dụ bảng tổng kết khi ĐẠT:**
```text
OK (7 tests, 16 assertions)
```
*Số `assertions` là số lần "khẳng định" trong code. Càng nhiều assertion được thông qua chứng tỏ code càng tin cậy.*

**Nếu có lỗi, Codeception sẽ chỉ rõ:**
1. **Tên file và hàm bị lỗi**: Giúp bạn biết chính xác chỗ cần sửa.
2. **So sánh (Diff)**: `Expected` (Mong đợi) và `Actual` (Thực tế) khác nhau ở đâu.


Con số **`OK (7 tests, 16 assertions)`** là "chứng chỉ" đảm bảo chất lượng cho code bạn viết. Ý nghĩa cụ thể như sau:

### 1. `7 tests` (7 bài kiểm tra)
Đây là tổng số các tình huống (kịch bản) mà máy tính vừa chạy qua. Trong thư mục `Unit` của module content, tôi đã viết cho bạn 7 hàm bắt đầu bằng chữ `test...`, tương ứng với 7 kịch bản:
1.  Lấy thông tin chủ đề thành công.
2.  Lấy thông tin chủ đề khi ID không tồn tại.
3.  Lấy danh sách chủ đề cho dropdown select.
4.  Lưu một chủ đề mới.
5.  Kiểm duyệt dữ liệu đúng.
6.  Kiểm duyệt dữ liệu khi thiếu tiêu đề.
7.  Kiểm duyệt dữ liệu khi trùng Alias.

### 2. `16 assertions` (16 lời khẳng định)
Đây mới là con số quan trọng nhất. Một bài test (kịch bản) có thể chứa nhiều **lời khẳng định**.
*Ví dụ*: Trong kịch bản "Lấy danh sách chủ đề cho dropdown", tôi đã bắt máy tính phải khẳng định 3 điều:
*   Khẳng định 1: Kết quả trả về **phải là một MẢNG**.
*   Khẳng định 2: Mảng đó **phải có đúng 2 phần tử**.
*   Khẳng định 3: Tên của chủ đề số 1 **phải là 'Cat 1'**.

Nếu **tất cả 16 lời khẳng định** này đều đúng thực tế so với code bạn viết, nó sẽ hiện ra màu xanh và báo `OK`. Nếu chỉ cần **1 trong 16** khẳng định này sai (ví dụ bạn vô tình sửa code làm tên chủ đề biến thành 'Cat 2'), hệ thống sẽ báo `Fail` ngay lập tức.

### Tóm lại:
*   **7 tests**: Quy mô bao phủ của các kịch bản (Bạn đã kiểm tra bao nhiêu trường hợp).
*   **16 assertions**: Độ sâu và độ chặt chẽ của việc kiểm tra (Bạn đã kiểm tra kỹ đến mức nào trong mỗi trường hợp).

Số **assertions** càng cao mà vẫn báo `OK` thì bạn càng có thể "ngủ ngon" vì code của mình đã được máy tính bảo vệ cực kỳ chặt chẽ!


### 2.2. API Test (Kiểm thử Endpoint)
Kiểm tra các phản hồi JSON từ hệ thống API. Suite `Api` đã được cấu hình chuyên dụng để chạy qua CURL (PhpBrowser), **không cần mở trình duyệt**, giúp tốc độ kiểm thử cực nhanh.

**Cách chạy:**
```bash
# Chạy toàn bộ các API test của riêng module Content
php vendor/bin/codecept run Api tests/modules/Content/API/

# Chạy một file cụ thể trong module Content
php vendor/bin/codecept run Api tests/modules/Content/API/AdminCatApiCest.php

# Chạy một hàm (kịch bản) cụ thể bên trong file
php vendor/bin/codecept run Api tests/modules/Content/API/AdminCatApiCest.php:testGetCatDetailSuccess
```

### 2.3. Acceptance Test (Kiểm thử Giao diện)
Mô phỏng hành động của người dùng trên trình duyệt (Admin/Frontend). Yêu cầu Selenium Server đang chạy.
```bash
php vendor/bin/codecept run Acceptance tests/modules/Content/Acceptance/
```

### 2.4. Kiểm thử cho Module ảo (Virtual Module)
Nếu bạn có một module ảo (ví dụ: `content-virtual`) được clone từ module `Content`, bạn có thể chạy lại chính bộ test này cho module ảo đó bằng cách sử dụng biến môi trường `NV_MODULE`.

**Trên Bash (Git Bash, Linux, macOS):**
```bash
NV_MODULE="content-virtual" php vendor/bin/codecept run Api tests/modules/Content/API/
NV_MODULE="content-virtual" php vendor/bin/codecept run Acceptance tests/modules/Content/Acceptance/
```

**Trên PowerShell (Windows):**
```powershell
$env:NV_MODULE="content-virtual"; php vendor/bin/codecept run Api tests/modules/Content/API/
$env:NV_MODULE="content-virtual"; php vendor/bin/codecept run Acceptance tests/modules/Content/Acceptance/
```

---
*Lưu ý: Luôn dọn dẹp dữ liệu rác trong database sau khi test nếu bạn không dùng database test riêng.*
