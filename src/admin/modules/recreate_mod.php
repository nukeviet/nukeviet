<?php

/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_IS_FILE_MODULES')) {
    exit('Stop!!!');
}

$modname = $nv_Request->get_title('mod', 'post');
$sample = $nv_Request->get_int('sample', 'post', 0);

if (!empty($modname) and preg_match($global_config['check_module'], $modname) and md5(NV_CHECK_SESSION . '_' . $module_name . '_setup_mod_' . $modname) == $nv_Request->get_string('checkss', 'post')) {
    nv_insert_logs(NV_LANG_DATA, $module_name, $nv_Lang->getGlobal('recreate') . ' module "' . $modname . '"', '', $admin_info['userid']);
    if (!defined('NV_MODULE_RECREATE')) {
        define('NV_MODULE_RECREATE', true);
    }
    $check = nv_setup_data_module(NV_LANG_DATA, $modname, $sample);
    if ($check['success']) {
        nv_jsonOutput([
            'status' => 'success',
            'refresh' => 1
        ]);
    }
}

nv_jsonOutput([
    'status' => 'error',
    'mess' => 'Error re-create!'
]);
