<?php

/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_SYSTEM')) {
    exit('Stop!!!');
}

define('NV_MOD_2STEP_VERIFICATION', true);

// Chuyển đến domain quản lý sso
if (defined('NV_IS_USER_FORUM') and defined('SSO_SERVER')) {
    require NV_ROOTDIR . '/' . $global_config['dir_forum'] . '/nukeviet/twostep.php';
    exit();
}

// Sau này ảo hóa thì thay đổi giá trị này thành giá trị cấu hình trong CSDL
define('NV_BRIDGE_USER_MODULE', 'users');

if (!isset($site_mods[NV_BRIDGE_USER_MODULE]) or (!defined('NV_IS_USER') and !defined('NV_IS_1STEP_USER'))) {
    nv_redirect_location(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA);
}

$GoogleAuthenticator = new \NukeViet\Core\GoogleAuthenticator();
$nv_BotManager->setPrivate();

/**
 * nv_creat_backupcodes()
 */
function nv_creat_backupcodes()
{
    global $user_info, $db, $db_config, $site_mods;

    $module_data = $db_config['prefix'] . '_' . $site_mods[NV_BRIDGE_USER_MODULE]['module_data'];
    $db->query('DELETE FROM ' . $module_data . '_backupcodes WHERE userid=' . $user_info['userid']);

    $new_code = [];
    while (count($new_code) < 10) {
        $code = nv_strtolower(nv_genpass(8, 0));
        if (!in_array($code, $new_code, true)) {
            $new_code[] = $code;
        }
    }

    foreach ($new_code as $code) {
        $db->query('INSERT INTO ' . $module_data . '_backupcodes (userid, code, is_used, time_used, time_creat) VALUES (
        ' . $user_info['userid'] . ', ' . $db->quote($code) . ', 0, 0, ' . NV_CURRENTTIME . ')');
    }
}

/**
 * Hàm tạo mã bí mật và lưu nó trong 30 phút
 * @return string
 */
function nv_get_secretkey()
{
    global $nv_Request, $module_data, $GoogleAuthenticator;

    $sess_secretkey = json_decode($nv_Request->get_string($module_data . '_secretkey', 'session', ''), true);
    if (!is_array($sess_secretkey)) {
        $sess_secretkey = [];
    }
    if (!empty($sess_secretkey['secretkey']) and NV_CURRENTTIME - ($sess_secretkey['time'] ?? 0) < 1800 and csrf_check($sess_secretkey['csrf'] ?? '', $module_data . '_secretkey')) {
        return $sess_secretkey['secretkey'];
    }

    $secretkey = $GoogleAuthenticator->creatSecretkey();
    $nv_Request->set_Session($module_data . '_secretkey', json_encode([
        'secretkey' => $secretkey,
        'csrf' => csrf_create($module_data . '_secretkey'),
        'time' => NV_CURRENTTIME
    ]));
    return $secretkey;
}

$tokend_key = md5($user_info['username'] . '_' . $user_info['current_login'] . '_' . NV_BRIDGE_USER_MODULE . '_confirm_pass_' . NV_CHECK_SESSION);
$tokend_confirm_password = $nv_Request->get_title($tokend_key, 'session', '');
$tokend = md5(NV_BRIDGE_USER_MODULE . '_confirm_pass_' . NV_CHECK_SESSION);

if ($tokend_confirm_password != $tokend and $op != 'confirm') {
    nv_redirect_location(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $module_info['alias']['confirm'] . '&nv_redirect=' . nv_redirect_encrypt($client_info['selfurl']));
}
