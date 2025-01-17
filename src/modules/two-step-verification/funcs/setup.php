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

// Trang thông báo kết quả
if (!empty($array_op[1]) and $array_op[1] == 'complete') {
    $csrf = $nv_Request->get_title($module_data . '_setsuccess', 'session', '');
    if (empty($user_info['active2step']) or empty($csrf) or !csrf_check($csrf, $module_data . '_setsuccess')) {
        nv_redirect_location(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name);
    }

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
    $array_data['redirect'] = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name;

    if (!empty($nv_redirect)) {
        $array_data['redirect'] = nv_redirect_decrypt($nv_redirect);
    }

    if (defined('SSO_REGISTER_SECRET')) {
        $sso_client = $nv_Request->get_title('sso_client_' . $module_data, 'session', '');
        $sso_redirect = $nv_Request->get_title('sso_redirect_' . $module_data, 'session', '');
        /** @disregard P1011 */
        $iv = substr(SSO_REGISTER_SECRET, 0, 16);
        $sso_redirect = strtr($sso_redirect, '-_,', '+/=');
        /** @disregard P1011 */
        $sso_redirect = openssl_decrypt($sso_redirect, 'aes-256-cbc', SSO_REGISTER_SECRET, 0, $iv);

        if (!empty($sso_redirect) and !empty($sso_client) and str_starts_with($sso_redirect, $sso_client)) {
            $array_data['redirect'] = $sso_redirect;
            $array_data['redirect'] = $sso_client;
        }

        $nv_Request->unset_request('sso_client_' . $module_data, 'session');
        $nv_Request->unset_request('sso_redirect_' . $module_data, 'session');
    }

    $canonicalUrl = getCanonicalUrl($page_url, true, true);
    $contents = nv_theme_complete_2step($backupcodes, $array_data);

    include NV_ROOTDIR . '/includes/header.php';
    echo nv_site_theme($contents);
    include NV_ROOTDIR . '/includes/footer.php';
}

// Thiết lập
if (!empty($user_info['active2step'])) {
    nv_redirect_location(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name);
}

// Show QR-Image
if (isset($array_op[1]) and $array_op[1] == 'qr-image') {
    $url = 'otpauth://totp/' . $user_info['email'] . '?secret=' . $secretkey . '&issuer=' . urlencode(NV_SERVER_NAME . ' | ' . $user_info['username']);

    // instantiate the barcode class
    $barcode = new Com\Tecnick\Barcode\Barcode();
    // generate a barcode
    $bobj = $barcode->getBarcodeObj(
        'QRCODE,H',  // barcode type and additional comma-separated parameters
        $url,        // data string to encode
        -4,         // bar width (use absolute or negative value as multiplication factor)
        -4,         // bar height (use absolute or negative value as multiplication factor)
        'black',     // foreground color
        [-2, -2, -2, -2] // padding (use absolute or negative values as multiplication factors)
    )->setBackgroundColor('white'); // background color
    $data = $bobj->getSvgCode();
    header('Content-Type: image/svg+xml');
    header('Cache-Control: private, must-revalidate, post-check=0, pre-check=0, max-age=1');
    header('Pragma: public');
    header('Expires: ' . gmdate('D, d M Y H:i:s', time() - 3600) . ' GMT'); // Date in the past
    header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
    header('Content-Disposition: inline; filename="' . md5($url) . '.svg";');
    header('access-control-allow-origin: *');
    header('Vary: Accept-Encoding');
    if (empty($_SERVER['HTTP_ACCEPT_ENCODING'])) {
        // the content length may vary if the server is using compression
        header('Content-Length: ' . strlen($data));
    }
    echo $data;
    exit();
}

// Verify code
$checkss = $nv_Request->get_title('checkss', 'post', '');

if ($checkss == NV_CHECK_SESSION) {
    $opt = $nv_Request->get_title('opt', 'post', 6);

    if (!$GoogleAuthenticator->verifyOpt($secretkey, $opt)) {
        nv_jsonOutput([
            'status' => 'error',
            'input' => 'opt',
            'mess' => $nv_Lang->getModule('wrong_confirm')
        ]);
    }

    try {
        $sql = 'UPDATE ' . $db_config['prefix'] . '_' . $site_mods[NV_BRIDGE_USER_MODULE]['module_data'] . ' SET
            active2step=1, last_update=' . NV_CURRENTTIME . '
        WHERE userid=' . $user_info['userid'];
        $db->query($sql);

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

$canonicalUrl = getCanonicalUrl($page_url, true, true);

$contents = nv_theme_config_2step($secretkey, $nv_redirect);

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
