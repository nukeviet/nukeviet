# Hướng Dẫn MySQL NukeViet 5.x

## Prefix bảng — không hardcode

```php
NV_PREFIXLANG  . '_ten_bang'   // bảng đa ngôn ngữ  → nv5_vi_news
NV_TABLEPREFIX . '_ten_bang'   // bảng dùng chung   → nv5_users
// ❌ Không viết: 'nv5_vi_news'
```

---

## $db và $db_slave

NukeViet cung cấp **hai** biến database:

| Biến | Dùng cho |
|---|---|
| `$db` | WRITE — INSERT, UPDATE, DELETE, và SELECT cần fresh data |
| `$db_slave` | READ — SELECT thông thường (tối ưu cho slave DB hoặc caching) |

Trong thực tế môi trường single-server, `$db_slave` trỏ cùng server với `$db`. Tuy nhiên **luôn dùng `$db_slave` cho SELECT** ở frontend/block để code sẵn sàng scale, không bắt buộc ở admin.

---

## $db — method hay dùng

```php
// Chạy query trực tiếp
$db->query($sql)                  // trả về PDOStatement
$db->query($sql)->fetch()         // lấy 1 dòng (associative array)
$db->query($sql)->fetchAll()      // lấy tất cả dòng
$db->query($sql)->fetchColumn()   // lấy giá trị ô đầu tiên (COUNT, MAX...)

// Prepared statement (cho user input)
$db->prepare($sql)                // chuẩn bị statement, trả về PDOStatement
$db->lastInsertId()               // ID vừa INSERT

// Escape helpers
$db->dblikeescape($value)         // escape ký tự đặc biệt trong LIKE (%, _)
$db->regexpescape($value)         // escape ký tự đặc biệt trong REGEXP
$db->quote($value)                // PDO quote — dùng khi không thể dùng bindValue (ví dụ: query() trực tiếp)

// closeCursor — giải phóng connection sau while-loop (quan trọng khi có nhiều query song song)
$result = $db_slave->query($sql);
while ($row = $result->fetch()) {
    // xử lý
}
$result->closeCursor();

// fetchAll với numeric index — dùng với list() destructuring
$rows = $db_slave->query($sql)->fetchAll(PDO::FETCH_NUM);
foreach ($rows as [$id, $title, $alias]) {
    // truy cập theo thứ tự cột SELECT
}
```

---

## Pattern: TRUY VẤN AN TOÀN (PDO)

NukeViet 5.0 không còn sử dụng Query Builder. Mọi truy vấn có tham số từ người dùng **PHẢI** sử dụng Placeholders và Prepared Statements.

### Mục tiêu
1. Chống SQL Injection tuyệt đối.
2. Tối ưu hiệu suất bằng cách tái sử dụng Statement.
3. Code sạch, dễ bảo trì.

### Các bước thực hiện
1. Viết SQL với các placeholder dạng `:name`.
2. Dùng `$db->prepare($sql)` để chuẩn bị.
3. Dùng `$sth->bindValue(':name', $value, $type)` để gán giá trị.
4. Dùng `$sth->execute()` để thực thi.

---

## Pattern: SELECT

```php
// Nhiều dòng — dùng $db_slave
$sql  = 'SELECT id, title FROM ' . NV_PREFIXLANG . '_items WHERE status = 1 ORDER BY weight ASC';
$rows = $db_slave->query($sql)->fetchAll();

// 1 dòng — dùng $db_slave
$sql = 'SELECT * FROM ' . NV_PREFIXLANG . '_items WHERE id = ' . (int) $id . ' LIMIT 1';
$row = $db_slave->query($sql)->fetch();

// 1 giá trị (COUNT, MAX...) — dùng $db_slave
$total = (int) $db_slave->query('SELECT COUNT(*) FROM ' . NV_PREFIXLANG . '_items')->fetchColumn();
$max   = (int) $db_slave->query('SELECT MAX(weight) FROM ' . NV_PREFIXLANG . '_items')->fetchColumn();
```

## Pattern: phân trang
> **Tham khảo code mẫu:** `docs/knowledge/examples/mysql/PatternPagination.php`

---

## Pattern: INSERT / UPDATE an toàn
**Quy tắc:** số nguyên và hằng hệ thống nối thẳng vào SQL — dữ liệu từ user input dùng `:named_param` + `bindParam`.
> **Tham khảo code mẫu INSERT/UPDATE/DELETE:** `docs/knowledge/examples/mysql/PatternWrite.php`

### LIKE query an toàn
> **Tham khảo code mẫu query LIKE:** `docs/knowledge/examples/mysql/PatternLike.php`

---

## Schema bảng chuẩn (dùng trong action_mysql.php)
> **Tham khảo chuỗi CREATE TABLE chuẩn:** `docs/knowledge/examples/mysql/Schema.php`

---

## $nv_Cache — cache kết quả query

Dùng `$nv_Cache->db()` thay cho `$db_slave->query()` trực tiếp khi dữ liệu ít thay đổi (config, danh sách tĩnh, block):

```php
// Signature: $nv_Cache->db($sql, $key_field, $module_name)
// - $key_field: tên cột làm key array kết quả ('' = numeric index)
// - $module_name: dùng để invalidate cache khi module cập nhật

// Lấy config module (key_field = 'config_name', dùng '' để lấy numeric array)
$sql  = 'SELECT config_name, config_value FROM ' . NV_PREFIXLANG . '_' . $module_data . '_config';
$list = $nv_Cache->db($sql, '', $module_name);
$config = [];
foreach ($list as $row) {
    $config[$row['config_name']] = $row['config_value'];
}

// Lấy danh sách với key = 'id'
$sql = 'SELECT id, title, alias FROM ' . NV_PREFIXLANG . '_items WHERE status = 1 ORDER BY weight ASC LIMIT 10';
$list = $nv_Cache->db($sql, 'id', $module_name);
// $list['5'] = ['id' => 5, 'title' => '...', 'alias' => '...']
```

> Hướng dẫn đầy đủ về hệ thống cache: xem tại `docs/knowledge/cache.md`.

> Cache bị xóa tự động khi module admin thực hiện thao tác ghi nếu tuân thủ pattern chuẩn. Nếu tự viết logic ghi DB, hãy dùng `$nv_Cache->delMod($module_name)`.

---

## Tối ưu — dấu hiệu cần xử lý

| Vấn đề | Giải pháp |
|---|---|
| `LIKE '%keyword%'` trên cột lớn | Thêm FULLTEXT INDEX |
| Query nằm trong vòng lặp | Dùng `IN (id1, id2, ...)` một lần |
| `SELECT *` | Chỉ SELECT cột thực sự cần |
| Lọc/sort mà không có INDEX | Thêm composite index phù hợp |
