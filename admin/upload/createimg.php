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

$path = nv_check_path_upload($nv_Request->get_string('path', 'post'));
$check_allow_upload_dir = nv_check_allow_upload_dir($path);

if (!isset($check_allow_upload_dir['create_file'])) {
    exit('ERROR_' . $lang_module['notlevel']);
}

$width = $nv_Request->get_int('width', 'post');
$height = $nv_Request->get_int('height', 'post');
$imagename = htmlspecialchars(trim($nv_Request->get_string('img', 'post')), ENT_QUOTES);
$imagename = basename($imagename);

if (preg_match('/^(.*)(\.[a-zA-Z]+)$/', $imagename, $matches)) {
    $file_old = $matches[1];
    $file_ext = $matches[2];
} else {
    $file_old = $imagename;
    $file_ext = '';
}

$i = 1;
$file = $file_old . '_' . $width . '_' . $height . $file_ext;
while (file_exists(NV_ROOTDIR . '/' . $path . '/' . $file)) {
    $file = $file_old . '_' . $width . '_' . $height . '_' . $i . $file_ext;
    ++$i;
}

if (isset($array_thumb_config[$path])) {
    $thumb_config = $array_thumb_config[$path];
} else {
    $thumb_config = $array_thumb_config[''];
    $_arr_path = explode('/', $path);
    while (sizeof($_arr_path) > 1) {
        array_pop($_arr_path);
        $_path = implode('/', $_arr_path);
        if (isset($array_thumb_config[$_path])) {
            $thumb_config = $array_thumb_config[$_path];
            break;
        }
    }
}

$createImage = new NukeViet\Files\Image(NV_ROOTDIR . '/' . $path . '/' . $imagename, NV_MAX_WIDTH, NV_MAX_HEIGHT);
$createImage->resizeXY($width, $height);
$createImage->save(NV_ROOTDIR . '/' . $path, $file, $thumb_config['thumb_quality']);
$createImage->close();

if (isset($array_dirname[$path])) {
    $did = $array_dirname[$path];
    $info = nv_getFileInfo($path, $file);
    $info['userid'] = $admin_info['userid'];

    $sth = $db->prepare('INSERT INTO ' . NV_UPLOAD_GLOBALTABLE . '_file (
        name, ext, type, filesize, src, srcwidth, srcheight, sizes, userid, mtime, did, title
    ) VALUES (
        :name, :ext, :type, :filesize, :src, :srcwidth, :srcheight, :sizes, :userid, :mtime, :did, :title
    )');
    $sth->bindValue(':name', $info['name'], PDO::PARAM_STR);
    $sth->bindValue(':ext', $info['ext'], PDO::PARAM_STR);
    $sth->bindValue(':type', $info['type'], PDO::PARAM_STR);
    $sth->bindValue(':filesize', $info['filesize'], PDO::PARAM_INT);
    $sth->bindValue(':src', $info['src'], PDO::PARAM_STR);
    $sth->bindValue(':srcwidth', $info['srcwidth'], PDO::PARAM_INT);
    $sth->bindValue(':srcheight', $info['srcheight'], PDO::PARAM_INT);
    $sth->bindValue(':sizes', $info['size'], PDO::PARAM_STR);
    $sth->bindValue(':userid', $info['userid'], PDO::PARAM_INT);
    $sth->bindValue(':mtime', $info['mtime'], PDO::PARAM_INT);
    $sth->bindValue(':did', $did, PDO::PARAM_INT);
    $sth->bindValue(':title', $file, PDO::PARAM_STR);
    $sth->execute();

    nv_dirListRefreshSize();
}

nv_insert_logs(NV_LANG_DATA, $module_name, $lang_module['upload_createimage'], $path . '/' . $file, $admin_info['userid']);

echo $file;
exit();
