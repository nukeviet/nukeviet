<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2022 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_IS_FILE_ADMIN')) {
    exit('Stop!!!');
}

if (!(class_exists('Tinify\Tinify') and !empty($global_config['tinify_active']) and !empty($global_config['tinify_api']))) {
    nv_jsonOutput([
        'status' => 'error',
        'mess' => 'ERROR#Not allowed'
    ]);
}

$path = nv_check_path_upload($nv_Request->get_string('path', 'post'));
$check_allow_upload_dir = nv_check_allow_upload_dir($path);

if (!isset($check_allow_upload_dir['move_file'])) {
    nv_jsonOutput([
        'status' => 'error',
        'mess' => 'ERROR#' . $lang_module['notlevel']
    ]);
}

$img = htmlspecialchars(trim($nv_Request->get_string('img', 'post')), ENT_QUOTES);
$img = basename($img);

if (empty($img) or !nv_is_file(NV_BASE_SITEURL . $path . '/' . $img, $path)) {
    nv_jsonOutput([
        'status' => 'error',
        'mess' => 'ERROR#' . $lang_module['errorNotSelectFile'] . NV_ROOTDIR . '/' . $path . '/' . $img
    ]);
}

$newimg = preg_replace('/^\W+|\W+$/', '', $img);
$newimg = preg_replace('/[ ]+/', '_', $newimg);
$newimg = strtolower(preg_replace('/\W-/', '', $newimg));
$_array_name = explode('.', $newimg);
$_ext = end($_array_name);
$newimg = preg_replace('/.' . array_pop($_array_name) . '$/', '', $newimg);
$newimg = $newimg . (!str_ends_with($newimg, '.opt') ? '.opt' : '') . '.' . $_ext;

$isNewFile = nv_is_file(NV_BASE_SITEURL . $path . '/' . $newimg, $path) ? false : true;

\Tinify\setKey($global_config['tinify_api']);
$source = \Tinify\fromFile(NV_ROOTDIR . '/' . $path . '/' . $img);
$source->toFile(NV_ROOTDIR . '/' . $path . '/' . $newimg);

if (isset($array_dirname[$path])) {
    $did = $array_dirname[$path];
    $info = nv_getFileInfo($path, $newimg);
    if (!empty($info['filesize'])) {
        if ($isNewFile) {
            $db->query('INSERT INTO ' . NV_UPLOAD_GLOBALTABLE . "_file
                (name, ext, type, filesize, src, srcwidth, srcheight, sizes, userid, mtime, did, title) VALUES
                ('" . $info['name'] . "', '" . $info['ext'] . "', '" . $info['type'] . "', " . $info['filesize'] . ", '" . $info['src'] . "', " . $info['srcwidth'] . ', ' . $info['srcheight'] . ", '" . $info['size'] . "', " . $admin_info['userid'] . ', ' . $info['mtime'] . ', ' . $did . ", '" . $newimg . "')");
        } else {
            $db->query('UPDATE ' . NV_UPLOAD_GLOBALTABLE . '_file SET
                filesize=' . $info['filesize'] . ', srcwidth=' . $info['srcwidth'] . ', srcheight=' . $info['srcheight'] . ", sizes='" . $info['size'] . "', userid=" . $admin_info['userid'] . ', mtime=' . $info['mtime'] . ' WHERE did = ' . $did . " AND title = '" . $newimg . "'");
        }
        nv_dirListRefreshSize();
        nv_insert_logs(NV_LANG_DATA, $module_name, $lang_module['compressimage'], $path . '/' . $newimg, $admin_info['userid']);

        nv_jsonOutput([
            'status' => 'OK',
            'file' => $newimg
        ]);
    }
}

nv_jsonOutput([
    'status' => 'error',
    'mess' => 'ERROR#File not found'
]);
