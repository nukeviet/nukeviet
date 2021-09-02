<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2021 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_IS_FILE_ADMIN')) {
    exit('Stop!!!');
}

$path = nv_check_path_upload($nv_Request->get_string('path', 'post,get'));

$image = htmlspecialchars(trim($nv_Request->get_string('img', 'post,get')), ENT_QUOTES);
$image = basename($image);

$path_filename = NV_BASE_SITEURL . $path . '/' . $image;

if (!empty($image) and nv_is_file($path_filename, $path) and nv_check_allow_upload_dir($path)) {
    //Download file
    $download = new NukeViet\Files\Download(NV_DOCUMENT_ROOT . $path_filename, NV_ROOTDIR . '/' . $path, $image);
    $download->download_file();
    exit();
}
    echo 'file not exist !';
