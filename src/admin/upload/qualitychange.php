<?php

/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_IS_FILE_ADMIN')) {
    exit('Stop!!!');
}

if (!csrf_check($nv_Request->get_string('checkss', 'post'), $_csrf_key)) {
    nv_jsonOutput([
        'status' => 'error',
        'mess' => $nv_Lang->getGlobal('error_checkss')
    ]);
}

$path = nv_check_path_upload($nv_Request->get_string('path', 'post'));
$check_allow_upload_dir = nv_check_allow_upload_dir($path);

if (!isset($check_allow_upload_dir['move_file'])) {
    nv_jsonOutput([
        'status' => 'error',
        'mess' => $nv_Lang->getModule('notlevel')
    ]);
}

$img = htmlspecialchars(trim($nv_Request->get_string('img', 'post')), ENT_QUOTES);
$img = basename($img);

if (empty($img)) {
    nv_jsonOutput([
        'status' => 'error',
        'mess' => $nv_Lang->getModule('errorNotSelectFile')
    ]);
}
if (!nv_is_file(NV_BASE_SITEURL . $path . '/' . $img, $path)) {
    nv_jsonOutput([
        'status' => 'error',
        'mess' => $nv_Lang->getModule('file_no_exists')
    ]);
}

$quality = $nv_Request->get_int('quality', 'post', 0);
!in_array($quality, [100, 95, 90, 85, 80, 75, 70, 65, 60, 55, 50, 45, 40, 35, 30, 25, 20, 15, 10], true) && $quality = 100;

$fimage = new NukeViet\Files\Image(NV_ROOTDIR . '/' . $path . '/' . $img, NV_MAX_WIDTH, NV_MAX_HEIGHT);

if ($nv_Request->isset_request('preview', 'post')) {
    [$data, $length] = $fimage->base64data($quality);
    nv_jsonOutput([
        'status' => 'success',
        'imgdata' => $data,
        'imglength' => nv_convertfromBytes($length)
    ]);
}

$fimage->save(NV_ROOTDIR . '/' . $path, $img, $quality);
$fimage->close();
if (isset($array_dirname[$path])) {
    $info = nv_getFileInfo($path, $img);
    $did = $array_dirname[$path];
    $sth = $db->prepare('UPDATE ' . NV_UPLOAD_GLOBALTABLE . '_file SET filesize = :filesize, src = :src, srcwidth = :srcwidth, srcheight = :srcheight, sizes = :sizes, userid = :userid, mtime = :mtime WHERE did = :did AND title = :title');
    $sth->bindValue(':filesize', $info['filesize'], PDO::PARAM_INT);
    $sth->bindValue(':src', $info['src'], PDO::PARAM_STR);
    $sth->bindValue(':srcwidth', $info['srcwidth'], PDO::PARAM_INT);
    $sth->bindValue(':srcheight', $info['srcheight'], PDO::PARAM_INT);
    $sth->bindValue(':sizes', $info['size'], PDO::PARAM_STR);
    $sth->bindValue(':userid', $admin_info['userid'], PDO::PARAM_INT);
    $sth->bindValue(':mtime', $info['mtime'], PDO::PARAM_INT);
    $sth->bindValue(':did', $did, PDO::PARAM_INT);
    $sth->bindValue(':title', $img, PDO::PARAM_STR);
    $sth->execute();
    nv_dirListRefreshSize();
}

nv_jsonOutput([
    'status' => 'OK',
    'name' => $img
]);
