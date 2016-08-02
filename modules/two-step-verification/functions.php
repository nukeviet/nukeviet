<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
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
        while (1) {
            $_secretkey = strtolower(nv_genpass(16));
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

// Lấy mã bí mật
$secretkey = nv_get_user_secretkey();

//otpauth://totp/Example99:alice@google.com?secret=JBSWY3DPEHPK3PXP&issuer=Example99
