# NukeViet 5 — Design Workspace

Thư mục chứa quy trình + prompt + bundle cho việc **thiết kế và build theme NukeViet 5** với sự hỗ trợ của AI (claude.ai/design + Claude Code).

---

## Cấu trúc

```
design/
├── README.md                          # File này — tổng quan workflow
├── guide-rebuild-design-system.md     # Quy trình chi tiết rebuild design system theme future
├── design-system.sh                   # Script tự động đóng bundle SCSS + TPL của future
├── prompt-claude-design.md            # Prompt paste vào claude.ai/design
├── prompt-claude-code.md              # ⭐ WORKFLOW spec — paste vào Claude Code (Phase 0 + 5 phase A-E)
├── theme-patterns.md                  # ⭐ KNOWLEDGE base — patterns/traps generic NV5 (đọc trước, KHÔNG paste)
├── <theme-name>/Plan.md               # ⭐ PLAN cụ thể do bạn hoặc AI sinh ra (chứa token override, block list, seed data, ID Map)
└── future/                            # Output bundle cho theme future
    ├── output/                        # Kết quả design-system.sh
    └── uploads/                       # Screenshots reference
```

> **3-layer documentation**:
> - **Workflow** (handoff) = generic Phase 0 + 5 phase A-E, bảo Claude Code đọc file này mỗi lần build.
> - **Patterns** (theme-patterns.md) = knowledge base, đọc 1 lần để hiểu trap & convention
> - **Plan** (`<theme-name>/Plan.md`, ví dụ `news2026/Plan.md`) = decision cụ thể từng theme (token override, block list, seed data, ID Map).

---

## Quy trình build theme NV5 (5 stage)

Áp dụng cho mọi theme. Stage 1 có 2 nhánh tùy thuộc đã có mockup designer chưa.

### STAGE 1 — Chuẩn bị input (chọn 1 trong 2 nhánh)

**Nhánh A — CHƯA có mockup → tự sinh qua claude.ai/design**
1. Chạy `bash design/design-system.sh` → sinh thư mục `design/design-system/` (bundle SCSS + TPL future).
2. Điền `_release-notes.md`, copy `screenshots/`, nén zip lại.
3. Upload file zip lên claude.ai/design. Paste prompt từ `design/prompt-claude-design.md`.
4. Nhận bundle output (`design-system.html`, `partials/`, `mockups/`, `nv-routes.md`, `nv-tokens JSON`) và đặt vào `design/<theme-name>/output/`.

**Nhánh B — ĐÃ có mockup designer (HTML + SCSS sẵn)**
1. Tạo thư mục: `mkdir -p design/<theme-name>/{scss,assets,uploads}`
2. Đặt mockup HTML, `_variables.scss`, `_override.scss`, `theme.css` (làm reference), và screenshot vào thư mục trên.
*(Lưu ý: KHÔNG copy chrome.js/JS riêng — sẽ ưu tiên dùng Bootstrap Modal/Collapse nguyên bản).*

---

### STAGE 2 — Kích hoạt Claude Code (Có gate xác nhận sau mỗi phase)

Mở terminal Claude Code, dùng model Opus (do Sonnet ko đủ Context window) tại project root. Gõ 1 câu duy nhất:
> `Hãy đọc hướng dẫn trong file design/prompt-claude-code.md và thực hiện build theme news2026. Bundle đã có ở design/news2026/output`

**Claude Code sẽ tự động chạy theo từng phase, dừng chờ bạn confirm:**

1. **Phase 0:** Tự động phân tích `nv-routes.md` + đọc `theme-patterns.md`, **tự sinh `<theme>/Plan.md`** (token override, block list, seed manifest, ID Map). *Dừng chờ bạn gõ `OK`*.
2. **Phase A:** Skeleton + tất cả block hardcode HTML + setblocks đầy đủ + Build CSS. *Dừng chờ verify trên browser → gõ `OK chuyển B`*.
3. **Phase B:** Module TPL viết mới (`viewcat_main_left.tpl`, `detail.tpl`…) cho category/article page. *Gate xác nhận*.
4. **Phase C:** Tạo seed manifest (`src/data/seeder/<theme>/`) + chạy Admin → Seeder + ghi ID Map. *Gate xác nhận*.
5. **Phase D:** Tinh chỉnh `<setblocks>` per-route với ID thật từ ID Map. *Gate xác nhận*.
6. **Phase E:** Convert block hardcode → reuse module block / data thật từ DB.

