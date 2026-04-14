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
use NukeViet\Module\Devtool\Schema\MvcCodeGenerator;

$schemaRepo = new SchemaRepository($db, NV_ROOTDIR . '/data/devtool');

$table        = $nv_Request->get_title('table', 'post,get', '');
$moduleFilter = $nv_Request->get_title('module_filter', 'post,get', '');

// Validate: phải có bảng và schema đã được lưu
if (empty($table) || !$schemaRepo->tableExists($table)) {
    header('location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=main');
    exit();
}

$entity = $schemaRepo->loadSchema($table);

if ($entity === null) {
    // Schema chưa được lưu → chuyển về trang schemas để lưu trước
    header('location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=schemas&table=' . urlencode($table) . '&module_filter=' . urlencode($moduleFilter));
    exit();
}

$page_title = $nv_Lang->getModule('mvc_generate') ?: ('Tạo MVC: ' . $table);

$generator = new MvcCodeGenerator();
$files     = $generator->generate($entity, NV_ROOTDIR);

// ══════ XỬ LÝ POST — Ghi file thực sự ══════
if ($nv_Request->isset_request('submit_generate', 'post')) {
    if (!csrf_check($nv_Request->get_string('checkss', 'post'), $csrf_key)) {
        nv_jsonOutput(['status' => 'error', 'mess' => $nv_Lang->getGlobal('error_checkss')]);
    }

    $written = [];
    $errors  = [];

    foreach ($files as $file) {
        $dir = dirname($file['full_path']);
        if (!is_dir($dir) && !mkdir($dir, 0755, true) && !is_dir($dir)) {
            $errors[] = $file['path'];
            continue;
        }
        if (file_put_contents($file['full_path'], $file['content']) !== false) {
            $written[] = $file['path'];
        } else {
            $errors[] = $file['path'];
        }
    }

    nv_insert_logs(NV_LANG_DATA, $module_name, 'Generate MVC', 'table: ' . $table . ', files: ' . count($written), $admin_info['userid']);

    nv_jsonOutput([
        'status'  => empty($errors) ? 'OK' : 'error',
        'mess'    => empty($errors)
            ? sprintf($lang_module['mvc_success'] ?? 'Đã tạo %d file MVC thành công.', count($written))
            : sprintf($lang_module['mvc_partial'] ?? 'Tạo được %d file, %d file lỗi.', count($written), count($errors)),
        'written' => $written,
        'errors'  => $errors,
    ]);
}

// ══════ RENDER PREVIEW ══════
$tpl = new \NukeViet\Template\NVSmarty();
$tpl->setTemplateDir(get_module_tpl_dir('schemas-mvc.tpl'));
$tpl->assign('LANG', $nv_Lang);
$tpl->assign('TABLE', $table);
$tpl->assign('MODULE_FILTER', $moduleFilter);
$tpl->assign('ENTITY', $entity->toArray());
// JSON an toàn để nhúng trong <script> (escape HTML entities)
$filesJson = json_encode($files, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
$filesJson = str_replace('\\', '\\\\', $filesJson);
$tpl->assign('FILES', $files);
$tpl->assign('FILES_JSON', $filesJson);
$tpl->assign('CHECKSS', csrf_create($csrf_key));
$tpl->assign('MODULE_NAME', $module_name);
$tpl->assign('NV_BASE_ADMINURL', NV_BASE_ADMINURL);
$tpl->assign('NV_LANG_VARIABLE', NV_LANG_VARIABLE);
$tpl->assign('NV_LANG_DATA', NV_LANG_DATA);
$tpl->assign('NV_NAME_VARIABLE', NV_NAME_VARIABLE);
$tpl->assign('NV_OP_VARIABLE', NV_OP_VARIABLE);

$contents = $tpl->fetch('schemas-mvc.tpl');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
