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

$vid = $nv_Request->get_int('vid', 'post', 0);

if ($vid > 0) {
    $_csrf_key = $admin_info['admin_id'] . '_' . $module_name . '_change_act_' . $vid;
    if (!csrf_check($nv_Request->get_string('checkss', 'post'), $_csrf_key)) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => $nv_Lang->getGlobal('error_checkss')
        ]);
    }
    $stmt = $db->prepare('SELECT act FROM ' . NV_PREFIXLANG . '_' . $module_data . ' WHERE vid = :vid');
    $stmt->bindValue(':vid', $vid, PDO::PARAM_INT);
    $stmt->execute();
    $row = $stmt->fetch();
    $stmt->closeCursor();

    if (!empty($row)) {
        $act_vid = $row['act'] ? 0 : 1;
        nv_insert_logs(NV_LANG_DATA, $module_name, 'log_change_vote', 'active ' . $act_vid . ' votingid ' . $vid, $admin_info['userid']);

        $stmt = $db->prepare('UPDATE ' . NV_PREFIXLANG . '_' . $module_data . ' SET act = :act WHERE vid = :vid');
        $stmt->bindValue(':act', $act_vid, PDO::PARAM_INT);
        $stmt->bindValue(':vid', $vid, PDO::PARAM_INT);
        $stmt->execute();
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
