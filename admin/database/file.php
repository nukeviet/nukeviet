<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2021 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_IS_FILE_DATABASE')) {
    exit('Stop!!!');
}

$log_dir = NV_ROOTDIR . '/' . NV_LOGS_DIR . '/dump_backup';
if ($global_config['idsite']) {
    $log_dir .= '/' . $global_config['site_dir'];
}

$xtpl = new XTemplate('files.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('GLANG', $lang_global);

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

// Tải về
if ($nv_Request->isset_request('getbackup,index,checkss', 'get')) {
    $filetime = $nv_Request->get_absint('getbackup', 'get', 0);
    $index = $nv_Request->get_absint('index', 'get', 0);
    $checkss = $nv_Request->get_title('checkss', 'get', '');

    if (isset($array_content[$filetime], $array_content[$filetime][$index]) and md5($filetime . $index . NV_CHECK_SESSION) === $checkss) {
        $file = $array_content[$filetime][$index];

        nv_insert_logs(NV_LANG_DATA, $module_name, $lang_module['download'], 'File name: ' . basename($file['path']), $admin_info['userid']);

        // Download file
        $name = change_alias($file['oname']) . '.' . $file['ext'];
        $download = new NukeViet\Files\Download($file['path'], $log_dir, $name);
        $download->download_file();
    }

    nv_info_die($lang_global['error_404_title'], $lang_global['error_404_title'], $lang_global['error_404_content'], 403);
}

// Xóa
if ($nv_Request->isset_request('delbackup,index,checkss', 'get')) {
    $filetime = $nv_Request->get_absint('delbackup', 'get', 0);
    $index = $nv_Request->get_absint('index', 'get', 0);
    $checkss = $nv_Request->get_title('checkss', 'get', '');

    if (isset($array_content[$filetime], $array_content[$filetime][$index]) and md5($filetime . $index . NV_CHECK_SESSION) === $checkss) {
        $file = $array_content[$filetime][$index];
        nv_insert_logs(NV_LANG_DATA, $module_name, $lang_global['delete'] . ' ' . $lang_module['file_backup'], 'File name: ' . basename($file['path']), $admin_info['userid']);
        nv_deletefile($file['path']);
        nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op . '&rand=' . nv_genpass());
    }

    nv_info_die($lang_global['error_404_title'], $lang_global['error_404_title'], $lang_global['error_404_content'], 403);
}

krsort($array_content);

$stt = 0;
foreach ($array_content as $filetime => $files) {
    krsort($files);

    foreach ($files as $file_index => $file) {
        $link_getfile = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;getbackup=' . $filetime . '&amp;index=' . $file_index . '&amp;checkss=' . md5($filetime . $file_index . NV_CHECK_SESSION);
        $link_delete = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;delbackup=' . $filetime . '&amp;index=' . $file_index . '&amp;checkss=' . md5($filetime . $file_index . NV_CHECK_SESSION);

        $xtpl->assign('ROW', [
            'stt' => ++$stt,
            'name' => $file['name'],
            'filesize' => nv_convertfromBytes($file['filesize']),
            'filetime' => nv_date('l d/m/Y h:i:s A', $filetime),
            'link_getfile' => $link_getfile,
            'link_delete' => $link_delete
        ]);

        $xtpl->parse('main.loop');
    }
}

$xtpl->assign('BACKUPNOW', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=download&amp;checkss=' . NV_CHECK_SESSION);
$page_title = $lang_module['file_backup'];

$xtpl->parse('main');
$contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
