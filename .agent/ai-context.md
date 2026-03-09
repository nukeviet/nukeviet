# NukeViet 5.x — AI Context

> File này là **nguồn sự thật duy nhất** cho tất cả AI tool.
> Tư liệu mở rộng tham khảo tại từng plugin ở `.agent/skills/*/SKILL.md`.
> Cấu trúc module auto-command nằm ở `.agent/workflows/`.
> Khi cần cập nhật convention → chỉ sửa file này.

## Stack
PHP 8.2 - 8.5, MariaDB, MySQL, NukeViet 5.x · Linux (almalinux, rockylinux, ubuntu), Nginx+PHP-FPM
Node.js v18.17+, NPM v10.5+, Composer v2.6+
GitLab/GitHub: `main` (prod) | `develop` (staging)

---

## Bảo mật — KHÔNG được vi phạm

| Tình huống | Đúng |
|---|---|
| Lấy input | `$nv_Request->get_int/get_title/get_editor()` — không `$_GET/$_POST` trực tiếp |
| SQL | PDO `prepare()` + `bindParam()` cho chuỗi từ user — số nguyên dùng `(int)` trực tiếp |
| HTML output | `nv_htmlspecialchars()` |
| Kiểm tra file | `nv_is_file()` — không `is_file()` với path từ user |
| Redirect | `$page_url` hoặc `nv_redirect_encrypt()` — không `$client_info['selfurl']` trực tiếp |
| Admin ghi | Kiểm tra `defined('NV_IS_ADMIN')` trước |

---

## Conventions

```
- Bảng đa ngôn ngữ : `NV_PREFIXLANG  . '_ten_bang'`  → `nv5_vi_news`
- Bảng dùng chung  : `NV_TABLEPREFIX . '_ten_bang'`  → `nv5_users`
Thời gian        : NV_CURRENTTIME
Đường dẫn gốc   : NV_ROOTDIR
URL              : ?lang=vi&nv=ten-module&op=ten-func
```

**Kiểm tra hằng bắt buộc đầu mỗi file:**
```
version.php              → NV_ADMIN + NV_MAINFILE
functions.php            → NV_SYSTEM
admin.functions.php      → NV_ADMIN + NV_MAINFILE + NV_IS_MODADMIN
admin.menu.php           → NV_ADMIN
action_mysql.php         → NV_IS_FILE_MODULES
global.functions.php     → NV_MAINFILE
funcs/main.php           → NV_IS_MOD_TENMODULE  (define trong functions.php)
admin/main.php           → NV_IS_FILE_ADMIN     (define trong admin.functions.php)
blocks/global.*.php (module) → NV_MAINFILE      (KHÔNG phải NV_IS_BLOCK_THEME)
blocks/module.*.php (module) → NV_MAINFILE      (chỉ active khi module đang chạy)
blocks/*.php (theme)     → NV_IS_BLOCK_THEME
Shared/*.php             → NV_MAINFILE
```

**$allow_func — hai pattern hợp lệ:**
```
Module đơn giản (page): $allow_func khai báo trong admin.functions.php
Module phức tạp (news): $allow_func khai báo trong admin.menu.php (cùng với $submenu)
```

**Hằng phân quyền quan trọng:**
```
NV_IS_ADMIN      → đã đăng nhập admin (bất kỳ level)
NV_IS_MODADMIN   → là admin của module hiện tại
NV_IS_SPADMIN    → super admin — dùng để kiểm soát tính năng đặc quyền
                   vd: $allow_func[] = 'config' chỉ khi defined('NV_IS_SPADMIN')
```

**Hằng bảng database:**
```
- `NV_PREFIXLANG`        → prefix bảng đa ngôn ngữ   (vd: `nv5_vi`)
- `NV_TABLEPREFIX`       → prefix bảng dùng chung      (vd: `nv5`)
NV_CONFIG_GLOBALTABLE→ bảng config toàn cục hệ thống (dùng cho comment, API key...)
```

**Code style:** 4 spaces · camelCase biến/hàm · PascalCase class · PHPDoc + comment tiếng Việt

---

## Cấu trúc Module

```
modules/ten-module/
├── version.php           # BẮT BUỘC — phiên bản dạng X.Y.ZZ (vd: 5.0.00)
├── functions.php         # BẮT BUỘC — không xóa dù rỗng; define NV_IS_MOD_*
├── admin.functions.php   # define NV_IS_FILE_ADMIN; $allow_func (module đơn giản)
├── admin.menu.php        # $submenu; $allow_func (module phức tạp — cả hai ở đây)
├── action_mysql.php      # $sql_create_module + $sql_drop_module
├── global.functions.php  # tùy chọn — hàm dùng chung frontend+admin (guard: NV_MAINFILE)
├── theme.php             # hàm giao diện ngoài site
├── funcs/main.php        # func mặc định ngoài site
├── admin/main.php        # func mặc định admin
├── Shared/               # PSR-4 classes: namespace NukeViet\Module\{name}\Shared\
└── language/vi.php · en.php · admin_vi.php · admin_en.php
```
Template `.tpl` → `themes/[theme]/modules/[module]/` — **KHÔNG** trong `modules/`

---

## Cấu trúc Theme

