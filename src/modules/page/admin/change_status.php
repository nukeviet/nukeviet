<?php

/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_IS_FILE_ADMIN')) {
    exit('Stop!!!');
}

$id = $nv_Request->get_int('id', 'post', 0);

if (!csrf_check($nv_Request->get_string('checkss', 'post'), $admin_info['admin_id'] . '_' . $module_name . '_' . $id)) {
    nv_jsonOutput([
        'success' => 0,
        'text' => $nv_Lang->getGlobal('error_checkss')
    ]);
}

if ($id > 0) {
    $row = $db->query('SELECT status FROM ' . NV_PREFIXLANG . '_' . $module_data . ' WHERE id=' . $id)->fetch();
    if (!empty($row)) {
        $act_id = $row['status'] ? 0 : 1;
        nv_insert_logs(NV_LANG_DATA, $module_name, 'log_change_status' , 'status ' . $act_id . ' pageid ' . $id, $admin_info['userid']);
        $db->query('UPDATE ' . NV_PREFIXLANG . '_' . $module_data . ' SET status=' . $act_id . ' WHERE id= ' . $id);

        $nv_Cache->delMod($module_name);
        nv_jsonOutput([
            'success' => 1,
            'text' => 'Success!'
        ]);
    }
}

nv_jsonOutput([
    'success' => 0,
    'text' => 'Wrong data!'
]);

