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

if (!isset($check_allow_upload_dir['create_dir']) or $check_allow_upload_dir['create_dir'] !== true) {
    nv_jsonOutput([
        'status' => 'error',
        'mess' => $nv_Lang->getModule('notlevel')
    ]);
}
if (empty($path)) {
    nv_jsonOutput([
        'status' => 'error',
        'mess' => $nv_Lang->getModule('notlevel')
    ]);
}
if (empty($newname)) {
    nv_jsonOutput([
        'status' => 'error',
        'input' => 'newname',
        'mess' => $nv_Lang->getGlobal('required_invalid')
    ]);
}

$newpath = $path . '/' . $newname;
if (is_dir(NV_ROOTDIR . '/' . $newpath)) {
    nv_jsonOutput([
        'status' => 'error',
        'input' => 'newname',
        'mess' => $nv_Lang->getModule('folder_exists')
    ]);
}

$n_dir = nv_mkdir(NV_ROOTDIR . '/' . $path, $newname);

if (!empty($n_dir[0])) {
    $sth = $db->prepare('INSERT INTO ' . NV_UPLOAD_GLOBALTABLE . '_dir (dirname, time, thumb_type, thumb_width, thumb_height, thumb_quality) VALUES (:newpath, 0, 0, 0, 0, 0)');
    $sth->bindParam(':newpath', $newpath, PDO::PARAM_STR);
    $sth->execute();

    nv_insert_logs(NV_LANG_DATA, $module_name, $nv_Lang->getModule('createfolder'), $newpath, $admin_info['userid']);
    nv_jsonOutput([
        'status' => 'success',
        'path' => $newpath
    ]);
}

nv_jsonOutput([
    'status' => 'error',
    'input' => 'newname',
    'mess' => $n_dir[1]
]);
