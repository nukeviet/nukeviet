<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC <contact@vinades.vn>
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 3/12/2010 12:25
 */

if (!defined('NV_IS_FILE_ADMIN')) {
    die('Stop!!!');
}

$forms = nv_scandir(NV_ROOTDIR . '/modules/' . $module_name . '/forms', '/^form\_([a-zA-Z0-9\_\-]+)\.php$/');
$forms = preg_replace('/^form\_([a-zA-Z0-9\_\-]+)\.php$/', '\\1', $forms);

$error = '';
$groups_list = nv_groups_list();
unset($groups_list[1], $groups_list[2], $groups_list[3], $groups_list[5], $groups_list[6]);

if ($nv_Request->get_int('save', 'post') == '1') {
    $blang = strip_tags($nv_Request->get_string('blang', 'post', ''));

    if (!empty($blang) and !in_array($blang, $global_config['allow_sitelangs'])) {
        $blang = '';
    }

    $title = nv_htmlspecialchars(strip_tags($nv_Request->get_string('title', 'post', '')));
    $description = defined('NV_EDITOR') ? $nv_Request->get_string('description', 'post', '') : strip_tags($nv_Request->get_string('description', 'post', ''));
    $form = $nv_Request->get_string('form', 'post', 'sequential');
	$require_image = $nv_Request->get_int('require_image', 'post', 0);
    if (!in_array($form, $forms)) {
        $form = 'sequential';
    }

    $width = $nv_Request->get_int('width', 'post', 0);
    $height = $nv_Request->get_int('height', 'post', 0);

    $uploadtype = $nv_Request->get_typed_array('uploadtype', 'post', 'title', array());
    $uploadtype = implode(',', $uploadtype);

    $uploadgroup = $nv_Request->get_array('uploadgroup', 'post', array());
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
        $error = $lang_module['title_empty'];
    } elseif ($width < 50 or $height < 50) {
        $error = $lang_module['size_incorrect'];
    } else {
        if (!empty($description)) {
            $description = defined('NV_EDITOR') ? nv_nl2br($description, '') : nv_nl2br(nv_htmlspecialchars($description), '<br />');
        }

        $_sql = 'INSERT INTO ' . NV_BANNERS_GLOBALTABLE. '_plans (
            blang, title, description, form, width, height, act, require_image, uploadtype, uploadgroup, exp_time
        ) VALUES (
            :blang, :title, :description, :form, ' . $width . ', ' . $height . ', 1, :require_image, :uploadtype, :uploadgroup, :exp_time
        )';
        $data_insert = array();
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

$info = (!empty($error)) ? $error : $lang_module['add_plan_info'];
$is_error = (!empty($error)) ? 1 : 0;

$allow_langs = array_flip($global_config['allow_sitelangs']);
$allow_langs = array_intersect_key($language_array, $allow_langs);

$contents = array();
$contents['info'] = $info;
$contents['is_error'] = $is_error;
$contents['submit'] = $lang_module['add_plan'];
$contents['action'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=add_plan';
$contents['title'] = array($lang_module['title'], 'title', $title, 255);
$contents['blang'] = array($lang_module['blang'], 'blang', $lang_module['blang_all'], $allow_langs, $blang);
$contents['form'] = array($lang_module['form'], 'form', $forms, $form);
$contents['size'] = $lang_module['size'];
$contents['require_image'] = $require_image;
$contents['width'] = array($lang_module['width'], 'width', $width, 4);
$contents['height'] = array($lang_module['height'], 'height', $height, 4);
$contents['description'] = array($lang_module['description'], 'description', $description, '99%', '300px', defined('NV_EDITOR') ? true : false);
$contents['exp_time'] = $exp_time;
$contents['exp_time_custom'] = $exp_time_custom ? $exp_time_custom : '';
$contents['uploadgroup'] = $uploadgroup;
$contents['uploadtype'] = $uploadtype;

if (defined('NV_EDITOR')) {
    require_once NV_ROOTDIR . '/' . NV_EDITORSDIR . '/' . NV_EDITOR . '/nv.php';
}

$contents = nv_add_plan_theme($contents, $array_uploadtype, $groups_list);

$page_title = $lang_module['add_plan'];

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
