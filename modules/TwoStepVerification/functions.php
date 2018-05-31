<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC <contact@vinades.vn>
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 10/03/2010 10:51
 */

if (! defined('NV_SYSTEM')) {
    die('Stop!!!');
}

define('NV_MOD_2STEP_VERIFICATION', true);

// Sau này ảo hóa thì thay đổi giá trị này thành giá trị cấu hình trong CSDL
define('NV_BRIDGE_USER_MODULE', 'users');

if (!isset($site_mods[NV_BRIDGE_USER_MODULE]) or (!defined('NV_IS_USER') and !defined('NV_IS_1STEP_USER'))) {
    header('Location: ' . nv_url_rewrite(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA, true));
    die();
}

$GoogleAuthenticator = new \NukeViet\Core\GoogleAuthenticator();

/**
 * nv_get_user_secretkey()
 *
 * @return
 */
function nv_get_user_secretkey()
{
    global $db, $site_mods, $user_info, $db_config;

    $module_data = $db_config['prefix'] . '_' . $site_mods[NV_BRIDGE_USER_MODULE]['module_data'];
    $secretkey = $db->query('SELECT secretkey FROM ' . $module_data . ' WHERE userid=' . $user_info['userid'])->fetchColumn();

    if (empty($secretkey)) {
        global $GoogleAuthenticator;
        while (1) {
            $_secretkey = $GoogleAuthenticator->creatSecretkey();
            if ($db->query('SELECT COUNT(*) FROM ' . $module_data . ' WHERE secretkey=' . $db->quote($_secretkey))->fetchColumn() == 0) {
                if ($db->exec('UPDATE ' . $module_data . ' SET secretkey=' . $db->quote($_secretkey) . ' WHERE userid=' . $user_info['userid'])) {
                    $secretkey = $_secretkey;
                    break;
                } else {
                    trigger_error('Error creat user secretkey!!!', 256);
                }
            }
        }
    }

    return $secretkey;
}

/**
 * nv_creat_backupcodes()
 *
 * @return void
 */
function nv_creat_backupcodes()
{
    global $user_info, $db, $db_config, $site_mods;

    $module_data = $db_config['prefix'] . '_' . $site_mods[NV_BRIDGE_USER_MODULE]['module_data'];
    $db->query('DELETE FROM ' . $module_data . '_backupcodes WHERE userid=' . $user_info['userid']);

    $new_code = array();
    while (sizeof($new_code) < 10) {
        $code = nv_strtolower(nv_genpass(8, 0));
        if (!in_array($code, $new_code)) {
            $new_code[] = $code;
        }
    }

    foreach ($new_code as $code) {
        $db->query('INSERT INTO ' . $module_data . '_backupcodes (userid, code, is_used, time_used, time_creat) VALUES (
        ' . $user_info['userid'] . ', ' . $db->quote($code) . ', 0, 0, ' . NV_CURRENTTIME . ')');
    }
}

// Lấy mã bí mật
$secretkey = nv_get_user_secretkey();

$tokend_key = md5($user_info['username'] . '_' . $user_info['current_login'] . '_' . NV_BRIDGE_USER_MODULE . '_confirm_pass_' . NV_CHECK_SESSION);
$tokend_confirm_password = $nv_Request->get_title($tokend_key, 'session', '');
$tokend = md5(NV_BRIDGE_USER_MODULE . '_confirm_pass_' . NV_CHECK_SESSION);

if ($tokend_confirm_password != $tokend and $op != 'confirm') {
    header('Location: ' . nv_url_rewrite(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $module_info['alias']['confirm'] . '&nv_redirect=' . nv_redirect_encrypt($client_info['selfurl']), true));
    die();
}
