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

$catid = $nv_Request->get_int('catid', 'post', 0);

if (!csrf_check($nv_Request->get_string('checkss', 'post', ''), $admin_info['admin_id'] . '_' . $module_name . '_cat')) {
    nv_jsonOutput([
        'status' => 'error',
        'mess' => $nv_Lang->getGlobal('error_checkss')
    ]);
}

$title = $nv_Request->get_title('title', 'post', '');

$alias = change_alias($title);
$alias = !empty($config['alias_lower']) ? strtolower($alias) : $alias;

$catRepo = new CatRepository($db, $tables, $nv_Cache, $module_name);

if ($catRepo->isAliasExists($alias, $catid)) {
    $alias .= '-' . (time() % 1000); // Simple suffix for category alias collision
}

nv_jsonOutput([
    'status' => 'success',
    'alias' => $alias
]);
