<?php

/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_IS_FILE_ADMIN')) {
    exit('Stop!!!');
}

$id = $nv_Request->get_int('id', 'get', 0);

$sql = 'SELECT * FROM ' . NV_BANNERS_GLOBALTABLE . '_plans WHERE id=' . $id;
$row = $db->query($sql)->fetch();

if (empty($row)) {
    nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name);
}

$row['caption'] = $nv_Lang->getModule('info_plan_caption', $row['title']);
$row['blang_format'] = !empty($row['blang']) ? $language_array[$row['blang']]['name'] : $nv_Lang->getModule('blang_all');
$row['form_format'] = $nv_Lang->existsModule('form_' . $row['form']) ? $nv_Lang->getModule('form_' . $row['form']) : $row['form'];
$row['require_image'] = $nv_Lang->getModule('require_image' . $row['require_image']);
$row['uploadtype'] = str_replace(',', ', ', $row['uploadtype']);
$row['plan_exp_time'] = empty($row['exp_time']) ? $nv_Lang->getModule('plan_exp_time_nolimit') : nv_convertfromSec($row['exp_time']);

$groups_list = nv_groups_list();
$uploadgroup = [];
if (!empty($row['uploadgroup'])) {
    $row['uploadgroup'] = array_map('intval', explode(',', $row['uploadgroup']));
    foreach ($groups_list as $k => $v) {
        if (in_array($k, $row['uploadgroup'], true)) {
            $uploadgroup[] = $v;
        }
    }
}
$row['uploadgroup'] = implode(', ', $uploadgroup);

// Build status cards - link tới trang main với filter pid + act
$act_colors = [0 => 'secondary', 1 => 'success', 2 => 'warning', 3 => 'danger', 4 => 'info'];
$main_url = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=main&amp;pid=' . $id;

$status_cards = [];
foreach ([0, 1, 2, 3, 4] as $s) {
    $count = (int) $db->query('SELECT COUNT(*) FROM ' . NV_BANNERS_GLOBALTABLE . '_rows WHERE pid=' . $id . ' AND act=' . $s)->fetchColumn();
    $status_cards[] = [
        'act'   => $s,
        'count' => $count,
        'title' => $nv_Lang->getModule('banner_act_' . $s),
        'url'   => $main_url . '&amp;act=' . $s,
        'color' => $act_colors[$s],
    ];
}

$tpl = new \NukeViet\Template\NVSmarty();
$tpl->setTemplateDir(get_module_tpl_dir('info-plan.tpl'));

$tpl->assign('LANG', $nv_Lang);
$tpl->assign('MODULE_NAME', $module_name);
$tpl->assign('OP', $op);
$tpl->assign('CHECKSS', csrf_create($csrf_key));
$tpl->assign('ROW', $row);
$tpl->assign('STATUS_CARDS', $status_cards);

$contents = $tpl->fetch('info-plan.tpl');

$page_title = $nv_Lang->getModule('info_plan');
$set_active_op = 'plans_list';

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
