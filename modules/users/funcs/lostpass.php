<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC <contact@vinades.vn>
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 10/03/2010 10:51
 */

if (!defined('NV_IS_MOD_USER')) {
    die('Stop!!!');
}

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
 * @param mixed $row
 * @return void
 */
function lost_pass_sendMail($row)
{
    global $db, $global_config, $lang_module;

    $passlostkey = (!empty($row['passlostkey']) and preg_match("/^([0-9]{10,15})\|([a-z0-9]{32})$/i", $row['passlostkey'], $matches)) ? array(
        $matches[1],
        $matches[2]
    ) : array();
    if (!isset($passlostkey[0]) or !isset($passlostkey[1]) or (int) $passlostkey[0] < NV_CURRENTTIME) {
        $key = strtoupper(nv_genpass(10));
        $passlostkey = md5($row['userid'] . $key . $global_config['sitekey']);
        $pa = NV_CURRENTTIME + 3600;
        $passlostkey = $pa . '|' . $passlostkey;

        $name = $global_config['name_show'] ? array(
            $row['first_name'],
            $row['last_name']
        ) : array(
            $row['last_name'],
            $row['first_name']
        );
        $name = array_filter($name);
        $name = implode(' ', $name);
        $sitename = '<a href="' . NV_MY_DOMAIN . NV_BASE_SITEURL . '">' . $global_config['site_name'] . '</a>';
        $lang_module['lostpass_email_subject'] = sprintf($lang_module['lostpass_email_subject'], NV_MY_DOMAIN);
        $message = sprintf($lang_module['lostpass_email_content'], $name, $sitename, $key, nv_date('H:i d/m/Y', $pa));
        if (!nv_sendmail($global_config['site_email'], $row['email'], $lang_module['lostpass_email_subject'], $message)) {
            nv_jsonOutput(array(
                'status' => 'error',
                'input' => '',
                'step' => 'step1',
                'mess' => $lang_module['lostpass_sendmail_error']
            ));
        }

        $sql = "UPDATE " . NV_MOD_TABLE . " SET passlostkey='" . $passlostkey . "' WHERE userid=" . $row['userid'];
        $db->query($sql);
    }
}

$nv_redirect = '';
if ($nv_Request->isset_request('nv_redirect', 'post,get')) {
    $nv_redirect = nv_get_redirect();
}

$data = array();
$data['checkss'] = NV_CHECK_SESSION;
$checkss = $nv_Request->get_title('checkss', 'post', '');

