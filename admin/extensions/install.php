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
$request['id'] = $nv_Request->get_int('id', 'get', 0);
$request['fid'] = $nv_Request->get_int('fid', 'get', 0);

// Fixed request
$request['lang'] = NV_LANG_INTERFACE;
$request['basever'] = $global_config['version'];

$xtpl = new XTemplate($op . '.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('REQUEST', $request);
$xtpl->assign('NV_BASE_ADMINURL', NV_BASE_ADMINURL);
$xtpl->assign('NV_LANG_VARIABLE', NV_LANG_VARIABLE);
$xtpl->assign('NV_LANG_DATA', NV_LANG_DATA);
$xtpl->assign('NV_NAME_VARIABLE', NV_NAME_VARIABLE);
$xtpl->assign('MODULE_NAME', $module_name);

$error = '';
$message = '';

$NV_Http = new NukeViet\Http\Http($global_config, NV_TEMP_DIR);
$stored_cookies = nv_get_cookies();

// Find file
if (empty($request['fid'])) {
    $request['mode'] = 'getfile';
}
// Download file
else {
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

    $cookies = [];
    $array = $NV_Http->post(NUKEVIET_STORE_APIURL, $args);

    if (is_array($array)) {
        $cookies = $array['cookies'];
        $array = !empty($array['body']) ? @unserialize($array['body']) : [];
    } else {
        // Do post có thể trả về object
        $array = [];
    }

    // Next step
    if (!empty($array['data']['compatible']['id']) and $request['mode'] == 'getfile') {
        header('location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=install&id=' . $array['data']['id'] . '&fid=' . $array['data']['compatible']['id'] . '&getfile=1');
        exit();
    }

    if (!empty(NukeViet\Http\Http::$error)) {
        $error = nv_http_get_lang(NukeViet\Http\Http::$error);
    } elseif (empty($array['status']) or !isset($array['error']) or !isset($array['data']) or !isset($array['pagination']) or !is_array($array['error']) or !is_array($array['data']) or !is_array($array['pagination']) or (!empty($array['error']) and (!isset($array['error']['level']) or empty($array['error']['message'])))) {
        $error = $lang_global['error_valid_response'];
    } elseif (!empty($array['error']['message'])) {
        $error = $array['error']['message'];
    }
}

// Show error
if (!empty($error)) {
    $xtpl->assign('ERROR', $error);
    $xtpl->parse('main.error');
} else {
    // Save cookies
    nv_store_cookies(nv_object2array($cookies), $stored_cookies);

    if ($request['mode'] == 'getfile') {
        $xtpl->parse('main.getfile_error');
    } else {
        $array = $array['data'];
        unset($array['data']);

        $xtpl->assign('DATA', $array);

        $array_string = $array;
        unset($array_string['title'], $array_string['documentation'], $array_string['require']);
        $xtpl->assign('STRING_DATA', nv_base64_encode(@serialize($array_string)));

        $page_title = sprintf($lang_module['install_title'], $array['title']);

        // Show getfile info
        if ($request['getfile']) {
            $xtpl->parse('main.install.getfile');
        }

        if (empty($array['compatible']['id'])) {
            $xtpl->parse('main.install.incompatible');
        } else {
            $xtpl->parse('main.install.compatible');

            // Check require plugin
            $allow_continue = true;
            if (!empty($array['require'])) {
                $require_installed = nv_extensions_is_installed($array['require']['tid'], $array['require']['name'], '');

                if ($require_installed === 0) {
                    $allow_continue = false;
                    $xtpl->assign('REQUIRE_MESSAGE', sprintf($lang_module['install_check_require_fail'], $array['require']['title']));
                    $xtpl->assign('REQUIRE_LINK', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=detail&amp;id=' . $array['require']['id']);
                    $xtpl->assign('REQUIRE_TITLE', sprintf($lang_module['detail_title'], $array['require']['title']));

                    $xtpl->parse('main.install.require_noexists');
                } else {
                    $xtpl->parse('main.install.require_exists');
                }
            }

            if ($allow_continue === true) {
                // Check auto install
                if ($array['compatible']['type'] != 1 or !in_array((int) $array['tid'], [1, 2, 3, 4], true)) {
                    $xtpl->assign('MANUAL_MESSAGE', $array['documentation'] ? $lang_module['install_manual_install'] : $lang_module['install_manual_install_danger']);
                    $xtpl->parse('main.install.manual');
                } else {
                    $xtpl->parse('main.install.auto');

                    $xtpl->assign('CANCEL_LINK', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name);

                    // Check installed
                    $installed = nv_extensions_is_installed($array['tid'], $array['name'], $array['compatible']['ver']);

                    if ($installed == 1) {
                        $xtpl->assign('INSTALLED_MESSAGE', sprintf($lang_module['install_check_installed_error'], NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name));
                        $xtpl->parse('main.install.installed');
                    } else {
                        // Da thanh toan
                        if ($array['compatible']['status'] === 'paid') {
                            if ($installed == 2) {
                                $xtpl->parse('main.install.not_install.paid.unsure');
                            } else {
                                $xtpl->parse('main.install.not_install.paid.startdownload');
                            }

                            $xtpl->parse('main.install.not_install.paid');
                        } elseif ($array['compatible']['status'] == 'await') {
                            // Dang thanh toan. Khong cho phep download

                            $xtpl->parse('main.install.not_install.await');
                        } elseif ($array['compatible']['status'] == 'notlogin') {
                            // Dang nhap de kiem tra

                            $xtpl->assign('LOGIN_LINK', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=login&amp;redirect=' . nv_redirect_encrypt($client_info['selfurl']));
                            $xtpl->parse('main.install.not_install.notlogin');
                        } else {
                            // Chua thanh toan, xuat link thanh toan

                            $xtpl->parse('main.install.not_install.unpaid');
                        }

                        $xtpl->parse('main.install.not_install');
                    }
                }
            }
        }

        $xtpl->parse('main.install');
    }
}

$xtpl->parse('main');
$contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
