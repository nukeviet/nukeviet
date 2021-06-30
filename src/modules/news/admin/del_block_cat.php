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

$bid = $nv_Request->get_int('bid', 'post', 0);

$contents = 'NO_' . $bid;
$bid = $db->query('SELECT bid FROM ' . NV_PREFIXLANG . '_' . $module_data . '_block_cat WHERE bid=' . (int) $bid)->fetchColumn();
if ($bid > 0) {
    nv_insert_logs(NV_LANG_DATA, $module_name, 'log_del_blockcat', 'block_catid ' . $bid, $admin_info['userid']);
    $query = 'DELETE FROM ' . NV_PREFIXLANG . '_' . $module_data . '_block_cat WHERE bid=' . $bid;
    if ($db->exec($query)) {
        $query = 'DELETE FROM ' . NV_PREFIXLANG . '_' . $module_data . '_block WHERE bid=' . $bid;
        $db->query($query);
        nv_fix_block_cat();
        $nv_Cache->delMod($module_name);
        $contents = 'OK_' . $bid;
    }
}

include NV_ROOTDIR . '/includes/header.php';
echo $contents;
include NV_ROOTDIR . '/includes/footer.php';
