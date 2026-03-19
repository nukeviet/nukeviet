# Hệ Thống Testing NukeViet 5.x

NukeViet 5 sử dụng **Codeception** làm framework kiểm thử chính, hỗ trợ cả Unit Testing và Acceptance Testing (kiểm thử trình duyệt).

## 1. Yêu cầu hệ thống

Để chạy kiểm thử đầy đủ, bạn cần:
- **Selenium Server**: (Cho Acceptance Test)
    ```bash
    npm install selenium-standalone -g
    selenium-standalone install
    selenium-standalone start
    ```
- **Hệ điều hành**: Unix/Linux khuyến khích (Windows hỗ trợ qua Selenium).
- **Environment**: File `.env` cần được cấu hình đúng URL và DB.

---

## 2. Cách chạy Testing

### Lệnh cơ bản
| Phạm vi | Lệnh chạy |
|---|---|
| **Toàn bộ** | `php vendor/bin/codecept run` |
| **Unit Test** | `php vendor/bin/codecept run Unit` |
| **Acceptance** | `php vendor/bin/codecept run Acceptance` |

### Chạy theo Nhóm (Group)
Bạn có thể dùng annotation `@group [name]` trong code và chạy:
- `php vendor/bin/codecept run -g install` (Cài đặt hệ thống)
- `php vendor/bin/codecept run -g users` (Chức năng thành viên)
- `php vendor/bin/codecept run -g news` (Module tin tức)

---

## 3. Unit Testing

Nằm trong `tests/Unit/`. Sử dụng chuẩn PHPUnit tích hợp trong Codeception.

### Cấu trúc Unit Test chuẩn
> **Tham khảo cấu trúc Unit Test:** `docs/knowledge/examples/testing/UnitTester.php`

**Lớp UnitTester** cung cấp các helper như `$this->tester->listFile()`, `$this->tester->seeInDatabase()`.

---

## 4. Acceptance Testing (Kiểm thử trình duyệt)

Nằm trong `tests/Acceptance/`. Sử dụng định dạng **Cest** của Codeception.

### Cấu trúc Acceptance Test chuẩn
> **Tham khảo cấu trúc Acceptance Test (Cest):** `docs/knowledge/examples/testing/AcceptanceCest.php`

**Các helper quan trọng của $I**:
- `$I->login()`: Đăng nhập Admin.
- `$I->amOnUrl($url)`: Chuyển trang.
- `$I->fillField($selector, $value)`: Nhập liệu.
- `$I->click($selector)`: Click button/link.
- `$I->waitForText($text, $timeout)`: Chờ văn bản xuất hiện.
- `$I->executeJS($script)`: Chạy javascript trực tiếp trên trình duyệt.

---

## 5. Metadata và Groups

Luôn thêm annotation `@group` để phân loại test:
> **Tham khảo chú thích @group:** `docs/knowledge/examples/testing/MetaGroups.php`

---

## 6. Best Practices

1. **Database dọn dẹp**: Luôn `DROP TABLE` hoặc xóa dữ liệu rác trong `_after()` để đảm bảo môi trường sạch cho test sau.
2. **Wait hợp lý**: Dùng `$I->waitForElement` hoặc `$I->waitForText` thay vì `$I->wait(fixed_time)` để tối ưu tốc độ test.
3. **Môi trường Test**: Nên dùng một database riêng cho testing để tránh mất dữ liệu thực tế.
4. **.env file**: Đảm bảo tệp `.env` nằm ở thư mục gốc của dự án.
