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
if (! isset($check_allow_upload_dir['delete_file'])) {
    die('ERROR#' . $lang_module['notlevel']);
}

$files = array_map('basename', explode('|', htmlspecialchars(trim($nv_Request->get_string('file', 'post')), ENT_QUOTES)));

// Check choose file
if (empty($files)) {
    die('ERROR#' . $lang_module['errorNotSelectFile']);
}

// Check file exists
foreach ($files as  $file) {
    if (! nv_is_file(NV_BASE_SITEURL . $path . '/' . $file, $path)) {
        die('ERROR#' . $lang_module['file_no_exists'] . ': ' . $file);
    }
}

// Do action: Delete
foreach ($files as  $file) {
    @nv_deletefile(NV_ROOTDIR . '/' . $path . '/' . $file);

    if (preg_match('/^' . nv_preg_quote(NV_UPLOADS_DIR) . '\/(([a-z0-9\-\_\/]+\/)*([a-z0-9\-\_\.]+)(\.(gif|jpg|jpeg|png|bmp)))$/i', $path . '/' . $file, $m)) {
        @nv_deletefile(NV_ROOTDIR . '/' . NV_FILES_DIR . '/' . $m[1]);
    }

    if (isset($array_dirname[$path])) {
        $db->query("DELETE FROM " . NV_UPLOAD_GLOBALTABLE . "_file WHERE did = " . $array_dirname[$path] . " AND title='" . $file . "'");
    }

    nv_insert_logs(NV_LANG_DATA, $module_name, $lang_module['upload_delfile'], $path . '/' . $file, $admin_info['userid']);
}

echo 'OK#Success!!!';
