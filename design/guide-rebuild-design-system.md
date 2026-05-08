# Hướng dẫn rebuild Design System khi phát hành phiên bản NukeViet mới

> Tài liệu này mô tả quy trình **tái tạo file `design-system.html` + bộ mockup** khi phát hành 1 phiên bản giao diện (theme) mới của NukeViet. Mục tiêu: một nhân viên thiết kế / dev mới nhận việc cũng làm được, dựa vào AI (Claude) để tự động hóa phần lớn công đoạn.

---

## 0. Khi nào cần rebuild?

Rebuild design system khi xảy ra **1 trong các điều kiện** sau:

- Phát hành version lớn của theme (vd. Future 2.0, Neo, Horizon...)
- Đổi màu chủ đạo, typography, spacing scale
- Đổi layout khung (từ 3 cột sang 2 cột, đổi breakpoint...)
- Thêm/bỏ component quan trọng (breadcrumb, pagination, block style...)
- Module tin tức / liên hệ / users có file `.tpl` mới

Nếu chỉ **sửa nhỏ** (đổi 1 màu, 1 block), không cần rebuild — chỉ cần sửa trực tiếp file `design-system.html` rồi commit.

---

## 1. Chuẩn bị input

### 1.1. Thu thập source code của theme mới

> **Mẹo nhanh**: chạy `bash design/design-system.sh` để **tự động** thu thập + đóng gói toàn bộ file dưới đây vào `design/design-system/` + `design/design-system.zip` (~85 KB). Bỏ qua mục 1.1 → 1.3 thủ công, nhảy thẳng tới mục 2.

Nếu làm thủ công, từ repo NukeViet (branch phát hành version mới), cần lấy:

| Nhóm | File | Mục đích |
|---|---|---|
| **Tokens** | `scss/future/_variables.scss` | Màu, spacing, font, shadow (Bootstrap overrides + NV maps) |
| | `scss/future/_variables-dark.scss` | Token dark mode |
| | `scss/_functions.scss` | `tint()` cho gray scale |
| **Layout SCSS** | `scss/future/_root.scss` | CSS custom properties (`--nv-*`, `--cr-*`) |
| | `scss/future/_common.scss` | Utility class (`.text-truncate-N`, `.fw-*`, cookie-notice…) |
| | `scss/future/_header.scss` | `.site-header`, `.topbar`, `.site-nav` |
| | `scss/future/_main.scss` | Layout 3 cột (`.main-{start,content,end}`) |
| | `scss/future/_main-nav.scss` | Main nav + submenu |
| | `scss/future/_footer.scss` | Footer 3 cột |
| | `scss/future/_blocks.scss` | `.nv-block`, `.nv-block-default`, tooltip |
| | `scss/future/_forms.scss` | Form override |
| | `scss/future/_news.scss` | Module news |
| | `scss/future/_contact.scss` | Module contact |
| | `scss/future/_inform.scss` | Alert / notification |
| | `scss/future/_override.scss` | LUÔN import cuối — ưu tiên override |
| | `scss/future/_style_header.scss` + `_style_footer.scss` | Scaffold + import order |
| | `scss/future/style.{d,r}.scss` | Entry points (Desktop / Responsive) |
| | `scss/future/mixins/_responsive.scss` | Mixin `responsive()` / `non-responsive()` |
| **Dependency util** | `scss/_show_pass.scss` | Button show/hide password |
| | `scss/_ckeditor5.scss` | Style cho rich editor (tùy chọn) |
| | `scss/standalone/_alert.scss` + `_polyfill.scss` | Alert popup core + polyfill |
| **Layout templates** | `src/themes/<tên>/layout/layout.*.tpl` | 6 layout cột (content / ssbar-content / …) |
| | `src/themes/<tên>/layout/simple.tpl` | Layout đơn |
| | `src/themes/<tên>/layout/header_{extended,only}.tpl` | Header extended / only |
| | `src/themes/<tên>/layout/footer_{extended,only}.tpl` | Footer extended / only |
| | `src/themes/<tên>/layout/block.{default,no_title,simple}.tpl` | 3 biến thể block wrapper |
| **Module templates** | `src/themes/<tên>/modules/news/block_*.tpl` + `viewcat_*.tpl` | Block news + 4 layout xem chuyên mục |
| | `src/themes/<tên>/modules/contact/{form,main,block.contact_*}.tpl` | Form + main + block contact |
| | `src/themes/<tên>/modules/users/{login,login_ajax,login_form,block.user_button}.tpl` | Đăng nhập + block user |
| | `src/themes/<tên>/modules/page/{main,detail,block.about,global.page_list}.tpl` | Page tĩnh |

