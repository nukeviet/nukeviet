<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC <contact@vinades.vn>
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 31/05/2010, 00:36
 */

if (!defined('NV_ADMIN') or !defined('NV_MAINFILE') or !defined('NV_IS_MODADMIN')) {
    die('Stop!!!');
}

$page_title = $lang_module['smtp_config'];
$smtp_encrypted_array = [];
$smtp_encrypted_array[0] = 'None';
$smtp_encrypted_array[1] = 'SSL';
$smtp_encrypted_array[2] = 'TLS';

$array_config = [];
$errormess = '';

$checkss = md5(NV_CHECK_SESSION . '_' . $module_name . '_' . $op . '_' . $admin_info['userid']);
if ($nv_Request->isset_request('submitsave', 'post') and $checkss == $nv_Request->get_string('checkss', 'post')) {
    $array_config['mailer_mode'] = nv_substr($nv_Request->get_title('mailer_mode', 'post', '', 1), 0, 255);
    $array_config['smtp_host'] = nv_substr($nv_Request->get_title('smtp_host', 'post', '', 1), 0, 255);
    $array_config['smtp_port'] = nv_substr($nv_Request->get_title('smtp_port', 'post', '', 1), 0, 255);
    $array_config['smtp_username'] = nv_substr($nv_Request->get_title('smtp_username', 'post', ''), 0, 255);
    $array_config['smtp_password'] = nv_substr($nv_Request->get_title('smtp_password', 'post', ''), 0, 255);
    $array_config['sender_name'] = nv_substr($nv_Request->get_title('sender_name', 'post', ''), 0, 250);
    $array_config['sender_email'] = nv_substr($nv_Request->get_title('sender_email', 'post', ''), 0, 250);
    $array_config['reply_name'] = nv_substr($nv_Request->get_title('reply_name', 'post', ''), 0, 250);
    $array_config['reply_email'] = nv_substr($nv_Request->get_title('reply_email', 'post', ''), 0, 250);
    $array_config['force_sender'] = intval($nv_Request->get_bool('force_sender', 'post', false));
    $array_config['force_reply'] = intval($nv_Request->get_bool('force_reply', 'post', false));
    $array_config['notify_email_error'] = intval($nv_Request->get_bool('notify_email_error', 'post', false));

    $array_config['sender_email'] = nv_check_valid_email($array_config['sender_email'], true);
    if ($array_config['sender_email'][0] == '') {
        $array_config['sender_email'] = $array_config['sender_email'][1];
    } else {
        $array_config['sender_email'] = '';
    }
    $array_config['reply_email'] = nv_check_valid_email($array_config['reply_email'], true);
    if ($array_config['reply_email'][0] == '') {
        $array_config['reply_email'] = $array_config['reply_email'][1];
    } else {
        $array_config['reply_email'] = '';
    }

    $array_config['smtp_ssl'] = $nv_Request->get_int('smtp_ssl', 'post', 0);
    $array_config['verify_peer_ssl'] = $nv_Request->get_int('verify_peer_ssl', 'post', 0);
    $array_config['verify_peer_name_ssl'] = $nv_Request->get_int('verify_peer_name_ssl', 'post', 0);

    $smtp_password = $array_config['smtp_password'];
    $array_config['smtp_password'] = $crypt->encrypt($smtp_password);

    $sth = $db->prepare("UPDATE " . NV_CONFIG_GLOBALTABLE . " SET config_value = :config_value WHERE lang = 'sys' AND module = 'site' AND config_name = :config_name");
    foreach ($array_config as $config_name => $config_value) {
        $sth->bindParam(':config_name', $config_name, PDO::PARAM_STR, 30);
        $sth->bindParam(':config_value', $config_value, PDO::PARAM_STR);
        $sth->execute();
    }
    $nv_Cache->delMod('settings');

    if ($array_config['smtp_ssl'] == 1 and $array_config['mailer_mode'] == 'smtp') {
        require_once NV_ROOTDIR . '/includes/core/phpinfo.php';
        $array_phpmod = phpinfo_array(8, 1);
        if (!empty($array_phpmod) and !array_key_exists('openssl', $array_phpmod)) {
            $errormess = $lang_module['smtp_error_openssl'];
        }
    }

    if (empty($errormess)) {
        nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op . '&rand=' . nv_genpass());
    }
    $array_config['smtp_password'] = $smtp_password;
} else {
    $array_config['mailer_mode'] = $global_config['mailer_mode'];
    $array_config['smtp_host'] = $global_config['smtp_host'];
    $array_config['smtp_port'] = $global_config['smtp_port'];
    $array_config['smtp_username'] = $global_config['smtp_username'];
    $array_config['smtp_password'] = $global_config['smtp_password'];
    $array_config['sender_name'] = $global_config['sender_name'];
    $array_config['sender_email'] = $global_config['sender_email'];
    $array_config['reply_name'] = $global_config['reply_name'];
    $array_config['reply_email'] = $global_config['reply_email'];
    $array_config['force_sender'] = $global_config['force_sender'];
    $array_config['force_reply'] = $global_config['force_reply'];
    $array_config['smtp_ssl'] = $global_config['smtp_ssl'];
    $array_config['verify_peer_ssl'] = $global_config['verify_peer_ssl'];
    $array_config['verify_peer_name_ssl'] = $global_config['verify_peer_name_ssl'];
    $array_config['notify_email_error'] = $global_config['notify_email_error'];
}

