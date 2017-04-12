<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 10/03/2010 10:51
 */

if (! defined('NV_MOD_2STEP_VERIFICATION')) {
    die('Stop!!!');
}

$page_title = $module_info['site_title'];
$key_words = $module_info['keywords'];

if (empty($user_info['active2step']) and in_array($global_config['two_step_verification'], array(1, 3))) {
    header('Location:' . nv_url_rewrite(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=setup', true));
    die();
}

$allow_disable_2step = true;
if (in_array($global_config['two_step_verification'], array(1, 3))) {
    $allow_disable_2step = false;
} elseif (defined('NV_IS_ADMIN') and in_array($global_config['two_step_verification'], array(1, 2))) {
    $allow_disable_2step = false;
}

if (isset($array_op[0]) and $array_op[0] == 'turnoff' and $allow_disable_2step) {
    $db->query('UPDATE ' . $db_config['prefix'] . '_' . $site_mods[NV_BRIDGE_USER_MODULE]['module_data'] . ' SET active2step=0, secretkey=\'\' WHERE userid=' . $user_info['userid']);
    header('Location:' . nv_url_rewrite(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name, true));
    die();
}

if (isset($array_op[0]) and $array_op[0] == 'changecode' and !empty($user_info['active2step'])) {
    nv_creat_backupcodes();
    header('Location:' . nv_url_rewrite(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name, true));
    die();
}

$sql = 'SELECT * FROM ' . $db_config['prefix'] . '_' . $site_mods[NV_BRIDGE_USER_MODULE]['module_data'] . '_backupcodes WHERE userid=' . $user_info['userid'];
$backupcodes = $db->query($sql)->fetchAll();

$contents = nv_theme_info_2step($backupcodes, $allow_disable_2step);

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
