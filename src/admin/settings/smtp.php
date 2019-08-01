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

$page_title = $nv_Lang->getModule('smtp_config');
$smtp_encrypted_array = [];
$smtp_encrypted_array[0] = 'None';
$smtp_encrypted_array[1] = 'SSL';
$smtp_encrypted_array[2] = 'TLS';

$array_config = [];
$errormess = '';
$array_config['mailer_mode'] = nv_substr($nv_Request->get_title('mailer_mode', 'post', $global_config['mailer_mode'], 1), 0, 255);
$array_config['smtp_host'] = nv_substr($nv_Request->get_title('smtp_host', 'post', $global_config['smtp_host'], 1), 0, 255);
$array_config['smtp_port'] = nv_substr($nv_Request->get_title('smtp_port', 'post', $global_config['smtp_port'], 1), 0, 255);
$array_config['smtp_username'] = nv_substr($nv_Request->get_title('smtp_username', 'post', $global_config['smtp_username']), 0, 255);
$array_config['smtp_password'] = nv_substr($nv_Request->get_title('smtp_password', 'post', $global_config['smtp_password']), 0, 255);

if ($nv_Request->isset_request('mailer_mode', 'post')) {
    $array_config['smtp_ssl'] = $nv_Request->get_int('smtp_ssl', 'post', 0);
} else {
    $array_config['smtp_ssl'] = intval($global_config['smtp_ssl']);
}
$array_config['verify_peer_ssl'] = $nv_Request->get_int('verify_peer_ssl', 'post', $global_config['verify_peer_ssl']);
$array_config['verify_peer_name_ssl'] = $nv_Request->get_int('verify_peer_name_ssl', 'post', $global_config['verify_peer_name_ssl']);

if ($nv_Request->isset_request('submitsave', 'post')) {
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
            $errormess = $nv_Lang->getModule('smtp_error_openssl');
        }
    }

    if (empty($errormess)) {
        nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op . '&rand=' . nv_genpass());
    }
    $array_config['smtp_password'] = $smtp_password;
}

$tpl = new \NukeViet\Template\Smarty();
$tpl->setTemplateDir(NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$tpl->assign('LANG', $nv_Lang);
$tpl->assign('FORM_ACTION', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op);
$tpl->assign('DATA', $array_config);
$tpl->assign('SMTP_ENCRYPTED', $smtp_encrypted_array);
$tpl->assign('GLOBAL_CONFIG', $global_config);
$tpl->assign('ERROR', $errormess);

$testMailMessage = '';
$testMailSubmit = false;

if (!empty($global_config['smtp_host']) and !empty($global_config['smtp_username'])) {
    // Gửi thử email để kiểm tra
    if ($nv_Request->isset_request('submittest', 'post')) {
        $testMailSubmit = true;
        $testMailMessage = nv_sendmail([$global_config['site_name'], $global_config['site_email']], $admin_info['email'], $nv_Lang->getModule('smtp_test_subject'), $nv_Lang->getModule('smtp_test_message'), '', false, '', '', true);
    }
}

$tpl->assign('TESTMAILMESSAGE', $testMailMessage);
$tpl->assign('TESTMAILSUBMIT', $testMailSubmit);

$contents = $tpl->fetch('smtp.tpl');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
