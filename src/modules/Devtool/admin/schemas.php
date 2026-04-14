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
use NukeViet\Module\Devtool\Schema\SchemaService;

$schemaRepo = new SchemaRepository($db, NV_ROOTDIR . '/data/devtool');
$schemaService = new SchemaService($schemaRepo);

$table = $nv_Request->get_title('table', 'get', '');
$moduleFilter = $nv_Request->get_title('module_filter', 'get', '');

if (empty($moduleFilter) or !$schemaRepo->tableExists($table)) {
    header('location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=main');
    exit();
}

$page_title = $nv_Lang->getModule('schemas') . ': ' . $table;

$existing = $schemaRepo->loadSchema($table);
$dbColumns = $schemaRepo->getTableColumns($table);
$columnViewData = $schemaService->buildColumnViewData($dbColumns, $existing);
$pageSettings = $existing?->getPageSettingsForTpl() ?? $schemaService->getDefaultPageSettings($dbColumns);
if ($existing === null) {
    $pageSettings['function_name'] = $schemaService->suggestFunctionName($table);
}
// Danh sách tất cả bảng DB — dùng cho dropdown chọn bảng nguồn trong choice config
$allTables = $schemaRepo->getTablesList();

$tpl = new \NukeViet\Template\NVSmarty();
$tpl->setTemplateDir(get_module_tpl_dir('schemas.tpl'));
$tpl->assign('LANG', $nv_Lang);
$tpl->assign('TABLE', $table);
$tpl->assign('COLUMNS', $columnViewData);
$tpl->assign('PAGE_SETTINGS', $pageSettings);
$tpl->assign('CHECKSS', csrf_create($csrf_key));
$tpl->assign('MODULE_NAME', $module_name);
$tpl->assign('NV_BASE_ADMINURL', NV_BASE_ADMINURL);
$tpl->assign('NV_LANG_VARIABLE', NV_LANG_VARIABLE);
$tpl->assign('NV_LANG_DATA', NV_LANG_DATA);
$tpl->assign('NV_NAME_VARIABLE', NV_NAME_VARIABLE);
$tpl->assign('NV_OP_VARIABLE', NV_OP_VARIABLE);
$tpl->assign('MODULE_FILTER', $moduleFilter);
$tpl->assign('ALL_TABLES', $allTables);
$tpl->assign('IS_EXISTING', ($existing !== null));

$contents = $tpl->fetch('schemas.tpl');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
