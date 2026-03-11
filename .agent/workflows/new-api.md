---
description: Khởi tạo tệp cơ sở (scaffold) cho một NukeViet API (Remote API, Local API hoặc cả hai)
---

# Workflow: `/new-api`

Tính năng này giúp bạn tạo nhanh boilerplate một tệp lệnh API phục vụ hệ sinh thái NukeViet 5.
Theo cấu trúc chuẩn, API của một module được đặt tại thư mục `Api` (dành cho Admin) hoặc `Uapi` (dành cho User/Public) bên trong thư mục module đó.

<!-- Cảnh báo cấu trúc: Agent sẽ đặt các file này vào thư mục của module `modules/[moduleName]/Api/[Action].php` hoặc `Uapi`. -->

## 1. Thu thập thông tin từ user
Yêu cầu người dùng cung cấp các thông tin sau (Hỏi gọn gàng 1 lần):
1. **Tên Module đích** (Ví dụ: `news`, `shops`, `page`).
2. **Loại API**: `1` (Admin API - `Api/`) hoặc `2` (User/Public API - `Uapi/`).
3. **Tên Action**: PascalCase (VD: `GetList`, `UpdateItem`).
4. **Namespace đích**: Chuẩn `NukeViet\Module\TênModuleViếtHoa\Api`.
5. **Cách gọi dự kiến**: `Remote`, `Local`, hoặc `Cả hai`.

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
Load mẫu mã nguồn từ `.agent/skills/nukeviet-api/SKILL.md` (hoặc các file trong thư mục `examples/` của skill đó).
Dùng công cụ `write_to_file` để tạo nội dung class vào `modules/{module_name}/{api_type}/{action_name}.php`.
Thay thế `namespace` và tên `Class` cấu hình mặc định (phân quyền) cho phù hợp.

## 4. Tạo mẫu gọi Local API
Nếu người dùng chọn **Local** hoặc **Cả hai**, hiển thị đoạn code PHP mẫu gọi `nv_local_api()` cho action vừa tạo để user tham khảo.

## 5. Kết thúc
Thông báo hoàn tất và cung cấp URL test (nếu Remote) hoặc hàm mẫu (nếu Local).
