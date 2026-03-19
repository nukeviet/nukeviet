# NukeViet 5.x — Claude Code Guide

## Quy trình làm việc bắt buộc

Với mọi task không trivial: **Phân tích → Lập kế hoạch → Xác nhận → Thực thi**

1. Đọc các file liên quan (Read, Grep, Glob) trước khi đề xuất thay đổi
2. Trình bày kế hoạch: **Mục tiêu · Các file ảnh hưởng · Thay đổi dự kiến · Rủi ro**
3. Chờ Dev phản hồi **"OK"** trước khi bắt đầu viết code
4. Thực thi: tuân thủ PSR-12, conventions NukeViet 5

## Stack & Lệnh thường dùng

- **Runtime:** PHP 8.2–8.5, Node.js v18.17+, NPM v10.5+, Composer v2.6+
- **Infrastructure:** MariaDB/MySQL, Linux (AlmaLinux/RockyLinux/Ubuntu), Nginx + PHP-FPM

```bash
npm run admin-css  # Build CSS Admin
npm run core-css   # Build CSS Core

# Xóa cache (chạy sau mọi thay đổi PHP/template):
rm -rf src/data/cache/*/*.cache && rm -rf src/data/cache/smarty-compile/*.php
```

## Kiến trúc NukeViet 5

### Biến & Constants cốt lõi

- DB tiền tố đa ngôn ngữ: `NV_PREFIXLANG . '_ten_bang'` (VD: `nv5_vi_news`)
- DB tiền tố dùng chung: `NV_TABLEPREFIX . '_ten_bang'` (VD: `nv5_users`)
- Config toàn cục: `NV_CONFIG_GLOBALTABLE`
- Thời gian: `NV_CURRENTTIME` | Root path: `NV_ROOTDIR`
- URL pattern: `?lang=vi&nv=ten-module&op=ten-func`
- Auth: `NV_IS_ADMIN` (mọi level) · `NV_IS_MODADMIN` (quyền module) · `NV_IS_SPADMIN` (super admin)

### Kiến trúc & Conventions

- Code style: 4 spaces, `camelCase` (biến/hàm), `PascalCase` (Class/PSR-4), PHPDoc + comment tiếng Việt
- Core Namespace: Composer PSR-4 tại `src/includes/vendor/vinades/nukeviet/` (`NukeViet\Core\Request`, v.v.)
- Strict Types: dùng `declare(strict_types=1)` cho các class thư viện mới
- Frontend Assets: SCSS qua NPM — KHÔNG chỉnh sửa file `.css` đã compile trực tiếp
- Testing: Codeception — `php vendor/bin/codecept run Unit`

### Database Pattern

- `$db` → WRITE (master)
- `$db_slave` → READ (frontend)
- `$nv_Cache->db()` → cached READ (ưu tiên dùng khi không cần real-time)

## Quy tắc Bảo mật (KHÔNG được vi phạm)

1. **Input:** PHẢI qua `$nv_Request`. KHÔNG dùng `$_GET`/`$_POST`/`$_REQUEST` trực tiếp
2. **SQL:** Chuỗi user → `prepare()` + `bindParam()`. Số nguyên → cast `(int)` nối thẳng
3. **Output HTML:** Raw DB/user data → `nv_htmlspecialchars()`. Data từ `get_title()` đã escape — KHÔNG escape lại (tránh double-encode)
4. **CSRF:**
   ```php
   $csrf_key = $module_name . '_' . $op . '_' . $admin_info['admin_id'];
   $csrf     = csrf_create($csrf_key);   // sinh token (1 lần / page)
   csrf_check($csrf, $csrf_key);          // kiểm tra khi nhận POST
   ```
5. **File:** Dùng `nv_is_file()`, KHÔNG `is_file()` với path từ user
6. **Admin:** Luôn kiểm tra `defined('NV_IS_ADMIN')` trước khi ghi dữ liệu

## Git Workflow

- Nhánh chính: `nukeviet4.5` (main/production) | Nhánh phát triển: `nukeviet5.0` (dev)
- KHÔNG push thẳng vào `nukeviet4.5` hoặc `nukeviet5.0`
- PR target: feature branch → `nukeviet5.0`
- Commit format: `feat|fix|refactor|docs: mô tả [AI-assisted]`
- Mọi MR cần 1 Peer Review trước khi merge
