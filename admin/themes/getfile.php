<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2021 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_IS_FILE_THEMES')) {
    exit('Stop!!!');
}

$filename = $nv_Request->get_title('filename', 'get', '');
$checkss = $nv_Request->get_title('checkss', 'get', '');
$mod = $nv_Request->get_title('mod', 'get', '');

$path_filename = NV_BASE_SITEURL . NV_TEMP_DIR . '/' . $filename;

if (!empty($mod) and nv_is_file($path_filename, NV_TEMP_DIR) and $checkss == md5($filename . NV_CHECK_SESSION)) {
    //Download file
    $download = new NukeViet\Files\Download(NV_DOCUMENT_ROOT . $path_filename, NV_ROOTDIR . '/' . NV_TEMP_DIR, $mod);
    $download->download_file();
    exit();
}

$contents = 'file not exist !';

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
