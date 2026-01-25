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

if ($nv_Request->get_title('checkss', 'post', '') !== NV_CHECK_SESSION) {
    nv_jsonOutput([
        'status' => 'error',
        'mess' => 'Error session!!!'
    ]);
}

$path = nv_check_path_upload($nv_Request->get_string('path', 'post'));
$newname = nv_string_to_filename(htmlspecialchars(trim($nv_Request->get_string('newname', 'post')), ENT_QUOTES));
$check_allow_upload_dir = nv_check_allow_upload_dir($path);

if (!isset($check_allow_upload_dir['rename_dir']) or $check_allow_upload_dir['rename_dir'] !== true) {
    nv_jsonOutput([
        'status' => 'error',
        'mess' => $nv_Lang->getModule('notlevel')
    ]);
}

if (empty($path) or $path == NV_UPLOADS_DIR) {
    nv_jsonOutput([
        'status' => 'error',
        'mess' => $nv_Lang->getModule('notlevel')
    ]);
}

if (empty($newname)) {
    nv_jsonOutput([
        'status' => 'error',
        'input' => 'newname',
        'mess' => $nv_Lang->getModule('rename_nonamefolder')
    ]);
}

unset($matches);
preg_match('/(.*)\/([a-z0-9\-\_]+)$/i', $path, $matches);
if (!isset($matches) or empty($matches)) {
    nv_jsonOutput([
        'status' => 'error',
        'mess' => $nv_Lang->getModule('notlevel')
    ]);
}

if ($newname == basename($path)) {
    nv_jsonOutput([
        'status' => 'error',
        'input' => 'newname',
        'mess' => $nv_Lang->getModule('renamefolder_nochange')
    ]);
}

$newpath = $matches[1] . '/' . $newname;
if (file_exists(NV_ROOTDIR . '/' . $newpath)) {
    nv_jsonOutput([
        'status' => 'error',
        'mess' => $nv_Lang->getModule('folder_exists')
    ]);
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

    $result = $db->query('SELECT did, dirname FROM ' . NV_UPLOAD_GLOBALTABLE . "_dir WHERE dirname='" . $path . "' OR dirname LIKE '" . $path . "/%'");
    while ([$did, $dirname] = $result->fetch(3)) {
        $dirname2 = str_replace(NV_ROOTDIR . '/' . $path, $newpath, NV_ROOTDIR . '/' . $dirname);
        $result_file = $db->query('SELECT src, title FROM ' . NV_UPLOAD_GLOBALTABLE . '_file WHERE did=' . $did . " AND type = 'image'");
        while ([$src, $title] = $result_file->fetch(3)) {
            if ($action) {
                $src2 = preg_replace('/^' . nv_preg_quote($dir_replace1) . '/', $dir_replace2, $src);
            } else {
                $src2 = preg_replace('/^' . nv_preg_quote($dirname) . '/', $dirname2, $src);
            }
            $db->query('UPDATE ' . NV_UPLOAD_GLOBALTABLE . "_file SET src = '" . $src2 . "' WHERE did = " . $did . " AND title='" . $title . "'");
        }
        $db->query('UPDATE ' . NV_UPLOAD_GLOBALTABLE . "_dir SET dirname = '" . $dirname2 . "' WHERE did = " . $did);
    }
    nv_dirListRefreshSize();
    nv_insert_logs(NV_LANG_DATA, $module_name, $nv_Lang->getModule('renamefolder'), $path . ' -> ' . $newpath, $admin_info['userid']);
    nv_jsonOutput([
        'status' => 'success',
        'path' => $newpath
    ]);
}

nv_jsonOutput([
    'status' => 'error',
    'mess' => $nv_Lang->getModule('rename_error_folder')
]);
