<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2021 VINADES.,JSC. All rights reserved
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
    nv_insert_logs(NV_LANG_DATA, 'login', '[' . $user_info['username'] . '] ' . $lang_global['admin_logout_title'], ' Client IP:' . NV_CLIENT_IP, $log_userid);
    $nv_Request->unset_request('admin,online', 'session');
} elseif (!empty($global_users_config['active_user_logs'])) {
    nv_insert_logs(NV_LANG_DATA, $module_name, '[' . $user_info['username'] . '] ' . $lang_module['userlogout'], ' Client IP:' . NV_CLIENT_IP, $log_userid);
}

$url_redirect = !empty($client_info['referer']) ? $client_info['referer'] : (isset($_SERVER['SCRIPT_URI']) ? $_SERVER['SCRIPT_URI'] : NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA);
if (defined('NV_IS_USER_FORUM') or defined('SSO_SERVER')) {
    require_once NV_ROOTDIR . '/' . $global_config['dir_forum'] . '/nukeviet/logout.php';
} else {
    $nv_Request->unset_request('nvloginhash', 'cookie');
    if ($user_info['current_mode'] == 4 and file_exists(NV_ROOTDIR . '/modules/users/login/cas-' . $user_info['openid_server'] . '.php')) {
        define('CAS_LOGOUT_URL_REDIRECT', $url_redirect);
        include NV_ROOTDIR . '/modules/users/login/cas-' . $user_info['openid_server'] . '.php';
    }
}

$nv_ajax_login = $nv_Request->get_int('nv_ajax_login', 'post', 0);
if ($nv_ajax_login) {
    $info = $lang_module['logout_ok'] . '<br /><br /><img border="0" src="' . NV_STATIC_URL . NV_ASSETS_DIR . '/images/load_bar.gif">';
    include NV_ROOTDIR . '/includes/header.php';
    echo $info;
    include NV_ROOTDIR . '/includes/footer.php';
    exit;
}

$page_title = $module_info['site_title'];
$key_words = $module_info['keywords'];
$mod_title = isset($lang_module['main_title']) ? $lang_module['main_title'] : $module_info['custom_title'];
$page_url = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op;
$canonicalUrl = getCanonicalUrl($page_url);

$info = $lang_module['logout_ok'] . '<br /><br />';
$info .= '<img border="0" src="' . NV_STATIC_URL . NV_ASSETS_DIR . '/images/load_bar.gif"><br /><br />';
$info .= '[<a href="' . $url_redirect . '">' . $lang_module['redirect_to_back'] . '</a>]';

$contents = user_info_exit($info);
$contents .= '<meta http-equiv="refresh" content="2;url=' . nv_url_rewrite($url_redirect) . '" />';

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
