<?php

/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_MOD_2STEP_VERIFICATION')) {
    exit('Stop!!!');
}

$page_title = $module_info['site_title'];
$key_words = $module_info['keywords'];
$page_url = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name;

// Tự động chuyển đến trang thiết lập nếu hệ thống bắt buộc xác thực ở quản trị, hoặc tất cả các khu vực
if (empty($user_info['active2step']) and in_array((int) $global_config['two_step_verification'], [1, 3], true)) {
    nv_redirect_location(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=setup');
}

$array_data = [];
// checkss khớp với modules/users/funcs/editinfo.php thay đổi cần cập nhật
$array_data['checkss'] = md5(NV_CHECK_SESSION . '_' . NV_BRIDGE_USER_MODULE . '_editinfo_' . $user_info['userid']);
$array_data['form_url'] = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . NV_BRIDGE_USER_MODULE . '&amp;' . NV_OP_VARIABLE . '=editinfo/passkey';
$array_data['page_url'] = $page_url;
$array_data['show_type'] = $nv_Request->get_title('type', 'get', '');
$array_data['publicKeys'] = [];
$array_data['login_keys'] = 0;
$array_data['security_keys'] = 0;
$array_data['pref_2fa'] = $user_info['pref_2fa'];

// Lấy danh sách khóa đăng nhập, khóa bảo mật
$sql = 'SELECT id, keyid, created_at, last_used_at, clid, enable_login, nickname
FROM ' . $db_config['prefix'] . '_' . $site_mods[NV_BRIDGE_USER_MODULE]['module_data'] . '_passkey WHERE userid=' . $user_info['userid'];
$result = $db->query($sql);
while ($_row = $result->fetch()) {
    $array_data['publicKeys'][$_row['keyid']] = $_row;
    if (!empty($_row['enable_login'])) {
        $array_data['login_keys']++;
    } else {
        $array_data['security_keys']++;
    }
}
$result->closeCursor();

// Lưu phương thức xác thực 2 bước ưu thích
if ($nv_Request->isset_request('change_preferred_2fa', 'post')) {
    if (!defined('NV_IS_AJAX') or $nv_Request->get_title('change_preferred_2fa', 'post', '') !== NV_CHECK_SESSION) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => 'Not allowed!'
        ]);
    }
    $pref_2fa = $nv_Request->get_int('pref_2fa', 'post', 0);
    if (!in_array($pref_2fa, [1, 2], true)) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => 'Invalid preferred 2fa!'
        ]);
    }
    if (empty($user_info['active2step'])) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => 'Please enable 2-step verification first!'
        ]);
    }
    if ($pref_2fa == 2 and empty($array_data['publicKeys'])) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => 'Please add a security key or passkey first!'
        ]);
    }
    if ($pref_2fa != $user_info['pref_2fa']) {
        $sql = 'UPDATE ' . $db_config['prefix'] . '_' . $site_mods[NV_BRIDGE_USER_MODULE]['module_data'] . ' SET
            pref_2fa=' . $pref_2fa . ', last_update=' . NV_CURRENTTIME . '
        WHERE userid=' . $user_info['userid'];
        $db->query($sql);
        nv_insert_logs(NV_LANG_DATA, $module_name, 'log_change_pref_2fa', $pref_2fa, $user_info['userid']);
    }
    nv_jsonOutput([
        'status' => 'ok',
        'mess' => 'OK'
    ]);
}

/*
 * Tắt xác thực hai bước
 * Lưu ý quan trọng: Chỉ tài khoản thành viên đã full xác thực mới có thể tắt!
 * Không cho phép tắt nếu tài khoản này mới chỉ login 1 bước
 */
