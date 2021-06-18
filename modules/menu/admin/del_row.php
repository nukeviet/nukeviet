<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 20-03-2011 20:08
 */

if (! defined('NV_IS_FILE_ADMIN')) {
    die('Stop!!!');
}

if (! defined('NV_IS_AJAX')) {
    die('Wrong URL');
}

$id = $nv_Request->get_int('id', 'post', 0);
$mid = $nv_Request->get_int('mid', 'post', 0);
$parentid = $nv_Request->get_int('parentid', 'post', 0);

if (!nv_menu_del_sub($id, $parentid)) {
    die('NO_' . $id);
}
menu_fix_order($mid);
$nv_Cache->delMod($module_name);

include NV_ROOTDIR . '/includes/header.php';
echo 'OK_' . $id . '_' . $mid . '_' . $parentid;
include NV_ROOTDIR . '/includes/footer.php';
