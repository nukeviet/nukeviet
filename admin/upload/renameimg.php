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

if (!isset($check_allow_upload_dir['rename_file'])) {
    exit('ERROR_' . $lang_module['notlevel']);
}

$file = htmlspecialchars(trim($nv_Request->get_string('file', 'post')), ENT_QUOTES);
$file = basename($file);

if (empty($file) or !nv_is_file(NV_BASE_SITEURL . $path . '/' . $file, $path)) {
    exit('ERROR_' . $lang_module['errorNotSelectFile']);
}

$newname = htmlspecialchars(trim($nv_Request->get_string('newname', 'post')), ENT_QUOTES);
$newname = nv_string_to_filename(basename($newname));

if (empty($newname)) {
    exit('ERROR_' . $lang_module['rename_noname']);
}

$newalt = $nv_Request->get_title('newalt', 'post', $newname, 1);

$ext = nv_getextension($file);
$newname = $newname . '.' . $ext;
if ($file != $newname) {
    $newname2 = $newname;

    $i = 1;
    while (file_exists(NV_ROOTDIR . '/' . $path . '/' . $newname2)) {
        $newname2 = preg_replace('/(.*)(\.[a-zA-Z0-9]+)$/', '\1_' . $i . '\2', $newname);
        ++$i;
    }

    $newname = $newname2;
    if (!@rename(NV_ROOTDIR . '/' . $path . '/' . $file, NV_ROOTDIR . '/' . $path . '/' . $newname)) {
        exit('ERROR_' . $lang_module['errorNotRenameFile']);
    }

    if (preg_match('/^' . nv_preg_quote(NV_UPLOADS_DIR) . '\/(([a-z0-9\-\_\/]+\/)*([a-z0-9\-\_\.]+)(\.(gif|jpg|jpeg|png|bmp|webp)))$/i', $path . '/' . $file, $m)) {
        @nv_deletefile(NV_ROOTDIR . '/' . NV_FILES_DIR . '/' . $m[1]);
        @nv_deletefile(NV_ROOTDIR . '/' . NV_MOBILE_FILES_DIR . '/' . $m[1]);
    }
    if (isset($array_dirname[$path])) {
        $info = nv_getFileInfo($path, $newname);

        $stmt = $db->prepare('UPDATE ' . NV_UPLOAD_GLOBALTABLE . '_file SET name = :name, src = :src, title = :newtitle, alt = :newalt WHERE did = :did AND title = :title');
        $stmt->bindValue(':name', $info['name'], PDO::PARAM_STR);
        $stmt->bindValue(':src', $info['src'], PDO::PARAM_STR);
        $stmt->bindValue(':newtitle', $newname, PDO::PARAM_STR);
        $stmt->bindValue(':newalt', $newalt, PDO::PARAM_STR);
        $stmt->bindValue(':did', $array_dirname[$path], PDO::PARAM_INT);
        $stmt->bindValue(':title', $file, PDO::PARAM_STR);
        $stmt->execute();
    }
    nv_insert_logs(NV_LANG_DATA, $module_name, $lang_module['rename'], $path . '/' . $file . ' -> ' . $path . '/' . $newname, $admin_info['userid']);
} else {
    $stmt_alt = $db->prepare('UPDATE ' . NV_UPLOAD_GLOBALTABLE . '_file SET alt = :newalt WHERE did = :did AND title = :title');
    $stmt_alt->bindValue(':newalt', $newalt, PDO::PARAM_STR);
    $stmt_alt->bindValue(':did', $array_dirname[$path], PDO::PARAM_INT);
    $stmt_alt->bindValue(':title', $file, PDO::PARAM_STR);
    $stmt_alt->execute();

    nv_insert_logs(NV_LANG_DATA, $module_name, $lang_module['rename'], $path . '/' . $file . ' -> ' . $path . '/' . $newname, $admin_info['userid']);
}
echo $newname;
exit();
