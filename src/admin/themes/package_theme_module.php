<?php

/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_IS_FILE_THEMES')) {
    exit('Stop!!!');
}

$checkss = md5(NV_CHECK_SESSION . '_' . $module_name . '_' . $op . '_' . $admin_info['userid']);
$page_title = $nv_Lang->getModule('package_theme_module');

if ($checkss == $nv_Request->get_string('checkss', 'post')) {
    $themename = $nv_Request->get_string('themename', 'post');

    if (preg_match($global_config['check_theme'], $themename) or preg_match($global_config['check_theme_mobile'], $themename)) {
        $allowfolder = [];
        $modulearray = [];
        $all_module_file = $nv_Request->get_title('module_file', 'post');
        $module_file_array = explode(',', $all_module_file);
        array_unique($module_file_array);
        foreach ($module_file_array as $_module_file) {
            $_module_file = nv_unhtmlspecialchars($_module_file);
            if (preg_match($global_config['check_module'], $_module_file)) {
                $modulearray[] = $_module_file;
                $allowfolder[] = NV_ROOTDIR . '/themes/' . $themename . '/modules/' . $_module_file . '/';

                if (file_exists(NV_ROOTDIR . '/themes/' . $themename . '/css/' . $_module_file . '.css')) {
                    $allowfolder[] = NV_ROOTDIR . '/themes/' . $themename . '/css/' . $_module_file . '.css';
                }

                $_files = glob(NV_ROOTDIR . '/themes/' . $themename . '/js/' . $_module_file . '*.js');
                foreach ($_files as $_file) {
                    $allowfolder[] = $_file;
                }

                if (file_exists(NV_ROOTDIR . '/themes/' . $themename . '/images/' . $_module_file . '/')) {
                    $allowfolder[] = NV_ROOTDIR . '/themes/' . $themename . '/images/' . $_module_file . '/';
                }
            }
        }
        if (!empty($allowfolder)) {
            $all_module_file = implode('_', $modulearray);
            $file_src = NV_ROOTDIR . '/' . NV_TEMP_DIR . '/' . NV_TEMPNAM_PREFIX . 'theme_' . $themename . '_' . $all_module_file . '_' . md5(nv_genpass(10) . NV_CHECK_SESSION) . '.zip';

            $zip = new PclZip($file_src);
            $zip->create($allowfolder, PCLZIP_OPT_REMOVE_PATH, NV_ROOTDIR . '/themes');

            $filesize = filesize($file_src);
            $file_name = basename($file_src);

            nv_insert_logs(NV_LANG_DATA, $module_name, $nv_Lang->getModule('package_theme_module'), 'file name : ' . $themename . '_' . $all_module_file . '.zip', $admin_info['userid']);

            $linkgetfile = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=getfile&amp;mod=nv4_theme_' . $themename . '_' . $all_module_file . '.zip&amp;checkss=' . md5($file_name . NV_CHECK_SESSION) . '&amp;filename=' . $file_name;

            nv_jsonOutput([
                'status' => 'success',
                'link' => $linkgetfile,
                'size' => nv_convertfromBytes($filesize),
                'name' => 'nv4_theme_' . $themename . '_' . $all_module_file . '.zip'
            ]);
        }
    }

    nv_jsonOutput([
        'status' => 'error',
        'mess' => $nv_Lang->getModule('package_noselect_module_theme')
    ]);
}

$tpl = new \NukeViet\Template\NVSmarty();
$tpl->setTemplateDir(get_module_tpl_dir('package_theme_module.tpl'));
$tpl->assign('LANG', $nv_Lang);
$tpl->assign('MODULE_NAME', $module_name);
$tpl->assign('OP', $op);
$tpl->assign('CHECKSS', $checkss);

$op = $nv_Request->get_title(NV_OP_VARIABLE, 'get', '');
$theme_list = nv_scandir(NV_ROOTDIR . '/themes', [$global_config['check_theme'], $global_config['check_theme_mobile']]);
$array_themes = [];
foreach ($theme_list as $themes_i) {
    if (file_exists(NV_ROOTDIR . '/themes/' . $themes_i . '/config.ini')) {
        $array_themes[] = $themes_i;
    }
}
$tpl->assign('ARRAY_THEMES', $array_themes);

$result = $db->query('SELECT title, module_file, custom_title FROM ' . NV_MODULES_TABLE . ' ORDER BY weight ASC');
$array_module_setup = $array_modules = [];
while ($row = $result->fetch()) {
    if ($row['module_file'] == $row['module_file']) {
        $array_module_setup[] = $row['module_file'];
        $array_modules[] = [
            'module_file' => $row['module_file'],
            'custom_title' => $row['custom_title']
        ];
    }
}
$modules_list = nv_scandir(NV_ROOTDIR . '/modules', $global_config['check_module']);
foreach ($modules_list as $module_i) {
    if (!in_array($module_i, $array_module_setup, true)) {
        $array_modules[] = [
            'module_file' => $module_i,
            'custom_title' => $module_i
        ];
    }
}
$tpl->assign('ARRAY_MODULES', $array_modules);

$contents = $tpl->fetch('package_theme_module.tpl');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
