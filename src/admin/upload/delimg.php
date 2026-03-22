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

// Tệp được xóa có thể nhiều thư mục khác nhau khi tìm kiếm
$files = array_filter(explode('|', htmlspecialchars(trim($nv_Request->get_string('files', 'post', '')), ENT_QUOTES)));
if (empty($files)) {
    nv_jsonOutput([
        'status' => 'error',
        'mess' => $nv_Lang->getModule('errorNotSelectFile')
    ]);
}

$deleted = 0;
$error = '';
foreach ($files as $file) {
    $path = nv_check_path_upload(dirname($file));
    $file = basename($file);
    $check_allow = nv_check_allow_upload_dir($path);

    // Kiểm tra quyền xóa file
    if (empty($check_allow['delete_file'])) {
        $error = $nv_Lang->getModule('notlevel');
        continue;
    }
    if (!nv_is_file(NV_BASE_SITEURL . $path . '/' . $file, $path)) {
        $error = $nv_Lang->getModule('file_no_exists') . ': ' . $file;
        continue;
    }
    nv_insert_logs(NV_LANG_DATA, $module_name, $nv_Lang->getModule('upload_delfile'), $path . '/' . $file, $admin_info['userid']);
    nv_deletefile(NV_ROOTDIR . '/' . $path . '/' . $file);
    if (preg_match('/^' . nv_preg_quote(NV_UPLOADS_DIR) . '\/(([a-z0-9\-\_\/]+\/)*([a-z0-9\-\_\.]+)(\.(gif|jpg|jpeg|png|bmp|webp)))$/i', $path . '/' . $file, $m)) {
        nv_deletefile(NV_ROOTDIR . '/' . NV_FILES_DIR . '/' . $m[1]);
        nv_deletefile(NV_ROOTDIR . '/' . NV_MOBILE_FILES_DIR . '/' . $m[1]);
    }
    if (isset($array_dirname[$path])) {
        $sth = $db->prepare('DELETE FROM ' . NV_UPLOAD_GLOBALTABLE . '_file WHERE did = :did AND title = :title');
        $sth->bindValue(':did', $array_dirname[$path], PDO::PARAM_INT);
        $sth->bindValue(':title', $file, PDO::PARAM_STR);
        $sth->execute();
        nv_dirListRefreshSize();
    }
    $deleted++;
}

if (!empty($error) and empty($deleted)) {
    nv_jsonOutput([
        'status' => 'error',
        'mess' => $error
    ]);
}

nv_jsonOutput([
    'status' => 'success'
]);
