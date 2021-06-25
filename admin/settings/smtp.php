<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2021 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_ADMIN') or !defined('NV_MAINFILE') or !defined('NV_IS_MODADMIN')) {
    exit('Stop!!!');
}

$page_title = $lang_module['smtp_config'];
$checkss = md5(NV_CHECK_SESSION . '_' . $module_name . '_' . $op . '_' . $admin_info['userid']);

$smtp_encrypted_array = [];
$smtp_encrypted_array[0] = 'None';
$smtp_encrypted_array[1] = 'SSL';
$smtp_encrypted_array[2] = 'TLS';

$array_config = [];
$errormess = '';
$cert_list = nv_scandir(NV_ROOTDIR . '/' . NV_CERTS_DIR, '/^(.+)[\_]{2}(.+)\.crt/', 1);
!empty($cert_list) && $cert_list = array_map(function ($email) {
    return substr(str_replace('__', '@', $email), 0, -4);
}, $cert_list);

$dkim_list = nv_scandir(NV_ROOTDIR . '/' . NV_CERTS_DIR, '/^nv\_dkim\.(.+)\.public\.pem/', 1);
!empty($dkim_list) && $dkim_list = array_map(function ($domain) {
    return substr($domain, 8, -11);
}, $dkim_list);

$dkim_verified_list = nv_scandir(NV_ROOTDIR . '/' . NV_CERTS_DIR, '/^nv\_dkim\.(.+)\.verified/', 1);
!empty($dkim_verified_list) && $dkim_verified_list = array_map(function ($domain) {
    return substr($domain, 8, -9);
}, $dkim_verified_list);

