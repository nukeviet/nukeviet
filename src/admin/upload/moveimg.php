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

// Tệp được di chuyển có thể nằm nhiều thư mục khác nhau vì tính năng tìm kiếm
$images = array_filter(explode('|', htmlspecialchars(trim($nv_Request->get_string('files', 'post', '')), ENT_QUOTES)));
if (empty($images)) {
    nv_jsonOutput([
        'status' => 'error',
        'mess' => $nv_Lang->getModule('errorNotSelectFile')
    ]);
}

// Thư mục chuyển tới
$newfolder = nv_check_path_upload($nv_Request->get_string('newpath', 'post'));
$check_allow_upload_dir = nv_check_allow_upload_dir($newfolder);
if (!isset($check_allow_upload_dir['create_file'])) {
    nv_jsonOutput([
        'status' => 'error',
        'input' => 'newpath',
        'mess' => $nv_Lang->getModule('notlevel')
    ]);
}

$mirror = $nv_Request->get_int('mirror', 'post', 0);

$moved_images = [];
$moved_num = 0;
$error = '';

foreach ($images as $image) {
    $path = nv_check_path_upload(dirname($image));
    $image = basename($image);
    $check_allow = nv_check_allow_upload_dir($path);

    // Quyền di chuyển
    if (empty($check_allow['move_file'])) {
        $error = $nv_Lang->getModule('notlevel');
        continue;
    }
    // File tồn tại
    if (!nv_is_file(NV_BASE_SITEURL . $path . '/' . $image, $path)) {
        $error = $nv_Lang->getModule('file_no_exists');
        continue;
    }
    // Thư mục giống nhau
    if ($path == $newfolder) {
        $error = $nv_Lang->getModule('move_same_folder');
        continue;
    }

    $i = 1;
    $file = $image;

    // Change file name if exists
    while (file_exists(NV_ROOTDIR . '/' . $newfolder . '/' . $file)) {
        $file = preg_replace('/(.*)(\.[a-zA-Z0-9]+)$/', '\1_' . $i . '\2', $image);
        ++$i;
    }

    $moved_images[] = $file;

    if (!nv_copyfile(NV_ROOTDIR . '/' . $path . '/' . $image, NV_ROOTDIR . '/' . $newfolder . '/' . $file)) {
        $error = $nv_Lang->getModule('errorNotCopyFile');
        continue;
    }

    if (isset($array_dirname[$newfolder])) {
        $did = $array_dirname[$newfolder];
        $info = nv_getFileInfo($newfolder, $file);
        $info['userid'] = $admin_info['userid'];

        $db->query('INSERT INTO ' . NV_UPLOAD_GLOBALTABLE . "_file (name, ext, type, filesize, src, srcwidth, srcheight, sizes, userid, mtime, did, title) VALUES ('" . $info['name'] . "', '" . $info['ext'] . "', '" . $info['type'] . "', " . $info['filesize'] . ", '" . $info['src'] . "', " . $info['srcwidth'] . ', ' . $info['srcheight'] . ", '" . $info['size'] . "', " . $info['userid'] . ', ' . $info['mtime'] . ', ' . $did . ", '" . $file . "')");
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
            $db->query('DELETE FROM ' . NV_UPLOAD_GLOBALTABLE . '_file WHERE did = ' . $did . " AND title='" . $image . "'");
        }
    }
    nv_dirListRefreshSize();
    $moved_num++;
    nv_insert_logs(NV_LANG_DATA, $module_name, $nv_Lang->getModule('move'), $path . '/' . $image . ' -> ' . $newfolder . '/' . $file, $admin_info['userid']);
}

if (!empty($error) and empty($moved_num)) {
    nv_jsonOutput([
        'status' => 'error',
        'mess' => $error
    ]);
}

nv_jsonOutput([
    'status' => 'success',
    'files' => $moved_images
]);
