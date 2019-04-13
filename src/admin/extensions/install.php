<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 2-1-2010 22:5
 */

if (!defined('NV_IS_FILE_EXTENSIONS')) {
    die('Stop!!!');
}

$page_title = $nv_Lang->getGlobal('mod_extensions');

$request = [];
$request['id'] = $nv_Request->get_int('id', 'get', 0);
$request['fid'] = $nv_Request->get_int('fid', 'get', 0);

// Fixed request
$request['lang'] = NV_LANG_INTERFACE;
$request['basever'] = $global_config['version'];

$tpl = new \NukeViet\Template\Smarty();
$tpl->setTemplateDir(NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$tpl->assign('LANG', $nv_Lang);

$error = '';
$message = '';

$NV_Http = new NukeViet\Http\Http($global_config, NV_TEMP_DIR);
$stored_cookies = nv_get_cookies();

if (empty($request['fid'])) {
    // Tìm ra file tương thích để cài
    $request['mode'] = 'getfile';
} else {
    // Tải về file cài đặt
    $request['mode'] = 'install';
    $request['getfile'] = $nv_Request->get_int('getfile', 'get', 0);
}

if (empty($error) and empty($message)) {
    $args = [
        'headers' => [
            'Referer' => NUKEVIET_STORE_APIURL,
        ],
        'cookies' => $stored_cookies,
        'body' => $request
    ];

    $array = $NV_Http->post(NUKEVIET_STORE_APIURL, $args);
    $cookies = $array['cookies'];
    $array = !empty($array['body']) ? @unserialize($array['body']) : [];

    // Tự động chuyển sang bước download file về sau khi tìm ra file tương thích
    if (!empty($array['data']['compatible']['id']) and $request['mode'] == 'getfile') {
        nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=install&id=' . $array['data']['id'] . '&fid=' . $array['data']['compatible']['id'] . '&getfile=1');
    }

    if (!empty(NukeViet\Http\Http::$error)) {
        $error = nv_http_get_lang(NukeViet\Http\Http::$error);
    } elseif (empty($array['status']) or !isset($array['error']) or !isset($array['data']) or !isset($array['pagination']) or !is_array($array['error']) or !is_array($array['data']) or !is_array($array['pagination']) or (!empty($array['error']) and (!isset($array['error']['level']) or empty($array['error']['message'])))) {
        $error = $nv_Lang->getGlobal('error_valid_response');
    } elseif (!empty($array['error']['message'])) {
        $error = $array['error']['message'];
    }
}

$tpl->assign('ERROR', $error);
$tpl->assign('REQUEST', $request);

if (empty($error))  {
    // Save cookies
    nv_store_cookies(nv_object2array($cookies), $stored_cookies);

    if ($request['mode'] != 'getfile') {
        $array = $array['data'];
        unset($array['data']);

        $tpl->assign('DATA', $array);

        $array_string = $array;
        unset($array_string['title'], $array_string['documentation'], $array_string['require']);
        $tpl->assign('STRING_DATA', nv_base64_encode(@serialize($array_string)));

        $page_title = sprintf($nv_Lang->getModule('install_title'), $array['title']);

        if (!empty($array['compatible']['id'])) {
            // Kiểm tra ứng dụng bắt buộc
            $allow_continue = true;
            if (!empty($array['require'])) {
                $require_installed = nv_extensions_is_installed($array['require']['tid'], $array['require']['name'], '');
                if ($require_installed === 0) {
                    $allow_continue = false;
                    $tpl->assign('REQUIRE_MESSAGE', sprintf($nv_Lang->getModule('install_check_require_fail'), $array['require']['title']));
                    $tpl->assign('REQUIRE_LINK', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=detail&amp;id=' . $array['require']['id']);
                    $tpl->assign('REQUIRE_TITLE', sprintf($nv_Lang->getModule('detail_title'), $array['require']['title']));
                }
            }

            $tpl->assign('ALLOW_CONTINUE', $allow_continue);

            if ($allow_continue === true) {
                // Kiểm tra cài đặt tự động được hay không
                if ($array['compatible']['type'] != 1 or !in_array($array['tid'], [1, 2, 3, 4])) {
                    $tpl->assign('MANUAL_MESSAGE', $array['documentation'] ? $nv_Lang->getModule('install_manual_install') : $nv_Lang->getModule('install_manual_install_danger'));
                } else {
                    $tpl->assign('CANCEL_LINK', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name);

                    // Kiểm tra đã cài đặt hay chưa
                    $installed = nv_extensions_is_installed($array['tid'], $array['name'], $array['compatible']['ver']);
                    $tpl->assign('INSTALLED', $installed);
                    $tpl->assign('LOGIN_LINK', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=login&amp;redirect=' . nv_redirect_encrypt($client_info['selfurl']));

                    if ($installed == 1) {
                        $tpl->assign('INSTALLED_MESSAGE', sprintf($nv_Lang->getModule('install_check_installed_error'), NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name));
                    }
                }
            }
        }
    }
}

$contents = $tpl->fetch($op . '.tpl');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