// Doc public key cua DKIM
if ($nv_Request->isset_request('dkimread, domain', 'post')) {
    $domain = $nv_Request->get_title('domain', 'post', '');

    if (!in_array($domain, $dkim_list, true)) {
        exit(0);
    }

    $xtpl = new XTemplate('smtp.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
    $xtpl->assign('LANG', $lang_module);
    $xtpl->assign('DOMAIN', $domain);

    $publickeyfile = NV_ROOTDIR . '/' . NV_CERTS_DIR . '/nv_dkim.' . $domain . '.public.pem';
    $publickey = file_get_contents($publickeyfile);
    $publickey = preg_replace('/^-+.*?-+$/m', '', $publickey);
    $publickey = str_replace(["\r", "\n"], '', $publickey);
    $publickey = str_split($publickey, 253);
    $publickey = 'v=DKIM1; h=sha256; t=s; p=' . implode('', $publickey);
    $xtpl->assign('DNSVALUE', $publickey);
    $is_verified = (!empty($dkim_verified_list) and in_array($domain, $dkim_verified_list, true));

    if ($is_verified) {
        $xtpl->parse('dkimread.verified');
    } else {
        $xtpl->parse('dkimread.unverified');
    }

    $xtpl->parse('dkimread');
    $contents = $xtpl->text('dkimread');
    include NV_ROOTDIR . '/includes/header.php';
    echo $contents;
    include NV_ROOTDIR . '/includes/footer.php';
}

// Kiem tra DKIM
if ($nv_Request->isset_request('dkimverify, domain', 'post')) {
    $domain = $nv_Request->get_title('domain', 'post', '');

    if (!in_array($domain, $dkim_list, true)) {
        exit(0);
    }

    $verifiedkey = NV_ROOTDIR . '/' . NV_CERTS_DIR . '/nv_dkim.' . $domain . '.verified';
    $verified = DKIM_verify($domain, 'nv');
    if (!$verified) {
        file_exists($verifiedkey) && @unlink($verifiedkey);
        nv_jsonOutput([
            'status' => 'error',
            'mess' => $lang_module['DKIM_unverified']
        ]);
    }

    file_put_contents($verifiedkey, NV_CURRENTTIME, LOCK_EX);
    nv_jsonOutput([
        'status' => 'OK',
        'mess' => $lang_module['DKIM_verified']
    ]);
}

// Xoa DKIM
if ($nv_Request->isset_request('dkimdel, domain', 'post')) {
    $domain = $nv_Request->get_title('domain', 'post', '');

    if (!in_array($domain, $dkim_list, true)) {
        exit(0);
    }

    $privatekeyfile = NV_ROOTDIR . '/' . NV_CERTS_DIR . '/nv_dkim.' . $domain . '.private.pem';
    $publickeyfile = NV_ROOTDIR . '/' . NV_CERTS_DIR . '/nv_dkim.' . $domain . '.public.pem';
    $verifiedkey = NV_ROOTDIR . '/' . NV_CERTS_DIR . '/nv_dkim.' . $domain . '.verified';

    if (file_exists($privatekeyfile)) {
        nv_deletefile($privatekeyfile);
    }
    if (file_exists($publickeyfile)) {
        nv_deletefile($publickeyfile);
    }
    if (file_exists($verifiedkey)) {
        nv_deletefile($verifiedkey);
    }
    echo 'OK';
    exit(0);
}

// Them DKIM
if ($nv_Request->isset_request('dkimadd', 'post') and $checkss == $nv_Request->get_string('checkss', 'post')) {
    $domain = $nv_Request->get_title('domain', 'post', '');
    $domain = nv_check_domain($domain);
    if (empty($domain)) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => $lang_module['DKIM_domain_error']
        ]);
    }

    $privatekeyfile = NV_ROOTDIR . '/' . NV_CERTS_DIR . '/nv_dkim.' . $domain . '.private.pem';
    $publickeyfile = NV_ROOTDIR . '/' . NV_CERTS_DIR . '/nv_dkim.' . $domain . '.public.pem';

    if (file_exists($privatekeyfile) or file_exists($publickeyfile)) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => $lang_module['DKIM_domain_exists']
        ]);
    }

    $pk = openssl_pkey_new(
        [
            'digest_alg' => 'sha256',
            'private_key_bits' => 2048,
            'private_key_type' => OPENSSL_KEYTYPE_RSA,
        ]
    );
    openssl_pkey_export_to_file($pk, $privatekeyfile);
    $pubKey = openssl_pkey_get_details($pk);
    file_put_contents($publickeyfile, $pubKey['key']);
    nv_jsonOutput([
        'status' => 'OK',
        'mess' => sprintf($lang_module['DKIM_created'], $domain)
    ]);
}

