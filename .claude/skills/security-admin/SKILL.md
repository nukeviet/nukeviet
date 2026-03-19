---
name: security-admin
description: Deep audit bảo mật và chất lượng code cho một function admin cụ thể của module NukeViet 5.
argument-hint: <module/func>
disable-model-invocation: false
allowed-tools: Read, Grep, Glob, Bash
---

Deep audit bảo mật và chất lượng code cho một function admin cụ thể.

**Mục tiêu:** $ARGUMENTS

Ví dụ: `/security-admin myapi/main` — audit file `admin/main.php` của module `myapi`

## Các bước thực hiện

### 1. Xác định mục tiêu
Từ `$ARGUMENTS` (format: `{module}/{file}`), split chuỗi theo `/`:
- Phần trước `/` đầu tiên = tên module (VD: `myapi`)
- Phần sau `/` đầu tiên = tên file không đuôi (VD: `main`)

Ví dụ: `$ARGUMENTS = "myapi/main"` → module=`myapi`, file=`main`

Nếu `$ARGUMENTS` trống hoặc không có dấu `/`, hỏi lại user theo format `{module}/{file}`.

### 2. Truy vết file liên quan

**PHP cần đọc (theo thứ tự):**
1. `src/modules/$0/admin/$1.php` — file chính
2. `src/modules/$0/admin.functions.php`
3. `src/modules/$0/global.functions.php`

**Template & JS theo theme priority:**

Admin Future (ưu tiên):
- `src/themes/admin_future/modules/$0/$1.tpl`
- `src/themes/admin_future/modules/$0/$1-*.tpl`
- `src/themes/admin_future/js/$0.js`

Admin Default (fallback):
- `src/themes/admin_default/modules/$0/$1.tpl`
- `src/themes/admin_default/js/$0.js`

Chỉ audit **một** giao diện theo thứ tự ưu tiên. Chỉ sửa JS xuất phát từ file `.tpl` tương ứng.

### 3. Đọc skill bắt buộc
Đọc `docs/knowledge/security.md` trước khi phân tích.

### 4. Rà soát chuyên sâu

**SQLi:**
- Chuỗi user → `prepare()` + `bindParam()`?
- Đã cast `(int)` hoặc quote → gợi ý cải tiến, không phải lỗi nghiêm trọng

**CSRF:**
- `$csrf_key = $module_name . '_' . $op . '_' . $admin_info['admin_id']`
- `csrf_create($csrf_key)` — sinh 1 lần nếu cùng giá trị
- `csrf_check($csrf, $csrf_key)` — kiểm tra khi nhận POST
- Kiểm tra đồng bộ `.tpl` và `.js` tương ứng

**Phân quyền:**
- Kiểm tra `defined('NV_IS_ADMIN')` và permission cụ thể?

**Hiệu năng:**
- SQL trong vòng lặp (N+1)?

**Code quality:**
- Logic trùng lặp (DRY), thiếu comment cho logic phức tạp

### 5. Báo cáo

**🔴 LỖI NGHIÊM TRỌNG:**
- Mô tả lỗ hổng + code fix sẵn sàng paste

**🟡 CODE CHƯA TỐT:**
- Convention, logic, hiệu năng — giải thích lý do

**💡 GỢI Ý CẢI THIỆN:**
- Refactor với code mẫu
