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
    nv_admin_logout();
} elseif (!empty($global_users_config['active_user_logs'])) {
    nv_insert_logs(NV_LANG_DATA, $module_name, '[' . $user_info['username'] . '] ' . $lang_module['userlogout'], ' Client IP:' . NV_CLIENT_IP, $log_userid);
}

$url_redirect = !empty($client_info['referer']) ? $client_info['referer'] : (isset($_SERVER['SCRIPT_URI']) ? $_SERVER['SCRIPT_URI'] : NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA);
$is_safe_redirect = false;
if (!empty($url_redirect)) {
    $parsed_url = parse_url($url_redirect);
    if ($parsed_url !== false) {
        if (!empty($parsed_url['scheme'])) {
            // Nếu có scheme, bắt buộc phải là http hoặc https, và host phải thuộc danh sách tên miền của hệ thống
            if (in_array(strtolower($parsed_url['scheme']), ['http', 'https'], true)
                and !empty($parsed_url['host'])
                and in_array(strtolower($parsed_url['host']), array_map('strtolower', $global_config['my_domains']), true)
            ) {
                $is_safe_redirect = true;
            }
        } else {
            // Nếu là URL tương đối, không được bắt đầu bằng // và phải bắt đầu bằng NV_BASE_SITEURL
            if (!str_starts_with($url_redirect, '//')
                and str_starts_with($url_redirect, NV_BASE_SITEURL)
            ) {
                $is_safe_redirect = true;
            }
        }
    }
}
if (!$is_safe_redirect) {
    $url_redirect = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA;
}

if (defined('NV_IS_USER_FORUM') or defined('SSO_SERVER')) {
    require_once NV_ROOTDIR . '/' . $global_config['dir_forum'] . '/nukeviet/logout.php';
} else {
    NukeViet\Core\User::unset_userlogin_hash();
    if ($user_info['current_mode'] == 4 and preg_match('/^[a-z0-9_]+$/i', $user_info['openid_server']) and file_exists(NV_ROOTDIR . '/modules/users/login/cas-' . $user_info['openid_server'] . '.php')) {
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
$url_redirect = nv_htmlspecialchars(nv_url_rewrite($url_redirect, true), 'url');

$info = $lang_module['logout_ok'] . '<br /><br />';
$info .= '<img border="0" src="' . NV_STATIC_URL . NV_ASSETS_DIR . '/images/load_bar.gif"><br /><br />';
$info .= '[<a href="' . $url_redirect . '">' . $lang_module['redirect_to_back'] . '</a>]';

$contents = user_info_exit($info);
$contents .= '<meta http-equiv="refresh" content="2;url=' . $url_redirect . '" />';

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
