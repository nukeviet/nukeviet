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
$request['page'] = $nv_Request->get_page('page', 'get', 1);
$request['mode'] = $nv_Request->get_title('mode', 'get', '');
$request['q'] = nv_substr($nv_Request->get_title('q', 'get', ''), 0, 64);
$request['per_page'] = 10;
$request['lang'] = NV_LANG_INTERFACE;
$request['basever'] = $global_config['version'];

// Mặc định chuyển về phần quản lí ứng dụng
if (!in_array($request['mode'], ['search', 'newest', 'popular', 'featured', 'downloaded', 'favorites'], true)) {
    nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=manage');
}
$page_url = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;mode=' . $request['mode'];

if (!empty($request['q'])) {
    $page_url .= '&amp;q=' . urlencode($request['q']);
}
if ($request['page'] > 1) {
    $page_url .= '&amp;page=' . $request['page'];
}
if ($request['mode'] != 'search') {
    $set_active_op = $request['mode'];
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
$contents = '';
if (!empty($error)) {
    $contents = nv_theme_alert($nv_Lang->getGlobal('danger_level'), $error, 'danger');
} elseif ($array['status'] == 'notlogin') {
    $message = $nv_Lang->getModule('login_require', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=login&amp;redirect=' . nv_redirect_encrypt(str_replace('&amp;', '&', $page_url)));
    $contents = nv_theme_alert($nv_Lang->getGlobal('info_level'), $message, 'info');
} elseif (empty($array['data'])) {
    $contents = nv_theme_alert($nv_Lang->getGlobal('info_level'), $nv_Lang->getModule('empty_response'), 'info');
}
if (!empty($contents)) {
    include NV_ROOTDIR . '/includes/header.php';
    echo nv_admin_theme($contents);
    include NV_ROOTDIR . '/includes/footer.php';
}

// Lưu cookie giao tiếp với kho Store lại
nv_store_cookies(nv_object2array($cookies), $stored_cookies);

$tpl = new \NukeViet\Template\NVSmarty();
$tpl->registerPlugin('modifier', 'ceil', 'ceil');
$tpl->setTemplateDir(get_module_tpl_dir('main.tpl'));
$tpl->assign('LANG', $nv_Lang);
$tpl->assign('MODULE_NAME', $module_name);
$tpl->assign('OP', $op);
$tpl->assign('GCONFIG', $global_config);

$tpl->assign('REQUEST', $request);
$tpl->assign('ARRAY', $array['data']);

// Phân trang
$generate_page = '';
if (!empty($array['pagination']['all_page'])) {
    $base_url = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;mode=' . $request['mode'] . '&amp;q=' . urlencode($request['q']);
    $generate_page = nv_generate_page($base_url, (int) ($array['pagination']['all_page']), $request['per_page'], $request['page']);
}
$tpl->assign('PAGINATION', $generate_page);

$contents = $tpl->fetch('main.tpl');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
