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
$newname = nv_string_to_filename(htmlspecialchars(trim($nv_Request->get_string('newname', 'post')), ENT_QUOTES));

$check_allow_upload_dir = nv_check_allow_upload_dir($path);

if (!isset($check_allow_upload_dir['rename_dir']) or $check_allow_upload_dir['rename_dir'] !== true) {
    exit('ERROR_' . $lang_module['notlevel']);
}

if (empty($path) or $path == NV_UPLOADS_DIR) {
    exit('ERROR_' . $lang_module['notlevel']);
}

if (empty($newname)) {
    exit('ERROR_' . $lang_module['rename_nonamefolder']);
}

unset($matches);
preg_match('/(.*)\/([a-z0-9\-\_]+)$/i', $path, $matches);
if (!isset($matches) or empty($matches)) {
    exit('ERROR_' . $lang_module['notlevel']);
}

$newpath = $matches[1] . '/' . $newname;
if (is_dir(NV_ROOTDIR . '/' . $newpath)) {
    exit('ERROR_' . $lang_module['folder_exists']);
}

if (rename(NV_ROOTDIR . '/' . $path, NV_ROOTDIR . '/' . $newpath)) {
    $action = 0;
    if (preg_match('/^' . nv_preg_quote(NV_UPLOADS_DIR) . '\/([a-z0-9\-\_\/]+)$/i', $path, $m1) and preg_match('/^' . nv_preg_quote(NV_UPLOADS_DIR) . '\/([a-z0-9\-\_\/]+)$/i', $newpath, $m2)) {
        rename(NV_ROOTDIR . '/' . NV_MOBILE_FILES_DIR . '/' . $m1[1], NV_ROOTDIR . '/' . NV_MOBILE_FILES_DIR . '/' . $m2[1]);
        rename(NV_ROOTDIR . '/' . NV_FILES_DIR . '/' . $m1[1], NV_ROOTDIR . '/' . NV_FILES_DIR . '/' . $m2[1]);
        $action = 1;
        $dir_replace1 = NV_FILES_DIR . '/' . $m1[1] . '/';
        $dir_replace2 = NV_FILES_DIR . '/' . $m2[1] . '/';
    }

    $stmt = $db->prepare('SELECT did, dirname FROM ' . NV_UPLOAD_GLOBALTABLE . '_dir WHERE dirname = :path OR dirname LIKE :path_like');
    $stmt->bindParam(':path', $path, PDO::PARAM_STR);
    $path_like = $db->dblikeescape($path, true) . '/%';
    $stmt->bindParam(':path_like', $path_like, PDO::PARAM_STR);
    $stmt->execute();
    while ($_scratch = $stmt->fetch(3)) {
        list($did, $dirname) = $_scratch;
        unset($_scratch);
        $dirname2 = str_replace(NV_ROOTDIR . '/' . $path, $newpath, NV_ROOTDIR . '/' . $dirname);
        $result_file = $db->query('SELECT src, title FROM ' . NV_UPLOAD_GLOBALTABLE . '_file WHERE did=' . $did . " AND type = 'image'");
        while ($_scratch = $result_file->fetch(3)) {
            list($src, $title) = $_scratch;
            unset($_scratch);
            if ($action) {
                $src2 = preg_replace('/^' . nv_preg_quote($dir_replace1) . '/', $dir_replace2, $src);
            } else {
                $src2 = preg_replace('/^' . nv_preg_quote($dirname) . '/', $dirname2, $src);
            }
            $stmt_update = $db->prepare('UPDATE ' . NV_UPLOAD_GLOBALTABLE . '_file SET src = :src WHERE did = :did AND title = :title');
            $stmt_update->bindParam(':src', $src2, PDO::PARAM_STR);
            $stmt_update->bindParam(':did', $did, PDO::PARAM_INT);
            $stmt_update->bindParam(':title', $title, PDO::PARAM_STR);
            $stmt_update->execute();
        }
        $stmt_update_dir = $db->prepare('UPDATE ' . NV_UPLOAD_GLOBALTABLE . '_dir SET dirname = :dirname WHERE did = :did');
        $stmt_update_dir->bindParam(':dirname', $dirname2, PDO::PARAM_STR);
        $stmt_update_dir->bindParam(':did', $did, PDO::PARAM_INT);
        $stmt_update_dir->execute();
    }
    nv_dirListRefreshSize();
    nv_insert_logs(NV_LANG_DATA, $module_name, $lang_module['renamefolder'], $path . ' -> ' . $newpath, $admin_info['userid']);
    echo $newpath;
} else {
    exit('ERROR_' . $lang_module['rename_error_folder']);
}
