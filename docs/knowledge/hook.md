# Hệ Thống Hook NukeViet 5.x

## 1. Tổng quan

Hook NukeViet 5 là cơ chế **event-driven** cho phép một module lắng nghe và xử lý sự kiện phát ra từ module khác hoặc từ hệ thống — **mà không cần sửa code core**.

| Khái niệm | Mô tả |
|---|---|
| **Tag** | Tên sự kiện (ví dụ: `'user_delete'`, `'before_detail_theme'`) |
| **Module phát sự kiện** | Module gọi `nv_apply_hook()` |
| **Module nhận sự kiện** | Module có file hook đăng ký qua `nv_add_hook()` |
| **Plugin/Hook file** | File PHP chứa callback, lưu trong `modules/*/hooks/` hoặc `includes/plugin/` |
| **Priority** | Số ưu tiên thực thi — số **càng cao** chạy **trước** (krsort) |

---

## 2. Hai hàm cốt lõi

### `nv_apply_hook()` — Phát sự kiện (Fire)

```php
nv_apply_hook(
    string $module,      // Module phát sự kiện. Rỗng '' = hệ thống
    string $tag,         // Tên event tag
    array  $args = [],   // Tham số truyền cho callback (luôn là mảng)
    mixed  $default = null, // Giá trị trả về mặc định nếu không có hook
    int    $return_type = 0  // 0: lấy kết quả cuối. 1: array_merge. 2: array_merge_recursive
)
```

**Cách callback nhận tham số:**
```php
// Signature bắt buộc của mọi callback hook
$callback = function (&$args, $from_data, $receive_data) {
    // $args = mảng tham số từ $args của nv_apply_hook()
    //        + $args['pid'] được tự động thêm vào (ID trong DB)
    // $from_data = ['module_name' => ..., 'module_info' => ...] — module phát sự kiện
    // $receive_data = ['module_name' => ..., 'module_info' => ...] — module nhận
    return $result; // null = bỏ qua, non-null = ghi nhận
};
```

### `nv_add_hook()` — Đăng ký lắng nghe (Listen)

```php
nv_add_hook(
    string $module_name, // Module xảy ra event (phải khớp với $module ở nv_apply_hook)
    string $tag,         // TAG phải khớp chính xác
    int    $priority,    // Ưu tiên — số cao chạy trước (biến $priority từ DB)
    mixed  $callback,    // Hàm/closure xử lý
    string $hook_module = '', // Module nhận sở hữu hook này
    int    $pid = 0          // ID quản lý trong DB (biến $pid từ DB)
)
```

---

## 3. Cấu trúc file hook

### Vị trí file
```
modules/ten-module/hooks/
└── ten_file_hook.php      # hook của module
```
hoặc:
```
includes/plugin/
└── ten_plugin.php         # hook hệ thống (plugin toàn cục)
```

> **Tham khảo Template file hook chuẩn:** `docs/knowledge/examples/hook/TemplateHook.php`

> ⚠️ **QUAN TRỌNG:** `nv_add_hook()` **BẮT BUỘC** gọi ở **cuối file** sau khi đã định nghĩa `$callback`.

---

## 4. Ví dụ thực tế

### Ví dụ 1 — Hook xóa dữ liệu khi user bị xóa
> **Tham khảo code mẫu:** `docs/knowledge/examples/hook/ExampleUserDelete.php`

### Ví dụ 2 — Hook chỉnh sửa nội dung trước khi render detail
> **Tham khảo code mẫu:** `docs/knowledge/examples/hook/ExampleBeforeDetailTheme.php`

### Ví dụ 3 — Hook hệ thống `change_site_buffer`
> **Tham khảo code mẫu:** `docs/knowledge/examples/hook/ExampleChangeSiteBuffer.php`

### Ví dụ 4 — Hook với `return_type = 1` (array_merge)
> **Tham khảo code mẫu:** `docs/knowledge/examples/hook/ExampleReturnType.php`

---

## 5. Danh sách Hook phổ biến

### Hook hệ thống (module = `''`)

