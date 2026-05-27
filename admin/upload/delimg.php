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
if (!isset($check_allow_upload_dir['delete_file'])) {
    exit('ERROR#' . $lang_module['notlevel']);
}

$files = array_map('basename', explode('|', htmlspecialchars(trim($nv_Request->get_string('file', 'post')), ENT_QUOTES)));

// Check choose file
if (empty($files)) {
    exit('ERROR#' . $lang_module['errorNotSelectFile']);
}

// Check file exists
foreach ($files as $file) {
    if (!nv_is_file(NV_BASE_SITEURL . $path . '/' . $file, $path)) {
        exit('ERROR#' . $lang_module['file_no_exists'] . ': ' . $file);
    }
}

// Do action: Delete
foreach ($files as $file) {
    @nv_deletefile(NV_ROOTDIR . '/' . $path . '/' . $file);

    if (preg_match('/^' . nv_preg_quote(NV_UPLOADS_DIR) . '\/(([a-z0-9\-\_\/]+\/)*([a-z0-9\-\_\.]+)(\.(gif|jpg|jpeg|png|bmp|webp)))$/i', $path . '/' . $file, $m)) {
        @nv_deletefile(NV_ROOTDIR . '/' . NV_FILES_DIR . '/' . $m[1]);
        @nv_deletefile(NV_ROOTDIR . '/' . NV_MOBILE_FILES_DIR . '/' . $m[1]);
    }

    if (isset($array_dirname[$path])) {
        $sth = $db->prepare('DELETE FROM ' . NV_UPLOAD_GLOBALTABLE . '_file WHERE did = :did AND title = :title');
        $sth->bindValue(':did', $array_dirname[$path], PDO::PARAM_INT);
        $sth->bindValue(':title', $file, PDO::PARAM_STR);
        $sth->execute();

        nv_dirListRefreshSize();
    }

    nv_insert_logs(NV_LANG_DATA, $module_name, $lang_module['upload_delfile'], $path . '/' . $file, $admin_info['userid']);
}

echo 'OK#Success!!!';
