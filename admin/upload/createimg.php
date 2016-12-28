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

$path = nv_check_path_upload($nv_Request->get_string('path', 'post'));
$check_allow_upload_dir = nv_check_allow_upload_dir($path);

if (! isset($check_allow_upload_dir['create_file'])) {
    die('ERROR_' . $lang_module['notlevel']);
}

$width = $nv_Request->get_int('width', 'post');
$height = $nv_Request->get_int('height', 'post');
$imagename = htmlspecialchars(trim($nv_Request->get_string('img', 'post')), ENT_QUOTES);
$imagename = basename($imagename);

$file = preg_replace('/^(.*)(\.[a-zA-Z]+)$/', '\1_' . $width . '_' . $height . '\2', $imagename);

$i = 1;
while (file_exists(NV_ROOTDIR . '/' . $path . '/' . $file)) {
    $file = preg_replace('/^(.*)(\.[a-zA-Z]+)$/', '\1_' . $width . '_' . $height . '_' . $i . '\2', $imagename);
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
    $db->query("INSERT INTO " . NV_UPLOAD_GLOBALTABLE . "_file
							(name, ext, type, filesize, src, srcwidth, srcheight, sizes, userid, mtime, did, title) VALUES
							('" . $info['name'] . "', '" . $info['ext'] . "', '" . $info['type'] . "', " . $info['filesize'] . ", '" . $info['src'] . "', " . $info['srcwidth'] . ", " . $info['srcheight'] . ", '" . $info['size'] . "', " . $info['userid'] . ", " . $info['mtime'] . ", " . $did . ", '" . $file . "')");
}

nv_insert_logs(NV_LANG_DATA, $module_name, $lang_module['upload_createimage'], $path . '/' . $file, $admin_info['userid']);

echo $file;
exit();
