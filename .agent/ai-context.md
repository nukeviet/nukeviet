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

**KHÔNG ĐƯỢC VI PHẠM** các quy tắc bảo mật sau:

| Ngữ cảnh | Best Practice (Bắt buộc) | KHÔNG được dùng |
|:---|:---|:---|
| **Input Data** | `$nv_Request->get_int()`, `get_title()`, `get_editor()` | `$_GET`, `$_POST` (trực tiếp) |
| **SQL Queries** | PDO `prepare()` + `bindParam()` cho chuỗi user. Cast `(int)` cho số nguyên. | Nối biến chuỗi thẳng vào SQL |
| **HTML Output** | `nv_htmlspecialchars()` | In trực tiếp chưa filter |
| **File Handling**| `nv_is_file()` | `is_file()` với path từ user |
| **Redirection** | Chỉ dùng biến `$page_url` hoặc `nv_redirect_encrypt()` | `$client_info['selfurl']` |
| **Admin Write** | Luôn kiểm tra `defined('NV_IS_ADMIN')` trước khi lưu dữ liệu | Code ghi đè không guard |

## 3. Core Architecture (NukeViet 5)

Tính tương thích ngược (Backward Compatibility) trong NV5 rất cao. Các hàm và method cốt lõi (`$nv_Request`, `$db_slave`, `$nv_Cache`) hoàn toàn giống phiên bản trước. Tuy nhiên, AI Agent cần đặc biệt chú ý các thay đổi sau:

- **Core Namespace:** Chuyển sang quản lý qua Composer, chuẩn PSR-4 (`NukeViet\Core\Request`, v.v.). Mã nguồn lõi nằm tại `src/includes/vendor/vinades/nukeviet/`.
- **Strict Types:** NV5 yêu cầu PHP 8.2+. Cần tuân thủ type hint và khuyến khích dùng `declare(strict_types=1);` ở các lớp thư viện mới.
- **Testing:** Chuyển sang Codeception. Chạy test bằng lệnh `php vendor/bin/codecept run Unit` (không dùng PHPUnit thuần).
- **Frontend Assets:** Quản lý bằng SCSS. Bắt buộc dùng `npm install` và `npm run watch-admin` hoặc `npm run watch-core` để build. KHÔNG trực tiếp chỉnh sửa các file `.css` compile ra.

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

// Blocks Constants
src/modules/[module]/blocks/global.*.php (mod)=> defined('NV_MAINFILE')         // (KHÔNG dùng NV_IS_BLOCK_THEME)
src/modules/[module]/blocks/module.*.php (mod)=> defined('NV_MAINFILE')         // (Chỉ active khi module đang chạy)
src/themes/[theme]/blocks/*.php (theme)     => defined('NV_IS_BLOCK_THEME')
```

## 5. Directory Structures

### 5.1. Module Structure (`src/modules/[module]/`)
- `version.php` (Bắt buộc): Phiên bản theo dạng chuẩn X.Y.ZZ (VD: 5.0.00).
- `functions.php` (Bắt buộc): Dù rỗng cũng không được xóa. Define `NV_IS_MOD_*`.
- `admin.functions.php`: Nơi define `NV_IS_FILE_ADMIN` và khái báo mảng `$allow_func` (với module đơn giản).
- `admin.menu.php`: Nơi khai báo `$submenu`. Với module phức tạp, `$allow_func` cũng đặt ở đây.
- `action_mysql.php`: Khai báo SQL `$sql_create_module` và `$sql_drop_module`.
- `global.functions.php`: (Tùy chọn) Hàm dùng chung cho frontend và admin (Guard: `NV_MAINFILE`).
- `theme.php` / `funcs/main.php` / `admin/main.php`: Các endpoints xử lý hiển thị.
- `Shared/`: Thư mục các class PSR-4 chuẩn `namespace NukeViet\Module\[name]\Shared\`.
- `language/`: Tệp i18n (`vi.php`, `admin_vi.php`, v.v.).
- **Lưu ý TPL:** File `.tpl` của module băt buộc nằm ở `src/themes/[theme]/modules/[module]/` (KHÔNG đặt trong thư mục code `src/modules/`).

### 5.2. Theme Structure (`src/themes/[theme]/`)
- `config.ini`: Khai báo `<layoutdefault>`, `<positions>`, `<setlayout>`, `<setblocks>`.
  - Block tags dùng định dạng: `<name>TAG</name>` để liên kết với layout.
- `config_default.php` / `config.php`: Mặc định và form tùy biến CSS thông qua Admin giao diện.
- `theme.php`: Hàm cấu hình (Guard: `NV_SYSTEM` và `NV_MAINFILE`).
- `css/custom.css` & `js/custom.js`: File tùy biến ghi đè CSS/JS load CÚ CÙNG.
- `layout/`: File khung giao diện: `block.default.tpl` (bắt buộc), `layout.*.tpl`, `simple.tpl`.
- `blocks/global.TEN.{php,tpl,ini}`: Các file block giao diện thuần túy của theme.
> **Lưu ý Cấu hình Theme:** Sau khi sửa XML trong `config.ini`, cần vào Admin -> Công cụ web -> Làm sạch cache. Layout NukeViet sử dụng hệ thống grid 24 cột.

## 6. Database & Cache Patterns

### 6.1. MySQL Queries (PDO)
```php
// READ — Luôn dùng $db_slave cho thao tác SELECT
$db_slave->query($sql)->fetch();         // Lấy 1 dòng
$db_slave->query($sql)->fetchAll();      // Lấy tất cả các dòng
$db_slave->query($sql)->fetchColumn();   // Lấy ô đầu tiên (COUNT, MAX...)

// QUERY BUILDER (Pattern chuẩn và phổ biến nhất)
$db_slave->sqlreset()->select('*')->from(NV_PREFIXLANG . '_items')
    ->where('status=1')->order('weight ASC')->limit(10);
$rows = $db_slave->query($db_slave->sql())->fetchAll();

// WRITE — Dùng $db cho INSERT/UPDATE/DELETE
$db->prepare($sql);                      // Chuẩn bị statement nếu có data chuỗi từ user
$db->lastInsertId();                     // ID vừa INSERT
$db->insert_id($sql, '', $data);         // Helper (Nhanh): INSERT trả về ID
$db->affected_rows_count($sql, $data);   // Helper (Nhanh): UPDATE/DELETE trả về rowCount
```

### 6.2. System Caching
Nên vận dụng cache hệ thống trước khi gọi trực tiếp CSDL:
```php
$data = $nv_Cache->db($sql, $key_field, $module_name); // Array tự động cache
```

## 7. Migration & Upgrades

- **Tài liệu Nâng Cấp:** Được lưu trữ trong `.agent/upgrade/` (cho cả Module lẫn Theme). 
- Đọc file theo trình tự lộ trình (Ví dụ: từ 4.5.00 lên 5.0.00 thì đọc `NV-4.5.00-len-5.0.00.md`).
- Hướng dẫn kỹ năng xem qua `.agent/skills/nukeviet-upgrade/SKILL.md`.

## 8. Git Workflow

- Tuyệt đối **KHÔNG** push thẳng nhánh `main` hoặc `develop`.
- Format Commit Message: `feat|fix|refactor|docs: mô tả [AI-assisted]`.
- Bất kỳ Merge Request (MR) nào cũng cần được 1 Peer Review trước khi merge.
