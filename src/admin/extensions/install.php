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

$page_title = $nv_Lang->getGlobal('mod_extensions');

$request = [];
$request['id'] = $nv_Request->get_int('id', 'get', 0);
$request['fid'] = $nv_Request->get_int('fid', 'get', 0);
$request['lang'] = NV_LANG_INTERFACE;
$request['basever'] = $global_config['version'];

if (empty($request['fid'])) {
    // Tìm phiên bản tự động
    $request['mode'] = 'getfile';
} else {
    // Cài phiên bản được chỉ định
    $request['mode'] = 'install';
    $request['getfile'] = $nv_Request->get_absint('getfile', 'get', 0);
}

$NV_Http = new NukeViet\Http\Http($global_config, NV_TEMP_DIR);
$stored_cookies = nv_get_cookies();
$args = [
    'headers' => [
        'Referer' => NUKEVIET_STORE_APIURL,
    ],
    'cookies' => $stored_cookies,
    'body' => $request
];
$cookies = [];
$array = $NV_Http->post(NUKEVIET_STORE_APIURL, $args);

if (is_array($array)) {
    $cookies = $array['cookies'];
    $array = !empty($array['body']) ? @unserialize($array['body']) : [];
} else {
    // Do post có thể trả về object
    $array = [];
}

// Tự chuyển đến trang tải tệp tin sau khi tìm thấy
if (!empty($array['data']['compatible']['id']) and $request['mode'] == 'getfile') {
    nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=install&id=' . $array['data']['id'] . '&fid=' . $array['data']['compatible']['id'] . '&getfile=1');
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
    $contents = nv_theme_alert($nv_Lang->getGlobal('danger_level'), $error, 'danger');
    include NV_ROOTDIR . '/includes/header.php';
    echo nv_admin_theme($contents);
    include NV_ROOTDIR . '/includes/footer.php';
}

// Save cookies
nv_store_cookies(nv_object2array($cookies), $stored_cookies);

$tpl = new \NukeViet\Template\NVSmarty();
$tpl->setTemplateDir(get_module_tpl_dir('install.tpl'));
$tpl->assign('LANG', $nv_Lang);
$tpl->assign('MODULE_NAME', $module_name);
$tpl->assign('OP', $op);

$tpl->assign('REQUEST', $request);
$tpl->assign('DATA', $array['data']);

$array_string = $array['data'];
unset($array_string['title'], $array_string['documentation'], $array_string['require']);
$tpl->assign('STRING_DATA', nv_base64_encode(json_encode($array_string)));

$page_title = $nv_Lang->getModule('install_title', $array['data']['title']);

$allow_continue = true;
$has_require = 0;
$has_installed = 0;

if ($request['mode'] != 'getfile' and !empty($array['data']['compatible']) and !empty($array['data']['compatible']['id'])) {
    // Kiểm tra ứng dụng bắt buộc nếu có đã cài chưa
    if (!empty($array['data']['require'])) {
        $has_require = 1;
        $require_installed = nv_extensions_is_installed($array['data']['require']['tid'], $array['data']['require']['name'], '');
        if ($require_installed === 0) {
            $allow_continue = false;
        }
    }
}
if ($allow_continue) {
    // Kiểm tra ứng dụng này đã được cài đặt chưa
    $has_installed = nv_extensions_is_installed($array['data']['tid'], $array['data']['name'], $array['data']['compatible']['ver']);
}

$page_url = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op . '&id=' . $request['id'] . '&fid=' . $request['fid'] . '&getfile=' . $request['getfile'];

$tpl->assign('ALLOW_CONTINUE', $allow_continue);
$tpl->assign('HAS_REQUIRE', $has_require);
$tpl->assign('HAS_INSTALLED', $has_installed);
$tpl->assign('REDIRECT', nv_redirect_encrypt($page_url));

$contents = $tpl->fetch('install.tpl');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
