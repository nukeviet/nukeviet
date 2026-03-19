---
name: new-api
description: Scaffold một NukeViet API mới (Admin hoặc User/Public). Dùng khi tạo endpoint API cho module.
argument-hint: <tên-module>
disable-model-invocation: true
allowed-tools: Read, Write, Bash, Glob, Grep
---

Scaffold một NukeViet API mới (Admin API hoặc User/Public API).

**Tham số:** $ARGUMENTS

## Các bước thực hiện

### 1. Thu thập thông tin
Hỏi user (gộp 1 lần) nếu chưa có đủ từ `$ARGUMENTS`:
1. **Tên Module đích** (VD: `news`, `shops`)
2. **Loại API**: `1` = Admin API (`Api/`) hoặc `2` = User/Public API (`Uapi/`)
3. **Tên Action**: PascalCase (VD: `GetList`, `UpdateItem`)
4. **Cách gọi**: `Remote`, `Local`, hoặc `Cả hai`

### 2. Đọc skill reference
Đọc `docs/knowledge/api.md` và toàn bộ file trong `docs/knowledge/examples/api/`.

### 3. Tạo thư mục
```bash
mkdir -p "src/modules/MODULE_LOWER/API_FOLDER"
```

### 4. Tạo file PHP API
Dựa trên examples đã đọc, tạo `modules/{module}/{Api|Uapi}/{ActionName}.php` với:
- Namespace chuẩn: `NukeViet\Module\TênModule\{Api|Uapi}`
- Class kế thừa đúng interface
- Phân quyền phù hợp

### 5. Tạo mẫu gọi Local API (nếu cần)
Nếu user chọn **Local** hoặc **Cả hai**, hiển thị đoạn code mẫu `nv_local_api()`.

### 6. Xóa cache
```bash
rm -rf src/data/cache/*/*.cache
rm -rf src/data/cache/smarty-compile/*.php
```

### 7. Báo cáo
Thông báo đường dẫn file và URL test nếu là Remote API.
