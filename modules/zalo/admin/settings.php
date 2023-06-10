<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2021 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_IS_FILE_ZALO')) {
    exit('Stop!!!');
}

$zaloWebhookIPs = !empty($global_config['zaloWebhookIPs']) ? $global_config['zaloWebhookIPs'] : [];

if ($nv_Request->get_string('func', 'get', '') == 'access_token_create') {
    $result = $zalo->oa_accesstoken_create(NV_MY_DOMAIN . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=settings&func=accesstoken');

    $xtpl = new XTemplate('settings.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
    if (empty($result)) {
        $err = zaloGetError();
        $xtpl->assign('ERROR', $err);
        $xtpl->parse('isError');
        $contents = $xtpl->text('isError');
        include NV_ROOTDIR . '/includes/header.php';
        echo nv_admin_theme($contents, false);
        include NV_ROOTDIR . '/includes/footer.php';
    } elseif (isset($result['access_token'])) {
        accessTokenUpdate($result);
        $xtpl->assign('RESULT', $result);
        $xtpl->parse('isSuccess');
        $contents = $xtpl->text('isSuccess');
        include NV_ROOTDIR . '/includes/header.php';
        echo nv_admin_theme($contents, false);
        include NV_ROOTDIR . '/includes/footer.php';
    } else {
        $nv_Request->set_Session('oa_code_verifier', $result['code_verifier']);
        nv_redirect_location($result['permission_url']);
    }
}

if ($nv_Request->get_string('func', 'get', '') == 'accesstoken' and $nv_Request->isset_request('code, oa_id', 'get')) {
    $authorization_code = $nv_Request->get_string('code', 'get', '');
    $oa_id = $nv_Request->get_string('oa_id', 'get', '');
    $code_verifier = $nv_Request->get_string('oa_code_verifier', 'session', '');
    $nv_Request->unset_request('oa_code_verifier', 'session');

    $result = $zalo->oa_accesstoken_new($authorization_code, $oa_id, $code_verifier);

    $xtpl = new XTemplate('settings.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
    if (empty($result)) {
        $err = zaloGetError();
        $xtpl->assign('ERROR', $err);
        $xtpl->parse('isError');
        $contents = $xtpl->text('isError');
        include NV_ROOTDIR . '/includes/header.php';
        echo nv_admin_theme($contents, false);
        include NV_ROOTDIR . '/includes/footer.php';
    } else {
        accessTokenUpdate($result);
        $xtpl->assign('RESULT', $result);
        $xtpl->parse('isSuccess');
        $contents = $xtpl->text('isSuccess');
        include NV_ROOTDIR . '/includes/header.php';
        echo nv_admin_theme($contents, false);
        include NV_ROOTDIR . '/includes/footer.php';
    }
}

if ($nv_Request->isset_request('callingcodesSave', 'post')) {
    $callingcodes = [];
    $db_callingcodes2 = [];

    $callcodes = $nv_Request->get_typed_array('callcode', 'post', 'array', []);
    foreach ($callcodes as $name => $codes) {
        $codes = array_filter($codes);
        $codes = array_unique($codes);
        foreach ($codes as $code) {
            $code = (int) $code;
            $callingcodes[$name . $code] = [$code, $name];
            !isset($db_callingcodes2[$code]) && $db_callingcodes2[$code] = [];
            $db_callingcodes2[$code][] = $name . $code;
        }
    }

    $output = '<?php' . "\n\n";
    $output .= NV_FILEHEAD . "\n\n";
    $output .= "if (!defined('NV_MAINFILE')) {\n    exit('Stop!!!');\n}\n\n";

    ksort($callingcodes, SORT_STRING);
    $db_callingcodes = [];
    foreach ($callingcodes as $country => $vals) {
        $db_callingcodes[] = "    '" . $country . "' => ['" . $vals[0] . "', '" . $vals[1] . "']";
    }
    $output .= "\$callingcodes = [\n" . implode(",\n", $db_callingcodes) . "\n];\n\n";

    ksort($db_callingcodes2, SORT_STRING);
    $lines = [];
    foreach ($db_callingcodes2 as $callcode => $country_code) {
        $country_code = implode("', '", $country_code);
        $lines[] = "    '" . $callcode . "' => ['" . $country_code . "']";
    }
    $output .= "\$callingcodes2 = [\n" . implode(",\n", $lines) . "\n];\n";

    file_put_contents(NV_ROOTDIR . '/' . NV_DATADIR . '/callingcodes.php', $output, LOCK_EX);

    $contents = callingcodes_to_html($callingcodes);

    nv_jsonOutput([
        'status' => 'success',
        'content' => $contents
    ]);
}

if ($nv_Request->isset_request('vnsubdivisionsSave, parent', 'post')) {
    require_once NV_ROOTDIR . '/' . NV_DATADIR . '/vnsubdivisions.php';
    $db_provinces = $provinces;
    $db_districts = $districts;

    $parent = $nv_Request->get_title('parent', 'post', '');
    if (!empty($parent) and !isset($provinces[$parent])) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => $nv_Lang->getModule('vnsubdivisions_error')
        ]);
    }

    $subdiv_mainname = $nv_Request->get_typed_array('subdiv_mainname', 'post', 'title', []);
    foreach ($subdiv_mainname as $code => $name) {
        $code = (string) $code;
        $name = trim(strip_tags($name));
        if (empty($name)) {
            nv_jsonOutput([
                'status' => 'error',
                'mess' => $nv_Lang->getModule('vnsubdivisions_title_empty')
            ]);
        }

        if (empty($parent)) {
            $db_provinces[$code] = [$name];
        } else {
            $db_districts[$parent][$code] = [$name];
        }
    }

    $subdiv_othername = $nv_Request->get_typed_array('subdiv_othername', 'post', 'array', []);
    foreach ($subdiv_othername as $code => $names) {
        $code = (string) $code;
        $names = array_filter($names);
        $names = array_map('strip_tags', $names);
        $names = array_map('trim', $names);
        $names = array_unique($names, SORT_LOCALE_STRING);
        if (!empty($names)) {
            foreach ($names as $name) {
                if (empty($parent)) {
                    if (!in_array($name, $db_provinces[$code], true)) {
                        $db_provinces[$code][] = $name;
                    }
                } else {
                    if (!in_array($name, $db_districts[$parent][$code], true)) {
                        $db_districts[$parent][$code][] = $name;
                    }
                }
            }
        }
    }

    $output = '<?php' . "\n\n";
    $output .= NV_FILEHEAD . "\n\n";
    $output .= "if (!defined('NV_MAINFILE')) {\n    exit('Stop!!!');\n}\n\n";

    $output .= "\$provinces = [\n";
    $prs = [];
    foreach ($db_provinces as $code => $name) {
        $name = array_map('addslashes', $name);
        $name = implode("', '", $name);
        $prs[] = "    '" . $code . "' => ['" . $name . "']";
    }
    $output .= implode(",\n", $prs) . "\n";
    $output .= "];\n\n";
    $output .= "\$districts = [\n";
    $strs = [];
    foreach ($db_districts as $code => $unit) {
        $strs[$code] = "    '" . $code . "' => [\n";
        $sts = [];
        foreach ($unit as $_code => $names) {
            $names = array_map('addslashes', $names);
            $names = implode("', '", $names);
            $sts[] = "        '" . $_code . "' => ['" . $names . "']";
        }
        $strs[$code] .= implode(",\n", $sts) . "\n";
        $strs[$code] .= '    ]';
    }
    $output .= implode(",\n", $strs) . "\n";
    $output .= "];\n";

    file_put_contents(NV_ROOTDIR . '/' . NV_DATADIR . '/vnsubdivisions.php', $output, LOCK_EX);

    $data = empty($parent) ? $db_provinces : $db_districts[$parent];

    $contents = vnsubdivisions_to_html($db_provinces, $data, $parent);

    nv_jsonOutput([
        'status' => 'success',
        'content' => $contents
    ]);
}

