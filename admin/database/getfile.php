<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 2-2-2010 12:55
 */

if (! defined('NV_IS_FILE_DATABASE')) {
    die('Stop!!!');
}

$filename = $nv_Request->get_title('filename', 'get', '');
$checkss = $nv_Request->get_title('checkss', 'get', '');

$log_dir = NV_LOGS_DIR . '/dump_backup';
if ($global_config['idsite']) {
    $log_dir .= '/' . $global_config['site_dir'];
}

$path_filename = NV_BASE_SITEURL . '/' . $log_dir . '/' . $filename;

if (nv_is_file($path_filename, $log_dir) === true and $checkss == md5($filename . NV_CHECK_SESSION)) {
    nv_insert_logs(NV_LANG_DATA, $module_name, $lang_module['download'], 'File name: ' . basename($filename), $admin_info['userid']);

    //Download file
    $name = basename($path_filename);
    $name_arr = explode('_', $name);

    if (sizeof($name_arr) > 1 and strlen($name_arr[0]) == 32) {
        $name = substr($name, 33);
    }

    $download = new NukeViet\Files\Download(NV_DOCUMENT_ROOT . $path_filename, NV_ROOTDIR . '/' . NV_LOGS_DIR . '/dump_backup', $name);
    $download->download_file();
    exit();
} else {
    $contents = 'File not exist !';

    include NV_ROOTDIR . '/includes/header.php';
    echo nv_admin_theme($contents);
    include NV_ROOTDIR . '/includes/footer.php';
}
