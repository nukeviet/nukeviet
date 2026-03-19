# Hướng Dẫn Bảo Mật NukeViet 5.x

## Quy trình audit bảo mật

**Kết hợp 2 bước để có kết quả tốt nhất:**
```bash
# Bước 1 — Code Security audit 1 mục tiêu (Lập list lỗi tiềm ẩn)
/security-audit news        # Module frontend
/security-audit authors     # Module hệ thống (admin)
/security-audit src/modules/news/blocks/global.block_news.php # File cụ thể

# Bước 2 — Rà soát thủ công theo checklist bên dưới
```

---

## Quét bảo mật tự động

> Để chạy quét tự động, dùng lệnh `/security-audit` — xem chi tiết quy trình tại `.claude/skills/security-audit/SKILL.md`.

---

## Lỗi phổ biến và cách fix

### Input — PHẢI qua $nv_Request
> **Tham khảo mẫu chống XSS/SQLi qua Request:** `docs/knowledge/examples/security/PatternInput.php`

### SQL — PDO prepared statement cho user input
> **Tham khảo mẫu SQL Prepared Statement:** `docs/knowledge/examples/security/PatternSQL.php`

> Hằng hệ thống (`NV_CURRENTTIME`, `$admin_info['admin_id']`...) và số nguyên đã ép kiểu `(int)` nối thẳng vào SQL là an toàn — không cần prepare.

### CSRF — Kiểm tra token trước khi xử lý POST

> **Tham khảo mẫu verify CSRF Token:** `docs/knowledge/examples/security/PatternCSRF.php`

> [!IMPORTANT]
> Luôn dùng `csrf_check($csrf, $csrf_key)` để so sánh token CSRF.
> Quy trình chuẩn:
> 1. `$csrf_key` đã được tạo mức độ hệ thống
> 2. Tạo token: `$csrf_create = csrf_create($csrf_key);` Nếu `$csrf_create` chỉ dùng 1 lần (gán vào template), KHÔNG cần tạo biến phụ
> 3. Kiểm tra: `if (csrf_check($nv_Request->get_string('checkss', 'post'), $csrf_key))`

### Các lỗi bảo mật khác (XSS, Path Traversal, Open Redirect, Upload, Object Injection)
> **Tham khảo code mẫu phòng chống các lỗi còn lại:** `docs/knowledge/examples/security/PatternMisc.php`

---

## Bảng tra nhanh — hàm bảo mật

- `$nv_Request`: Bắt buộc dùng để lấy input (chi tiết các method `get_int`, `get_title`, v.v xem thêm tại `docs/knowledge/module.md`).
- `nv_htmlspecialchars()`: Escape HTML output, chống XSS.
- `$db->prepare()` + `bindParam()`: Tham số đầu vào chuỗi SQL (chi tiết tại `docs/knowledge/mysql.md`).
- `$db->dblikeescape($value)`: Escape ký tự đặc biệt trong câu lệnh LIKE.
- `csrf_create` / `csrf_check`: Token và các hàm kiểm tra chống CSRF.
- `nv_is_file()`: Kiểm tra sự tồn tại của file an toàn, chống Path Traversal.
- `nv_redirect_encrypt()` / `nv_redirect_decrypt()`: Cấu trúc redirect an toàn.
- `nv_check_valid_email()`: Hàm hệ thống dùng để validate email.

> **Lưu ý:** Các kiến thức chung về Code Convention (PSR-12), tối ưu hiệu năng (Cache, N+1 Queries), `$db_slave`, cấu trúc file/module, và chi tiết `$nv_Request` đã được chuẩn hóa tại `docs/knowledge/module.md` và `docs/knowledge/mysql.md`. Vui lòng tham khảo các file tương ứng trong quá trình review/code.

---

## Mức độ báo cáo khi review (Bắt buộc tuân thủ)

Ngoài đánh giá bảo mật, AI bắt buộc phải ghi nhận và gợi ý sửa lại các điểm mã nguồn (code) chưa tốt. Vui lòng chia báo cáo rà soát thành 3 chuyên mục:

- 🔴 **LỖI BẢO MẬT NGHIÊM TRỌNG (CHẶN MERGE)** — SQLi, XSS rõ ràng, thiếu CSRF token, thiếu kiểm tra phân quyền, `unserialize` không giới hạn class, vòng lặp chứa câu truy vấn ác ý.
- 🟡 **CÁC ĐIỂM CODE CHƯA TỐT (CODE SMELLS)** — Lỗi Logic, chưa chuẩn Convention (PSR-12), truy vấn SQL chưa tối ưu (vòng lặp chứa SQL, không dùng `$nv_Cache`, sử dụng sai `$db_slave` cho tác vụ READ ở frontend), thiếu comments, code rườm rà.
- 💡 **GỢI Ý CẢI THIỆN (REFACTOR)** — Đề xuất giải pháp và viết đoạn code gợi ý để cấu trúc lại, cải thiện tính tái sử dụng, khử code thừa.