if ($nv_Request->isset_request('vnsubdivisionsLoad, subdivParent', 'post')) {
    require_once NV_ROOTDIR . '/' . NV_DATADIR . '/vnsubdivisions.php';
    $subdivParent = $nv_Request->get_string('subdivParent', 'post', '');
    if (!empty($subdivParent) and !isset($provinces[$subdivParent])) {
        $subdivParent = '';
    }

    $data = empty($subdivParent) ? $provinces : $districts[$subdivParent];
    $contents = vnsubdivisions_to_html($provinces, $data, $subdivParent);

    echo $contents;
    exit;
}

if ($nv_Request->isset_request('callingcodesLoad', 'post')) {
    require_once NV_ROOTDIR . '/' . NV_DATADIR . '/callingcodes.php';
    $contents = callingcodes_to_html($callingcodes);
    echo $contents;
    exit;
}

$checkss = md5(NV_CHECK_SESSION . '_' . $module_name . '_' . $op . '_' . $admin_info['userid']);
$errormess = '';
$array_config_site = [];

if ($checkss == $nv_Request->get_string('checkss', 'post') and $nv_Request->get_string('func', 'post', '') == 'webhook') {
    $array_config_site['zaloOASecretKey'] = $nv_Request->get_title('zaloOASecretKey', 'post', '');
    $sth = $db->prepare('UPDATE ' . NV_CONFIG_GLOBALTABLE . " SET config_value = :config_value WHERE lang = 'sys' AND module = 'site' AND config_name = :config_name");
    foreach ($array_config_site as $config_name => $config_value) {
        $sth->bindParam(':config_name', $config_name, PDO::PARAM_STR, 30);
        $sth->bindParam(':config_value', $config_value, PDO::PARAM_STR);
        $sth->execute();
    }

    $nv_Cache->delAll(false);
    if (empty($errormess)) {
        nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op . '&action=webhook_setup');
    }
}

