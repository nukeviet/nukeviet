<?php

/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_IS_FILE_EXTENSIONS')) {
    exit('Stop!!!');
}

$page_title = $nv_Lang->getModule('login_pagetitle');

$request = [];
$request['username'] = $nv_Request->get_title('username', 'post', '');
$request['password'] = $nv_Request->get_title('password', 'post', '');
$request['redirect'] = $nv_Request->get_title('redirect', 'post,get', '');

$checksess = md5(NV_CHECK_SESSION . 'mer-login');

$tpl = new \NukeViet\Template\NVSmarty();
$tpl->setTemplateDir(get_module_tpl_dir('login.tpl'));
$tpl->assign('LANG', $nv_Lang);
$tpl->assign('MODULE_NAME', $module_name);
$tpl->assign('OP', $op);

$tpl->assign('CHECKSESS', $checksess);
$tpl->assign('REQUEST', $request);

// Submit đăng nhập
if ($nv_Request->isset_request('checksess', 'post')) {
    if ($nv_Request->get_title('checksess', 'post', '') !== $checksess) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => 'Session error!!!',
        ]);
    }

    if (empty($request['username'])) {
        nv_jsonOutput([
            'status' => 'error',
            'input' => 'username',
            'mess' => $nv_Lang->getGlobal('required_invalid')
        ]);
    }
    if (empty($request['password'])) {
        nv_jsonOutput([
            'status' => 'error',
            'input' => 'password',
            'mess' => $nv_Lang->getGlobal('required_invalid')
        ]);
    }

    $request['lang'] = NV_LANG_INTERFACE;
    $request['basever'] = $global_config['version'];
    $request['mode'] = 'login';
    $request['domain'] = NV_MY_DOMAIN;

    $NV_Http = new NukeViet\Http\Http($global_config, NV_TEMP_DIR);
    $stored_cookies = nv_get_cookies();

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
        $error = $nv_Lang->getGlobal('error_valid_response');
    } elseif (!empty($array['error']['message'])) {
        $error = $array['error']['message'];
    }
    if (!empty($error)) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => $error
        ]);
    }

    // Lưu cookie mới
    nv_store_cookies(nv_object2array($cookies), $stored_cookies);
    $redirect = $request['redirect'] ? nv_redirect_decrypt($request['redirect']) : NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name;
    nv_jsonOutput([
        'status' => 'success',
        'redirect' => $redirect,
        'mess' => $nv_Lang->getModule('login_success')
    ]);
}

$contents = $tpl->fetch('login.tpl');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
