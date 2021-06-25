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

if ($client_info['is_myreferer'] != 1) {
    exit('Wrong URL');
}

$id = $nv_Request->get_int('id', 'get', 0);
$groups_list = nv_groups_list();

$sql = 'SELECT * FROM ' . NV_BANNERS_GLOBALTABLE . '_plans WHERE id=' . $id;
$row = $db->query($sql)->fetch();

if (empty($row)) {
    exit('Stop!!!');
}

$contents = [];
$contents['caption'] = sprintf($lang_module['info_plan_caption'], $row['title']);
$contents['rows']['title'] = [$lang_module['title'], $row['title']];
$contents['rows']['blang'] = [$lang_module['blang'], (!empty($row['blang'])) ? $language_array[$row['blang']]['name'] : $lang_module['blang_all']];
$contents['rows']['form'] = [$lang_module['form'], (isset($lang_module['form_' . $row['form']]) ? $lang_module['form_' . $row['form']] : $row['form'])];
$contents['rows']['size'] = [$lang_module['size'], $row['width'] . ' x ' . $row['height'] . 'px'];
$contents['rows']['is_act'] = [$lang_module['is_act'], $row['act'] ? $lang_global['yes'] : $lang_global['no']];
$contents['rows']['require_image'] = [$lang_module['require_image'], $lang_module['require_image' . $row['require_image']]];
$contents['rows']['uploadtype'] = [$lang_module['uploadtype'], str_replace(',', ', ', $row['uploadtype'])];

$uploadgroup = [];
if (!empty($row['uploadgroup'])) {
    $row['uploadgroup'] = array_map('intval', explode(',', $row['uploadgroup']));
    foreach ($groups_list as $k => $v) {
        if (in_array($k, $row['uploadgroup'], true)) {
            $uploadgroup[] = $v;
        }
    }
}
$contents['rows']['uploadgroup'] = [$lang_module['plan_uploadgroup'], implode(', ', $uploadgroup)];
$contents['rows']['exp_time'] = [$lang_module['plan_exp_time'], empty($row['exp_time']) ? $lang_module['plan_exp_time_nolimit'] : nv_convertfromSec($row['exp_time'])];

if (!empty($row['description'])) {
    $contents['rows']['description'] = [$lang_module['description'], $row['description']];
}

$contents['edit'] = [NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=edit_plan&amp;id=' . $id, $lang_global['edit']];
$contents['add'] = [NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=add_banner&amp;pid=' . $id, $lang_module['add_banner']];
$contents['del'] = ['nv_pl_del2(' . $id . ');', $lang_global['delete']];
$contents['act'] = ['nv_pl_chang_act2(' . $id . ');', $lang_module['change_act']];

$contents = nv_info_pl_theme($contents);

include NV_ROOTDIR . '/includes/header.php';
echo $contents;
include NV_ROOTDIR . '/includes/footer.php';
