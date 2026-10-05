<?php

/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_IS_FILE_ZALO')) {
    exit('Stop!!!');
}

$zaloWebhookIPs = !empty($global_config['zaloWebhookIPs']) ? $global_config['zaloWebhookIPs'] : [];
$page_url = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op;

// Tạo access token trong cửa sổ popup: chuyển sang trang cấp quyền của Zalo, sau đó Zalo gọi lại với func=accesstoken
$get_func = $nv_Request->get_string('func', 'get', '');
if ($get_func == 'access_token_create' or ($get_func == 'accesstoken' and $nv_Request->isset_request('code, oa_id', 'get'))) {
    if ($get_func == 'access_token_create') {
        $result = $myZalo->oa_accesstoken_create(NV_MY_DOMAIN . $page_url . '&func=accesstoken');
        if (!empty($result) and !isset($result['access_token'])) {
            $nv_Request->set_Session('oa_code_verifier', $result['code_verifier']);
            nv_redirect_location($result['permission_url']);
        }
    } else {
        $codeVerifier = $nv_Request->get_string('oa_code_verifier', 'session', '');
        $nv_Request->unset_request('oa_code_verifier', 'session');
        $result = $myZalo->accesstokenGet($codeVerifier);
    }

    $token_result = [
        'status' => 'error',
        'mess' => ''
    ];
    if (empty($result)) {
        $token_result['mess'] = nv_htmlspecialchars(zaloGetError());
    } else {
        accessTokenUpdate($result);
        nv_insert_logs(NV_LANG_DATA, $module_name, $nv_Lang->getModule('access_token_create'), '', $admin_info['userid']);
        $token_result['status'] = 'success';
    }

    $tpl = new \NukeViet\Template\NVSmarty();
    $tpl->setTemplateDir(get_module_tpl_dir('settings-token-result.tpl'));
    $tpl->assign('LANG', $nv_Lang);
    $tpl->assign('RESULT', $token_result);

    $contents = $tpl->fetch('settings-token-result.tpl');

    include NV_ROOTDIR . '/includes/header.php';
    echo nv_admin_theme($contents, false);
    include NV_ROOTDIR . '/includes/footer.php';
}