if ($checkss == $nv_Request->get_string('checkss', 'post') and $nv_Request->get_string('func', 'post', '') == 'access_token_copy') {
    $result = [
        'access_token' => $nv_Request->get_title('new_access_token', 'post', ''),
        'refresh_token' => $nv_Request->get_title('new_refresh_token', 'post', '')
    ];
    if (!empty($result['access_token']) and !empty($result['refresh_token'])) {
        accessTokenUpdate($result);
    }
    nv_jsonOutput([
        'status' => 'success',
        'mess' => ''
    ]);
}

if ($checkss == $nv_Request->get_string('checkss', 'post') and $nv_Request->get_string('func', 'post', '') == 'webhookIPs') {
    $zaloWebhookIPs = $nv_Request->get_textarea('zaloWebhookIPs', 'post', '');
    $zaloWebhookIPs = !empty($zaloWebhookIPs) ? array_map('trim', explode("\n", $zaloWebhookIPs)) : [];
    if (!empty($zaloWebhookIPs)) {
        $zaloWebhookIPs = array_unique($zaloWebhookIPs);
        $zaloWebhookIPs = array_filter($zaloWebhookIPs, function ($el) {
            return filter_var($el, FILTER_VALIDATE_IP) ? true : false;
        });
        $zaloWebhookIPs = json_encode($zaloWebhookIPs);
    } else {
        $zaloWebhookIPs = '';
    }

    $sth = $db->prepare('UPDATE ' . NV_CONFIG_GLOBALTABLE . " SET config_value = :config_value WHERE lang = 'sys' AND module = 'global' AND config_name = 'zaloWebhookIPs'");
    $sth->bindParam(':config_value', $zaloWebhookIPs, PDO::PARAM_STR);
    $sth->execute();

    nv_save_file_config_global();
    nv_jsonOutput([
        'status' => 'success',
        'mess' => ''
    ]);
}

if ($checkss == $nv_Request->get_string('checkss', 'post') and $nv_Request->get_string('func', 'post', '') == 'zalowebhook_ip_update') {
    $_long = nv_scandir(NV_ROOTDIR . '/' . NV_LOGS_DIR . '/zalo_logs', '/^[0-9]+\.' . nv_preg_quote(NV_LOGS_EXT) . '$/');
    if (!empty($_long)) {
        foreach ($_long as $l) {
            $zaloWebhookIPs[] = trim(file_get_contents(NV_ROOTDIR . '/' . NV_LOGS_DIR . '/zalo_logs/' . $l));
        }
    }

    if (!empty($zaloWebhookIPs)) {
        $zaloWebhookIPs = array_unique($zaloWebhookIPs);
        $zaloWebhookIPs = json_encode($zaloWebhookIPs);
    } else {
        $zaloWebhookIPs = '';
    }

    $sth = $db->prepare('UPDATE ' . NV_CONFIG_GLOBALTABLE . " SET config_value = :config_value WHERE lang = 'sys' AND module = 'global' AND config_name = 'zaloWebhookIPs'");
    $sth->bindParam(':config_value', $zaloWebhookIPs, PDO::PARAM_STR);
    $sth->execute();

    nv_save_file_config_global();
    nv_jsonOutput([
        'status' => 'success',
        'mess' => ''
    ]);
}

if ($checkss == $nv_Request->get_string('checkss', 'post') and $nv_Request->get_string('func', 'post', '') == 'check_zaloip') {
    $expired = NV_CURRENTTIME + 600;
    $sth = $db->prepare('UPDATE ' . NV_CONFIG_GLOBALTABLE . " SET config_value = :config_value WHERE lang = 'sys' AND module = 'global' AND config_name = 'check_zaloip_expired'");
    $sth->bindParam(':config_value', $expired, PDO::PARAM_STR);
    $sth->execute();
    nv_save_file_config_global();
    echo 'OK';
    exit();
}

