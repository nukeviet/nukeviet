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
$page_url = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op;
if (!empty($array_op[1]) and $array_op[1] == 'complete') {
    $page_url .= '/' . $array_op[1];

    if (!empty($array_op[2]) and $array_op[2] == 'review') {
        $page_url .= '/' . $array_op[2];
    }
}

$nv_redirect = '';
if ($nv_Request->isset_request('nv_redirect', 'post,get')) {
    $nv_redirect = nv_get_redirect();
    if ($nv_Request->isset_request('nv_redirect', 'get') and !empty($nv_redirect)) {
        $page_url .= '&amp;nv_redirect=' . $nv_redirect;
    }
} elseif ($nv_Request->isset_request('sso_redirect', 'get')) {
    $sso_redirect = $nv_Request->get_title('sso_redirect', 'get', '');
    if (!empty($sso_redirect)) {
        $nv_Request->set_Session('sso_redirect_' . $module_data, $sso_redirect);
    }
}

if (defined('SSO_CLIENT_DOMAIN')) {
    $sso_client = $nv_Request->get_title('client', 'get', '');
    if (!empty($sso_client)) {
        /** @disregard P1011 */
        $allowed_client_origin = explode(',', SSO_CLIENT_DOMAIN);
        if (!in_array($sso_client, $allowed_client_origin, true)) {
            // 406 Not Acceptable
            nv_info_die($nv_Lang->getGlobal('error_404_title'), $nv_Lang->getGlobal('error_404_title'), $nv_Lang->getGlobal('error_404_content'), 406);
        }
        $nv_Request->set_Session('sso_client_' . $module_data, $sso_client);
    }
}

// Xem lại toàn bộ các xác thực 2 bước
if (!empty($array_op[2]) and $array_op[2] == 'review' and $array_op[1] == 'complete') {
    $csrf = $nv_Request->get_title($module_data . '_setreview', 'session', '');
    if (empty($user_info['active2step']) or empty($csrf) or !csrf_check($csrf, $module_data . '_setreview')) {
        nv_redirect_location(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name);
    }
    $nv_Request->unset_request($module_data . '_setreview', 'session');

    $array_data = [];
    $array_data['redirect'] = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name;
    $array_data['link_passkey'] = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . NV_BRIDGE_USER_MODULE . '&amp;' . NV_OP_VARIABLE . '=editinfo/passkey';
    $array_data['link_seckey'] = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name;
    $array_data['publicKeys'] = [];
    $array_data['login_keys'] = 0;
    $array_data['security_keys'] = 0;

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

    if (!empty($nv_redirect)) {
        $array_data['redirect'] = nv_redirect_decrypt($nv_redirect);
    }

    if (defined('SSO_REGISTER_SECRET')) {
        $sso_client = $nv_Request->get_title('sso_client_' . $module_data, 'session', '');
        $sso_redirect = $nv_Request->get_title('sso_redirect_' . $module_data, 'session', '');
        $sso_redirect = NukeViet\Client\Sso::decrypt($sso_redirect);

        if (!empty($sso_redirect) and !empty($sso_client) and str_starts_with($sso_redirect, $sso_client)) {
            $array_data['redirect'] = $sso_redirect;
            $array_data['client'] = $sso_client;
        }

        $nv_Request->unset_request('sso_client_' . $module_data, 'session');
        $nv_Request->unset_request('sso_redirect_' . $module_data, 'session');
    }

    $canonicalUrl = getCanonicalUrl($page_url);
    $contents = nv_theme_review_2step($array_data);

    include NV_ROOTDIR . '/includes/header.php';
    echo nv_site_theme($contents);
    include NV_ROOTDIR . '/includes/footer.php';
}