// Doc chung chi
if ($nv_Request->isset_request('smimeread, email', 'post')) {
    $email = $nv_Request->get_title('email', 'post', '');

    if (!in_array($email, $cert_list, true)) {
        exit(0);
    }

    $xtpl = new XTemplate('smtp.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
    $xtpl->assign('LANG', $lang_module);
    $xtpl->assign('EMAIL', $email);

    $email_name = str_replace('@', '__', $email);
    $cert_crt = file_get_contents(NV_ROOTDIR . '/' . NV_CERTS_DIR . '/' . $email_name . '.crt');
    $certPriv = openssl_x509_parse(openssl_x509_read($cert_crt));
    $certPriv['validFrom_format'] = date('d/m/Y', $certPriv['validFrom_time_t']);
    $certPriv['validTo_format'] = date('d/m/Y', $certPriv['validTo_time_t']);
    $certPriv['purposes_list'] = [];
    foreach ($certPriv['purposes'] as $purpose) {
        if ($purpose[0]) {
            $val = $purpose[2];
            if ($purpose[1]) {
                $val .= ' (CA)';
            }
            $certPriv['purposes_list'][] = $val;
        }
    }
    $certPriv['purposes_list'] = !empty($certPriv['purposes_list']) ? implode(', ', $certPriv['purposes_list']) : '';

    $xtpl->assign('SMIMEREAD', $certPriv);
    $xtpl->parse('smimeread');
    $contents = $xtpl->text('smimeread');
    include NV_ROOTDIR . '/includes/header.php';
    echo $contents;
    include NV_ROOTDIR . '/includes/footer.php';
}

// Xoa chung chi
if ($nv_Request->isset_request('smimedel, email', 'post')) {
    $email = $nv_Request->get_title('email', 'post', '');
    if (!in_array($email, $cert_list, true)) {
        exit(0);
    }
    $email_name = str_replace('@', '__', $email);
    if (file_exists(NV_ROOTDIR . '/' . NV_CERTS_DIR . '/' . $email_name . '.crt')) {
        nv_deletefile(NV_ROOTDIR . '/' . NV_CERTS_DIR . '/' . $email_name . '.crt');
    }
    if (file_exists(NV_ROOTDIR . '/' . NV_CERTS_DIR . '/' . $email_name . '.key')) {
        nv_deletefile(NV_ROOTDIR . '/' . NV_CERTS_DIR . '/' . $email_name . '.key');
    }
    if (file_exists(NV_ROOTDIR . '/' . NV_CERTS_DIR . '/' . $email_name . '.pem')) {
        nv_deletefile(NV_ROOTDIR . '/' . NV_CERTS_DIR . '/' . $email_name . '.pem');
    }
    echo 'OK';
    exit(0);
}

// Them chung chi
if ($nv_Request->isset_request('smimeadd', 'post') and $checkss == $nv_Request->get_string('checkss', 'post')) {
    $passphrase = $nv_Request->get_string('passphrase', 'post', '');
    $upload = new NukeViet\Files\Upload(['certificate'], $global_config['forbid_extensions'], $global_config['forbid_mimes']);
    $upload->setLanguage($lang_global);
    $upload_info = $upload->save_file($_FILES['pkcs12'], NV_ROOTDIR . '/' . NV_CERTS_DIR, true);

    if (is_file($_FILES['pkcs12']['tmp_name'])) {
        @unlink($_FILES['pkcs12']['tmp_name']);
    }

    if (!empty($upload_info['error'])) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => $upload_info['error']
        ]);
    }

    if (!in_array($upload_info['ext'], ['pfx', 'p12'], true)) {
        @unlink($upload_info['name']);
        nv_jsonOutput([
            'status' => 'error',
            'mess' => $lang_module['smime_pkcs12_ext_error']
        ]);
    }

    $results = [];
    if (!openssl_pkcs12_read(file_get_contents($upload_info['name']), $results, $passphrase) or empty($results['cert']) or empty($results['pkey'])) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => $lang_module['smime_pkcs12_cannot_be_read'] . ' (' . openssl_error_string() . ')'
        ]);
    }

    $certPriv = openssl_x509_parse(openssl_x509_read($results['cert']));
    $smimesign = false;
    foreach ($certPriv['purposes'] as $purpose) {
        if ($purpose[0] == '1' and $purpose[2] == 'smimesign') {
            $smimesign = true;
            break;
        }
    }
    if (!$smimesign) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => $lang_module['smime_pkcs12_smimesign_error']
        ]);
    }

    $email = trim($certPriv['subject']['CN']);
    $email_name = str_replace('@', '__', $email);
    $cert_key = NV_ROOTDIR . '/' . NV_CERTS_DIR . '/' . $email_name . '.key';
    $cert_crt = NV_ROOTDIR . '/' . NV_CERTS_DIR . '/' . $email_name . '.crt';
    $certchain_pem = NV_ROOTDIR . '/' . NV_CERTS_DIR . '/' . $email_name . '.pem';

    $overwrite = $nv_Request->get_int('overwrite', 'post', 0);
    if (file_exists($cert_crt) and !$overwrite) {
        @unlink($upload_info['name']);
        nv_jsonOutput([
            'status' => 'overwrite',
            'mess' => $lang_module['smime_pkcs12_overwrite']
        ]);
    }

    file_put_contents($cert_key, $results['pkey'], LOCK_EX);
    file_put_contents($cert_crt, $results['cert'], LOCK_EX);
    if (!empty($results['extracerts'])) {
        $extracerts = implode('', $results['extracerts']);
        file_put_contents($certchain_pem, $extracerts, LOCK_EX);
    }

    @unlink($upload_info['name']);

    nv_jsonOutput([
        'status' => 'ok',
        'mess' => 'OK'
    ]);
}