if ($nv_Request->isset_request('turnoff2step', 'post')) {
    $tokend = $nv_Request->get_title('tokend', 'post', '');
    if (!defined('NV_IS_AJAX') or $tokend != NV_CHECK_SESSION or !defined('NV_IS_USER')) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => 'Not allowed!'
        ]);
    }

    $sql = 'UPDATE ' . $db_config['prefix'] . '_' . $site_mods[NV_BRIDGE_USER_MODULE]['module_data'] . ' SET
        active2step=0, secretkey=\'\', last_update=' . NV_CURRENTTIME . '
    WHERE userid=' . $user_info['userid'];
    $db->query($sql);

    // Xóa security keys
    $sql = 'DELETE FROM ' . $db_config['prefix'] . '_' . $site_mods[NV_BRIDGE_USER_MODULE]['module_data'] . '_passkey WHERE userid=' . $user_info['userid'] . ' AND enable_login=0';
    $db->query($sql);

    // Xóa mã dự phòng
    $sql = 'DELETE FROM ' . $db_config['prefix'] . '_' . $site_mods[NV_BRIDGE_USER_MODULE]['module_data'] . '_backupcodes WHERE userid=' . $user_info['userid'];
    $db->query($sql);

    // Gửi email thông báo bảo mật
    $send_data = [[
        'to' => $user_info['email'],
        'data' => [
            'greeting_user' => greeting_for_user_create($user_info['username'], $user_info['first_name'], $user_info['last_name'], $user_info['gender']),
            'Home' => urlRewriteWithDomain(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA, NV_MY_DOMAIN),
            'time' => nv_datetime_format(NV_CURRENTTIME, 0, 0),
            'ip' => NV_CLIENT_IP,
            'browser' => NV_USER_AGENT,
            'link' => urlRewriteWithDomain(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name, NV_MY_DOMAIN)
        ]
    ]];
    nv_sendmail_template_async([$module_file, NukeViet\Template\Email\Tpl2Step::DEACTIVATE_2STEP], $send_data);
    nv_insert_logs(NV_LANG_DATA, $module_name, 'log_deactive_2step', '', $user_info['userid']);
    nv_jsonOutput([
        'status' => 'ok',
        'mess' => 'OK'
    ]);
}

// Tạo lại mã dự phòng
if ($nv_Request->isset_request('changecode2step', 'post')) {
    $tokend = $nv_Request->get_title('tokend', 'post', '');
    if (!defined('NV_IS_AJAX') or $tokend != NV_CHECK_SESSION) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => 'Not allowed!'
        ]);
    }
    nv_creat_backupcodes();

    // Gửi email thông báo bảo mật
    $send_data = [[
        'to' => $user_info['email'],
        'data' => [
            'greeting_user' => greeting_for_user_create($user_info['username'], $user_info['first_name'], $user_info['last_name'], $user_info['gender']),
            'Home' => urlRewriteWithDomain(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA, NV_MY_DOMAIN),
            'time' => nv_datetime_format(NV_CURRENTTIME, 0, 0),
            'ip' => NV_CLIENT_IP,
            'browser' => NV_USER_AGENT,
            'link' => urlRewriteWithDomain(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name, NV_MY_DOMAIN)
        ]
    ]];
    nv_sendmail_template_async([$module_file, NukeViet\Template\Email\Tpl2Step::RENEW_BACKUPCODE], $send_data);
    nv_insert_logs(NV_LANG_DATA, $module_name, 'log_renew_backupcode', '', $user_info['userid']);
    nv_jsonOutput([
        'status' => 'ok',
        'mess' => 'OK'
    ]);
}

$sql = 'SELECT * FROM ' . $db_config['prefix'] . '_' . $site_mods[NV_BRIDGE_USER_MODULE]['module_data'] . '_backupcodes WHERE userid=' . $user_info['userid'];
$array_data['backupcodes'] = $db->query($sql)->fetchAll();

