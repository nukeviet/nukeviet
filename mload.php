<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2023 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

define('NV_SYSTEM', true);
define('NV_MOD_LOAD', true);

// Xác định thư mục gốc của site
define('NV_ROOTDIR', pathinfo(str_replace(DIRECTORY_SEPARATOR, '/', __FILE__), PATHINFO_DIRNAME));

require NV_ROOTDIR . '/includes/mainfile.php';

$module_name = $nv_Request->get_string(NV_NAME_VARIABLE, 'post,get');
$op = $nv_Request->get_string(NV_OP_VARIABLE, 'post,get');
$mhash = $nv_Request->get_title('mhash', 'post,get'); // là kết quả của md5(NV_CHECK_SESSION . '_' . $module_name . '_' . $op)

if (!hash_equals(mhash_create($module_name, $op), $mhash)) {
    exit('MODHASH_NOT_CORRECT');
}

if (!preg_match($global_config['check_module'], $module_name) or !isset($sys_mods[$module_name])) {
    exit('MODULE_NOT_FOUND');
}

// Xác định các biến toàn cục của module
$module_info = $sys_mods[$module_name];
$module_file = $module_info['module_file'];
$module_data = $module_info['module_data'];
$module_upload = $module_info['module_upload'];

if (!preg_match($global_config['check_op'], $op)) {
    exit('FUNCTION_NOT_FOUND');
}

$other_file = false;
if (!isset($module_info['funcs'][$op])) {
    if (file_exists(NV_ROOTDIR . '/modules/' . $module_file . '/load/' . $op . '.php')) {
        $other_file = true;
    } else {
        exit('FUNCTION_NOT_FOUND');
    }
}

$array_op = [];

// Kết nối với file ngôn ngữ của module
if (file_exists(NV_ROOTDIR . '/modules/' . $module_file . '/language/' . NV_LANG_INTERFACE . '.php')) {
    require NV_ROOTDIR . '/modules/' . $module_file . '/language/' . NV_LANG_INTERFACE . '.php';
} elseif (file_exists(NV_ROOTDIR . '/modules/' . $module_file . '/language/en.php')) {
    require NV_ROOTDIR . '/modules/' . $module_file . '/language/en.php';
}

// Kết nối với file functions.php của module
if (file_exists(NV_ROOTDIR . '/modules/' . $module_file . '/functions.php')) {
    require NV_ROOTDIR . '/modules/' . $module_file . '/functions.php';
}

// Kết nối đến file (function) của biến $op
if (!$other_file) {
    require NV_ROOTDIR . '/modules/' . $module_file . '/funcs/' . $op . '.php';
} else {
    require NV_ROOTDIR . '/modules/' . $module_file . '/load/' . $op . '.php';
}
