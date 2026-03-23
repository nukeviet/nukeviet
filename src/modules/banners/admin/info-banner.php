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

$sql = 'SELECT * FROM ' . NV_BANNERS_GLOBALTABLE . '_rows WHERE id=' . $id;
$row = $db->query($sql)->fetch();

if (empty($row)) {
    nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name);
}

[$ptitle_raw, $blang] = $db->query('SELECT title, blang FROM ' . NV_BANNERS_GLOBALTABLE . '_plans WHERE id=' . $row['pid'])->fetch(3);
$blang_name = !empty($blang) ? $language_array[$blang]['name'] : $nv_Lang->getModule('blang_all');

$img_info = '';
if ($row['file_ext'] != 'no_image') {
    $img_info = $nv_Lang->getModule('img_info2', $row['file_ext'], $row['file_mime'], $row['width'], $row['height']);
}

$cl_user = [];
if (!empty($row['clid'])) {
    $user = $db->query('SELECT userid, username, md5username FROM ' . NV_USERS_GLOBALTABLE . ' WHERE userid=' . $row['clid'])->fetch();
    if (!empty($user)) {
        $cl_user = [
            'username' => $user['username'],
            'link' => nv_user_in_groups($global_config['whoviewuser']) ? NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=users&' . NV_OP_VARIABLE . '=memberlist/' . change_alias($user['username']) . '-' . $user['md5username'] : ''
        ];
    }
}

$current_month = (int) date('n');
$current_year = (int) date('Y');
$publ_month = (int) date('n', $row['publ_time']);
$publ_year = (int) date('Y', $row['publ_time']);
$bymonth = [];
for ($i = $current_month; $i > 0; --$i) {
    if ($i < $publ_month && $current_year == $publ_year) {
        break;
    }
    $bymonth[$i] = nv_monthname($i) . ' ' . date('Y');
}

$exts = [
    'day' => $nv_Lang->getModule('exts_day'),
    'country' => $nv_Lang->getModule('exts_country'),
    'browse' => $nv_Lang->getModule('exts_browse'),
    'os' => $nv_Lang->getModule('exts_os'),
];

$tpl = new \NukeViet\Template\NVSmarty();
$tpl->setTemplateDir(get_module_tpl_dir(basename(__FILE__, '.php') . '.tpl'));
$tpl->registerPlugin('modifier', 'ddatetime', 'nv_datetime_format');

$tpl->assign('LANG', $nv_Lang);
$tpl->assign('MODULE_NAME', $module_name);
$tpl->assign('OP', $op);
$tpl->assign('CHECKSS', csrf_create($csrf_key));
$tpl->assign('BANNER_ID', $id);
$tpl->assign('ROW', $row);
$tpl->assign('PLAN', ['id' => $row['pid'], 'title' => $ptitle_raw, 'blang_name' => $blang_name]);
$tpl->assign('CL_USER', $cl_user);
$tpl->assign('IMG_INFO', $img_info);
$tpl->assign('TARGETS', $targets);
$tpl->assign('BYMONTH', $bymonth);
$tpl->assign('EXTS', $exts);

$contents = $tpl->fetch(basename(__FILE__, '.php') . '.tpl');

$page_title = $nv_Lang->getModule('info_banner_title');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
