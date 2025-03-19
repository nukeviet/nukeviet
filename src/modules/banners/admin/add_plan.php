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

$error = '';
$groups_list = nv_groups_list();
unset($groups_list[1], $groups_list[2], $groups_list[3], $groups_list[5], $groups_list[6]);

if ($nv_Request->get_int('save', 'post') == '1') {
    $blang = strip_tags($nv_Request->get_string('blang', 'post', ''));

    if (!empty($blang) and !in_array($blang, $global_config['allow_sitelangs'], true)) {
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
        $error = $nv_Lang->getModule('title_empty');
    } elseif ($width < 50 or $height < 50) {
        $error = $nv_Lang->getModule('size_incorrect');
    } else {
        if (!empty($description)) {
            $description = defined('NV_EDITOR') ? nv_nl2br($description, '') : nv_nl2br(nv_htmlspecialchars($description), '<br />');
        }

        $_sql = 'INSERT INTO ' . NV_BANNERS_GLOBALTABLE . '_plans (
            blang, title, description, form, width, height, act, require_image, uploadtype, uploadgroup, exp_time
        ) VALUES (
            :blang, :title, :description, :form, ' . $width . ', ' . $height . ', 1, :require_image, :uploadtype, :uploadgroup, :exp_time
        )';
        $data_insert = [];
        $data_insert['blang'] = $blang;
        $data_insert['title'] = $title;
        $data_insert['description'] = $description;
        $data_insert['form'] = $form;
        $data_insert['require_image'] = $require_image;
        $data_insert['uploadtype'] = $uploadtype;
        $data_insert['uploadgroup'] = $uploadgroup;
        $data_insert['exp_time'] = $exp_time_value;
        $id = $db->insert_id($_sql, 'id', $data_insert);

        $nv_Cache->delMod($module_name);
        nv_insert_logs(NV_LANG_DATA, $module_name, 'log_add_plan', 'planid ' . $id, $admin_info['userid']);
        nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=info_plan&id=' . $id);
    }
} else {
    $blang = $title = $description = '';
    $form = 'sequential';
    $width = $height = 50;
    $require_image = 1;
    $exp_time = 0;
    $exp_time_custom = 0;
    $uploadgroup = '';
    $uploadtype = '';
}

if (!empty($description)) {
    $description = nv_htmlspecialchars($description);
}
if (empty($width)) {
    $width = 50;
}
if (empty($height)) {
    $height = 50;
}

$info = (!empty($error)) ? $error : $nv_Lang->getModule('add_plan_info');
$is_error = (!empty($error)) ? 1 : 0;

$allow_langs = array_flip($global_config['allow_sitelangs']);
$allow_langs = array_intersect_key($language_array, $allow_langs);

$data = [];
$data['info'] = $info;
$data['is_error'] = $is_error;
$data['submit'] = $nv_Lang->getModule('add_plan');
$data['action'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=add_plan';
$data['title'] = [$nv_Lang->getModule('title'), 'title', $title, 255];
$data['blang'] = [$nv_Lang->getModule('blang'), 'blang', $nv_Lang->getModule('blang_all'), $allow_langs, $blang];
$data['form'] = [$nv_Lang->getModule('form'), 'form', $forms, $form];
$data['size'] = $nv_Lang->getModule('size');
$data['require_image'] = $require_image;
$data['width'] = [$nv_Lang->getModule('width'), 'width', $width, 4];
$data['height'] = [$nv_Lang->getModule('height'), 'height', $height, 4];
$data['description'] = [$nv_Lang->getModule('description'), 'description', $description, '99%', '300px', defined('NV_EDITOR') ? true : false];
$data['exp_time'] = $exp_time;
$data['exp_time_custom'] = $exp_time_custom ?: '';
$data['uploadgroup'] = $uploadgroup;
$data['uploadtype'] = $uploadtype;

if (defined('NV_EDITOR')) {
    require_once NV_ROOTDIR . '/' . NV_EDITORSDIR . '/' . NV_EDITOR . '/nv.php';
}

$tpl = new \NukeViet\Template\NVSmarty();
$tpl->setTemplateDir(get_module_tpl_dir('add_plan.tpl'));
$tpl->assign('LANG', $nv_Lang);
$tpl->assign('MODULE_NAME', $module_name);
$tpl->assign('MODULE_DATA', $module_data);
$tpl->assign('OP', $op);

$data['uploadtype'] = explode(',', $data['uploadtype']);
$tpl->assign('data', $data);

if ($data['description'][5] and nv_function_exists('nv_aleditor')) {
    $description = nv_aleditor($data['description'][1], $data['description'][3], $data['description'][4], $data['description'][2], '', NV_UPLOADS_DIR . '/' . $module_upload, NV_UPLOADS_DIR . '/' . $module_upload . '/files');
} else {
    $description = '<textarea name="' . $data['description'][1] . '" id="' . $data['description'][1] . '" style="width:' . $data['description'][3] . ';height:' . $data['description'][4] . '">' . $data['description'][2] . '</textarea>\n';
}
$tpl->assign('DESCRIPTION', $description);
$tpl->assign('array_uploadtype', $array_uploadtype);
$tpl->assign('groups_list', $groups_list);
$tpl->assign('array_exp_time', $array_exp_time);

$uploadgroup = array_map('intval', explode(',', $data['uploadgroup']));
$tpl->assign('uploadgroup', $uploadgroup);

$contents = $tpl->fetch('add_plan.tpl');
$page_title = $nv_Lang->getModule('add_plan');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
