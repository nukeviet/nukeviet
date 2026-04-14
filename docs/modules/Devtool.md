# Kế Hoạch Phát Triển Module Devtool (Schema Builder)

Module này được thiết kế để hỗ trợ quá trình lập trình tự động hóa (Automated Development) trên NukeViet 5, đóng vai trò là cầu nối giữa Cơ sở dữ liệu và AI để sinh mã nguồn MVC chính xác 100%.

## 1. Mục tiêu và Ý tưởng cốt lõi
- **Input**: Bảng dữ liệu MySQL thực tế.
- **Process**: Cung cấp giao diện trực quan (UI Builder) để lập trình viên cấu hình cách ánh xạ (mapping) từ Cột DB sang Form Control.
- **Output**: File cấu hình JSON chuẩn hóa tại `data/devtool/{table_name}.json`.
- **Integration**: Cung cấp "não bộ" cho Skill `add-func-mvc` và trình sinh mã `schemas-mvc` để sinh code chính xác mà không cần hỏi lại người dùng.

---

## 2. Kiến trúc Module `Devtool`

### Cấu trúc thư mục dự kiến
```text
src/
└── modules/Devtool/
    ├── version.php              # Metadata của module
    ├── admin.functions.php      # Khai báo quyền Super Admin (NV_IS_SPADMIN)
    ├── admin.menu.php           # Menu quản trị: "Quản lý Schema"
    ├── admin/
    │   ├── main.php             # Danh sách bảng hệ thống
    │   ├── schemas.php          # Xử lý Logic Builder & Xuất JSON
    │   └── schemas-save.php     # AJAX xử lý lưu file
    └── language/
        └── vi.php
themes/admin_default/modules/Devtool/
    ├── main.tpl                 # UI chọn bảng
    └── schemas.tpl              # UI cấu hình Mapping (Grid Table)
data/Devtool/                    # Nơi lưu trữ các file cấu hình JSON sinh ra
```

---

## 3. Chức năng chi tiết (Roadmap)

### Giai đoạn 1: Phân tích Schema & Load trạng thái cũ
1. **Quét CSDL**: Sử dụng `SHOW TABLES` để lấy danh sách bảng trong Database của dự án.
2. **Trạng thái cũ (Load Config)**: Khi người dùng chọn 1 bảng, hệ thống sẽ kiểm tra xem file cấu hình `data/Devtool/{table_name}.json` đã tồn tại chưa.
    - Nếu CÓ: Load file JSON lên, tự động điền lại các tuỳ chọn cũ vào UI (Phục vụ việc Sửa cấu hình).
    - Nếu KHÔNG: Dùng `SHOW FULL COLUMNS FROM {table}` để đọc cấu trúc DB tươi mới, tự động đề xuất tuỳ chọn mặc định (Phục vụ việc Tạo mới cấu hình).

### Giai đoạn 2: Giao diện Mapping Cột (Column Options)
Xây dựng một giao diện bảng lưới (Grid) chuẩn hóa. Tuỳ thuộc vào kiểu dữ liệu SQL (Datatype) hoặc cấu hình Load từ JSON cũ, hệ thống sẽ tự động hiển thị danh sách `View` (Kiểu hiển thị Form) phù hợp:

1. **Với kiểu `varchar`**:
    - `textbox` (Textbox Text)
    - `email` (Email)
    - `url` (URL)
    - `textfile` (Textbox kèm nút Browse Server)
    - `textalias` (Textbox tự sinh Alias)
    - `password` (Mật khẩu)
    - `select` (Selectbox)
    - `radio` (Radio)
    - `checkbox` (Checkbox)
2. **Với kiểu `text / mediumtext`**:
    - `textarea` (Ô nhập liệu nhiều dòng)
    - `editor` (Trình soạn thảo mã HTML - CKEditor)
3. **Với kiểu `int / tinyint / smallint`**:
    - `number_int` (Số nguyên)
    - `number_float` (Số thực)
    - `date` (Ngày/Tháng/Năm)
    - `time` (Giờ:Phút Ngày/Tháng/Năm)
    - `textbox`, `select`, `radio`...

Bên cạnh đó, **mỗi cột** sẽ có các Checked box tùy chọn phụ trợ:
- **Bắt buộc** (`Required`): Phục vụ hàm Validator.
- **Hidden** (`Hidden`): Bỏ ra khỏi Form diện mạo (ngầm xử lý hoặc lưu id).
- **Hiện trên List** (`List`): In cột này ra ở bảng danh sách trang Quản lý.
- **Dữ liệu mảng mẫu (Cho Select/Radio)**: Lấy từ CSDL (chọn module, table, column) HOẶC tự nhập Text cố định.
- **Tiêu đề hiển thị**: Lấy từ Column Comment trong MySQL (Chỉ dùng VI, EN sẽ dịch tự động sau).

### Giai đoạn 3: Cấu hình chung cấp màn hình (Page Level Settings)
Khu vực cấu hình chung cho trang CRUD:
- **Tên Function**: (Ví dụ `page`, `main`).
- **Giao diện sinh ra**: (1) Cả List và Form; (2) Chỉ List; (3) Chỉ Form.
- **Khu vực**: Module Admin hay Module Ngoài Site (Frontend).
- **Chức năng kích hoạt (Active)**: Chọn cột đảm nhận (Ví dụ `status`).
- **Chức năng phân trang**: Có / Không.
- **Chức năng sắp xếp (Weight)**: Chọn cột đảm nhận sắp xếp thứ tự.
- **Chức năng tìm kiếm**: Bật / Tắt ô tìm kiếm phía trên List.
- **Alias Target**: Chọn cột nào làm gốc (ví dụ `title`) để sinh alias cho cột nào (ví dụ `alias`).

