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
- [design/theme-patterns.md](../theme-patterns.md) — knowledge base patterns/traps generic
- [design/<theme-name>/Plan.md](<theme-name>/Plan.md) — plan cụ thể decision (token, block, seed) — ví dụ `design/news2026/Plan.md`

### PHASE 0 — ĐỌC + LẬP KẾ HOẠCH (BẮT BUỘC, chờ Dev OK trước khi sang Phase A)

1. Đọc:
   - design/<theme-name>/output/nv-routes.md (toàn bộ — đây là contract)
   - design/<theme-name>/output/design-system.html (chỉ phần <script id="nv-tokens">)
   - design/<theme-name>/output/partials/nv-theme.css (đếm component, ước CSS size)
   - design/<theme-name>/output/release-notes.md (version, theme name)
   - 1-2 file blocks bất kỳ trong partials/blocks/ để hiểu data-nv-* convention
   - design/theme-patterns.md (knowledge base — đọc 1 lần)

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

### TỔNG QUAN 5 PHASE (gate sau mỗi phase)

| Phase | Tên | Output verify |
|-------|-----|---------------|
| **A** | Skeleton + tất cả block hardcode HTML + setblocks + Build CSS từ scss/<theme-name> | Active theme → trang chủ render giống mockup ≥ 95%, KHÔNG cần DB |
| **B** | Module TPL viết mới (`viewcat_main_left.tpl`, `detail.tpl`…) cho category/article page | Click vào chuyên mục/bài viết → render khớp mockup |
| **C** | Seed manifest (`src/data/seeder/<theme-name>/`: categories, articles, banners, menus, users, theme-config) | Admin → Seeder chạy hết step, DB có data demo |
| **D** | Setblocks tinh chỉnh per-route (theo data đã seed) | Mỗi route hiện đúng block đặc thù (vd home khác category) |
| **E** | Convert block hardcode → reuse module block / data thật | Block load data thật từ DB, KHÔNG còn HTML hardcode |

⚠️ **GATE BẮT BUỘC**: Sau khi Claude Code làm xong 1 phase, **BÁO CÁO + DỪNG**.
Dev kiểm tra trên trình duyệt + gõ `OK chuyển B/C/D/E` mới chạy phase kế.
KHÔNG chạy liền 2 phase mà không có confirm.

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

| File | Thay đổi |
|------|----------|
| `header_extended.tpl` | Hardcode HTML từ `partials/site-header.html` + `site-nav.html` (topbar + logo + 16 chuyên mục). Thêm position `[TOPBAR]` `[USER_BUTTON]` `[MAIN_NAV]` để mở rộng |
| `footer_extended.tpl` | Hardcode HTML từ `partials/site-footer.html` (footer-top 5 cột chuyên mục + col-apps hotline/social + footer-bar copyright). Thêm position `[FOOTER_INFO]` `[FOOTER_MENU]` `[FOOTER_SOCIAL]` `[FOOTER_COPYRIGHT]` |
| `layout.content-esbar.tpl` | Khung: `[BANNER_TOP]` → `[TICKER]` → `[HERO]` → wrapper `.hero-with-rail` chứa `[HERO_MAIN]+[HERO_RAIL]` → row 8/4 cột với `[CONTENT_TOP/MID/BOTTOM]` + `[SIDEBAR_RIGHT]` |
| `layout.content.tpl` | Bổ sung `[BANNER_TOP]` `[TICKER]` cho contact page |

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
nếu không viết mới, NV5 fallback `default/` (XTemplate) → Smarty fail
(xem theme-patterns.md §3).

#### B.1. Đối chiếu nv-routes.md

- Đọc cột "Layout" + "TPL target" trong `nv-routes.md`
- Liệt kê các TPL VIẾT MỚI Smarty (thường là):
  - `modules/news/viewcat_main_left.tpl` (category page)
  - `modules/news/detail.tpl` (article detail)
  - `modules/news/main.tpl` (nếu mockup home có khác future)
  - `modules/contact/main.tpl` (nếu mockup contact đặc thù)
  - … (theo nv-routes.md cụ thể)

#### B.2. Convert mockup → TPL Smarty

Với MỖI mockup trong `mockups/*.html` cần convert:

1. Tách main content (giữa header/footer) — header/footer đã ở layout
2. Convert biến:
   - `<a href="#">` → `<a href="{$smarty.const.NV_BASE_SITEURL}…">`
   - Text label → `{$LANG->getModule('key')}` hoặc `{$LANG->getGlobal('key')}`
   - List item → `{foreach $items as $item}…{/foreach}`
