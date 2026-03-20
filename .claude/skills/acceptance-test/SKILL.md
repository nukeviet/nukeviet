---
name: acceptance-test
description: Chạy Acceptance Test NukeViet 5 bằng Codeception + Selenium. Dùng khi user muốn test UI trên trình duyệt, chạy test file cụ thể, chạy theo group, hoặc toàn bộ bộ test.
argument-hint: tên-file|prefix-số|group|all
allowed-tools: Bash
---

Chạy Acceptance Test NukeViet 5.

**Tham số:** `$ARGUMENTS`

## Quy tắc xác định lệnh chạy

Dựa vào `$ARGUMENTS` để chọn lệnh phù hợp (theo thứ tự ưu tiên):

| Trường hợp | Ví dụ `$ARGUMENTS` | Hành động |
|---|---|---|
| Không có tham số hoặc `all` | _(trống)_ / `all` | `php vendor/bin/codecept run Acceptance` |
| Đường dẫn đầy đủ (chứa `/`) | `tests/Acceptance/009_UsersFieldsCest.php` | Dùng trực tiếp |
| Tên file (chứa `Cest`) | `009_UsersFieldsCest` | `php vendor/bin/codecept run Acceptance tests/Acceptance/009_UsersFieldsCest.php` |
| **Toàn chữ số** | `009` / `9` | Quét `tests/Acceptance/` tìm file có prefix khớp (xem bên dưới) |
| Group (còn lại) | `users-fields` / `users` / `install` | `php vendor/bin/codecept run Acceptance -g users-fields` |

### Xử lý tham số toàn chữ số

Khi `$ARGUMENTS` chỉ gồm chữ số (VD: `009`, `9`, `12`):

1. Dùng Bash liệt kê file trong `tests/Acceptance/` có tên bắt đầu bằng số đó (cả dạng `009_*` lẫn dạng `9_*` nếu input là `9`):
   ```bash
   ls tests/Acceptance/ | grep -E "^0*${ARGUMENTS}[^0-9]"
   ```
2. **Tìm thấy đúng 1 file** → chạy file đó luôn.
3. **Tìm thấy nhiều file** → liệt kê cho user và hỏi chọn file nào, không tự chạy.
4. **Không tìm thấy file nào** → thông báo không có file phù hợp và dừng.

## Các bước thực hiện

### 0. Xác nhận trước khi chạy

Trước khi thực hiện bất kỳ hành động nào, hiển thị cho user:

- Lệnh sẽ chạy (hoặc tên file/group đã xác định)
- Cảnh báo nếu là `all` hoặc group lớn: DB thật, không rollback

Sau đó hỏi: **"Xác nhận chạy? (OK để tiếp tục)"**

Chỉ tiếp tục khi user trả lời **"OK"** (hoặc tương đương: "ok", "yes", "có", "y"). Nếu user từ chối hoặc không trả lời → dừng.

---

### 1. Kiểm tra Selenium

```bash
curl -s http://localhost:4444/status 2>/dev/null | grep -q '"ready"' && echo "READY" || echo "NOT_READY"
```

- Nếu **READY** → bỏ qua bước 2.
- Nếu **NOT_READY** → chạy bước 2.

### 2. Khởi động Selenium (nếu chưa chạy)

```bash
selenium-standalone start > /tmp/selenium.log 2>&1 &
```

Sau đó chờ tối đa 30 giây cho đến khi sẵn sàng:

```bash
for i in $(seq 1 15); do
  curl -s http://localhost:4444/status | grep -q '"ready"' && echo "Selenium ready" && break
  echo "Waiting... ($i/15)"
  sleep 2
done
```

Nếu sau 30 giây vẫn chưa ready → báo lỗi và dừng.

### 3. Dọn dẹp error log trước khi chạy

Xóa toàn bộ file `.log` trong `src/data/logs/error_logs/` và các thư mục con để đảm bảo sau test chỉ có log do lần chạy này tạo ra:

```bash
find src/data/logs/error_logs -name "*.log" -type f -delete
```

### 4. Chạy test

Xây dựng lệnh từ quy tắc ở trên rồi chạy:

