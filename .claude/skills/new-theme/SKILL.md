---
name: new-theme
description: Tạo mới hoặc build CSS Theme NukeViet 5. Dùng khi cần tạo theme mới từ default hoặc compile lại SCSS.
argument-hint: <tên-theme>
disable-model-invocation: true
allowed-tools: Read, Write, Edit, Bash, Glob
---

Tạo mới hoặc build CSS Theme NukeViet 5.

**Tên theme:** $ARGUMENTS

## Các bước thực hiện

### 1. Xác nhận tên theme
Tên theme phải là kebab-case (VD: `my-theme`, `blue-sky`). Nếu chưa có, hỏi user.

### 2. Đọc skill reference
Đọc `docs/knowledge/theme.md` và các file trong `docs/knowledge/examples/theme/`.

### 3. Copy từ theme default
```bash
cp -r "src/themes/default" "src/themes/$ARGUMENTS"
```

### 4. Cập nhật cấu hình theme
Chỉnh sửa trong `src/themes/$ARGUMENTS/`:
- `config.ini`: Đổi tên hiển thị, xóa position không cần thiết
- `config_default.php`: Cập nhật các giá trị mặc định

### 5. Build assets (nếu user yêu cầu)
```bash
npm install
npm run core-css
```

### 6. Xóa cache
```bash
rm -rf src/data/cache/*/*.cache
rm -rf src/data/cache/smarty-compile/*.php
```

### 7. Báo cáo
Hướng dẫn kích hoạt tại **Admin > Giao diện > Quản lý giao diện**.
