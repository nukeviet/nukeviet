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

$path_filename = NV_BASE_SITEURL . $log_dir . '/' . $filename;

if (nv_is_file($path_filename, $log_dir) === true and $checkss == md5($filename . NV_CHECK_SESSION)) {
    $temp = explode('_', $filename);

    nv_insert_logs(NV_LANG_DATA, $module_name, $lang_global['delete'] . ' ' . $lang_module['file_backup'], 'File name: ' . end($temp), $admin_info['userid']);

    nv_deletefile(NV_DOCUMENT_ROOT . $path_filename);

    nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=file&rand=' . nv_genpass());
} else {
    $contents = 'File not exist !';

    include NV_ROOTDIR . '/includes/header.php';
    echo nv_admin_theme($contents);
    include NV_ROOTDIR . '/includes/footer.php';
}
