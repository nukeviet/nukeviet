---
name: seed-sample-data
description: Tạo hàm test sinh dữ liệu mẫu (sample data) cho một file PHP của module NukeViet 5, rồi bổ sung vào SampleDataTest.php.
argument-hint: <module/admin|site/ten_file> [mô tả thêm]
allowed-tools: Read, Grep, Glob, Bash, Edit
---

Tạo hàm test sinh dữ liệu mẫu và bổ sung vào `tests/Unit/SampleDataTest.php`.

**Tham số:** `$ARGUMENTS`

## Bước 1 — Phân tích tham số

Từ `$ARGUMENTS`, tách theo khoảng trắng:
- **Phần đầu** (trước khoảng trắng đầu tiên): path dạng `{module}/{location}/{filename}`
  - `{module}` = tên module (VD: `users`, `news`, `banner`)
  - `{location}` = `admin` hoặc `site`
  - `{filename}` = tên file không có `.php`
- **Phần còn lại** (nếu có): mô tả thêm về yêu cầu dữ liệu mẫu cần sinh

Ví dụ: `users/admin/user_edit Sinh dữ liệu user với avatar và 2-step` → module=`users`, location=`admin`, file=`user_edit`, mô tả=`Sinh dữ liệu user với avatar và 2-step`

Nếu `$ARGUMENTS` trống hoặc thiếu phần path (không có ít nhất 2 dấu `/`), hỏi lại user theo format đúng rồi dừng.

## Bước 2 — Xác định đường dẫn file

Quy tắc ánh xạ `{location}` → thư mục:
- `admin` → `src/modules/{module}/admin/{filename}.php`
- `site`  → `src/modules/{module}/funcs/{filename}.php`

**Kiểm tra file tồn tại:**
1. Thử đọc file theo đường dẫn trên.
2. Nếu không tìm thấy, thử tìm rộng hơn bằng Glob trong `src/modules/{module}/`:
   ```
   src/modules/{module}/**/{filename}.php
   ```
3. Nếu Glob tìm được đúng 1 file → dùng file đó, thông báo đường dẫn thực tế cho user.
4. Nếu tìm được nhiều file → liệt kê cho user và hỏi chọn file nào, rồi dừng chờ phản hồi.
5. Nếu không tìm thấy file nào → thông báo rõ và hỏi user cung cấp đường dẫn chính xác, rồi dừng.

## Bước 3 — Đọc file nguồn & action_mysql.php

Đọc song song **hai file**:

1. **File mục tiêu** (xác định ở Bước 2)
2. **`src/modules/{module}/action_mysql.php` (nếu có)** — chứa toàn bộ SQL queries của module, giúp hiểu cấu trúc bảng và cách thức thao tác dữ liệu

Nếu `action_mysql.php` không tồn tại, thực hiện theo các bước sau để tìm hiểu cấu trúc bảng:

1. **Trích xuất tên bảng** được tham chiếu trong file mục tiêu (đã đọc ở trên) bằng cách tìm các pattern SQL như `FROM`, `JOIN`, `INTO`, `UPDATE` kèm tên bảng — thường có dạng `NV_TABLEPREFIX . '_ten_bang'` hoặc chuỗi literal chứa prefix.

2. **Tìm định nghĩa/thao tác các bảng đó** trong toàn bộ file PHP của module:
   ```
   grep -rl "{ten_bang}" src/modules/{module}/ --include="*.php"
   ```
   Chạy lệnh này cho từng tên bảng tìm được ở bước trên.

3. **Đọc các file tìm được** — ưu tiên file nào có nhiều câu SELECT/INSERT/UPDATE nhất liên quan đến bảng đó, để hiểu: các cột tồn tại, kiểu giá trị được dùng, và logic nghiệp vụ khi thao tác dữ liệu.

## Bước 4 — Phân tích cấu trúc dữ liệu

Từ hai file đã đọc, xác định:

1. **Các bảng liên quan** đến chức năng của file mục tiêu:
   - Tên bảng (dùng `NV_TABLEPREFIX` hay `NV_PREFIXLANG`?)
   - Các cột quan trọng: kiểu dữ liệu, ràng buộc, giá trị mặc định
   - Quan hệ giữa các bảng (FK logic, không nhất thiết là FK DB)

