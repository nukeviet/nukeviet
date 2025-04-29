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

$checkss = $nv_Request->get_string('checkss', 'post');
$id = $nv_Request->get_int('id', 'post', 0);
if ($id > 0 and $checkss == md5($id . NV_CHECK_SESSION)) {
    $row = $db->query('SELECT status FROM ' . NV_PREFIXLANG . '_' . $module_data . ' WHERE id=' . $id)->fetch();
    if (!empty($row)) {
        $act_id = $row['status'] ? 0 : 1;
        nv_insert_logs(NV_LANG_DATA, $module_name, 'log_change_status' , 'status ' . $act_id . ' pageid ' . $id, $admin_info['userid']);
        $db->query('UPDATE ' . NV_PREFIXLANG . '_' . $module_data . ' SET status=' . $act_id . ' WHERE id= ' . $id);
        $nv_Cache->delMod('modules');
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

