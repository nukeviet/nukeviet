# Seeder — Generator dữ liệu demo cho NukeViet 5

> Module **generic** để seed dữ liệu mẫu cho **nhiều theme NV5** cùng lúc (news, contact, banners, menu, users, theme config). Dev không cần viết code PHP — **chỉ cần tạo folder `src/data/seeder/<theme>/` với các file JSON**.
>
> **Module gốc KHÔNG bị đụng** — seeder ghi thẳng vào bảng DB qua PDO với schema đã khảo sát.
>
> **Chỉ chạy qua Admin UI** (CLI đã loại bỏ — file [tools/seed.php](tools/seed.php) chỉ còn stub deprecation).

---

## 1. Quick start (cho theme mới)

1. **Cài module seeder** qua Admin → Module → Cài đặt (lần đầu)
2. **Tạo folder** `src/data/seeder/<theme_name>/` với các file JSON manifest (copy từ `src/data/seeder/newsviet/` làm template)
3. **Vào Admin → Seeder → Chạy seed** → chọn theme từ dropdown → "Chạy tất cả"
4. **Cài / activate theme đích** → setblocks tự gắn block

---

## 2. Cấu trúc

### 2.1 Module code (`src/modules/seeder/`)

```
src/modules/seeder/
├── version.php                 # Module manifest (NV5 cài đặt)
├── functions.php               # NV_IS_MOD_SEEDER constant
├── admin.functions.php         # whitelist admin pages
├── admin.menu.php              # submenu
├── action_mysql.php            # CREATE bảng nv5_seeder_log
├── language/{vi,en}.php
├── admin/                      # Admin UI
│   ├── main.php                # Dashboard tổng hợp
│   ├── run.php                 # Form chạy step + dropdown chọn theme
│   └── reset.php               # Form reset (xác nhận YES)
├── tools/
│   ├── seed.php                # ⚠️ Stub deprecation — không còn CLI
│   └── seed_functions.php      # ⭐ Lib hàm seed_* + helper seeder_get_theme/list_themes
└── lib/
    ├── SeedTracker.php         # PSR-4 \Seeder\SeedTracker
    ├── PicsumImageDownloader.php
    └── LoremNewsGenerator.php
```

### 2.2 Data manifest (`src/data/seeder/<theme>/`)

```
src/data/seeder/                 # ⭐ MULTI-THEME — Dev sửa ở đây
├── newsviet/                    # Manifest cho theme NewsViet
│   ├── categories.json
│   ├── topics.json
│   ├── departments.json
│   ├── menus.json
│   ├── banner-positions.json
│   ├── users.json
│   ├── theme-config.json
│   ├── articles.sample.json     # (suffix `.sample` cho file dữ liệu mẫu lớn)
│   └── banners.sample.json
├── shop_theme/                  # Khi build theme khác — copy + sửa
│   ├── categories.json
│   └── ...
└── <theme_n>/                   # Mỗi theme 1 folder, không ghi đè nhau
```

> **Tại sao tách `data/` ra ngoài module?** Để hỗ trợ nhiều theme cùng tồn tại — mỗi theme có content khác nhau. Trước đây `data/` ở trong module → khi build theme thứ 2 phải ghi đè.
>
> Seeder tự list folder qua `seeder_list_themes()` → admin UI có dropdown chọn.

---

## 3. 9 step có sẵn

> Manifest đường dẫn `src/data/seeder/<THEME>/<step>.json` — `<THEME>` là folder dev đã tạo.

| Step | Manifest | Bảng đích | Idempotent theo |
|---|---|---|---|
| `categories` | `<THEME>/categories.json` | `_news_cat` + tạo bảng `_<catid>` LIKE `_rows` | `alias` (UNIQUE) |
| `topics` | `<THEME>/topics.json` | `_news_topics` | `alias` (UNIQUE) |
| `departments` | `<THEME>/departments.json` | `_contact_department` | `alias` (UNIQUE) |
| `menus` | `<THEME>/menus.json` | `_menu` + `_menu_rows` | `title` (UNIQUE) |
| `banner-positions` | `<THEME>/banner-positions.json` | `_banners_plans` | `(title, blang)` |
| `users` | `<THEME>/users.json` | `_users` (hash pass đúng cách) | `username` (UNIQUE) |
| `articles` | `<THEME>/articles.sample.json` | `_news_rows` + `_news_<catid>` + `_news_detail` + ảnh | `(catid, alias)` |
| `banners` | `<THEME>/banners.sample.json` | `_banners_rows` + ảnh | `title` |
| `theme-config` | `<THEME>/theme-config.json` | `themes/<_target_theme>/language/vi.php` (file) | block marker |

