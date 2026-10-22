# Vận hành tài liệu docs/manual

## Cấu trúc

```text
docs/manual/
├── .vitepress/config.mts        # Cấu hình site, menu, sidebar, tìm kiếm
├── index.md                     # Trang chủ tiếng Việt
├── guide/                       # Hướng dẫn sử dụng, mỗi phần một thư mục
├── dev/                         # Tài liệu kỹ thuật, mỗi phần một thư mục
├── en/                          # Bản tiếng Anh, cùng cấu trúc với bản tiếng Việt
├── tools/
│   ├── functions.php            # Sinh trang hàm dùng chung
│   ├── hooks.php                # Sinh trang danh sách hook
│   └── lib.php
├── scripts/check-translations.mjs
└── package.json

.github/workflows/docs.yml       # Build và deploy
```

Các file, thư mục sinh ra khi chạy, đã có trong `.gitignore`:

- `dev/reference/functions/` - trang hàm dùng chung
- `dev/hooks/list.md` - trang danh sách hook
- `node_modules/`, `.vitepress/cache/`, `.vitepress/dist/`

## Cài đặt GitHub Pages lần đầu

Chỉ làm một lần, cần quyền admin repo và quản lý DNS.

1. Vào Settings → Secrets and variables → Actions → Variables → Repository variables, tạo biến `DOCS_DOMAIN`, giá trị là tên miền, VD `docs.nukeviet.vn`
2. Tại DNS của tên miền, tạo bản ghi `CNAME` cho `docs` trỏ tới `nukeviet.github.io`
3. Vào Settings → Actions → General → Workflow permissions, đảm bảo workflow được phép ghi (`contents: write`) nếu tổ chức có giới hạn
4. Chạy workflow lần đầu: Actions → Docs → Run workflow, chọn nhánh `nukeviet5.0`
5. Chờ workflow chạy xong, kiểm tra repo đã có nhánh `gh-pages`
6. Vào Settings → Pages:
    - Source: Deploy from a branch
    - Branch: `gh-pages`, thư mục `/ (root)`
    - Custom domain: nhập giá trị giống `DOCS_DOMAIN`
    - Bật Enforce HTTPS khi tùy chọn này sáng lên
7. Vào Settings của tổ chức nukeviet → Pages → Add a domain, xác minh tên miền theo hướng dẫn của GitHub
8. Mở `https://<DOCS_DOMAIN>/`, trang tự chuyển tới `/5.0/`

Không đặt `DOCS_DOMAIN` thì site chạy tại `https://nukeviet.github.io/nukeviet/5.0/`.

## Chạy trên máy

Yêu cầu: Node.js 18 trở lên, PHP 8.2 trở lên có trong PATH.

1. Cài đặt:
    ```bash
    cd docs/manual
    npm install
    ```
2. Xem trước khi đang viết, trang tự tải lại khi sửa file:
    ```bash
    npm run dev
    ```
    Mở `http://localhost:5173/5.0/`
3. Build và xem bản giống trên GitHub Pages:
    ```bash
    npm run build
    npm run preview
    ```
    Mở `http://localhost:4173/5.0/`

`npm run dev` và `npm run build` tự chạy `npm run gen` để sinh trang hàm dùng chung và danh sách hook. Sửa PHPDoc hoặc comment `@hook` trong lúc `npm run dev` đang chạy thì chạy lại `npm run gen` để cập nhật.

## Trang hàm dùng chung

Sinh từ PHPDoc của các hàm khai báo ở cấp ngoài cùng trong các file sau, mỗi nhóm một trang:

| Trang | Nguồn |
|---|---|
| `dev/reference/functions/system.md` | `src/includes/functions.php` |
| `dev/reference/functions/utf8.md` | `src/includes/utf8/` |
| `dev/reference/functions/filesystem.md` | `src/includes/core/filesystem_functions.php` |
| `dev/reference/functions/admin.md` | `src/includes/core/admin_functions.php` |
| `dev/reference/functions/site.md` | `src/includes/core/user_functions.php` |
| `dev/reference/functions/theme.md` | `src/includes/core/theme_functions.php` |

Viết PHPDoc cho hàm:

