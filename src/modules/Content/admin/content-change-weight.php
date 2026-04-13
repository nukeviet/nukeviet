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

use NukeViet\Module\Content\Shared\ContentService;

$id = $nv_Request->get_int('id', 'post', 0);

if (!csrf_check($nv_Request->get_string('checkss', 'post'), $admin_info['admin_id'] . '_' . $module_name . '_' . $id)) {
    nv_jsonOutput([
        'success' => 0,
        'text' => $nv_Lang->getGlobal('error_checkss')
    ]);
}

$new_weight = $nv_Request->get_int('new_weight', 'post', 0);

if (empty($id) || empty($new_weight)) {
    nv_jsonOutput([
        'success' => 0,
        'text' => 'Wrong data!'
    ]);
}

$row_data = $repo->findById($id);
if (empty($row_data)) {
    nv_jsonOutput([
        'success' => 0,
        'text' => 'Not exists!'
    ]);
}

$service = new ContentService($repo);
if ($service->changeWeight($id, $new_weight, $module_name)) {
    nv_insert_logs(NV_LANG_DATA, $module_name, 'Change weight ID: ' . $row_data->id . ': ' . $row_data->title, $row_data->weight . ' -> ' . $new_weight, $admin_info['userid']);
    nv_jsonOutput([
        'success' => 1,
        'text' => 'Success!'
    ]);
}

nv_jsonOutput([
    'success' => 0,
    'text' => 'Wrong data!'
]);
