#!/usr/bin/env bash
# Bundle "design-system" — SCSS + Smarty TPL của theme future cho claude.ai/design
# Chạy: bash design/design-system.bash  (từ bất kỳ đâu)
set -euo pipefail

# Tự về project root (parent của design/)
cd "$(dirname "$0")/.."

OUT="design/design-system"
rm -rf "$OUT" "$OUT.zip"

# 1. Workspace
mkdir -p "$OUT"/scss/{future,standalone} \
         "$OUT"/themes/future/{layout,modules/{news,contact,users,page}}

# 2. SCSS — future + 5 dependency external
cp -r scss/future/.                "$OUT/scss/future/"
cp scss/{_functions,_show_pass,_ckeditor5}.scss  "$OUT/scss/"
cp scss/standalone/{_alert,_polyfill}.scss       "$OUT/scss/standalone/"

# 3. Layout TPL (15 file — header/footer/block + 6 layout cột + simple)
cp src/themes/future/layout/{layout.*,simple,header_*,footer_*,block.*}.tpl \
   "$OUT/themes/future/layout/"

# 4. Module TPL — chỉ module future thực sự override
#    News KHÔNG có main/detail (fallback default theme XTemplate)
for m in news contact users page; do
    cp src/themes/future/modules/$m/*.tpl "$OUT/themes/future/modules/$m/"
done

# 5. Cleanup + constraint
find "$OUT/themes" -name 'index.html' -delete
cp package.json "$OUT/"
cp design/prompt-claude-design.md "$OUT/"

# 6. Template _release-notes.md (guide mục 1.2 — user điền trước khi upload)
cat > "$OUT/_release-notes.md" <<'EOF'
# Thông tin phát hành

- Tên theme: <Future v2.0 | Neo | Horizon | ...>
- NukeViet core version: 5.x
- Bootstrap version: 5.3.3
- Font Awesome version: 6.5.1
- Ngày phát hành: YYYY-MM-DD
- Tác giả: ...
- Link repo: github.com/nukeviet/nukeviet/tree/<branch>

# Các thay đổi lớn so với version trước

- Đổi primary color từ #1477cc → #...
- Đổi font body sang ...
- Layout mới: ...
- Component mới: ...
- Component bỏ: ...
EOF

# 7. Folder screenshots/ (guide mục 1.3 — user copy ảnh vào)
mkdir -p "$OUT/screenshots"
cat > "$OUT/screenshots/README.txt" <<'EOF'
Bỏ 5–7 screenshot trang thật vào thư mục này (ground truth visual cho AI):
  - home.png            (trang chủ)
  - category.png        (trang chuyên mục news)
  - article.png         (chi tiết bài)
  - contact.png         (form liên hệ)
  - login.png           (đăng nhập)
  - dark-mode.png       (nếu có dark mode)

Format: PNG hoặc JPG, độ phân giải 1920×... (desktop) hoặc 375×... (mobile).
File này có thể xóa sau khi đã copy ảnh vào.
EOF

# 8. README — context cho claude.ai/design
cat > "$OUT/README.txt" <<'EOF'
NukeViet 5 — Frontend bundle "future" theme
============================================

Stack:
- Sass 1.71.1 (KHÔNG dùng lighten/darken/mix — đã deprecated)
- Bootstrap 5.3.3 (đã include trong node_modules)
- Font Awesome 6.5.1
- Smarty 5 (template engine, không phải Twig/Blade)
- Build CSS: sass + postcss (autoprefixer) + rtlcss

Thư mục:
- prompt-claude-design.md = ⭐ INSTRUCTION cho claude.ai/design (đọc file này để biết phải sinh gì)
- scss/future/           = SCSS source (~30 file) — toàn bộ token + component
- scss/{_functions,_show_pass,_ckeditor5}.scss = util chung
- scss/standalone/       = alert + polyfill core
- themes/future/layout/  = 15 layout TPL (header/footer/block wrapper + 6 layout cột)
- themes/future/modules/ = TPL override cho 4 module: news, contact, users, page
- _release-notes.md      = metadata phát hành (version, changelog)
- screenshots/           = ảnh ground truth của các trang thật

SCSS Entry:
- style.d.scss = Desktop ($enable-responsive: false, min-width = container xxl)
- style.r.scss = Responsive ($enable-responsive: true)
- {news,contact,banners,page,users,comment,myapi}.{d,r}.scss = bundle riêng/module

SCSS Pattern:
- _style_header.scss = scaffold (functions + mixins + variables + variables-dark)
- _style_footer.scss = import 14 partial component, _override LUÔN ở cuối (specificity)
- Tokens: scss/future/_variables.scss, _variables-dark.scss, _root.scss
- Prefix BS namespace: $prefix = "bs-"
- Layout maps: $main-columns, $footer-columns, $main-mobile-columns-order
- Mixin: responsive() / non-responsive() — scss/future/mixins/_responsive.scss

TPL Pattern (Smarty 5):
- Layout: layout.<name>.tpl chọn theo $module_info['layout_funcs'][$op_file]
- Block wrapper: block.<template>.tpl (template = default|no_title|simple)
- Module override: themes/future/modules/<module>/<view>.tpl
- Nếu thiếu, NV5 fallback themes/default/modules/<module>/<view>.tpl (XTemplate cũ)

QUAN TRỌNG — Module news:
- future KHÔNG override news/main.tpl, news/detail.tpl, news/topic.tpl
  (NV5 fallback default theme XTemplate, KHÔNG phải Smarty)
- future CHỈ override block_*.tpl + viewcat_*.tpl của news
- Khi redesign theme MỚI: phải VIẾT Smarty version cho main/detail/topic
  trong themes/<new-theme>/modules/news/ — KHÔNG để fallback (sẽ vỡ Smarty parse)

Convention class CSS:
- .nv-block, .nv-block-default = block wrapper
- .block-heading = title của block (có ::after orange accent 48px)
- .main-{start,content,end} = grid 3-cột
- .text-truncate-{2,3,4} = clamp dòng
- Bootstrap 5.3 native: btn, card, alert, modal, dropdown, offcanvas, collapse
EOF

# 9. Report
echo "✓ Bundle: $OUT ($(du -sh "$OUT" | cut -f1))"
echo "  SCSS: $(find "$OUT/scss" -type f | wc -l) | Layout: $(find "$OUT/themes/future/layout" -type f | wc -l) | Module: $(find "$OUT/themes/future/modules" -type f | wc -l)"
echo
echo "Bước tiếp theo:"
echo "  1. Mở $OUT/_release-notes.md → điền version + changelog"
echo "  2. Copy screenshot vào $OUT/screenshots/"
echo "  3. Nén bundle khi đã đủ (chọn 1):"
echo "       ( cd design && zip -rq design-system.zip design-system/ )"
echo "       powershell -Command \"Compress-Archive -Path 'design\\design-system\\*' -DestinationPath 'design\\design-system.zip' -Force\""
echo "  4. Upload design-system.zip lên claude.ai/design + paste prompt ngắn:"
echo "       \"Đọc prompt-claude-design.md trong bundle, sinh design system theo spec đó.\""
