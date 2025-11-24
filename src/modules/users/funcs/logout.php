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

if (!defined('NV_IS_USER') and !defined('NV_IS_1STEP_USER')) {
    nv_redirect_location(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name);
}

$is_system = $nv_Request->get_int('system', 'post', 0);
$log_userid = $is_system ? 0 : $user_info['userid'];

if (defined('NV_IS_ADMIN')) {
    nv_insert_logs(NV_LANG_DATA, 'login', '[' . $user_info['username'] . '] ' . $nv_Lang->getGlobal('admin_logout_title'), ' Client IP:' . NV_CLIENT_IP, $log_userid);
    nv_admin_logout();
} elseif (!empty($global_users_config['active_user_logs'])) {
    nv_insert_logs(NV_LANG_DATA, $module_name, '[' . $user_info['username'] . '] ' . $nv_Lang->getModule('userlogout'), ' Client IP:' . NV_CLIENT_IP, $log_userid);
}

$nv_redirect = '';
if ($nv_Request->isset_request('nv_redirect', 'post,get')) {
    $nv_redirect = nv_get_redirect();
    if ($nv_Request->isset_request('nv_redirect', 'get') and !empty($nv_redirect)) {
        $nv_Request->set_Session('nv_redirect_' . $module_data, $nv_redirect);
    }
} elseif ($nv_Request->isset_request('sso_redirect', 'get')) {
    $sso_redirect = $nv_Request->get_title('sso_redirect', 'get', '');
    if (!empty($sso_redirect)) {
        $nv_Request->set_Session('sso_redirect_' . $module_data, $sso_redirect);
    }
}
if (defined('SSO_CLIENT_DOMAIN')) {
    /** @disregard PHP0415 */
    $allowed_client_origin = explode(',', SSO_CLIENT_DOMAIN);
    $sso_client = $nv_Request->get_title('client', 'get', '');
    if (!empty($sso_client)) {
        if (!in_array($sso_client, $allowed_client_origin, true)) {
            // 406 Not Acceptable
            nv_info_die($nv_Lang->getGlobal('error_404_title'), $nv_Lang->getGlobal('error_404_title'), $nv_Lang->getGlobal('error_404_content'), 406);
        }
        $nv_Request->set_Session('sso_client_' . $module_data, $sso_client);
    }
}

$url_redirect = nv_redirect_decrypt($nv_redirect);
if (defined('SSO_REGISTER_SECRET')) {
    $sso_client = $nv_Request->get_title('sso_client_' . $module_data, 'session', '');
    $sso_redirect = $nv_Request->get_title('sso_redirect_' . $module_data, 'session', '');
    $sso_redirect = NukeViet\Client\Sso::decrypt($sso_redirect);

    if (!empty($sso_redirect) and !empty($sso_client) and str_starts_with($sso_redirect, $sso_client)) {
        $url_redirect = $sso_redirect;
    }

    $nv_Request->unset_request('sso_client_' . $module_data, 'session');
    $nv_Request->unset_request('sso_redirect_' . $module_data, 'session');
}

empty($url_redirect) && ($url_redirect = !empty($client_info['referer']) ? $client_info['referer'] : ($_SERVER['SCRIPT_URI'] ?? NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA));

if (defined('NV_IS_USER_FORUM') or defined('SSO_SERVER')) {
    require_once NV_ROOTDIR . '/' . $global_config['dir_forum'] . '/nukeviet/logout.php';
} else {
    $db->query('DELETE FROM ' . NV_MOD_TABLE . '_login WHERE userid=' . $user_info['userid'] . ' AND clid=' . $db->quote($client_info['clid']));
    NukeViet\Core\User::unset_userlogin_hash();
    if ($user_info['current_mode'] == 4 and module_file_exists('users/login/cas-' . $user_info['openid_server'] . '.php')) {
        define('CAS_LOGOUT_URL_REDIRECT', $url_redirect);
        include NV_ROOTDIR . '/modules/users/login/cas-' . $user_info['openid_server'] . '.php';
    }
}

$nv_ajax_login = $nv_Request->get_int('nv_ajax_login', 'post', 0);
if ($nv_ajax_login) {
    nv_htmlOutput($nv_Lang->getModule('logout_ok'));
}

$page_title = $module_info['site_title'];
$key_words = $module_info['keywords'];
$page_url = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op;
$canonicalUrl = getCanonicalUrl($page_url);

$info = $nv_Lang->getModule('logout_ok');
$info .= '<p><span class="load-bar"></span></p>';
$info .= '<p>[<a href="' . $url_redirect . '">' . $nv_Lang->getModule('redirect_to_back') . '</a>]</p>';

$contents = user_info_exit($info);
$contents .= '<meta http-equiv="refresh" content="2;url=' . nv_url_rewrite($url_redirect) . '" />';

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
