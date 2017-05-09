<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2017 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 04/18/2017 09:47
 */

if (! defined('NV_IS_FILE_ADMIN')) {
    die('Stop!!!');
}
if (! defined('NV_IS_AJAX')) {
    die('Wrong URL');
}

$parentid = $nv_Request->get_int('parentid', 'get', 0);
$page = $nv_Request->get_int('page', 'get', 1);
$per_page = 10;
$base_url = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=location';

$contents = shops_show_location_list($parentid, $page, $per_page, $base_url);

include NV_ROOTDIR . '/includes/header.php';
echo $contents;
include NV_ROOTDIR . '/includes/footer.php';
