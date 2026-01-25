<?php

/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_MAINFILE')) {
    exit('Stop!!!');
}

// Các trường dữ liệu tổng quát. Đưa vào gì thì sẽ assign ra cái đó.
$callback = function ($vars, $from_data, $receive_data) {
    $merge_fields = [];
    $vars['pid'] = (int) $vars['pid'];
    $vars['setpids'] = array_map('intval', $vars['setpids']);

    if (in_array($vars['pid'], $vars['setpids'], true)) {
        if ($vars['mode'] != 'PRE') {
            global $nv_Lang;

            foreach ($vars as $key => $value) {
                $merge_fields[$key] = [
                    'name' => $nv_Lang->getGlobal($key),
                    'data' => $value
                ];
            }
        }
    }

    return $merge_fields;
};
nv_add_hook($module_name, 'get_email_merge_fields', $priority, $callback, $hook_module, $pid);