> ⚠️ **Cảnh báo về module news**: theme `future` **KHÔNG override** `news/main.tpl`, `news/detail.tpl`, `news/topic.tpl` — NV5 fallback `themes/default/modules/news/` (XTemplate cũ, **KHÔNG phải Smarty**). Khi rebuild design system cho theme MỚI, **phải VIẾT Smarty version** cho main/detail/topic — không để fallback.

Tải tất cả vào **1 thư mục**, ví dụ `~/Desktop/nv-theme-v2-source/` (hoặc dùng `design/design-system/` từ script tự động).

### 1.2. Thu thập thông tin metadata

Ghi ra một file `_release-notes.md`:

```markdown
# Thông tin phát hành
- Tên theme: Future v2.0 (hoặc Neo, Horizon...)
- NukeViet core version: 5.1
- Bootstrap version: 5.3.3
- Font Awesome version: 6.7.2
- Ngày phát hành: YYYY-MM-DD
- Tác giả: ...
- Link repo: github.com/nukeviet/nukeviet/tree/<branch>

# Các thay đổi lớn so với version trước
- Đổi primary color từ #1477cc → #...
- Đổi font body sang ...
- Layout mới: ...
- Component mới: ...
- Component bỏ: ...
```

### 1.3. Screenshot tham chiếu (tùy chọn)

Chụp 5–7 ảnh các trang thật trên staging / demo site để AI có ground truth visual:
- Trang chủ
- Trang chuyên mục
- Bài chi tiết
- Liên hệ
- Đăng nhập
- Dark mode (nếu có)

Đặt chung trong thư mục `~/Desktop/nv-theme-v2-source/screenshots/`.

---

## 2. Tạo project mới trên Claude

1. Mở Claude (web hoặc app) → **New Project**
2. Đặt tên: `NukeViet <version>` (ví dụ `NukeViet 5.1`)
3. **Đính kèm làm context** toàn bộ file đã chuẩn bị ở mục 1:
   - Toàn bộ `.scss` trong mục 1.1
   - Toàn bộ `.tpl` trong mục 1.1
   - File `_release-notes.md`
   - Thư mục `screenshots/` (nếu có)

Đính kèm càng đầy đủ, AI sinh càng chính xác. **Không cần upload ảnh binary nặng** — chỉ cần file text `.scss` + `.tpl`.

---

## 3. Prompt khởi tạo cho claude.ai/design

Prompt đầy đủ đã tách thành file riêng để dễ maintain — paste vào claude.ai/design 1 lần:

→ **[prompt-claude-design.md](prompt-claude-design.md)**

Prompt này yêu cầu claude.ai/design sinh:
- `design-system.html` (single-page docs + tokens + components + nav)
- `partials/{site-header,site-footer,site-nav}.html` + `nv-theme.css` + `include.js`
- `partials/blocks/<name>.html` (mỗi NV block 1 file)
- `mockups/{home,category,article,contact,login}.html` (dùng `data-include` để share header/footer/nav — KHÔNG inline copy)
- **`nv-routes.md`** — bridge file mapping mockup → NV5 route + TPL target
- `<script id="nv-tokens">` JSON ở cuối design-system.html (Claude Code parse tự động)

Convention bắt buộc trong output:
- Mỗi `<section class="nv-block">` có `data-nv-{block,position,funcs,source}` (xem chi tiết trong file prompt)
- Mỗi `<body>` mockup có `data-nv-{route,layout}` + `data-nav` cho active nav item
- Sass-compatible (KHÔNG `lighten()/darken()`), Bootstrap 5.3 native, dark mode `[data-bs-theme="dark"]`

**Tổng thời gian**: 15-30 phút claude.ai/design sinh xong.

---

## 4. Review & chỉnh sửa

Sau khi AI sinh xong, làm các bước kiểm tra:

### 4.1. Kiểm tra tokens

Mở `design-system.html`, section "Colors" → so với `_variables.scss`:
- Primary, secondary, success, danger... khớp không?
- Gray scale 100–900 khớp không?
- Semantic color (body-bg, body-color, border-color) khớp không?

### 4.2. Kiểm tra mockups

Mở lần lượt 5 file trong `mockups/`:
- Layout có đúng 3 cột 22/56/22 không?
- Class name có đúng `.nv-block`, `.main-start` không?
- Breadcrumb + pagination có đầy đủ không?
- Responsive — resize xuống 600px, layout dồn thành 1 cột không?

### 4.3. Kiểm tra dark mode

Thêm `data-bs-theme="dark"` vào `<html>` của `design-system.html` → tokens dark có swap không?

### 4.4. Checklist verify đầy đủ

```
[ ] Mở mockups/home.html qua HTTP local (data-include cần HTTP, không chạy file://):
      cd <folder output> && php -S localhost:8000   # → http://localhost:8000/mockups/home.html
      # hoặc nếu thích Node:  npx serve <folder output>
    — header/footer load được, nav active đúng
[ ] DevTools Console KHÔNG có lỗi 404/CORS
[ ] Sửa partials/site-header.html 1 dòng → reload mọi mockup → đều thay đổi
[ ] Mỗi <section class="nv-block"> có đủ 4 data-attribute
    (data-nv-block, data-nv-position, data-nv-funcs, data-nv-source)
[ ] nv-routes.md có đủ 3 bảng: Mockup, Block markup, Components mới
[ ] design-system.html có <script id="nv-tokens"> với JSON valid
    (test: JSON.parse(document.getElementById('nv-tokens').textContent))
[ ] Toggle data-bs-theme="dark" trên <html> → tokens swap
[ ] Resize ≤ 600px → mockup dồn 1 cột
```

### 4.5. Sửa trực tiếp bằng AI

Nếu thấy sai, nói với Claude:
```
Section "Colors" trong design-system.html đang sai màu secondary.
Từ _variables.scss thật là #ABCDEF, bạn đang viết #123456. Sửa giúp.
```

Hoặc:
```
mockups/home.html đang dùng layout 2 cột. Theo theme mới phải là 3 cột 22/56/22 giống trước. Rewrite lại.
```

---

## 4.6. Hand-off cho Claude Code (build theme NV5 thực tế)

Sau khi review bundle xong, dùng Claude Code để **convert HTML mockup → theme NukeViet 5 hoàn chỉnh** (SCSS source + Smarty TPL + block PHP + config.ini với setblocks).

→ **[prompt-claude-code-handoff.md](prompt-claude-code-handoff.md)**

Workflow:

1. Đặt bundle output từ claude.ai/design vào `design/<theme-name>-design/`
2. Chạy Claude Code tại project root
3. Paste prompt trong [prompt-claude-code-handoff.md](prompt-claude-code-handoff.md)
4. Claude Code đọc `nv-routes.md` (mapping bridge) + `<script id="nv-tokens">` (token JSON) + tự lập kế hoạch
5. **Phase 0 dừng chờ bạn "OK"** trước khi phase 1-7 chạy
6. 7 phase: SCSS → Skeleton TPL → Block PHP+TPL+JSON+config → Module TPL → **Seed data** → config.ini setblocks → Verify

> ⚠️ **Phase Seed (5) BẮT BUỘC trước Phase setblocks (6)** — vì `<setblocks>` cho block module gốc cần ID dynamic (catid, topicid, menuid, pid). NV5 KHÔNG tự lookup → phải hardcode ID. Chỉ khi seed chạy trên DB sạch (auto-increment từ 1) → ID mới deterministic = thứ tự manifest. Chi tiết: [theme-patterns.md §1.2](theme-patterns.md).

Bridge file `nv-routes.md` (do claude.ai/design sinh) là **contract** — Claude Code dựa vào đó biết mockup nào → TPL nào, block nào reuse module gốc, block nào tự viết. Không dùng bridge file → Claude Code phải đoán → sai bóc.

---

## 5. Phát hành

Sau khi review xong:

### 5.1. Cập nhật changelog