### Giai đoạn 4: Action Outputs (Xuất file định dạng)
Khi người dùng ấn "Lưu Cấu Hình Schema", hệ thống sẽ xuất ra file định hướng AI (`.json`):
- Gộp hai phần cấu hình (Columns và Page Settings), biên dịch qua `json_encode`.
- Lưu trữ tại `data/devtool/{table_name}.json`.
- Sau khi lưu, hệ thống cung cấp nút "Tạo MVC" để thực hiện sinh mã nguồn tự động cho module.

### Giai đoạn 5: Tích hợp AI (Skill Update)
- AI đọc file JSON và ánh xạ chính xác 100% thành mã PHP, Smarty TPL mà không cần phải đoán hay hỏi lại người dùng.

---

## 4. Công nghệ sử dụng
- **PHP**: Xử lý logic đọc CSDL (SHOW COLUMNS, SHOW TABLES) và `json_encode`.
- **NVSmarty**: Giao diện quản trị Admin.
- **JavaScript/AJAX**: Ẩn hiện các loại Control tùy thuộc vào kiểu CSDL gốc.

---

## 5. Danh sách file cấu hình JSON mẫu (Phản ánh cấu trúc thực tế)
```json
{
  "page_settings": {
    "module": "contact",
    "table": "nv4_vi_page",
    "function_name": "page",
    "is_frontend": false,
    "layout_type": "list_and_form",
    "features": {
      "pagination": true,
      "search": true,
      "active_field": "status",
      "weight_field": "weight",
      "alias_source_field": "title"
    }
  },
  "columns": {
    "title": {
      "sql_type": "varchar",
      "view_type": "textbox",
      "required": true,
      "hidden": false,
      "show_in_list": true,
      "label_vi": "Tiêu đề"
    },
    "bodytext": {
      "sql_type": "mediumtext",
      "view_type": "editor",
      "required": false,
      "hidden": false,
      "show_in_list": false,
      "label_vi": "Nội dung"
    },
    "category_id": {
      "sql_type": "int",
      "view_type": "select",
      "required": true,
      "hidden": false,
      "show_in_list": true,
      "label_vi": "Chuyên mục",
      "label_en": "Category",
      "choice_type": "sql",
      "choice_sql": {
        "module": "contact",
        "table": "nv4_vi_categories",
        "column": "catid"
      }
    }
  }
}
```

---

## 6. Quy tắc tự động hóa và Ghi chú AI (AI Automation Rules)

Để tối ưu hóa quá trình sinh mã nguồn thông qua `schemas-mvc`, hệ thống áp dụng các quy tắc tự động nhận diện và cấu hình mặc định (AI-Powered Defaults).

### 6.1. Tự động nhận diện và cấu hình (Giai đoạn Builder)
Khi hệ thống quét cấu trúc DB một bảng mới, các cột sau sẽ được ưu tiên nhận diện và thiết lập Ghi chú AI (Note):

- **Trạng thái (Active)**: Tự động nhận diện cột `status`.
    - Ghi chú AI: `Trạng thái (Active)`
    - View type: `checkbox`
- **Sắp xếp (Weight)**: Tự động nhận diện cột `weight` hoặc `sort`.
    - Ghi chú AI: `Sắp xếp (Weight)`
    - View type: `number_int`
- **Nguồn tạo Alias**: Tự động nhận diện cột `title` hoặc `name`.
    - Ghi chú AI: `Nguồn tạo Alias`
- **Bộ soạn thảo (Editor)**: Các cột có kiểu `bodytext`, `mediumtext` sẽ tự động chọn view type là `editor`.
- **Cột hệ thống tự động**: Các cột `admin_id`, `add_time`, `edit_time`, `hitstotal` sẽ được cấu hình:
    - Ghi chú AI: `Ẩn để schemas-mvc tự động sinh`
    - Trạng thái: `Hidden` (Ẩn khỏi Form).

### 6.2. Quy tắc sinh mã (Giai đoạn schemas-mvc)
Dựa trên Ghi chú AI, trình sinh mã (Code Generator) thực hiện các logic tự động hóa trong mã nguồn MVC:

- **Loại bỏ khỏi Form**: Các cột được đánh dấu là `Trạng thái (Active)`, `Sắp xếp (Weight)` hoặc `Ẩn để schemas-mvc tự động sinh` sẽ không xuất hiện trong Form (`.tpl`), không thực hiện kiểm tra `Validator` và không thu thập dữ liệu từ Request trong `Service`.
- **Tự động gán giá trị (Service Layer)**: Tại hàm `save{Item}`, hệ thống tự sinh mã gán giá trị:
    - `status`: Mặc định = 1 khi thêm mới.
    - `weight`: Gọi `$this->repo->getNewWeight()` khi thêm mới.
    - `admin_id`: Gán bằng ID admin hiện tại khi thêm mới.
    - `add_time`: Gán `NV_CURRENTTIME` khi thêm mới.
    - `edit_time`: Gán `NV_CURRENTTIME` mỗi khi lưu.
    - `hitstotal`: Khởi tạo bằng 0.
- **Hỗ trợ Repository**: Tự động sinh hàm `getNewWeight()` trong lớp Repository nếu phát hiện bảng có cấu hình `Weight`.
