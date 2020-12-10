<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 2-2-2010 12:55
 */

if (! defined('NV_IS_FILE_ADMIN')) {
    die('Stop!!!');
}

$path = nv_check_path_upload($nv_Request->get_string('path', 'post,get'));

$image = htmlspecialchars(trim($nv_Request->get_string('img', 'post,get')), ENT_QUOTES);
$image = basename($image);

$path_filename = NV_BASE_SITEURL . $path . '/' . $image;

if (! empty($image) and nv_is_file($path_filename, $path) and nv_check_allow_upload_dir($path)) {
    //Download file
    $download = new NukeViet\Files\Download(NV_DOCUMENT_ROOT . $path_filename, NV_ROOTDIR . '/' . $path, $image);
    $download->download_file();
    exit();
} else {
    echo 'file not exist !';
}