// Lưu mã gọi quốc gia
if ($nv_Request->isset_request('callingcodesSave', 'post')) {
    if (!csrf_check($nv_Request->get_string('checkss', 'post'), $csrf_key)) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => $nv_Lang->getGlobal('error_checkss')
        ]);
    }

    $callingcodes = [];
    $db_callingcodes2 = [];

    $callcodes = $nv_Request->get_typed_array('callcode', 'post', 'array', []);
    foreach ($callcodes as $name => $codes) {
        if (!preg_match('/^[A-Z0-9]+$/', $name)) {
            continue;
        }
        $codes = array_unique(array_filter(array_map('intval', (array) $codes)));
        if (empty($codes)) {
            nv_jsonOutput([
                'status' => 'error',
                'mess' => $nv_Lang->getModule('country_callcode_error'),
                'input' => 'callcode[' . $name . ']'
            ]);
        }
        foreach ($codes as $code) {
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
    nv_insert_logs(NV_LANG_DATA, $module_name, $nv_Lang->getModule('callingcodes_settings'), '', $admin_info['userid']);

    nv_jsonOutput([
        'status' => 'OK',
        'mess' => $nv_Lang->getGlobal('save_success'),
        'redirect' => nv_url_rewrite($page_url . '&action=callingcodes', true)
    ]);
}

// Lưu các đơn vị hành chính
if ($nv_Request->isset_request('vnsubdivisionsSave, parent', 'post')) {
    if (!csrf_check($nv_Request->get_string('checkss', 'post'), $csrf_key)) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => $nv_Lang->getGlobal('error_checkss')
        ]);
    }

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
        if (empty($name) or !preg_match('/^[A-Z0-9]+$/', $code)) {
            nv_jsonOutput([
                'status' => 'error',
                'mess' => $nv_Lang->getModule('vnsubdivisions_title_empty'),
                'input' => 'subdiv_mainname[' . $code . ']'
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
        if (!empty($names) and preg_match('/^[A-Z0-9]+$/', $code)) {
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
    nv_insert_logs(NV_LANG_DATA, $module_name, $nv_Lang->getModule('vnsubdivisions_settings'), $parent, $admin_info['userid']);

    nv_jsonOutput([
        'status' => 'OK',
        'mess' => $nv_Lang->getGlobal('save_success'),
        'redirect' => nv_url_rewrite($page_url . '&action=vnsubdivisions' . (!empty($parent) ? '&subdiv=' . $parent : ''), true)
    ]);
}

// Tải nội dung tab các đơn vị hành chính
if ($nv_Request->isset_request('vnsubdivisionsLoad, subdivParent', 'post')) {
    require_once NV_ROOTDIR . '/' . NV_DATADIR . '/vnsubdivisions.php';
    $subdivParent = $nv_Request->get_string('subdivParent', 'post', '');
    if (!empty($subdivParent) and !isset($provinces[$subdivParent])) {
        $subdivParent = '';
    }

    $array_provinces = [];
    foreach ($provinces as $code => $names) {
        $array_provinces[] = [
            'code' => (string) $code,
            'name' => nv_htmlspecialchars($names[0])
        ];
    }

    $array_subdivs = [];
    $data = empty($subdivParent) ? $provinces : $districts[$subdivParent];
    foreach ($data as $code => $names) {
        $mainname = array_shift($names);
        $array_subdivs[] = [
            'code' => (string) $code,
            'code_format' => (!empty($subdivParent) ? $subdivParent . '-' : '') . $code,
            'mainname' => nv_htmlspecialchars($mainname),
            'othernames' => empty($names) ? [''] : nv_htmlspecialchars(array_values($names))
        ];
    }

    $tpl = new \NukeViet\Template\NVSmarty();
    $tpl->setTemplateDir(get_module_tpl_dir('settings-vnsubdivisions.tpl'));
    $tpl->assign('LANG', $nv_Lang);
    $tpl->assign('MODULE_NAME', $module_name);
    $tpl->assign('OP', $op);
    $tpl->assign('CHECKSS', csrf_create($csrf_key));
    $tpl->assign('PARENT', $subdivParent);
    $tpl->assign('PROVINCES', $array_provinces);
    $tpl->assign('SUBDIVS', $array_subdivs);

    echo $tpl->fetch('settings-vnsubdivisions.tpl');
    exit();
}

// Tải nội dung tab mã gọi quốc gia
if ($nv_Request->isset_request('callingcodesLoad', 'post')) {
    require_once NV_ROOTDIR . '/' . NV_DATADIR . '/callingcodes.php';

    $countries = [];
    foreach ($callingcodes as $country) {
        if (!isset($countries[$country[1]])) {
            $countries[$country[1]] = [
                'code' => $country[1],
                'name' => $nv_Lang->existsGlobal('country_' . $country[1]) ? $nv_Lang->getGlobal('country_' . $country[1]) : $country[1],
                'callcodes' => []
            ];
        }
        $countries[$country[1]]['callcodes'][] = $country[0];
    }

    $tpl = new \NukeViet\Template\NVSmarty();
    $tpl->setTemplateDir(get_module_tpl_dir('settings-callingcodes.tpl'));
    $tpl->assign('LANG', $nv_Lang);
    $tpl->assign('MODULE_NAME', $module_name);
    $tpl->assign('OP', $op);
    $tpl->assign('CHECKSS', csrf_create($csrf_key));
    $tpl->assign('COUNTRIES', $countries);

    echo $tpl->fetch('settings-callingcodes.tpl');
    exit();
}

$func = $nv_Request->get_string('func', 'post', '');
$checkss = $nv_Request->get_string('checkss', 'post', '');

if (!empty($func) and !csrf_check($checkss, $csrf_key)) {
    nv_jsonOutput([
        'status' => 'error',
        'mess' => $nv_Lang->getGlobal('error_checkss')
    ]);
}

// Lưu khóa bí mật của OA
if ($func == 'webhook') {
    $sth = $db->prepare('UPDATE ' . NV_CONFIG_GLOBALTABLE . " SET config_value = :config_value WHERE lang = 'sys' AND module = 'site' AND config_name = 'zaloOASecretKey'");
    $sth->bindValue(':config_value', $nv_Request->get_title('zaloOASecretKey', 'post', ''), PDO::PARAM_STR);
    $sth->execute();

    $nv_Cache->delAll(false);
    nv_insert_logs(NV_LANG_DATA, $module_name, $nv_Lang->getModule('webhook_setup'), $nv_Lang->getModule('oa_secrect_key'), $admin_info['userid']);

    nv_jsonOutput([
        'status' => 'OK',
        'mess' => $nv_Lang->getGlobal('save_success'),
        'redirect' => nv_url_rewrite($page_url . '&action=webhook_setup', true)
    ]);
}

// Lưu access token sao chép từ trình tạo mã của Zalo
if ($func == 'access_token_copy') {
    $result = [
        'access_token' => $nv_Request->get_title('new_access_token', 'post', ''),
        'refresh_token' => $nv_Request->get_title('new_refresh_token', 'post', '')
    ];
    if (empty($result['access_token'])) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => $nv_Lang->getGlobal('required_invalid'),
            'input' => 'new_access_token'
        ]);
    }
    if (empty($result['refresh_token'])) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => $nv_Lang->getGlobal('required_invalid'),
            'input' => 'new_refresh_token'
        ]);
    }

    accessTokenUpdate($result);
    nv_insert_logs(NV_LANG_DATA, $module_name, $nv_Lang->getModule('access_token_copy'), '', $admin_info['userid']);

    nv_jsonOutput([
        'status' => 'OK',
        'mess' => $nv_Lang->getGlobal('save_success'),
        'redirect' => nv_url_rewrite($page_url . '&action=access_token_create', true)
    ]);
}

