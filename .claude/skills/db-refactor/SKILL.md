---
name: db-refactor
description: Chuyển đổi các lệnh truy vấn SQL từ ghép chuỗi sang Prepared Statements (PDO) trong NukeViet 5
argument-hint: <path/to/file.php> hoặc <module_name>
disable-model-invocation: false
allowed-tools: Read, Grep, Glob, Edit, Write, Bash
---

# db-refactor — NukeViet 5

Chuẩn hóa SQL sang PDO Prepared Statements. Phản hồi bằng **Tiếng Việt**.

## Bước 1 — Xác định phạm vi & quét
- Tham số là file → xử lý file đó. Là module → toàn bộ `src/admin/{module}/` và `src/modules/{module}/` nếu có
- Nếu là toàn bộ thư mục (vd: src/admin): Liệt kê tất cả module con, quét và refactor từng module tuần tự để tránh xung đột.
- Quét dấu hiệu cần refactor:
```bash
grep -rn '\$db->query\|\$db->exec' <path> --include="*.php"
```
- Nếu là module: Phân tích toàn bộ file trong module để sửa đồng thời, tránh quét lặp lại.

## Bước 2 — Quy tắc chuyển đổi

- **Nguyên tắc chung:** Thay ghép chuỗi biến vào SQL bằng `:placeholder` + `bindValue()`.
- **Chọn biến Database:**
  - `$db`: Dùng cho các lệnh ghi (INSERT, UPDATE, DELETE) và SELECT cần dữ liệu chính xác tuyệt đối (thường ở Admin).
  - `$db_slave`: **Bắt buộc** dùng cho các lệnh `SELECT` ở Frontend/Block (truy cập công cộng) để tối ưu hiệu năng.
- **Không tham số hóa:** Hằng số bảng (`NV_PREFIXLANG`, `$db_config['prefix']`, `NV_USERS_GLOBALTABLE`, v.v.) — đây là tên bảng tĩnh.
- **Loại bỏ Query Builder:** Hủy bỏ hoàn toàn cấu trúc Query Builder (vd: `$db->sqlreset()->select(...)->from(...)`) và chuyển sang dùng chuỗi SQL chuẩn kết hợp PDO Prepared Statements (`$db->prepare('SELECT ...')`) để tối ưu hóa, giữ code đồng nhất và thân thiện với lập trình viên mới.
- **Định dạng chuỗi:** Sử dụng dấu ngoặc kép `""` bao ngoài câu lệnh SQL **chỉ khi** câu lệnh đó chứa các giá trị tĩnh được bao bởi dấu nháy đơn `'` (ví dụ: `$db->prepare("SELECT ... WHERE type = 'admin'")`). Việc này giúp tránh dùng ký tự thoát (escape) `\'`. Nếu SQL không chứa nháy đơn, hãy ưu tiên dùng dấu nháy đơn `'` bao ngoài để tối ưu hiệu năng PHP.

### Trường hợp SQL tĩnh, hoặc không có tham số
Giữ nguyên `$db->query()` / `$db->exec()` khi câu lệnh không chứa biến truyền vào (chỉ có hằng số bảng hệ thống):
- `$db->query()`: Lệnh có result set cần fetch, không có tham số — `SELECT`, `OPTIMIZE TABLE`, `SHOW ...`
- `$db->exec()`: Lệnh không có result set, không có tham số — `TRUNCATE`, `DROP`, `ALTER`, `CREATE`

### SELECT / fetchColumn / fetchAll / while-fetch
```php
// SELECT ở Frontend (ưu tiên $db_slave)
$stmt = $db_slave->prepare('SELECT * FROM ' . NV_PREFIXLANG . '_table WHERE id = :id AND lang = :lang');
$stmt->bindValue(':id', $id, PDO::PARAM_INT);
$stmt->bindValue(':lang', $lang, PDO::PARAM_STR);
$stmt->execute();

$row   = $stmt->fetch();           // Lấy một dòng
$rows  = $stmt->fetchAll();        // Lấy nhiều dòng
$count = $stmt->fetchColumn();     // Lấy một giá trị
```

Quản lý tài nguyên (Resource Management):
1. Nếu dùng fetchAll(), PDO tự động lấy hết dữ liệu và đóng cursor ngầm, nên closeCursor() không bắt buộc.
2. Với fetch(), hãy luôn tập thói quen đóng cursor bằng $stmt->closeCursor() nếu không định gọi fetch() thêm lần nào nữa cho cùng một $stmt (ví dụ: chỉ lấy 1 dòng duy nhất).

```php
// Vòng lặp: fetch trực tiếp từ $stmt, tuyệt đối KHÔNG gán $result = $stmt
while ($row = $stmt->fetch()) {
    // ...
}
$stmt->closeCursor(); // Đóng cursor sau khi kết thúc vòng lặp hoặc khi thoát vòng lặp sớm
```
Lưu ý định dạng:
  - Phân tách (cách 1 dòng trắng) giữa các cụm lệnh PDO độc lập liên tiếp nhau (từ bước `prepare` đến `execute`/`fetch`) để code thoáng và dễ bảo trì.
  - Chỉ cách 1 dòng nếu sau đó là code logic mới.
  - Tuyệt đối KHÔNG cách dòng nếu ngay sau đó là dấu đóng khối '}'.