$array_config['smtp_ssl_checked'] = ($array_config['smtp_ssl'] == 1) ? ' checked="checked"' : '';
$array_config['force_sender'] = $array_config['force_sender'] ? ' checked="checked"' : '';
$array_config['force_reply'] = $array_config['force_reply'] ? ' checked="checked"' : '';
$array_config['notify_email_error'] = $array_config['notify_email_error'] ? ' checked="checked"' : '';

$array_config['mailer_mode_smtpt'] = ($array_config['mailer_mode'] == 'smtp') ? ' checked="checked"' : '';
$array_config['mailer_mode_sendmail'] = ($array_config['mailer_mode'] == 'sendmail') ? ' checked="checked"' : '';
$array_config['mailer_mode_phpmail'] = ($array_config['mailer_mode'] == 'mail') ? ' checked="checked"' : '';
$array_config['mailer_mode_no'] = ($array_config['mailer_mode'] == 'no') ? ' checked="checked"' : '';
$array_config['mailer_mode_smtpt_show'] = ($array_config['mailer_mode'] == 'smtp') ? '' : ' style="display: none" ';
$array_config['checkss'] = $checkss;
$xtpl = new XTemplate('smtp.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);

$xtpl->assign('LANG', $lang_module);
$xtpl->assign('DATA', $array_config);
$xtpl->assign('NV_BASE_ADMINURL', NV_BASE_ADMINURL);
$xtpl->assign('NV_NAME_VARIABLE', NV_NAME_VARIABLE);
$xtpl->assign('MODULE_NAME', $module_name);
$xtpl->assign('NV_OP_VARIABLE', NV_OP_VARIABLE);
$xtpl->assign('OP', $op);

if (empty($global_config['idsite'])) {
    $xtpl->parse('smtp.mailhost');
}

foreach ($smtp_encrypted_array as $id => $value) {
    $encrypted = [
        'id' => $id,
        'value' => $value,
        'sl' => ($global_config['smtp_ssl'] == $id) ? ' selected="selected"' : ''
    ];

    $xtpl->assign('EMCRYPTED', $encrypted);
    $xtpl->parse('smtp.encrypted_connection');
}
if ($global_config['verify_peer_ssl'] == 1) {
    $xtpl->assign('PEER_SSL_YES', ' checked="checked"');
} else {
    $xtpl->assign('PEER_SSL_NO', ' checked="checked"');
}
if ($global_config['verify_peer_name_ssl'] == 1) {
    $xtpl->assign('PEER_NAME_SSL_YES', ' checked="checked"');
} else {
    $xtpl->assign('PEER_NAME_SSL_NO', ' checked="checked"');
}
if ($errormess != '') {
    $xtpl->assign('ERROR', $errormess);
    $xtpl->parse('smtp.error');
}

if (!empty($global_config['smtp_host']) and !empty($global_config['smtp_username'])) {
    // Gửi thử email để kiểm tra
    if ($nv_Request->isset_request('submittest', 'post')) {
        $check = nv_sendmail([
            $global_config['site_name'],
            $global_config['site_email']
        ], $admin_info['email'], $lang_module['smtp_test_subject'], $lang_module['smtp_test_message'], '', false, true);
        if (!empty($check)) {
            $xtpl->assign('TEST_MESSAGE', $check);
            $xtpl->parse('smtp.testmail_fail');
        } else {
            $xtpl->parse('smtp.testmail_success');
        }
    }

    $xtpl->parse('smtp.testmail');
    $xtpl->parse('smtp.testmail1');
}

$xtpl->parse('smtp');
$contents = $xtpl->text('smtp');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
