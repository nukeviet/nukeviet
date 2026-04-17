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

/**
 * Hook dọn các session key tùy chỉnh sau khi user logout.
 *
 * Cách dùng:
 * - Khai báo các key cần xóa vào biến toàn cục $nv_custom_session_clean.
 * - Sau khi logout hoàn tất, module users sẽ phát tag clean_custom_session_key.
 * - Payload hỗ trợ:
 *   + ['keys' => ['key1', 'key2']]
 *   + ['keys' => 'key1,key2']
 */
$callback = function ($vars, $from_data, $receive_data) {
    global $nv_Request;

    $keys = $vars['keys'] ?? [];
    if (is_string($keys)) {
        $keys = array_map('trim', explode(',', $keys));
    }

    if (!is_array($keys)) {
        return null;
    }

    $keys = array_values(array_filter(array_map(static function ($key) {
        return is_scalar($key) ? trim((string) $key) : '';
    }, $keys)));

    if (empty($keys)) {
        return null;
    }

    $nv_Request->unset_request(implode(',', $keys), 'session');

    return null;
};

nv_add_hook($module_name, 'clean_custom_session_key', $priority, $callback, $hook_module, $pid);