---

## 4. Reuse cho theme mới (vd `shop_theme`)

Giả sử bạn build 1 theme mới `shop_theme` (e-commerce). Cần seed:
- 8 chuyên mục sản phẩm (thay 14 chuyên mục báo chí)
- 5 menu (Top, Mega, 3 Footer)
- 4 vị trí banner (Hero, Sidebar, Cart, Footer)
- 50 sản phẩm mẫu
- Theme config riêng (logo, hotline shop)

### Bước 1 — Tạo folder + sửa manifest

Copy folder template từ newsviet rồi sửa các file JSON:

```
src/data/seeder/newsviet  →  src/data/seeder/shop_theme
```

**`src/data/seeder/shop_theme/categories.json`** — đổi 14 cat news thành 8 cat sản phẩm:

```json
{
  "_natural_key": "alias",
  "items": [
    { "title": "Thời trang nam",  "alias": "thoi-trang-nam", "weight": 1, "viewcat": "viewcat_main_left" },
    { "title": "Thời trang nữ",   "alias": "thoi-trang-nu",  "weight": 2 },
    { "title": "Phụ kiện",        "alias": "phu-kien",       "weight": 3 },
    { "title": "Giày dép",        "alias": "giay-dep",       "weight": 4 },
    ...
  ]
}
```

**`src/data/seeder/shop_theme/menus.json`** — đổi cấu trúc menu phù hợp shop:

```json
{
  "items": [
    {
      "title": "ShopTheme — Top Nav",
      "items": [
        { "catalias": "thoi-trang-nam" },
        { "catalias": "thoi-trang-nu" },
        { "title": "Khuyến mãi", "link": "?nv=news&op=khuyen-mai" }
      ]
    },
    ...
  ]
}
```

**`src/data/seeder/shop_theme/banner-positions.json`** — 4 vị trí banner:

```json
{
  "items": [
    { "title": "ShopTheme — Hero Slider 1920x600", "form": "image", "width": 1920, "height": 600, "act": 1, "blang": "vi", "require_image": 1 },
    { "title": "ShopTheme — Sidebar 300x250",     "form": "image", "width": 300,  "height": 250, "act": 1, "blang": "vi", "require_image": 1 },
    ...
  ]
}
```

**`src/data/seeder/shop_theme/articles.sample.json`** — sản phẩm mẫu (vẫn dùng cấu trúc news, chỉ đổi catalias + content):

```json
{
  "items": [
    { "catalias": "thoi-trang-nam", "title": "Áo thun nam basic — chất cotton 100%", "image_seed": "shirt-1", "hometop": 1 },
    { "catalias": "giay-dep",       "title": "Sneaker da bò khâu tay",                "image_seed": "shoe-1",  "hometop": 1 },
    ...
  ]
}
```

**`src/data/seeder/shop_theme/theme-config.json`** — đặc biệt quan trọng: **`_target_theme: "shop_theme"`** (theme đích để ghi `language/vi.php`):

```json
{
  "_target_theme": "shop_theme",
  "logo": {
    "logo_text":   "ShopVN",
    "logo_slogan": "Mua sắm online tin cậy"
  },
  "hotline": { "hn": "1900 1234", "hcm": "1900 5678" },
  "channels": {
    "email":   "support@shop.test",
    "fb":      "https://fb.com/shopvn",
    "rss":     "/rss.xml"
  },
  "hot_keywords": ["Áo thun", "Sneaker", "Giảm giá", "Black Friday"]
}
```

### Bước 2 — Theme config (`themes/shop_theme/blocks/`)

Block của theme đọc `$lang_global['nv_*']` (key giữ prefix `nv_`):

```php
function nv_shop_hotline($block_config) {
    global $lang_global;
    $hn = $lang_global['nv_hotline_hn'] ?? '';
    // ...
}
```

→ Sau khi seed `theme-config`, file `themes/shop_theme/language/vi.php` có block:

```php
// === SEEDER-MARKER ===
$lang_global['nv_logo_text']    = 'ShopVN';
$lang_global['nv_hotline_hn']   = '1900 1234';
// ...
// === END SEEDER ===
```

