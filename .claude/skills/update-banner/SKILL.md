---
name: update-banner
description: "Cập nhật banner bản quyền (năm @copyright, @version, tiêu đề) trong toàn bộ mã nguồn NukeViet 5 bằng tools/update-banner.php, sau đó tự kiểm tra lại kết quả"
argument-hint: "[năm, mặc định năm hiện tại]"
disable-model-invocation: false
allowed-tools: Bash, Read, Grep
---

# Skill: Cập nhật banner bản quyền NukeViet

Chạy `tools/update-banner.php` để đồng bộ banner bản quyền của mã nguồn, rồi tự kiểm tra để chắc chắn chỉ dòng banner bị thay đổi.

Tool chỉ sửa giá trị trong dòng của các khối comment (`/**` hoặc `/*!`, bắt đầu ở đầu dòng) có chứa `VINADES` và `@copyright`:

- Năm trong `@copyright (C) 2009-YYYY VINADES...`
- Giá trị `@version` (hằng `BANNER_VERSION` trong tool, hiện là `5.x`)
- Tiêu đề `NukeViet Content Management System` (chuẩn hóa chữ hoa, thường)
- Khối banner kiểu NukeViet 4 (`@project`, `@createdate`) được thay bằng banner chuẩn

Phạm vi: file git đang theo dõi trong `docs`, `scss`, `src`, `tests`, `tools`, đuôi `php`, `js`, `scss`, `css`, `tpl`, `md`, `map`. Bỏ qua `src/data/cache`, `src/data/tmp` và các file `assume-unchanged`, `skip-worktree` (file cấu hình local của Dev). Trước khi ghi, tool tự kiểm chứng từng file: số dòng không đổi, ký tự xuống dòng giữ nguyên, chỉ dòng banner thay đổi. File `.map` phải còn là JSON hợp lệ và chỉ `sourcesContent` thay đổi.

Năm: lấy từ đối số của skill, không có thì dùng năm hiện tại. Dưới đây gọi là `YEAR`.

## Quy trình

Chạy tất cả lệnh tại thư mục gốc repo, bằng Bash.

### Bước 1: Kiểm tra cây thư mục

```bash
git status --short
```

Nếu có thay đổi chưa commit: báo Dev danh sách file và hỏi có tiếp tục không. Lý do: kết quả kiểm tra ở bước 4 dựa trên `git diff`, thay đổi có sẵn sẽ lẫn vào. Không tự stash, reset hay checkout.

### Bước 2: Chạy thử

```bash
php tools/update-banner.php --dry-run --year=YEAR
```

Tóm tắt cho Dev: số file, số khối banner theo đuôi file. Nếu có các mục sau thì dừng lại, trình bày và chờ Dev quyết định trước khi ghi:

- "Không qua kiểm chứng, không ghi"
- "Thay banner NukeViet 4 bằng banner chuẩn"
- "Có khối VINADES + @copyright không đúng mẫu"

Nếu số file là 0: chạy luôn bước 4a để xác nhận rồi báo không có gì cần cập nhật.

### Bước 3: Ghi

```bash
php tools/update-banner.php --year=YEAR
```

Exit code khác 0 nghĩa là có file không qua kiểm chứng: báo Dev, không tự sửa tay.

### Bước 4: Tự kiểm tra

a. Không còn banner chưa chuẩn (phải in 0 file và exit 0):

```bash
php tools/update-banner.php --check --year=YEAR; echo "exit=$?"
```

b. Trong `git diff`, dòng thêm và dòng xóa chỉ được là dòng banner (lệnh phải không in ra gì):

```bash
git diff -U0 | grep '^[-+]' | grep -v '^\(---\|+++\)' | grep -vi '@copyright (C) [0-9-]* VINADES\|@version \|NukeViet Content Management System'
```

Dùng `grep -vi` vì dòng tiêu đề cũ có thể viết `NUKEVIET` in hoa.

Riêng file có banner NukeViet 4 được thay ở bước 3 thì được phép có thêm dòng khác, xem diff của từng file đó.

c. Số dòng thêm bằng số dòng xóa ở từng file (lệnh phải không in ra gì, trừ file banner NukeViet 4):

```bash
git diff --numstat | awk '$1 != $2'
```

d. Cú pháp các file đã đổi (các lệnh phải không in ra lỗi):

```bash
git diff --name-only -- '*.php' | xargs -r -P 8 -n 20 php -l | grep -v '^No syntax errors'
git diff --name-only -- '*.js' | xargs -r -n 1 node --check
git diff --name-only -- '*.map' | xargs -r -I{} php -r 'json_decode(file_get_contents("{}")); if (json_last_error()) echo "JSON lỗi: {}\n";'
```

### Bước 5: Báo cáo

Báo cho Dev bằng tiếng Việt:

- Năm, số file và số khối banner đã cập nhật theo đuôi file
- Kết quả từng mục kiểm tra ở bước 4 (đạt hoặc lỗi, kèm output nếu lỗi)
- Danh sách file `assume-unchanged`, `skip-worktree` đã bỏ qua. Các file cấu hình trong `src/data/config` do hệ thống tự sinh header bằng hằng `NV_FILEHEAD` (năm lấy theo `gmdate('Y')`) nên không cần sửa tay
- Các mục cần Dev xem lại (banner NukeViet 4, khối không đúng mẫu) nếu có

Không commit, trừ khi Dev yêu cầu.

## Ghi chú

- CSS biên dịch và file `.map` được sửa trực tiếp cùng file SCSS nguồn. Thay đổi chỉ nằm trong comment banner nên kết quả giống hệt khi build lại bằng npm, không cần chạy `npm run site-css`, `admin-css`, `core-css`.
- Lên phiên bản lớn mới (ví dụ 6.x): sửa hằng `BANNER_VERSION` trong `tools/update-banner.php` trước khi chạy skill.
- Chuỗi sinh header trong code (`NV_FILEHEAD` ở `src/includes/constants.php`, `IP_FILEHEAD` ở `tools/GeoLite2/src/functions.php`) không phải comment nên tool không đụng tới.
