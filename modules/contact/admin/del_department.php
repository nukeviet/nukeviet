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

$id = $nv_Request->get_int('id', 'post', 0);

$sql = 'SELECT id FROM ' . NV_PREFIXLANG . '_' . $module_data . '_department WHERE id=' . $id;
$id = $db->query($sql)->fetchColumn();

if (empty($id)) {
    exit('NO');
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
    exit('NO');
}

$nv_Cache->delMod($module_name);

include NV_ROOTDIR . '/includes/header.php';
echo 'OK';
include NV_ROOTDIR . '/includes/footer.php';
