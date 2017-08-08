<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC <contact@vinades.vn>
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 3/13/2010 0:3
 */

if (!defined('NV_IS_FILE_ADMIN')) {
    die('Stop!!!');
}

$id = $nv_Request->get_int('id', 'get', 0);

$sql = 'SELECT title FROM ' . NV_BANNERS_GLOBALTABLE . '_plans WHERE id=' . $id;
$row = $db->query($sql)->fetch();

if (empty($row)) {
    nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name);
}

$page_title = $lang_module['info_plan'];

$contents = array();
$contents['containerid'] = array(
    'plan_info',
    'banners_list_act',
    'banners_list_queue',
    'banners_list_timeract',
    'banners_list_exp',
    'banners_list_deact'
);
$contents['aj'] = array(
    "nv_plan_info(" . $id . ", 'plan_info');",
    "nv_show_banners_list('banners_list_act', 0, " . $id . ", 1);",
    "nv_show_banners_list('banners_list_queue', 0, " . $id . ", 4);",
    "nv_show_banners_list('banners_list_timeract', 0, " . $id . ", 0);",
    "nv_show_banners_list('banners_list_exp', 0, " . $id . ", 2);",
    "nv_show_banners_list('banners_list_deact', 0, " . $id . ", 3);"
);

$contents = nv_info_plan_theme($contents);
$set_active_op = 'plans_list';

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