```php
/**
 * Mô tả hàm, có thể viết nhiều dòng, dùng được Markdown
 *
 * @param string $string Mô tả tham số
 * @return string Mô tả giá trị trả về
 * @since 5.0.00
 */
function nv_example($string)
```

- Hàm có tên bắt đầu bằng `_`, VD `_otherMethodSendmail`, là hàm nội bộ, không đưa vào trang
- Dòng đầu PHPDoc chỉ ghi tên hàm, VD `nv_htmlspecialchars()`, sẽ được bỏ qua khi sinh trang
- Các tag được dùng: `@param`, `@return`, `@since`, `@deprecated`, `@see`, `@throws`
- Link tới một hàm: `/dev/reference/functions/system.md#nv_htmlspecialchars`

Thêm file hoặc nhóm mới: sửa mảng `$groups` trong `tools/functions.php`, thêm trang vào sidebar trong `.vitepress/config.mts` và vào `dev/reference/index.md`.

## Viết mô tả hook

Viết khối comment ngay trên câu lệnh gọi `nv_apply_hook`:

```php
/**
 * @hook get_list_inform Lấy danh sách thông báo của thành viên
 * Dòng mô tả thêm, mỗi dòng là một đoạn
 * @param array $user_info Thành viên đang đăng nhập
 * @param string $filter Bộ lọc
 * @return array Giá trị hàm xử lý cần trả về
 * @since 5.0.00
 */
[$num_items, $items, $count] = nv_apply_hook($module_name, 'get_list_inform', [$user_info, $filter], [null, null, null]);
```

1. Tên sau `@hook` phải trùng tên hook
2. Mỗi phần tử của mảng `$args` có một `@param`, đúng thứ tự
3. `$args` là biến thay vì mảng viết trực tiếp thì tên trong `@param` là key của mảng, VD `@param string $mode` ứng với `$vars['mode']`
4. Hook được gọi ở nhiều chỗ thì viết khối comment ở mọi chỗ gọi
5. Kiểm tra:
    ```bash
    php docs/manual/tools/hooks.php
    ```
    Lệnh in cảnh báo khi thiếu khối comment, sai tên hook hoặc số `@param` không khớp số phần tử `$args`

Link tới một hook: `/dev/hooks/list.md#get_list_inform`. Hook trùng tên ở nhiều module thì anchor có tiền tố module, VD `#news-before_detail_theme`.

## Viết bài

1. Tạo file `.md` trong thư mục phần tương ứng, VD `guide/install/localhost.md`, tên file viết thường, nối bằng `-`
2. Dòng đầu là tiêu đề cấp 1: `# Cài đặt trên localhost`
3. Thêm link bài vào trang mục lục của phần, VD `guide/install/index.md`, xóa dòng tương ứng ở mục Nội dung dự kiến
4. Thêm bài vào sidebar trong `.vitepress/config.mts`, hàm `sidebarVi()`:
    ```ts
    {
        text: 'Phần 2: Cài đặt',
        link: '/guide/install/',
        collapsed: true,
        items: [{ text: 'Cài đặt trên localhost', link: '/guide/install/localhost' }]
    }
    ```
