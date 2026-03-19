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

### `$nv_Cache->db()` — Cache truy vấn SQL
Đây là phương thức phổ biến nhất, dùng để cache mảng kết quả của một câu lệnh SELECT.
> **Tham khảo cú pháp `$nv_Cache->db()`:** `docs/knowledge/examples/cache/CacheDb.php`

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

1. **MD5 cho DB Cache**: Hệ thống dùng `md5($sql)` để tạo tên tệp cho `db()`. Nếu SQL thay đổi dù chỉ 1 khoảng trắng, cache cũ sẽ không được dùng. Nên dùng Query Builder `$db->sql()` để đảm bảo SQL ổn định.
2. **TTL (Time To Live)**:
    - Với File Cache, TTL được kiểm tra khi đọc (`getItem`).
    - Với Memcached/Redis, TTL được thiết lập ngay khi ghi.
3. **Prefix**: Hệ thống tự động thêm `NV_CACHE_PREFIX` và ngôn ngữ vào tên key/file, bạn không cần tự thêm.
4. **Serialization**: Luôn `json_encode` trước khi `setItem` và `json_decode` sau khi `getItem` nếu dữ liệu không phải là string thuần túy.
5. **Security**: Tên file cache phải kết thúc bằng `.cache`. Các driver NukeViet 5 có regex kiểm tra tính hợp lệ của tên file để tránh tấn công path traversal.
