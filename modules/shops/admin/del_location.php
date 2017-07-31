<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2017 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 04/18/2017 09:47
 */

if (!defined('NV_IS_FILE_ADMIN')) {
    die('Stop!!!');
}

$locationid = $nv_Request->get_int('locationid', 'post, get', 0);
$contents = "NO_" . $locationid;

list($locationid, $parentid, $title) = $db->query("SELECT id, parentid FROM " . $db_config['prefix'] . "_" . $module_data . "_location WHERE id=" . $locationid)->fetch(3);

if ($locationid > 0) {
    $result = $db->query("DELETE FROM " . $db_config['prefix'] . "_" . $module_data . "_location WHERE parentid=" . $locationid);
    $result = $db->query("DELETE FROM " . $db_config['prefix'] . "_" . $module_data . "_location WHERE id=" . $locationid);
    if ($result) {
        $contents = 'OK';
        nv_fix_location_order();
        $nv_Cache->delMod($module_name);
    }
}

include NV_ROOTDIR . '/includes/header.php';
echo $contents;
include NV_ROOTDIR . '/includes/footer.php';