if ($checkss == $data['checkss']) {
    $data['step'] = $nv_Request->get_title('step', 'post', '');
    if ($data['step'] != 'step2' and $data['step'] != 'step3' and $data['step'] != 'step4') {
        $data['step'] = 'step1';
    }
    $seccode = $nv_Request->get_string('lostpass_seccode', 'session', '');

    if ($global_config['captcha_type'] == 2) {
        $data['nv_seccode'] = $nv_Request->get_title('gcaptcha_session', 'post', '');
    } else {
        $data['nv_seccode'] = $nv_Request->get_title('nv_seccode', 'post', '');
    }

    if (empty($data['nv_seccode']) or (!empty($data['nv_seccode']) and md5($data['nv_seccode']) != $seccode and !nv_capcha_txt($data['nv_seccode']))) {
        $nv_Request->set_Session('lostpass_seccode', '');
        nv_jsonOutput(array(
            'status' => 'error',
            'input' => ($global_config['captcha_type'] == 2 ? '' : 'nv_seccode'),
            'step' => 'step1',
            'mess' => ($global_config['captcha_type'] == 2 ? $lang_global['securitycodeincorrect1'] : $lang_global['securitycodeincorrect'])
        ));
    }

    $data['userField'] = nv_substr($nv_Request->get_title('userField', 'post', '', 1), 0, 100);
    if (empty($data['userField'])) {
        $nv_Request->set_Session('lostpass_seccode', '');
        nv_jsonOutput(array(
            'status' => 'error',
            'input' => 'userField',
            'step' => 'step1',
            'mess' => $lang_module['lostpass_no_info1']
        ));
    }

    $check_email = nv_check_valid_email($data['userField']);
    if (empty($check_email)) {
        $sql = 'SELECT * FROM ' . NV_MOD_TABLE . ' WHERE email= :userField AND active=1';
        $userField = nv_strtolower($data['userField']);
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
        nv_jsonOutput(array(
            'status' => 'error',
            'input' => 'userField',
            'step' => 'step1',
            'mess' => $lang_module['lostpass_no_info2']
        ));
    }

    $email_hint = empty($check_email) ? $row['email'] : (substr($row['email'], 0, 3) . '***' . substr($row['email'], -6));

    if (empty($row['password'])) {
        $nv_Request->set_Session('lostpass_seccode', '');

        $url = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=login';
        if (!empty($nv_redirect)) {
            $url .= '&nv_redirect=' . $nv_redirect;
        }

        nv_jsonOutput(array(
            'status' => 'ok',
            'input' => nv_url_rewrite($url, true),
            'step' => '',
            'mess' => $lang_module['openid_lostpass_info']
        ));
    }

    if ($global_config['allowquestion'] and (empty($row['question']) or empty($row['answer']))) {
        $nv_Request->set_Session('lostpass_seccode', '');

        $url = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=login';
        if (!empty($nv_redirect)) {
            $url .= '&nv_redirect=' . $nv_redirect;
        }

        nv_jsonOutput(array(
            'status' => 'ok',
            'input' => nv_url_rewrite($url, true),
            'step' => '',
            'mess' => $lang_module['lostpass_question_empty']
        ));
    }

    $nv_Request->set_Session('lostpass_seccode', md5($data['nv_seccode']));

    if ($data['step'] == 'step1') {
        if ($global_config['allowquestion']) {
            nv_jsonOutput(array(
                'status' => 'error',
                'input' => 'answer',
                'step' => 'step2',
                'info' => $row['question'],
                'mess' => $row['question']
            ));
        } else {
            lost_pass_sendMail($row);
            nv_jsonOutput(array(
                'status' => 'error',
                'input' => 'verifykey',
                'step' => 'step3',
                'info' => sprintf($lang_module['lostpass_content_mess'], $email_hint),
                'mess' => sprintf($lang_module['lostpass_content_mess'], $email_hint)
            ));
        }
    }

    if ($global_config['allowquestion']) {
        $data['answer'] = $nv_Request->get_title('answer', 'post', '', 1);
        if ($data['answer'] != $row['answer']) {
            nv_jsonOutput(array(
                'status' => 'error',
                'input' => 'answer',
                'step' => 'step2',
                'info' => $row['question'],
                'mess' => $lang_module['answer_failed']
            ));
        }

        if ($data['step'] == 'step2') {
            lost_pass_sendMail($row);
            nv_jsonOutput(array(
                'status' => 'error',
                'input' => 'verifykey',
                'step' => 'step3',
                'info' => sprintf($lang_module['lostpass_content_mess'], $email_hint),
                'mess' => sprintf($lang_module['lostpass_content_mess'], $email_hint)
            ));
        }
    }

    $data['verifykey'] = strtoupper($nv_Request->get_title('verifykey', 'post', '', 1));

    unset($matches);
    $passlostkey = (!empty($row['passlostkey']) and preg_match("/^([0-9]{10,15})\|([a-z0-9]{32})$/i", $row['passlostkey'], $matches)) ? array(
        $matches[1],
        $matches[2]
    ) : array();

    if (!isset($passlostkey[0]) or !isset($passlostkey[1]) or (int) $passlostkey[0] < NV_CURRENTTIME) {
        lost_pass_sendMail($row);
        nv_jsonOutput(array(
            'status' => 'error',
            'input' => 'verifykey',
            'step' => 'step3',
            'info' => sprintf($lang_module['lostpass_content_mess'], $email_hint),
            'mess' => sprintf($lang_module['lostpass_content_mess'], $email_hint)
        ));
    }

    if (empty($data['verifykey']) or $passlostkey[1] != md5($row['userid'] . $data['verifykey'] . $global_config['sitekey'])) {
        nv_jsonOutput(array(
            'status' => 'error',
            'input' => 'verifykey',
            'step' => 'step3',
            'info' => sprintf($lang_module['lostpass_content_mess'], $email_hint),
            'mess' => $lang_module['lostpass_active_error']
        ));
    }

    if ($data['step'] == 'step3') {
        nv_jsonOutput(array(
            'status' => 'error',
            'input' => 'new_password',
            'step' => 'step4',
            'info' => $lang_module['lostpass_newpass_mess'],
            'mess' => $lang_module['lostpass_newpass_mess']
        ));
    }

    $new_password = $nv_Request->get_title('new_password', 'post', '');
    $re_password = $nv_Request->get_title('re_password', 'post', '');

    if (($check_new_password = nv_check_valid_pass($new_password, $global_config['nv_upassmax'], $global_config['nv_upassmin'])) != '') {
        nv_jsonOutput(array(
            'status' => 'error',
            'input' => 'new_password',
            'step' => 'step4',
            'info' => $lang_module['lostpass_newpass_mess'],
            'mess' => $check_new_password
        ));
    }

    if ($new_password != $re_password) {
        nv_jsonOutput(array(
            'status' => 'error',
            'input' => 're_password',
            'step' => 'step4',
            'info' => $lang_module['lostpass_newpass_mess'],
            'mess' => $lang_global['passwordsincorrect']
        ));
    }

    $re_password = $crypt->hash_password($new_password, $global_config['hashprefix']);

    $stmt = $db->prepare("UPDATE " . NV_MOD_TABLE . " SET password= :password, passlostkey='' WHERE userid=" . $row['userid']);
    $stmt->bindParam(':password', $re_password, PDO::PARAM_STR);
    $stmt->execute();

    $name = $global_config['name_show'] ? array(
        $row['first_name'],
        $row['last_name']
    ) : array(
        $row['last_name'],
        $row['first_name']
    );
    $name = array_filter($name);
    $name = implode(' ', $name);
    $sitename = '<a href="' . NV_MY_DOMAIN . NV_BASE_SITEURL . '">' . $global_config['site_name'] . '</a>';
    $message = sprintf($lang_module['edit_mail_content'], $name, $sitename, $lang_global['password'], $new_password);
    @nv_sendmail($global_config['site_email'], $row['email'], $lang_module['edit_mail_subject'], $message);

    $redirect = nv_redirect_decrypt($nv_redirect, true);
    $url = !empty($redirect) ? $redirect : nv_url_rewrite(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name, true);
    nv_jsonOutput(array(
        'status' => 'ok',
        'input' => $url,
        'step' => '',
        'mess' => $lang_module['editinfo_ok']
    ));
}

$page_title = $mod_title = $lang_module['lostpass_page_title'];
$key_words = $module_info['keywords'];

$contents = user_lostpass($data);

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
