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
}

/**
 * nv_json_result()
 *
 * @param mixed $array
 * @return
 */
function nv_json_result($array)
{
    global $nv_redirect;

    $array['redirect'] = $nv_redirect ? nv_redirect_decrypt($nv_redirect) : '';
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
            'mess' => $lang_module['wrong_confirm']
        ]);
    }

    try {
        $db->query('UPDATE ' . $db_config['prefix'] . '_' . $site_mods[NV_BRIDGE_USER_MODULE]['module_data'] . ' SET active2step=1 WHERE userid=' . $user_info['userid']);

        // Gửi email thông báo bảo mật
        $m_time = nv_date('H:i:s d/m/Y', NV_CURRENTTIME);
        $m_link = NV_MY_DOMAIN . nv_url_rewrite(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA, true);
        $message = sprintf($lang_module['email_2step_on'], $m_time, NV_CLIENT_IP, NV_USER_AGENT, $user_info['username'], $m_link, $global_config['site_name']);
        nv_sendmail('', $user_info['email'], $lang_module['email_subject'], $message);
    } catch (Exception $e) {
        trigger_error('Error active 2-step Auth!!!', 256);
    }

    nv_creat_backupcodes();

    nv_json_result([
        'status' => 'ok',
        'input' => '',
        'mess' => ''
    ]);
}

$canonicalUrl = getCanonicalUrl($page_url, true, true);

$contents = nv_theme_config_2step($secretkey, $nv_redirect);

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