// Lưu danh sách IP của Zalo Webhook nhập thủ công
if ($func == 'webhookIPs') {
    $zaloWebhookIPs = $nv_Request->get_textarea('zaloWebhookIPs', 'post', '');
    $zaloWebhookIPs = !empty($zaloWebhookIPs) ? array_map('trim', explode("\n", $zaloWebhookIPs)) : [];
    if (!empty($zaloWebhookIPs)) {
        $zaloWebhookIPs = array_unique($zaloWebhookIPs);
        $zaloWebhookIPs = array_filter($zaloWebhookIPs, function ($el) {
            return filter_var($el, FILTER_VALIDATE_IP) ? true : false;
        });
        $zaloWebhookIPs = json_encode($zaloWebhookIPs, NV_JSON_ENCODE);
    } else {
        $zaloWebhookIPs = '';
    }

    $sth = $db->prepare('UPDATE ' . NV_CONFIG_GLOBALTABLE . " SET config_value = :config_value WHERE lang = 'sys' AND module = 'global' AND config_name = 'zaloWebhookIPs'");
    $sth->bindValue(':config_value', $zaloWebhookIPs, PDO::PARAM_STR);
    $sth->execute();

    nv_save_file_config_global();
    nv_insert_logs(NV_LANG_DATA, $module_name, $nv_Lang->getModule('zalowebhook_ips'), '', $admin_info['userid']);

    nv_jsonOutput([
        'status' => 'OK',
        'mess' => $nv_Lang->getGlobal('save_success'),
        'redirect' => nv_url_rewrite($page_url . '&action=webhook_setup', true)
    ]);
}

// Cập nhật IP của Zalo Webhook từ log ghi nhận được trong thời gian kiểm tra
if ($func == 'zalowebhook_ip_update') {
    $_long = nv_scandir(NV_ROOTDIR . '/' . NV_LOGS_DIR . '/zalo_logs', '/^[0-9]+\.' . nv_preg_quote(NV_LOGS_EXT) . '$/');
    if (!empty($_long)) {
        foreach ($_long as $l) {
            $zaloWebhookIPs[] = trim(file_get_contents(NV_ROOTDIR . '/' . NV_LOGS_DIR . '/zalo_logs/' . $l));
        }
    }

    if (!empty($zaloWebhookIPs)) {
        $zaloWebhookIPs = array_unique($zaloWebhookIPs);
        $zaloWebhookIPs = json_encode($zaloWebhookIPs, NV_JSON_ENCODE);
    } else {
        $zaloWebhookIPs = '';
    }

    $sth = $db->prepare('UPDATE ' . NV_CONFIG_GLOBALTABLE . " SET config_value = :config_value WHERE lang = 'sys' AND module = 'global' AND config_name = 'zaloWebhookIPs'");
    $sth->bindValue(':config_value', $zaloWebhookIPs, PDO::PARAM_STR);
    $sth->execute();

    nv_save_file_config_global();
    nv_insert_logs(NV_LANG_DATA, $module_name, $nv_Lang->getModule('zalowebhook_ips'), $nv_Lang->getModule('zalowebhook_ip_update'), $admin_info['userid']);

    nv_jsonOutput([
        'status' => 'OK',
        'mess' => $nv_Lang->getGlobal('save_success'),
        'redirect' => nv_url_rewrite($page_url . '&action=webhook_setup', true)
    ]);
}

