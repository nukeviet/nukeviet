<?php

/**
 * @Project NUKEVIET 5.0
 * @Author VINADES.,JSC <contact@vinades.vn>
 * @Copyright (C) 2024 VINADES.,JSC. All rights reserved
 * @License: GNU/GPL version 2 or any later version
 */

if (!defined('NV_IS_FILE_ADMIN')) {
    exit('Stop!!!');
}

use NukeViet\Module\Devtool\Schema\SchemaRepository;

$schemaRepo = new SchemaRepository($db, NV_ROOTDIR . '/data/devtool');

// Module filter: người dùng chọn module nào để lọc bảng
$moduleFilter = $nv_Request->get_title('module_filter', 'get', '');

$modules = $schemaRepo->getModulesList(NV_ROOTDIR . '/modules');
$tables = $moduleFilter !== ''
    ? $schemaRepo->getFilteredTablesList($moduleFilter, $db_config['prefix'])
    : $schemaRepo->getTablesList();

$page_title = $nv_Lang->getModule('select_table');

$tpl = new \NukeViet\Template\NVSmarty();
$tpl->setTemplateDir(get_module_tpl_dir('main.tpl'));
$tpl->assign('LANG', $nv_Lang);
$tpl->assign('MODULES', $modules);
$tpl->assign('MODULE_FILTER', $moduleFilter);
$tpl->assign('TABLES', $tables);
$tpl->assign('MODULE_NAME', $module_name);
$tpl->assign('NV_BASE_ADMINURL', NV_BASE_ADMINURL);
$tpl->assign('NV_LANG_VARIABLE', NV_LANG_VARIABLE);
$tpl->assign('NV_LANG_DATA', NV_LANG_DATA);
$tpl->assign('NV_NAME_VARIABLE', NV_NAME_VARIABLE);
$tpl->assign('NV_OP_VARIABLE', NV_OP_VARIABLE);

$contents = $tpl->fetch('main.tpl');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
