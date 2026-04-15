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

use NukeViet\Module\Content\Cat\CatRepository;
use NukeViet\Module\Content\Cat\CatService;

$catid = $nv_Request->get_int('catid', 'post', 0);

if (!csrf_check($nv_Request->get_string('checkss', 'post'), $admin_info['admin_id'] . '_' . $module_name . '_cat_' . $catid)) {
    nv_jsonOutput([
        'status' => 'error',
        'mess' => $nv_Lang->getGlobal('error_checkss')
    ]);
}

$new_weight = $nv_Request->get_int('new_weight', 'post', 0);

if (empty($catid) || empty($new_weight)) {
    nv_jsonOutput([
        'status' => 'error',
        'mess' => 'Wrong data!'
    ]);
}

$catRepo = new CatRepository($db, $tables, $nv_Cache, $module_name);
$catService = new CatService($catRepo);

$cat = $catRepo->findById($catid);
if (empty($cat)) {
    nv_jsonOutput([
        'status' => 'error',
        'mess' => 'Not exists!'
    ]);
}

if ($catService->changeWeight($catid, $new_weight, $module_name)) {
    nv_insert_logs(NV_LANG_DATA, $module_name, 'Change Cat Weight ID: ' . $catid . ': ' . $cat->title, $cat->weight . ' -> ' . $new_weight, $admin_info['userid']);
    nv_jsonOutput([
        'status' => 'success',
        'mess' => 'Success!'
    ]);
}

nv_jsonOutput([
    'status' => 'error',
    'mess' => 'Wrong data!'
]);
