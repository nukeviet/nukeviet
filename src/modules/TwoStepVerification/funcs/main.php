<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC <contact@vinades.vn>
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
    nv_redirect_location(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=setup');
}

// Tắt xác thực hai bước
if ($nv_Request->isset_request('turnoff2step', 'post')) {
    $tokend = $nv_Request->get_title('tokend', 'post', '');
    if (!defined('NV_IS_AJAX') or $tokend != NV_CHECK_SESSION) {
        nv_htmlOutput('Wrong URL');
    }
    $db->query('UPDATE ' . $db_config['prefix'] . '_' . $site_mods[NV_BRIDGE_USER_MODULE]['module_data'] . ' SET active2step=0, secretkey=\'\' WHERE userid=' . $user_info['userid']);
    nv_htmlOutput('OK');
}

// Tạo lại mã dự phòng
if ($nv_Request->isset_request('changecode2step', 'post')) {
    $tokend = $nv_Request->get_title('tokend', 'post', '');
    if (!defined('NV_IS_AJAX') or $tokend != NV_CHECK_SESSION) {
        nv_htmlOutput('Wrong URL');
    }
    nv_creat_backupcodes();
    $nv_Request->set_Session('showcode_' . $module_data, 1);
    nv_htmlOutput('OK');
}

$sql = 'SELECT * FROM ' . $db_config['prefix'] . '_' . $site_mods[NV_BRIDGE_USER_MODULE]['module_data'] . '_backupcodes WHERE userid=' . $user_info['userid'];
$backupcodes = $db->query($sql)->fetchAll();

$autoshowcode = false;
if ($nv_Request->isset_request('showcode_' . $module_data, 'session')) {
    $autoshowcode = true;
    $nv_Request->unset_request('showcode_' . $module_data, 'session');
}

$contents = nv_theme_info_2step($backupcodes, $autoshowcode);

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
