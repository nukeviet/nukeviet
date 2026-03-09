# AI Agent Guide — NukeViet 5.x

Đọc `.agent/ai-context.md` trước. Chi tiết kỹ thuật ở từng file trong `.agent/`.
Biểu mẫu này dùng chung cho các AI Assistant (AntiGravity, Claude, GitHub Copilot, Cursor, v.v.).

## Quy trình làm việc

Dev mô tả task → AI (bạn) vào Plan Mode phân tích codebase + lên kế hoạch → dev duyệt plan → AI tự code → dev review kết quả.

**Nguyên tắc: dev mô tả — AI thực thi. Dev chỉ can thiệp code trực tiếp khi thực sự cần thiết.**

Khi nhận task:
1. Luôn vào Plan Mode trước — không code ngay.
2. Đọc code hiện tại liên quan hoặc dùng công cụ tìm kiếm trong file trước khi đề xuất plan.
3. Plan phải rõ: sửa file/folder nào, thay đổi gì, lý do tại sao.
4. Chờ dev duyệt plan trước khi bắt tay code.

## Lệnh thường dùng (NukeViet 5)
```bash
# Cài đặt thư viện lúc khởi tạo:
composer install
npm install

# Build CSS liên tục trong quá trình dev:
npm run watch-admin
npm run watch-core

# Kiểm thử với Codeception:
php vendor/bin/codecept run Unit    # Chạy unit tests
php vendor/bin/codecept run         # Chạy toàn bộ test
php vendor/bin/codecept run -g install # Test nhóm chức năng cài đặt
```

## Tài liệu kỹ thuật chuyên sâu (`.agent/skills/`)
Mọi hướng dẫn chi tiết theo tính năng NukeViet 5 đã được đóng gói dưới dạng "kỹ năng" cho AI.
- `nukeviet-module`  — cấu trúc file bắt buộc, PSR-4 namespaces, Code mẫu controller/funcs.
- `nukeviet-api`     — Kiến trúc Remote API (Api & Uapi), Hướng dẫn tạo Class kế thừa interface.
- `nukeviet-block`   — Cấu trúc Block NukeViet 5 (XTemplate cho Module Block, Smarty cho Theme Block).
- `nukeviet-theme`   — Cấu trúc layout grid 24 cột, compile assets qua `npm run`, định nghĩa Block vị trí.
- `nukeviet-hook`    — Hệ thống Hook (Plugin): nv_apply_hook(), nv_add_hook(), template file hook trong modules/*/hooks/.
- `nukeviet-language`— Hệ thống đa ngôn ngữ: NV_LANG_DATA/INTERFACE, $lang_module/$lang_global, nạp thủ công $nv_Lang->loadModule(), email template, i18n JSON.
- `nukeviet-cache`   — Hệ thống Cache: $nv_Cache->db(), setItem/getItem, delMod, hỗ trợ Files/Memcached/Redis.
- `nukeviet-testing` — Hệ thống Testing: Unit Test, Acceptance Test với Codeception, Selenium, chạy theo Group.
- `nukeviet-security`— Quét lỗ hổng nguy hiểm (SQLi, CSRF, XSS), sử dụng chuẩn filter `$nv_Request` và `nv_htmlspecialchars()`.
- `nukeviet-mysql`   — Query Builder thông qua `$db_slave` / `$db`, Prefix đa ngôn ngữ chuẩn NukeViet 5.
- `nukeviet-review`  — Quy trình review code, tiêu chuẩn bảo mật, convention và cách báo cáo lỗi.
- `nukeviet-upgrade` — Lộ trình nâng cấp từ phiên bản NukeViet 4.x.
- `upgrade-guide/`   — Kho chứa logs chi tiết các breaking config cho Core.

## Workflows Tự Động (Automation)
Dự án được tích hợp các kịch bản chạy tự động trong `.agent/workflows/`. Hãy chạy chúng như một Slash Command:
`/new-api` · `/new-block` · `/new-hook` · `/new-module` · `/new-theme` · `/add-func` · `/upgrade-module` · `/upgrade-theme` · `/review-mr` · `/security-audit`
