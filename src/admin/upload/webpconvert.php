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

$newimg = NukeViet\Files\Image::createFilename($img, 'webp');
if (nv_is_file(NV_BASE_SITEURL . $path . '/' . $newimg, $path)) {
    nv_jsonOutput([
        'status' => 'info',
        'mess' => $nv_Lang->getModule('webpconvert_exists')
    ]);
}

$createImage = new NukeViet\Files\Image(NV_ROOTDIR . '/' . $path . '/' . $img, NV_MAX_WIDTH, NV_MAX_HEIGHT);
$createImage->webpConvert(NV_ROOTDIR . '/' . $path . '/' . $newimg);
$createImage->close();

if (isset($array_dirname[$path])) {
    $did = $array_dirname[$path];
    $info = nv_getFileInfo($path, $newimg);
    $info['userid'] = $admin_info['userid'];
    $stmt = $db->prepare('INSERT INTO ' . NV_UPLOAD_GLOBALTABLE . '_file (
        name, ext, type, filesize, src, srcwidth, srcheight, sizes, userid, mtime, did, title
    ) VALUES (
        :name, :ext, :type, :filesize, :src, :srcwidth, :srcheight, :sizes, :userid, :mtime, :did, :title
    )');
    $stmt->bindValue(':name', $info['name'], PDO::PARAM_STR);
    $stmt->bindValue(':ext', $info['ext'], PDO::PARAM_STR);
    $stmt->bindValue(':type', $info['type'], PDO::PARAM_STR);
    $stmt->bindValue(':filesize', $info['filesize'], PDO::PARAM_INT);
    $stmt->bindValue(':src', $info['src'], PDO::PARAM_STR);
    $stmt->bindValue(':srcwidth', $info['srcwidth'], PDO::PARAM_INT);
    $stmt->bindValue(':srcheight', $info['srcheight'], PDO::PARAM_INT);
    $stmt->bindValue(':sizes', $info['size'], PDO::PARAM_STR);
    $stmt->bindValue(':userid', $info['userid'], PDO::PARAM_INT);
    $stmt->bindValue(':mtime', $info['mtime'], PDO::PARAM_INT);
    $stmt->bindValue(':did', $did, PDO::PARAM_INT);
    $stmt->bindValue(':title', $newimg, PDO::PARAM_STR);
    $stmt->execute();
}

nv_insert_logs(NV_LANG_DATA, $module_name, $nv_Lang->getModule('webpconvert'), $path . '/' . $newimg, $admin_info['userid']);

nv_jsonOutput([
    'status' => 'success',
    'file' => $newimg
]);