> 🔑 **Triết lý skeleton-first**: Phase A render UI giống mockup ngay (HTML cứng, không cần DB). Phase E mới rút hardcode thành data thật. Dev kiểm soát từng lớp, không bị nhiễu.

---

### STAGE 3 — Verify & Fine-tune

Sau Phase E:
1. Vào Admin → Giao diện → Kích hoạt theme (đã active từ Phase A.6).
2. Kiểm tra 5 route (home, category, article, contact, login) render khớp mockup ≥ 95% với data thật.
3. Tùy chỉnh (fine-tune) lại bằng tay hoặc gọi thêm Claude Code nếu cần sửa chi tiết.

> 🔑 **Cốt lõi**: workflow KHÔNG đổi (handoff Phase 0 + A-E generic) + patterns KHÔNG đổi (knowledge base). Chỉ có **plan cụ thể** (`<theme>/Plan.md`) thay đổi từng theme. Sửa 1 file, build 1 theme.

> ⚠️ **Khi gặp trap chưa có trong [theme-patterns.md](theme-patterns.md)** → bổ sung vào patterns (knowledge generic, áp dụng cho mọi theme sau), KHÔNG nhồi vào Request (Request chỉ chứa decision cụ thể theme đó).

---

## Bắt đầu từ đâu

| Tình huống | Đọc file |
|---|---|
| Lần đầu làm theme NV5 — đọc trap/convention generic trước | [theme-patterns.md](theme-patterns.md) |
| Lần đầu rebuild theme mới — đọc tổng quan workflow | [guide-rebuild-design-system.md](guide-rebuild-design-system.md) |
| Đã quen workflow, cần prompt cho claude.ai/design (Stage 1A) | [prompt-claude-design.md](prompt-claude-design.md) |
| Build theme NV5 (mọi theme — Stage 2) | [prompt-claude-code.md](prompt-claude-code.md) |
| Cần plan cụ thể theme `<theme-name>` (Phase 0 sẽ auto-sinh) | `<theme-name>/Plan.md` (vd `news2026/Plan.md`) |
| Cần seed dữ liệu demo (categories, menus, banners…) | [theme-patterns.md §7](theme-patterns.md) + [src/modules/seeder/README.md](../src/modules/seeder/README.md) |

---

## Triết lý

- **Bridge file `nv-routes.md`** = contract giữa designer và developer. Không có bridge → AI phải đoán → sai bóc.
- **Shared components qua `data-include`** = sửa 1 chỗ, mọi mockup đồng bộ. Không inline copy markup.
- **Token JSON** = `<script id="nv-tokens">` ở cuối design-system.html cho Claude Code parse trực tiếp sang `_variables.scss`, không phải đoán từ CSS compiled.
- **Phase 0 dừng chờ "OK"** + **gate sau mỗi Phase A/B/C/D** = mọi prompt cho Claude Code phải có phase plan trước khi thực thi, theo CLAUDE.md "Phân tích → Plan → Xác nhận → Thực thi". Phase nặng nhiều biến số (theme build) cần gate giữa các phase để Dev kiểm soát từng lớp.
- **Skeleton-first** = Phase A hardcode HTML để render giống mockup ngay (không cần DB). Phase E mới rút hardcode thành data thật. Tách concern UI vs data → khoanh vùng lỗi dễ hơn.
- **3-layer doc** (workflow / patterns / plan) = tách concern. Workflow generic paste vào AI; patterns generic đọc 1 lần; plan cụ thể từng theme. Không nhồi tất cả vào 1 file (sẽ phình + lẫn spec với knowledge).
