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

use NukeViet\Module\Content\Content\ContentRepository;
use NukeViet\Module\Content\Content\ContentService;

$id = $nv_Request->get_int('id', 'post', 0);

if (!csrf_check($nv_Request->get_string('checkss', 'post'), $admin_info['admin_id'] . '_' . $module_name . '_' . $id)) {
    nv_jsonOutput([
        'status' => 'error',
        'mess' => $nv_Lang->getGlobal('error_checkss')
    ]);
}

if ($id > 0) {
    $contentRepo = new ContentRepository($db, $tables, $nv_Cache, $module_name);
    $service = new ContentService($contentRepo);

    $commentTable = '';
    if (isset($site_mods['comment'])) {
        $commentTable = NV_PREFIXLANG . '_comment';
    }

    $row = $contentRepo->findById($id);
    if ($row) {
        nv_insert_logs(NV_LANG_DATA, $module_name, 'log_del_page', 'pageid ' . $id, $admin_info['userid']);
        if ($service->deleteContent($id, $module_name, $commentTable)) {
            nv_jsonOutput([
                'status' => 'success',
            ]);
        }
    }
}

nv_jsonOutput([
    'status' => 'error',
    'mess' => $nv_Lang->getModule('page_delete_unsuccess')
]);