$array_data['print_code_url'] = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=print';
$array_data['download_code_url'] = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;downloadcode=' . md5('downloadcode' . NV_CHECK_SESSION);
$array_data['text_codes'] = [];
foreach ($array_data['backupcodes'] as $code) {
    if (!empty($code['is_used'])) {
        continue;
    }
    $array_data['text_codes'][] = $code['code'];
}
$array_data['text_codes'] = implode("\n", $array_data['text_codes']);

// Tải xuống code
if ($nv_Request->isset_request('downloadcode', 'get') and $nv_Request->get_title('downloadcode', 'get', '') == md5('downloadcode' . NV_CHECK_SESSION)) {
    $filename = change_alias(NV_SERVER_NAME) . '-recovery-codes.txt';
    $data = '';

    foreach ($array_data['backupcodes'] as $code) {
        if (!empty($code['is_used'])) {
            continue;
        }
        $data .= $code['code'] . "\n";
    }

    header('Pragma: public');
    header('Expires: 0');
    header('Cache-Control:');
    header('Cache-Control: public');
    header('Content-Description: File Transfer');
    header('Content-Type: text/plain; charset=UTF-8');
    header('Content-Disposition: attachment; filename="' . $filename . '";');
    header('Last-Modified: ' . date('D, d M Y H:i:s \G\M\T', NV_CURRENTTIME));
    header('Content-Length: ' . strlen($data));

    echo $data;
    exit();
}

// In code ra
if ($array_op[0] ?? '' == 'print') {
    $page_url .= '&amp;' . NV_OP_VARIABLE . '=print';
    $canonicalUrl = getCanonicalUrl($page_url);

    $contents = nv_theme_print_code($array_data['backupcodes']);
    include NV_ROOTDIR . '/includes/header.php';
    echo nv_site_theme($contents, false);
    include NV_ROOTDIR . '/includes/footer.php';
}

// Sửa App
if ($array_data['show_type'] == 'app') {
    $array_data['secretkey'] = nv_get_secretkey();

    // Lưu thiết lập
    if ($nv_Request->isset_request('checkss', 'post')) {
        $checkss = $nv_Request->get_title('checkss', 'post', '');
        if ($checkss !== NV_CHECK_SESSION) {
            nv_jsonOutput([
                'status' => 'error',
                'input' => '',
                'mess' => 'Session error!'
            ]);
        }

        $opt = $nv_Request->get_title('opt', 'post', '');

        if (!$GoogleAuthenticator->verifyOpt($array_data['secretkey'], $opt)) {
            nv_jsonOutput([
                'status' => 'error',
                'input' => 'opt',
                'mess' => $nv_Lang->getModule('wrong_confirm')
            ]);
        }

        nv_insert_logs(NV_LANG_DATA, $module_name, 'log_edit_2step', '', $user_info['userid']);

        $sql = 'UPDATE ' . $db_config['prefix'] . '_' . $site_mods[NV_BRIDGE_USER_MODULE]['module_data'] . ' SET
            active2step=1, secretkey=' . $db->quote($array_data['secretkey']) . ', last_update=' . NV_CURRENTTIME . '
        WHERE userid=' . $user_info['userid'];
        $db->query($sql);

        $nv_Request->unset_request($module_data . '_secretkey', 'session');

        nv_jsonOutput([
            'status' => 'ok',
            'redirect' => str_replace('&amp;', '&', nv_url_rewrite($page_url, true))
        ]);
    }
} elseif ($nv_Request->isset_request($module_data . '_secretkey', 'session')) {
    $nv_Request->unset_request($module_data . '_secretkey', 'session');
}

$canonicalUrl = getCanonicalUrl($page_url);

