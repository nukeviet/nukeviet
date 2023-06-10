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

$sql = 'SELECT * FROM ' . NV_BANNERS_GLOBALTABLE . '_plans ORDER BY blang ASC';
$result = $db->query($sql);

$contents = [];
$contents['caption'] = $nv_Lang->getModule('plans_list2');
$contents['thead'] = [$nv_Lang->getModule('title'), $nv_Lang->getModule('blang'), $nv_Lang->getModule('size'), $nv_Lang->getModule('is_act'), $nv_Lang->getGlobal('actions')];
$contents['view'] = $nv_Lang->getGlobal('detail');
$contents['edit'] = $nv_Lang->getGlobal('edit');
$contents['add'] = $nv_Lang->getModule('admin_add_banner');
$contents['del'] = $nv_Lang->getGlobal('delete');
$contents['rows'] = [];

while ($row = $result->fetch()) {
    $contents['rows'][$row['id']]['title'] = $row['title'];
    $contents['rows'][$row['id']]['blang'] = (!empty($row['blang'])) ? $language_array[$row['blang']]['name'] : $nv_Lang->getModule('blang_all');
    $contents['rows'][$row['id']]['size'] = $row['width'] . ' x ' . $row['height'] . 'px';
    $contents['rows'][$row['id']]['act'] = ['act_' . $row['id'], $row['act'], 'nv_pl_chang_act(' . $row['id'] . ",'act_" . $row['id'] . "');"];
    $contents['rows'][$row['id']]['view'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=info_plan&amp;id=' . $row['id'];
    $contents['rows'][$row['id']]['edit'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=edit_plan&amp;id=' . $row['id'];
    $contents['rows'][$row['id']]['add'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=add_banner&amp;pid=' . $row['id'];
    $contents['rows'][$row['id']]['del'] = 'nv_pl_del(' . $row['id'] . ');';
}

include NV_ROOTDIR . '/includes/header.php';
echo nv_plist_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
