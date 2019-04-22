<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 2-2-2010 12:55
 */

if (!defined('NV_IS_FILE_ADMIN')) {
    die('Stop!!!');
}

/**
 * nv_set_dir_class()
 *
 * @param mixed $array
 * @return void
 */
function nv_set_dir_class($array)
{
    $class = ['folder'];
    $menu = false;

    if (!empty($array)) {
        foreach ($array as $key => $item) {
            if ($item) {
                $class[] = $key;
            }
            if ($key == 'create_dir' and $item) {
                $menu = true;
            }
            if ($key == 'rename_dir' and $item) {
                $menu = true;
            }
            if ($key == 'delete_dir' and $item) {
                $menu = true;
            }
        }
    }

    $class = implode(' ', $class);

    if ($menu) {
        $class .= ' menu';
    }

    return $class;
}

$path = nv_check_path_upload($nv_Request->get_string('path', 'get,post', NV_UPLOADS_DIR));
if (empty($path)) {
    $path = NV_UPLOADS_DIR;
}
$currentpath = nv_check_path_upload($nv_Request->get_string('currentpath', 'request', NV_UPLOADS_DIR));
$check_allow_upload_dir = nv_check_allow_upload_dir($path);

$data = [];
$data['class'] = nv_set_dir_class($check_allow_upload_dir) . ' pos' . nv_string_to_filename($path);
$data['title'] = $path;
$data['titlepath'] = empty($path) ? NV_BASE_SITEURL : $path;

$tpl = new \NukeViet\Template\Smarty();
$tpl->registerPlugin('modifier', 'pregquote', 'nv_preg_quote');
$tpl->registerPlugin('modifier', 'pregGrep', 'preg_grep');
$tpl->registerPlugin('modifier', 'arrayKeys', 'array_keys');
$tpl->registerPlugin('modifier', 'isAllowed', 'nv_check_allow_upload_dir');
$tpl->registerPlugin('modifier', 'getDirName', 'basename');
$tpl->registerPlugin('modifier', 'strpos', 'strpos');
$tpl->registerPlugin('modifier', 'getClassOfDir', 'nv_set_dir_class');
$tpl->registerPlugin('modifier', 'getClassDisplayDirName', 'nv_string_to_filename');
$tpl->setTemplateDir(NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$tpl->assign('LANG', $nv_Lang);

$tpl->assign('PATH', $path);
$tpl->assign('CURRENTPATH', $currentpath);
$tpl->assign('DATA', $data);
$tpl->assign('ALL_FOLDERS', $array_dirname);

$check_allow_upload_dir = nv_check_allow_upload_dir($currentpath);
$tpl->assign('VIEW_DIR', (isset($check_allow_upload_dir['view_dir']) and $check_allow_upload_dir['view_dir'] === true) ? 1 : 0);
$tpl->assign('CREATE_DIR', (isset($check_allow_upload_dir['create_dir']) and $check_allow_upload_dir['create_dir'] === true) ? 1 : 0);
$tpl->assign('RENAME_DIR', (isset($check_allow_upload_dir['rename_dir']) and $check_allow_upload_dir['rename_dir'] === true) ? 1 : 0);
$tpl->assign('DELETE_DIR', (isset($check_allow_upload_dir['delete_dir']) and $check_allow_upload_dir['delete_dir'] === true) ? 1 : 0);
$tpl->assign('UPLOAD_FILE', (isset($check_allow_upload_dir['upload_file']) and $check_allow_upload_dir['upload_file'] === true) ? 1 : 0);
$tpl->assign('CREATE_FILE', (isset($check_allow_upload_dir['create_file']) and $check_allow_upload_dir['create_file'] === true) ? 1 : 0);
$tpl->assign('RENAME_FILE', (isset($check_allow_upload_dir['rename_file']) and $check_allow_upload_dir['rename_file'] === true) ? 1 : 0);
$tpl->assign('CROP_FILE', (isset($check_allow_upload_dir['crop_file']) and $check_allow_upload_dir['crop_file'] === true) ? 1 : 0);
$tpl->assign('ROTATE_FILE', (isset($check_allow_upload_dir['rotate_file']) and $check_allow_upload_dir['rotate_file'] === true) ? 1 : 0);
$tpl->assign('DELETE_FILE', (isset($check_allow_upload_dir['delete_file']) and $check_allow_upload_dir['delete_file'] === true) ? 1 : 0);
$tpl->assign('MOVE_FILE', (isset($check_allow_upload_dir['move_file']) and $check_allow_upload_dir['move_file'] === true) ? 1 : 0);

$contents = $tpl->fetch('foldlist.tpl');

include NV_ROOTDIR . '/includes/header.php';
echo $contents;
include NV_ROOTDIR . '/includes/footer.php';
