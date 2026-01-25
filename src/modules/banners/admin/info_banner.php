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

[$ptitle, $plang] = $db->query('SELECT title, blang FROM ' . NV_BANNERS_GLOBALTABLE . '_plans WHERE id=' . $row['pid'])->fetch(3);

$ptitle = $ptitle . ' (' . (!empty($plang) ? $language_array[$plang]['name'] : $nv_Lang->getModule('blang_all')) . ')';
$ptitle = '<a href="' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=info_plan&amp;id=' . $row['pid'] . '">' . $ptitle . '</a>';

if ($row['file_ext'] != 'no_image') {
    $img_info = $nv_Lang->getModule('img_info2', $row['file_ext'], $row['file_mime'], $row['width'], $row['height']);
} else {
    $img_info = '';
}
$click_url = $row['click_url'];

if (!empty($click_url)) {
    $click_url = '<a href="' . $click_url . '" target="_blank">' . $click_url . '</a>';
}

$contents = [];
$contents['caption'] = $nv_Lang->getModule('info_banner_caption', $row['title']);
$contents['edit'] = [NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=edit_banner&amp;id=' . $id, $nv_Lang->getGlobal('edit')];
$contents['del'] = ['nv_b_del2(' . $id . ');', $nv_Lang->getGlobal('delete')];

if ($row['act'] != '2') {
    $contents['act'] = ['nv_b_chang_act2(' . $id . ');', $nv_Lang->getModule('change_act')];
}

$contents['rows'][] = ['id', $row['id']];
$contents['rows'][] = [$nv_Lang->getModule('title'), $row['title']];
$contents['rows'][] = [$nv_Lang->getModule('in_plan'), $ptitle];

$cl_full_name = '';
if (!empty($row['clid'])) {
    $user = $db->query('SELECT userid, username, md5username FROM ' . NV_USERS_GLOBALTABLE . ' WHERE userid=' . $row['clid'])->fetch();
    if (!empty($user)) {
        $cl_full_name = $user['username'];
        if (nv_user_in_groups($global_config['whoviewuser'])) {
            $cl_full_name = '<a href="' . NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=users&' . NV_OP_VARIABLE . '=memberlist/' . change_alias($user['username']) . '-' . $user['md5username'] . '">' . $cl_full_name . '</a>';
        }
    }
}
$contents['rows'][] = [$nv_Lang->getModule('of_user'), $cl_full_name];
$contents['rows'][] = [$nv_Lang->getModule('file_name'), '<a href="javascript:void(0)" data-src="' . NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . NV_BANNER_DIR . '/' . $row['file_name'] . '" class="open_modal_image">' . $nv_Lang->getModule('click_show_img') . '</a>'];
$contents['rows'][] = [$nv_Lang->getModule('img_info1'), $img_info];
if (!empty($row['imageforswf'])) {
    $contents['rows'][] = [$nv_Lang->getModule('imageforswf'), '<a href="javascript:void(0)" data-src="' . NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . NV_BANNER_DIR . '/' . $row['imageforswf'] . '" class="open_modal_image">' . $nv_Lang->getModule('click_show_img') . '</a>'];
}
$contents['rows'][] = [$nv_Lang->getModule('file_alt'), $row['file_alt']];
$contents['rows'][] = [$nv_Lang->getModule('click_url'), $click_url];
$contents['rows'][] = [$nv_Lang->getModule('target'), $targets[$row['target']]];

$contents['rows'][] = [$nv_Lang->getModule('add_date'), date('d/m/Y H:i', $row['add_time'])];
$contents['rows'][] = [$nv_Lang->getModule('publ_date'), date('d/m/Y H:i', $row['publ_time'])];
$contents['rows'][] = [$nv_Lang->getModule('exp_date'), (!empty($row['exp_time']) ? date('d/m/Y H:i', $row['exp_time']) : $nv_Lang->getModule('unlimited'))];
$contents['rows'][] = [$nv_Lang->getGlobal('status'), $nv_Lang->getModule('act' . $row['act'])];
$contents['rows'][] = [$nv_Lang->getModule('hits_total'), $row['hits_total']];

$current_month = date('n');
$current_year = date('Y');
$publ_month = date('n', $row['publ_time']);
$publ_year = date('Y', $row['publ_time']);
$bymonth = [];

for ($i = $current_month; $i > 0; --$i) {
    if ($i < $publ_month and $current_year == $publ_year) {
        break;
    }
    $bymonth[$i] = nv_monthname($i) . ' ' . date('Y');
}

$exts = [];
$exts['day'] = $nv_Lang->getModule('exts_day');
$exts['country'] = $nv_Lang->getModule('exts_country');
$exts['browse'] = $nv_Lang->getModule('exts_browse');
$exts['os'] = $nv_Lang->getModule('exts_os');

$contents['stat'] = [
    $nv_Lang->getModule('info_stat_caption'),
    $nv_Lang->getModule('please_select_month'),
    'select_month',
    $bymonth,
    'select_ext',
    $exts,
    $nv_Lang->getModule('select'),
    'submit_stat',
    'nv_show_stat(' . $id . ",'select_month','select_ext', 'submit_stat','statistic');"
];

$contents['containerid'] = 'statistic';

$contents = call_user_func('nv_info_b_theme', $contents);

$page_title = $nv_Lang->getModule('info_banner_title');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
