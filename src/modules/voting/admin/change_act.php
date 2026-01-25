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
$vid = $nv_Request->get_int('vid', 'post', 0);

if ($vid > 0 and $checkss == md5($vid . NV_CHECK_SESSION)) {
    $row = $db->query('SELECT act FROM ' . NV_PREFIXLANG . '_' . $module_data . ' WHERE vid=' . $vid)->fetch();
    if (!empty($row)) {
        $act_vid = $row['act'] ? 0 : 1;
        nv_insert_logs(NV_LANG_DATA, $module_name, 'log_change_vote' , 'active ' . $act_vid . ' votingid ' . $vid, $admin_info['userid']);
        $db->query('UPDATE ' . NV_PREFIXLANG . '_' . $module_data . ' SET act=' . $act_vid . ' WHERE vid= ' . $vid);
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