if ($checkss == $nv_Request->get_string('checkss', 'post') and $nv_Request->get_string('func', 'post', '') == 'settings') {
    $array_config_site['zaloOfficialAccountID'] = $nv_Request->get_title('zaloOfficialAccountID', 'post', '');
    $array_config_site['zaloOfficialAccountID'] = preg_replace('/[^0-9]/', '', $array_config_site['zaloOfficialAccountID']);
    $array_config_site['zaloAppID'] = $nv_Request->get_title('zaloAppID', 'post', '');
    $array_config_site['zaloAppID'] = preg_replace('/[^0-9]/', '', $array_config_site['zaloAppID']);
    $array_config_site['zaloAppSecretKey'] = $nv_Request->get_title('zaloAppSecretKey', 'post', '');
    $array_config_site['zaloAppSecretKey'] = preg_replace('/[^a-zA-Z0-9\_\-]/', '', $array_config_site['zaloAppSecretKey']);

    $sth = $db->prepare('UPDATE ' . NV_CONFIG_GLOBALTABLE . " SET config_value = :config_value WHERE lang = 'sys' AND module = 'site' AND config_name = :config_name");
    foreach ($array_config_site as $config_name => $config_value) {
        $sth->bindParam(':config_name', $config_name, PDO::PARAM_STR, 30);
        $sth->bindParam(':config_value', $config_value, PDO::PARAM_STR);
        $sth->execute();
    }

    $nv_Cache->delAll(false);
    if (empty($errormess)) {
        nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op . '&action=general_settings');
    }
}

$global_config['checkss'] = $checkss;

require_once NV_ROOTDIR . '/' . NV_DATADIR . '/vnsubdivisions.php';

$action = $nv_Request->get_string('action', 'get', 'general_settings');
$subdiv_parent = $nv_Request->get_string('subdiv', 'get', '');
if (!empty($subdiv_parent) and !isset($provinces[$subdiv_parent])) {
    $subdiv_parent = '';
}

$global_config['zaloWebhookIPs_format'] = !empty($zaloWebhookIPs) ? implode("\n", $zaloWebhookIPs) : '';

$nv_Lang->setModule('access_token_copy_note', $nv_Lang->getModule('access_token_copy_note', 'https://developers.zalo.me/tools/explorer/' . $global_config['zaloAppID'], 'https://developers.zalo.me/docs/api/official-account-api/xac-thuc-va-uy-quyen/cach-2-xac-thuc-voi-cong-cu-api-explorer/phuong-thuc-lay-access-token-su-dung-cong-cu-api-explorer-post-5004'));
$nv_Lang->setModule('zalowebhook_ip_check_note', $nv_Lang->getModule('zalowebhook_ip_check_note', 'https://developers.zalo.me/app/' . $global_config['zaloAppID'] . '/webhook'));

