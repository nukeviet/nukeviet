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
$newname = nv_string_to_filename(htmlspecialchars(trim($nv_Request->get_string('newname', 'post')), ENT_QUOTES));

$check_allow_upload_dir = nv_check_allow_upload_dir($path);

if (!isset($check_allow_upload_dir['create_dir']) or $check_allow_upload_dir['create_dir'] !== true) {
    exit('ERROR_' . $lang_module['notlevel']);
}
if (empty($path)) {
    exit('ERROR_' . $lang_module['notlevel']);
}
if (empty($newname)) {
    exit('ERROR_' . $lang_module['name_nonamefolder']);
}

$newpath = $path . '/' . $newname;
if (is_dir(NV_ROOTDIR . '/' . $newpath)) {
    exit('ERROR_' . $lang_module['folder_exists']);
}

$n_dir = nv_mkdir(NV_ROOTDIR . '/' . $path, $newname);

if (!empty($n_dir[0])) {
    $sth = $db->prepare('INSERT INTO ' . NV_UPLOAD_GLOBALTABLE . '_dir (dirname, time, thumb_type, thumb_width, thumb_height, thumb_quality) VALUES (:newpath, 0, 0, 0, 0, 0)');
    $sth->bindParam(':newpath', $newpath, PDO::PARAM_STR);
    $sth->execute();

    nv_insert_logs(NV_LANG_DATA, $module_name, $lang_module['createfolder'], $newpath, $admin_info['userid']);
    echo $newpath;
    exit();
}
    exit('ERROR_' . $n_dir[1]);