2. **Logic nghiệp vụ** cần tôn trọng khi sinh dữ liệu mẫu:
   - Giá trị enum/set hợp lệ
   - Trường bắt buộc
   - Phụ thuộc dữ liệu (VD: phải có user trước khi insert openid)

3. **Mô tả thêm từ user** (nếu có ở Bước 1): tích hợp vào logic sinh dữ liệu

## Bước 5 — Đề xuất kế hoạch (bắt buộc xác nhận)

Trước khi viết code, trình bày ngắn gọn:

- **Tên hàm test** sẽ tạo (VD: `testInsertSampleDataForUsersEdit`)
- **Các bảng sẽ insert** + số lượng bản ghi dự kiến
- **Chiến lược sinh dữ liệu**: random, fixed set, phụ thuộc dữ liệu hiện có, v.v.
- **Rủi ro**: có xóa/ghi đè dữ liệu hiện có không?

Sau đó hỏi: **"Kế hoạch trên OK chưa? Xác nhận để tôi viết code."**

Chỉ tiếp tục Bước 6 khi user xác nhận **"OK"** (hoặc tương đương: "ok", "yes", "có", "y").

## Bước 6 — Viết hàm test

Viết hàm test PHP tuân thủ các quy tắc sau:

### Quy tắc bắt buộc

- **Khai báo:** `public function testInsertSampleDataFor{PascalCase}()`
- **Group tag:** `@group sample-data` trong PHPDoc
- **DB ops:** luôn sử dụng biến `$db` cho mọi thao tác (NukeViet 5 không còn dùng `$db_slave`)
- **Tên bảng:**
  - Bảng dùng chung: `$db_config['prefix'] . '_ten_bang'`
  - Bảng đa ngôn ngữ: `$db_config['prefix'] . '_' . $db_config['lang'] . '_ten_bang'` (nếu module có multilang)
- **Không dùng** `prepare()`/`bindParam()` trong test seed — dùng `sprintf()` + `$db->exec()` với bulk insert để nhanh
- **Escape string thủ công** khi cần: `str_replace(["\\", "'"], ["\\\\", "\\'"], $s)`
- **Dữ liệu phụ thuộc:** nếu cần dữ liệu từ bảng khác (VD: userid), SELECT trước rồi dùng `markTestSkipped()` nếu không có
- **Bulk insert:** gom tất cả values vào 1 câu INSERT duy nhất (như pattern trong `SampleDataTest.php` hiện có)
- **Kết thúc:** `$this->assertTrue(true)` nếu không có assertion thực, hoặc `$this->assertGreaterThan(0, $inserted, '...')` nếu kiểm tra số dòng insert

### Cấu trúc hàm mẫu

```php
/**
 * [Mô tả ngắn về dữ liệu mẫu sẽ được sinh]
 *
 * @group sample-data
 */
public function testInsertSampleDataFor{PascalCase}()
{
    global $db, $db_config;

    // [Comment giải thích từng khối dữ liệu]

    $values = [];
    // ... sinh dữ liệu ...

    $db->exec(
        "INSERT [IGNORE] INTO " . $db_config['prefix'] . "_ten_bang"
        . " (col1, col2, ...) VALUES "
        . implode(',', $values)
    );

    $this->assertTrue(true);
}
```

## Bước 7 — Bổ sung vào SampleDataTest.php

Đọc file `tests/Unit/SampleDataTest.php` để xác định vị trí chèn, sau đó dùng Edit để **chèn hàm mới vào trước dấu `}` cuối cùng** của class.

Không xóa, sửa, hay format lại các hàm test đã có.

## Bước 8 — Thông báo kết quả

Sau khi chèn xong, hiển thị:

- Tên hàm vừa tạo
- Số dòng chèn vào trong file
- Lệnh chạy test để kiểm chứng:
  ```bash
  php vendor/bin/codecept run Unit tests/Unit/SampleDataTest.php -g sample-data --steps
  ```
