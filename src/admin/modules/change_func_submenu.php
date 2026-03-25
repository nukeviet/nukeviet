<?php

/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_IS_FILE_MODULES')) {
    exit('Stop!!!');
}

$func_id = $nv_Request->get_int('id', 'post', 0);

if ($func_id > 0) {
    if (!csrf_check($nv_Request->get_string('checkss', 'post'), $admin_info['admin_id'] . '_' . $module_name . '_show')) {
        nv_jsonOutput([
            'success' => 0,
            'text' => $nv_Lang->getGlobal('error_checkss')
        ]);
    }
    $stmt = $db->prepare('SELECT in_submenu FROM ' . NV_MODFUNCS_TABLE . ' WHERE func_id = :id');
    $stmt->bindValue(':id', $func_id, PDO::PARAM_INT);
    $stmt->execute();
    $row = $stmt->fetch();
    $stmt->closeCursor();
    if (!empty($row)) {
        $in_submenu = $row['in_submenu'] ? 0 : 1;
        $stmt = $db->prepare('UPDATE ' . NV_MODFUNCS_TABLE . ' SET in_submenu = :in_submenu WHERE func_id = :id');
        $stmt->bindValue(':in_submenu', $in_submenu, PDO::PARAM_INT);
        $stmt->bindValue(':id', $func_id, PDO::PARAM_INT);
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
