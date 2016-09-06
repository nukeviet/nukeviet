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

if (! isset($check_allow_upload_dir['move_file'])) {
    die('ERROR#' . $lang_module['notlevel']);
}

$newfolder = nv_check_path_upload($nv_Request->get_string('newpath', 'post'));
$check_allow_upload_dir = nv_check_allow_upload_dir($newfolder);
if (! isset($check_allow_upload_dir['create_file'])) {
    die('ERROR#' . $lang_module['notlevel']);
}

$images = array_map('basename', explode('|', htmlspecialchars(trim($nv_Request->get_string('file', 'post')), ENT_QUOTES)));

// Check choose file
if (empty($images)) {
    die('ERROR#' . $lang_module['errorNotSelectFile']);
}

// Check file exists
foreach ($images as  $file) {
    if (! nv_is_file(NV_BASE_SITEURL . $path . '/' . $file, $path)) {
        die('ERROR#' . $lang_module['file_no_exists'] . ': ' . $file);
    }
}

$mirror = $nv_Request->get_int('mirror', 'post', 0);

$moved_images = array();

foreach ($images as $image) {
    $i = 1;
    $file = $image;

    // Change file name if exists
    while (file_exists(NV_ROOTDIR . '/' . $newfolder . '/' . $file)) {
        $file = preg_replace('/(.*)(\.[a-zA-Z0-9]+)$/', '\1_' . $i . '\2', $image);
        ++ $i;
    }

    $moved_images[] = $file;

    if (! nv_copyfile(NV_ROOTDIR . '/' . $path . '/' . $image, NV_ROOTDIR . '/' . $newfolder . '/' . $file)) {
        die('ERROR#' . $lang_module['errorNotCopyFile']);
    }

    if (isset($array_dirname[$newfolder])) {
        $did = $array_dirname[$newfolder];
        $info = nv_getFileInfo($newfolder, $file);
        $info['userid'] = $admin_info['userid'];

        $db->query("INSERT INTO " . NV_UPLOAD_GLOBALTABLE . "_file (name, ext, type, filesize, src, srcwidth, srcheight, sizes, userid, mtime, did, title) VALUES ('" . $info['name'] . "', '" . $info['ext'] . "', '" . $info['type'] . "', " . $info['filesize'] . ", '" . $info['src'] . "', " . $info['srcwidth'] . ", " . $info['srcheight'] . ", '" . $info['size'] . "', " . $info['userid'] . ", " . $info['mtime'] . ", " . $did . ", '" . $file . "')");
    }

    if (! $mirror) {
        @nv_deletefile(NV_ROOTDIR . '/' . $path . '/' . $image);

        // Delete old thumb
        if (preg_match('/^' . nv_preg_quote(NV_UPLOADS_DIR) . '\/(([a-z0-9\-\_\/]+\/)*([a-z0-9\-\_\.]+)(\.(gif|jpg|jpeg|png|bmp)))$/i', $path . '/' . $image, $m)) {
            @nv_deletefile(NV_ROOTDIR . '/' . NV_FILES_DIR . '/' . $m[1]);
        }

        if (isset($array_dirname[$path])) {
            $did = $array_dirname[$path];
            $db->query("DELETE FROM " . NV_UPLOAD_GLOBALTABLE . "_file WHERE did = " . $did . " AND title='" . $image . "'");
        }
    }

    nv_insert_logs(NV_LANG_DATA, $module_name, $lang_module['move'], $path . '/' . $image . ' -> ' . $newfolder . '/' . $file, $admin_info['userid']);
}

echo 'OK#' . implode('|', $moved_images);
exit();