3. Sanitize output: `{$var|escape:'html'}` cho raw, KHÔNG escape `get_title()`
4. Lưu vào đúng path TPL theo nv-routes.md

⚠️ Nếu mockup có block (partials/blocks/* include qua `data-include`) →
KHÔNG inline lại trong TPL. Block đã có ở Phase A, sẽ tự gắn qua position
trong layout TPL.

#### B.3. Verify Phase B

- Syntax pass: `find src/themes/<theme-name> -name "*.tpl"` (Smarty không
  có CLI lint, dựa vào fetch test)
- **BÁO CÁO + DỪNG**:
  - Danh sách TPL đã viết mới
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
| `articles.sample.json` | Bài viết mẫu (set `homeimgthumb=2` vì lưu uploads/) |
| `banners.sample.json` | Banner mẫu (`plan_title` trỏ tới banner-positions) |

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

### PHASE E — Convert block hardcode → reuse module block / data thật

Mục tiêu: rút HTML hardcode trong block TPL Phase A, chuyển sang:
- Reuse module block (`global.news.cat.php`, `global.menu.simple.php`…) nếu phù hợp
- HOẶC giữ theme block nhưng rewrite PHP để query DB thật

#### E.1. Phân loại block

Với mỗi block ở `themes/<theme-name>/blocks/`, quyết định:

| Strategy | Khi nào | Hành động |
|----------|---------|-----------|
| **Reuse module block** | Block giống module sẵn có (vd "tin mới nhất" giống `news/global.news.cat.php`) | Trong setblocks: đổi `module` từ `"theme"` → `"news"`, `file_name` → `"global.news.cat.php"`. Xóa block ở `themes/<name>/blocks/` |
| **Rewrite theme block** | Block đặc thù theme (vd ticker custom) | Giữ file PHP nhưng thay logic minimal bằng query DB + render TPL với data thật |
| **Giữ hardcode** | Block UI tĩnh không cần DB (vd footer copyright, hotline) | Giữ nguyên |

#### E.2. Rewrite theme block (nếu cần)

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
**BẮT BUỘC** truyền `model: "sonnet"` cho mọi sub-agent task NV5.

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
  > Log notice RỖNG. Nếu OK → kết thúc build, commit + tạo MR.

### RÀNG BUỘC TUÂN THỦ (áp dụng mọi phase)

- CLAUDE.md: PSR-12, camelCase, `$nv_Lang->getModule/getGlobal`
  (KHÔNG `$lang_module`)
- `$nv_Request` (KHÔNG `$_GET/$_POST` trực tiếp)
- `prepare()+bindParam()` cho user string, `(int)` cast cho số nguyên
- Output: `nv_htmlspecialchars()` cho raw, KHÔNG escape lại data từ `get_title()`
- CSRF: `csrf_create()` + `csrf_check()` khi nhận POST
- `nv_is_file()` KHÔNG `is_file()` với path từ user
- `defined('NV_IS_ADMIN')` trước khi ghi data
- Sass 1.71+: KHÔNG `lighten()/darken()/mix()` — dùng `color.adjust()`
- Sub-agent: `model="sonnet"` BẮT BUỘC
- Mọi tool description tiếng Việt
- **Pattern verify**: dùng `find -exec php -l` + `python -m json.tool` thay
  vì `php -r "..."` để tránh permission prompt
- **Quy trình NV5**: Phân tích → Plan → CHỜ "OK" → Thực thi
  - Phase 0 dừng chờ Dev OK
  - Sau MỖI Phase A/B/C/D dừng chờ Dev gõ `OK chuyển <phase kế>`
  - KHÔNG chạy 2 phase liền nhau khi chưa có confirm
```

---

## Khi nào cần chạy riêng 1 phase

Không phải lúc nào cũng phải chạy 5 phase từ đầu. Ví dụ:
- **Đổi tokens (đã có theme)**: "Chạy lại Phase A.1 với token mới trong design-system.html"
- **Thêm 1 block mới**: "Đọc partials/blocks/<new>.html, làm Phase A.2-A.3 cho block này + Phase E nếu cần data thật"
- **Convert thêm 1 mockup**: "mockups/new-page.html, làm Phase B + cập nhật nv-routes.md"
- **Reseed lại dữ liệu**: "Reset DB + chạy Phase C lại + Phase D update ID"

Cú pháp: "Đọc nv-routes.md + chạy Phase X cho `<file>`".
