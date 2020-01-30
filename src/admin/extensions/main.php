<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC <contact@vinades.vn>
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 2-1-2010 22:5
 */

if (!defined('NV_IS_FILE_EXTENSIONS')) {
    die('Stop!!!');
}

$page_title = $nv_Lang->getGlobal('mod_extensions');

$request = [];
$request['page'] = $nv_Request->get_int('page', 'get', 1);
$request['mode'] = $nv_Request->get_title('mode', 'get', '');
$request['q'] = nv_substr($nv_Request->get_title('q', 'get', ''), 0, 64);

// Fixed request
$request['per_page'] = 10;
$request['lang'] = NV_LANG_INTERFACE;
$request['basever'] = $global_config['version'];

// Mode filter
if (!in_array($request['mode'], ['search', 'newest', 'popular', 'featured', 'downloaded', 'favorites'])) {
    nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=manage');
}

if ($request['mode'] != 'search') {
    $set_active_op = $request['mode'];
}

$tpl = new \NukeViet\Template\Smarty();
$tpl->setTemplateDir(NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$tpl->assign('LANG', $nv_Lang);
$tpl->assign('NV_BASE_ADMINURL', NV_BASE_ADMINURL);
$tpl->assign('MODULE_NAME', $module_name);

$NV_Http = new NukeViet\Http\Http($global_config, NV_TEMP_DIR);
$stored_cookies = nv_get_cookies();

// Debug
$args = [
    'headers' => [
        'Referer' => NUKEVIET_STORE_APIURL,
    ],
    'cookies' => $stored_cookies,
    'body' => $request
];

$array = $NV_Http->post(NUKEVIET_STORE_APIURL, $args);

$cookies = $array['cookies'];
$array = !empty($array['body']) ? (is_serialized_string($array['body']) ? unserialize($array['body']) : []) : [];

$error = '';
if (!empty(NukeViet\Http\Http::$error)) {
    $error = nv_http_get_lang(NukeViet\Http\Http::$error);
} elseif (empty($array['status']) or !isset($array['error']) or !isset($array['data']) or !isset($array['pagination']) or !is_array($array['error']) or !is_array($array['data']) or !is_array($array['pagination']) or (!empty($array['error']) and (!isset($array['error']['level']) or empty($array['error']['message'])))) {
    $error = $nv_Lang->getGlobal('error_valid_response');
} elseif (!empty($array['error']['message'])) {
    $error = $array['error']['message'];
}

$tpl->assign('EXTENSION_SETUP', ($global_config['extension_setup'] == 2 or $global_config['extension_setup'] == 3));
$tpl->assign('ERROR', $error);
$tpl->assign('DATA', $array);
$tpl->assign('REQUEST', $request);
$tpl->assign('LOGIN_LINK', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=login&amp;redirect=' . nv_redirect_encrypt($client_info['selfurl']));

if (empty($error) and $array['status'] != 'notlogin' and !empty($array['data'])) {
    // Save cookies
    nv_store_cookies(nv_object2array($cookies), $stored_cookies);

    $array_items = [];

    foreach ($array['data'] as $row) {
        $row['rating_avg'] = ceil($row['rating_avg']);
        $row['type'] = $nv_Lang->getModule('types_' . intval($row['tid']));
        $row['compatible_class'] = empty($row['compatible']) ? 'text-danger' : 'text-success';
        $row['compatible_title'] = empty($row['compatible']) ? $nv_Lang->getModule('incompatible') : $nv_Lang->getModule('compatible');

        if (empty($row['image_small'])) {
            $row['image_small'] = NV_BASE_SITEURL . 'themes/default/images/no_image.gif';
        }

        $row['install_link'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=install&amp;id=' . $row['id'];
        $row['detail_link'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=detail&amp;id=' . $row['id'];
        $row['detail_title'] = sprintf($nv_Lang->getModule('detail_title'), $row['title']);

        $array_items[] = $row;
    }

    $tpl->assign('ARRAY_ITEMS', $array_items);

    if (!empty($array['pagination']['all_page'])) {
        $base_url = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;mode=' . $request['mode'] . '&amp;q=' . urlencode($request['q']);
        $generate_page = nv_generate_page($base_url, intval($array['pagination']['all_page']), $request['per_page'], $request['page']);

        if (!empty($generate_page)) {
            $tpl->assign('GENERATE_PAGE', $generate_page);
        }
    }
}

$contents = $tpl->fetch($op . '.tpl');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
