---
name: upgrade-theme
description: Nâng cấp Theme NukeViet lên chuẩn NukeViet 5 (NPM SCSS, config.json, Bootstrap 5). Dùng khi migrate theme cũ.
argument-hint: <themes/ten-theme>
disable-model-invocation: true
allowed-tools: Read, Write, Edit, Bash, Glob
---

Nâng cấp Theme NukeViet lên chuẩn NukeViet 5 (NPM SCSS, config.json, Bootstrap 5).

**Theme cần nâng cấp:** $ARGUMENTS

## Các bước thực hiện

### 1. Xác định target
Nếu không có `$ARGUMENTS`, hỏi user.

### 2. Đọc tài liệu nâng cấp
Đọc `docs/knowledge/upgrade.md`.

### 3. Quét cấu hình hiện tại
```bash
# Chuẩn hóa: bỏ prefix "themes/" hoặc "src/themes/" nếu user nhập thừa
# Đường dẫn chuẩn: src/themes/{tên-theme}/
THEME_PATH="src/themes/$ARGUMENTS"
cat "$THEME_PATH/config.ini"
```

### 4. So sánh template override
```bash
diff -r src/themes/default/layout "$THEME_PATH/layout" 2>/dev/null || true
```

### 5. Cập nhật cấu hình
- Tạo/cập nhật `config.json` theo chuẩn NV5
- Cập nhật `config.ini` nếu cần thêm/bỏ position
- Cập nhật `config_default.php`

### 6. Build lại CSS
```bash
npm install
npm run core-css
```

### 7. Xóa cache
```bash
rm -rf src/data/cache/*/*.cache
rm -rf src/data/cache/smarty-compile/*.php
```

### 8. Báo cáo
Tóm tắt thay đổi và template cần kiểm tra thủ công sau nâng cấp.
