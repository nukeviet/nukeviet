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

// Prepared statement (cho user input là chuỗi)
$db->prepare($sql)                // chuẩn bị statement, trả về PDOStatement
$db->lastInsertId()               // ID vừa INSERT

// Helper methods (gộp prepare + execute + result)
$db->insert_id($sql, '', $data)           // INSERT + trả về lastInsertId
$db->affected_rows_count($sql, $data)     // UPDATE/DELETE + trả về rowCount()

// Escape helpers
$db->dblikeescape($value)         // escape ký tự đặc biệt trong LIKE (%, _)
$db->regexpescape($value)         // escape ký tự đặc biệt trong REGEXP
$db->quote($value)                // PDO quote — dùng khi không thể dùng bindParam

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

## Query Builder — pattern phổ biến nhất

Thay vì nối chuỗi SQL thủ công, dùng Query Builder (luôn kết hợp với `$db_slave` cho SELECT):

```php
$db_slave->sqlreset()           // reset tất cả điều kiện cũ
    ->select('id, title, alias')
    ->from(NV_PREFIXLANG . '_items')
    ->where('status = 1')
    ->order('weight ASC')
    ->limit(10)
    ->offset(($page - 1) * 10);

$result = $db_slave->query($db_slave->sql());
while ($row = $result->fetch()) {
    // xử lý từng dòng
}

// Hoặc dùng fetchAll():
$rows = $db_slave->query($db_slave->sql())->fetchAll();
```

Có thể chain tiếp sau `sqlreset()` mà không cần reset lại (chỉ thay đổi clause cần thiết):
```php
// Đếm trước
$db_slave->sqlreset()->select('COUNT(*)')->from(NV_PREFIXLANG . '_items')->where('status=1');
$total = (int) $db_slave->query($db_slave->sql())->fetchColumn();

// Dùng lại, chỉ đổi select + thêm limit/offset
$db_slave->select('*')->order('weight ASC')->limit($per_page)->offset($offset);
$rows = $db_slave->query($db_slave->sql())->fetchAll();
```

Tất cả method Query Builder đều trả về `$this` nên chain được:

| Method | Tương đương SQL |
|---|---|
| `select('col1, col2')` | `SELECT col1, col2` |
| `from('table')` | `FROM table` |
| `join('LEFT JOIN t2 ON t1.id = t2.id')` | `LEFT JOIN ...` — truyền cả mệnh đề JOIN |
| `where('status = 1 AND id > 0')` | `WHERE ...` |
| `group('category_id')` | `GROUP BY category_id` |
| `having('COUNT(*) > 1')` | `HAVING COUNT(*) > 1` |
| `order('weight ASC')` | `ORDER BY weight ASC` |
| `limit(10)` | `LIMIT 10` |
| `offset(20)` | `OFFSET 20` |
| `sql()` | Trả về chuỗi SQL hoàn chỉnh |
| `sqlreset()` | Reset tất cả về rỗng — gọi trước mỗi query mới |

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
$db->sqlreset()->select('id, title, alias')->from(NV_PREFIXLANG . '_items')
    ->where('status = 1')->order('weight ASC')->limit(10);
$list = $nv_Cache->db($db->sql(), 'id', $module_name);
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
