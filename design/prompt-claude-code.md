# Prompt cho Claude Code — Build Theme NV5 từ Design System

> **Cách Dùng (Không cần copy paste dài dòng):**
> Trong Claude Code (tại project root), bạn chỉ cần gõ 1 câu duy nhất:
>
> `Hãy đọc hướng dẫn trong file design/prompt-claude-code.md và thực hiện build theme <theme-name>. Bundle đã có ở design/<theme-name>/output/.`
>
> *(Nhớ thay `<theme-name>` bằng tên theme thật, ví dụ `news2026`)*
>
> Claude Code sẽ tự động mở file này ra đọc toàn bộ quy trình và thực hiện.

---

## Triết lý: Skeleton-first, từng Phase một

Theme NV5 nhiều biến số (DB ID, position, setblocks, module TPL, Smarty fallback…). Nếu code thẳng "block thật + data thật" ngay từ đầu thì khi lỗi Dev khó khoanh vùng. Quy trình mới:

1. **Phase A** — dựng skeleton + **HARDCODE HTML** trong block TPL + setblocks đầy đủ. Mục tiêu: kích hoạt theme là **trang chủ render giống mockup ≥ 95%**, KHÔNG cần DB data.
2. **Phase B–E** — đổi từng phần sang TPL/data thật, mỗi phase 1 lớp.
3. **Mỗi phase có Develop xác nhận**: Claude Code làm xong → báo cáo → Dev kiểm tra → gõ `OK chuyển <phase kế>` mới chạy phase tiếp.

Lý do hardcode HTML ở Phase A: Dev nhìn thấy ngay UI giống mockup mà KHÔNG bị nhiễu bởi data sai/seed thiếu. Phase E mới "rút" hardcode thành data thật.

---

## Plan.md — Schema canonical (mọi theme tuân thủ)

Mọi theme NV5 PHẢI có file `design/<theme>/Plan.md` chứa decision cụ thể. Đây là contract giữa Dev và Claude Code: sau khi có Plan, Code chạy A→F không cần hỏi lại.

Schema bắt buộc (11 section, theo thứ tự):

| # | Section | Nội dung tối thiểu |
|---|---------|--------------------|
| 1 | **Phase tổng quan** | Bảng phase A→F + gate sau mỗi phase. Nếu dùng Stage 1C → thêm Phase 0' đầu bảng |
| 2 | **Phase 0' chi tiết** (chỉ Stage 1C) | 9 sub-step sinh bundle từ screenshot — đọc input → Design Brief → foundation → partials → blocks HTML → mockups → design-system.html + nv-tokens → nv-routes.md → verify |
| 3 | **Token override** | Block SCSS `_variables.scss` cho `scss/<theme>/`: 10-15 biến Bootstrap-overrides + 10-20 biến theme-overrides. Color HEX cụ thể, KHÔNG để placeholder |
| 4 | **Block list** | Bảng N block: file prefix, HTML source, position, funcs, **Phase E strategy** (1/2/3/4 — đọc theme-patterns.md §9). Strategy phải chốt sớm để Phase E không phải quyết định lại |
| 5 | **Layout list** | Bảng file `layout/*.tpl`: Copy future / Chỉnh / Viết mới. Kèm khung `layout.content-esbar.tpl` (hoặc layout default) dạng Smarty |
| 6 | **Positions `config.ini`** | XML đầy đủ `<positions>` — mọi `data-nv-position` từ partials + blocks |
| 7 | **Module TPL viết mới** (Phase B) | Bảng file: engine (**Smarty / XTemplate** — BẮT BUỘC verify từ source, KHÔNG đoán), mockup nguồn, action (viết mới / copy future) |
| 8 | **Seed manifest** (Phase C) | Bảng file JSON: số lượng (≥10 bài/cat cho mọi cat hiện ở home), mô tả. Pattern sinh articles song song với sub-agent Haiku |
| 9 | **Setblocks per-route** (Phase D) | Bảng route → layout → block hiện. Note type trap (vd `catid` là array, không string) |
| 10 | **ID Map deterministic** | Bảng category/topic/menu/banner-plan với ID expected (auto_increment từ 1 sau reset DB). Phase C fill, Phase D dùng |
| 11 | **Risk + Tham chiếu** | Trap đặc thù theme + link file canonical (theme-patterns, Block.md, prompt-claude-code…) |

> **Nguyên tắc**: schema GIỮ NGUYÊN cho mọi theme. Chỉ NỘI DUNG mỗi section khác (token màu, block list, cat list…). Code sinh Plan PHẢI tuân thủ schema này — sai schema = sai contract.

---

## Stage 1C — Code tự sinh bundle từ screenshot (KHÔNG dùng claude.ai/design)

Stage 1 hiện có 2 nhánh (xem README): A = qua claude.ai/design, B = designer đã có mockup HTML/SCSS. Nhánh C là phương án thứ 3: **Code tự sinh bundle từ screenshot + theme cha**.

### Khi nào chọn 1C

- Dev có sẵn screenshot UI (chụp / vẽ Figma export PNG / tham chiếu site khác)
- Không muốn upload zip sang claude.ai/design (privacy, tốc độ, hoặc không có account)
- Đã có theme cha `future` làm baseline → Code đọc trực tiếp scss/future/_variables.scss + design/future/output/ thay vì đoán

### Khi nào KHÔNG nên chọn 1C

- Theme phong cách KHÁC HẲN future (vd theme dark cyberpunk, theme print/magazine, theme RTL Ả Rập) — Code thiếu reference, dễ sai
- Theme cần iterate UI nhiều lần với Dev → claude.ai/design UI sandbox tốt hơn
- Dev chưa quen workflow NV5 → nên dùng 1A để có bundle chuẩn rồi học

### Phase 0' — 9 sub-step (Code chạy sau khi có Plan với section §2)

