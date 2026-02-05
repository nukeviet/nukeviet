# PHP 8.5 Compatibility Tools

## Tổng quan

Bộ công cụ này giúp đảm bảo tương thích với PHP 8.5 bằng cách phát hiện và sửa các vấn đề liên quan đến array destructuring với PDO fetch.

## Vấn đề

Trong PHP 8.5+, việc sử dụng array destructuring với giá trị không phải array sẽ gây ra `TypeError`. Ví dụ:

```php
// PDO fetch() trả về false khi không có kết quả
[$var1, $var2] = $db->query("SELECT...")->fetch(3); // TypeError trong PHP 8.5+
```

Lỗi này xảy ra vì:
- `PDO::fetch()` trả về `false` khi không còn dữ liệu
- PHP 8.5+ không cho phép destructuring với giá trị `false` (hoặc bất kỳ giá trị non-array nào)
- Lỗi: `TypeError: Cannot use bool as array`

## Các công cụ

### 1. check_php85_compatibility.php

**Chức năng:** Quét toàn bộ mã nguồn để tìm các pattern có thể gây lỗi trong PHP 8.5.

**Cách sử dụng:**
```bash
php tools/check_php85_compatibility.php
```

**Kết quả:**
- Liệt kê tất cả các trường hợp cần sửa
- Phân biệt giữa safe patterns (while loops) và unsafe patterns (direct assignments)
- Tạo file log chi tiết tại `tests/_output/php85_compatibility_issues.log`

**Ví dụ output:**
```
✓ Tìm thấy 163 trường hợp an toàn (while loops)
✗ Tìm thấy 0 trường hợp CẦN SỬA (direct assignments)
✓ Tất cả các trường hợp không an toàn đã được sửa!
```

### 2. fix_php85_compatibility.php

**Chức năng:** Tự động sửa các vấn đề bằng cách thêm null coalescing operator.

**Cách sử dụng:**
```bash
# Chạy ở chế độ dry-run để xem trước các thay đổi
php tools/fix_php85_compatibility.php --dry-run

# Thực sự sửa các file
php tools/fix_php85_compatibility.php
```

**Cách sửa:**

Pattern cũ (unsafe):
```php
[$var1, $var2] = $db->query(...)->fetch(3);
```

Pattern mới (safe):
```php
[$var1, $var2] = $db->query(...)->fetch(3) ?: [null, null];
```

Đối với if statements:
```php
// Cũ
if ([$title] = $re->fetch(3)) {
    return $title;
}

// Mới
$row = $re->fetch(3);
if ($row !== false) {
    [$title] = $row;
    return $title;
}
```

### 3. test_php85_fixes.php

**Chức năng:** Kiểm tra tính đúng đắn của các sửa đổi.

**Cách sử dụng:**
```bash
php tools/test_php85_fixes.php
```

**Tests:**
- Test với fetch trả về false
- Test với fetch trả về array
- Test với while loops
- Test với if statements

### 4. tests/Unit/Php85CompatibilityTest.php

**Chức năng:** Unit test tích hợp vào Codeception.

**Cách sử dụng:**
```bash
php vendor/bin/codecept run unit Php85CompatibilityTest
```

## Safe vs Unsafe Patterns

### Safe Patterns (Không cần sửa)

**While loops:**
```php
while ([$var1, $var2] = $result->fetch(3)) {
    // An toàn vì vòng lặp tự động dừng khi fetch() trả về false
}
```

### Unsafe Patterns (Cần sửa)

**Direct assignments:**
```php
[$var1, $var2] = $db->query(...)->fetch(3);
// Cần sửa thành:
[$var1, $var2] = $db->query(...)->fetch(3) ?: [null, null];
```

**If statements:**
```php
if ([$var] = $result->fetch(3)) {
    // Cần refactor
}
```

## Workflow Đề xuất

1. **Kiểm tra hiện trạng:**
   ```bash
   php tools/check_php85_compatibility.php
   ```

2. **Xem trước các sửa đổi:**
   ```bash
   php tools/fix_php85_compatibility.php --dry-run
   ```

3. **Áp dụng sửa đổi:**
   ```bash
   php tools/fix_php85_compatibility.php
   ```

4. **Xác minh kết quả:**
   ```bash
   php tools/check_php85_compatibility.php
   php tools/test_php85_fixes.php
   ```

5. **Chạy tests:**
   ```bash
   php vendor/bin/codecept run unit Php85CompatibilityTest
   ```

## Lưu ý

- Các công cụ tự động skip template fetch (không phải PDO fetch)
- While loops không được sửa vì chúng đã an toàn
- Các giá trị mặc định được set là `null` - có thể cần điều chỉnh tùy logic
- Luôn kiểm tra kỹ các thay đổi trước khi commit

## Thống kê

Trong dự án NukeViet:
- **163** trường hợp sử dụng trong while loops (an toàn)
- **57** trường hợp cần sửa (đã sửa xong)
- **0** trường hợp còn lại cần xử lý

## Tham khảo

- [PHP 8.5 Release Notes](https://www.php.net/releases/8.5/en.php)
- [PHP RFC: Deprecate array_destructuring with non-arrays](https://wiki.php.net/rfc/deprecate_array_destructuring_non_arrays)
