<?php
 
/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2026 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_MAINFILE')) {
    exit('Stop!!!');
}

// Khi module 'users' xóa user → xóa dữ liệu module của chúng ta
$callback = function ($args, $from_data, $receive_data) {
    global $db;

    $userid = (int) ($args[0] ?? 0);
    if ($userid <= 0) {
        return null;
    }

    // Xóa bài viết của user trong module tenmodule
    $db->query('DELETE FROM ' . NV_PREFIXLANG . '_tenmodule WHERE userid = ' . $userid);

    return true; // báo hiệu đã xử lý
};

nv_add_hook($module_name, 'user_delete', $priority, $callback, $hook_module, $pid);
