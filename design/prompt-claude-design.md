# Spec sinh Design System cho NukeViet 5 (claude.ai/design)

Tôi vừa upload bundle "design-system" của NukeViet 5 (đọc README.txt + _release-notes.md
trong bundle để biết version theme + changelog). Cần bạn sinh bộ Design System HTML đầy đủ
theo chuẩn dưới — output sau này sẽ chuyển giao cho Claude Code build thành theme NV5 thực tế,
nên CONVENTION dưới phải tuân thủ tuyệt đối.

## 1. CẤU TRÚC OUTPUT

```text
output/
├── design-system.html              # Single-page docs: tokens + components + nav
├── nv-routes.md                    # ⭐ MAPPING file (xem mục 4)
├── partials/
│   ├── site-header.html            # Markup <header> độc lập
│   ├── site-footer.html            # Markup <footer> độc lập
│   ├── site-nav.html               # Main nav với data-bs-* (Bootstrap)
│   ├── nv-theme.css                # CSS shared cho mọi mockup
│   ├── include.js                  # 8 dòng JS load partial qua data-include
│   └── blocks/
│       ├── ticker.html
│       ├── news-cat.html
│       ├── tophits.html
│       ├── ...                     # Mỗi NV block 1 file
└── mockups/
    ├── home.html
    ├── category.html
    ├── article.html
    ├── contact.html
    └── login.html
```

## 2. SHARED COMPONENTS — KHÔNG ĐƯỢC INLINE COPY

Mọi mockup PHẢI dùng chung header/footer/main-nav qua pattern data-include:

partials/include.js (tạo file này):
```js
document.querySelectorAll('[data-include]').forEach(async el => {
  const html = await fetch(el.dataset.include).then(r => r.text());
  el.outerHTML = html;
  // Re-run cho partial mới insert (vd nav include trong header)
  el.querySelectorAll?.('[data-include]').forEach(...);
});

// Active nav item theo body[data-nav]
const activeNav = document.body.dataset.nav;
if (activeNav) {
  document.querySelector(`.site-nav [data-nav-key="${activeNav}"]`)
    ?.classList.add('active');
}
```

Mỗi mockup theo template:
```html
<!DOCTYPE html>
<html lang="vi" data-bs-theme="light">
<head>
  <meta charset="utf-8">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.5.1/css/all.min.css">
  <link rel="stylesheet" href="../partials/nv-theme.css">
  <title>...</title>
</head>
<body data-nv-route="news/viewcat" data-nv-layout="content" data-nav="news">
  <div data-include="../partials/site-header.html"></div>
  <div data-include="../partials/site-nav.html"></div>

  <main class="container">
    <!-- Nội dung mockup -->
  </main>

  <div data-include="../partials/site-footer.html"></div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="../partials/include.js"></script>
</body>
</html>
```

KHÔNG inline copy markup header/footer/nav vào mockup.
KHÔNG dùng ```<iframe>```. KHÔNG dùng build tool (webpack/vite).

## 3. CONVENTION DATA-ATTRIBUTE BẮT BUỘC

Mỗi NV block trong mockup PHẢI có data-attribute để Claude Code extract:

```html
<section class="nv-block nv-block-default"
         data-nv-block="ticker"            ← tên file: global.ticker.{php,tpl,json}
         data-nv-position="[TICKER]"       ← position trong layout TPL
         data-nv-funcs="all"               ← "all" | "news:main" | "news:main,contact:main"
         data-nv-source="theme">           ← "theme" (tự viết) | "news"|"banners"|"menu" (reuse)
    <h3 class="block-heading">Tin nóng</h3>
    <div class="block-body">...</div>
</section>
```

<body> của mỗi mockup:
- data-nv-route="news/viewcat"      → ?nv=news&op=viewcat
- data-nv-layout="content"          → khớp layout.<name>.tpl
- data-nav="news"                   → active nav item

## 4. nv-routes.md — BRIDGE FILE (BẮT BUỘC)

Cuối cùng tạo nv-routes.md chứa MAPPING — đây là contract để Claude Code biết
chính xác convert HTML nào sang TPL nào trong NV5:

# Hand-off mapping cho Claude Code

