# Hướng Dẫn NukeViet API (NukeViet 5.x)

NukeViet 5 được tích hợp sẵn hệ thống **API** với 2 cơ chế gọi:

- **Remote API**: Gọi qua HTTP thông qua file `api.php` (từ bên ngoài, Mobile App, hệ thống khác).
- **Local API**: Gọi trực tiếp trong PHP qua hàm `nv_local_api()` (nội bộ, cùng tiến trình, không qua HTTP).

Cả 2 cơ chế **dùng chung API class**, nghĩa là viết 1 class API → gọi được cả Remote lẫn Local.

Hệ thống Remote API được chia làm 2 phân vùng quyền rõ rệt:

1. **Admin API (`\NukeViet\Api\IApi`)**: Dành cho quản trị viên, đòi hỏi xác thực chặt chẽ (IP, Lev, Quota). Các file được lưu trong `src/modules/[moduleName]/Api/[Action].php`.
2. **User API (`\NukeViet\Uapi\UiApi`)**: Dành cho public hoặc User bình thường, vẫn kiểm soát Quota nhưng bảo mật lỏng hơn (Ví dụ: Ứng dụng mobile cho khách hàng). Các file được lưu trong `src/modules/[moduleName]/Uapi/[Action].php`.

---

## 1. Flow tạo API Endpoint

Bất cứ class API nào của một module (Ví dụ: `news`, action: `get_list`) cũng **BẮT BUỘC** phải:
1. Nằm trong đúng namespace PSR-4: `NukeViet\Module\[moduleName]\Api` (hoặc `Uapi`).
2. Implements interface `\NukeViet\Api\IApi` (hoặc `\NukeViet\Uapi\UiApi`).
3. Khai báo hàm tĩnh `getCat()` để trả về chuỗi cấu hình danh mục.
4. Triển khai phương thức `setResultHander($result)` để nhận biến kết quả.
5. Triển khai phương thức `execute()` chứa logic chính, cuối cùng trả về chuỗi JSON thông qua biến `$result`.

---

## 2. Admin API Template (Api)

**File lưu tại:** `modules/ten-module/Api/TenAction.php`
> **Tham khảo code mẫu hoàn chỉnh:** `docs/knowledge/examples/api/AdminApi.php`

---

## 3. User API Template (Uapi)

**File lưu tại:** `modules/ten-module/Uapi/TenAction.php`
> **Tham khảo code mẫu hoàn chỉnh:** `docs/knowledge/examples/api/UserApi.php`

---

## 4. Cấu hình CSDL và Phân quyền API
Để một API hoạt động và không bị cảnh báo "Api Lang Not Found" hay "Api module not found", DEV phải vào khu vực Admin CMS NukeViet 5 thiết lập thủ công:
1. Cấu hình Bật API toàn cục trong cấu hình site.
2. Tại khu vực **Công cụ web > Điều khiển API**:
    - Tạo `Credential` (Key + Secret + Ủy quyền IP).
    - Tạo `Vai trò (Roles)` cấp phát API theo phương thức phân luồng (chỉ chọn đúng action của Module được cấp phép).
    - Quy định hạn ngạch sử dụng (Quota / Rate Limiting) cho Vai trò đó.
    - Cấp Vai trò trên cho Credential đã tạo.

## 5. Test Gọi API (Client Example)
> **Tham khảo cách dùng `DoApi` class:** `docs/knowledge/examples/api/ClientDoApi.php`

---

## Đa Ngôn Ngữ trong API Endpoint

Khi API cần trả về nội dung ngôn ngữ hoặc đọc chuỗi `$lang_module`, cần load thủ công trong phương thức `execute()`:

```php
public function execute()
{
    global $nv_Lang;

    // Load ngôn ngữ module trước khi dùng $lang_module
    // (trong luồng API, Core không tự load ngôn ngữ module)
    $nv_Lang->loadModule($module_info['module_file'], false, true); // api.php đã gọi khi adminLev+module
    // Nếu Uapi module: cũng đã được load tự động trong src/api.php
    // Chỉ cần load thủ công khi viết system Api không gắn với module
    // Tham số thứ 3 = true để load tạm, tránh đè mất ngôn ngữ chính của module hiện tại (nếu có)

    // Sau đó dùng bình thường
    $message = $nv_Lang->getModule('hello');

    // Trước khi kết thúc cần hủy lang tạm
    $nv_Lang->changeLang();

    // ...
}
```

> **Lưu ý:** Theo `api.php`, khi `!empty($api_request['module'])`, hệ thống đã tự gọi `$nv_Lang->loadModule()` nên DEV không phải load lại. Chỉ cần tự load khi viết API hệ thống (`apidir = 'Uapi'`, không có module).

---

## 6. Local API (`nv_local_api`)

### 6.1. Local API là gì?

Local API cho phép **gọi cùng class API** (Admin API `IApi`) trực tiếp trong PHP mà **không qua HTTP**. Hệ thống sẽ:
1. Resolve class API từ namespace
2. Kiểm tra quyền admin (theo admin đang đăng nhập hoặc theo `$adminidentity` truyền vào)
3. Inject `$params` vào `$_POST` → chạy `$api->execute()` → khôi phục `$_POST`
4. Trả về chuỗi JSON kết quả

### 6.2. Signature

```php
function nv_local_api($cmd, $params, $adminidentity = '', $module = '')
```

| Tham số | Kiểu | Mô tả |
|---|---|---|
| `$cmd` | string | Tên class API (PascalCase). Ví dụ: `'GetList'`, `'CreateItem'` |
| `$params` | array | Mảng dữ liệu truyền vào API (sẽ được inject vào `$_POST`) |
| `$adminidentity` | string | Username hoặc userid admin. Rỗng = dùng admin đang đăng nhập |
| `$module` | string | Tên module. Rỗng = gọi API hệ thống (`NukeViet\Api\{$cmd}`) |

**Trả về:** Chuỗi JSON (cần `json_decode` để xử lý).

### 6.3. So sánh Remote vs Local API

| Tiêu chí | Remote API | Local API |
|---|---|---|
| **Cơ chế** | HTTP request qua `api.php` | Gọi trực tiếp trong PHP |
| **Class API** | Dùng chung | Dùng chung |
| **Xác thực** | API Key + Secret + IP | Admin session hoặc `$adminidentity` |
| **Hiệu suất** | Chậm hơn (qua HTTP) | Nhanh (in-process) |
| **Khi nào dùng** | Mobile App, hệ thống ngoài, SPA | Module gọi chéo, admin function, tái sử dụng logic |

### 6.4. Các pattern sử dụng phổ biến (Local API)
> **Tham khảo các cách gọi Local API thường gặp:** `docs/knowledge/examples/api/LocalApiPattern.php`

### 6.6. Lưu ý quan trọng

- `nv_local_api()` chỉ hỗ trợ class **Admin API** (`IApi`). Không hỗ trợ gọi Uapi.
- Kết quả trả về là **chuỗi JSON**, luôn cần `json_decode($result, true)` trước khi sử dụng.
- Kiểm tra `$result['code'] == '0000'` để biết thành công hay thất bại.
- Hàm tự động backup/restore `$_POST`, nên an toàn khi gọi giữa chừng trong function.
