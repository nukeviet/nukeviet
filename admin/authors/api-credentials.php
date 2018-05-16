<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC <contact@vinades.vn>
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 2-1-2010 21:24
 */

if (!defined('NV_IS_FILE_AUTHORS')) {
    die('Stop!!!');
}

$page_title = $nv_Lang->getModule('api_cr');

// Lấy tất cả API Roles
$sql = 'SELECT * FROM ' . NV_AUTHORS_GLOBALTABLE . '_api_role ORDER BY role_id DESC';
$result = $db->query($sql);

$global_array_roles = array();
while ($row = $result->fetch()) {
    $global_array_roles[$row['role_id']] = $row;
}

if (empty($global_array_roles)) {
    $url = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=api-roles';
    $contents = nv_theme_alert($nv_Lang->getGlobal('site_info'), $nv_Lang->getModule('api_cr_error_role_empty'), 'info', $url, $nv_Lang->getModule('api_roles_add'));
    include NV_ROOTDIR . '/includes/header.php';
    echo nv_admin_theme($contents);
    include NV_ROOTDIR . '/includes/footer.php';
}

// Lấy tất cả các tài khoản quản trị có lẫn không có quyền sử dụng API
$sql = 'SELECT t1.admin_id admin_id, t2.username username, t2.email email, t2.first_name first_name, t2.last_name last_name
FROM ' . NV_AUTHORS_GLOBALTABLE . ' t1 INNER JOIN ' . NV_USERS_GLOBALTABLE . ' t2 ON t1.admin_id = t2.userid ORDER BY t1.lev ASC';
$result = $db->query($sql);

$array = array();
$num_api_admin = 0;
while ($row = $result->fetch()) {
    $array[$row['admin_id']] = $row;
}

$xtpl = new XTemplate($op . '.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$xtpl->assign('LANG', \NukeViet\Core\Language::$lang_module);

// Thông báo nếu Remote API đang tắt.
if (empty($global_config['remote_api_access'])) {
    $url = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=settings&amp;' . NV_OP_VARIABLE . '=system';
    $xtpl->assign('MESSAGE', $nv_Lang->getModule('api_remote_off', $url));
    $xtpl->parse('main.remote_api_off');
}

if (empty($array)) {
    $array;
} else {
    $array;
}

$xtpl->parse('main');
$contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