## Mockup → NV5 route + TPL target
| Mockup | NV route | Layout | TPL target | Ghi chú |
|---|---|---|---|---|
| home.html | (homepage) | layout.ssbar-content-esbar | (no override - block-driven) | Render qua setblocks |
| category.html | ?nv=news&op=<cat> | layout.content | modules/news/viewcat_main_left.tpl | Future không có — VIẾT MỚI Smarty |
| article.html | ?nv=news&op=<cat>/<alias> | layout.content | modules/news/detail.tpl | Future không có — VIẾT MỚI Smarty |
| contact.html | ?nv=contact | layout.content | modules/contact/main.tpl | Có thể kế thừa future |
| login.html | ?nv=users&op=login | layout.simple | modules/users/login.tpl | Có thể kế thừa future |

## Block markup → NV5 block file

> **Yêu cầu cấu trúc Block:** Cần thiết kế cấu trúc HTML và bóc tách tối đa các phần dữ liệu có thể thay đổi (ví dụ: text, link, hình ảnh, icon, hotline, danh sách mạng xã hội, các text nhãn...). Mục tiêu là định hướng để sau này lập trình viên có thể dễ dàng chuyển các dữ liệu này thành các block. Từ đó cho phép quản trị viên thay đổi trực tiếp qua giao diện admin thay vì phải can thiệp sửa code HTML/TPL.

| partials/blocks/ | NV5 block | Module gốc | Position | Funcs |
|---|---|---|---|---|
| ticker.html | themes/<theme>/blocks/global.ticker.{php,tpl,json} | theme | [TICKER] | all |
| hotline.html | themes/<theme>/blocks/global.hotline.{php,tpl,json} | theme | [HOTLINE] | all |
| news-cat.html | (REUSE) news/global.block_news_cat.php | news | [HERO] | news:main |
| tophits.html | (REUSE) news/global.block_tophits.php | news | [RAIL_TOP] | news:main |

## Components mới (chưa có trong scss/future/)
- .nv-hot-ticker      — banner đỏ marquee (nv-theme.css line ___)
- .feature-hero       — hero card 16:9 với scrim (line ___)
- .news-bullets       — list dấu ▪ đỏ (line ___)
- ...                 # liệt kê đầy đủ

## 5. TOKEN EXPORT — CUỐI design-system.html

Đặt cuối <body> để Claude Code parse JSON tự động sang SCSS. Cấu trúc khớp
SCSS variable name của future để Claude Code map 1-1 sang scss/<theme>/_variables.scss:

<script type="application/json" id="nv-tokens">
{
  "_note": "Đọc giá trị THẬT từ scss/future/_variables.scss trong bundle. Theme mới có thể đè (giữ structure key) hoặc thêm token mới ở 'theme-overrides'. KHÔNG bịa giá trị.",

  "bootstrap-overrides": {
    "$gray":            "#22313C",
    "$blue":            "#1477cc",
    "$red":             "#CF4646",
    "$orange":          "#f7651e",
    "$yellow":          "#e0a800",
    "$green":           "#508250",
    "$cyan":            "#5a9ab2",
    "$body-bg":         "$white",
    "$body-color":      "$gray-700",
    "$prefix":          "bs-",
    "$enable-shadows":  true
  },

  "typography": {
    "$font-size-base":  "1rem",
    "$h1-font-size":    "1.625rem",
    "$h2-font-size":    "1.5rem",
    "$h3-font-size":    "1.375rem",
    "$h4-font-size":    "1.25rem",
    "$h5-font-size":    "1.125rem",
    "$h6-font-size":    "1rem"
  },

  "radius": {
    "$border-radius":     ".25rem",
    "$border-radius-sm":  ".1875rem",
    "$border-radius-lg":  ".375rem",
    "$border-radius-xl":  ".5rem",
    "$border-radius-xxl": ".625rem"
  },

  "spacing": {
    "$block-spacer":       "1.5rem",
    "$grid-gutter-width":  "1rem"
  },

  "layout": {
    "$theme-layout-breakpoint": "lg",
    "$main-columns":            { "start": "22%", "content": "56%", "end": "22%" },
    "$main-columns-lg":         { "start": "30%", "content": "70%", "end": "30%" },
    "$footer-columns":          { "start": "40%", "center": "35%", "end": "25%" },
    "$header-height":           "2.4375rem",
    "$main-nav-height":         "3.125rem",
    "$container-max-widths":    { "sm": "540px", "md": "705px", "lg": "940px", "xl": "1094px", "xxl": "1168px" }
  },

  "theme-overrides": {
    "_note": "Token mới (chưa có trong future) hoặc giá trị override theo design theme mới. Vd nếu primary = đỏ thì đè $blue hoặc thêm $primary mới.",
    "$primary": "<HEX nếu override>",
    "$nv-ticker-bg": "<HEX nếu thiết kế có ticker>",
    "...": "..."
  }
}
</script>

