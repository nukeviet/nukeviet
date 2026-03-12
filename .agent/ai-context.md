# NukeViet 5.x — AI Context

> **Primary Directive:** File này là **NGUỒN SỰ THẬT DUY NHẤT** (Single Source of Truth) cho tất cả các AI Agents làm việc với dự án.
> - **Kỹ năng chuyên sâu (Skills):** Bạn **BẮT BUỘC** phải load và đọc kỹ các file `SKILL.md` tương ứng trong `.agent/skills/` (VD: `nukeviet-mysql`, `nukeviet-theme`...) trước khi thao tác với một thành phần cụ thể.
> - **Tự động hóa (Workflows):** Mỗi khi nhận yêu cầu khởi tạo mã nguồn (module, theme, api, block...), bạn **LUÔN LUÔN** phải đối chiếu và chạy theo các bước định sẵn tại `.agent/workflows/`.
> - Chỉ cập nhật các rules và conventions chung nhất tại file này.

## 1. System Environment & Stack

- **Languages & Runtimes:** PHP 8.2 - 8.5, Node.js v18.17+, NPM v10.5+, Composer v2.6+
- **Infrastructure:** MariaDB, MySQL, Linux (AlmaLinux, RockyLinux, Ubuntu), Nginx + PHP-FPM
- **Git Strategy:** `main` (Production) | `develop` (Staging)

## 2. Security Protocols (STRICT)

> Chi tiết các quy tắc bảo mật, CSRF token (`hash_hmac`/`hash_equals`), Input/Output filter, SQL injection: xem [nukeviet-security](skills/nukeviet-security/SKILL.md) và [nukeviet-module](skills/nukeviet-module/SKILL.md).

**Nguyên tắc cốt lõi (không được vi phạm):**
- Input PHẢI qua `$nv_Request`, KHÔNG dùng `$_GET`/`$_POST` trực tiếp.
- SQL chuỗi user PHẢI dùng `prepare()` + `bindParam()`. Số nguyên cast `(int)` nối thẳng.
- Output HTML: dữ liệu từ `get_string()`, `get_editor()`, `get_textarea()` hoặc raw DB dùng `nv_htmlspecialchars()`. Dữ liệu từ `get_title()` đã escape — **KHÔNG** escape lại (tránh double-encode).
- Admin: Luôn kiểm tra `defined('NV_IS_ADMIN')` trước khi ghi dữ liệu.
- Kiểm tra file dùng `nv_is_file()`, KHÔNG dùng `is_file()` với path từ user.

## 3. Core Architecture (NukeViet 5)

Tính tương thích ngược (Backward Compatibility) trong NV5 rất cao. Các hàm và method cốt lõi (`$nv_Request`, `$db_slave`, `$nv_Cache`) hoàn toàn giống phiên bản trước. Tuy nhiên, AI Agent cần đặc biệt chú ý các thay đổi sau:

- **Core Namespace:** Chuyển sang quản lý qua Composer, chuẩn PSR-4 (`NukeViet\Core\Request`, v.v.). Mã nguồn lõi nằm tại `src/includes/vendor/vinades/nukeviet/`.
- **Strict Types:** NV5 yêu cầu PHP 8.2+. Cần tuân thủ type hint và khuyến khích dùng `declare(strict_types=1);` ở các lớp thư viện mới.
- **Testing:** Chuyển sang Codeception. Chạy test bằng lệnh `php vendor/bin/codecept run Unit` (không dùng PHPUnit thuần).
- **Frontend Assets:** Quản lý bằng SCSS. Bắt buộc dùng `npm install` và `npm run admin-css` hoặc `npm run core-css` để build. KHÔNG trực tiếp chỉnh sửa các file `.css` compile ra.

## 4. Coding Conventions & Definitions

### 4.1. Global Variables & Constants
- **Database Tables:**
  - Tiền tố đa ngôn ngữ: `NV_PREFIXLANG . '_ten_bang'` (VD: `nv5_vi_news`)
  - Tiền tố dùng chung: `NV_TABLEPREFIX . '_ten_bang'` (VD: `nv5_users`)
  - Bảng config toàn cục: `NV_CONFIG_GLOBALTABLE`
- **Core Constants:**
  - Thời gian hiện tại: `NV_CURRENTTIME`
  - Đường dẫn gốc: `NV_ROOTDIR`
- **URL Pattern:** `?lang=vi&nv=ten-module&op=ten-func`
- **Auth Constants:**
  - `NV_IS_ADMIN`: Đã đăng nhập hệ thống Admin (mọi level).
  - `NV_IS_MODADMIN`: Admin có quyền trên module hiện tại.
  - `NV_IS_SPADMIN`: Super Admin (Kiểm soát đặc quyền, ví dụ cấu hình tối cao).
- **Code Style:** 4 khoảng trắng, `camelCase` (biến/hàm), `PascalCase` (Class/PSR-4), PHPDoc + comment tiếng Việt.

### 4.2. File Security Guards (Bắt buộc đầu file)
```php
version.php              => defined('NV_ADMIN') && defined('NV_MAINFILE')
global.functions.php     => defined('NV_MAINFILE')
Shared/*.php             => defined('NV_MAINFILE')
funcs/main.php           => defined('NV_IS_MOD_TENMODULE') // (define trong functions.php)
admin/main.php           => defined('NV_IS_FILE_ADMIN')    // (define trong admin.functions.php)

// Blocks — tất cả đều dùng NV_MAINFILE
src/modules/[module]/blocks/global.*.php  => defined('NV_MAINFILE')
src/modules/[module]/blocks/module.*.php  => defined('NV_MAINFILE')
src/themes/[theme]/blocks/*.php           => defined('NV_MAINFILE')
```

## 5. Directory Structures

> Cấu trúc file module chi tiết (cây thư mục đầy đủ bao gồm cả themes): xem [nukeviet-module](skills/nukeviet-module/SKILL.md).

> Cấu trúc file theme chi tiết: xem [nukeviet-theme](skills/nukeviet-theme/SKILL.md).

## 6. Database & Cache

> Chi tiết MySQL (Query Builder, Prepared Statement, `$db`/`$db_slave`): xem [nukeviet-mysql](skills/nukeviet-mysql/SKILL.md).
> Chi tiết hệ thống Cache (`$nv_Cache->db()`, `setItem`, `delMod`): xem [nukeviet-cache](skills/nukeviet-cache/SKILL.md).

**Nguyên tắc cốt lõi:**
- `$db`: Dùng cho WRITE (INSERT/UPDATE/DELETE).
- `$db_slave`: Ưu tiên cho READ (SELECT) ở frontend/block. Không bắt buộc ở admin.
- `$nv_Cache->db()`: Dùng thay `$db_slave->query()` cho dữ liệu ít thay đổi.

## 7. Migration & Upgrades

> Chi tiết quy trình nâng cấp module/theme: xem [nukeviet-upgrade](skills/nukeviet-upgrade/SKILL.md). Tài liệu lộ trình lưu tại `.agent/upgrade/`.

## 8. Git Workflow

- Tuyệt đối **KHÔNG** push thẳng nhánh `main` hoặc `develop`.
- Format Commit Message: `feat|fix|refactor|docs: mô tả [AI-assisted]`.
- Bất kỳ Merge Request (MR) nào cũng cần được 1 Peer Review trước khi merge.