| Step | Action | Gate |
|------|--------|------|
| 0'.1 | Đọc input: screenshot, design/future/output/, scss/future/_variables.scss, prompt-claude-design.md (convention), theme-patterns.md §3.1 + §10 | (không) |
| 0'.2 | Sinh Design Brief 5-7 dòng (phong cách, color, typography, component mới so future) | **DỪNG chờ Dev `OK chuyển 0'.3`** |
| 0'.3 | Sinh `partials/include.js` (copy future) + `partials/nv-theme.css` (viết mới component .nv-*) | (không) |
| 0'.4 | Sinh `partials/site-{header,footer,nav}.html` | (không) |
| 0'.5 | Sinh `partials/blocks/<name>.html` (mỗi block 1 file, data-nv-* đầy đủ) | (không) |
| 0'.6 | Sinh `mockups/{home,category,article,contact,login}.html` (data-include header/footer/nav, KHÔNG inline) | (không) |
| 0'.7 | Sinh `design-system.html` + `<script id="nv-tokens">` JSON (parse từ scss/future, KHÔNG bịa) | (không) |
| 0'.8 | Sinh `nv-routes.md` mapping mockup → route + TPL target + engine TPL | (không) |
| 0'.9 | Verify: count file ~25-30, mở mockup local trong browser | **DỪNG chờ Dev verify ≥ 90% khớp screenshot → `OK chuyển A`** |

### Convention output GIỮ NGUYÊN

Bundle sinh ra ở Stage 1C phải khớp convention trong `design/prompt-claude-design.md` (data-nv-* attribute, nv-tokens JSON schema, shared components via `data-include`, prefix `.nv-*`, Sass 1.71+ compat). Stage 1C chỉ thay **người tạo bundle** (Code thay vì claude.ai/design), KHÔNG thay convention.

### Câu lệnh chuẩn Stage 1C (xem README.md "Câu lệnh chuẩn" cho 4 mẫu đầy đủ)

```text
Build theme <theme> NV5 từ screenshot tại design/<theme>/screenshot/.
Tự sinh design/<theme>/Plan.md theo schema canonical (11 section trong prompt-claude-code.md),
DỪNG chờ Dev OK, rồi chạy Phase 0' → F theo workflow.
```

---

## Chi tiết Workflow (Claude Code sẽ tự đọc phần này)

```text
Tôi đã nhận bundle Design System từ claude.ai/design, đặt tại design/<theme-name>/output/.

Bundle có:
- design-system.html         (chứa <script id="nv-tokens"> JSON tokens)
- nv-routes.md               (mapping mockup → NV5 route + TPL target)
- partials/site-{header,footer,nav}.html + nv-theme.css + include.js
- partials/blocks/<name>.html (mỗi NV block 1 file, có data-nv-* attribute)
- mockups/{home,category,article,contact,login}.html

Build theme `<theme-name>` cho NukeViet 5 theo workflow dưới. Tuân thủ:
- [CLAUDE.md](../../CLAUDE.md) — quy tắc chung NV5
- [docs/knowledge/theme.md](../../docs/knowledge/theme.md) — convention theme NV5
- [design/theme-patterns.md](../theme-patterns.md) — knowledge base patterns/traps generic (10 sections)
- [design/Block.md](../Block.md) — inventory 30 block sẵn có ở 5 thư mục + mapping + phương án (BẮT BUỘC đọc trước Phase E để chọn module reuse phù hợp)
- [design/<theme-name>/Plan.md](<theme-name>/Plan.md) — plan cụ thể decision (token, block, seed) — ví dụ `design/news2026/Plan.md`

### PHASE 0 — ĐỌC + LẬP KẾ HOẠCH (BẮT BUỘC, chờ Dev OK trước khi sang Phase A)

1. Đọc:
   - design/<theme-name>/output/nv-routes.md (toàn bộ — đây là contract)
   - design/<theme-name>/output/design-system.html (chỉ phần <script id="nv-tokens">)
   - design/<theme-name>/output/partials/nv-theme.css (đếm component, ước CSS size)
   - design/<theme-name>/output/release-notes.md (version, theme name)
   - 1-2 file blocks bất kỳ trong partials/blocks/ để hiểu data-nv-* convention
   - design/theme-patterns.md (knowledge base — đọc 1 lần, đặc biệt §3.1 engine TPL module + §10 Smarty PHP 8+ traps)
   - design/Block.md — biết file tồn tại (inventory 30 block module sẵn có); CHƯA cần đọc kỹ ở Phase 0 — Phase E sẽ tham chiếu

2. NẾU file `design/<theme-name>/Plan.md` chưa tồn tại, **TỰ ĐỘNG TẠO Plan.md**
   với các section bắt buộc:
   - **Mục tiêu** — theme name, version, mockup nguồn
   - **Tokens override** — top 10 biến SCSS quan trọng (parse từ <script id="nv-tokens">)
   - **Block list** — đầy đủ tên block (data-nv-block) + position (data-nv-position)
     + module nguồn (data-nv-source) — phân loại "theme" (Phase A hardcode) vs
     "reuse module" (Phase E sẽ xử lý)
   - **Layout list** — các layout TPL cần tạo (theo cột "Layout" trong nv-routes.md)
   - **Module TPL viết mới** — danh sách TPL phải viết mới (cột "VIẾT MỚI Smarty")
   - **Seed manifest list** — categories/topics/menus/banners/articles cần seed
   - **Setblocks per-route** — tinh chỉnh block nào hiện ở route nào (Phase D)
   - **ID Map** — bảng ID deterministic (điền ở Phase C, dùng ở Phase D)
   - **Risk** — trap chrome.js, Smarty/XTemplate fallback, type field config

3. Trình bày plan rút gọn 5 dòng cho Dev xem nhanh:
   - Theme target name + version
   - Số block (theme/reuse) + số position + số layout
   - Số TPL module phải viết mới
   - Số seed manifest
   - Risk chính

4. **CHỜ Dev gõ "OK"** trước khi sang Phase A.

### TỔNG QUAN 6 PHASE (gate sau mỗi phase)

> **Stage 1C** (Code tự sinh bundle từ screenshot) thêm **Phase 0'** trước Phase A — xem section "## Stage 1C" phía trên. Stage 1A/1B bỏ qua Phase 0' vì bundle đã có sẵn.

| Phase | Tên | Output verify |
|-------|-----|---------------|
| **0'** (Stage 1C only) | Code sinh bundle từ screenshot → design/<theme>/output/ | Dev mở mockup local → khớp screenshot ≥ 90% |
| **A** | Skeleton + tất cả block hardcode HTML + setblocks + Build CSS từ scss/<theme-name> | Active theme → trang chủ render giống mockup ≥ 95%, KHÔNG cần DB |
| **B** | Module TPL viết mới (`viewcat_main_left.tpl` Smarty, `detail.tpl` **XTemplate**) cho category/article page | Click vào chuyên mục/bài viết → render khớp mockup |
| **C** | Seed manifest (`src/data/seeder/<theme-name>/`: categories, articles ≥10/cat, banners, menus, users, theme-config) | Admin → Seeder chạy hết step, DB có data demo |
| **D** | Setblocks tinh chỉnh per-route (theo data đã seed) — `funcs` per route | Mỗi route hiện đúng block đặc thù (vd home khác category) |
| **E** | Convert block hardcode → reuse module / theme block với data thật (4 strategy — xem E.1) | Block load data thật, hardcode còn lại chỉ ở UI tĩnh |
| **F** | Polish — override TPL module (Smarty/XTemplate) cho khớp mockup CSS class · fix Smarty PHP 8+ traps · audit/fix lỗi runtime | Trang render khớp mockup với data thật, log notice rỗng |

