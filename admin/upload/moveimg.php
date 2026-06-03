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

$checkss = $nv_Request->get_string('checkss', 'post');
if (empty($checkss) or $checkss != NV_CHECK_SESSION) {
    exit('Stop!!!');
}

$path = nv_check_path_upload($nv_Request->get_string('path', 'post'));
$check_allow_upload_dir = nv_check_allow_upload_dir($path);

if (!isset($check_allow_upload_dir['move_file'])) {
    exit('ERROR#' . $lang_module['notlevel']);
}

$newfolder = nv_check_path_upload($nv_Request->get_string('newpath', 'post'));
$check_allow_upload_dir = nv_check_allow_upload_dir($newfolder);
if (!isset($check_allow_upload_dir['create_file'])) {
    exit('ERROR#' . $lang_module['notlevel']);
}

$images = array_map('basename', explode('|', htmlspecialchars(trim($nv_Request->get_string('file', 'post')), ENT_QUOTES)));

// Check choose file
if (empty($images)) {
    exit('ERROR#' . $lang_module['errorNotSelectFile']);
}

// Check file exists
foreach ($images as $file) {
    if (!nv_is_file(NV_BASE_SITEURL . $path . '/' . $file, $path)) {
        exit('ERROR#' . $lang_module['file_no_exists'] . ': ' . $file);
    }
}

$mirror = $nv_Request->get_int('mirror', 'post', 0);

$moved_images = [];

foreach ($images as $image) {
    $i = 1;
    $file = $image;

    // Change file name if exists
    while (file_exists(NV_ROOTDIR . '/' . $newfolder . '/' . $file)) {
        $file = preg_replace('/(.*)(\.[a-zA-Z0-9]+)$/', '\1_' . $i . '\2', $image);
        ++$i;
    }

    $moved_images[] = $file;

    if (!nv_copyfile(NV_ROOTDIR . '/' . $path . '/' . $image, NV_ROOTDIR . '/' . $newfolder . '/' . $file)) {
        exit('ERROR#' . $lang_module['errorNotCopyFile']);
    }

    if (isset($array_dirname[$newfolder])) {
        $did = $array_dirname[$newfolder];
        $info = nv_getFileInfo($newfolder, $file);
        $info['userid'] = $admin_info['userid'];

        $sth = $db->prepare('INSERT INTO ' . NV_UPLOAD_GLOBALTABLE . '_file (name, ext, type, filesize, src, srcwidth, srcheight, sizes, userid, mtime, did, title) VALUES (:name, :ext, :type, :filesize, :src, :srcwidth, :srcheight, :sizes, :userid, :mtime, :did, :title)');
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
    }

    if (!$mirror) {
        @nv_deletefile(NV_ROOTDIR . '/' . $path . '/' . $image);

        // Delete old thumb
        if (preg_match('/^' . nv_preg_quote(NV_UPLOADS_DIR) . '\/(([a-z0-9\-\_\/]+\/)*([a-z0-9\-\_\.]+)(\.(gif|jpg|jpeg|png|bmp|webp)))$/i', $path . '/' . $image, $m)) {
            @nv_deletefile(NV_ROOTDIR . '/' . NV_FILES_DIR . '/' . $m[1]);
            @nv_deletefile(NV_ROOTDIR . '/' . NV_MOBILE_FILES_DIR . '/' . $m[1]);
        }

        if (isset($array_dirname[$path])) {
            $did = $array_dirname[$path];
            $sth = $db->prepare('DELETE FROM ' . NV_UPLOAD_GLOBALTABLE . '_file WHERE did = :did AND title = :title');
            $sth->bindValue(':did', $did, PDO::PARAM_INT);
            $sth->bindValue(':title', $image, PDO::PARAM_STR);
            $sth->execute();
        }
    }
    nv_dirListRefreshSize();

    nv_insert_logs(NV_LANG_DATA, $module_name, $lang_module['move'], $path . '/' . $image . ' -> ' . $newfolder . '/' . $file, $admin_info['userid']);
}

echo 'OK#' . implode('|', $moved_images);
exit();
