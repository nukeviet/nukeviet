<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC <contact@vinades.vn>
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 2-10-2010 9:32
 */

if (! defined('NV_IS_FILE_MODULES')) {
    die('Stop!!!');
}

if (sizeof($site_mods) < 1) {
    nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=setup');
}

$page_title = $lang_module['main'];

$contents = array();
$contents['div_id'] = 'list_mods';
$contents['ajax'] = 'nv_show_list_mods();';

$contents = call_user_func('main_theme', $contents);

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';