Quy tắc:
- KEY phải dùng đúng tên SCSS variable của future (vd `$h1-font-size`, KHÔNG `h1`)
- Map (vd $main-columns) giữ dạng nested object, KHÔNG flatten thành chuỗi
- Color reference biến khác (vd `$body-color: $gray-700`) → giữ string `"$gray-700"`,
  KHÔNG resolve sang hex (Claude Code sẽ giữ nguyên reference khi sinh SCSS)
- KHÔNG bịa giá trị — chỉ ghi giá trị đã đọc thực tế từ scss/future/_variables.scss
  HOẶC override theo _release-notes.md/screenshots

## 6. RÀNG BUỘC KỸ THUẬT (đọc package.json + scss/future/ trong bundle)

- Sass 1.71.1 → CSS phải dùng được với color.adjust(), KHÔNG lighten()/darken()/mix()
- Bootstrap 5.3.3 native (btn, card, modal, dropdown, offcanvas, collapse) — KHÔNG tự viết lại
- Font Awesome 6.5.1 (qua CDN trong mỗi mockup)
- Smarty 5 cho TPL (Claude Code sẽ convert sau)
- Class CSS prefix `.nv-*` cho NukeViet, KHÔNG tạo namespace lạ
- Layout 3 cột tỉ lệ 22/56/22 (khớp $main-columns trong scss/future/_variables.scss)
- Heading block: border-bottom 2px + ::after accent (đọc nv-theme.css để biết màu)
- Dark mode: dùng [data-bs-theme="dark"] (Bootstrap 5.3 native), KHÔNG class riêng
- RTL: CSS phải logical (margin-inline-start thay margin-left khi có thể)
- Tất cả text tiếng Việt
- Heading scale + token THẬT: đọc bundle, KHÔNG bịa

## 7. YÊU CẦU NÂNG CẤP UI/UX

Nâng cấp giao diện hiện tại:
- Thêm ảnh minh họa thay vì để trống (dùng ảnh placeholder đẹp) để giao diện sinh động hơn.
- Thêm hover effect cho buttons và cards (ví dụ: mượt, scale nhẹ, đổ bóng).
- Thêm shadow và border-radius nhất quán giữa các khối nội dung.

Thêm dropdown menu cho navigation:
- Ví dụ: Hover vào "Sản phẩm" → thả menu xuống gồm: [Danh mục A, Danh mục B, Danh mục C].
- Ví dụ: Hover vào "Dịch vụ" → thả menu gồm: [Tư vấn, Triển khai, Hỗ trợ].
- Animation: fade + slide down mượt (sử dụng CSS transition).
- Highlight item đang active một cách nổi bật.
- Mobile: Không hiển thị toàn bộ menu, click để toggle (accordion style, tận dụng Bootstrap).

## 8. CẢNH BÁO MODULE NEWS

scss/future/ KHÔNG override news/main.tpl, news/detail.tpl, news/topic.tpl
(NV5 fallback themes/default/modules/news/ — XTemplate cũ, KHÔNG phải Smarty).

→ Mockup category.html + article.html PHẢI thiết kế MỚI hoàn toàn.
→ nv-routes.md ghi rõ "VIẾT MỚI Smarty" cho 2 file này.

## 9. OUTPUT FORMAT

Mỗi file 1 message, bắt đầu với đường dẫn `output/<path>` rõ ràng.
Theo thứ tự sinh:
1. partials/include.js + nv-theme.css (foundation trước)
2. partials/site-{header,footer,nav}.html
3. partials/blocks/*.html (mỗi block 1 file)
4. mockups/*.html (5 file)
5. design-system.html (cuối, dài nhất, có nv-tokens JSON)
6. nv-routes.md (cuối cùng, mapping toàn bộ)

Sau mỗi file, kèm 2-3 câu giải thích token/component nào được chọn và lý do.
