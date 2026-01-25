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

use NukeViet\Module\users\Shared\Emails;

if (defined('NV_IS_USER')) {
    nv_redirect_location(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name);
}

if (defined('NV_IS_USER_FORUM')) {
    require_once NV_ROOTDIR . '/' . $global_config['dir_forum'] . '/nukeviet/lostpass.php';
    exit();
}

/**
 * lost_pass_sendMail()
 *
 * @param array $row
 */
function lost_pass_sendMail($row)
{
    global $db, $global_config, $nv_Lang, $module_name;

    $passlostkey = (!empty($row['passlostkey']) and preg_match("/^([0-9]{10,15})\|([a-z0-9]{32})$/i", $row['passlostkey'], $matches)) ? [
        $matches[1],
        $matches[2]
    ] : [];
    if (!isset($passlostkey[0]) or !isset($passlostkey[1]) or (int) $passlostkey[0] < NV_CURRENTTIME) {
        $key = strtoupper(nv_genpass(10));
        $passlostkey = md5($row['userid'] . $key . $global_config['sitekey']);
        $pa = NV_CURRENTTIME + 3600;
        $passlostkey = $pa . '|' . $passlostkey;

        $send_data = [[
            'to' => $row['email'],
            'data' => [
                'first_name' => $row['first_name'],
                'last_name' => $row['last_name'],
                'username' => $row['username'],
                'email' => $row['email'],
                'gender' => $row['gender'],
                'lang' => NV_LANG_INTERFACE,
                'code' => $key,
                'deadline' => $pa,
                'ip' => NV_CLIENT_IP,
                'user_agent' => NV_USER_AGENT,
                'request_time' => nv_datetime_format(NV_CURRENTTIME, 1)
            ]
        ]];
        $send = nv_sendmail_from_template([$module_name, Emails::LOST_PASS], $send_data, NV_LANG_INTERFACE);
        if (!$send) {
            nv_jsonOutput([
                'status' => 'error',
                'input' => '',
                'step' => 'step1',
                'mess' => $nv_Lang->getModule('lostpass_sendmail_error'),
                'redirect' => nv_url_rewrite(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name, true)
            ]);
        }

        $sql = 'UPDATE ' . NV_MOD_TABLE . " SET passlostkey='" . $passlostkey . "' WHERE userid=" . $row['userid'];
        $db->query($sql);
    }
}

$page_url = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op;

$nv_redirect = '';
if ($nv_Request->isset_request('nv_redirect', 'post,get')) {
    $nv_redirect = nv_get_redirect();

    if ($nv_Request->isset_request('nv_redirect', 'get') and !empty($nv_redirect)) {
        $page_url .= '&nv_redirect=' . $nv_redirect;
    }
}

$array_gfx_chk = !empty($global_config['captcha_area']) ? explode(',', $global_config['captcha_area']) : [];
$gfx_chk = (!empty($array_gfx_chk) and in_array('p', $array_gfx_chk, true)) ? 1 : 0;

$data = [];
$data['checkss'] = md5(NV_CHECK_SESSION . '_' . $module_name . '_' . $op);
$checkss = $nv_Request->get_title('checkss', 'post', '');