// Trang thông báo kết quả
if (!empty($array_op[1]) and $array_op[1] == 'complete') {
    $csrf = $nv_Request->get_title($module_data . '_setsuccess', 'session', '');
    if (empty($user_info['active2step']) or empty($csrf) or !csrf_check($csrf, $module_data . '_setsuccess')) {
        nv_redirect_location(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name);
    }
    $nv_Request->unset_request($module_data . '_setsuccess', 'session');
    $nv_Request->set_Session($module_data . '_setreview', csrf_create($module_data . '_setreview'));

    $sql = 'SELECT * FROM ' . $db_config['prefix'] . '_' . $site_mods[NV_BRIDGE_USER_MODULE]['module_data'] . '_backupcodes WHERE userid=' . $user_info['userid'];
    $backupcodes = $db->query($sql)->fetchAll();

    $array_data = [];
    $array_data['print_url'] = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=print';
    $array_data['download_url'] = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;downloadcode=' . md5('downloadcode' . NV_CHECK_SESSION);
    $array_data['text_codes'] = [];
    foreach ($backupcodes as $code) {
        $array_data['text_codes'][] = $code['code'];
    }
    $array_data['text_codes'] = implode("\n", $array_data['text_codes']);
    $array_data['redirect'] = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '/complete/review';
    if (!empty($nv_redirect)) {
        $array_data['redirect'] .= '&amp;nv_redirect=' . $nv_redirect;
    }

    $canonicalUrl = getCanonicalUrl($page_url);
    $contents = nv_theme_complete_2step($backupcodes, $array_data);

    include NV_ROOTDIR . '/includes/header.php';
    echo nv_site_theme($contents);
    include NV_ROOTDIR . '/includes/footer.php';
}

// Thiết lập
if (!empty($user_info['active2step'])) {
    nv_redirect_location(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name);
}

// Verify code
$checkss = $nv_Request->get_title('checkss', 'post', '');
$secretkey = nv_get_secretkey();

if ($checkss == NV_CHECK_SESSION) {
    $opt = $nv_Request->get_title('opt', 'post', '');

    if (!$GoogleAuthenticator->verifyOpt($secretkey, $opt)) {
        nv_jsonOutput([
            'status' => 'error',
            'input' => 'opt',
            'mess' => $nv_Lang->getModule('wrong_confirm')
        ]);
    }

    try {
        $sql = 'UPDATE ' . $db_config['prefix'] . '_' . $site_mods[NV_BRIDGE_USER_MODULE]['module_data'] . ' SET
            active2step=1, secretkey=' . $db->quote($secretkey) . ', last_update=' . NV_CURRENTTIME . '
        WHERE userid=' . $user_info['userid'];
        $db->query($sql);

        $nv_Request->unset_request($module_data . '_secretkey', 'session');

        // Gửi email thông báo bảo mật
        $send_data = [[
            'to' => $user_info['email'],
            'data' => [
                'greeting_user' => greeting_for_user_create($user_info['username'], $user_info['first_name'], $user_info['last_name'], $user_info['gender']),
                'Home' => urlRewriteWithDomain(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA, NV_MY_DOMAIN),
                'time' => nv_datetime_format(NV_CURRENTTIME, 1, 0),
                'ip' => NV_CLIENT_IP,
                'browser' => NV_USER_AGENT
            ]
        ]];
        nv_sendmail_template_async([$module_file, NukeViet\Template\Email\Tpl2Step::ACTIVE_2STEP], $send_data);
    } catch (Throwable $e) {
        trigger_error(print_r($e, true));
        http_response_code(500);
        trigger_error('Error active 2-step Auth!!!', E_USER_ERROR);
    }

    nv_creat_backupcodes();
    $nv_Request->set_Session($module_data . '_setsuccess', csrf_create($module_data . '_setsuccess'));
    $redirect = $page_url . '/complete';
    if (!empty($nv_redirect)) {
        $redirect .= '&amp;nv_redirect=' . $nv_redirect;
    }

    nv_jsonOutput([
        'status' => 'ok',
        'redirect' => str_replace('&amp;', '&', nv_url_rewrite($redirect, true))
    ]);
}

$canonicalUrl = getCanonicalUrl($page_url);

$contents = nv_theme_config_2step($secretkey, $nv_redirect);

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
