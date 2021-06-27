<?php

/**
 * NUKEVIET Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2021 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_IS_FILE_ADMIN')) {
    exit('Stop!!!');
}

$id = $nv_Request->get_int('id', 'post', 0);

$sql = 'SELECT id, status FROM ' . NV_PREFIXLANG . '_' . $module_data . ' WHERE id=' . $id;
$result = $db->query($sql);

if ($result->rowCount() != 1) {
    exit('NO_' . $id);
}

list($id, $status_old) = $result->fetch(3);

$new_status = $nv_Request->get_bool('new_status', 'post');
$new_status = (int) $new_status;

$sql = 'UPDATE ' . NV_PREFIXLANG . '_' . $module_data . ' SET status=' . $new_status . ' WHERE id=' . $id;
$db->query($sql);

nv_apply_hook($module_name, 'after_change_post_status', [$id, $status_old, $new_status]);
$nv_Cache->delMod($module_name);

include NV_ROOTDIR . '/includes/header.php';
echo 'OK_' . $id;
include NV_ROOTDIR . '/includes/footer.php';
