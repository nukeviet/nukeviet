<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 2-9-2010 14:43
 */

if (! defined('NV_IS_FILE_ADMIN')) {
    die('Stop!!!');
}

if (! defined('NV_IS_AJAX')) {
    die('Wrong URL');
}

$userid = $nv_Request->get_int('userid', 'post', 0);

if (! $userid or $admin_info['admin_id'] == $userid) {
    die('NO');
}

$sql = 'SELECT * FROM ' . NV_MOD_TABLE . ' WHERE userid = ' . $userid;
$row = $db->query($sql)->fetch();

if (!empty($row)) {
    $row['in_groups'] = explode(',', $row['in_groups']);
    
    if ($row['group_id'] != 7 and !in_array(7, $row['in_groups'])) {
        die('NO');
    }
    
    if ($row['group_id'] == 7) {
        $row['group_id'] = 4;
    }
    $row['in_groups'] = array_diff($row['in_groups'], array(7));
    
    $db->query('UPDATE ' . NV_MOD_TABLE . ' SET group_id = ' . $row['group_id'] . ", in_groups='" . implode(',', $row['in_groups']) . "' WHERE userid = " . $userid);
    $db->query('UPDATE ' . NV_MOD_TABLE . '_groups SET numbers = numbers-1 WHERE group_id=7');
    $db->query('UPDATE ' . NV_MOD_TABLE . '_groups SET numbers = numbers+1 WHERE group_id=4');
    
    $nv_Cache->delMod($module_name);
    die('OK');
}

die('NO');