<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 2-17-2010 0:5
 */

if (! defined('NV_IS_FILE_MODULES')) {
    die('Stop!!!');
}

$contents = 'NO_' . $module_name;
$modname = $nv_Request->get_title('mod', 'post');
$sample = $nv_Request->get_int('sample', 'post', 0);

if (! empty($modname) and preg_match($global_config['check_module'], $modname)) {
    nv_insert_logs(NV_LANG_DATA, $module_name, $lang_global['recreate'] . ' module "' . $modname . '"', '', $admin_info['userid']);
    $contents = nv_setup_data_module(NV_LANG_DATA, $modname, $sample);
}

include NV_ROOTDIR . '/includes/header.php';
echo $contents;
include NV_ROOTDIR . '/includes/footer.php';
