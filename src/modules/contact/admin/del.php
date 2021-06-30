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

$t = $nv_Request->get_int('t', 'get', 0);

nv_insert_logs(NV_LANG_DATA, $module_name, 'log_del', 'id ' . $t, $admin_info['userid']);

if ($t == 3) {
    $result = $db->query('SELECT id FROM ' . NV_PREFIXLANG . '_' . $module_data . '_send');
    while (list($id) = $result->fetch(3)) {
        nv_delete_notification(NV_LANG_DATA, $module_name, 'contact_new', $id);
    }
    $db->query('TRUNCATE TABLE ' . NV_PREFIXLANG . '_' . $module_data . '_send');
    $db->query('TRUNCATE TABLE ' . NV_PREFIXLANG . '_' . $module_data . '_reply');
} elseif ($t == 2) {
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
    $id = $nv_Request->get_int('id', 'get', 0);

    if ($id) {
        $db->query('DELETE FROM ' . NV_PREFIXLANG . '_' . $module_data . '_send WHERE id = ' . $id);
        $db->query('DELETE FROM ' . NV_PREFIXLANG . '_' . $module_data . '_reply WHERE id = ' . $id);
        nv_delete_notification(NV_LANG_DATA, $module_name, 'contact_new', $id);
    }
}

$nv_Cache->delMod($module_name);

nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name);
