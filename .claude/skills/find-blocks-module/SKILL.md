---
name: find-blocks-module
description: Phân tích toàn bộ blocks đã đăng ký cho một module NukeViet 5 qua tất cả ngôn ngữ đã cài đặt.
argument-hint: <module_file> [file_name]
disable-model-invocation: false
allowed-tools: Read
---

Phân tích và liệt kê blocks đã đăng ký cho module NukeViet 5.

**Arguments:** `$ARGUMENTS`

---

## Bước 1 — Parse arguments

Tách `$ARGUMENTS` theo khoảng trắng:
- Phần tử đầu tiên → `MODULE_FILE` (bắt buộc)
- Phần tử thứ hai (nếu có) → `FILE_NAME` (tùy chọn, VD: `global.voting_random.php`)

Nếu `MODULE_FILE` trống hoặc chứa ký tự không hợp lệ (chỉ cho phép `a-z`, `0-9`, `_`, `-`), dừng lại và hỏi:
> "Vui lòng cung cấp tên module_file cần phân tích (VD: `voting`, `news`). Tùy chọn: thêm file_name để lọc block cụ thể (VD: `voting global.voting_random.php`)."

---

## Bước 2 — Xác định DB prefix

Đọc file `src/config.php` và tìm dòng có dạng:
```php
$db_config['prefix'] = '...';
```

Lấy giá trị chuỗi trong dấu nháy đơn làm `DB_PREFIX`.

- Nếu file không tồn tại → báo lỗi: "Không tìm thấy `src/config.php`." và dừng.
- Nếu không tìm thấy `$db_config['prefix']` → báo lỗi: "Không xác định được DB prefix trong `src/config.php`." và dừng.

Từ đây, mọi tên bảng đều dùng `{DB_PREFIX}` thay vì hardcode `nv5`.

---

## Bước 3 — Lấy danh sách ngôn ngữ đã cài

```sql
SELECT lang FROM {DB_PREFIX}_setup_language WHERE setup = 1 ORDER BY weight ASC
```

Ghi nhớ danh sách `lang` trả về (VD: `['vi', 'en']`). Đây là tập ngôn ngữ sẽ tra cứu ở các bước tiếp theo.

---

## Bước 4 — Tìm tất cả module instances theo từng ngôn ngữ

Với **mỗi `lang`** trong danh sách trên, chạy:

```sql
SELECT title, custom_title, act
FROM {DB_PREFIX}_{lang}_modules
WHERE module_file = '{MODULE_FILE}'
```

Ghi nhận tất cả `title` (instance name — khóa nối sang blocks_groups) và `custom_title` (tên hiển thị).
Một `module_file` có thể có nhiều `title` (nhiều instance).

Nếu ngôn ngữ nào không tìm thấy dòng nào → ghi nhận "Không có instance nào ở ngôn ngữ `{lang}`".

---

## Bước 5 — Tìm blocks theo từng ngôn ngữ

Với **mỗi `lang`**, dùng danh sách `title` đã tìm được ở Bước 4:

**Nếu `FILE_NAME` được truyền vào:**
```sql
SELECT bid, module, file_name, title, theme, position, act, weight, template
FROM {DB_PREFIX}_{lang}_blocks_groups
WHERE module IN ('{title1}', '{title2}', ...)
  AND file_name = '{FILE_NAME}'
ORDER BY module, weight ASC
```

**Nếu không có `FILE_NAME`:**
```sql
SELECT bid, module, file_name, title, theme, position, act, weight, template
FROM {DB_PREFIX}_{lang}_blocks_groups
WHERE module IN ('{title1}', '{title2}', ...)
ORDER BY module, weight ASC
```

Nếu danh sách `title` ở ngôn ngữ đó rỗng → bỏ qua bước này cho ngôn ngữ đó.

---

## Bước 6 — Trình bày kết quả

### Tổng quan

Hiển thị DB prefix đang dùng và filter đang áp dụng:
- **DB Prefix:** `{DB_PREFIX}`
- **Module file:** `{MODULE_FILE}`
- **Filter file_name:** `{FILE_NAME}` (hoặc "tất cả" nếu không truyền)

| Ngôn ngữ | Số instances | Số blocks |
|----------|-------------|-----------|
| vi       | X           | Y         |
| en       | X           | Y         |

### Chi tiết theo ngôn ngữ

Với mỗi ngôn ngữ, nhóm theo module instance:

**`{lang}` — Instance: `{title}` (`{custom_title}`)**

| bid | file_name | title | theme | position | act | weight |
|-----|-----------|-------|-------|----------|-----|--------|
| ... | ...       | ...   | ...   | ...      | ... | ...    |

### Nhận xét

- Blocks nào đang active (`act = 1`) / inactive (`act = 0`)
- File PHP nào được dùng (liệt kê `file_name` duy nhất) — chỉ khi không có filter `FILE_NAME`
- Có instance nào tồn tại nhưng không có block nào không
- Các vị trí (`position`) đang được sử dụng
