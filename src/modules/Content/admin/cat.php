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
use NukeViet\Module\Content\Cat\CatValidator;

// File này cần CatRepository nên khởi tạo tại chỗ
$catRepo = new CatRepository($db, $tables, $nv_Cache, $module_name);
$catService = new CatService($catRepo);

$page_title = $nv_Lang->getModule('cat_list');

// === XỬ LÝ LƯU FORM (NVSmarty + CSRF)===
$edit_catid = $nv_Request->get_int('catid', 'post,get', 0);

if ($nv_Request->isset_request('save', 'post')) {
    if (!csrf_check($nv_Request->get_string('checkss', 'post'), $csrf_key)) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => $nv_Lang->getGlobal('error_checkss')
        ]);
    }

    $respon = ['status' => 'error', 'mess' => ''];

    $data = $catService->collectRequestData($nv_Request);

    // Chuẩn hóa dữ liệu (alias, keywords, image) qua Service — DRY
    $data = $catService->prepareSaveData($data, $config, $module_upload);

    $saveId = $edit_catid ?: 0;

    try {
        $validator = new CatValidator($catRepo);

        // 1. Validator bắt lỗi (Exception văng ra nếu Invalid)
        $validator->validateSave($data, $saveId);

        // 2. Chuyển cho Service chuyên lưu trữ DB
        $savedId = $catService->saveCat($data, $saveId, $module_name);

        // 3. Ghi log sau phi vụ thành công
        if ($saveId) {
            nv_insert_logs(NV_LANG_DATA, $module_name, 'Edit Cat', 'catid: ' . $saveId, $admin_info['userid']);
        } else {
            nv_insert_logs(NV_LANG_DATA, $module_name, 'Add Cat', 'catid: ' . $savedId, $admin_info['userid']);
        }

    } catch (\InvalidArgumentException $e) {
        $fieldMap = [1 => 'title', 2 => 'alias'];
        $respon['input'] = $fieldMap[$e->getCode()] ?? reset($fieldMap);
        $respon['mess'] = $nv_Lang->getModule($e->getMessage());

        // Trả toàn bộ lỗi cho JS nếu có nhiều trường sai cùng lúc
        if ($e instanceof \NukeViet\Module\Content\Shared\ValidationException) {
            $respon['errors'] = [];
            foreach ($e->getErrors() as $code => $langKey) {
                $respon['errors'][] = [
                    'field' => $fieldMap[$code] ?? 'title',
                    'message' => $nv_Lang->getModule($langKey),
                ];
            }
        }
        nv_jsonOutput($respon);
    } catch (\Throwable $e) {
        trigger_error($e);
        $respon['mess'] = $nv_Lang->getGlobal('error_system');
        nv_jsonOutput($respon);
    }

    $respon['status'] = 'success';
    $respon['mess'] = $nv_Lang->getGlobal('save_success');
    $respon['redirect'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA
        . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=cat';
    nv_jsonOutput($respon);
}

// === RENDER DANH SÁCH + FORM ===
$_cats = $catRepo->getAll();
$num_cats = count($_cats);
$array_cats = [];
foreach ($_cats as $cat) {
    $cat->checkss = csrf_create($admin_info['admin_id'] . '_' . $module_name . '_cat_' . $cat->catid);
    $cat->url_edit = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA
        . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=cat&amp;catid=' . $cat->catid;
    $array_cats[] = $cat->toArray();
}

// Dữ liệu form edit (nếu có catid)
$edit_data = null;
if ($edit_catid > 0) {
    $editEntity = $catRepo->findById($edit_catid);
    if ($editEntity) {
        $edit_data = $editEntity->toArray();
        $page_title = $nv_Lang->getModule('cat_edit');
    }
}

$tpl = new \NukeViet\Template\NVSmarty();
$tpl->setTemplateDir(get_module_tpl_dir('cat.tpl'));
$tpl->registerPlugin('modifier', 'ddatetime', 'nv_datetime_format');
$tpl->assign('LANG', $nv_Lang);
$tpl->assign('MODULE_NAME', $module_name);
$tpl->assign('OP', $op);
$tpl->assign('DATA', $array_cats);
$tpl->assign('NUM_CATS', $num_cats);
$tpl->assign('EDIT_DATA', $edit_data);
$tpl->assign('EDIT_CATID', $edit_catid);
$tpl->assign('UPLOADS_DIR_USER', NV_UPLOADS_DIR . '/' . $module_upload);
$tpl->assign('CHECKSS', csrf_create($csrf_key));

$contents = $tpl->fetch('cat.tpl');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