### Bước 3 — Chạy seed qua Admin UI

```
Admin → Seeder → Chạy seed
  → dropdown "Theme target": chọn "shop_theme"
  → tick các step (hoặc bấm "Chạy tất cả")
  → bấm "Chạy"
```

Output sẽ hiện trong khung `<pre>` ngay dưới form, kèm thời gian thực thi (ms).

### Bước 4 — Cài / activate theme `shop_theme`

NV5 sẽ parse `<setblocks>` của `themes/shop_theme/config.ini` và auto-gắn block.

---

## 5. Convention quan trọng — phải nhớ

### 5.1 ID hardcode trong `<setblocks>`

`<setblocks>` của theme có thể hardcode `catid`, `topicid`, `pid`, `menuid`. **ID = thứ tự manifest** (auto-increment 1, 2, 3...) khi DB sạch.

→ **Workflow đúng**: cài seeder → chạy `Chạy tất cả` → cài theme. ID sẽ deterministic.

→ Nếu DB đã có data trước, ID sẽ lệch. Phải sửa `<setblocks>` config tương ứng hoặc reset DB.

### 5.2 Hash password

[`seed_users()`](tools/seed_functions.php) dùng `$crypt->hash_password()` của NV5, KHÔNG `password_hash()` thuần. Tự động đúng — Dev không cần lo.

### 5.3 URL ảnh news

Convention NV5 (đã verify ở seeder):
- `homeimgfile` lưu **relative từ `uploads/<module_upload>/`** (vd `2026_05/foo.jpg`)
- `homeimgthumb = 2` (file in uploads/) khi seed thẳng từ picsum
- Build URL khi render block: `NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $site_mods['news']['module_upload'] . '/' . $homeimgfile`

→ Block của theme đọc news image phải xét `homeimgthumb` (1/2/3) — xem [theme `newsviet/blocks/global.spotlight.php`](../../themes/newsviet/blocks/global.spotlight.php) làm reference.

### 5.4 Lưu ảnh theo `Y_m`

Articles seed lưu ảnh vào `uploads/news/<Y_m>/` (vd `uploads/news/2026_05/`) thay vì `<catalias>/` để tiện tạo thumb hàng loạt.

### 5.5 Manifest `_*` field — internal config

Các field bắt đầu `_` không phải data, là cấu hình cho seeder:

| Field | Manifest | Mục đích |
|---|---|---|
| `_comment` | tất cả | Ghi chú cho dev đọc, seeder bỏ qua |
| `_natural_key` | tất cả | Đánh dấu key dùng để lookup idempotent |
| `_target_theme` | `theme-config.json` | Theme đích để ghi `language/vi.php` |
| `_default_password` | `users.json` | Password mặc định cho user (sẽ hash) |
| `_image_size` | `articles.sample.json` | Mảng kích thước ảnh `{default:[w,h], hometop:[w,h], ...}` |

---

## 6. Bẫy đã từng gặp

### 6.1 Manifest có suffix `.sample`

`articles` và `banners` có file `articles.sample.json` / `banners.sample.json`. Khi gọi `load_manifest()` phải truyền tên đầy đủ:

```php
load_manifest('articles.sample')   // ✅
load_manifest('articles')          // ❌ → file not found
```

→ Nếu thêm step mới, không bắt buộc dùng `.sample` suffix. Đặt tên file = tên step.

### 6.2 Seeder log table chưa được tạo

Bảng `nv5_seeder_log` được tạo qua `action_mysql.php` khi **cài module qua Admin**. Nếu Dev clone code mà không cài qua Admin → bảng không tồn tại → SeedTracker fail.

→ Luôn cài module qua Admin → Module → Cài đặt module mới.

### 6.3 Step `articles` / `banners` cần internet

Hai step này tải ảnh từ `picsum.photos`. Trong admin UI có badge ⚠️ `internet` cảnh báo. Nếu server không có internet hoặc bị firewall chặn → step này fail (các step khác vẫn OK).

### 6.4 Articles/banners chạy lâu — cẩn thận timeout

90 ảnh có thể mất ~60s. `admin/run.php` đã `set_time_limit(900)` (15 phút). Nếu vẫn timeout → tách ra chạy theo nhóm step nhỏ hơn.

---

## 7. API summary — cho dev đọc code

