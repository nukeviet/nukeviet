<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2021 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_IS_FILE_ADMIN')) {
    exit('Stop!!!');
}

$id = $nv_Request->get_int('id', 'get', 0);
$query = 'SELECT * FROM ' . NV_BANNERS_GLOBALTABLE . '_plans WHERE id=' . $id;
$row = $db->query($query)->fetch();

if (empty($row)) {
    nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name);
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
    $require_image = $nv_Request->get_int('require_image', 'post', '0');
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
        $error = $lang_module['title_empty'];
    } elseif ($width < 50 or $height < 50) {
        $error = $lang_module['size_incorrect'];
    } else {
        if (!empty($description)) {
            $description = defined('NV_EDITOR') ? nv_nl2br($description, '') : nv_nl2br(nv_htmlspecialchars($description), '<br />');
        }

        list($blang_old, $form_old) = $db->query('SELECT blang, form FROM ' . NV_BANNERS_GLOBALTABLE . '_plans WHERE id=' . (int) $id)->fetch(3);

        $stmt = $db->prepare('UPDATE ' . NV_BANNERS_GLOBALTABLE . '_plans SET
            blang= :blang, title= :title, description= :description, form= :form, require_image= :require_image, width=' . $width . ', height=' . $height . ',
            uploadtype=:uploadtype, uploadgroup=:uploadgroup, exp_time=:exp_time
        WHERE id=' . $id);
        $stmt->bindParam(':blang', $blang, PDO::PARAM_STR);
        $stmt->bindParam(':title', $title, PDO::PARAM_STR);
        $stmt->bindParam(':description', $description, PDO::PARAM_STR);
        $stmt->bindParam(':form', $form, PDO::PARAM_STR);
        $stmt->bindParam(':require_image', $require_image, PDO::PARAM_STR);
        $stmt->bindParam(':uploadtype', $uploadtype, PDO::PARAM_STR);
        $stmt->bindParam(':uploadgroup', $uploadgroup, PDO::PARAM_STR);
        $stmt->bindParam(':exp_time', $exp_time_value, PDO::PARAM_INT);
        $stmt->execute();

        if ($form_old != $form or $blang_old != $blang) {
            nv_fix_banner_weight($id);
        }

        nv_insert_logs(NV_LANG_DATA, $module_name, 'log_edit_plan', 'planid ' . $id, $admin_info['userid']);
        nv_CreateXML_bannerPlan();
        $nv_Cache->delMod($module_name);
        nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=info_plan&id=' . $id);
    }
} else {
    $blang = $row['blang'];
    $title = $row['title'];
    $description = nv_br2nl($row['description']);
    $form = $row['form'];
    $require_image = $row['require_image'];
    $width = $row['width'];
    $height = $row['height'];
    $exp_time = $row['exp_time'];
    $exp_time_custom = 0;
    $uploadgroup = $row['uploadgroup'];
    $uploadtype = $row['uploadtype'];
    if (!empty($row['exp_time'])) {
        $is_custom_exptime = true;
        foreach ($array_exp_time as $expt) {
            if ($expt[0] == $row['exp_time']) {
                $is_custom_exptime = false;
                break;
            }
        }
        if ($is_custom_exptime) {
            $exp_time_custom = round($row['exp_time'] / 86400, 2);
            $exp_time = -1;
        }
    }
}

if (!empty($description)) {
    $description = nv_htmlspecialchars($description);
}
if (empty($form)) {
    $form = 'sequential';
}
if (empty($width)) {
    $width = 50;
}
if (empty($height)) {
    $height = 50;
}

$info = (!empty($error)) ? $error : $lang_module['edit_plan_info'];
$is_error = (!empty($error)) ? 1 : 0;

$allow_langs = array_flip($global_config['allow_sitelangs']);
$allow_langs = array_intersect_key($language_array, $allow_langs);

$contents = [];
$contents['info'] = $info;
$contents['is_error'] = $is_error;
$contents['submit'] = $lang_module['edit_plan'];
$contents['action'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=edit_plan&amp;id=' . $id;
$contents['title'] = [$lang_module['title'], 'title', $title, 255];
$contents['blang'] = [$lang_module['blang'], 'blang', $lang_module['blang_all'], $allow_langs, $blang];
$contents['form'] = [$lang_module['form'], 'form', $forms, $form, $require_image];
$contents['size'] = $lang_module['size'];
$contents['require_image'] = $require_image;
$contents['width'] = [$lang_module['width'], 'width', $width, 4];
$contents['height'] = [$lang_module['height'], 'height', $height, 4];
$contents['description'] = [$lang_module['description'], 'description', $description, '99%', '300px', defined('NV_EDITOR') ? true : false];
$contents['exp_time'] = $exp_time;
$contents['exp_time_custom'] = $exp_time_custom ? $exp_time_custom : '';
$contents['uploadgroup'] = $uploadgroup;
$contents['uploadtype'] = $uploadtype;

if (defined('NV_EDITOR')) {
    require_once NV_ROOTDIR . '/' . NV_EDITORSDIR . '/' . NV_EDITOR . '/nv.php';
}

$contents = call_user_func('nv_edit_plan_theme', $contents, $array_uploadtype, $groups_list);

$page_title = $lang_module['edit_plan'];

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
