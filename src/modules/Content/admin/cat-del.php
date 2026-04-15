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
use NukeViet\Module\Content\Cat\CatRepository;
use NukeViet\Module\Content\Cat\CatService;

$catid = $nv_Request->get_int('catid', 'post', 0);

if (!csrf_check($nv_Request->get_string('checkss', 'post'), $admin_info['admin_id'] . '_' . $module_name . '_cat_' . $catid)) {
    nv_jsonOutput([
        'status' => 'error',
        'mess' => $nv_Lang->getGlobal('error_checkss')
    ]);
}

if ($catid > 0) {
    $catRepo = new CatRepository($db, $tables, $nv_Cache, $module_name);
    $catService = new CatService($catRepo);

    // Kiểm tra xem chủ đề có bài viết không
    $contentRepo = new ContentRepository($db, $tables, $nv_Cache, $module_name);
    $count_content = $contentRepo->countByCatid($catid);

    if ($count_content > 0) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => $nv_Lang->getModule('cat_has_content')
        ]);
    }

    $catEntity = $catRepo->findById($catid);
    if ($catEntity) {
        nv_insert_logs(NV_LANG_DATA, $module_name, 'Del Cat', 'catid ' . $catid, $admin_info['userid']);
        if ($catService->deleteCat($catid, $module_name)) {
            nv_jsonOutput([
                'status' => 'success',
            ]);
        }
    }
}

nv_jsonOutput([
    'status' => 'error',
    'mess' => $nv_Lang->getModule('cat_delete_unsuccess')
]);
