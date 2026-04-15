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
use NukeViet\Module\Content\Content\ContentValidator;
use NukeViet\Module\Content\Cat\CatRepository;
use NukeViet\Module\Content\Cat\CatService;
use NukeViet\Module\Content\Shared\SchemaHelper;

// Kiểm tra dung lượng upload
if (!empty($global_config['over_capacity']) and !defined('NV_IS_GODADMIN')) {
    $contents = nv_theme_alert('', $nv_Lang->getGlobal('error_upload_over_capacity1'));
    include NV_ROOTDIR . '/includes/header.php';
    echo nv_admin_theme($contents);
    include NV_ROOTDIR . '/includes/footer.php';
}

$contentRepo = new ContentRepository($db, $tables, $nv_Cache, $module_name);

// File này cần dùng tới Cat + Content Service nên khởi tạo tận nơi
$service = new ContentService($contentRepo);
$catRepo = new CatRepository($db, $tables, $nv_Cache, $module_name);
$catService = new CatService($catRepo);

$id = $nv_Request->get_int('id', 'post,get', 0);
$copy = $nv_Request->get_int('copy', 'get,post', 0);
$entity = null;

if ($id) {
    $entity = $contentRepo->findById($id);
    if (empty($entity)) {
        nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA
            . '&' . NV_NAME_VARIABLE . '=' . $module_name);
    }

    // Sao chép bài viết
    if ($copy) {
        $entity->alias .= '-copy' . nv_date('Hidmy');
    }

    $page_title = $nv_Lang->getModule('edit');
} else {
    $page_title = $nv_Lang->getModule('add');
}

$selectthemes = (!empty($site_mods[$module_name]['theme'])) ? $site_mods[$module_name]['theme'] : $global_config['site_theme'];
$layout_array = nv_scandir(NV_ROOTDIR . '/themes/' . $selectthemes . '/layout', $global_config['check_op_layout']);
$groups_list = nv_groups_list();

// === XỬ LÝ LƯU (AJAX + CSRF) ===
if ($nv_Request->isset_request('checkss', 'post')) {
    if (!csrf_check($nv_Request->get_string('checkss', 'post'), $csrf_key)) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => $nv_Lang->getGlobal('error_checkss')
        ]);
    }

    $respon = ['status' => 'error', 'mess' => ''];

    $row = $service->collectRequestData($nv_Request);

    // Chuẩn hóa toàn bộ dữ liệu (alias, keywords, image, layout, schema, activecomm...) qua Service — DRY
    $row = $service->prepareSaveData($row, $config, $module_upload, $layout_array);

    // Luồng chuẩn: Controller nhận Request -> Đóng gói gửi Validator -> Gọi Service -> Đưa ra Template
    try {
        $saveId = ($id and !$copy) ? $id : 0;
        $validator = new ContentValidator($contentRepo);

        // 1. Kiểm lỗi logic nghiệp vụ
        $validator->validateSave($row, $saveId);

        // 2. Chuyển cho Service chuyên lưu trữ DB (Đã được giao quản lý hệ thống weight, timestamps)
        $savedId = $service->saveContent($row, $saveId, $module_name, $config, $admin_info['admin_id']);

        // 3. Log hành động
        nv_insert_logs(NV_LANG_DATA, $module_name, $saveId ? 'Edit' : 'Add', 'ID: ' . $savedId, $admin_info['userid']);

    } catch (\InvalidArgumentException $e) {
        $fieldMap = [1 => 'title', 2 => 'bodytext', 3 => 'alias'];
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
        . '&' . NV_NAME_VARIABLE . '=' . $module_name;
    nv_jsonOutput($respon);
} elseif ($copy) {
    $sourceEntity = $contentRepo->findById($copy);
    if ($sourceEntity) {
        $row = $service->duplicateContentData($sourceEntity);
        $id = 0;
    }
} elseif (empty($id)) {
    $row = [
        'catid' => 0,
        'title' => '',
        'alias' => '',
        'description' => '',
        'bodytext' => '',
        'keywords' => '',
        'image' => '',
        'imagealt' => '',
        'imageposition' => 0,
        'socialbutton' => 1,
        'status' => 1,
        'hot_post' => 0,
        'layout_func' => '',
        'activecomm' => $module_config[$module_name]['setcomm'] ?? '',
        'schema_type' => $config['schema_type'] ?? 'article',
        'schema_about' => SchemaHelper::$schema_abouts[$config['schema_about'] ?? 'organization'] ?? 'Organization'
    ];
}

