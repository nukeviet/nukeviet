# AI Agent Guide — NukeViet 5.x

**Context Root:** Toàn bộ đường dẫn trong file này và các file `.agent/*.md` đều tính từ thư mục gốc của dự án.

**Primary Directive:** Đọc `.agent/ai-context.md` để nắm tổng quan project trước khi thực hiện bất cứ thao tác nào.

## Quy trình làm việc (Strict Protocol)

Bạn (AI) phải hoạt động theo chu kỳ: **Phân tích → Lập kế hoạch → Xác nhận → Thực thi.**

1. **Plan Mode (Bắt buộc):** Khi nhận task, tuyệt đối KHÔNG viết code ngay.
   - Sử dụng công cụ `list_dir`, `grep_search`, `view_file` để khảo sát các file liên quan.
   - Trình bày kế hoạch theo cấu trúc:
     - **Mục tiêu:** (Hiểu task như thế nào?)
     - **Các file ảnh hưởng:** (Liệt kê đường dẫn cụ thể)
     - **Thay đổi dự kiến:** (Mô tả logic sẽ sửa/thêm)
     - **Rủi ro:** (Các breaking changes nếu có)
2. **Chờ xác nhận:** Chỉ bắt đầu code sau khi tôi (Dev) phản hồi "OK"
3. **Thực thi:** Tuân thủ PSR-12 cho PHP và các kỹ năng trong `.agent/skills/`

## Lệnh & Tự động hóa (Automation)

### Lệnh thường dùng
```bash
composer install && npm install  # Khởi tạo môi trường
npm run admin-css                # Build CSS Admin
npm run core-css                 # Build CSS Core
php vendor/bin/codecept run      # Chạy toàn bộ kịch bản test
php vendor/bin/codecept run Unit # Chạy Unit Test
```
## Slash Commands Custom
Khi tôi nhập các lệnh bắt đầu bằng /, hãy truy cập vào thư mục .agent/workflows/, tìm file tương ứng (ví dụ /new-api -> .agent/workflows/new-api.md) và làm theo quy trình trong đó:

| Lệnh | Mô tả chức năng |
|---|---|
| `/new-module` | Khởi tạo cấu trúc Module chuẩn cho NukeViet 5 |
| `/new-theme` | Build hoặc tạo mới CSS Theme NukeViet 5 |
| `/new-block` | Khởi tạo tệp cơ sở (scaffold) cho một NukeViet Block |
| `/new-api` | Khởi tạo tệp cơ sở (scaffold) cho một NukeViet API |
| `/new-hook` | Khởi tạo tệp cơ sở (scaffold) cho một Hook (Plugin) |
| `/add-func` | Thêm function mới cho Module (Frontend / Admin) |
| `/upgrade-module` | Nâng cấp Module cũ lên chuẩn NukeViet 5 |
| `/security-audit` | Quét đầy đủ (Syntax, PSR-12, Security) |

## Danh mục kỹ năng (.agent/skills/)
Dùng để tra cứu khi làm việc với các thành phần cụ thể:
| Kỹ năng / Thư mục | Nội dung & Mục đích sử dụng |
|---|---|
| `nukeviet-module` | Cấu trúc file bắt buộc, PSR-4 namespaces, Code mẫu controller/funcs. |
| `nukeviet-api` | Kiến trúc Remote API (Api & Uapi), Hướng dẫn tạo Class kế thừa interface. |
| `nukeviet-block` | Cấu trúc Block NV5 (XTemplate cho Module Block, Smarty cho Theme Block). |
| `nukeviet-theme` | Cấu trúc layout grid 24 cột, compile assets qua `npm run`, định nghĩa Block vị trí. |
| `nukeviet-hook` | Hệ thống Hook (Plugin): `nv_apply_hook()`, `nv_add_hook()`, template file hook. |
| `nukeviet-language` | Đa ngôn ngữ: `NV_LANG_DATA`, `$lang_module`, nạp thủ công `loadModule()`, i18n JSON. |
| `nukeviet-cache` | Hệ thống Cache: `db()`, `setItem`/`getItem`, `delMod`, hỗ trợ Files/Memcached/Redis. |
| `nukeviet-testing` | Unit Test, Acceptance Test với Codeception, Selenium, chạy theo Group. |
| `nukeviet-security` | Quét lỗ hổng nguy hiểm (SQLi, CSRF, XSS), filter `$nv_Request`, `nv_htmlspecialchars()`. |
| `nukeviet-mysql` | Query Builder thông qua `$db_slave` / `$db`, Prefix đa ngôn ngữ chuẩn NukeViet 5. |
| `nukeviet-upgrade` | Lộ trình nâng cấp từ phiên bản NukeViet 4.x. |
| `.agent/upgrade/` | Kho chứa logs chi tiết các breaking config khi nâng cấp module/theme. |