if ($checkss == $data['checkss']) {
    $data['step'] = $nv_Request->get_title('step', 'post', '');
    if ($data['step'] != 'step2' and $data['step'] != 'step3' and $data['step'] != 'step4') {
        $data['step'] = 'step1';
    }

    $seccode = $nv_Request->get_string('lostpass_seccode', 'session', '');

    if ($module_captcha == 'recaptcha') {
        $data['nv_seccode'] = $nv_Request->get_title('gcaptcha_session', 'post', '');
    } elseif ($module_captcha == 'turnstile') {
        $data['nv_seccode'] = $nv_Request->get_title('cf-turnstile-response', 'post', '');
    } elseif ($module_captcha == 'captcha') {
        $data['nv_seccode'] = $nv_Request->get_title('nv_seccode', 'post', '');
    }

    $check_seccode = true;
    if ($gfx_chk and ($module_captcha == 'captcha' or $module_captcha == 'recaptcha')) {
        $check_seccode = ((!empty($seccode) and md5($data['nv_seccode']) == $seccode) or nv_capcha_txt($data['nv_seccode'], $module_captcha));
    }

    if (!$check_seccode) {
        $nv_Request->set_Session('lostpass_seccode', '');
        nv_jsonOutput([
            'status' => 'error',
            'input' => '',
            'step' => 'step1',
            'mess' => ($module_captcha == 'recaptcha') ? $nv_Lang->getGlobal('securitycodeincorrect1') : (($module_captcha == 'turnstile') ? $nv_Lang->getGlobal('securitycodeincorrect2') : $nv_Lang->getGlobal('securitycodeincorrect'))
        ]);
    }

    $data['userField'] = nv_substr($nv_Request->get_title('userField', 'post', '', 1), 0, 100);
    if (empty($data['userField'])) {
        $nv_Request->set_Session('lostpass_seccode', '');
        nv_jsonOutput([
            'status' => 'error',
            'input' => 'userField',
            'step' => 'step1',
            'mess' => $nv_Lang->getModule('lostpass_no_info1')
        ]);
    }

    $check_email = nv_check_valid_email($data['userField'], true);
    if (empty($check_email[0])) {
        $sql = 'SELECT * FROM ' . NV_MOD_TABLE . ' WHERE email= :userField AND active=1';
        $userField = $check_email[1];
    } else {
        $sql = 'SELECT * FROM ' . NV_MOD_TABLE . ' WHERE md5username=:userField AND active=1';
        $userField = nv_md5safe($data['userField']);
    }
    $stmt = $db->prepare($sql);
    $stmt->bindParam(':userField', $userField, PDO::PARAM_STR);
    $stmt->execute();
    $row = $stmt->fetch();
    if (empty($row)) {
        $nv_Request->set_Session('lostpass_seccode', '');
        nv_jsonOutput([
            'status' => 'error',
            'input' => 'userField',
            'step' => 'step1',
            'mess' => $nv_Lang->getModule('lostpass_no_info2')
        ]);
    }

    $email_hint = empty($check_email[0]) ? $row['email'] : (substr($row['email'], 0, 3) . '***' . substr($row['email'], -6));

    if (empty($row['password'])) {
        $nv_Request->set_Session('lostpass_seccode', '');

        $url = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=login';
        if (!empty($nv_redirect)) {
            $url .= '&nv_redirect=' . $nv_redirect;
        }

        nv_jsonOutput([
            'status' => 'ok',
            'input' => nv_url_rewrite($url, true),
            'step' => '',
            'mess' => $nv_Lang->getModule('openid_lostpass_info')
        ]);
    }

    if ($global_config['allowquestion'] and (empty($row['question']) or empty($row['answer']))) {
        $nv_Request->set_Session('lostpass_seccode', '');

        $url = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=login';
        if (!empty($nv_redirect)) {
            $url .= '&nv_redirect=' . $nv_redirect;
        }

        nv_jsonOutput([
            'status' => 'ok',
            'input' => nv_url_rewrite($url, true),
            'step' => '',
            'mess' => $nv_Lang->getModule('lostpass_question_empty')
        ]);
    }

    if (isset($data['nv_seccode'])) {
        $nv_Request->set_Session('lostpass_seccode', md5($data['nv_seccode']));
    }

    if ($data['step'] == 'step1') {
        if ($global_config['allowquestion']) {
            nv_jsonOutput([
                'status' => 'answer',
                'input' => 'answer',
                'step' => 'step2',
                'info' => '<p>' . $nv_Lang->getModule('lostpass_question') . ':</p><strong>' . $row['question'] . '</strong>',
                'mess' => ''
            ]);
        } else {
            lost_pass_sendMail($row);
            nv_jsonOutput([
                'status' => 'verify',
                'input' => 'verifykey',
                'step' => 'step3',
                'info' => $nv_Lang->getModule('lostpass_content_mess', $email_hint),
                'mess' => $nv_Lang->getModule('lostpass_content_mess', $email_hint)
            ]);
        }
    }

    if ($global_config['allowquestion']) {
        $data['answer'] = $nv_Request->get_title('answer', 'post', '', 1);
        if ($data['answer'] != $row['answer']) {
            nv_jsonOutput([
                'status' => 'error',
                'input' => 'answer',
                'step' => 'step2',
                'info' => '<p>' . $nv_Lang->getModule('lostpass_question') . ':</p><strong>' . $row['question'] . '</strong>',
                'mess' => $nv_Lang->getModule('answer_failed')
            ]);
        }

        if ($data['step'] == 'step2') {
            lost_pass_sendMail($row);
            nv_jsonOutput([
                'status' => 'verify',
                'input' => 'verifykey',
                'step' => 'step3',
                'info' => $nv_Lang->getModule('lostpass_content_mess', $email_hint),
                'mess' => $nv_Lang->getModule('lostpass_content_mess', $email_hint)
            ]);
        }
    }

    $data['verifykey'] = strtoupper($nv_Request->get_title('verifykey', 'post', '', 1));

    unset($matches);
    $passlostkey = (!empty($row['passlostkey']) and preg_match("/^([0-9]{10,15})\|([a-z0-9]{32})$/i", $row['passlostkey'], $matches)) ? [
        $matches[1],
        $matches[2]
    ] : [];

    if (!isset($passlostkey[0]) or !isset($passlostkey[1]) or (int) $passlostkey[0] < NV_CURRENTTIME) {
        lost_pass_sendMail($row);
        nv_jsonOutput([
            'status' => 'error',
            'input' => 'verifykey',
            'step' => 'step3',
            'info' => $nv_Lang->getModule('lostpass_content_mess', $email_hint),
            'mess' => $nv_Lang->getModule('lostpass_content_mess', $email_hint)
        ]);
    }

    if (empty($data['verifykey']) or $passlostkey[1] != md5($row['userid'] . $data['verifykey'] . $global_config['sitekey'])) {
        nv_jsonOutput([
            'status' => 'error',
            'input' => 'verifykey',
            'step' => 'step3',
            'info' => $nv_Lang->getModule('lostpass_content_mess', $email_hint),
            'mess' => $nv_Lang->getModule('lostpass_active_error')
        ]);
    }

    if ($data['step'] == 'step3') {
        nv_jsonOutput([
            'status' => 'new_password',
            'input' => 'new_password',
            'step' => 'step4',
            'info' => $nv_Lang->getModule('lostpass_newpass_mess'),
            'mess' => $nv_Lang->getModule('lostpass_newpass_mess')
        ]);
    }

    $new_password = $nv_Request->get_title('new_password', 'post', '');
    $re_password = $nv_Request->get_title('re_password', 'post', '');

    if (($check_new_password = nv_check_valid_pass($new_password, $global_config['nv_upassmax'], $global_config['nv_upassmin'])) != '') {
        nv_jsonOutput([
            'status' => 'error',
            'input' => 'new_password',
            'step' => 'step4',
            'info' => $nv_Lang->getModule('lostpass_newpass_mess'),
            'mess' => $check_new_password
        ]);
    }

    if ($new_password != $re_password) {
        nv_jsonOutput([
            'status' => 'error',
            'input' => 're_password',
            'step' => 'step4',
            'info' => $nv_Lang->getModule('lostpass_newpass_mess'),
            'mess' => $nv_Lang->getGlobal('passwordsincorrect')
        ]);
    }

    if (!passCmp($new_password, $row['password'], $row['userid'])) {
        nv_jsonOutput([
            'status' => 'error',
            'input' => 'new_password',
            'step' => 'step4',
            'info' => $nv_Lang->getModule('lostpass_newpass_mess'),
            'mess' => $nv_Lang->getModule('password_was_used', $global_config['oldpass_num'])
        ]);
    }

    $re_password = $crypt->hash_password($new_password, $global_config['hashprefix']);

    $stmt = $db->prepare('UPDATE ' . NV_MOD_TABLE . " SET password= :password, passlostkey='', pass_creation_time=" . NV_CURRENTTIME . ', pass_reset_request=0, last_update=' . NV_CURRENTTIME . ' WHERE userid=' . $row['userid']);
    $stmt->bindParam(':password', $re_password, PDO::PARAM_STR);
    $stmt->execute();

    oldPassSave($row['userid'], $row['password'], $row['pass_creation_time']);
    nv_apply_hook($module_name, 'user_lostpass_success', [$row, $new_password]);

    $send_data = [[
        'to' => $row['email'],
        'data' => [
            'first_name' => $row['first_name'],
            'last_name' => $row['last_name'],
            'username' => $row['username'],
            'email' => $row['email'],
            'gender' => $row['gender'],
            'lang' => NV_LANG_INTERFACE,
            'label' => $nv_Lang->getGlobal('password'),
            'newvalue' => $new_password,
            'send_newvalue' => $global_config['send_pass']
        ]
    ]];
    nv_sendmail_template_async([$module_name, Emails::SELF_EDIT], $send_data, NV_LANG_INTERFACE);

    $redirect = nv_redirect_decrypt($nv_redirect, true);
    $url = !empty($redirect) ? $redirect : nv_url_rewrite(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name, true);
    nv_jsonOutput([
        'status' => 'ok',
        'input' => $url,
        'step' => '',
        'mess' => $nv_Lang->getModule('editinfo_ok')
    ]);
}

$mailer_mode = strtolower($global_config['mailer_mode']);
if ($mailer_mode != 'smtp' and defined('NV_REGISTER_DOMAIN') and $global_config['idsite'] > 0) {
    // Chức năng quyên mật khẩu cần điều hướng về site chính, do các site con không có smtp để gửi mail
    /** @disregard P1011 */
    nv_redirect_location(NV_REGISTER_DOMAIN . NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op . '&nv_redirect=' . nv_redirect_encrypt($client_info['selfurl']));
}

$page_title = $nv_Lang->getModule('lostpass_page_title');
$key_words = $module_info['keywords'];

$canonicalUrl = getCanonicalUrl($page_url);

$contents = user_lostpass($data);

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
