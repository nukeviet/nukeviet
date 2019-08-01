<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 2-10-2010 18:49
 */

if (! defined('NV_IS_FILE_ADMIN')) {
    die('Stop!!!');
}

$id = $nv_Request->get_int('id', 'post', 0);

$sql = 'SELECT id, status FROM ' . NV_PREFIXLANG . '_' . $module_data . ' WHERE id=' . $id;
$result = $db->query($sql);

if ($result->rowCount() != 1) {
    die('NO_' . $id);
}

list($id, $status_old) = $result->fetch(3);

$new_status = $nv_Request->get_bool('new_status', 'post');
$new_status = ( int )$new_status;

$sql = 'UPDATE ' . NV_PREFIXLANG . '_' . $module_data . ' SET status=' . $new_status . ' WHERE id=' . $id;
$db->query($sql);

nv_apply_hook($module_name, 'after_change_post_status', array($id, $status_old, $new_status));
$nv_Cache->delMod($module_name);

include NV_ROOTDIR . '/includes/header.php';
echo 'OK_' . $id;
include NV_ROOTDIR . '/includes/footer.php';