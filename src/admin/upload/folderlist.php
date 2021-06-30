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

/**
 * nv_set_dir_class()
 *
 * @param array $array
 * @return string
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

/**
 * viewdirtree()
 *
 * @param string $dir
 * @param string $currentpath
 * @return string
 */
function viewdirtree($dir, $currentpath)
{
    global $array_dirname, $global_config, $module_file;

    $pattern = !empty($dir) ? '/^(' . nv_preg_quote($dir) . ')\/([^\/]+)$/' : '/^([^\/]+)$/';
    $_dirlist = preg_grep($pattern, array_keys($array_dirname));

    $content = '';
    foreach ($_dirlist as $_dir) {
        $check_allow_upload_dir = nv_check_allow_upload_dir($_dir);

        if (!empty($check_allow_upload_dir)) {
            $class_li = ($_dir == $currentpath or str_contains($currentpath, $_dir . '/')) ? 'open collapsable' : 'expandable';
            $style_color = ($_dir == $currentpath) ? ' style="color:red"' : '';

            $tree = [];
            $tree['class1'] = $class_li;
            $tree['class2'] = nv_set_dir_class($check_allow_upload_dir) . ' pos' . nv_string_to_filename($dir);
            $tree['style'] = $style_color;
            $tree['title'] = $_dir;
            $tree['titlepath'] = basename($_dir);

            $content2 = viewdirtree($_dir, $currentpath);

            $xtpl = new XTemplate('foldlist.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
            $xtpl->assign('DIRTREE', $tree);

            if (empty($content2)) {
                $content2 = '<li class="hide">&nbsp;</li>';
            }

            if (!empty($content2)) {
                $xtpl->assign('TREE_CONTENT', $content2);
                $xtpl->parse('tree.tree_content');
            }

            $xtpl->parse('tree');
            $content .= $xtpl->text('tree');
        }
    }

    return $content;
}

$path = nv_check_path_upload($nv_Request->get_string('path', 'get,post', NV_UPLOADS_DIR));
if (empty($path)) {
    $path = NV_UPLOADS_DIR;
}
$currentpath = nv_check_path_upload($nv_Request->get_string('currentpath', 'request', NV_UPLOADS_DIR));
$check_allow_upload_dir = nv_check_allow_upload_dir($path);

$data = [];
$data['style'] = $path == $currentpath ? ' style="color:red"' : '';
$data['class'] = nv_set_dir_class($check_allow_upload_dir) . ' pos' . nv_string_to_filename($path);
$data['title'] = $path;
$data['titlepath'] = empty($path) ? NV_BASE_SITEURL : $path;

$content = viewdirtree($path, $currentpath);

$xtpl = new XTemplate('foldlist.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$xtpl->assign('DATA', $data);
$xtpl->assign('PATH', $path);
$xtpl->assign('CURRENTPATH', $currentpath);

$check_allow_upload_dir = nv_check_allow_upload_dir($currentpath);
$xtpl->assign('VIEW_DIR', (isset($check_allow_upload_dir['view_dir']) and $check_allow_upload_dir['view_dir'] === true) ? 1 : 0);
$xtpl->assign('CREATE_DIR', (isset($check_allow_upload_dir['create_dir']) and $check_allow_upload_dir['create_dir'] === true) ? 1 : 0);
$xtpl->assign('RENAME_DIR', (isset($check_allow_upload_dir['rename_dir']) and $check_allow_upload_dir['rename_dir'] === true) ? 1 : 0);
$xtpl->assign('DELETE_DIR', (isset($check_allow_upload_dir['delete_dir']) and $check_allow_upload_dir['delete_dir'] === true) ? 1 : 0);
$xtpl->assign('UPLOAD_FILE', (isset($check_allow_upload_dir['upload_file']) and $check_allow_upload_dir['upload_file'] === true) ? 1 : 0);
$xtpl->assign('CREATE_FILE', (isset($check_allow_upload_dir['create_file']) and $check_allow_upload_dir['create_file'] === true) ? 1 : 0);
$xtpl->assign('RENAME_FILE', (isset($check_allow_upload_dir['rename_file']) and $check_allow_upload_dir['rename_file'] === true) ? 1 : 0);
$xtpl->assign('CROP_FILE', (isset($check_allow_upload_dir['crop_file']) and $check_allow_upload_dir['crop_file'] === true) ? 1 : 0);
$xtpl->assign('ROTATE_FILE', (isset($check_allow_upload_dir['rotate_file']) and $check_allow_upload_dir['rotate_file'] === true) ? 1 : 0);
$xtpl->assign('DELETE_FILE', (isset($check_allow_upload_dir['delete_file']) and $check_allow_upload_dir['delete_file'] === true) ? 1 : 0);
$xtpl->assign('MOVE_FILE', (isset($check_allow_upload_dir['move_file']) and $check_allow_upload_dir['move_file'] === true) ? 1 : 0);

if (empty($content)) {
    $content = '<li class="hide">&nbsp;</li>';
}

if (!empty($content)) {
    $xtpl->assign('CONTENT', $content);
    $xtpl->parse('main.main_content');
}

$xtpl->parse('main');
$contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo $contents;
include NV_ROOTDIR . '/includes/footer.php';