// Chỉ render NVSmarty khi theme Future; còn lại dùng XTemplate cũ để tương thích
$use_future = (($global_config['site_theme'] ?? '') === 'future') || (($global_config['module_theme'] ?? '') === 'future');
if ($use_future) {
    $template_js = get_tpl_dir([$global_config['module_theme'], $global_config['site_theme']], 'default', 'js/users.passkey.js');
    $link_turnon = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=setup';

    $showKeys = ($array_data['show_type'] == 'key');
    $showCodes = ($array_data['show_type'] == 'code');
    $messageLoginKeys = '';
    if ($array_data['login_keys'] > 0) {
        $messageLoginKeys = $nv_Lang->getModule('rcode_note', nv_number_format($array_data['login_keys']));
    }

    $secKeys = [];
    foreach ($array_data['publicKeys'] as $seckey) {
        if (!empty($seckey['enable_login'])) {
            continue;
        }
        $secKeys[] = [
            'id' => $seckey['id'],
            'nickname' => $seckey['nickname'],
            'created_at' => nv_datetime_format($seckey['created_at'], 1),
            'last_used_at' => nv_datetime_format($seckey['last_used_at'], 1),
            'this_client' => ($seckey['clid'] == ($client_info['clid'] ?? ''))
        ];
    }
    $numberKeysText = $nv_Lang->getModule('number_keys', nv_number_format($array_data['security_keys']));

    $codeUnused = 0;
    foreach ($array_data['backupcodes'] as $code) {
        $codeUnused += empty($code['is_used']) ? 1 : 0;
    }
    $remainCodeText = $nv_Lang->getModule('remain_code', nv_number_format($codeUnused));
    $usedupCode = ($codeUnused < 1);
    $lackCode = (!$usedupCode && $codeUnused < 3);

    $qrSrc = '';
    $formAction = '';
    $nvRedirect = '';
    $secretkeyLower = '';
    $scrollApp = false;
    if ($array_data['show_type'] == 'app') {
        $array_data['secretkey'] = nv_get_secretkey();
        $qrSrc = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=qrimg&amp;t=' . nv_genpass();
        $formAction = $array_data['page_url'] . '&amp;type=app';
        $nvRedirect = '';
        $secretkeyLower = strtolower($array_data['secretkey']);
        $scrollApp = true;
    }

    $tpl = new \NukeViet\Template\NVSmarty();
    $tpl_dir = get_module_tpl_dir('main.tpl');
    $tpl->setTemplateDir($tpl_dir);
    $tpl->assign('LANG', $nv_Lang);
    $tpl->assign('GLANG', \NukeViet\Core\Language::$lang_global);
    $tpl->assign('NV_CHECK_SESSION', NV_CHECK_SESSION);
    $tpl->assign('ACTIVE_2STEP', !empty($user_info['active2step']));
    $tpl->assign('LINK_TURNON', $link_turnon);
    $tpl->assign('TEMPLATE_JS', $template_js);
    $tpl->assign('DATA', $array_data);
    $tpl->assign('MESSAGE', $messageLoginKeys);
    $tpl->assign('SHOW_KEYS', $showKeys);
    $tpl->assign('SHOW_CODES', $showCodes);
    $tpl->assign('SECKEYS', $secKeys);
    $tpl->assign('NUMBER_KEYS_TEXT', $numberKeysText);
    $tpl->assign('REMAIN_CODE_TEXT', $remainCodeText);
    $tpl->assign('USEDUP_CODE', $usedupCode);
    $tpl->assign('LACK_CODE', $lackCode);
    $tpl->assign('QR_SRC', $qrSrc);
    $tpl->assign('FORM_ACTION', $formAction);
    $tpl->assign('NV_REDIRECT', $nvRedirect);
    $tpl->assign('SECRETKEY', $secretkeyLower);
    $tpl->assign('SCROLL_APP', $scrollApp);

    $is_future_tpl = (strpos(str_replace('\\', '/', $tpl_dir), '/themes/future/') !== false);
    if ($is_future_tpl) {
        $contents = $tpl->fetch('main.tpl');
    } else {
        // Fallback nếu template thực tế không phải future
        $contents = nv_theme_info_2step($array_data);
    }
} else {
    $contents = nv_theme_info_2step($array_data);
}

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
