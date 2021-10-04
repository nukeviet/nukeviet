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

$contents = [];
$contents['list'] = [];
$contents['keyword'] = $nv_Request->get_title('q', 'get', '');
$contents['pid'] = $nv_Request->get_int('pid', 'get', 0);

$where = '';
if (!empty($contents['keyword'])) {
    $keyword = $db->dblikeescape($contents['keyword']);
    $where .= " AND (title LIKE '%" . $keyword . "%' OR file_alt LIKE '%" . $keyword . "%' OR click_url LIKE '%" . $keyword . "%' OR bannerhtml LIKE '%" . $keyword . "%')";
}

if (!empty($contents['pid'])) {
    $where .= ' AND pid=' . $contents['pid'];
}

// Chờ duyệt
$new = $db->query('SELECT COUNT(*) FROM ' . NV_BANNERS_GLOBALTABLE . '_rows WHERE act=4' . $where)->fetchColumn();

if ($new > 0) {
    $contents['list'][] = [
        'key' => 'new_list',
        'act' => 4,
        'title' => $lang_module['banners_list4'],
        'num' => $new
    ];
}

// Chờ hoạt động
$deact = $db->query('SELECT COUNT(*) FROM ' . NV_BANNERS_GLOBALTABLE . '_rows WHERE act=0' . $where)->fetchColumn();

if ($deact > 0) {
    $contents['list'][] = [
        'key' => 'unpub_list',
        'act' => 0,
        'title' => $lang_module['banners_list0'],
        'num' => $deact
    ];
}

// Đình chỉ hoạt động
$deact = $db->query('SELECT COUNT(*) FROM ' . NV_BANNERS_GLOBALTABLE . '_rows WHERE act=3' . $where)->fetchColumn();

if ($deact > 0) {
    $contents['list'][] = [
        'key' => 'deact_list',
        'act' => 3,
        'title' => $lang_module['banners_list3'],
        'num' => $deact
    ];
}

// Hết hạn
$exp = $db->query('SELECT COUNT(*) FROM ' . NV_BANNERS_GLOBALTABLE . '_rows WHERE act=2' . $where)->fetchColumn();

if ($exp > 0) {
    $contents['list'][] = [
        'key' => 'exp_list',
        'act' => 2,
        'title' => $lang_module['banners_list2'],
        'num' => $exp
    ];
}

if (empty($contents['list']) and empty($contents['keyword']) and empty($contents['pid'])) {
    nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=banners_list');
}

$xtpl = new XTemplate('main.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('GLANG', $lang_global);
$xtpl->assign('FORM_ACTION', NV_BASE_ADMINURL . 'index.php');
$xtpl->assign('MODULE_NAME', $module_name);
$xtpl->assign('CONTENTS', $contents);

$sql = 'SELECT * FROM ' . NV_BANNERS_GLOBALTABLE . '_plans ORDER BY blang ASC';
$plans = $db->query($sql)->fetchAll();

foreach ($plans as $plan) {
    $plan['selected'] = $plan['id'] == $contents['pid'] ? ' selected="selected"' : '';
    $xtpl->assign('PLAN', $plan);
    $xtpl->parse('main.plan');
}

foreach ($contents['list'] as $list) {
    $xtpl->assign('LIST', $list);
    $xtpl->parse('main.loop');
}

$xtpl->parse('main');

$contents = $xtpl->text('main');
$page_title = $lang_module['main_caption'];

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