⚠️ **GATE BẮT BUỘC**: Sau khi Claude Code làm xong 1 phase, **BÁO CÁO + DỪNG**.
Dev kiểm tra trên trình duyệt + gõ `OK chuyển B/C/D/E/F` mới chạy phase kế.
KHÔNG chạy liền 2 phase mà không có confirm.

⚠️ Exception: nếu **Plan.md đã có sẵn** (Dev đã làm planning trước), main agent
có thể chạy A→F liền mạch mà không chờ confirm — Dev review cuối tại Phase F.
Đây là cách build news2026 (verify 2026-05-08).

### PHASE A — Skeleton + Block hardcode + setblocks + Build CSS

Mục tiêu Phase A: kích hoạt theme là **trang chủ giống mockup ≥ 95%** mà KHÔNG
cần seed DB. Tất cả block render HTML cứng (link `#`, ảnh demo, text demo).
Phase E sẽ thay bằng data thật.

#### A.0. Theme skeleton

- Copy NGUYÊN `src/themes/future/` → `src/themes/<theme-name>/` làm baseline
- ⚠️ BẮT BUỘC copy cả thư mục `modules/` (11 module). Nếu thiếu, NV5 fallback
  default XTemplate → Smarty fail (xem theme-patterns.md §3)
- Cấu trúc đầy đủ phải có:
  - `theme.php`, `theme_*.php` (theme_site, theme_module…), `config.php`,
    `config_default.php`
  - `language/{vi,en}.php`
  - `layout/` (các file .tpl)
  - `modules/` (11 module — copy nguyên từ future)
  - `system/` (config.tpl, mail.tpl, admin_toolbar.tpl, alert.tpl,
    error_info.tpl, info_die.tpl)
  - `webfonts/`, `js/` (nv.main.js + nv.custom.js + bootstrap.bundle.min.js)
  - `default.jpg` (preview), `index.html` (chống listing)
- Sửa metadata `theme.php`: name, author, version (lấy từ release-notes.md)

#### A.1. Build CSS

- `rm -rf scss/<theme-name>/` rồi `cp -r scss/future/. scss/<theme-name>/`
  → kế thừa **đầy đủ** SCSS từ future (giữ partial pattern d/r mode)
- **Override `_variables.scss`** theo tokens design mới:
  - Parse JSON từ `<script id="nv-tokens">` trong design-system.html
  - Map mỗi key JSON → biến SCSS (ĐÈ giá trị, KHÔNG xóa biến cũ của future
    như `$contact-icons-light`, `$core-btn-colors`, `$main-columns`)
  - Append `<theme-name>-tokens` vào CUỐI file
- **Replace `_override.scss`** bằng content của `partials/nv-theme.css`
  (chỉ phần component .nv-* mới — bỏ Bootstrap reset)
  - Sửa `lighten()/darken()/mix()` → `color.adjust()` (Sass 1.71+)
- Thêm npm scripts `<theme-name>-css/compile/prefix/rtl` vào `package.json`
- **Build**: `npm run <theme-name>-css` → verify exit 0, không warning Sass
  - 16 file CSS output trong `src/themes/<theme-name>/css/`

#### A.2. Block PHP — pattern minimal (BẮT BUỘC)

- **XÓA HẾT** `src/themes/<theme-name>/blocks/` (do copy từ future để lại) để
  build lại từ đầu
- **Block đặt tại `themes/<theme-name>/blocks/`** (theme block) thay vì reuse
  module block — vì Phase A cần hardcode HTML giống thiết kế. Phase E sẽ
  chuyển sang reuse module nếu phù hợp

Pattern minimal cho mọi block ở Phase A:

```php
<?php
if (!defined('NV_MAINFILE')) {
    exit('Stop!!!');
}

function nv5_block_<name>($block_config) {
    global $nv_Lang;

    $tpl = new \NukeViet\Template\NVSmarty();
    $tpl->setTemplateDir($block_config['real_path']);
    $tpl->assign('LANG', $nv_Lang);
    $tpl->assign('DATA', $block_config);

    return $tpl->fetch('global.<name>.tpl');
}

if (defined('NV_SYSTEM')) {
    $content = nv5_block_<name>($block_config);
}
```

⚠️ **KHÔNG** truy vấn DB, **KHÔNG** xử lý input, **CHỈ** load TPL.
Phase E sẽ thay logic data thật.

#### A.3. Block TPL — hardcode HTML

- Copy **NGUYÊN VĂN** từ `design/<theme-name>/output/partials/blocks/*.html`
  vào `src/themes/<theme-name>/blocks/global.<name>.tpl`
- **Giữ nguyên**: link `#`, ảnh Unsplash/picsum, text demo
- **KHÔNG** nhúng `{$VAR}` Smarty (trừ link Smarty const cho contact_short
  như `{$smarty.const.NV_BASE_SITEURL}`)
- Mỗi block 3 file: `global.<name>.php` + `global.<name>.tpl` + `global.<name>.json`
  - `.json` chứa default config schema (kể cả Phase A chưa dùng — Phase E sẽ dùng)

#### A.4. Layout TPL

⚠️ Khuyến nghị (verified news2026): tách `header_extended` + `footer_extended`
thành **wrapper mỏng chứa position**, mỗi mảng UI lớn (topbar / logo / nav /
footer-info / footer-bar) là **1 block riêng** ở Phase A. Cách này:
- Phase E dễ thay từng phần (vd nav → reuse `menu/global.bootstrap`, footer_info
  → reuse `themes/future/global.company_info`)
- Admin có thể tắt từng phần qua giao diện không cần sửa code
- Dev dễ quản lý từng đoạn HTML độc lập

