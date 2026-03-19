---
name: new-block
description: Scaffold một NukeViet Block mới (Module Block hoặc Theme Block). Dùng khi tạo block cho module hoặc theme.
argument-hint: <tên-block>
disable-model-invocation: true
allowed-tools: Read, Write, Bash, Glob, Grep
---

Scaffold một NukeViet Block mới (Module Block hoặc Theme Block).

**Tham số:** $ARGUMENTS

## Các bước thực hiện

### 1. Thu thập thông tin
Hỏi user (gộp 1 lần) nếu chưa có đủ:
1. **Loại Block**: `1` = Module Block hoặc `2` = Theme Block
2. **Tên Module / Theme đích** (VD: `news`, `default`)
3. **Tên Block** — snake_case, chỉ phần đuôi (VD: `about`, `qr_code`)

### 2. Đọc skill reference
Đọc `docs/knowledge/block.md` và toàn bộ file trong `docs/knowledge/examples/block/`.

### 3. Tạo cấu trúc file

**Module Block:**
```bash
mkdir -p "src/modules/DESTINATION/blocks"
mkdir -p "src/themes/default/modules/DESTINATION"
touch "src/modules/DESTINATION/blocks/global.BLOCK_NAME.php"
touch "src/themes/default/modules/DESTINATION/block.BLOCK_NAME.tpl"
```

**Theme Block:**
```bash
mkdir -p "src/themes/DESTINATION/blocks/smarty"
touch "src/themes/DESTINATION/blocks/global.BLOCK_NAME.php"
touch "src/themes/DESTINATION/blocks/smarty/global.BLOCK_NAME.tpl"
```

### 4. Điền nội dung file
Dựa trên examples đã đọc:
- PHP: logic xử lý, security guard `defined('NV_MAINFILE')`
- JSON: khai báo tham số block — **bắt buộc lấy schema từ examples**, không dùng `{}`
- TPL: XTemplate (Module Block) hoặc Smarty (Theme Block)

### 5. Xóa cache
```bash
rm -rf src/data/cache/*/*.cache
rm -rf src/data/cache/smarty-compile/*.php
```

### 6. Báo cáo
Liệt kê file đã tạo và nhắc kích hoạt tại **Admin > Giao diện > Quản lý Block**.
