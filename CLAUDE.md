# NukeViet 5.x - AI Code Guide

## Quy trình làm việc bắt buộc

Với mọi task không trivial: **Phân tích → Lập kế hoạch → Xác nhận → Thực thi**

1. Đọc các file liên quan (Read, Grep, Glob) trước khi đề xuất thay đổi
2. Trình bày kế hoạch: **Mục tiêu · Các file ảnh hưởng · Thay đổi dự kiến · Rủi ro**
3. Chờ Dev phản hồi **"OK"** trước khi bắt đầu viết code
4. Thực thi: tuân thủ PSR-12, conventions NukeViet 5

## Quy tắc An toàn thư mục làm việc (không được vi phạm)

Thư mục làm việc và CSDL local là tài sản của Dev, không phải môi trường nháp của AI.

### 1. Tuyệt đối không tự ý chạy lệnh làm thay đổi thư mục làm việc

Cấm chạy khi Dev không yêu cầu trực tiếp, kể cả để "kiểm tra" rồi hoàn nguyên ngay:

- `git stash` (đặc biệt `-u` / `-a`) · `git clean` · `git reset --hard` · `git checkout -- <path>` · `git restore` · `git rm`
- Xóa/di chuyển/ghi đè file, thư mục trong repo

**Lý do:** các lệnh này phá dữ liệu không nằm trong git nên **không thể hoàn nguyên**. Cụ thể: `git stash push -u` bên trong gọi `git clean -fd`, cờ `-d` xóa luôn thư mục rỗng chưa track, và `git stash pop` không phục hồi lại được (git không lưu thư mục rỗng).

**Cần so sánh với thư mục sạch thì:** dùng `git worktree add <dir> <ref>` ở thư mục riêng, hoặc `git show <ref>:<path>`, hoặc lập luận từ danh sách file đã sửa, không stash.

### 2. Chỉ ghi file tạm vào scratchpad

Script kiểm tra, dữ liệu trung gian, log → thư mục scratchpad của session. không tạo file trong repo trừ khi file đó là một phần kế hoạch đã được Dev xác nhận.

### 3. Xin phép trước khi chạy test làm thay đổi trạng thái

- `run Unit` - an toàn, ghi `tests/_output/`, chạy được tự do
- `run Acceptance` / `run Api` - **phải xin phép**: `InstallCest` cài lại NukeViet, ghi đè CSDL test
- Không tự ý chạy toàn bộ suite khi chỉ cần một file test

### 4. Báo cáo trung thực khi đã gây hại

Phát hiện mình làm hỏng gì thì nói ngay, nêu đúng cơ chế và phạm vi, không giảm nhẹ. Sau lệnh có rủi ro, kiểm tra lại cả **cây thư mục** chứ không chỉ `git status` - `git status` không thấy được thư mục chưa track đã mất.

## Stack & Lệnh thường dùng

- **Runtime:** PHP 8.2-8.5, Node.js v18.17+, NPM v10.5+, Composer v2.6+
- **Infrastructure:** MariaDB/MySQL, Linux (AlmaLinux/RockyLinux/Ubuntu), Nginx + PHP-FPM

```bash
npm run admin-css  # Build CSS Admin
npm run core-css   # Build CSS Core
```

## Kiến trúc NukeViet 5

### Biến & Constants cốt lõi

- DB tiền tố đa ngôn ngữ: `NV_PREFIXLANG . '_ten_bang'` (VD: `nv5_vi_news`)
- DB tiền tố dùng chung: `NV_TABLEPREFIX . '_ten_bang'` (VD: `nv5_users`)
- Config toàn cục: `NV_CONFIG_GLOBALTABLE`
- Thời gian: `NV_CURRENTTIME` | Root path: `NV_ROOTDIR`
- URL pattern: `?lang=vi&nv=ten-module&op=ten-func`
- Auth: `NV_IS_ADMIN` (mọi level) · `NV_IS_MODADMIN` (quyền module) · `NV_IS_SPADMIN` (super admin)

### Ngôn ngữ (Language)

- Sử dụng đối tượng `$nv_Lang` để truy xuất ngôn ngữ. **không** dùng mảng `$lang_module` hay `$lang_global` (cách cũ của NV 4.5).
- Truy xuất ngôn ngữ module: `$nv_Lang->getModule('key')`
- Truy xuất ngôn ngữ toàn cục: `$nv_Lang->getGlobal('key')`

### Kiến trúc & Conventions

- Code style: 4 spaces, `camelCase` (biến/hàm), `PascalCase` (Class/PSR-4), PHPDoc + comment tiếng Việt
- Văn phong mô tả, docs, comment, commit message:
  - Không viết in hoa cả cụm từ để nhấn mạnh (không "PHẢI", "KHÔNG ĐƯỢC", "LƯU Ý"), cứ viết bình thường. In hoa chỉ dùng cho tên hằng, tên viết tắt, tên riêng kỹ thuật (`NV_IS_ADMIN`, SQL, CSRF, PSR-12)
  - Chỉ dùng dấu gạch ngang ngắn `-`, không dùng gạch ngang dài `—` hay gạch nối trung `–`
- Core Namespace: Composer PSR-4 tại `src/includes/vendor/vinades/nukeviet/` (`NukeViet\Core\Request`, v.v.)
- Strict Types: dùng `declare(strict_types=1)` cho các class thư viện mới
- Frontend Assets: SCSS qua NPM - không chỉnh sửa file `.css` đã compile trực tiếp
- Testing: Codeception - `php vendor/bin/codecept run Unit`

### Database Pattern

- `$db` → READ + WRITE ($db_slave đã bỏ)
- `$nv_Cache->db()` → cached READ (ưu tiên dùng khi không cần real-time)

## Quy tắc Bảo mật (không được vi phạm)

1. **Input:** Phải qua `$nv_Request`. không dùng `$_GET`/`$_POST`/`$_REQUEST` trực tiếp
2. **SQL:** Chuỗi user → `prepare()` + `bindParam()`. Số nguyên → cast `(int)` nối thẳng
3. **Output HTML:** Raw DB/user data → `nv_htmlspecialchars()`. Data từ `get_title()` đã escape - không escape lại (tránh double-encode)
4. **CSRF:**
   ```php
   $csrf     = csrf_create($csrf_key);   // $csrf_key hệ thống định nghĩa sẵn
   csrf_check($csrf, $csrf_key);          // kiểm tra khi nhận POST
   ```
5. **File:** Dùng `nv_is_file()`, không `is_file()` với path từ user
6. **Admin:** Luôn kiểm tra `defined('NV_IS_ADMIN')` trước khi ghi dữ liệu

## Git Workflow

- Nhánh chính: `nukeviet4.5` (main/production) | Nhánh phát triển: `nukeviet5.0` (dev)
- không push thẳng vào `nukeviet4.5` hoặc `nukeviet5.0`
- PR target: feature branch → `nukeviet5.0`
- Commit format: `feat|fix|refactor|docs: mô tả [AI-assisted]`
- Mọi MR cần 1 Peer Review trước khi merge