// === RENDER FORM (NVSmarty) ===
if (defined('NV_EDITOR')) {
    require_once NV_ROOTDIR . '/' . NV_EDITORSDIR . '/' . NV_EDITOR . '/nv.php';
}

$row_data = isset($entity) ? $entity->toArray() : $row;
$row_data['description'] = nv_htmlspecialchars(nv_br2nl($row_data['description']));
$row_data['bodytext'] = htmlspecialchars(nv_editor_br2nl($row_data['bodytext']));
if (defined('NV_EDITOR') and nv_function_exists('nv_aleditor')) {
    $row_data['bodytext'] = nv_aleditor('bodytext', '100%', '400px', $row_data['bodytext'], '', NV_UPLOADS_DIR . '/' . $module_upload, NV_UPLOADS_DIR . '/' . $module_upload);
} else {
    $row_data['bodytext'] = '<textarea class="form-control" name="bodytext" id="bodytext" rows="15">' . $row_data['bodytext'] . '</textarea>';
}

if (!empty($row_data['image']) and nv_is_file(NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_upload . '/' . $row_data['image'], NV_UPLOADS_DIR . '/' . $module_upload)) {
    $row_data['image'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_upload . '/' . $row_data['image'];
}

$nv_Lang->setGlobal('title_suggest_max', $nv_Lang->getGlobal('length_suggest_max', 65));
$nv_Lang->setGlobal('description_suggest_max', $nv_Lang->getGlobal('length_suggest_max', 160));

// Chuẩn bị mảng layout
$layout_list = [];
foreach ($layout_array as $value) {
    $layout_list[] = preg_replace($global_config['check_op_layout'], '\\1', $value);
}

// Chuẩn bị mảng activecomm
$activecomm = array_map('intval', explode(',', $row_data['activecomm'] ?? ''));

// Chuẩn bị mảng vị trí ảnh
$array_imgposition = [
    0 => $nv_Lang->getModule('imgposition_0'),
    1 => $nv_Lang->getModule('imgposition_1'),
    2 => $nv_Lang->getModule('imgposition_2')
];

$tpl = new \NukeViet\Template\NVSmarty();
$tpl->setTemplateDir(get_module_tpl_dir('content.tpl'));
$tpl->assign('LANG', $nv_Lang);
$tpl->assign('MODULE_NAME', $module_name);
$tpl->assign('OP', $op);
$tpl->assign('ID', $id);
$tpl->assign('ISCOPY', $copy);
$tpl->assign('UPLOADS_DIR_USER', NV_UPLOADS_DIR . '/' . $module_upload);
$tpl->assign('UPLOAD_CURRENT', NV_UPLOADS_DIR . '/' . $module_upload);
$tpl->assign('DATA', $row_data);
$tpl->assign('CATS', $catService->getList());
$tpl->assign('LAYOUT_ARRAY', $layout_list);
$tpl->assign('GROUPS_LIST', $groups_list);
$tpl->assign('ACTIVECOMM', $activecomm);
$tpl->assign('ARRAY_IMGPOSITION', $array_imgposition);
$tpl->assign('SCHEMA_TYPES', SchemaHelper::$schema_types);
$tpl->assign('SCHEMA_ABOUTS', SchemaHelper::$schema_abouts);
$tpl->assign('CHECKSS', csrf_create($csrf_key));

$contents = $tpl->fetch('content.tpl');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
