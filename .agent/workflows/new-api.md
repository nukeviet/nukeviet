---
description: Khởi tạo tệp cơ sở (scaffold) cho một NukeViet API (Remote API, Local API hoặc cả hai)
---

# Workflow: `/new-api`

Tính năng này giúp bạn tạo nhanh boilerplate một tệp lệnh API phục vụ hệ sinh thái NukeViet 5.
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
5. **Cách gọi dự kiến**: Hỏi người dùng API này sẽ được gọi từ đâu:
   - `Remote` = Chỉ gọi qua HTTP (`api.php`), từ bên ngoài.
   - `Local` = Chỉ gọi nội bộ qua `nv_local_api()`, trong các function admin/frontend.
   - `Cả hai` = Vừa gọi Remote lẫn Local (mặc định).

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

## 4. Tạo mẫu gọi Local API (nếu chọn Local hoặc Cả hai)

Nếu người dùng chọn **Local** hoặc **Cả hai** ở bước 1, Agent tạo thêm đoạn code mẫu gọi `nv_local_api()` để người dùng tham khảo. Tham khảo section **6. Local API** trong SKILL.md.

Mẫu code gọi cần bao gồm:
```php
// Gọi Local API: [ActionName] của module [module_name]
$params = [
    // Các tham số tùy API
];
$json_result = nv_local_api('[ActionName]', $params, $admin_info['username'], $module_name);
$result = json_decode($json_result, true);

if ($result['code'] == '0000') {
    // Xử lý thành công
} else {
    $error = $result['message'];
}
```

## 5. Kết thúc
- Gửi thông báo đến người dùng về:
  - **Nếu Remote/Cả hai:** URL dự kiến `DOMAIN/api.php?module=[module_name]&action=[action_name]&language=vi`. Lưu ý vào Quản trị / Điều khiển API để cấu hình Vai trò (Role).
  - **Nếu Local/Cả hai:** Hướng dẫn gọi `nv_local_api('[ActionName]', $params, $admin_info['username'], '[module_name]')` trong function admin hoặc frontend.
