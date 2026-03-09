---
name: nukeviet-mysql
description: MySQL NukeViet 5.x. Load khi viết query, thiết kế schema bảng, tối ưu truy vấn.
allowed-tools: Read, Write, Bash, Grep
---


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

Trong thực tế môi trường single-server, `$db_slave` trỏ cùng server với `$db`. Tuy nhiên **luôn dùng `$db_slave` cho SELECT** để code sẵn sàng scale.

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

```php
// Dùng Query Builder (chuẩn)
$db_slave->sqlreset()
    ->select('COUNT(*)')
    ->from(NV_PREFIXLANG . '_items')
    ->where('status = 1');
$total = (int) $db_slave->query($db_slave->sql())->fetchColumn();

if ($total > 0) {
    $offset = ($page - 1) * $perPage;
    $db_slave->select('id, title, alias, created_at')
        ->order('created_at DESC')
        ->limit($perPage)
        ->offset($offset);
    $rows = $db_slave->query($db_slave->sql())->fetchAll();
}

// Hoặc dùng SQL thuần (cũng được)
$where = ' WHERE status = 1';
$total = (int) $db_slave->query('SELECT COUNT(*) FROM ' . NV_PREFIXLANG . '_items' . $where)->fetchColumn();

if ($total > 0) {
    $offset = ($page - 1) * $perPage;
    $sql    = 'SELECT id, title, alias, created_at'
            . ' FROM '  . NV_PREFIXLANG . '_items' . $where
            . ' ORDER BY created_at DESC'
            . ' LIMIT ' . $perPage . ' OFFSET ' . $offset;
    $rows = $db_slave->query($sql)->fetchAll();
}
```

---

## Pattern: INSERT / UPDATE an toàn

**Quy tắc:** số nguyên và hằng hệ thống nối thẳng vào SQL — dữ liệu từ user input dùng `:named_param` + `bindParam`.

```php
// INSERT
$sql = 'INSERT INTO ' . NV_PREFIXLANG . '_items (title, content, status, created_at)
        VALUES (:title, :content, :status, ' . NV_CURRENTTIME . ')';
$sth = $db->prepare($sql);
$sth->bindParam(':title',   $row['title'],   PDO::PARAM_STR);
$sth->bindParam(':content', $row['content'], PDO::PARAM_STR, strlen($row['content']));
$sth->bindParam(':status',  $row['status'],  PDO::PARAM_INT);
$sth->execute();
$new_id = $db->lastInsertId();

// UPDATE
$sql = 'UPDATE ' . NV_PREFIXLANG . '_items
        SET title = :title, content = :content, updated_at = ' . NV_CURRENTTIME . '
        WHERE id = ' . $id;
$sth = $db->prepare($sql);
$sth->bindParam(':title',   $row['title'],   PDO::PARAM_STR);
$sth->bindParam(':content', $row['content'], PDO::PARAM_STR, strlen($row['content']));
$sth->execute();
if ($sth->rowCount()) {
    // cập nhật thành công
}

// DELETE
$db->query('DELETE FROM ' . NV_PREFIXLANG . '_items WHERE id = ' . (int) $id);
```

### Dùng helper insert_id() và affected_rows_count()

```php
// INSERT với helper — gọn hơn prepare+bindParam+execute+lastInsertId
$sql = 'INSERT INTO ' . NV_PREFIXLANG . '_items (title, status, created_at)
        VALUES (:title, :status, ' . NV_CURRENTTIME . ')';
$new_id = $db->insert_id($sql, '', [
    'title'  => $row['title'],
    'status' => (string) $row['status']
]);

// UPDATE/DELETE với helper — trả về số dòng bị ảnh hưởng
$sql = 'UPDATE ' . NV_PREFIXLANG . '_items SET status = :status WHERE id = ' . (int) $id;
$affected = $db->affected_rows_count($sql, ['status' => '1']);
if ($affected) {
    // cập nhật thành công
}
```

> Lưu ý: `insert_id()` và `affected_rows_count()` nhận `$data` là array `['key' => 'value']` — tất cả value đều là string (PDO::PARAM_STR). Nếu cần PARAM_INT, dùng `prepare()` + `bindParam()` thủ công.

### LIKE query an toàn

```php
// Escape ký tự đặc biệt % và _ trong LIKE
$keyword = $db->dblikeescape($nv_Request->get_title('keyword', 'get', ''));
$sql = 'SELECT * FROM ' . NV_PREFIXLANG . '_items WHERE title LIKE :kw';
$sth = $db->prepare($sql);
$kw  = '%' . $keyword . '%';
$sth->bindParam(':kw', $kw, PDO::PARAM_STR);
$sth->execute();
$rows = $sth->fetchAll();
```

---

## Schema bảng chuẩn (dùng trong action_mysql.php)

```php
'CREATE TABLE ' . $db_config['prefix'] . '_' . $lang . '_' . $module_data . '_items ('
. ' `id`         MEDIUMINT(8) UNSIGNED NOT NULL AUTO_INCREMENT,'
. ' `title`      VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,'
. ' `alias`      VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT \'\','
. ' `content`    MEDIUMTEXT  CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,'
. ' `status`     TINYINT(1)  NOT NULL DEFAULT \'1\','
. ' `order`      SMALLINT(5) UNSIGNED NOT NULL DEFAULT \'0\','
. ' `created_at` INT(11)     UNSIGNED NOT NULL DEFAULT \'0\','
. ' `updated_at` INT(11)     UNSIGNED NOT NULL DEFAULT \'0\','
. ' `author_id`  INT(11)     UNSIGNED NOT NULL DEFAULT \'0\','
. ' PRIMARY KEY (`id`),'
. ' KEY `idx_status` (`status`, `created_at`),'
. ' UNIQUE KEY `uq_alias` (`alias`)'
. ') ENGINE=MyISAM DEFAULT CHARSET=utf8'
// Dùng utf8mb4 nếu cần lưu emoji — đồng bộ với $db_config['charset']
```

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

> Hướng dẫn đầy đủ về hệ thống cache: xem tại [nukeviet-cache SKILL.md](../nukeviet-cache/SKILL.md).

> Cache bị xóa tự động khi module admin thực hiện thao tác ghi nếu tuân thủ pattern chuẩn. Nếu tự viết logic ghi DB, hãy dùng `$nv_Cache->delMod($module_name)`.

---

## Tối ưu — dấu hiệu cần xử lý

| Vấn đề | Giải pháp |
|---|---|
| `LIKE '%keyword%'` trên cột lớn | Thêm FULLTEXT INDEX |
| Query nằm trong vòng lặp | Dùng `IN (id1, id2, ...)` một lần |
| `SELECT *` | Chỉ SELECT cột thực sự cần |
| Lọc/sort mà không có INDEX | Thêm composite index phù hợp |
