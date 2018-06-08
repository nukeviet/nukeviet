<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC <contact@vinades.vn>
 * @Copyright (C) 2014 VINADES ., JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Dec 3, 2010 11:24:58 AM
 */

// Xác định xem sẽ dùng hook của module nào
$nv_hook_module = 'page';

// Reload lại menu khi module bị xóa
$callback = function($vars, $from_data, $receive_data) {
    global $db, $nv_Cache, $admin_info;

    file_put_contents(NV_ROOTDIR . '/hooklogs.log', "--------------------------\nINPUT: " . var_export($vars, true) . "\n", FILE_APPEND);
    file_put_contents(NV_ROOTDIR . '/hooklogs.log', "FROM: " . $from_data['module_name'] . "\n", FILE_APPEND);
    file_put_contents(NV_ROOTDIR . '/hooklogs.log', "CALLTO: " . $receive_data['module_name'] . "\n", FILE_APPEND);

    //return $vars[0];
};
nv_add_hook($module_name, 'after_change_post_status', $priority, $callback, $hook_module);