| Tag | Nơi phát | Mô tả |
|---|---|---|
| `change_site_buffer` | `includes/footer.php` | Can thiệp toàn bộ HTML output cuối trang |
| `check_server` | `includes/mainfile.php` | Kiểm tra cấu hình server khi khởi động |
| `db_slave_connect` | `includes/mainfile.php` | Thay thế/cấu hình kết nối DB slave |
| `get_qr_code` | `index.php` | Can thiệp vào quá trình tạo QR code |
| `get_rewrite_domain` | `includes/functions.php` | Thay đổi domain khi rewrite URL |
| `modify_global_config` | `includes/mainfile.php` | Chỉnh sửa `$global_config` sau khi load |
| `modify_sso_login_url` | `modules/users/funcs/login.php` | Can thiệp URL SSO login |
| `before_rss_output_xml` | `includes/core/theme_functions.php` | Chỉnh sửa nội dung RSS trước khi xuất XML |
| `generating_sitemap_module` | `includes/core/theme_functions.php` | Can thiệp vào tạo sitemap |
| `nv_redirect_location` | `includes/functions.php` | Can thiệp trước khi redirect |
| `sendmail_others_actions` | `includes/functions.php` | Thực hiện hành động thêm sau khi gửi mail |
| `get_email_merge_fields` | `includes/functions.php` | Đăng ký trường merge cho email template |
| `get_email_data_before_fetch` | `includes/functions.php` | Chỉnh sửa email data trước khi render |
| `sector1` → `sector5` | Nhiều nơi | Hook rỗng (slot mở rộng kiến trúc) |
| `cron_user_datadeletion_handling` | `includes/cronjobs/` | Cron xử lý xóa dữ liệu user theo yêu cầu |

### Hook module `users`

| Tag | Mô tả |
|---|---|
| `user_delete` | Sau khi xóa user — xóa dữ liệu liên quan |
| `admin_active_account` | Trước khi admin duyệt tài khoản |
| `check_email_already_exists` | Kiểm tra email trùng lặp tùy chỉnh |
| `user_change_password` | Sau khi user đổi mật khẩu |
| `user_lostpass_success` | Sau khi khôi phục mật khẩu thành công |
| `custom_login_openid_attribs` | Tùy biến thuộc tính OpenID khi login |
| `prepare_user_data_deletion` | Chuẩn bị dữ liệu trước khi xóa tài khoản |
| `user_remove_2step` | Khi user xóa xác thực 2 bước |

### Hook module `news` / `page`

| Tag | Mô tả |
|---|---|
| `before_detail_theme` | Trước khi render trang chi tiết bài viết |
| `before_redirect_external_link` | Trước khi redirect đến link ngoài (news) |

### Hook module `inform`

| Tag | Mô tả |
|---|---|
| `get_list_inform` | Lấy danh sách thông báo |
| `inform_get_list_after` | Sau khi lấy danh sách thông báo |
| `set_status_inform` | Đổi trạng thái thông báo |
| `get_all_inform_link` | Lấy URL trang xem tất cả thông báo |

### Hook module `feeds`

| Tag | Mô tả |
|---|---|
| `before_generate_rss` | Trước khi tạo RSS feed |

---

## 6. Đăng ký Hook qua Admin UI

Hook không gọi `nv_add_hook()` trực tiếp trong code module. Thay vào đó, hệ thống dùng bảng `{prefix}_plugins` trong CSDL:

| Cột | Ý nghĩa |
|---|---|
| `hook_module` | Module phát sự kiện (string, hoặc rỗng = hệ thống) |
| `plugin_area` | Tag (tên event) |
| `plugin_file` | Tên file hook |
| `plugin_module_file` | Module chứa file hook (rỗng = `includes/plugin/`) |
| `plugin_module_name` | Module nhận dữ liệu |
| `weight` | Priority (số cao chạy trước theo krsort) |
| `plugin_lang` | `'all'` hoặc locale cụ thể (`'vi'`, `'en'`) |
| `pid` | ID row (tự động inject vào callback khi gọi) |

> Hook được khai báo qua **Admin → Công cụ web → Plugin** và hệ thống tự load file tương ứng theo `$nv_plugins`.

---

## 7. Luồng khi hook được load

```
mainfile.php load $nv_plugins (từ config_global.php cache)
    ↓
Khi module chạy: Core require các file hook tương ứng
    ↓
Mỗi file hook: định nghĩa $callback + gọi nv_add_hook()
    ↓
nv_add_hook() → đăng ký vào $nv_hooks[$module][$tag][$priority][]
    ↓
Khi module phát sự kiện: gọi nv_apply_hook($module, $tag, $args)
    ↓
nv_apply_hook() → foreach $nv_hooks[$module][$tag] theo priority (desc)
    → call_user_func_array($callback, [&$args, $from_data, $receive_data])
```

---

## 8. Checklist tạo hook file

- [ ] Guard `if (!defined('NV_MAINFILE'))` ở đầu file
- [ ] `$callback` là closure (anonymous function) hoặc named function
- [ ] Signature callback đúng: `function ($args, $from_data, $receive_data)`
- [ ] `nv_add_hook()` gọi ở **cuối file** sau khi đã khai báo `$callback`
- [ ] `$module_name`, `$hook_module`, `$priority`, `$pid` không khai báo trong file — được inject tự động từ hệ thống
- [ ] File đặt trong `modules/ten-module/hooks/` (không phải trong `funcs/` hay `blocks/`)
- [ ] Nếu hook có thể return null (khi không xử lý) — **luôn return null** thay vì bỏ trống
- [ ] Đăng ký trong Admin UI → Plugin sau khi tạo file