// Bật chế độ ghi nhận IP của Zalo Webhook trong 10 phút
if ($func == 'check_zaloip') {
    $sth = $db->prepare('UPDATE ' . NV_CONFIG_GLOBALTABLE . " SET config_value = :config_value WHERE lang = 'sys' AND module = 'global' AND config_name = 'check_zaloip_expired'");
    $sth->bindValue(':config_value', NV_CURRENTTIME + 600, PDO::PARAM_STR);
    $sth->execute();
    nv_save_file_config_global();

    nv_jsonOutput([
        'status' => 'OK',
        'mess' => ''
    ]);
}

// Lưu cấu hình chung
if ($func == 'settings') {
    $array_config_site = [];
    $array_config_site['zaloOfficialAccountID'] = preg_replace('/[^0-9]/', '', $nv_Request->get_title('zaloOfficialAccountID', 'post', ''));
    $array_config_site['zaloAppID'] = preg_replace('/[^0-9]/', '', $nv_Request->get_title('zaloAppID', 'post', ''));
    $array_config_site['zaloAppSecretKey'] = preg_replace('/[^a-zA-Z0-9\_\-]/', '', $nv_Request->get_title('zaloAppSecretKey', 'post', ''));

    $sth = $db->prepare('UPDATE ' . NV_CONFIG_GLOBALTABLE . " SET config_value = :config_value WHERE lang = 'sys' AND module = 'site' AND config_name = :config_name");
    foreach ($array_config_site as $config_name => $config_value) {
        $sth->bindValue(':config_name', $config_name, PDO::PARAM_STR);
        $sth->bindValue(':config_value', $config_value, PDO::PARAM_STR);
        $sth->execute();
    }

    $nv_Cache->delAll(false);
    nv_insert_logs(NV_LANG_DATA, $module_name, $nv_Lang->getModule('general_settings'), '', $admin_info['userid']);

    nv_jsonOutput([
        'status' => 'OK',
        'mess' => $nv_Lang->getGlobal('save_success'),
        'redirect' => nv_url_rewrite($page_url . '&action=general_settings', true)
    ]);
}

require_once NV_ROOTDIR . '/' . NV_DATADIR . '/vnsubdivisions.php';

$array_actions = ['general_settings', 'access_token_create', 'webhook_setup', 'system_check', 'vnsubdivisions', 'callingcodes'];
$action = $nv_Request->get_title('action', 'get', '');
if (!in_array($action, $array_actions, true)) {
    $action = 'general_settings';
}
$subdiv_parent = $nv_Request->get_string('subdiv', 'get', '');
if (!empty($subdiv_parent) and !isset($provinces[$subdiv_parent])) {
    $subdiv_parent = '';
}

// Kiểm tra tính tương thích của hệ thống với việc tải tập tin lên Zalo
$norm = 5242880;
$norm_format = nv_convertfromBytes($norm);
$allow_files = ['adobe', 'documents', 'images'];
$upload_config_url = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=upload&amp;' . NV_OP_VARIABLE . '=uploadconfig';

$upload_max_filesize = nv_converttoBytes(ini_get('upload_max_filesize'));
$post_max_size = nv_converttoBytes(ini_get('post_max_size'));
$nv_max_size = (int) $global_config['nv_max_size'];
$file_allowed_ext_current = array_intersect($allow_files, $global_config['file_allowed_ext']);

