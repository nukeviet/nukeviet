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

$forms = nv_scandir(NV_ROOTDIR . '/modules/' . $module_name . '/forms', '/^form\_([a-zA-Z0-9\_\-]+)\.php$/');
$forms = preg_replace('/^form\_([a-zA-Z0-9\_\-]+)\.php$/', '\\1', $forms);

$groups_list = nv_groups_list();
unset($groups_list[1], $groups_list[2], $groups_list[3], $groups_list[5], $groups_list[6]);

// Xử lý khi lưu (AJAX)
if ($nv_Request->isset_request('checkss', 'post')) {
    if (!csrf_check($nv_Request->get_string('checkss', 'post'), $csrf_key)) {
        nv_jsonOutput(['status' => 'error', 'mess' => $nv_Lang->getGlobal('error_checkss')]);
    }

    $blang = strip_tags($nv_Request->get_string('blang', 'post', ''));
    if (!empty($blang) && !in_array($blang, $global_config['allow_sitelangs'], true)) {
        $blang = '';
    }

    $title = nv_htmlspecialchars(strip_tags($nv_Request->get_string('title', 'post', '')));
    $description = defined('NV_EDITOR') ? $nv_Request->get_string('description', 'post', '') : strip_tags($nv_Request->get_string('description', 'post', ''));
    $form = $nv_Request->get_string('form', 'post', 'sequential');
    $require_image = $nv_Request->get_int('require_image', 'post', 0);
    if (!in_array($form, $forms, true)) {
        $form = 'sequential';
    }

    $width = $nv_Request->get_int('width', 'post', 0);
    $height = $nv_Request->get_int('height', 'post', 0);

    $uploadtype = $nv_Request->get_typed_array('uploadtype', 'post', 'title', []);
    $uploadtype = implode(',', $uploadtype);

    $uploadgroup = $nv_Request->get_array('uploadgroup', 'post', []);
    $uploadgroup = !empty($uploadgroup) ? implode(',', nv_groups_post(array_intersect($uploadgroup, array_keys($groups_list)))) : '';

    $exp_time = $nv_Request->get_int('exp_time', 'post', 0);
    $exp_time_custom = $nv_Request->get_float('exp_time_custom', 'post', 0);
    if ($exp_time_custom < 0) {
        $exp_time_custom = 0;
    }
    $exp_time_value = $exp_time;
    if ($exp_time < 0) {
        $exp_time = -1;
        $exp_time_value = $exp_time_custom * 86400;
    } else {
        $exp_time_custom = 0;
    }

    if (empty($title)) {
        nv_jsonOutput(['status' => 'error', 'mess' => $nv_Lang->getModule('title_empty'), 'input' => 'title']);
    }

    if ($width < 50 || $height < 50) {
        nv_jsonOutput(['status' => 'error', 'mess' => $nv_Lang->getModule('size_incorrect')]);
    }

    if (!empty($description)) {
        $description = defined('NV_EDITOR') ? nv_nl2br($description, '') : nv_nl2br(nv_htmlspecialchars($description), '<br />');
    }

    $_sql = 'INSERT INTO ' . NV_BANNERS_GLOBALTABLE . '_plans (
            blang, title, description, form, width, height, act, require_image, uploadtype, uploadgroup, exp_time
        ) VALUES (
            :blang, :title, :description, :form, ' . $width . ', ' . $height . ', 1, :require_image, :uploadtype, :uploadgroup, :exp_time
        )';
    $data_insert = [
        'blang'         => $blang,
        'title'         => $title,
        'description'   => $description,
        'form'          => $form,
        'require_image' => $require_image,
        'uploadtype'    => $uploadtype,
        'uploadgroup'   => $uploadgroup,
        'exp_time'      => $exp_time_value,
    ];
    $id = $db->insert_id($_sql, 'id', $data_insert);

    $nv_Cache->delMod($module_name);
    nv_insert_logs(NV_LANG_DATA, $module_name, 'log_add_plan', 'planid ' . $id, $admin_info['userid']);
    nv_jsonOutput([
        'status'   => 'OK',
        'mess'     => $nv_Lang->getGlobal('success_level'),
        'redirect' => NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=info_plan&id=' . $id,
    ]);
}

// Giá trị mặc định cho form hiển thị
$item = [
    'title'           => '',
    'blang'           => '',
    'form'            => 'sequential',
    'require_image'   => 1,
    'width'           => 50,
    'height'          => 50,
    'exp_time'        => 0,
    'exp_time_custom' => '',
    'uploadtype'      => [],
    'uploadgroup'     => [],
];

// Build danh sách ngôn ngữ cho phép
$allow_langs = array_intersect_key($language_array, array_flip($global_config['allow_sitelangs']));

// Build danh sách kiểu hiển thị
$forms_list = [];
foreach ($forms as $form_key) {
    $forms_list[] = [
        'key'   => $form_key,
        'title' => $nv_Lang->existsModule('form_' . $form_key) ? $nv_Lang->getModule('form_' . $form_key) : $form_key,
    ];
}

// Build danh sách nhóm người dùng
$uploadgroup_list = [];
foreach ($groups_list as $group_id => $group_title) {
    $uploadgroup_list[] = ['id' => (int) $group_id, 'title' => $group_title];
}

// Build danh sách thời gian hiển thị
$exp_time_list = [];
foreach ($array_exp_time as $expt) {
    $exp_time_list[] = ['key' => $expt[0], 'title' => $expt[1]];
}

// Editor mô tả
if (defined('NV_EDITOR')) {
    require_once NV_ROOTDIR . '/' . NV_EDITORSDIR . '/' . NV_EDITOR . '/nv.php';
}
if (defined('NV_EDITOR') && nv_function_exists('nv_aleditor')) {
    $description_html = nv_aleditor('description', '100%', '300px', '', '', NV_UPLOADS_DIR . '/' . $module_upload, NV_UPLOADS_DIR . '/' . $module_upload . '/files');
} else {
    $description_html = '<textarea name="description" id="description" class="form-control" style="height:300px"></textarea>';
}

$tpl = new \NukeViet\Template\NVSmarty();
$tpl->setTemplateDir(get_module_tpl_dir('add-plan.tpl'));

$tpl->assign('LANG', $nv_Lang);
$tpl->assign('MODULE_NAME', $module_name);
$tpl->assign('OP', $op);
$tpl->assign('CHECKSS', csrf_create($csrf_key));
$tpl->assign('ITEM', $item);
$tpl->assign('ALLOW_LANGS', $allow_langs);
$tpl->assign('FORMS_LIST', $forms_list);
$tpl->assign('ARRAY_UPLOADTYPE', $array_uploadtype);
$tpl->assign('UPLOADGROUP_LIST', $uploadgroup_list);
$tpl->assign('EXP_TIME_LIST', $exp_time_list);
$tpl->assign('DESCRIPTION', $description_html);

$contents = $tpl->fetch('add-plan.tpl');

$page_title = $nv_Lang->getModule('add_plan');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
