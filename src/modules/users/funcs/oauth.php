<?php

/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_IS_MOD_USER')) {
    exit('Stop!!!');
}

$nv_redirect = '';
if ($nv_Request->isset_request('nv_redirect', 'post,get')) {
    $nv_redirect = nv_get_redirect();
    if (!empty($nv_redirect)) {
        $nv_Request->set_Session('nv_redirect_' . $module_data, $nv_redirect);
    }
} elseif ($nv_Request->isset_request('sso_redirect', 'get')) {
    $sso_redirect = $nv_Request->get_title('sso_redirect', 'get', '');
    if (!empty($sso_redirect)) {
        $nv_Request->set_Session('sso_redirect_' . $module_data, $sso_redirect);
    }
}

if (defined('SSO_CLIENT_DOMAIN')) {
    /** @disregard P1011 */
    $allowed_client_origin = explode(',', SSO_CLIENT_DOMAIN);
    $sso_client = $nv_Request->get_title('client', 'get', '');
    if (!empty($sso_client)) {
        if (!in_array($sso_client, $allowed_client_origin, true)) {
            // 406 Not Acceptable
            nv_info_die($nv_Lang->getGlobal('error_404_title'), $nv_Lang->getGlobal('error_404_title'), $nv_Lang->getGlobal('error_404_content'), 406);
        }
        $nv_Request->set_Session('sso_client_' . $module_data, $sso_client);
        // Xử lý nếu client đã đăng nhập rồi mà submit vào đây nữa
        if (defined('NV_IS_USER')) {
            opidr_login([
                'status' => 'success',
                'mess' => $nv_Lang->getModule('login_ok')
            ]);
        }
    }
}

if ($global_config['allowuserlogin'] and defined('NV_OPENID_ALLOWED')) {
    $server = $nv_Request->get_string('server', 'get', '');

    if (!empty($server) and in_array($server, $global_config['openid_servers'], true)) {
        $global_config['avatar_width'] = $global_users_config['avatar_width'];
        $global_config['avatar_height'] = $global_users_config['avatar_height'];
        if (defined('NV_IS_USER_FORUM') and $server != 'google-identity') {
            require_once NV_ROOTDIR . '/' . $global_config['dir_forum'] . '/nukeviet/oauth.php';
        } elseif (module_file_exists('users/login/oauth-' . $server . '.php')) {
            include NV_ROOTDIR . '/modules/users/login/oauth-' . $server . '.php';
        } elseif (module_file_exists('users/login/cas-' . $server . '.php')) {
            include NV_ROOTDIR . '/modules/users/login/cas-' . $server . '.php';
        }
    }
}

nv_redirect_location(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name);
