---
name: csrf-refactor
description: Refactor CSRF cho module
argument-hint: <module>
disable-model-invocation: false
allowed-tools: Read, Grep, Glob, Edit, Write, Bash
---

# Refactor CSRF cho module, hỗ trợ Smarty và XTemplate.

## Ngôn ngữ Giao tiếp
Sử dụng Tiếng Việt trong mọi phản hồi và tài liệu.

## 1. Xác định phạm vi mục tiêu
Luạt chủ mới: tuỳ theo đầu vào, xác định phạm vi cần rà soát.

- **Toàn bộ Module**: `/csrf-refactor [module]` (Mặc định rà soát mọi file trong thư mục module).
- **Theo phân vùng**: `/csrf-refactor [module] [admin|site]` (Chỉ tập trung vào các thư mục tương ứng).
- **Tệp tin cụ thể**: `/csrf-refactor [path/to/file.php]` (Chỉ rà soát tệp được chỉ định).
- *Lưu ý*: Nếu người dùng không nhập tham số, AI sẽ tự động lấy thông tin từ tệp tin đang mở làm phạm vi.

Với mỗi module/phân vùng, tìm theo ưu tiên:
1. `src/modules/{module}/`
2. `src/admin/{module}/`
*Nếu không thấy -> Báo lỗi và dừng.*

## 2. Quy tắc Biến Token ($csrf_key & $_csrf_key)
- **`$csrf_key`**: Biến toàn cục (đã có sẵn trong `admin/index.php`).
  - *Xóa* định nghĩa thủ công: `$csrf_key = $admin_info['admin_id'] . '_' . $module_name . '_' . $op;`.
- **`$_csrf_key`**: Dùng khi cần liên kết giữa các file hoặc xử lý AJAX.
  - **Tĩnh**: `$_csrf_key = $admin_info['admin_id'] . '_' . $module_name . '_TênTácVụGốc';`.
  - **Động (cho từng ID)**: `$row['checkss'] = csrf_create($admin_info['admin_id'] . '_' . $module_name . '_' . $row['id']);`.
- **Global**: Bắt buộc `global $csrf_key;` (hoặc `$_csrf_key`) khi dùng trong function.

## 3. Thực thi Refactor

### Bước 1: Cập nhật Template (TPL)
Ưu tiên sửa file trong `admin_future`, sau đó mới đến `admin_default`.
- **Biến thay thế**: Thay `NV_CHECK_SESSION` bằng `CHECKSS`.
- **Thuộc tính `data-checkss` (AJAX)**:
  - Nếu đã có: **Giữ nguyên vị trí** để tránh diff thừa.
  - Nếu thêm mới: Đặt ở **cuối thẻ** (trước `>`).

### Bước 2: Cập nhật PHP
1. **Kiểm tra & Xử lý Token**:
   - Ưu tiên dùng trực tiếp `$csrf_key`. Chỉ dùng biến trung gian `$_csrf_key` khi thực sự cần (liên kết nhiều chỗ).
   - Nếu dùng 1 lần, hãy truyền thẳng chuỗi vào hàm: `csrf_create($admin_info[...] . '_content')`.
2. **Logic `csrf_check`**:
   - **Bắt buộc** nằm trong `if (!...)` để báo lỗi thất bại.
   - Sử dụng ngôn ngữ hệ thống: `$nv_Lang->getGlobal('error_checkss')`.
   - **AJAX**:
     ```php
     if (!csrf_check($nv_Request->get_string('checkss', 'post'), $_csrf_key)) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => $nv_Lang->getGlobal('error_checkss')
        ]);
     }
     ```
   - **Form/Link**: Dùng `nv_info_die` hoặc `nv_redirect_location` tùy ngữ cảnh.
3. **Truyền token vào template**:
   - **Dùng 1 lần** → inline thẳng, **không tạo biến trung gian**:
     ```php
     $tpl->assign('CHECKSS', csrf_create($csrf_key));
     ```
   - **Dùng nhiều lần** (vd: cũng cần so sánh trong PHP, hoặc gán vào nhiều response) → tạo biến:
     ```php
     $checkss = csrf_create($csrf_key);
     $tpl->assign('CHECKSS', $checkss);
     // dùng lại $checkss ở chỗ khác...
     ```
4. **Kiểm tra token** (`csrf_check`):
   - **Dùng 1 lần** → đọc input thẳng trong lời gọi, **không tạo biến trung gian**:
     ```php
     if (!csrf_check($nv_Request->get_string('checkss', 'post'), $csrf_key)) {
     ```
   - **Dùng nhiều lần** → gán biến rồi dùng lại:
     ```php
     $checkss = $nv_Request->get_string('checkss', 'post');
     if (!csrf_check($checkss, $csrf_key)) { ... }
     ```

### Bước 3: Cập nhật AJAX JS
Lấy token từ element thay vì dùng `checksess` chung của body:
```javascript
data: { checkss: btn.data('checkss'), ... }
```

## 4. Phạm vi & Quy tắc Bảo toàn
- ⚠️ **Bảo toàn logic gốc**: Tuyệt đối không sửa SQL, biến, hoặc validate không liên quan. Chỉ thay thế `NV_CHECK_SESSION` bằng `csrf_check()`.
- ✅ Chỉ sửa file Admin, bỏ qua Frontend nếu có đối số Theo phân vùng admin
- ❌ Không chạy test, không cài composer.

## 5. Dọn dẹp Cache
Sau khi hoàn tất, thực hiện xóa cache:
// turbo
```bash
rm -rf src/data/cache/*/*.cache
rm -rf src/data/cache/smarty-compile/*.php
```