// Download chung chi
if ($nv_Request->isset_request('smimedownload, email, passphrase', 'get')) {
    $email = $nv_Request->get_title('email', 'get', '');
    if (!in_array($email, $cert_list, true)) {
        exit(0);
    }

    $passphrase = $nv_Request->get_string('passphrase', 'get', '');
    if (empty($passphrase)) {
        exit(0);
    }

    $email_name = str_replace('@', '__', $email);
    $cert_key = NV_ROOTDIR . '/' . NV_CERTS_DIR . '/' . $email_name . '.key';
    $cert_crt = NV_ROOTDIR . '/' . NV_CERTS_DIR . '/' . $email_name . '.crt';
    $certchain_pem = NV_ROOTDIR . '/' . NV_CERTS_DIR . '/' . $email_name . '.pem';

    $cerificate_out = null;
    $signed_csr = file_get_contents($cert_crt);
    $private_key_resource = file_get_contents($cert_key);
    $pemChain = file_get_contents($certchain_pem);
    $matches = [];
    preg_match_all('/(-----BEGIN CERTIFICATE-----.*?-----END CERTIFICATE-----)/si', $pemChain, $matches);
    $args = ['extracerts' => $matches[0]];
    openssl_pkcs12_export($signed_csr, $cerificate_out, $private_key_resource, $passphrase, $args);
    $file_src = NV_ROOTDIR . '/' . NV_TEMP_DIR . '/' . NV_TEMPNAM_PREFIX . $email_name . '_' . md5(nv_genpass(10) . NV_CHECK_SESSION) . '.pfx';
    file_put_contents($file_src, $cerificate_out, LOCK_EX);
    $filesize = @filesize($file_src);
    if ($filesize > 0) {
        $download = new NukeViet\Files\Download($file_src, NV_ROOTDIR . '/' . NV_TEMP_DIR, $email_name . '.pfx');
        $download->download_file();
        exit();
    }
    exit(0);
}

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
    $array_config['force_sender'] = (int) ($nv_Request->get_bool('force_sender', 'post', false));
    $array_config['force_reply'] = (int) ($nv_Request->get_bool('force_reply', 'post', false));
    $array_config['notify_email_error'] = (int) ($nv_Request->get_bool('notify_email_error', 'post', false));
    $array_config['dkim_included'] = $nv_Request->get_typed_array('dkim_included', 'post', 'title');
    $array_config['smime_included'] = $nv_Request->get_typed_array('smime_included', 'post', 'title');

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

    $array_config['dkim_included'] = !empty($array_config['dkim_included']) ? implode(',', $array_config['dkim_included']) : '';
    $array_config['smime_included'] = !empty($array_config['smime_included']) ? implode(',', $array_config['smime_included']) : '';

    $sth = $db->prepare('UPDATE ' . NV_CONFIG_GLOBALTABLE . " SET config_value = :config_value WHERE lang = 'sys' AND module = 'site' AND config_name = :config_name");
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
    $array_config['dkim_included'] = !empty($global_config['dkim_included']) ? explode(',', $global_config['dkim_included']) : [];
    $array_config['smime_included'] = !empty($global_config['smime_included']) ? explode(',', $global_config['smime_included']) : [];
}