```php
// Trong seed_functions.php — namespace \Seeder

\Seeder\SeedTracker $tracker = new \Seeder\SeedTracker($db, $db_config['prefix']);

// Step API:
seed_categories($tracker);    // INSERT/UPDATE _news_cat + clone bảng _<catid>
seed_topics($tracker);        // INSERT/UPDATE _news_topics
seed_departments($tracker);   // INSERT/UPDATE _contact_department
seed_menus($tracker);         // INSERT _menu + reset _menu_rows
seed_banner_plans($tracker);  // INSERT/UPDATE _banners_plans
seed_users($tracker);         // INSERT/UPDATE _users (hash password)
seed_articles($tracker);      // Transaction: _news_rows + _news_<catid> + _news_detail + ảnh
seed_banners($tracker);       // INSERT/UPDATE _banners_rows + ảnh
seed_theme_config($tracker);  // Ghi block marker vào themes/<_target_theme>/language/vi.php

// Helper:
seed_run_step('articles', $tracker);       // Chạy 1 step theo tên
seed_run_steps(['cat','topic'], $tracker); // Chạy nhiều step
seed_run_all($tracker);                    // Chạy 9 step theo thứ tự dependencies
seed_reset_all($tracker);                  // Xóa item action='created' (giữ user nhập tay)
seed_clear_cache();                        // Xóa NV5 cache

// Theme target helpers:
seeder_set_theme('newsviet');              // Phải gọi trước seed_*
seeder_get_theme();                        // → string
seeder_get_data_dir();                     // → NV_ROOTDIR/data/seeder/<theme>
seeder_list_themes();                      // → ['newsviet', 'shop_theme', ...]
```

`SeedTracker` API:

```php
$tracker->log($step, $naturalKey, $dbId, $dbTable, $action, $message?);
$tracker->summary();                  // tổng hợp theo step
$tracker->getCreatedItems($step);     // item do seeder tạo (cho reset)
$tracker->clearStep($step);           // xóa log của step
```

---

## 8. Mở rộng — thêm step mới

Vd thêm `seed_products()` cho theme shop với bảng custom `nv5_<lang>_products`:

```php
// Trong tools/seed_functions.php

function seed_products(SeedTracker $tracker): void
{
    global $db, $db_config;

    $manifest = load_manifest('products');  // → data/seeder/<theme>/products.json
    $items    = $manifest['items'] ?? [];
    $tbl      = $db_config['prefix'] . '_' . NV_LANG_DATA . '_products';

    out_info("[seeder] step=products run_id={$tracker->getRunId()}");

    foreach ($items as $row) {
        // Lookup theo natural key
        $sth = $db->prepare("SELECT id FROM {$tbl} WHERE sku = :sku");
        $sth->bindValue(':sku', $row['sku']);
        $sth->execute();
        $id = (int) $sth->fetchColumn();

        try {
            if ($id > 0) {
                // UPDATE
                $tracker->log('products', $row['sku'], $id, $tbl, SeedTracker::ACTION_UPDATED);
            } else {
                // INSERT ... rồi $id = $db->lastInsertId()
                $tracker->log('products', $row['sku'], $id, $tbl, SeedTracker::ACTION_CREATED);
            }
        } catch (Throwable $e) {
            $tracker->log('products', $row['sku'], 0, $tbl, SeedTracker::ACTION_FAILED, $e->getMessage());
        }
    }
}

// Thêm vào seed_run_step():
case 'products': seed_products($tracker); return true;

// Thêm vào seed_run_all() ordered list nếu cần auto chạy
```

→ Sau đó tạo `data/seeder/<theme>/products.json` và admin UI sẽ tự thấy step mới (nếu thêm vào `$availableSteps` trong `admin/run.php`).

---

## 9. Tham khảo

| File | Nội dung |
|---|---|
| [design/README.md](../../../design/README.md) | Hướng dẫn build theme NV5 mới — kinh nghiệm tổng hợp |
| [tools/seed_functions.php](tools/seed_functions.php) | Library hàm seed_* (admin pages include) |
| [admin/run.php](admin/run.php) | Admin UI — form chạy step, dropdown theme target |
| [admin/reset.php](admin/reset.php) | Admin UI — form reset (cần gõ YES) |
| [admin/main.php](admin/main.php) | Admin Dashboard — tổng hợp đã seed |