Trong `design-system.html`, section "Phiên bản & Ghi chú" — cập nhật:
- Version NukeViet
- Version Bootstrap / Font Awesome
- Ngày rebuild
- Các thay đổi chính so với version trước

### 5.2. Commit vào repo design

```bash
cd /path/to/design-repo
git checkout -b design-system-v<version>
git add design-system.html partials/ mockups/ guide-*.md
git commit -m "Rebuild design system for NukeViet <version>"
git push origin design-system-v<version>
```

### 5.3. Bàn giao

Gửi cho team frontend / agency:
- File `design-system.html` (đính kèm mỗi session với Claude Code)
- Link repo design
- File `guide-claude-code-handoff.md` — quy trình áp design vào codebase thực

---

## 6. Checklist rebuild nhanh

```
INPUT (collect):
[ ] bash design/design-system.sh → tự động thu thập SCSS + TPL future
[ ] Điền design/design-system/_release-notes.md (version + changelog)
[ ] Chụp screenshot 5-7 trang thật → design/design-system/screenshots/
[ ] Nén design/design-system/ → design/design-system.zip

DESIGN (claude.ai/design):
[ ] Tạo Claude project mới, upload design-system.zip
[ ] Paste prompt từ design/prompt-claude-design.md
[ ] Review output: mở mockups qua HTTP local (cd <output> && php -S localhost:8000)
[ ] Verify shared partials: sửa site-header.html → reload mọi mockup → đều thay đổi
[ ] Verify nv-routes.md có đủ 3 bảng (Mockup, Block, Components mới)
[ ] Verify <script id="nv-tokens"> có JSON valid
[ ] Test dark mode (data-bs-theme="dark"), responsive (≤600px)

BUILD (Claude Code):
[ ] Đặt bundle output vào design/<theme-name>-design/
[ ] Paste prompt từ design/prompt-claude-code-handoff.md vào Claude Code
[ ] Phase 0 — review plan, gõ "OK"
[ ] Phase 1-7 chạy → verify trên browser → fine-tune (Phase 5 = Seed data, Phase 6 = setblocks hardcode ID, Phase 7 = active theme)

SHIP:
[ ] Update changelog trong design-system.html
[ ] Commit repo design + commit theme NV5 (separate branch)
[ ] Bàn giao team frontend (link repo + bundle zip)
```

---

## 7. Mẹo tối ưu

### 🎯 Không ngại attach nhiều file
Claude đọc `.scss` / `.tpl` / `.md` rất tốt. Càng đầy đủ input, càng ít phải sửa tay.

### 🎯 Giữ project cũ làm reference
Khi rebuild, bạn có thể cho Claude xem cấu trúc project cũ:
```
Đây là project design system cho NukeViet 5.0 cũ: [paste link hoặc attach design-system.html cũ]
Giữ NGUYÊN cấu trúc section, chỉ update tokens + mockup theo theme mới.
```
Cách này đảm bảo consistency giữa các version.

### 🎯 Rebuild theo nhóm nhỏ
Nếu file quá nhiều, chia làm nhiều phase:
- Phase 1: tokens (colors, typography, spacing)
- Phase 2: components (buttons, forms, cards, blocks)
- Phase 3: layouts + mockups
- Phase 4: documentation sections + sidebar nav

Mỗi phase 1 prompt riêng, AI dễ tập trung và ít sai.

### 🎯 Version hóa design system
Đặt tên file rõ ràng khi archive:
```
archive/
├── design-system-v5.0.html
├── design-system-v5.1.html
└── design-system-v6.0.html
```
Để khi cần so sánh, dễ tra cứu lại.

---

## 8. File liên quan

| File | Vai trò |
|---|---|
| [design-system.sh](design-system.sh) | Tự động hóa Mục 1 — thu thập SCSS + TPL của future |
| [prompt-claude-design.md](prompt-claude-design.md) | Mục 3 — prompt cho claude.ai/design (sinh design-system.html + mockups) |
| [prompt-claude-code-handoff.md](prompt-claude-code-handoff.md) | Mục 4.5 — prompt cho Claude Code (build theme NV5 từ bundle design) |

---

*File này là quy trình nội bộ — cập nhật khi quy trình thay đổi hoặc khi AI có capability mới.*