### Destructuring array (`[...]` / `list()`)
Không dùng destructuring trực tiếp trong điều kiện `while` — PHP không đảm bảo giá trị trả về luôn là array, gây `Warning: Cannot destructure non-array value`.

**Sửa — đặt tên biến theo ngữ nghĩa, dùng trực tiếp thay vì chia nhiều biến độc lập:**
```php
// ❌ Sai
while ([$layout, $in_module, $func_name] = $result->fetch(PDO::FETCH_NUM)) {

// ✅ Đúng
while ($_row_file = $result->fetch()) {
    // dùng $_row_file['layout'], $_row_file['in_module'], $_row_file['func_name']
}
```

Lưu ý đặt tên biến fetch:
- `$row` là hợp lệ cho vòng lặp đơn, không lồng nhau.
- Khi có **vòng lặp lồng nhau**, bắt buộc đặt tên theo ngữ nghĩa (`$_row_cat`, `$_row_user`, `$_row_file`, `$_row_module`...) để tránh trùng biến.
- Luôn dùng `$result->fetch()` — NukeViet 5 đã cấu hình `PDO::FETCH_ASSOC` làm mặc định trong `Database.php`, không cần truyền tham số. Không dùng `PDO::FETCH_NUM` — tránh bug ngầm khi thứ tự cột trong `SELECT` thay đổi.

### INSERT — chuẩn PDO
```php
$stmt = $db->prepare('INSERT INTO ' . NV_PREFIXLANG . '_table (title, body) VALUES (:title, :body)');
$stmt->bindValue(':title', $title, PDO::PARAM_STR);
$stmt->bindValue(':body', $body, PDO::PARAM_STR);
$stmt->execute();
$new_id = $db->lastInsertId();
```

### UPDATE / DELETE
```php
// Chỉ cần thực thi → prepare/execute
$stmt = $db->prepare('UPDATE ' . NV_PREFIXLANG . '_table SET status = :status WHERE id = :id');
$stmt->bindValue(':status', $status, PDO::PARAM_INT);
$stmt->bindValue(':id', $id, PDO::PARAM_INT);
$stmt->execute();

// Cần số dòng bị ảnh hưởng → rowCount()
$stmt = $db->prepare('DELETE FROM ' . NV_PREFIXLANG . '_table WHERE userid = :userid');
$stmt->bindValue(':userid', $userid, PDO::PARAM_INT);
$stmt->execute();
$affected = $stmt->rowCount();
```

### LIKE — dấu `%` ghép vào giá trị, không vào placeholder
```php
$stmt->bindValue(':kw', '%' . $keyword . '%', PDO::PARAM_STR);
```

### IN — mảng số nguyên (không cần placeholder động)
```php
// intval() đảm bảo không có rủi ro injection → ghép trực tiếp vào SQL
$ids  = implode(', ', array_map('intval', $id_array));
$stmt = $db->prepare('SELECT * FROM ' . NV_PREFIXLANG . '_table WHERE id IN (' . $ids . ')');
$stmt->execute();
```

### IN — mảng chuỗi (bắt buộc dùng placeholder động)
```php
$values       = array_values($str_array); // đảm bảo key là số nguyên liên tục 0,1,2...
$placeholders = implode(', ', array_map(fn($k) => ':v' . $k, array_keys($values)));
$stmt         = $db->prepare('SELECT * FROM ' . NV_PREFIXLANG . '_table WHERE slug IN (' . $placeholders . ')');
foreach ($values as $k => $v) {
    $stmt->bindValue(':v' . $k, $v, PDO::PARAM_STR);
}
$stmt->execute();
```

## Bước 3 — Lưu ý
- **Tái sử dụng prepared statement trong vòng lặp:** Gọi `prepare()` BÊN NGOÀI vòng lặp, sau đó dùng `bindValue()` kết hợp `execute()` BÊN TRONG vòng lặp. Tuyệt đối KHÔNG dùng `bindParam` trong vòng lặp vì tham chiếu có thể bị thay đổi bởi logic vòng lặp bên trong gây ra bug khó phát hiện.
- `bindValue` ưu tiên cho mọi trường hợp.
- **Bảo tồn logic & comment:** Tuyệt đối KHÔNG xóa comment hoặc các dòng khởi tạo biến (ví dụ `$array = [];`) của code cũ khi refactor SQL. Chỉ thay thế phần thực thi truy vấn.
- Giữ nguyên `intval()`, `strip_tags()`, v.v. — chúng phục vụ validate nghiệp vụ, không liên quan SQL.
- Đảm bảo `global $db, $db_slave;` được khai báo trong hàm nếu cần.
- Để tiết kiệm token, AI chỉ báo cáo các điểm thực sự quan trọng, Bỏ qua các giải thích lý thuyết rườm rà.
