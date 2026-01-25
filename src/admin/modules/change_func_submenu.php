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
    $row = $db->query('SELECT in_submenu FROM ' . NV_MODFUNCS_TABLE . ' WHERE func_id=' . $func_id)->fetch();
    if (!empty($row)) {
        $in_submenu = $row['in_submenu'] ? 0 : 1;
        $db->query('UPDATE ' . NV_MODFUNCS_TABLE . ' SET in_submenu=' . $in_submenu . ' WHERE func_id=' . $func_id);
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
