# Nâng cấp Module: NukeViet 4.5.00 → 5.0.00

> Áp dụng cho tất cả module custom phát triển trên NukeViet 4.5.00.  
> Đọc file này **sau** file lộ trình trước đó (nếu có).

---

## Breaking Changes

| Thành phần | Cũ (4.5.00) | Mới (5.0.00) | Bắt buộc? |
|---|---|---|---|
| _(chưa có dữ liệu — điền vào đây)_ | | | |

---

## 1. Hàm / API thay đổi

```php
// ❌ Cũ (4.5.00)
// ten_ham_cu($param);

// ✅ Mới (5.0.00)
// ten_ham_moi($param);
```

## 2. Hằng số thay đổi

| Cũ | Mới | Ghi chú |
|---|---|---|
| _(điền vào đây)_ | | |

## 3. Cấu trúc bảng DB thay đổi

```sql
-- Migration SQL nếu có
-- ALTER TABLE ...
```

## 4. Thay đổi khác

_(điền vào đây)_

---

## Checklist cho lộ trình này

- [ ] Thay thế hàm/hằng deprecated trong bảng trên
- [ ] Chạy `php -l` sau mỗi file sửa
- [ ] Cập nhật `version.php` khi đây là bước cuối lộ trình
