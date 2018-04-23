<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC <contact@vinades.vn>
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 3/13/2010 0:12
 */

if (!defined('NV_IS_FILE_ADMIN')) {
    die('Stop!!!');
}
if ($client_info['is_myreferer'] != 1) {
    die('Wrong URL');
}

$id = $nv_Request->get_int('id', 'get', 0);
$groups_list = nv_groups_list();

$sql = 'SELECT * FROM ' . NV_BANNERS_GLOBALTABLE . '_plans WHERE id=' . $id;
$row = $db->query($sql)->fetch();

if (empty($row)) {
    die('Stop!!!');
}

$contents = array();
$contents['caption'] = sprintf($nv_Lang->getModule('info_plan_caption'), $row['title']);
$contents['rows']['title'] = array($nv_Lang->getModule('title'), $row['title']);
$contents['rows']['blang'] = array($nv_Lang->getModule('blang'), (!empty($row['blang'])) ? $language_array[$row['blang']]['name'] : $nv_Lang->getModule('blang_all'));
$contents['rows']['form'] = array($nv_Lang->getModule('form'), $row['form']);
$contents['rows']['size'] = array($nv_Lang->getModule('size'), $row['width'] . ' x ' . $row['height'] . 'px');
$contents['rows']['is_act'] = array($nv_Lang->getModule('is_act'), $row['act'] ? $nv_Lang->getGlobal('yes') : $nv_Lang->getGlobal('no'));
$contents['rows']['require_image'] = array($nv_Lang->getModule('require_image'), $lang_module['require_image' . $row['require_image']]);
$contents['rows']['uploadtype'] = array($nv_Lang->getModule('uploadtype'), str_replace(',', ', ', $row['uploadtype']));

$uploadgroup = array();
if (!empty($row['uploadgroup'])) {
    $row['uploadgroup'] = array_map('trim', explode(',', $row['uploadgroup']));
    foreach ($groups_list as $k => $v) {
        if (in_array($k, $row['uploadgroup'])) {
            $uploadgroup[] = $v;
        }
    }

}
$contents['rows']['uploadgroup'] = array($nv_Lang->getModule('plan_uploadgroup'), implode(', ', $uploadgroup));
$contents['rows']['exp_time'] = array($nv_Lang->getModule('plan_exp_time'), empty($row['exp_time']) ? $nv_Lang->getModule('plan_exp_time_nolimit') : nv_convertfromSec($row['exp_time']));

if (!empty($row['description'])) {
    $contents['rows']['description'] = array($nv_Lang->getModule('description'), $row['description']);
}

$contents['edit'] = array(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=edit_plan&amp;id=' . $id, $nv_Lang->getGlobal('edit'));
$contents['add'] = array(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=add_banner&amp;pid=' . $id, $nv_Lang->getModule('add_banner'));
$contents['del'] = array('nv_pl_del2(' . $id . ');', $nv_Lang->getGlobal('delete'));
$contents['act'] = array('nv_pl_chang_act2(' . $id . ');', $nv_Lang->getModule('change_act'));

$contents = nv_info_pl_theme($contents);

include NV_ROOTDIR . '/includes/header.php';
echo $contents;
include NV_ROOTDIR . '/includes/footer.php';
