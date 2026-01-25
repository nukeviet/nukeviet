<?php

/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_IS_FILE_DATABASE')) {
    exit('Stop!!!');
}

$page_title = $nv_Lang->getModule('file_backup');

$log_dir = NV_ROOTDIR . '/' . NV_LOGS_DIR . '/dump_backup';
if ($global_config['idsite']) {
    $log_dir .= '/' . $global_config['site_dir'];
}

$array_content = [];
$files = scandir($log_dir);

$check_exists = [];
foreach ($files as $file) {
    // Lấy các file sql,gz xếp theo thời gian giảm dần và đổi tên để che path
    if (preg_match('/^([a-zA-Z0-9\-\_\.]+)\.(sql|sql\.gz)+$/', $file, $mc)) {
        $filetime = (int) (filemtime($log_dir . '/' . $file));
        if (!isset($check_exists[$filetime])) {
            $check_exists[$filetime] = 0;
        }
        $check_exists[$filetime]++;

        $name = date('Y-m-d-H-i-s', $filetime) . ($check_exists[$filetime] > 1 ? (' (' . $check_exists[$filetime] . ')') : '');
        $ext = nv_getextension($file);
        $ext = $ext == 'sql' ? $ext : 'sql.gz';
        $array_content[$filetime][] = [
            'ext' => $ext,
            'name' => $name . '.' . $ext,
            'oname' => $name,
            'file' => $file,
            'path' => $log_dir . '/' . $file,
            'filesize' => filesize($log_dir . '/' . $file)
        ];
    }
}

// Tải từ phần update ngoài site
if ($nv_Request->isset_request('getbackup,t,p,ext', 'get')) {
    $time = $nv_Request->get_absint('t', 'get', 0);
    $passphrase = $nv_Request->get_title('p', 'get', '');
    $ext = $nv_Request->get_title('ext', 'get', '');
    $checkss = $nv_Request->get_title('checkss', 'get', '');

    $name = date('Y-m-d-H-i-s', $time);
    $filename = $name . '_' . md5($passphrase . NV_CHECK_SESSION) . '.' . $ext;
    $path = $log_dir . '/' . $filename;
    if ($checkss !== md5($filename . NV_CHECK_SESSION) or !nv_is_file(NV_BASE_SITEURL . str_replace(NV_ROOTDIR . '/', '', $path), str_replace(NV_ROOTDIR . '/', '', $log_dir))) {
        nv_info_die($nv_Lang->getGlobal('error_404_title'), $nv_Lang->getGlobal('error_404_title'), $nv_Lang->getGlobal('error_404_content'), 403);
    }

    nv_insert_logs(NV_LANG_DATA, $module_name, $nv_Lang->getModule('download'), 'File name: ' . basename($path), $admin_info['userid']);

    $name = change_alias($name) . '.' . $ext;
    $download = new NukeViet\Files\Download($path, $log_dir, $name);
    $download->download_file();
}

// Tải về từ phần quản lý trong quản trị
if ($nv_Request->isset_request('getbackup,index,checkss', 'get')) {
    $filetime = $nv_Request->get_absint('getbackup', 'get', 0);
    $index = $nv_Request->get_absint('index', 'get', 0);
    $checkss = $nv_Request->get_title('checkss', 'get', '');

    if (isset($array_content[$filetime], $array_content[$filetime][$index]) and md5($filetime . $index . NV_CHECK_SESSION) === $checkss) {
        $file = $array_content[$filetime][$index];

        nv_insert_logs(NV_LANG_DATA, $module_name, $nv_Lang->getModule('download'), 'File name: ' . basename($file['path']), $admin_info['userid']);

        // Download file
        $name = change_alias($file['oname']) . '.' . $file['ext'];
        $download = new NukeViet\Files\Download($file['path'], $log_dir, $name);
        $download->download_file();
    }

    nv_info_die($nv_Lang->getGlobal('error_404_title'), $nv_Lang->getGlobal('error_404_title'), $nv_Lang->getGlobal('error_404_content'), 403);
}

// Xóa
if ($nv_Request->isset_request('delbackup,index,checkss', 'get')) {
    $filetime = $nv_Request->get_absint('delbackup', 'get', 0);
    $index = $nv_Request->get_absint('index', 'get', 0);
    $checkss = $nv_Request->get_title('checkss', 'get', '');

    $respon = [
        'error' => 1,
        'message' => 'Wrong Session or Data!!!'
    ];

    if (isset($array_content[$filetime], $array_content[$filetime][$index]) and md5($filetime . $index . NV_CHECK_SESSION) === $checkss) {
        $file = $array_content[$filetime][$index];
        nv_insert_logs(NV_LANG_DATA, $module_name, $nv_Lang->getGlobal('delete') . ' ' . $nv_Lang->getModule('file_backup'), 'File name: ' . basename($file['path']), $admin_info['userid']);
        nv_deletefile($file['path']);

        $respon['error'] = 0;
    }

    nv_jsonOutput($respon);
}

krsort($array_content);
foreach ($array_content as $filetime => $files) {
    krsort($files);
    foreach ($files as $file_index => $file) {
        $files[$file_index]['checkss'] = md5($filetime . $file_index . NV_CHECK_SESSION);
    }
    $array_content[$filetime] = $files;
}

$tpl = new \NukeViet\Template\NVSmarty();
$tpl->setTemplateDir(get_module_tpl_dir('files.tpl'));
$tpl->registerPlugin('modifier', 'displaySize', 'nv_convertfromBytes');
$tpl->registerPlugin('modifier', 'displayTime', 'nv_datetime_format');
$tpl->assign('LANG', $nv_Lang);
$tpl->assign('MODULE_NAME', $module_name);
$tpl->assign('OP', $op);
$tpl->assign('ARRAY', $array_content);

$contents = $tpl->fetch('files.tpl');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
