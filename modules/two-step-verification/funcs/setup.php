<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2021 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_MOD_2STEP_VERIFICATION')) {
    exit('Stop!!!');
}

if (!empty($user_info['active2step'])) {
    nv_redirect_location(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name);
}

$page_title = $module_info['site_title'];
$key_words = $module_info['keywords'];
$page_url = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op;

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
        $allowed_client_origin = explode(',', SSO_CLIENT_DOMAIN);
        if (!in_array($sso_client, $allowed_client_origin, true)) {
            // 406 Not Acceptable
            nv_info_die($nv_Lang->getGlobal('error_404_title'), $nv_Lang->getGlobal('error_404_title'), $nv_Lang->getGlobal('error_404_content'), 406);
        }
        $nv_Request->set_Session('sso_client_' . $module_data, $sso_client);
    }
}

/**
 * @param mixed $array
 * @return
 */
function nv_json_result($array)
{
    global $nv_redirect, $nv_Request, $module_data;

    if (!empty($nv_redirect)) {
        $array['redirect'] = nv_redirect_decrypt($nv_redirect);
    }

    if (defined('SSO_REGISTER_SECRET')) {
        $sso_client = $nv_Request->get_title('sso_client_' . $module_data, 'session', '');
        $sso_redirect = $nv_Request->get_title('sso_redirect_' . $module_data, 'session', '');
        $iv = substr(SSO_REGISTER_SECRET, 0, 16);
        $sso_redirect = strtr($sso_redirect, '-_,', '+/=');
        $sso_redirect = openssl_decrypt($sso_redirect, 'aes-256-cbc', SSO_REGISTER_SECRET, 0, $iv);

        if (!empty($sso_redirect) and !empty($sso_client) and str_starts_with($sso_redirect, $sso_client)) {
            $array['redirect'] = $sso_redirect;
            $array['client'] = $sso_client;
        }

        $nv_Request->unset_request('sso_client_' . $module_data, 'session');
        $nv_Request->unset_request('sso_redirect_' . $module_data, 'session');
    }

    nv_jsonOutput($array);
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
        nv_json_result([
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
        $m_time = nv_date('H:i:s d/m/Y', NV_CURRENTTIME);
        $m_link = urlRewriteWithDomain(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA, NV_MY_DOMAIN);
        $message = $nv_Lang->getModule('email_2step_on', $m_time, NV_CLIENT_IP, NV_USER_AGENT, $user_info['username'], $m_link, $global_config['site_name']);
        nv_sendmail_async('', $user_info['email'], $nv_Lang->getModule('email_subject'), $message);
    } catch (Exception $e) {
        trigger_error('Error active 2-step Auth!!!', 256);
    }

    nv_creat_backupcodes();

    if (!empty($global_config['allowuserloginmulti']) and $nv_Request->get_bool('forcedrelogin', 'post', false)) {
        $checknum = md5(nv_genpass(10));
        $stmt = $db->prepare('UPDATE ' . $db_config['prefix'] . '_' . $site_mods[NV_BRIDGE_USER_MODULE]['module_data'] . ' SET checknum=:checknum WHERE userid=' . $user_info['userid']);
        $stmt->bindParam(':checknum', $checknum, PDO::PARAM_STR);
        $stmt->execute();

        $redirect = nv_redirect_encrypt(urlRewriteWithDomain(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name, NV_MY_DOMAIN));
        $redirect = nv_url_rewrite(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=users&' . NV_OP_VARIABLE . '=login&nv_redirect=' . $redirect, true);

        nv_json_result([
            'status' => 'ok',
            'mess' => $nv_Lang->getModule('forcedrelogin_note'),
            'redirect' => $redirect
        ]);
    }

    nv_json_result([
        'status' => 'ok'
    ]);
}

$canonicalUrl = getCanonicalUrl($page_url, true, true);

$contents = nv_theme_config_2step($secretkey, $nv_redirect);

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
