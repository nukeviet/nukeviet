<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES (contact@vinades.vn)
 * @Copyright 2014 VINADES. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Apr 22, 2010 3:00:20 PM
 */

if (! defined('NV_IS_FILE_ADMIN')) {
    die('Stop!!!');
}

$id = $nv_Request->get_int('id', 'post', 0);

$sql = 'SELECT id FROM ' . NV_PREFIXLANG . '_' . $module_data . '_department WHERE id=' . $id;
$id = $db->query($sql)->fetchColumn();

if (empty($id)) {
    die('NO');
}

$ok = $db->exec('DELETE FROM ' . NV_PREFIXLANG . '_' . $module_data . '_department WHERE id = ' . $id);

if ($ok) {
    nv_insert_logs(NV_LANG_DATA, $module_name, 'log_del_row', 'rowid ' . $id, $admin_info['userid']);

    $db->query('DELETE FROM ' . NV_PREFIXLANG . '_' . $module_data . '_send WHERE cid = ' . $id);
    $db->query('DELETE FROM ' . NV_PREFIXLANG . '_' . $module_data . '_reply WHERE id NOT IN (SELECT id FROM ' . NV_PREFIXLANG . '_' . $module_data . '_send)');
    $db->query('OPTIMIZE TABLE ' . NV_PREFIXLANG . '_' . $module_data . '_department');
    $db->query('OPTIMIZE TABLE ' . NV_PREFIXLANG . '_' . $module_data . '_send');
    $db->query('OPTIMIZE TABLE ' . NV_PREFIXLANG . '_' . $module_data . '_reply');
} else {
    die('NO');
}

$nv_Cache->delMod($module_name);

include NV_ROOTDIR . '/includes/header.php';
echo 'OK';
include NV_ROOTDIR . '/includes/footer.php';