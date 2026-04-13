# Danh sách Hook trong Module Content

Tài liệu này tổng hợp các điểm Hook được phát ra (`nv_apply_hook`) từ module `content`. Các module khác có thể sử dụng các Tag này để can thiệp vào luồng xử lý của module `content`.

---

## 1. Hook Nghiệp vụ (Phát ra từ Service)

Các hook này được kích hoạt khi có sự thay đổi dữ liệu, bất kể hành động đến từ Admin, API hay Frontend.

| Tag | Vị trí | Loại | Mô tả | Tham số (`$args`) |
| :--- | :--- | :--- | :--- | :--- |
| **`before_content_save`** | `ContentService::saveContent` | Filter | Kích hoạt TRƯỚC khi ghi bài viết vào DB. | `[$data]` (Mảng dữ liệu bài viết) |
| **`content_saved`** | `ContentService::saveContent` | Action | Kích hoạt SAU khi ghi bài viết thành công. | `['id' => $id, 'title' => $title, 'action' => 'add'\|'edit']` |
| **`content_deleted`** | `ContentService::deleteContent` | Action | Kích hoạt SAU khi bài viết bị xóa. | `['id' => $id, 'title' => $title]` |
| **`content_status_changed`** | `ContentService::changeStatus` | Action | Kích hoạt SAU khi thay đổi trạng thái (ẩn/hiện). | `['id' => $id, 'new_status' => $status]` |
| **`cat_saved`** | `CatService::saveCat` | Action | Kích hoạt SAU khi ghi chuyên mục thành công. | `['id' => $id, 'title' => $title, 'action' => 'add'\|'edit']` |
| **`cat_deleted`** | `CatService::deleteCat` | Action | Kích hoạt SAU khi chuyên mục bị xóa. | `['id' => $id, 'title' => $title]` |
| **`cat_status_changed`** | `CatService::changeStatus` | Action | Kích hoạt SAU khi thay đổi trạng thái chuyên mục. | `['id' => $id, 'new_status' => $status]` |

---

## 2. Hook Hiển thị (Phát ra từ Controller)

Dùng để can thiệp vào dữ liệu trước khi đẩy ra giao diện người dùng.

| Tag | Vị trí | Loại | Mô tả | Tham số (`$args`) |
| :--- | :--- | :--- | :--- | :--- |
| **`before_detail_theme`** | `funcs/main.php` | Filter | Kích hoạt trước khi render trang chi tiết frontend. | `[$rowdetail, $other_links, $content_comment]` |

---

## 3. Cách sử dụng (Ví dụ)

Để lắng nghe sự kiện bài viết được lưu, hãy tạo file `modules/{your_module}/hooks/content_saved.php`:

```php
<?php
if (!defined('NV_MAINFILE')) {
    exit('Stop!!!');
}

$callback = function (&$args, $from_data, $receive_data) {
    $item_id = $args['id'];
    $title = $args['title'];
    $action = $args['action']; // 'add' hoặc 'edit'

    // Thực hiện logic của bạn (VD: Gửi thông báo, cập nhật bảng liên quan...)
};

// Đăng ký hook vào module content
// Lưu ý: nv_add_hook này thường được khai báo qua Admin UI -> Plugin
// nv_add_hook('content', 'content_saved', 10, $callback, 'your_module');
```

> [!TIP]
> Luôn sử dụng Service làm nơi phát Hook nghiệp vụ để đảm bảo tính nhất quán giữa Admin và API.
