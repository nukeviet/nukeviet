ok# Bộ tạo cấu hình Module (Devtool Config Generator)

## Tổng quan
Tài liệu này trình bày chiến lược tổng quát hóa và tự động hóa việc tạo giao diện cấu hình module (admin config) trong NukeViet 5.0.

Hiện tại, mỗi module đều phải tự viết mã cho `admin/config.php` và `config.tpl` một cách thủ công. Mục tiêu là cung cấp một phương pháp "Không cần viết mã" (Zero Coding), nơi các định nghĩa giao diện (UI) được lưu trữ trong một file metadata JSON, từ đó điều khiển việc sinh mã hoặc hiển thị động.

## Nguyên tắc cốt lõi
1.  **Nguồn dữ liệu thực (Source of Truth)**: Các cấu hình hiện có được lưu trữ trong `NV_CONFIG_GLOBALTABLE` (lọc theo `lang` và `module`).
2.  **Định nghĩa Metadata**: Một file JSON (`src/data/devtool/config_{module_name}.json`) ánh xạ các khóa cấu hình trong database với các thành phần giao diện và quy tắc kiểm tra dữ liệu.
3.  **Loại đầu vào thống nhất**: Hỗ trợ tất cả các loại nhập liệu tiêu chuẩn của NukeViet và Bootstrap 5.

## Ánh xạ thành phần (Các loại giao diện)
Các loại sau đây sẽ được hỗ trợ trong metadata dựa trên các yêu cầu:

| Loại | Mô tả | Triển khai trong NukeViet |
| :--- | :--- | :--- |
| **Số nguyên** (`number`) | Nhập liệu số | `<input type="number">` với các giá trị min/max |
| **Số thực** (`number`) | Nhập liệu số | `<input type="number">` với các giá trị min/max |
| **Ngày** (`date`) | Chọn ngày tháng | NukeViet Datepicker hoặc Bootstrap Datepicker |
| **Một dòng** (`textbox`) | Văn bản một dòng | `<input type="text">` |
| **Nhiều dòng** (`textarea`) | Văn bản nhiều dòng | `<textarea>` |
| **Trình soạn thảo** (`editor`) | Trình soạn thảo văn bản | Tích hợp CKEditor hoặc TinyMCE |
| **Lựa chọn thả xuống** (`selectbox`) | Danh sách thả xuống chọn một | `<select>` |
| **Một lựa chọn** (`radio`) | Nút chọn một | `<input type="radio">` |
| **Nhiều lựa chọn** (`checkbox`) | Kiểu Boolean hoặc chọn nhiều | `<input type="checkbox">` |
| **Nhiều lựa chọn thả xuống** (`multiselect`) | Thả xuống chọn nhiều | `<select multiple>` (Sử dụng Select2 hoặc Choices.js) |
| **File** (`file`) | Chọn file hoặc hình ảnh | Tích hợp trình quản lý tệp tin của NukeViet |

## Đặc tả Schema JSON
Đường dẫn: `src/data/devtool/config_{module_name}.json`

File này sẽ lưu trữ metadata giao diện cho mỗi khóa cấu hình tìm thấy trong database.

```json
{
    "module": "Content",
    "groups": [
        {
            "title": "config_common",
            "fields": {
                "viewtype": {
                    "type": "selectbox",
                    "label": "config_view_type",
                    "options": {
                        "0": "config_view_type_0",
                        "1": "config_view_type_1",
                        "2": "config_view_type_2"
                    }
                },
                "per_page": {
                    "type": "number",
                    "label": "config_view_type_page",
                    "min": 2,
                    "max": 30
                },
                "news_first": {
                    "type": "checkbox",
                    "label": "first_news"
                },
                "facebookapi": {
                    "type": "textbox",
                    "label": "config_facebookapi",
                    "note": "config_facebookapi_note"
                }
            }
        }
    ]
}
```

## Quy trình triển khai

### Giai đoạn 1: Trích xuất Metadata
Xử lý tại file: `src\modules\Devtool\admin\config-step1.php`

