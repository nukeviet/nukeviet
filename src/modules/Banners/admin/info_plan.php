<?php

/**
 * NUKEVIET Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2021 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_IS_FILE_ADMIN')) {
    exit('Stop!!!');
}

$id = $nv_Request->get_int('id', 'get', 0);

$sql = 'SELECT title FROM ' . NV_BANNERS_GLOBALTABLE . '_plans WHERE id=' . $id;
$row = $db->query($sql)->fetch();

if (empty($row)) {
    nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name);
}

$page_title = $nv_Lang->getModule('info_plan');

$contents = [];
$contents['containerid'] = [
    'plan_info',
    'banners_list_act',
    'banners_list_queue',
    'banners_list_timeract',
    'banners_list_exp',
    'banners_list_deact'
];
$contents['aj'] = [
    'nv_plan_info(' . $id . ", 'plan_info');",
    "nv_show_banners_list('banners_list_act', 0, " . $id . ', 1);',
    "nv_show_banners_list('banners_list_queue', 0, " . $id . ', 4);',
    "nv_show_banners_list('banners_list_timeract', 0, " . $id . ', 0);',
    "nv_show_banners_list('banners_list_exp', 0, " . $id . ', 2);',
    "nv_show_banners_list('banners_list_deact', 0, " . $id . ', 3);'
];

$contents = nv_info_plan_theme($contents);
$set_active_op = 'plans_list';

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