5. Link tới trang khác dùng đường dẫn tương đối kèm `.md`: `[Yêu cầu hệ thống](./requirements.md)`, link tới một mục: `[Yêu cầu](./requirements.md#yeu-cau)`
6. Ảnh đặt trong thư mục con `images/` cạnh bài viết, chèn bằng `![Mô tả](./images/ten-anh.png)`
7. Code dùng khối có tên ngôn ngữ: ` ```php `, ` ```bash `, ` ```html ` (file `.tpl` dùng `html`)
8. Ghi chú, cảnh báo:
    ```md
    ::: tip
    Nội dung gợi ý
    :::

    ::: warning
    Nội dung cảnh báo
    :::
    ```
9. Chạy `npm run dev` để xem kết quả

## Dịch sang tiếng Anh

1. Tạo file cùng đường dẫn trong `en/`, VD `guide/install/localhost.md` tương ứng `en/guide/install/localhost.md`
2. Link trong bản tiếng Anh trỏ tới trang trong `en/`
3. Thêm trang vào hàm `sidebarEn()` trong `.vitepress/config.mts`, đường dẫn bắt đầu bằng `/en/`
4. Chưa dịch thì không thêm vào `sidebarEn()`
5. Kiểm tra:
    ```bash
    npm run check-translations
    ```
    Lệnh liệt kê trang chưa có bản tiếng Anh và trang tiếng Việt được commit sau bản tiếng Anh. Trang sinh tự động được bỏ qua

## Trước khi gửi PR

1. Trong `docs/manual` chạy `npm run build`, sửa hết lỗi nếu có (link hỏng sẽ làm build lỗi)
2. Chạy `npm run check-translations`
3. Sửa hoặc thêm hook thì chạy `php docs/manual/tools/hooks.php`, sửa hết cảnh báo
4. Đánh dấu các ô kiểm tra trong mô tả PR

## Deploy

- Tự động: khi PR có thay đổi trong `docs/manual/`, `src/` hoặc `.github/workflows/docs.yml` được merge vào nhánh `nukeviet5.*`
- Chạy tay: Actions → Docs → Run workflow, chọn nhánh `nukeviet5.x` cần deploy
- Với PR: workflow chỉ build và kiểm tra, không deploy
- Xem kết quả: tab Actions, workflow Docs. Cảnh báo bản dịch và hook hiện ở phần Annotations

## Thêm phiên bản mới

VD thêm 5.1 từ nhánh `nukeviet5.1`:

1. Trên nhánh `nukeviet5.1`, sửa `.vitepress/config.mts`:
    - Giá trị mặc định của `base` thành `'/5.1/'`
    - Giá trị mặc định của `branch` thành `'nukeviet5.1'`
2. Sửa giá trị mặc định nhánh trong hàm `githubLink()` của `tools/lib.php` thành `'nukeviet5.1'`
3. Sửa lời giới thiệu trên `index.md`, `en/index.md` nếu có ghi số phiên bản
4. Push nhánh, workflow tự deploy vào `5.1/` trên `gh-pages`, thư mục `5.0/` giữ nguyên
5. Trang gốc tự chuyển tới phiên bản có số lớn nhất

Với phiên bản 6.x: thêm `'nukeviet6.*'` vào `on.push.branches` trong `.github/workflows/docs.yml`.

## Gỡ một phiên bản

1. Checkout nhánh `gh-pages` vào thư mục riêng:
    ```bash
    git fetch origin gh-pages:gh-pages
    git worktree add ../nukeviet-gh-pages gh-pages
    cd ../nukeviet-gh-pages
    ```
2. Xóa thư mục phiên bản, VD `git rm -r 5.0`
3. Nếu phiên bản bị xóa là bản mới nhất, sửa `index.html` và chép `404.html` từ phiên bản mới nhất còn lại
4. Commit và push nhánh `gh-pages`
5. Xóa worktree: `git worktree remove ../nukeviet-gh-pages`

## Cập nhật công cụ

- VitePress: sửa phiên bản trong `docs/manual/package.json`, chạy `npm install` để cập nhật `package-lock.json`, chạy `npm run build` kiểm tra
- GitHub Actions: sửa số phiên bản `actions/*@vN` trong `.github/workflows/docs.yml`

## Xử lý sự cố

| Hiện tượng | Cách xử lý |
|---|---|
| Build lỗi `Found dead link` | Sửa link trong file được báo, chạy lại `npm run build` |
| `npm run dev` báo không tìm thấy `php` | Thêm thư mục chứa `php` vào PATH |
| Bước "Xác định phiên bản" lỗi | Workflow chỉ chạy trên nhánh tên dạng `nukevietX.Y` |
| Bước "Đẩy lên gh-pages" lỗi 403 | Kiểm tra Workflow permissions ở mục Cài đặt GitHub Pages lần đầu |
| Tên miền báo 404 | Kiểm tra Settings → Pages chọn nhánh `gh-pages`, file `CNAME` có trên `gh-pages`, bản ghi DNS |
| Trang hàm thiếu hàm | Kiểm tra file chứa hàm có trong `$groups` của `tools/functions.php`, hàm khai báo ở cấp ngoài cùng của file |
| Hook hiện "Chưa có mô tả" | Thêm khối comment `@hook` ngay trên câu lệnh gọi, xem mục Viết mô tả hook |
| Tìm kiếm không ra bài mới | Chạy lại `npm run build`, chỉ mục tìm kiếm sinh khi build |
