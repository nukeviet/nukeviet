<?php

/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_IS_MOD_USER')) {
    exit('Stop!!!');
}

$page_title = $nv_Lang->getModule('verify_password_title');
$description = $keywords = 'no';
$page_url = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op;
$checkss = md5('verify_password.' . NV_CHECK_SESSION);

$array = [];
$array['redirect'] = nv_get_redirect();
$array['nv_redirect'] = nv_redirect_decrypt($array['redirect']);
$array['area'] = $nv_Request->get_title('area', 'get,post', '');

if (empty($array['area']) or empty($array['area']) or empty($array['nv_redirect'])) {
    nv_error404();
}
$array['form_action'] = $page_url;

if ($nv_Request->isset_request('_csrf', 'post')) {
    $_csrf = $nv_Request->get_title('_csrf', 'post', '');
    if (!hash_equals($_csrf, $checkss)) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => 'Error session!!!'
        ]);
    }

    // Kiểm tra mã xác nhận
    unset($nv_seccode);
    if ($module_captcha == 'recaptcha') {
        // Xác định giá trị của captcha nhập vào nếu sử dụng reCaptcha
        $nv_seccode = $nv_Request->get_title('g-recaptcha-response', 'post', '');
    } elseif ($module_captcha == 'turnstile') {
        // Xác định giá trị của captcha nhập vào nếu sử dụng Turnstile
        $nv_seccode = $nv_Request->get_title('cf-turnstile-response', 'post', '');
    } elseif ($module_captcha == 'captcha') {
        // Xác định giá trị của captcha nhập vào nếu sử dụng captcha hình
        $nv_seccode = $nv_Request->get_title('nv_seccode', 'post', '');
    }
    $check_seccode = isset($nv_seccode) ? nv_capcha_txt($nv_seccode, $module_captcha) : true;
    if (!$check_seccode) {
        nv_jsonOutput([
            'status' => 'error',
            'input' => '',
            'mess' => ($module_captcha == 'recaptcha') ? $nv_Lang->getGlobal('securitycodeincorrect1') : (($module_captcha == 'turnstile') ? $nv_Lang->getGlobal('securitycodeincorrect2') : $nv_Lang->getGlobal('securitycodeincorrect'))
        ]);
    }

    $nv_password = $nv_Request->get_title('password', 'post', '');
    if (empty($nv_password)) {
        nv_jsonOutput([
            'status' => 'error',
            'input' => 'password',
            'mess' => $nv_Lang->getGlobal('password_empty')
        ]);
    }
    $db_password = $db->query('SELECT password FROM ' . NV_MOD_TABLE . ' WHERE userid=' . $user_info['userid'])->fetchColumn();
    if (empty($db_password)) {
        nv_jsonOutput([
            'status' => 'error',
            'input' => 'password',
            'mess' => $nv_Lang->getModule('error_no_password')
        ]);
    }

    $blocker = new NukeViet\Core\Blocker(NV_ROOTDIR . '/' . NV_LOGS_DIR . '/ip_logs', NV_CLIENT_IP);
    $rules = [
        $global_config['login_number_tracking'],
        $global_config['login_time_tracking'],
        $global_config['login_time_ban']
    ];
    $blocker->trackLogin($rules, $global_config['is_login_blocker']);

    if ($global_config['login_number_tracking'] and $blocker->is_blocklogin($user_info['username'])) {
        nv_jsonOutput([
            'status' => 'error',
            'input' => '',
            'mess' => $nv_Lang->getGlobal('userlogin_blocked', $global_config['login_number_tracking'], nv_datetime_format($blocker->login_block_end, 1))
        ]);
    }

    if ($crypt->validate_password($nv_password, $db_password)) {
        $blocker->reset_trackLogin($user_info['username']);
        set_verified_password($array['area']);
        nv_jsonOutput([
            'status' => 'ok',
            'redirect' => $array['nv_redirect']
        ]);
    }

    if ($global_config['login_number_tracking'] and !empty($nv_password)) {
        $blocker->set_loginFailed($user_info['username'], NV_CURRENTTIME);
    }

    nv_jsonOutput([
        'status' => 'error',
        'input' => 'password',
        'mess' => $nv_Lang->getGlobal('incorrect_password')
    ]);
}

$canonicalUrl = getCanonicalUrl($page_url);
$contents = user_verify_password($array);

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
