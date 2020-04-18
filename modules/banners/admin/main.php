<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC <contact@vinades.vn>
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 2-9-2010 14:43
 */

if (!defined('NV_IS_FILE_ADMIN')) {
    die('Stop!!!');
}

$page_title = $lang_module['main_caption'];

$contents = array();
$contents['containerid'] = array();
$contents['aj'] = array();
$contents['keyword'] = $nv_Request->get_title('q', 'get', '');
$contents['pid'] = $nv_Request->get_int('pid', 'get', 0);

$sql = "SELECT * FROM " . NV_BANNERS_GLOBALTABLE. "_plans ORDER BY blang ASC";
$contents['plans'] = $db->query($sql)->fetchAll();

// Chờ duyệt
$new = $db->query("SELECT COUNT(*) FROM " . NV_BANNERS_GLOBALTABLE . "_rows WHERE act=4")->fetchColumn();

if ($new > 0) {
    $contents['containerid'][] = 'new_list';
    $contents['aj'][] = "nv_show_banners_list('new_list', 0, " . $contents['pid'] . ", 4, '" . $contents['keyword'] . "');";
}

// Chờ hoạt động
$deact = $db->query("SELECT COUNT(*) FROM " . NV_BANNERS_GLOBALTABLE . "_rows WHERE act=0")->fetchColumn();

if ($deact > 0) {
    $contents['containerid'][] = 'unpub_list';
    $contents['aj'][] = "nv_show_banners_list('unpub_list', 0, " . $contents['pid'] . ", 0, '" . $contents['keyword'] . "');";
}

// Đình chỉ hoạt động
$deact = $db->query("SELECT COUNT(*) FROM " . NV_BANNERS_GLOBALTABLE . "_rows WHERE act=3")->fetchColumn();

if ($deact > 0) {
    $contents['containerid'][] = 'deact_list';
    $contents['aj'][] = "nv_show_banners_list('deact_list', 0, " . $contents['pid'] . ", 3, '" . $contents['keyword'] . "');";
}

// Hết hạn
$exp = $db->query("SELECT COUNT(*) FROM " . NV_BANNERS_GLOBALTABLE . "_rows WHERE act=2")->fetchColumn();

if ($exp > 0) {
    $contents['containerid'][] = 'exp_list';
    $contents['aj'][] = "nv_show_banners_list('exp_list', 0, " . $contents['pid'] . ", 2, '" . $contents['keyword'] . "');";
}

if (empty($contents['containerid']) or empty($contents['aj'])) {
    nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=banners_list');
}

$contents = nv_main_theme($contents);

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
