<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2021 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_IS_FILE_EXTENSIONS')) {
    exit('Stop!!!');
}

$page_title = $lang_module['login_pagetitle'];

$request = [];
$request['username'] = $nv_Request->get_title('username', 'post', '');
$request['password'] = $nv_Request->get_title('password', 'post', '');
$request['redirect'] = $nv_Request->get_title('redirect', 'post,get', '');

$checksess = md5(NV_CHECK_SESSION . 'mer-login');

$xtpl = new XTemplate($op . '.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('GLANG', $lang_global);
$xtpl->assign('REQUEST', $request);
$xtpl->assign('NV_BASE_ADMINURL', NV_BASE_ADMINURL);
$xtpl->assign('NV_LANG_VARIABLE', NV_LANG_VARIABLE);
$xtpl->assign('NV_LANG_DATA', NV_LANG_DATA);
$xtpl->assign('NV_NAME_VARIABLE', NV_NAME_VARIABLE);
$xtpl->assign('MODULE_NAME', $module_name);
$xtpl->assign('CHECKSESS', $checksess);

if (!empty($request['username']) and !empty($request['password']) and $checksess === $nv_Request->get_title('checksess', 'post', '')) {
    // Fixed request
    $request['lang'] = NV_LANG_INTERFACE;
    $request['basever'] = $global_config['version'];
    $request['mode'] = 'login';
    $request['domain'] = NV_MY_DOMAIN;

    $NV_Http = new NukeViet\Http\Http($global_config, NV_TEMP_DIR);
    $stored_cookies = nv_get_cookies();

    // Debug
    $args = [
        'headers' => [
            'Referer' => NUKEVIET_STORE_APIURL
        ],
        'cookies' => $stored_cookies,
        'body' => $request
    ];

    $cookies = [];
    $array = $NV_Http->post(NUKEVIET_STORE_APIURL, $args);

    if (is_array($array)) {
        $cookies = $array['cookies'];
        $array = !empty($array['body']) ? (is_serialized_string($array['body']) ? unserialize($array['body']) : []) : [];
    }

    $error = '';
    if (!empty(NukeViet\Http\Http::$error)) {
        $error = nv_http_get_lang(NukeViet\Http\Http::$error);
    } elseif (empty($array['status']) or !isset($array['error']) or !isset($array['data']) or !isset($array['pagination']) or !is_array($array['error']) or !is_array($array['data']) or !is_array($array['pagination']) or (!empty($array['error']) and (!isset($array['error']['level']) or empty($array['error']['message'])))) {
        $error = $lang_global['error_valid_response'];
    } elseif (!empty($array['error']['message'])) {
        $error = $array['error']['message'];
    }

    // Show error
    if (!empty($error)) {
        $xtpl->assign('ERROR', $error);
        $xtpl->parse('main.error');

        $contents = $xtpl->text('main.error');
    } else {
        // Save cookies
        nv_store_cookies(nv_object2array($cookies), $stored_cookies);

        $redirect = $request['redirect'] ? nv_redirect_decrypt($request['redirect']) : NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name;

        $xtpl->assign('REDIRECT_LINK', $redirect);
        $xtpl->parse('main.ok');

        $contents = $xtpl->text('main.ok');
    }

    include NV_ROOTDIR . '/includes/header.php';
    echo $contents;
    include NV_ROOTDIR . '/includes/footer.php';
}

$xtpl->parse('main');
$contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