```bash
php vendor/bin/codecept run Acceptance <target> --steps 2>&1
```

Luôn thêm `--steps` để hiện chi tiết từng bước.

### 5. Hiển thị kết quả

Sau khi test chạy xong:

**5.1. Tóm tắt Codeception**

- Tổng test / passed / failed / errors
- Thời gian chạy
- Nếu có lỗi: liệt kê tên test thất bại + lý do ngắn gọn (từ output)

**5.2. Kiểm tra error log PHP**

```bash
find src/data/logs/error_logs -name "*.log" -type f
```

- Nếu **không có file nào** → ✅ Không có PHP error
- Nếu **có file** → ❌ Phát hiện PHP error, liệt kê tên file và đọc nội dung để tóm tắt lỗi

**5.3. Kết luận tổng thể**

Test được coi là **PASS hoàn toàn** chỉ khi **cả hai** điều kiện đều đạt:
1. Codeception: tất cả test xanh
2. Error log: không có file `.log` nào trong `src/data/logs/error_logs/`

Nếu Codeception pass nhưng có error log → vẫn báo **FAIL** (có lỗi PHP ngầm).

### 6. Phân tích và xử lý lỗi (nếu có)

Nếu có lỗi (Codeception thất bại **hoặc** có file `.log` trong error_logs), hỏi user: **"Có muốn phân tích lỗi và xử lý không? (OK để tiếp tục)"**

Nếu user xác nhận, thực hiện các bước sau:

**6.1. Thu thập thông tin lỗi**

- Đọc toàn bộ output từ lần chạy vừa rồi
- Quét `tests/_output/` tìm các file liên quan đến lỗi:
  - `*.fail.html` — snapshot HTML tại thời điểm thất bại
  - `*.png` / `*.jpg` — screenshot (nếu có)
  - `codeception.log` — log chi tiết
  ```bash
  ls -lt tests/_output/ | head -20
  ```
- Đọc nội dung các file lỗi mới nhất để lấy thêm context
- Nếu có file `.log` trong `src/data/logs/error_logs/`, đọc nội dung từng file để lấy stack trace PHP

**6.2. Phân tích nguyên nhân**

Dựa trên thông tin thu thập, xác định loại lỗi:

| Loại lỗi | Dấu hiệu | Hướng xử lý |
|---|---|---|
| Element not found | `ElementNotFoundException`, `waitForElement timeout` | Selector sai, DOM thay đổi, trang chưa load xong |
| Click bị chặn | `ElementClickInterceptedException` | Sticky navbar — dùng `executeJS` click |
| Connection error | `ConnectionException`, `curl: 7` | Selenium chưa sẵn sàng, WebDriver crash |
| Assertion failed | `Failed asserting that... contains` | Logic test sai, dữ liệu DB thay đổi, text UI thay đổi |
| AJAX timeout | `waitForElement` timeout sau submit | Response chậm, endpoint lỗi, redirect sai |
| DB error | `grabFromDatabase` / `seeInDatabase` fail | Schema thay đổi, dữ liệu không tồn tại |

**6.3. Đề xuất fix**

- Trình bày rõ nguyên nhân gốc rễ
- Đề xuất cụ thể: thay đổi selector, thêm wait, sửa assertion, v.v.
- Nếu cần sửa code test → hỏi xác nhận trước khi chỉnh file
- Nếu lỗi do code nguồn NukeViet (không phải test) → chỉ ra file/function cần kiểm tra, kết hợp skill `security-admin` hoặc đọc source để phân tích sâu hơn nếu cần

### Lưu ý quan trọng

- Không dừng Selenium sau khi test xong (giữ lại để chạy lần sau nhanh hơn).
- Nếu Selenium đang chạy nhưng test báo `ConnectionException`, thử restart:
  ```bash
  pkill -f selenium-standalone; sleep 2
  selenium-standalone start > /tmp/selenium.log 2>&1 &
  ```
- DB test dùng database thật (tên DB lấy từ `DB_NAME` trong `.env`), không có rollback — các thay đổi dữ liệu được giữ lại.
