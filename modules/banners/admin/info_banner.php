<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC <contact@vinades.vn>
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 3/15/2010 16:10
 */

if (!defined('NV_IS_FILE_ADMIN')) {
    die('Stop!!!');
}

$id = $nv_Request->get_int('id', 'get', 0);

$sql = 'SELECT * FROM ' . NV_BANNERS_GLOBALTABLE . '_rows WHERE id=' . $id;
$row = $db->query($sql)->fetch();

if (empty($row)) {
    nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name);
}

list($ptitle, $plang) = $db->query('SELECT title, blang FROM ' . NV_BANNERS_GLOBALTABLE . '_plans WHERE id=' . $row['pid'])->fetch(3);

$ptitle = $ptitle . ' (' . (!empty($plang) ? $language_array[$plang]['name'] : $lang_module['blang_all']) . ')';
$ptitle = '<a href="' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=info_plan&amp;id=' . $row['pid'] . '">' . $ptitle . '</a>';

if ($row['file_ext'] != 'no_image') {
    $img_info = sprintf($lang_module['img_info2'], $row['file_ext'], $row['file_mime'], $row['width'], $row['height']);
} else {
    $img_info = '';
}
$click_url = $row['click_url'];

if (!empty($click_url)) {
    $click_url = '<a href="' . $click_url . '" target="_blank">' . $click_url . '</a>';
}

$contents = array();
$contents['caption'] = sprintf($lang_module['info_banner_caption'], $row['title']);
$contents['edit'] = array(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=edit_banner&amp;id=' . $id, $lang_global['edit']);
$contents['del'] = array('nv_b_del2(' . $id . ');', $lang_global['delete']);

if ($row['act'] != '2') {
    $contents['act'] = array('nv_b_chang_act2(' . $id . ');', $lang_module['change_act']);
}

$contents['rows'][] = array('id', $row['id']);
$contents['rows'][] = array($lang_module['title'], $row['title']);
$contents['rows'][] = array($lang_module['in_plan'], $ptitle);

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
$contents['rows'][] = array($lang_module['of_user'], $cl_full_name);

$contents['rows'][] = array($lang_module['file_name'], '<a href="javascript:void(0)" data-src="' . NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . NV_BANNER_DIR . '/' . $row['file_name'] . '" id="' . ($row['file_ext'] == 'swf' ? 'open_modal_flash' : 'open_modal_image') . '" data-width="' . $row['width'] . '">' . $lang_module['click_show_img'] . '</a>');

if (!empty($row['imageforswf'])) {
    $contents['rows'][] = array($lang_module['imageforswf'], '<a href="javascript:void(0)" data-src="' . NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . NV_BANNER_DIR . '/' . $row['imageforswf'] . '" id="open_modal_image" data-width="' . $row['width'] . '">' . $lang_module['click_show_img'] . '</a>');
}

$contents['rows'][] = array($lang_module['img_info1'], $img_info);
$contents['rows'][] = array($lang_module['file_alt'], $row['file_alt']);
$contents['rows'][] = array($lang_module['click_url'], $click_url);
$contents['rows'][] = array($lang_module['target'], $targets[$row['target']]);

$contents['rows'][] = array($lang_module['add_date'], date('d/m/Y H:i', $row['add_time']));
$contents['rows'][] = array($lang_module['publ_date'], date('d/m/Y H:i', $row['publ_time']));
$contents['rows'][] = array($lang_module['exp_date'], (!empty($row['exp_time']) ? date('d/m/Y H:i', $row['exp_time']) : $lang_module['unlimited']));
$contents['rows'][] = array($lang_global['status'], $lang_module['act' . $row['act']]);
$contents['rows'][] = array($lang_module['hits_total'], $row['hits_total']);

$current_month = date('n');
$current_year = date('Y');
$publ_month = date('n', $row['publ_time']);
$publ_year = date('Y', $row['publ_time']);
$bymonth = array();

for ($i = $current_month; $i > 0; --$i) {
    if ($i < $publ_month and $current_year == $publ_year) {
        break;
    }
    $bymonth[$i] = nv_monthname($i) . ' ' . date('Y');
}

$exts = array();
$exts['day'] = $lang_module['exts_day'];
$exts['country'] = $lang_module['exts_country'];
$exts['browse'] = $lang_module['exts_browse'];
$exts['os'] = $lang_module['exts_os'];

$contents['stat'] = array(
    $lang_module['info_stat_caption'],
    $lang_module['please_select_month'],
    'select_month',
    $bymonth,
    'select_ext',
    $exts,
    $lang_module['select'],
    'submit_stat',
    "nv_show_stat(" . $id . ",'select_month','select_ext', 'submit_stat','statistic');"
);

$contents['containerid'] = 'statistic';

$contents = call_user_func('nv_info_b_theme', $contents);

$page_title = $lang_module['info_banner_title'];

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