$array_config['smtp_ssl_checked'] = ($array_config['smtp_ssl'] == 1) ? ' checked="checked"' : '';
$array_config['force_sender'] = $array_config['force_sender'] ? ' checked="checked"' : '';
$array_config['force_reply'] = $array_config['force_reply'] ? ' checked="checked"' : '';
$array_config['notify_email_error'] = $array_config['notify_email_error'] ? ' checked="checked"' : '';
$array_config['smtp_dkim_included'] = in_array('smtp', $array_config['dkim_included'], true) ? ' checked="checked"' : '';
$array_config['sendmail_dkim_included'] = in_array('sendmail', $array_config['dkim_included'], true) ? ' checked="checked"' : '';
$array_config['mail_dkim_included'] = in_array('mail', $array_config['dkim_included'], true) ? ' checked="checked"' : '';
$array_config['smtp_smime_included'] = in_array('smtp', $array_config['smime_included'], true) ? ' checked="checked"' : '';
$array_config['sendmail_smime_included'] = in_array('sendmail', $array_config['smime_included'], true) ? ' checked="checked"' : '';
$array_config['mail_smime_included'] = in_array('mail', $array_config['smime_included'], true) ? ' checked="checked"' : '';

$array_config['mailer_mode_smtpt'] = ($array_config['mailer_mode'] == 'smtp') ? ' checked="checked"' : '';
$array_config['mailer_mode_sendmail'] = ($array_config['mailer_mode'] == 'sendmail') ? ' checked="checked"' : '';
$array_config['mailer_mode_phpmail'] = ($array_config['mailer_mode'] == 'mail') ? ' checked="checked"' : '';
$array_config['mailer_mode_no'] = ($array_config['mailer_mode'] == 'no') ? ' checked="checked"' : '';
$array_config['mailer_mode_smtpt_show'] = ($array_config['mailer_mode'] == 'smtp') ? '' : ' style="display: none" ';
$array_config['checkss'] = $checkss;

$s = $nv_Request->get_title('s', 'get', '');
$d = $nv_Request->get_title('d', 'get', '');
$did = '';

$xtpl = new XTemplate('smtp.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);

$xtpl->assign('LANG', $lang_module);
$xtpl->assign('DATA', $array_config);
$xtpl->assign('NV_BASE_ADMINURL', NV_BASE_ADMINURL);
$xtpl->assign('NV_NAME_VARIABLE', NV_NAME_VARIABLE);
$xtpl->assign('MODULE_NAME', $module_name);
$xtpl->assign('NV_OP_VARIABLE', NV_OP_VARIABLE);
$xtpl->assign('SMIMEADD_ACTION', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op);
$xtpl->assign('DKIMADD_ACTION', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op);
$xtpl->assign('OP', $op);

if (empty($global_config['idsite'])) {
    $xtpl->parse('smtp.mailhost');
    $xtpl->parse('smtp.mailhost2');
    $xtpl->parse('smtp.mailhost3');
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

if (!empty($dkim_list)) {
    foreach ($dkim_list as $num => $domain) {
        if ($domain == $d) {
            $did = $num;
        }
        $is_verified = (!empty($dkim_verified_list) and in_array($domain, $dkim_verified_list, true));
        $xtpl->assign('DKIM', [
            'domain' => $domain,
            'num' => $num,
            'title' => $is_verified ? $lang_module['DKIM_verified'] : $lang_module['DKIM_unverified']
        ]);

        if ($is_verified) {
            $xtpl->parse('smtp.dkim_list.loop.if_verified');
        } else {
            $xtpl->parse('smtp.dkim_list.loop.if_unverified');
        }
        $xtpl->parse('smtp.dkim_list.loop');
    }
    $xtpl->parse('smtp.dkim_list');
}

if (!empty($cert_list)) {
    foreach ($cert_list as $num => $email) {
        $xtpl->assign('CERT', [
            'email' => $email,
            'num' => $num
        ]);
        $xtpl->parse('smtp.cert_list.loop');
    }
    $xtpl->parse('smtp.cert_list');
}

if (!empty($s)) {
    if ($did != '') {
        $xtpl->assign('DID', $did);
        $xtpl->parse('smtp.dcl');
    } else {
        $xtpl->assign('S', $s);
        $xtpl->parse('smtp.scroll');
    }
}

$xtpl->parse('smtp');
$contents = $xtpl->text('smtp');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
