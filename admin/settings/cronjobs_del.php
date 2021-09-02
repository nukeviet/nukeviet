<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2021 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_IS_FILE_SETTINGS')) {
    exit('Stop!!!');
}

$id = $nv_Request->get_int('id', 'get', 0);
$res = false;
$checkss = md5(NV_CHECK_SESSION . '_' . $module_name . '_' . $op . '_' . $id);
if ($checkss == $nv_Request->get_string('checkss', 'get') and !empty($id)) {
    nv_insert_logs(NV_LANG_DATA, $module_name, 'log_cronjob_del', 'id ' . $id, $admin_info['userid']);

    $sql = 'SELECT COUNT(*) FROM ' . NV_CRONJOBS_GLOBALTABLE . ' WHERE id=' . $id . ' AND is_sys=0';
    if ($db->query($sql)->fetchColumn()) {
        $res = $db->exec('DELETE FROM ' . NV_CRONJOBS_GLOBALTABLE . ' WHERE id = ' . $id);
        $db->query('OPTIMIZE TABLE ' . NV_CRONJOBS_GLOBALTABLE);
        update_cronjob_next_time();
    }
}

$res = $res ? 1 : 2;

include NV_ROOTDIR . '/includes/header.php';
echo $res;
include NV_ROOTDIR . '/includes/footer.php';