| File | Pattern khuyến nghị |
|------|---------------------|
| `header_extended.tpl` | Wrapper chứa **3 position riêng**: `[HEADER_TOPBAR]` (topbar mỏng) + `[HEADER_LOGO]` (logo + search + leaderboard) + `[HEADER_NAV]` (main navigation). Bootstrap Modal search + offcanvas menu mobile đặt cuối |
| `footer_extended.tpl` | Wrapper `<footer>` chứa **2 position**: `[FOOTER_INFO]` (cho 1-3 block info+categories+contact) + `[FOOTER_BAR]` (copyright bottom bar). Container Bootstrap đã wrap sẵn |
| `layout.content-esbar.tpl` | Khung: `[BANNER_TOP]` → `[TICKER]` → `[HERO]` → row 8/4 cột — col-8: `[CONTENT_TOP]` `{$MODULE_CONTENT}` `[CONTENT_MID]` `[CONTENT_BOTTOM]` + col-4: `[SIDEBAR_RIGHT]` |
| `layout.content.tpl` | Layout đơn cho contact: `[BANNER_TOP]` + `{$MODULE_CONTENT}` (không sidebar) |

⚠️ ANTI-PATTERN bắt buộc tránh (theme-patterns.md §4.2):
- KHÔNG copy/link `chrome.js` (hoặc bất kỳ JS render header/footer của mockup) vào theme
- Search overlay → Bootstrap Modal (`data-bs-toggle="modal"`), KHÔNG viết JS riêng
- Mega menu → Bootstrap Collapse
- Dropdown user/lang → Bootstrap Dropdown
- Sidebar mobile → Bootstrap Offcanvas
→ Sau Phase A, JS theme CHỈ load: `nv.main.js` + `nv.custom.js` + `bootstrap.bundle.min.js`

#### A.5. config.ini — positions + setblocks ĐẦY ĐỦ

Pattern theo `themes/default/config.ini`. Cấu hình:

1. **`<positions>`**: liệt kê TẤT CẢ `data-nv-position` duy nhất từ:
   - `partials/site-header.html` (vd `[TOPBAR]`, `[MAIN_NAV]`, `[USER_BUTTON]`)
   - `partials/site-footer.html` (vd `[FOOTER_INFO]`, `[FOOTER_MENU]`)
   - `partials/blocks/*.html` (mỗi block 1 position)
   - `layout.*.tpl` (vd `[HERO]`, `[HERO_MAIN]`, `[HERO_RAIL]`, `[CONTENT_TOP]`…)

2. **`<layoutdefault>`**: layout mặc định (thường `content-esbar`)

3. **`<setlayout>`**: route nào dùng layout khác default
   (vd contact dùng `content`, news/main dùng `content-esbar`)

4. **`<setblocks>`**: tạo ĐẦY ĐỦ 100% danh sách block (giống `themes/default/config.ini`).
   Parse mỗi `partials/blocks/*.html` lấy:
   - `module` = data-nv-source (Phase A: tất cả là `"theme"`)
   - `file_name` = `"global.<data-nv-block>.php"`
   - `position` = data-nv-position
   - `all_func` = "all" → 1, ngược lại 0
   - `funcs` = data-nv-funcs (nếu khác "all")
   - `config` = serialize default từ JSON manifest (Phase A có thể empty
     hoặc default đơn giản — không cần ID DB vì hardcode HTML)

   ⚠️ BẮT BUỘC: KHÔNG bỏ sót bất kỳ block nào (kể cả menu top, menu footer,
   header, ticker, contact). Nếu thiếu, khi cài theme vị trí đó sẽ trống trắng!

5. **Verify**:
   - `find src/data/cache -name "*.cache" -delete`
   - `find src/data/cache/smarty-compile -name "*.php" -delete`
   - Tạo `tools/check_xml.php` rồi `php tools/check_xml.php` (tránh `php -r`
     để không kích permission prompt — xem theme-patterns.md §pattern verify)

#### A.6. Verify Phase A

- Syntax pass:
  ```bash
  find src/themes/<theme-name> -name "*.php" -exec php -l {} \;
  find src/themes/<theme-name>/blocks -name "*.json" -exec python -m json.tool {} \;
  ```
- Build CSS chắc: `npm run <theme-name>-css`
- **BÁO CÁO + DỪNG**:
  - Số file đã tạo (chia: SCSS / TPL layout / Block 3-file / config.ini)
  - Token đã override (top 10)
  - Số block trong setblocks (count)
  - Risk còn lại / TODO
- **Hướng dẫn Dev verify**:
  > Vào Admin → Giao diện → Active theme `<theme-name>`. Trang chủ phải
  > render giống mockup home.html ≥ 95%. DevTools Network: KHÔNG có request
  > `chrome.js`. Nếu OK → gõ `OK chuyển B`.

### PHASE B — Module TPL viết mới (category, article page)

