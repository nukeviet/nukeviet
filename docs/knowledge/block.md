# Hướng Dẫn NukeViet Block (NukeViet 5.x)

Hệ thống Block của NukeViet 5 đóng vai trò mảng ghép giao diện, được quản lý dựa trên **chuẩn cấu trúc đa tệp**(`.php` xử lý logic, `.json` chứa config/đa ngôn ngữ, `.tpl` hoặc `XTemplate` hiển thị).
Có 2 loại Block chính: **Module Block** và **Theme Block**.

---

## 1. Module Block
Block trực thuộc một module, chỉ được quản lý cài đặt khi module đó được cài đặt. Khi gọi, **Block Module** ưu tiên dùng `XTemplate` lấy file `.tpl` nằm ở thư mục `themes/`.

**Vị trí file:**
- Logic: `modules/[module_name]/blocks/global.[block_name].php`
- Cấu hình: `modules/[module_name]/blocks/global.[block_name].json`
- Giao diện: Tại thư mục theme kích hoạt `themes/[theme_name]/modules/[module_name]/block.[block_name].tpl`

> **Lưu ý tên file:** file php bắt đầu bằng `global.` hoặc `module.` (Ví dụ `global.about.php`), nhưng tpl tương ứng thường đặt tên là `block.about.tpl`.

### 1.1 Nội dung file `.json` mẫu
Cung cấp thông tin hiển thị định danh cho Block trong màn hình cài đặt Admin.
> **Tham khảo JSON mẫu:** `docs/knowledge/examples/block/ModuleBlock.json`

### 1.2 Nội dung file `.php` mẫu
> **Tham khảo mã nguồn PHP Block mẫu:** `docs/knowledge/examples/block/ModuleBlock.php`

---

## 2. Theme Block
Block trực thuộc Theme, sử dụng cho các tính năng hệ thống/layout (như Menu Footer, QRCode, Custom HTML). Khi gọi hiển thị, **Block Theme** dùng `NVSmarty` trỏ thẳng tới file `.tpl` nằm ở thư mục con `smarty/`.

**Vị trí file:**
- Logic: `themes/[theme_name]/blocks/global.[block_name].php`
- Cấu hình: `themes/[theme_name]/blocks/global.[block_name].json`
- Giao diện: `themes/[theme_name]/blocks/smarty/global.[block_name].tpl`

### 2.1 File `.php` kết hợp NVSmarty mẫu
Theme Blocks sử dụng \NukeViet\Template\NVSmarty để parse.
> **Tham khảo mã nguồn PHP Block Theme mẫu:** `docs/knowledge/examples/block/ThemeBlock.php`

### 2.2 File `smarty/global.theme_example.tpl` mẫu
Sử dụng cú pháp của Smarty `{...}` thay vì XTemplate `{...}`
> **Tham khảo Smarty TPL mẫu:** `docs/knowledge/examples/block/ThemeBlock.tpl`

---

## Quy tắc Bắt Buộc
- **Security Check**: Tất cả file php của Block phải có `if (!defined('NV_SYSTEM'))` (hoặc NV_MAINFILE) ở trên cùng.
- **Biến trả về**: Biến để Core nhận là `$content`, luôn luôn phải gán `$content = function_block($block_config);` ở cuối file PHP.
- **Block Config**: Biến toàn cục từ hệ thống cấp phát cho block luôn mang tên `$block_config`. Hàm của bạn BẮT BUỘC nhận tham số đầu vào này.

---

## Ngôn Ngữ trong Block

### Các biến ngôn ngữ có sẵn trong Block

| Biến | Nguồn | Phạm vi |
|---|---|---|
| `$lang_global` | `includes/language/{locale}/global.php` | Sẵn có — chuỗi toàn hệ thống |
| `$lang_module` | `modules/{module}/language/{locale}.php` | Chỉ có khi block thuộc module đang active |
| `$lang_block` | File `.json` của block (section `i18n`) | Chuỗi config UI của block cụ thể |

> **Module Block** (`global.TEN.php`, `module.TEN.php`): `$lang_global` luôn có. `$lang_module` có nếu module đang được load trên trang đó.

### Khai báo i18n trong file `.json` của block
> **Tham khảo mẫu i18n JSON:** `docs/knowledge/examples/block/BlockI18n.json`

`$lang_block` sẽ chứa section `config` của ngôn ngữ hiện tại — dùng trong hàm config block.

### Dùng ngôn ngữ trong hàm config và render
> **Tham khảo cách dùng `$lang_block` và render Template:** `docs/knowledge/examples/block/BlockLanguagePHP.php`
