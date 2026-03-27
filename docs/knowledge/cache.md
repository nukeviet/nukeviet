# Hệ Thống Cache NukeViet 5.x

## 1. Tổng quan

Hệ thống Cache của NukeViet 5 giúp giảm tải cho cơ sở dữ liệu và tăng tốc độ phản hồi trang web.

| Thành phần | Chi tiết |
|---|---|
| **Biến toàn cục** | `$nv_Cache` (được khởi tạo trong `includes/mainfile.php`) |
| **Driver hỗ trợ** | Files (mặc định), Memcached, Redis |
| **Cấu hình** | `$global_config['cached']` trong `data/config/config_global.php` |
| **Vị trí lưu (file)** | `NV_ROOTDIR . '/' . NV_CACHEDIR . '/{module_name}/'` |

---

## 2. Các phương thức cốt lõi

### `$nv_Cache->db()` — Cache truy vấn SQL (Hỗ trợ Prepared Statements)
Đây là phương thức phổ biến nhất, dùng để cache mảng kết quả của một câu lệnh SELECT. Từ NukeViet 5.x, phương thức này đã hỗ trợ truyền mảng tham số `bind` để bảo mật chống SQL Injection và tối ưu hóa truy vấn.

**Cú pháp:**
```php
$nv_Cache->db(string $sql, string $key, string $moduleName, string $lang = '', int $ttl = 0, array $bind = []): array;
```

**Tham số `$bind`:**
Để đảm bảo tính chặt chẽ, mảng `$bind` yêu cầu mỗi phần tử là một mảng con gồm 3 giá trị: `[tên_placeholder, giá trị, kiểu_dữ_liệu]`.

**Ví dụ:**
```php
$sql = 'SELECT * FROM ' . NV_USERS_TABLE . ' WHERE userid = :userid AND status = :status';
$bind = [
    [':userid', 1, PDO::PARAM_INT],
    [':status', 1, PDO::PARAM_INT]
];
$user_data = $nv_Cache->db($sql, 'userid', 'users', 'vi', 0, $bind);
```

---

### `$nv_Cache->setItem()` & `getItem()` — Cache dữ liệu tùy ý
Dùng để lưu trữ chuỗi, mảng hoặc đối tượng (cần serialize nếu không phải chuỗi).
> **Tham khảo cú pháp SetItem/GetItem:** `docs/knowledge/examples/cache/CacheItem.php`

---

### `$nv_Cache->delMod()` — Xóa cache theo module
Xóa toàn bộ các tệp cache thuộc về một module cụ thể. Thường gọi sau khi có thay đổi dữ liệu (INSERT, UPDATE, DELETE).

```php
$nv_Cache->delMod(string $moduleName, string $lang = '');
```

**Lưu ý quan trọng:** Trong NukeViet 5, hệ thống tự động xóa cache module khi thực hiện các thao tác quản trị trong Admin nếu module đó tuân thủ đúng pattern CRUD. Nếu bạn tự viết logic cập nhật DB, **PHẢI** gọi hàm này.

---

### `$nv_Cache->delItem()` & `delAll()`

- `$nv_Cache->delItem($module, $file)`: Xóa một tệp cache cụ thể.
- `$nv_Cache->delAll(bool $sys = true)`:
    - `true`: Xóa sạch sành sanh bộ nhớ đệm.
    - `false`: Chỉ xóa bộ nhớ đệm của ngôn ngữ hiện tại.

---

## 3. Pattern sử dụng chuẩn

### Trong Block hoặc Func hiển thị
> **Tham khảo Pattern Block Display:** `docs/knowledge/examples/cache/PatternDisplay.php`

### Invalidate Cache sau khi cập nhật dữ liệu
> **Tham khảo Pattern Cache Invalidate:** `docs/knowledge/examples/cache/PatternInvalidate.php`

---

## 4. Lưu ý và Best Practices

1. **Khóa Cache (Key/Filename)**: Hệ thống dùng `md5($sql . serialize($bind))` để tạo định danh cho `db()`. Điều này đảm bảo rằng các truy vấn cùng SQL nhưng khác tham số `bind` sẽ được lưu trữ riêng biệt, tránh xung đột dữ liệu.
2. **TTL (Time To Live)**:
    - Với File Cache, TTL được kiểm tra khi đọc (`getItem`).
    - Với Memcached/Redis, TTL được thiết lập ngay khi ghi.
3. **Prefix**: Hệ thống tự động thêm `NV_CACHE_PREFIX` và ngôn ngữ vào tên key/file, bạn không cần tự thêm.
4. **Serialization**: Luôn `json_encode` trước khi `setItem` và `json_decode` sau khi `getItem` nếu dữ liệu không phải là string thuần túy.
5. **Security**: Tên file cache phải kết thúc bằng `.cache`. Các driver NukeViet 5 có regex kiểm tra tính hợp lệ của tên file để tránh tấn công path traversal.
