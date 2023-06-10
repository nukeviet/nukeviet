<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2022 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_IS_FILE_ADMIN')) {
    exit('Stop!!!');
}

$path = nv_check_path_upload($nv_Request->get_string('path', 'post'));
$check_allow_upload_dir = nv_check_allow_upload_dir($path);

if (!isset($check_allow_upload_dir['move_file'])) {
    nv_jsonOutput([
        'status' => 'error',
        'mess' => 'ERROR#' . $nv_Lang->getModule('notlevel')
    ]);
}

$img = htmlspecialchars(trim($nv_Request->get_string('img', 'post')), ENT_QUOTES);
$img = basename($img);

if (empty($img) or !nv_is_file(NV_BASE_SITEURL . $path . '/' . $img, $path)) {
    nv_jsonOutput([
        'status' => 'error',
        'mess' => 'ERROR#' . $nv_Lang->getModule('errorNotSelectFile') . NV_ROOTDIR . '/' . $path . '/' . $img
    ]);
}

$quality = $nv_Request->get_int('quality', 'post', 0);
!in_array($quality, [100, 95, 90, 85, 80, 75, 70, 65, 60, 55, 50, 45, 40, 35, 30, 25, 20, 15, 10], true) && $quality = 100;

$fimage = new NukeViet\Files\Image(NV_ROOTDIR . '/' . $path . '/' . $img, NV_MAX_WIDTH, NV_MAX_HEIGHT);

if ($nv_Request->isset_request('preview', 'post')) {
    list($data, $length) = $fimage->base64data($quality);
    nv_jsonOutput([
        'status' => 'OK',
        'imgdata' => $data,
        'imglength' => nv_convertfromBytes($length)
    ]);
}

$fimage->save(NV_ROOTDIR . '/' . $path, $img, $quality);
$fimage->close();
if (isset($array_dirname[$path])) {
    $info = nv_getFileInfo($path, $img);
    $did = $array_dirname[$path];
    $db->query('UPDATE ' . NV_UPLOAD_GLOBALTABLE . '_file SET filesize=' . $info['filesize'] . ", src='" . $info['src'] . "', srcwidth=" . $info['srcwidth'] . ', srcheight=' . $info['srcheight'] . ", sizes='" . $info['size'] . "', userid=" . $admin_info['userid'] . ', mtime=' . $info['mtime'] . ' WHERE did = ' . $did . " AND title = '" . $img . "'");
    nv_dirListRefreshSize();
}

nv_jsonOutput([
    'status' => 'OK',
    'file' => $img
]);
