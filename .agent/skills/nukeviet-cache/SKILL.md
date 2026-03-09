---
name: nukeviet-cache
description: Hệ thống Cache NukeViet 5.x. Load khi cần tối ưu hiệu năng, lưu trữ dữ liệu tạm, cache kết quả DB hoặc xóa cache module.
allowed-tools: Read, Write, Bash, Grep
---

# Hệ Thống Cache NukeViet 5.x

## 1. Tổng quan

Hệ thống Cache của NukeViet 5 giúp giảm tải cho cơ sở dữ liệu và tăng tốc độ phản hồi trang web.

| Thành phần | Chi tiết |
|---|---|
| **Biến toàn cục** | `$nv_Cache` (được khởi tạo trong `mainfile.php`) |
| **Driver hỗ trợ** | Files (mặc định), Memcached, Redis |
| **Cấu hình** | `$global_config['cached']` trong `data/config_global.php` |
| **Vị trí lưu (file)** | `NV_ROOTDIR . '/' . NV_CACHEDIR . '/{module_name}/'` |

---

## 2. Các phương thức cốt lõi

### `$nv_Cache->db()` — Cache truy vấn SQL
Đây là phương thức phổ biến nhất, dùng để cache mảng kết quả của một câu lệnh SELECT.

```php
$nv_Cache->db(
    string $sql,         // Câu lệnh SQL SELECT
    string $key = '',    // Tên trường làm key cho mảng kết quả ('' = mảng index số)
    string $moduleName,  // Tên module sở hữu cache này (để invalidate)
    string $lang = '',   // Ngôn ngữ (mặc định NV_LANG_DATA)
    int $ttl = 0         // Time-to-live (giây). 0 = vô hạn (cho đến khi bị xóa)
) : array
```

**Ví dụ:**
```php
$sql = 'SELECT id, title FROM ' . NV_PREFIXLANG . '_news WHERE status = 1';
$list = $nv_Cache->db($sql, 'id', 'news');
// Kết quả: [ '1' => ['id'=>1, 'title'=>'...'], '2' => [...] ]
```

---

### `$nv_Cache->setItem()` & `getItem()` — Cache dữ liệu tùy ý
Dùng để lưu trữ chuỗi, mảng hoặc đối tượng (cần serialize nếu không phải chuỗi).

```php
// Lưu cache
$nv_Cache->setItem(string $moduleName, string $fileName, string $content, string $lang = '', int $ttl = 0);

// Đọc cache
$content = $nv_Cache->getItem(string $moduleName, string $fileName, string $lang = '', int $ttl = 0);
```

**Ví dụ lưu mảng:**
```php
$data = ['name' => 'NukeViet', 'version' => '5.0'];
$nv_Cache->setItem('my_module', 'settings.cache', serialize($data));

// Đọc lại
$cache = $nv_Cache->getItem('my_module', 'settings.cache');
if ($cache !== false) {
    $data = unserialize($cache);
}
```

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
```php
$cache_file = 'hits_' . $id . '.cache';
if (($cache = $nv_Cache->getItem($module_name, $cache_file, '', 3600)) !== false) {
    $data = unserialize($cache);
} else {
    // Truy vấn DB và xử lý
    $data = ...;
    $nv_Cache->setItem($module_name, $cache_file, serialize($data));
}
```

### Invalidate Cache sau khi cập nhật dữ liệu
```php
// Sau khi UPDATE/INSERT/DELETE thành công
if ($sth->execute()) {
    $nv_Cache->delMod($module_name);
    // Hoặc nếu module có liên quan đến module khác (vd: menu)
    $nv_Cache->delMod('menu');
}
```

---

## 4. Lưu ý và Best Practices

1. **MD5 cho DB Cache**: Hệ thống dùng `md5($sql)` để tạo tên tệp cho `db()`. Nếu SQL thay đổi dù chỉ 1 khoảng trắng, cache cũ sẽ không được dùng. Nên dùng Query Builder `$db->sql()` để đảm bảo SQL ổn định.
2. **TTL (Time To Live)**: 
    - Với File Cache, TTL được kiểm tra khi đọc (`getItem`).
    - Với Memcached/Redis, TTL được thiết lập ngay khi ghi.
3. **Prefix**: Hệ thống tự động thêm `NV_CACHE_PREFIX` và ngôn ngữ vào tên key/file, bạn không cần tự thêm.
4. **Serialization**: Luôn `serialize` trước khi `setItem` và `unserialize` sau khi `getItem` nếu dữ liệu không phải là string thuần túy.
5. **Security**: Tên file cache phải kết thúc bằng `.cache`. Các driver NukeViet 5 có regex kiểm tra tính hợp lệ của tên file để tránh tấn công path traversal.
