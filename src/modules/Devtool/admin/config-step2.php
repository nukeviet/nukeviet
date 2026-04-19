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
use NukeViet\Module\Devtool\ModuleConfig\ModuleConfigGenerator;
use NukeViet\Module\Devtool\Schema\SchemaRepository;

$page_title = 'Bước 2: Xem trước và Áp dụng mã nguồn';

$target_module = $nv_Request->get_string('target_module', 'get', '');
if (empty($target_module)) {
    header('Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=config-step1');
    exit;
}

$op_raw  = $nv_Request->get_string('op_name', 'get', 'config');
$op_name = preg_replace('/[^a-z0-9\-]/', '', strtolower(trim($op_raw))) ?: 'config';

$repository = new ModuleConfigRepository($db);
$schemaRepository = new SchemaRepository($db, NV_ROOTDIR . '/' . NV_DATADIR . '/devtool/schema');
$service = new ModuleConfigService($repository, $schemaRepository);
$generator = new ModuleConfigGenerator();

$entity = $repository->loadMetadata($target_module, $op_name);
if (!$entity) {
    die('Metadata not found for module: ' . $target_module . ' / op: ' . $op_name);
}

// Xử lý Áp dụng mã nguồn
if ($nv_Request->isset_request('apply', 'post')) {
    if (!csrf_check($nv_Request->get_string('checkss', 'post'), $csrf_key)) {
        nv_jsonOutput(['status' => 'error', 'mess' => $nv_Lang->getGlobal('error_checkss')]);
    }

    $php_code = $generator->generatePHP($target_module, $entity);
    $tpl_code = $generator->generateTPL($entity);

    // Ghi file PHP: admin/{op_name}.php
    $php_file = NV_ROOTDIR . '/modules/' . $target_module . '/admin/' . $op_name . '.php';
    if (!is_dir(dirname($php_file))) {
        mkdir(dirname($php_file), 0755, true);
    }
    file_put_contents($php_file, $php_code);

    // Ghi file TPL: {op_name}.tpl
    $tpl_dir = NV_ROOTDIR . '/themes/admin_future/modules/' . $target_module;
    if (!is_dir($tpl_dir)) {
        mkdir($tpl_dir, 0755, true);
    }
    file_put_contents($tpl_dir . '/' . $op_name . '.tpl', $tpl_code);

    // Patch admin.functions.php + admin.menu.php
    $menu_label = '';
    $groups     = $entity->getGroups();
    if (!empty($groups[0]['title'])) {
        $menu_label = $groups[0]['title'];
    }
    $patched = $generator->patchAdminFiles(NV_ROOTDIR . '/src', $target_module, $op_name, $menu_label);

    $mess = 'Đã áp dụng mã nguồn vào module <strong>' . $target_module . '</strong> (op: <code>' . $op_name . '</code>)';
    if (!empty($patched)) {
        $mess .= '<br><small class="text-success">Đã cập nhật: ' . implode(', ', $patched) . '</small>';
    }

    nv_jsonOutput(['status' => 'success', 'mess' => $mess]);
}

$generated_php = $generator->generatePHP($target_module, $entity);
$generated_tpl = $generator->generateTPL($entity);

$tpl = new \NukeViet\Template\NVSmarty();
$tpl->setTemplateDir(get_module_tpl_dir('config-step2.tpl'));
$tpl->assign('LANG', $nv_Lang);
$tpl->assign('MODULE_NAME', $module_name);
$tpl->assign('TARGET_MODULE', $target_module);
$tpl->assign('OP_NAME', $op_name);
$tpl->assign('OP', $op);
$tpl->assign('PHP_CODE', $generated_php);
$tpl->assign('TPL_CODE', $generated_tpl);
$tpl->assign('CHECKSS', csrf_create($csrf_key));

$contents = $tpl->fetch('config-step2.tpl');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
