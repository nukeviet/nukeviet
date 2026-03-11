<?php
 
/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2026 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

// Phát sự kiện lấy danh sách merge fields cho email (return_type=1 → gộp tất cả callback)
$merge_fields = nv_apply_hook('', 'get_email_merge_fields', $_args, [], 1);

// Callback của các module trả về từng phần và được merge lại
$callback = function ($args, $from_data, $receive_data) {
    return [
        'my_field' => ['name' => 'Tên trường', 'data' => '']
    ];
};
nv_add_hook('', 'get_email_merge_fields', $priority, $callback, $hook_module, $pid);
