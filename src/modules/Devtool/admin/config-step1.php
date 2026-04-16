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

use NukeViet\Module\Devtool\ModuleConfig\ModuleConfigRepository;
use NukeViet\Module\Devtool\ModuleConfig\ModuleConfigService;
use NukeViet\Module\Devtool\Schema\SchemaRepository;

$page_title = $nv_Lang->getModule('config_step1_title');

$repository = new ModuleConfigRepository($db);
$schemaRepository = new SchemaRepository($db, NV_ROOTDIR . '/' . NV_DATADIR . '/devtool/schema');
$service = new ModuleConfigService($repository, $schemaRepository);

$target_module = $nv_Request->get_string('target_module', 'get,post', '');

// Xử lý AJAX lấy danh sách bảng
if ($nv_Request->get_string('mode', 'get') === 'get_tables') {
    $mod = $nv_Request->get_string('mod', 'get', '');
    $tables = $service->getTables($db, $mod);
    nv_jsonOutput($tables);
}

// Xử lý AJAX lấy danh sách cột
if ($nv_Request->get_string('mode', 'get') === 'get_columns') {
    $table = $nv_Request->get_string('table', 'get', '');
    $columns = $service->getColumns($db, $table);
    nv_jsonOutput($columns);
}

if ($nv_Request->isset_request('save', 'post')) {
    if (!csrf_check($nv_Request->get_string('checkss', 'post'), $csrf_key)) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => $nv_Lang->getGlobal('error_checkss')
        ]);
    }

    $entity = $service->collectFromRequest($nv_Request);
    $result = $service->save($entity);

    if ($result) {
        $res = [
            'status' => 'success',
            'mess' => $nv_Lang->getGlobal('save_success')
        ];
        
        if ($nv_Request->get_int('continue', 'post', 0) === 1) {
            $res['redirect'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=config-step2&target_module=' . $target_module;
        }
        
        nv_jsonOutput($res);
    } else {
        $module_test = $entity->getModule();
        nv_jsonOutput([
            'status' => 'error',
            'mess' => 'Error saving metadata for module: ' . ($module_test ?: 'EMPTY') . '. Check src/data/devtool/ permissions.'
        ]);
    }
}

$module_list = $service->getInstalledModules($db);

$data = [];
if (!empty($target_module)) {
    $entity = $service->prepareInitialMetadata($target_module, NV_LANG_DATA);
    $data = $entity->toArray();
}

$tpl = new \NukeViet\Template\NVSmarty();
$tpl->setTemplateDir(get_module_tpl_dir('config-step1.tpl'));
$tpl->assign('LANG', $nv_Lang);
$tpl->assign('MODULE_NAME', $module_name);
$tpl->assign('OP', $op);
$tpl->assign('MODULE_LIST', $module_list);
$tpl->assign('TARGET_MODULE', $target_module);
$tpl->assign('DATA', $data);
$tpl->assign('CHECKSS', csrf_create($csrf_key));

$contents = $tpl->fetch('config-step1.tpl');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
