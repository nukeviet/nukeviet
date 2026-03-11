<?php
 
/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2026 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

/**
 * Hook Template chuẩn NukeViet 5
 * Tham chiếu thực tế: src/modules/users/hooks/emf_code_user.php
 */

if (!defined('NV_MAINFILE')) {
    exit('Stop!!!');
}

// Biến hệ thống tự inject vào scope khi load:
// $module_name — Module phát sự kiện (string, '' nếu system)
// $hook_module — Module nhận (tên module của bạn, string)
// $priority    — Độ ưu tiên (int, mặc định 10)
// $pid         — ID quản lý trong CSDL (int)

// Callback signature thực tế: ($vars, $from_data, $receive_data)
// $vars: mảng dữ liệu chính từ nv_apply_hook()
// $from_data: thông tin module phát (array hoặc null)
// $receive_data: thông tin module nhận (array hoặc null)
$callback = function ($vars, $from_data, $receive_data) {
    // Xử lý logic tại đây
    // Ví dụ: chỉnh sửa dữ liệu, ghi log, gửi email...

    $result = [];
    // ... logic xử lý ...

    return $result; // hoặc null nếu không có gì trả về
};

// Đăng ký hook — gọi ở dòng cuối file
nv_add_hook($module_name, 'tag_name', $priority, $callback, $hook_module, $pid);
