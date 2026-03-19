---
name: new-hook
description: Scaffold một NukeViet Hook (Plugin) mới. Dùng khi cần module lắng nghe sự kiện từ module khác hoặc hệ thống.
argument-hint: <tên-hook>
disable-model-invocation: true
allowed-tools: Read, Write, Bash, Glob, Grep
---

Scaffold một NukeViet Hook (Plugin) mới trong module.

**Tham số:** $ARGUMENTS

## Các bước thực hiện

### 1. Thu thập thông tin
Hỏi user (gộp 1 lần) nếu chưa có đủ:
1. **Module nhận sự kiện** (module tạo hook): VD `news`, `ten_module`
2. **Module phát sự kiện** (module gọi `nv_apply_hook`): VD `users`, `page`, hoặc để trống (hệ thống)
3. **Tên Tag (event)**: VD `user_delete`, `before_detail_theme`
4. **Tên file hook** (không đuôi `.php`): VD `handle_user_delete`

### 2. Đọc skill reference
Đọc `docs/knowledge/hook.md` và file `docs/knowledge/examples/hook/TemplateHook.php`.

### 3. Tạo file hook
```bash
mkdir -p "src/modules/RECEIVE_MODULE/hooks"
touch "src/modules/RECEIVE_MODULE/hooks/HOOK_FILE.php"
```

### 4. Điền nội dung file
Dựa trên `TemplateHook.php`:
- Security guard `defined('NV_MAINFILE')`
- Logic xử lý với `$tag` tương ứng
- Gọi `nv_add_hook()` ở cuối file

### 5. Xóa cache
```bash
rm -rf src/data/cache/*/*.cache
rm -rf src/data/cache/smarty-compile/*.php
```

### 6. Báo cáo
- Đường dẫn file: `modules/{RECEIVE_MODULE}/hooks/{HOOK_FILE}.php`
- Nhắc đăng ký tại: **Admin > Công cụ web > Plugin > Thêm plugin mới**
- Cảnh báo xóa cache sau khi kích hoạt
