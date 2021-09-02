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
}
    $contents = 'File not exist !';

    include NV_ROOTDIR . '/includes/header.php';
    echo nv_admin_theme($contents);
    include NV_ROOTDIR . '/includes/footer.php';