$system_check = [
    [
        'key' => 'upload_max_filesize',
        'required' => $norm_format,
        'current' => nv_convertfromBytes($upload_max_filesize),
        'suitable' => $upload_max_filesize >= $norm,
        'recommendation' => $nv_Lang->getModule('upload_max_filesize_not_suitable')
    ],
    [
        'key' => 'post_max_size',
        'required' => $norm_format,
        'current' => nv_convertfromBytes($post_max_size),
        'suitable' => $post_max_size >= $norm,
        'recommendation' => $nv_Lang->getModule('post_max_size_not_suitable')
    ],
    [
        'key' => $nv_Lang->getModule('nv_max_size'),
        'required' => $norm_format,
        'current' => nv_convertfromBytes($nv_max_size),
        'suitable' => $nv_max_size >= $norm,
        'recommendation' => $nv_Lang->getModule('nv_max_size_not_suitable', $upload_config_url)
    ],
    [
        'key' => $nv_Lang->getModule('file_allowed_ext'),
        'required' => implode(', ', $allow_files),
        'current' => implode(', ', $file_allowed_ext_current),
        'suitable' => $file_allowed_ext_current == $allow_files,
        'recommendation' => $nv_Lang->getModule('file_allowed_ext_not_suitable', $upload_config_url)
    ]
];
$system_suitable = !in_array(false, array_column($system_check, 'suitable'), true);

// Webhook và access token chỉ thiết lập được khi đã khai báo đủ OAID, ID ứng dụng và khóa bí mật của ứng dụng
$is_allowed = (!empty($global_config['zaloOfficialAccountID']) and !empty($global_config['zaloAppID']) and !empty($global_config['zaloAppSecretKey']));

// Trạng thái các bước thiết lập, hiển thị ở thanh tiến độ
$setup_status = [
    'general_settings' => $is_allowed,
    'access_token_create' => !empty($global_config['zaloOAAccessToken']),
    'webhook_setup' => (!empty($global_config['zaloOASecretKey']) and !empty($zaloWebhookIPs)),
    'system_check' => $system_suitable
];

$nv_Lang->setModule('access_token_copy_note', $nv_Lang->getModule('access_token_copy_note', 'https://developers.zalo.me/tools/explorer/' . $global_config['zaloAppID'], 'https://developers.zalo.me/docs/api/official-account-api/xac-thuc-va-uy-quyen/cach-2-xac-thuc-voi-cong-cu-api-explorer/phuong-thuc-lay-access-token-su-dung-cong-cu-api-explorer-post-5004'));
$nv_Lang->setModule('zalowebhook_ip_check_note', $nv_Lang->getModule('zalowebhook_ip_check_note', 'https://developers.zalo.me/app/' . $global_config['zaloAppID'] . '/webhook'));
$nv_Lang->setModule('oa_create_note', $nv_Lang->getModule('oa_create_note', 'https://oa.zalo.me/manage/oa?option=create', 'https://oa.zalo.me/manage/oa'));
$nv_Lang->setModule('app_note', $nv_Lang->getModule('app_note', 'https://developers.zalo.me/createapp', 'https://developers.zalo.me/apps', NV_MY_DOMAIN . NV_BASE_ADMINURL . 'index.php', NV_MY_DOMAIN, NV_MY_DOMAIN . NV_BASE_SITEURL . 'index.php', NV_MY_DOMAIN . NV_BASE_ADMINURL . 'index.php'));
$nv_Lang->setModule('webhook_note', $nv_Lang->getModule('webhook_note', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=settings&amp;' . NV_OP_VARIABLE . '=plugin', 'https://developers.zalo.me/apps', NV_MY_DOMAIN . NV_BASE_SITEURL . '?zalo=' . $global_config['zaloAppID']));

$tpl = new \NukeViet\Template\NVSmarty();
$tpl->setTemplateDir(get_module_tpl_dir('settings.tpl'));
$tpl->assign('LANG', $nv_Lang);
$tpl->assign('MODULE_NAME', $module_name);
$tpl->assign('OP', $op);
$tpl->assign('CHECKSS', csrf_create($csrf_key));
$tpl->assign('GCONFIG', $global_config);
$tpl->assign('ACTION', $action);
$tpl->assign('SUBDIV_PARENT', $subdiv_parent);
$tpl->assign('IS_ALLOWED', $is_allowed);
$tpl->assign('SETUP_STATUS', $setup_status);
$tpl->assign('SYSTEM_CHECK', $system_check);
$tpl->assign('SYSTEM_SUITABLE', $system_suitable);
$tpl->assign('WEBHOOK_IPS', !empty($zaloWebhookIPs) ? implode("\n", $zaloWebhookIPs) : '');

$contents = $tpl->fetch('settings.tpl');

$page_title = $nv_Lang->getModule('settings');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