$xtpl = new XTemplate('settings.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);

$nv_Lang->setModule('oa_create_note', $nv_Lang->getModule('oa_create_note', 'https://oa.zalo.me/manage/oa?option=create', 'https://oa.zalo.me/manage/oa'));
$nv_Lang->setModule('app_note', $nv_Lang->getModule('app_note', 'https://developers.zalo.me/createapp', 'https://developers.zalo.me/apps', NV_MY_DOMAIN . NV_BASE_ADMINURL . 'index.php', NV_MY_DOMAIN, NV_MY_DOMAIN . NV_BASE_SITEURL . 'index.php', NV_MY_DOMAIN . NV_BASE_ADMINURL . 'index.php'));
$nv_Lang->setModule('webhook_note', $nv_Lang->getModule('webhook_note', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=settings&amp;' . NV_OP_VARIABLE . '=plugin', 'https://developers.zalo.me/apps', NV_MY_DOMAIN . NV_BASE_SITEURL . '?zalo=' . $global_config['zaloAppID']));
$xtpl->assign('LANG', \NukeViet\Core\Language::$lang_module);
$xtpl->assign('GLANG', \NukeViet\Core\Language::$lang_global);
$xtpl->assign('DATA', $global_config);
$xtpl->assign('MODULE_NAME', $module_name);
$xtpl->assign('PAGE_LINK', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op);
$xtpl->assign('SUBDIV_PARENT', $subdiv_parent);
$xtpl->assign('OP', $op);

if ($errormess != '') {
    $xtpl->assign('ERROR', $errormess);
    $xtpl->parse('main.error');
}

if (!empty($global_config['zaloOfficialAccountID']) and !empty($global_config['zaloAppID']) and !empty($global_config['zaloAppID'])) {
    $xtpl->parse('main.webhook_is_allowed');
    $xtpl->parse('main.access_token_is_allowed');
} else {
    $xtpl->parse('main.webhook_not_allowed');
    $xtpl->parse('main.access_token_not_allowed');
}

$norm = 5242880;
$norm_format = nv_convertfromBytes($norm);
$allow_files = ['adobe', 'documents', 'images'];
$finally = true;

$upload_max_filesize = nv_converttoBytes(ini_get('upload_max_filesize'));
$upload_max_filesize_suitable = $upload_max_filesize >= $norm;
$upload_max_filesize_format = nv_convertfromBytes($upload_max_filesize);
if (!$upload_max_filesize_suitable) {
    $finally = false;
}
$post_max_size = nv_converttoBytes(ini_get('post_max_size'));
$post_max_size_suitable = $post_max_size >= $norm;
$post_max_size_format = nv_convertfromBytes($post_max_size);
if (!$post_max_size_suitable) {
    $finally = false;
}
$nv_max_size = (int) $global_config['nv_max_size'];
$nv_max_size_suitable = $nv_max_size >= $norm;
$nv_max_size_format = nv_convertfromBytes($nv_max_size);
if (!$nv_max_size_suitable) {
    $finally = false;
}
$file_allowed_ext = $global_config['file_allowed_ext'];
$file_allowed_ext_current = array_intersect($allow_files, $file_allowed_ext);
$file_allowed_ext_suitable = $file_allowed_ext_current == $allow_files;
if (!$file_allowed_ext_suitable) {
    $finally = false;
}
$check = [
    'upload_max_filesize' => [
        'key' => 'upload_max_filesize',
        'required' => $norm_format,
        'current' => $upload_max_filesize_format,
        'suitable' => $upload_max_filesize_suitable,
        'suitable_info' => $upload_max_filesize_suitable ? $nv_Lang->getModule('suitable') : $nv_Lang->getModule('notsuitable'),
        'recommendation' => $upload_max_filesize_suitable ? '' : $nv_Lang->getModule('upload_max_filesize_not_suitable')
    ],
    'post_max_size' => [
        'key' => 'post_max_size',
        'required' => $norm_format,
        'current' => $post_max_size_format,
        'suitable' => $post_max_size_suitable,
        'suitable_info' => $post_max_size_suitable ? $nv_Lang->getModule('suitable') : $nv_Lang->getModule('notsuitable'),
        'recommendation' => $post_max_size_suitable ? '' : $nv_Lang->getModule('post_max_size_not_suitable')
    ],
    'nv_max_size' => [
        'key' => $nv_Lang->getModule('nv_max_size'),
        'required' => $norm_format,
        'current' => $nv_max_size_format,
        'suitable' => $nv_max_size_suitable,
        'suitable_info' => $nv_max_size_suitable ? $nv_Lang->getModule('suitable') : $nv_Lang->getModule('notsuitable'),
        'recommendation' => $nv_max_size_suitable ? '' : $nv_Lang->getModule('nv_max_size_not_suitable', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=upload&' . NV_OP_VARIABLE . '=uploadconfig')
    ],
    'file_allowed_ext' => [
        'key' => $nv_Lang->getModule('file_allowed_ext'),
        'required' => implode(', ', $allow_files),
        'current' => implode(', ', $file_allowed_ext_current),
        'suitable' => $file_allowed_ext_suitable,
        'suitable_info' => $file_allowed_ext_suitable ? $nv_Lang->getModule('suitable') : $nv_Lang->getModule('notsuitable'),
        'recommendation' => $file_allowed_ext_suitable ? '' : $nv_Lang->getModule('file_allowed_ext_not_suitable', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=upload&' . NV_OP_VARIABLE . '=uploadconfig')
    ]
];

foreach ($check as $ch) {
    $xtpl->assign('CHECK', $ch);
    if ($ch['suitable']) {
        $xtpl->parse('main.system_check.suitable');
    } else {
        $xtpl->parse('main.system_check.notsuitable');
    }
    $xtpl->parse('main.system_check');
}

if ($finally) {
    $xtpl->parse('main.suitable');
    $xtpl->parse('main.suitable2');
} else {
    $xtpl->parse('main.notsuitable');
    $xtpl->parse('main.notsuitable2');
}

if (!empty($action) and in_array($action, ['general_settings', 'webhook_setup', 'access_token_create', 'system_check', 'vnsubdivisions', 'callingcodes'], true)) {
    $xtpl->assign('ACTION', $action);
    $xtpl->parse('main.action');
}

$xtpl->parse('main');
$contents = $xtpl->text('main');

$page_title = $nv_Lang->getModule('settings');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
