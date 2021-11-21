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

$page_title = $lang_global['mod_extensions'];

$request = [];
$request['page'] = $nv_Request->get_int('page', 'get', 1);
$request['mode'] = $nv_Request->get_title('mode', 'get', '');
$request['q'] = nv_substr($nv_Request->get_title('q', 'get', ''), 0, 64);

// Fixed request
$request['per_page'] = 10;
$request['lang'] = NV_LANG_INTERFACE;
$request['basever'] = $global_config['version'];

// Mode filter
if (!in_array($request['mode'], ['search', 'newest', 'popular', 'featured', 'downloaded', 'favorites'], true)) {
    header('Location:' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=manage');
    exit();
}

if ($request['mode'] != 'search') {
    $set_active_op = $request['mode'];
}

$xtpl = new XTemplate($op . '.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('REQUEST', $request);
$xtpl->assign('NV_BASE_ADMINURL', NV_BASE_ADMINURL);
$xtpl->assign('NV_LANG_VARIABLE', NV_LANG_VARIABLE);
$xtpl->assign('NV_LANG_DATA', NV_LANG_DATA);
$xtpl->assign('NV_NAME_VARIABLE', NV_NAME_VARIABLE);
$xtpl->assign('MODULE_NAME', $module_name);

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
} elseif ($array['status'] == 'notlogin') {
    $xtpl->assign('LOGIN_NOTE', sprintf($lang_module['login_require'], NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=login&amp;redirect=' . nv_redirect_encrypt($client_info['selfurl'])));
    $xtpl->parse('main.login');
} elseif (empty($array['data'])) {
    $xtpl->parse('main.empty');
} else {
    // Save cookies
    nv_store_cookies(nv_object2array($cookies), $stored_cookies);

    foreach ($array['data'] as $row) {
        $row['rating_avg'] = ceil($row['rating_avg']);
        $row['type'] = $lang_module['types_' . (int) ($row['tid'])];
        $row['compatible_class'] = empty($row['compatible']) ? 'text-danger' : 'text-success';
        $row['compatible_title'] = empty($row['compatible']) ? $lang_module['incompatible'] : $lang_module['compatible'];

        if (empty($row['image_small'])) {
            $row['image_small'] = NV_STATIC_URL . 'themes/default/images/no_image.gif';
        }

        $row['install_link'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=install&amp;id=' . $row['id'];
        $row['detail_link'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=detail&amp;id=' . $row['id'];
        $row['detail_title'] = sprintf($lang_module['detail_title'], $row['title']);

        $xtpl->assign('ROW', $row);

        // Parse rating
        for ($i = 1; $i <= 5; ++$i) {
            $xtpl->assign('STAR', $row['rating_avg'] == $i ? ' active' : '');
            $xtpl->parse('main.data.loop.star');
        }

        // Tuong thich moi cho cai dat
        if (!empty($row['compatible']) and ($global_config['extension_setup'] == 2 or $global_config['extension_setup'] == 3)) {
            $xtpl->parse('main.data.loop.install');
        }

        $xtpl->parse('main.data.loop');
    }

    if (!empty($array['pagination']['all_page'])) {
        $base_url = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;mode=' . $request['mode'] . '&amp;q=' . urlencode($request['q']);
        $generate_page = nv_generate_page($base_url, (int) ($array['pagination']['all_page']), $request['per_page'], $request['page']);

        if (!empty($generate_page)) {
            $xtpl->assign('GENERATE_PAGE', $generate_page);
            $xtpl->parse('main.data.generate_page');
        }
    }

    $xtpl->parse('main.data');
}

$xtpl->parse('main');
$contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
