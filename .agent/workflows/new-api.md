---
description: Khởi tạo tệp cơ sở (scaffold) cho một NukeViet Remote API (Admin Api hoặc User Uapi)
---

# Workflow: `/new-api`

Tính năng này giúp bạn tạo nhanh boilerplate một tệp lệnh Remote API phục vụ hệ sinh thái NukeViet 5.
Theo cấu trúc chuẩn, API của một module được đặt tại thư mục `Api` (dành cho Admin) hoặc `Uapi` (dành cho User/Public) bên trong thư mục module đó.

<!-- Cảnh báo cấu trúc: Agent sẽ đặt các file này vào thư mục của module `modules/[moduleName]/Api/[Action].php` hoặc `Uapi`. -->

## 1. Yêu cầu nhập liệu
Hỏi người dùng các thông tin sau:
1. **Tên Module** đích (Ví dụ: `news`, `shops`, `page`).
2. **Loại API**:
   - `1` = Admin API (cần quyền hệ thống, đặt tại thư mục `Api`, kế thừa `IApi`).
   - `2` = User API/Public API (đặt tại thư mục `Uapi`, kế thừa `UiApi`).
3. **Tên Action** (chữ cái đầu viết hoa, dạng PascalCase. Ví dụ: `GetList`, `UpdateItem`, `SyncData`).
4. **Namespace đích**: Nhắc nhở người dùng theo chuẩn `NukeViet\Module\TênModuleViếtHoa\Api`.

## 2. Tạo logic thư mục (Tự động chạy)
Sử dụng công cụ `run_command` để tạo thư mục chứa nếu chưa tồn tại:

// turbo
```bash
# Biến tên thành thư mục thường (Ví dụ: news)
MODULE_LOWER="news"
# Thư mục đích có thể là Api hoặc Uapi
API_FOLDER="Api" 

mkdir -p "modules/$MODULE_LOWER/$API_FOLDER"
```

## 3. Tạo File PHP API
Agent bắt đầu load `.agent/skills/nukeviet-api/SKILL.md` để lấy mẫu code chuẩn (Template Boilerplate) và dùng công cụ `write_to_file` để điền nội dung class vào file mới tạo `modules/{module_name}/{api_type}/{action_name}.php`. 
Bảo đảm thay thế đúng các biến `namespace` và tên `Class` theo cái người dùng cung cấp.

Mặc định các giá trị phân quyền `getAdminLev()` hay `getCat()` để rỗng hoặc `ADMIN_LEV_MOD` để người dùng sửa sau.

## 4. Kết thúc
- Gửi thông báo đến người dùng về đường dẫn URL dự kiến để gọi hàm API này: `DOMAIN/api.php?module=[module_name]&action=[action_name]&language=vi`.
- Lưu ý họ vào phần Quản trị / Điều khiển API để cấu hình Vai trò (Role) thì API mới hoạt động thực tế.