1.  **Nhận diện**: Truy vấn `NV_CONFIG_GLOBALTABLE` với điều kiện `lang = NV_LANG_DATA` và `module = :module_name`.
2.  **Cấu hình tương tác**:
    *   Hệ thống liệt kê tất cả các khóa tìm thấy (config_name).
    *   Người dùng gán loại giao diện (Số, Ngày, v.v.).
    *   Người dùng cung cấp các khóa ngôn ngữ (Nhãn, Ghi chú).
    *   Người dùng định nghĩa các nhóm để bố trí giao diện (Thẻ/Dòng).
3.  **Chi tiết thuộc tính theo loại cấu hình**:
    *   **Số nguyên / Số thực**:
        *   Giá trị mặc định.
        *   Giá trị nhỏ nhất.
        *   Giá trị lớn nhất.
    *   **Ngày**:
        *   Hiển thị: Datepicker hoặc Datetimepicker.
    *   **Một dòng (textbox) & Nhiều dòng (textarea)**:
        *   Giá trị mặc định.
        *   Chiều dài ký tự ít nhất / nhiều nhất.
        *   **Kiểm tra dữ liệu (Validation)**:
            *   Không kiểm tra.
            *   Chỉ dùng A-Z, 0-9 và gạch dưới.
            *   Tên người (Unicode, gạch ngang, nháy đơn, khoảnh trắng).
            *   Email.
            *   URL.
            *   Biểu thức quy tắc (Regex).
            *   Sử dụng hàm tùy chỉnh (Custom Function).
    *   **Lựa chọn thả xuống (selectbox), Một lựa chọn (radio), Nhiều lựa chọn (checkbox), Nhiều lựa chọn thả xuống (multi selectbox)**:
        *   **Trường hợp 1: Lấy dữ liệu từ nhập liệu (Tĩnh)**:
            *   Danh sách gồm: STT, Khóa (Key), Giá trị (Value), Giá trị mặc định.
        *   **Trường hợp 2: Lấy dữ liệu từ CSDL (Động)**:
            *   Chọn Module.
            *   Chọn Bảng dữ liệu.
            *   Chọn cột dữ liệu: Cột làm ID (Khóa), Cột làm Value (Hiển thị).

4.  **Kết quả**: Lưu kết quả vào file metadata `src/data/devtool/config_{module_name}.json`.

### Giai đoạn 2: Sinh mã (Code Generation)
Xử lý tại file: `src\modules\Devtool\admin\config-step2.php`

1.  **Đọc dữ liệu**: Hệ thống đọc file `src/data/devtool/config_{module_name}.json` đã tạo ở giai đoạn 1.
2.  **Sinh mã Logic (`admin/config.php`)**:
    *   Sử dụng biến biến hệ thống `$module_config[$module_name]` cho các giá trị ban đầu.
    *   Ánh xạ dữ liệu POST trở lại đúng loại dữ liệu (ví dụ: checkbox thành 0/1, số thành kiểu int).
    *   Sử dụng Repository của module để lưu lại vào `NV_CONFIG_GLOBALTABLE`.
3.  **Sinh mã Giao diện (`config.tpl`)**:
    *   Sử dụng hệ thống lưới của Bootstrap 5 (row/col).
    *   Sử dụng cú pháp `NVSmarty` cho các vòng lặp và điều kiện.
    *   Tự động xử lý `ajax-submit` và kiểm tra `checkss` (CSRF).

## Chiến lược kỹ thuật
Để triển khai điều này, chúng ta sẽ xây dựng hai tệp xử lý riêng biệt trong module Devtool:
- `config-step1.php`: Tập trung vào việc quét DB và xây dựng bộ khung metadata.
- `config-step2.php`: Sử dụng một lớp `ConfigCodeGenerator` để phân tích metadata và xuất nội dung cho file `.php` và `.tpl`.
- Sử dụng code đúng mô hình MVC của NukeViet.

Phương pháp này giúp tách biệt rõ ràng giữa việc "định nghĩa giao diện" và "sinh mã nguồn", giúp việc bảo trì và mở rộng sau này dễ dàng hơn.
