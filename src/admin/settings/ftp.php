<?php

/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_ADMIN') or !defined('NV_MAINFILE') or !defined('NV_IS_MODADMIN')) {
    exit('Stop!!!');
}

$page_title = $nv_Lang->getModule('ftp_config');

if (!$sys_info['ftp_support']) {
    $contents = nv_theme_alert('', $nv_Lang->getModule('ftp_error_support'), 'danger');
    include NV_ROOTDIR . '/includes/header.php';
    echo nv_admin_theme($contents);
    include NV_ROOTDIR . '/includes/footer.php';
}

// Tự động nhận diện Remove Path
if ($nv_Request->isset_request('autodetect', 'post')) {
    $ftp_server = nv_unhtmlspecialchars($nv_Request->get_title('ftp_server', 'post', '', 1));
    $ftp_port = $nv_Request->get_int('ftp_port', 'post');
    $ftp_user_name = nv_unhtmlspecialchars($nv_Request->get_title('ftp_user_name', 'post', '', 1));
    $ftp_user_pass = nv_unhtmlspecialchars($nv_Request->get_title('ftp_user_pass', 'post', '', 0));
    $ftp_user_pass == '******' && $ftp_user_pass = $global_config['ftp_user_pass'];

    if (empty($ftp_server) or empty($ftp_user_name) or empty($ftp_user_pass)) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => $nv_Lang->getModule('ftp_error_full')
        ]);
    }

    $ftp = new NukeViet\Ftp\Ftp($ftp_server, $ftp_user_name, $ftp_user_pass, ['timeout' => 10], $ftp_port);

    if (!empty($ftp->error)) {
        $ftp->close();
        nv_jsonOutput([
            'status' => 'error',
            'mess' => (string) $ftp->error
        ]);
    }
    $list_valid = [NV_ASSETS_DIR, 'includes', 'modules', 'themes', 'vendor'];
    $ftp_root = $ftp->detectFtpRoot($list_valid, NV_ROOTDIR);

    if ($ftp_root === false) {
        $ftp->close();
        nv_jsonOutput([
            'status' => 'error',
            'mess' => empty($ftp->error) ? $nv_Lang->getModule('ftp_error_detect_root') : (string) $ftp->error
        ]);
    }

    $ftp->close();
    nv_jsonOutput([
        'status' => 'OK',
        'mess' => $ftp_root
    ]);
}

// Lưu cấu hình
$checkss = md5(NV_CHECK_SESSION . '_' . $module_name . '_' . $op . '_' . $admin_info['userid']);
if ($nv_Request->isset_request('ftp_server', 'post') and $checkss == $nv_Request->get_string('checkss', 'post')) {
    $post = [
        'ftp_server' => $nv_Request->get_title('ftp_server', 'post', '', 1),
        'ftp_port' => $nv_Request->get_int('ftp_port', 'post'),
        'ftp_user_name' => $nv_Request->get_title('ftp_user_name', 'post', '', 1),
        'ftp_user_pass' => $nv_Request->get_title('ftp_user_pass', 'post', '', 0),
        'ftp_path' => $nv_Request->get_title('ftp_path', 'post', '', 1)
    ];

    $post['ftp_user_pass'] == '******' && $post['ftp_user_pass'] = $global_config['ftp_user_pass'];

    if (empty($post['ftp_server'])) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => $nv_Lang->getModule('server_empty')
        ]);
    }

    if (empty($post['ftp_user_name'])) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => $nv_Lang->getModule('username_empty')
        ]);
    }

    if (empty($post['ftp_user_pass'])) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => $nv_Lang->getModule('password_empty')
        ]);
    }

    $ftp = new NukeViet\Ftp\Ftp(nv_unhtmlspecialchars($post['ftp_server']), nv_unhtmlspecialchars($post['ftp_user_name']), nv_unhtmlspecialchars($post['ftp_user_pass']), ['timeout' => 10], $post['ftp_port']);

    if (!empty($ftp->error)) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => (string) $ftp->error
        ]);
    }

    $ftp_path = nv_unhtmlspecialchars($post['ftp_path']);
    if ($ftp->chdir($ftp_path) === false) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => $nv_Lang->getGlobal('ftp_error_path')
        ]);
    }

    $check_files = [NV_ASSETS_DIR, 'includes', 'index.php', 'modules', 'themes'];
    $list_files = $ftp->listDetail($ftp_path, 'all');

    $a = 0;
    if (!empty($list_files)) {
        foreach ($list_files as $filename) {
            if (in_array($filename['name'], $check_files, true)) {
                ++$a;
            }
        }
    }

    if ($a !== count($check_files)) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => $nv_Lang->getGlobal('ftp_error_path')
        ]);
    }

    $ftp->close();

    $post['ftp_check_login'] = 1;
    $post['ftp_user_pass'] = $crypt->encrypt($post['ftp_user_pass']);

    $sth = $db->prepare('UPDATE ' . NV_CONFIG_GLOBALTABLE . " SET config_value= :config_value WHERE config_name = :config_name AND lang = 'sys' AND module='global'");
    foreach ($post as $config_name => $config_value) {
        $sth->bindParam(':config_name', $config_name, PDO::PARAM_STR, 30);
        $sth->bindParam(':config_value', $config_value, PDO::PARAM_STR);
        $sth->execute();
    }

    nv_save_file_config_global();
    nv_jsonOutput([
        'status' => 'OK',
        'mess' => $nv_Lang->getGlobal('save_success')
    ]);
}

$data = [
    'ftp_server' => $global_config['ftp_server'],
    'ftp_port' => $global_config['ftp_port'],
    'ftp_user_name' => $global_config['ftp_user_name'],
    'ftp_user_pass' => '******',
    'ftp_path' => $global_config['ftp_path']
];

$tpl = new \NukeViet\Template\NVSmarty();
$tpl->setTemplateDir(get_module_tpl_dir('ftp.tpl'));
$tpl->assign('LANG', $nv_Lang);
$tpl->assign('MODULE_NAME', $module_name);
$tpl->assign('OP', $op);

$tpl->assign('CHECKSS', $checkss);
$tpl->assign('DATA', $data);

$contents = $tpl->fetch('ftp.tpl');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
