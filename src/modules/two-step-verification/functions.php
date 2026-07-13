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
    global $user_info, $db, $db_config, $site_mods, $crypt;

    $module_data = $db_config['prefix'] . '_' . $site_mods[NV_BRIDGE_USER_MODULE]['module_data'];

    // Xóa toàn bộ mã hiện tại
    $stmt = $db->prepare('DELETE FROM ' . $module_data . '_backupcodes WHERE userid = :userid');
    $stmt->bindValue(':userid', $user_info['userid'], PDO::PARAM_INT);
    $stmt->execute();
    $stmt->closeCursor();

    $new_code = [];
    while (count($new_code) < 10) {
        $code = nv_strtolower(nv_genpass(8, 0));
        if (!in_array($code, $new_code, true)) {
            $new_code[] = $code;
        }
    }

    $stmt = $db->prepare('INSERT INTO ' . $module_data . '_backupcodes (userid, code, is_used, time_used, time_creat) VALUES (:userid, :code, 0, 0, :time_creat)');
    foreach ($new_code as $code) {
        $stmt->bindValue(':userid', $user_info['userid'], PDO::PARAM_INT);
        $stmt->bindValue(':code', $crypt->encryptDeterministic($code), PDO::PARAM_STR);
        $stmt->bindValue(':time_creat', NV_CURRENTTIME, PDO::PARAM_INT);
        $stmt->execute();
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
    ], NV_JSON_ENCODE));
    return $secretkey;
}

$tokend_key = md5($user_info['username'] . '_' . $user_info['current_login'] . '_' . NV_BRIDGE_USER_MODULE . '_confirm_pass_' . NV_CHECK_SESSION);
$tokend_confirm_password = $nv_Request->get_title($tokend_key, 'session', '');
$tokend = md5(NV_BRIDGE_USER_MODULE . '_confirm_pass_' . NV_CHECK_SESSION);

if (!hash_equals($tokend, $tokend_confirm_password) and $op != 'confirm') {
    nv_redirect_location(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $module_info['alias']['confirm'] . '&nv_redirect=' . nv_redirect_encrypt($client_info['selfurl']));
}

/**
 * Đồng bộ trạng thái xác nhận mật khẩu sang endpoint users/editinfo/passkey
 * (checkss khớp với modules/users/funcs/editinfo.php thay đổi cần cập nhật)
 */
if (hash_equals($tokend, $tokend_confirm_password) and !is_verified_password('passkey', NV_BRIDGE_USER_MODULE)) {
    set_verified_password('passkey', NV_BRIDGE_USER_MODULE);
}
