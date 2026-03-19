# Hướng Dẫn Nâng Cấp Module / Theme NukeViet

## Lộ trình nâng cấp — đọc file theo đúng thứ tự

### Nâng cấp Module → `docs/knowledge/upgrade/module/`

| Lộ trình | File cần đọc (theo thứ tự) |
|---|---|
| 4.5.06 → 5.0.00 | `NV-4.5.06-len-5.0.00.md` |

### Nâng cấp Theme → `docs/knowledge/upgrade/theme/`

Cùng bảng lộ trình, cùng tên file — chỉ khác thư mục.

---

## Quy trình nâng cấp module

### 1. Xác định phiên bản hiện tại
```bash
grep "version" modules/ten-module/version.php
```

### 2. Đọc tuần tự từng file tài liệu theo lộ trình
Không bỏ qua bước nào dù file có vẻ rỗng.

### 3. Quét code cần thay đổi
```bash
# Tìm hàm/hằng deprecated từ tài liệu vừa đọc
grep -rn "PATTERN_CAN_THAY" modules/ten-module/ --include="*.php"
```

### 4. Áp dụng từng breaking change
Sửa từng file → chạy `php -l [file]` ngay sau → không để lỗi tích lũy.

### 5. Cập nhật version.php
```php
'version' => 'X.Y.ZZ',   // phiên bản đích — dạng bắt buộc
'date'    => '...',
```

### 6. Kiểm tra cuối
```bash
find modules/ten-module/ -name "*.php" -exec php -l {} \;
phpcs --standard=PSR2 modules/ten-module/
```

---

## Quy trình nâng cấp theme

### 1. Đọc tuần tự từng file tài liệu theo lộ trình

### 2. So sánh file tpl hệ thống với default mới nhất
```bash
diff themes/default/layout/[file].tpl themes/[ten-theme]/layout/[file].tpl
```
- Theme đang **override** tpl đó → merge thủ công
- Theme **không override** → tự cập nhật khi sync từ default

### 3. Cập nhật config.ini nếu có thay đổi position/layout

### 4. Xóa cache
```
Admin → Công cụ web → Dọn dẹp hệ thống → Làm sạch cache
```

### 5. Kiểm tra
- [ ] Giao diện ngoài site — desktop + mobile
- [ ] Kéo thả block hoạt động bình thường
- [ ] `block.default.tpl` vẫn tồn tại

---

## Thêm tài liệu khi có phiên bản NukeViet mới

```
1. Tạo docs/knowledge/upgrade/module/NV-X.X.XX-len-X.X.XX.md
2. Tạo docs/knowledge/upgrade/theme/NV-X.X.XX-len-X.X.XX.md
3. Cập nhật bảng lộ trình trong docs/knowledge/upgrade.md
```
