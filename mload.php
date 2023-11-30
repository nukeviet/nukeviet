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
$modulefilelist = get_module_filelist();

$module_name = $nv_Request->get_string(NV_NAME_VARIABLE, 'post,get');
$op = $nv_Request->get_string(NV_OP_VARIABLE, 'post,get');

if (!defined('IS_CROSS_SITE_LOAD')) {
    $mhash = $nv_Request->get_title('mhash', 'post,get'); // là kết quả của md5(NV_CHECK_SESSION . '_' . $module_name . '_' . $op)

    if (!hash_equals(mhash_create($module_name, $op), $mhash)) {
        exit('MODHASH_NOT_CORRECT');
    }
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

if (isset($module_info['funcs'][$op])) {
    $op_file = $module_info['funcs'][$op]['func_name'];
    $full_op_file = $module_file . '/funcs/' . $op_file . '.php';
} else {
    $op_file = $op;
    $full_op_file = $module_file . '/load/' . $op_file . '.php';
}
if (!module_file_exists($full_op_file)) {
    exit('FUNCTION_NOT_FOUND');
}

// Xác định có là user hay không
if ($nv_Request->isset_request('checkuser', 'post,get')) {
    if (defined('NV_IS_USER')) {
        trigger_error('Hacking attempt', 256);
    }
    require NV_ROOTDIR . '/includes/core/is_user.php';
}

$array_op = [];

// Kết nối với file ngôn ngữ của module
$nv_Lang->loadModule($module_file);

// Kết nối với file functions.php của module
if (module_file_exists($module_file . '/functions.php')) {
    require NV_ROOTDIR . '/modules/' . $module_file . '/functions.php';
}

// Kết nối đến file (function) của biến $op
require NV_ROOTDIR . '/modules/' . $full_op_file;