```
themes/ten-theme/
├── config.ini            # tên, layoutdefault, positions, setlayout, setblocks
├── config_default.php    # CSS defaults (guard: NV_MAINFILE)
├── config.php            # form tùy biến CSS admin (guard: NV_IS_FILE_THEMES)
├── theme.php             # guard: NV_SYSTEM + NV_MAINFILE; $theme_config['pagination']
├── css/custom.css        # ← CSS tùy chỉnh — load SAU CÙNG (override được tất cả)
├── js/custom.js          # ← JS tùy chỉnh
├── language/             # ngôn ngữ của theme
├── fonts/                # icon fonts
├── system/               # config.tpl, mail.tpl, error tpls
├── layout/
│   ├── block.default.tpl     # BẮT BUỘC — không xóa
│   ├── block.{style}.tpl     # primary, simple, border, no_title
│   ├── header_only.tpl · header_extended.tpl
│   ├── footer_only.tpl · footer_extended.tpl
│   ├── simple.tpl            # layout tối giản, không block positions
│   └── layout.{name}.tpl     # layout chính; include header/footer qua {FILE "..."}
├── blocks/global.TEN.{php,tpl,ini}  # block của theme (guard: NV_MAINFILE)
└── modules/ten-module/   # override tpl — chỉ copy khi thực sự cần sửa
```

**Layout grid 24 cột**: `main`=24 | `main-right`=18-6 | `left-main`=6-18 | `left-main-right`=5-13-6
**Block position** — config.ini dùng `<name>TAG</name>` và `<tag>[TAG]</tag>` (không phải `<n>`)
**config.ini `<setlayout>`** — gán layout cố định theo `module:func`
**config.ini `<setblocks>`** — blocks cài sẵn khi install theme
**Sau thay đổi config.ini:** Admin → Công cụ web → Làm sạch cache

---

## MySQL Patterns

```php
// READ — luôn dùng $db_slave cho SELECT
$db_slave->query($sql)->fetch()         // lấy 1 dòng
$db_slave->query($sql)->fetchAll()      // lấy tất cả dòng
$db_slave->query($sql)->fetchColumn()   // lấy ô đầu tiên (COUNT, MAX...)

// Query Builder (pattern phổ biến nhất trong codebase)
$db_slave->sqlreset()->select('*')->from(NV_PREFIXLANG . '_items')
    ->where('status=1')->order('weight ASC')->limit(10);
$rows = $db_slave->query($db_slave->sql())->fetchAll();

// WRITE — dùng $db cho INSERT/UPDATE/DELETE
$db->prepare($sql)                       // prepared statement cho user input (chuỗi)
$db->lastInsertId()                      // ID vừa INSERT
$db->insert_id($sql, '', $data)          // INSERT helper — trả về ID mới
$db->affected_rows_count($sql, $data)    // UPDATE/DELETE helper — trả về rowCount

// Cache query (ưu tiên dùng trước khi gọi $db_slave trực tiếp)
$nv_Cache->db($sql, $key_field, $module_name)   // trả về array, cache tự động
```

Số nguyên và hằng hệ thống nối thẳng vào SQL — chuỗi từ user input dùng `prepare()` + `bindParam()`.
Chi tiết: xem `.agent/skills/nukeviet-mysql/SKILL.md`

---

## Nâng cấp Module

Tài liệu chi tiết: `.agent/skills/nukeviet-upgrade/SKILL.md` và `.agent/upgrade/module/`

**Lộ trình — đọc file theo đúng thứ tự:**

| Lộ trình | File cần đọc (theo thứ tự) |
|---|---|
| 4.5.00 → 5.0.00 | `NV-4.5.00-len-5.0.00.md` |

---

## Nâng cấp Theme

Tài liệu chi tiết: `.agent/upgrade/theme/`
Cùng bảng lộ trình và tên file như module — chỉ khác thư mục.

---

## Git
Không push thẳng `main`/`develop` · Commit: `feat|fix|refactor|docs: mô tả [AI-assisted]` · MR cần 1 peer review

---

## Kiến trúc Core bổ sung (NukeViet 5)

Tính tương thích ngược (Backward Compatibility) trong NV5 được giữ lại tối đa:
- Các hàm/method cốt lõi (như `$nv_Request->get_int()`, `$db_slave`, `$nv_Cache`, `nv_htmlspecialchars()`) **hoàn toàn TRÙNG KHỚP** với bản cũ. Không có hàm nào trong danh sách `.agent/` bị xóa.

Sự khác biệt thực tế so với NV4 AI agent cần ghi nhớ:
- **Core Namespace**: Lớp lõi chuyển sang quản lý bằng Composer, sử dụng chuẩn PSR-4 (`NukeViet\Core\Request`, `NukeViet\Core\Database`, `NukeViet\Core\Cache`), mã nguồn đặt trong `src/includes/vendor/vinades/nukeviet/`. Dù vậy, các biến truyền thống (`$nv_Request`, `$db_slave`, `$nv_Cache`) vẫn được cung cấp như các object instance toàn cục.
- **Testing**: Không dùng PHPUnit/Test cơ bản nữa. Cần dùng Codeception: `php vendor/bin/codecept run Unit`.
- **Frontend CSS**: Phải dùng `npm install` và `npm run watch-admin` hoặc `npm run watch-core` để biên dịch (compile) SCSS. Khác biệt ở chỗ NV5 không chỉnh sửa trực tiếp các tệp `.css` thành phẩm.
- **Strict type**: Do code NV5 yêu cầu PHP 8.2+, cần chú ý tuân thủ type hint và strict types chặt chẽ hơn trong tương lai (`declare(strict_types=1);` ở một số thư viện lõi).
