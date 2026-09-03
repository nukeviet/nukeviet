<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2021 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_IS_FILE_ADMIN')) {
    exit('Stop!!!');
}

if (!csrf_check($nv_Request->get_string('checkss', 'post', ''), $g_csrf_key[$op])) {
    nv_jsonOutput([
        'status' => 'error',
        'mess' => 'Security check failed!!!'
    ]);
}

$t = $nv_Request->get_int('t', 'post', 0);
if (!in_array($t, [1, 2, 3], true)) {
    nv_jsonOutput([
        'status' => 'error',
        'mess' => 'Invalid action!!!'
    ]);
}

nv_insert_logs(NV_LANG_DATA, $module_name, 'log_del', 'Type: ' . $t, $admin_info['userid']);

if ($t == 3) {
    // Xóa toàn bộ liên hệ
    $result = $db->query('SELECT id FROM ' . NV_PREFIXLANG . '_' . $module_data . '_send');
    while ($_scratch = $result->fetch(3)) {
        list($id) = $_scratch;
        unset($_scratch);
        nv_delete_notification(NV_LANG_DATA, $module_name, 'contact_new', $id);
    }
    $db->query('TRUNCATE TABLE ' . NV_PREFIXLANG . '_' . $module_data . '_send');
    $db->query('TRUNCATE TABLE ' . NV_PREFIXLANG . '_' . $module_data . '_reply');
} elseif ($t == 2) {
    // Xóa các liên hệ được chọn
    $sends = $nv_Request->get_typed_array('sends', 'post', 'int', []);

    if (!empty($sends)) {
        $in = implode(',', $sends);
        $db->query('DELETE FROM ' . NV_PREFIXLANG . '_' . $module_data . '_send WHERE id IN (' . $in . ')');
        $db->query('DELETE FROM ' . NV_PREFIXLANG . '_' . $module_data . '_reply WHERE id IN (' . $in . ')');
        foreach ($sends as $id) {
            nv_delete_notification(NV_LANG_DATA, $module_name, 'contact_new', $id);
        }
    }
} else {
    // Xóa một liên hệ cụ thể
    $id = $nv_Request->get_int('id', 'post', 0);

    if ($id) {
        $db->query('DELETE FROM ' . NV_PREFIXLANG . '_' . $module_data . '_send WHERE id = ' . $id);
        $db->query('DELETE FROM ' . NV_PREFIXLANG . '_' . $module_data . '_reply WHERE id = ' . $id);
        nv_delete_notification(NV_LANG_DATA, $module_name, 'contact_new', $id);
    }
}

$nv_Cache->delMod($module_name);

nv_jsonOutput([
    'status' => 'ok',
    'mess' => 'Delete successful!!!',
    'redirect' => NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name
]);