Mục tiêu: click vào chuyên mục / bài viết phải render khớp mockup. Phase A
chỉ lo trang chủ; Phase B lo các route module/news/*.

⚠️ Cảnh báo: future không override `viewcat_main_left.tpl`, `detail.tpl` →
nếu không viết mới, NV5 fallback `default/` → có thể parse fail
(xem theme-patterns.md §3).

#### B.0. ⚠️ TRAP — Engine TPL khác nhau giữa các file (Smarty vs XTemplate)

**Nguyên tắc**: TPL nào dùng Smarty hay XTemplate phụ thuộc vào file
`theme.php` / `funcs/*.php` của module gốc — KHÔNG đoán bừa.

→ **Đọc theme-patterns.md §3.1** để xem bảng engine đầy đủ + lệnh verify nhanh.

**Quan trọng cho Phase B (highlight nhanh)**:
- `viewcat_main_left.tpl` → **NVSmarty** (`{$VAR}`)
- `detail.tpl` → **XTemplate** (`{VAR}` + `<!-- BEGIN: x -->`) — KHÔNG phải Smarty

⚠️ Tài liệu cũ có thể ghi sai "viết Smarty cho detail.tpl" — verify từ source
hiện tại bằng `grep "new XTemplate\|NVSmarty.*detail.tpl" src/modules/news/theme.php`.

#### B.1. Đối chiếu nv-routes.md

- Đọc cột "Layout" + "TPL target" trong `nv-routes.md`
- Liệt kê các TPL VIẾT MỚI:
  - `modules/news/viewcat_main_left.tpl` (Smarty — category page)
  - `modules/news/detail.tpl` (**XTemplate** — article detail)
  - `modules/news/main.tpl` (nếu mockup home có khác future)
  - `modules/contact/main.tpl` (Smarty, nếu mockup contact đặc thù)
  - … (theo nv-routes.md cụ thể, kèm engine confirmed từ B.0)

#### B.2. Convert mockup → TPL

Với MỖI mockup trong `mockups/*.html` cần convert:

1. Tách main content (giữa header/footer) — header/footer đã ở layout
2. Convert biến **theo engine** đã xác nhận ở B.0:
   - **Smarty**: `{$row.title}`, `{foreach $items as $item}…{/foreach}`,
     `{if !empty($x)}` (xem theme-patterns.md §10)
   - **XTemplate**: `{ROW.title}`, `<!-- BEGIN: loop -->...<!-- END: loop -->`,
     không có if (dùng `parse('main.X')` từ PHP để tách flow)
3. Constant: `{$smarty.const.NV_BASE_SITEURL}` (Smarty) hoặc PHP-side build URL
   rồi assign vào XTemplate
4. LANG: `{$LANG->getModule('key')}` (Smarty) hoặc `{LANG.key}` (XTemplate
   lưu ý PHP assign `$xtpl->assign('LANG', $lang_module)` chứ KHÔNG phải object)
5. Sanitize: `{$var|escape:'html'}` (Smarty) cho raw, KHÔNG escape `get_title()`
6. Lưu vào đúng path TPL theo nv-routes.md

⚠️ Nếu mockup có block (partials/blocks/* include qua `data-include`) →
KHÔNG inline lại trong TPL. Block đã có ở Phase A, sẽ tự gắn qua position
trong layout TPL.

#### B.3. Verify Phase B

- Smarty: `grep -n '{[A-Z][A-Z_]*' <file>.tpl` — phát hiện sót XTemplate syntax
- XTemplate: `grep -n '{\$' <file>.tpl` — phát hiện sót Smarty syntax
- Cân đối block markers XTemplate: `grep -c "<!-- BEGIN:" <file>.tpl` =
  `grep -c "<!-- END:" <file>.tpl`
- **BÁO CÁO + DỪNG**:
  - Danh sách TPL đã viết mới (kèm engine từng file)
  - Risk còn lại
- **Hướng dẫn Dev verify**:
  > Click 1 chuyên mục bất kỳ + 1 bài bất kỳ. So với mockup category.html /
  > article.html. Nếu OK → gõ `OK chuyển C`.

### PHASE C — Seed manifest (data demo)

Mục tiêu: dựng full data demo cho theme (categories, articles, banners,
menus, users…). Chỉ là TẠO MANIFEST + CHẠY SEED — không động vào theme code.

#### C.1. Tạo manifest JSON

Đặt tại `src/data/seeder/<theme-name>/` (xem theme-patterns.md §7 +
src/modules/seeder/README.md):

| File | Mô tả |
|------|-------|
| `categories.json` | Chuyên mục news (theo bảng "Chuyên mục" trong Plan.md) |
| `topics.json` | Chủ đề news |
| `departments.json` | Phòng ban contact |
| `menus.json` | Bộ menu module menu |
| `banner-positions.json` | Vị trí banner (banners_plans) |
| `users.json` | User demo (password trong `_default_password`) |
| `theme-config.json` | Logo, hotline, schema.org info — `_target_theme: '<theme>'` |
| `articles.sample.json` | Bài viết mẫu (set `homeimgthumb=2` vì lưu uploads/) — **BẮT BUỘC ≥ 10 bài/cat** cho mọi cat hiển thị ở mockup home (xem C.1.1) |
| `banners.sample.json` | Banner mẫu (`plan_title` trỏ tới banner-positions) |

#### C.1.1. Quy chuẩn số lượng bài viết mẫu

**BẮT BUỘC: tối thiểu 10 bài/cat** cho mọi cat xuất hiện ở mockup home/category/article.
Lý do: block `news-cat` (cat-hero-grid) cần ≥ 7 bài/cat (1 hero card + 6 bullets).
Block `headline_hero` (1 hero + 4 rail) + `tophits` + `newest` còn cần thêm bài.
Nếu < 10 bài/cat, render trang chủ sẽ vỡ giao diện (block trống hoặc thiếu hero/bullets).

**Phân bổ chuẩn (vd 9 cat → 90 bài):**
- Cat `tin-noi-bat` (hero source): **10 bài tất cả `hometop: 1`** — feed cho headline_hero + ticker
- Các cat khác: 10 bài/cat, **1-2 bài có `hometop: 1`** để ticker fallback có data

**Trap nội dung:**
- KHÔNG dùng "Lorem ipsum" — phải tiếng Việt thực tế (tin tức giả lập có số liệu, tên tổ chức, địa điểm)
- `image_seed` phải UNIQUE giữa tất cả bài (format: `<catalias>-<keyword>-<index>`)
- Title đa dạng theo cat — cat `the-thao` không có bài về VN-Index, cat `cong-nghe` không có bài về bóng đá
- `bodyhtml` 2-3 đoạn `<p>...</p>`, ~400-600 ký tự (cô đọng, đủ render UI; KHÔNG cần dài hơn)

#### C.1.2. Pattern sinh `articles.sample.json` — 1 cat / 1 sub-agent / model Haiku

⚠️ **KHÔNG spawn 1 sub-agent monolithic generate cả 90 bài cùng lúc** —
agent stuck (đã verify Phase C build news2026: agent generate ~90 KB content
trong nội bộ KHÔNG flush ra Write file, kill sau 10+ phút không có output).

**Pattern đã verify hoạt động** (build news2026, ~3 phút tổng):

1. **Spawn N sub-agent song song** (N = số cat — vd 9 cat → 9 agent), trong **CÙNG 1 message** với nhiều `Agent` tool calls (parallelism = 9). Mỗi agent:
   - `subagent_type: "general-purpose"`
   - `model: "haiku"` ← **dùng Haiku cho task seed manifest** (text generation đơn giản, rẻ và nhanh hơn Sonnet ~3-4×; chỉ giao Sonnet cho task code/khảo sát phức tạp)
   - `run_in_background: true` (parallel với main session)
   - Phụ trách **đúng 1 cat** → ghi 1 file JSON tạm `src/data/seeder/<theme>/_articles_parts/<catalias>.json` chứa **MẢNG JSON 10 items** (KHÔNG có wrapper top-level — chỉ là `[{...}, {...}, ...]`)
   - Prompt self-contained: schema item, bảng chủ đề 10 bài cho cat đó (KHÔNG lặp với cat khác), anti-pattern (no Lorem, image_seed unique, no PowerShell cmdlet trong Bash)

2. **Đợi tất cả agent xong** — runtime tự thông báo, KHÔNG poll/tail file output.

3. **Merge bằng PHP script** `tools/merge_articles.php` (mẫu đã có ở repo nukeviet5.0):
   ```php
   $cats = ['tin-noi-bat', 'the-gioi', 'chinh-tri-xa-hoi', ...];
   $all = [];
   foreach ($cats as $cat) {
       $items = json_decode(file_get_contents($dir . '/' . $cat . '.json'), true);
       $all = array_merge($all, $items);
   }
   $wrapper = [
       '_comment' => '...',
       '_natural_key' => ['catalias', 'alias_hint'],
       '_image_size' => ['default' => [800, 500], 'hometop' => [1200, 630]],
       'items' => $all,
   ];
   file_put_contents($out, json_encode($wrapper, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
   ```

   Script tự verify trong cùng 1 chạy: count items mỗi cat, count `hometop=1`, count `homeimgthumb=2`, check `image_seed` uniqueness.

4. **Cleanup**: xóa thư mục tạm `_articles_parts/` sau khi merge (`find _articles_parts -type f -delete && find _articles_parts -type d -empty -delete`).

**Vì sao Haiku đủ cho task này:**
- Sinh title/sapo/bodyhtml đơn giản — không cần reasoning sâu
- Mỗi agent chỉ generate ~10 items (~5-7 KB) — Haiku xử lý dư sức
- Cost rẻ × 9 agent = vẫn rẻ hơn 1 Sonnet monolithic
- Nhanh hơn Sonnet ~3× → 9 agent parallel xong trong 2-3 phút

**Khi nào VẪN dùng Sonnet:** task migrate XTemplate→Smarty, rewrite block PHP query DB, override TPL phức tạp — mọi task có CODE (không phải text content). Xem theme-patterns.md §9.1 model selection table.

#### C.2. Reset DB sạch trước khi seed

Đảm bảo auto-increment từ 1 (để Phase D hardcode ID deterministic):
- Báo Dev backup DB hiện tại nếu có data quan trọng
- Reset module news + contact + menu + banners + users (qua Admin → Module
  → Gỡ → Cài lại) HOẶC truncate trực tiếp các bảng dữ liệu (giữ structure)

#### C.3. Chạy seed

Qua Admin → Seeder → Chạy seed:
- Dropdown "Theme target": chọn `<theme-name>`
- Tick các step cần (badge ⚠️ "internet" ở articles/banners cảnh báo cần
  internet để tải ảnh từ picsum.photos)
- Bấm "Chạy tất cả" (chạy theo dependencies)

Seeder KHÔNG có CLI — chỉ chạy qua Admin UI vì cần `$crypt`, `$db`, `$global_config`.

#### C.4. Ghi bảng ID deterministic vào Plan.md (mục "ID Map")

| Loại | Manifest order | ID expected | Alias |
|------|----------------|-------------|-------|
| Category 1 | catid=1 | 1 | tin-noi-bat |
| Topic 1 | topicid=1 | 1 | tin-nong |
| Menu 1 | menuid=1 | 1 | main-menu |
| Banner plan 1 | pid=1 | 1 | header-728 |

#### C.5. Verify Phase C

- Syntax pass:
  ```bash
  find src/data/seeder/<theme-name> -name "*.json" -exec python -m json.tool {} \;
  ```
- **BÁO CÁO + DỪNG**: số manifest đã tạo, số step seed đã chạy (n/9), bảng ID Map
- **Hướng dẫn Dev verify**:
  > Vào Admin → News → Chuyên mục: xem có đầy đủ chuyên mục.
  > Admin → News → Bài viết: xem có articles demo + ảnh.
  > Nếu OK → gõ `OK chuyển D`.

### PHASE D — Setblocks tinh chỉnh per-route (theo data đã seed)

Mục tiêu: Phase A đã có setblocks "thô" (tất cả block hiện trên home).
Phase D tinh chỉnh: block nào hiện ở route nào, với data ID nào.

#### D.1. Update <setblocks> với ID thật

Mở `src/themes/<theme-name>/config.ini`. Với MỖI block có cấu hình
tham chiếu DB (catid, topicid, menuid, pid…):
- Lấy ID từ bảng "ID Map" ở Plan.md (Phase C.4)
- Update `config` (serialize) với ID đúng

⚠️ TYPE TRAP: đọc `nv_block_config_<X>_submit()` để biết type chính xác.
Vd `catid` của `block_news_cat` là array (multi-select), KHÔNG string.
Tính serialize bằng PHP — KHÔNG dùng `php -r` (kích permission prompt) →
viết script `tools/serialize_check.php` rồi `php tools/serialize_check.php`.

Xem theme-patterns.md §1 (format) + §1.1 (type field) + §1.2 (workflow seed-first).

#### D.2. Tinh chỉnh per-route

Với mỗi route đặc thù (theo nv-routes.md):
- `all_func = 0` + `funcs = "main|viewcat|detail"` để giới hạn block hiện ở route nào
- Vd: block "Tin xem nhiều" chỉ hiện ở viewcat + detail, KHÔNG ở contact

#### D.3. Reset cache + verify

```bash
find src/data/cache -name "*.cache" -delete
find src/data/cache/smarty-compile -name "*.php" -delete
```

#### D.4. Verify Phase D

- **BÁO CÁO + DỪNG**: số block đã update config, route nào tinh chỉnh
- **Hướng dẫn Dev verify**:
  > Reset theme: Admin → Giao diện → Tắt rồi bật lại theme (NV5 re-parse
  > setblocks). Check log: `data/logs/error_logs/<today>_notice_log.log` RỖNG.
  > Click 5 route (home, category, article, contact, login) → block đặc thù
  > hiện đúng. Nếu OK → gõ `OK chuyển E`.

### PHASE E — Convert block hardcode → reuse module / data thật

> **Trước Phase E: ĐỌC `design/Block.md`** — file này có inventory 30 block sẵn
> ở 5 thư mục (`themes/future/blocks`, `modules/{news,banners,contact,menu}/blocks`)
> kèm function name, type field config, engine TPL. Block.md là source of truth
> để chọn block module reuse phù hợp + biết template engine override (Smarty
> hay XTemplate) — TRÁNH đoán bừa.

Mục tiêu: rút HTML hardcode trong block TPL Phase A, chuyển sang:
- Reuse module block (vd `news/global.block_tophits.php`) nếu phù hợp
- HOẶC giữ theme block nhưng rewrite PHP để query DB thật

#### E.1. Phân loại block — 4 strategy (KHÔNG phải 3)

Với mỗi block ở `themes/<theme-name>/blocks/`, quyết định:

| Strategy | Khi nào | Hành động |
|----------|---------|-----------|
| **(1) Reuse module — KHÔNG override TPL** | Block module có TPL Smarty default vừa đủ + module ASSIGN đầy đủ biến cần thiết | setblocks đổi `module="theme"` → `module="<m>"`, `file_name="global.X.php"` + serialize config với ID seed. Xóa file theme block. |
| **(2) Reuse module — CÓ override TPL** | Block module dùng được PHP nhưng TPL default KHÔNG khớp mockup | Như (1) + tạo override TPL ở `themes/<theme>/modules/<m>/<X>.tpl` (engine theo Block.md §1.2 — có thể XTemplate vd `block_groups.tpl`, không phải Smarty) |
| **(3) Theme block thay reuse** | Module có PHP nhưng **ASSIGN THIẾU BIẾN** (vd `nv_block_news_cat()` không assign `CATNAME`/`CATLINK` → không render được heading section) | Giữ file `themes/<theme>/blocks/global.X.php`, rewrite query DB + assign đủ biến + render Smarty TPL khớp mockup |
| **(4) Giữ hardcode** | Block UI tĩnh không cần DB (footer copyright, hotline, header logo) | Giữ nguyên Phase A |

⚠️ **Trap (2) — engine TPL override**: phải đọc PHP module block xem nó dùng
`new XTemplate(...)` hay `new \NukeViet\Template\NVSmarty(...)`. Nhầm engine
khi override → render text raw `{$VAR}` hoặc parse fail. Vd `news/global.block_news_cat.php`
dùng `XTemplate('block_groups.tpl')` — file override phải XTemplate syntax.

⚠️ **Trap (3) — module ASSIGN thiếu biến**: news2026 phát hiện
`nv_block_news_cat()` chỉ assign `ROW` trong loop — không có `CATNAME`/`CATLINK`/
`SUBCATS`. Nếu mockup yêu cầu heading "Kinh doanh" + tabs sub-cat, KHÔNG thể
render bằng (2) override TPL. Phải dùng (3): tạo theme block riêng query DB,
tách HERO+BULLETS+TABS, assign vào Smarty TPL khớp mockup.

→ Verify trước khi chọn (1)/(2)/(3): `grep "\$xtpl->assign\|\$tpl->assign"
src/modules/<m>/blocks/<X>.php` để liệt kê biến module assign.

#### E.2. Rewrite theme block (nếu chọn strategy 3)

Với block "Rewrite theme block" — viết đầy đủ 4 hàm chuẩn (xem
docs/knowledge/theme.md §"Block global" + theme-patterns.md §9):

- `nv_<name>_config()` — schema config
- `nv_<name>_submit()` — handle form submit
- `nv_<name>($block_config)` — query DB + render TPL
- Cuối: `if (defined('NV_SYSTEM')) { $content = nv_<name>($block_config); }`

JSON manifest: đầy đủ key default config (nếu thiếu → render lỗi
"Undefined array key", xem theme-patterns.md §1).

⚠️ Block phức tạp (>100 dòng PHP, multi-query) → spawn sub-agent với
`subagent_type="general-purpose"`, `model="sonnet"` (theme-patterns.md §9.2).
**BẮT BUỘC** truyền `model: "sonnet"` cho sub-agent CODE/khảo sát NV5.

⚠️ Riêng sub-agent generate **text content** (vd `articles.sample.json` —
xem C.1.2): dùng `model="haiku"` (rẻ + nhanh hơn ~3×, đủ năng lực cho
title/sapo/bodyhtml tiếng Việt đơn giản).

#### E.3. Update TPL block

Block đã rewrite — TPL convert hardcode HTML → Smarty:
- `<a href="#">` → `<a href="{$item.url}">`
- `<img src="https://picsum.photos/...">` → `<img src="{$item.image}">`
- `<h3>Tiêu đề demo</h3>` → `<h3>{$item.title|escape:'html'}</h3>`
- List item → `{foreach $items as $item}...{/foreach}`

#### E.4. Verify Phase E

- Syntax pass:
  ```bash
  find src/themes/<theme-name> -name "*.php" -exec php -l {} \;
  find src/themes/<theme-name>/blocks -name "*.json" -exec python -m json.tool {} \;
  ```
- Reset cache:
  ```bash
  find src/data/cache -name "*.cache" -delete
  find src/data/cache/smarty-compile -name "*.php" -delete
  ```
- **BÁO CÁO CUỐI**:
  - Số block đã reuse module / rewrite / giữ hardcode
  - Mockup nào convert OK (5/5 hay thiếu)
  - Risk còn lại / TODO
- **Hướng dẫn Dev verify**:
  > 5 route render khớp mockup ≥ 95% với data THẬT từ DB.
  > Log notice RỖNG. Nếu OK → gõ `OK chuyển F` (polish cuối).

### PHASE F — Polish: override TPL module + fix Smarty traps + audit lỗi

Mục tiêu: sau Phase E setblocks reuse module hoạt động, giao diện có thể chưa
khớp mockup 100% vì TPL module gốc render style khác. Phase F polish:

#### F.1. Override TPL module (Smarty/XTemplate theo source)

Với mỗi block chọn strategy (2) "Reuse + override TPL" ở Phase E:

1. Đọc PHP source `src/modules/<m>/blocks/<X>.php` xác định:
   - Engine: `new \NukeViet\Template\NVSmarty()` hay `new XTemplate()`
   - Biến assign: `$tpl->assign('VAR', ...)` hoặc `$xtpl->assign('VAR', ...)`
2. Tạo file override ở `src/themes/<theme>/modules/<m>/<X>.tpl` (engine khớp)
3. Render HTML khớp mockup CSS class (vd `.cat-hero-grid`, `.block-hits`,
   `.block-tags .tag`, `.partner-sidebar`)
4. Spawn N sub-agent song song nếu có nhiều TPL (model `sonnet`):
   - Group A: TPL Smarty (đã sẵn engine match nhau)
   - Group B: TPL XTemplate (engine khác — prompt phải nhấn mạnh)

#### F.2. Rewrite block PHP với data thật (strategy 3)

Block module gốc không assign đủ biến → rewrite theme block riêng:
- Query DB tương tự module nhưng tách theo cấu trúc mockup (vd HERO + BULLETS)
- Assign biến rõ ràng vào Smarty TPL: `CAT`, `TABS`, `HERO`, `BULLETS`...
- Vd `news_cat` của news2026: `nv_block_news_cat()` của module gốc không assign
  CATNAME → tạo theme block `global.news_cat.php` riêng query DB + tách
  HERO/BULLETS

Spawn sub-agent `model="sonnet"` cho rewrite này (code+query+TPL).

#### F.3. Fix Smarty PHP 8+ traps

→ Quy luật canonical + bảng mapping ở **theme-patterns.md §10** (đọc kỹ §10.1
`!empty()`, §10.2 `date_format` không `%`, §10.3 lệnh audit grep).

**Audit nhanh + fix** (chạy ở Phase F):

```bash
grep -rn '{if \$[A-Za-z_][A-Za-z0-9_.]*}' src/themes/<theme>/blocks src/themes/<theme>/modules
grep -rn 'date_format:"%' src/themes/<theme>
```

→ File theo theme đang build (tự tay tạo) phải clean. File copy từ future
(layout/, modules/ khác) có thể tạm bỏ qua — fix upstream ở `themes/future/`.

#### F.4. Verify Phase F

```bash
# Smarty syntax check
grep -rn '{if \$[A-Za-z_][A-Za-z0-9_.]*}' src/themes/<theme>/blocks  # nên = 0 với file tự build
grep -rn 'date_format:"%' src/themes/<theme>                          # phải = 0
# PHP + JSON
find src/themes/<theme> -name "*.php" -exec php -l {} \;
find src/themes/<theme>/blocks -name "*.json" -exec python -m json.tool {} \;
# Cache
find src/data/cache -name "*.cache" -delete
find src/data/cache/smarty-compile -name "*.php" -delete
```

- **BÁO CÁO CUỐI**:
  - Số TPL override đã tạo (chia: Smarty / XTemplate)
  - Số block rewrite (strategy 3)
  - Smarty trap đã fix (count if và date_format)
  - Render diff so mockup (5 route) — % match
- **Hướng dẫn Dev verify**:
  > 5 route khớp mockup 100% với data thật.
  > `data/logs/error_logs/<today>_notice_log.log` rỗng.
  > Nếu OK → kết thúc build, commit + tạo MR.

### RÀNG BUỘC TUÂN THỦ (áp dụng mọi phase)

**Code NV5 PHP**:
- CLAUDE.md: PSR-12, camelCase, `$nv_Lang->getModule/getGlobal`
  (KHÔNG `$lang_module`)
- `$nv_Request` (KHÔNG `$_GET/$_POST` trực tiếp)
- `prepare()+bindParam()` cho user string, `(int)` cast cho số nguyên
- Output: `nv_htmlspecialchars()` cho raw, KHÔNG escape lại data từ `get_title()`
- CSRF: `csrf_create()` + `csrf_check()` khi nhận POST
- `nv_is_file()` KHÔNG `is_file()` với path từ user
- `defined('NV_IS_ADMIN')` trước khi ghi data

**Smarty TPL (PHP 8+)**:
- BẮT BUỘC `{if !empty($var)}` thay `{if $var}` cho biến/property mảng — chi tiết theme-patterns.md §10.1
- BẮT BUỘC `date_format:"d/m/Y"` (không `%`) — chi tiết §10.2

**TPL engine theo source** (Smarty vs XTemplate):
- BẮT BUỘC verify từ source trước khi viết/override TPL — bảng đầy đủ ở theme-patterns.md §3.1
- Highlight: `viewcat_main_left.tpl` Smarty · `detail.tpl` **XTemplate** (KHÔNG Smarty) · `block_groups.tpl` `block_news.tpl` (override cho block_news_cat / module.block_news) **XTemplate**

**SCSS**:
- Sass 1.71+: KHÔNG `lighten()/darken()/mix()` — dùng `color.adjust()`

**Sub-agent**:
- CODE / khảo sát / convert mockup → `model="sonnet"` BẮT BUỘC
- Generate **text content** (seed manifest articles/banners) → `model="haiku"`
  (xem C.1.2 — pattern 1 cat/1 agent song song, KHÔNG monolithic)

**Tool/CLI**:
- Mọi tool description tiếng Việt
- **Pattern verify**: dùng `find -exec php -l` + `python -m json.tool` thay
  vì `php -r "..."` (kích permission prompt). XML config dùng
  `tools/check_xml.php`. Serialize dùng `tools/serialize_check.php`.

**Knowledge base reference** (đọc theo nhu cầu, KHÔNG đọc tất cả ở Phase 0):
- `design/theme-patterns.md` — patterns/traps generic NV5 (10 sections). Đọc tổng quan ở Phase 0; ref cụ thể ở từng phase: §1 setblocks (A.5/D.1) · §3 Smarty fallback (A.0) · §3.1 engine TPL (B.0/F.1) · §5 URL ảnh news (E.2/F.2) · §7 seeder (C) · §8 verify (mọi phase) · §9 sub-agents (mọi phase) · §10 Smarty PHP 8+ (F.3)
- `design/Block.md` — inventory 30 block module sẵn có. **Phase E.1** BẮT BUỘC đọc Phần 1 + 2 trước khi chọn strategy reuse. **Phase F.1** ref Phần 3 (phương án) khi override TPL. Phase 0/A/B/C/D KHÔNG cần đọc.
- `design/<theme>/Plan.md` — plan cụ thể từng theme (token, block, seed). Phase 0 tạo nếu chưa có; mọi phase tham chiếu.

**Quy trình NV5**: Phân tích → Plan → CHỜ "OK" → Thực thi
- Phase 0 dừng chờ Dev OK
- Sau MỖI Phase A/B/C/D dừng chờ Dev gõ `OK chuyển <phase kế>`
- KHÔNG chạy 2 phase liền nhau khi chưa có confirm
- Plan Dev đã làm sẵn (Plan.md tồn tại) → cứ chạy, Dev check cuối phase
```

---

## Khi nào cần chạy riêng 1 phase

Không phải lúc nào cũng phải chạy 6 phase từ đầu. Ví dụ:
- **Đổi tokens (đã có theme)**: "Chạy lại Phase A.1 với token mới trong design-system.html"
- **Thêm 1 block mới**: "Đọc partials/blocks/<new>.html, làm Phase A.2-A.3 cho block này + Phase E nếu cần data thật"
- **Convert thêm 1 mockup**: "mockups/new-page.html, làm Phase B + cập nhật nv-routes.md"
- **Reseed lại dữ liệu**: "Reset DB + chạy Phase C lại + Phase D update ID"
- **Override TPL module mới**: "Phase F.1 cho `<m>/<X>.tpl` — đọc Block.md §1.2 + theme-patterns.md §3.1 trước"
- **Fix Smarty PHP 8+ traps**: "Phase F.3 audit + fix theo theme-patterns.md §10"

Cú pháp: "Đọc nv-routes.md + chạy Phase X cho `<file>`".
