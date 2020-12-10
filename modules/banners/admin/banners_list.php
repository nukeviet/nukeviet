<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC <contact@vinades.vn>
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 3/12/2010 12:34
 */

if (!defined('NV_IS_FILE_ADMIN')) {
    die('Stop!!!');
}

$page_title = $lang_module['banners_list'];

$sql = "SELECT id,title,blang FROM " . NV_BANNERS_GLOBALTABLE . "_plans ORDER BY blang, title ASC";
$result = $db->query($sql);

$contents = array();
$contents['searchform'] = true;
$contents['plans'] = array();
$contents['keyword'] = $nv_Request->get_title('q', 'get', '');
$contents['pid'] = $nv_Request->get_int('pid', 'get', 0);
$contents['clid'] = $nv_Request->get_int('clid', 'get', 0);

$plans = array();
while ($row = $result->fetch()) {
    $contents['plans'][] = $row;
    $plans[$row['id']] = $row['title'] . " (" . (!empty($row['blang']) ? $language_array[$row['blang']]['name'] : $lang_module['blang_all']) . ")";
}

$sql = "SELECT * FROM " . NV_BANNERS_GLOBALTABLE . "_rows WHERE ";
if (in_array($nv_Request->get_int('act', 'get', 1), array(0, 2, 3, 4))) {
    $sql .= "act=" . $nv_Request->get_int('act', 'get');
    $contents['caption'] = $lang_module['banners_list' . $nv_Request->get_int('act', 'get')];
} else {
    $sql .= "act=1";
    $contents['caption'] = $lang_module['banners_list1'];
}

if (isset($clients[$contents['clid']])) {
    $sql .= " AND clid=" . $contents['clid'];
    $contents['caption'] .= " " . sprintf($lang_module['banners_list_cl'], $clients[$contents['clid']]);
} elseif (isset($plans[$contents['pid']])) {
    $sql .= " AND pid=" . $contents['pid'];
    $contents['caption'] .= " " . sprintf($lang_module['banners_list_pl'], $plans[$contents['pid']]);
}
if (!empty($contents['keyword'])) {
    $keyword = $db->dblikeescape($contents['keyword']);
    $sql .= " AND (title LIKE '%" . $keyword . "%' OR file_alt LIKE '%" . $keyword . "%' OR click_url LIKE '%" . $keyword . "%' OR bannerhtml LIKE '%" . $keyword . "%')";
}

$sql .= " ORDER BY id DESC";

$result = $db->query($sql);

$contents['thead'] = array(
    $lang_module['title'],
    $lang_module['in_plan'],
    $lang_module['of_user'],
    $lang_module['publ_date'],
    $lang_module['exp_date'],
    $lang_module['is_act'],
    $lang_global['actions']
);
$contents['view'] = $lang_global['detail'];
$contents['edit'] = $lang_global['edit'];
$contents['del'] = $lang_global['delete'];
$contents['rows'] = array();

$array_userids = $array_users = array();

while ($row = $result->fetch()) {
    if ($row['exp_time'] != 0 and $row['exp_time'] <= NV_CURRENTTIME) {
        $db->exec('UPDATE ' . NV_BANNERS_GLOBALTABLE . '_rows SET act=0 WHERE id=' . $row['id']);
        $row['act'] = 0;
    }
    $contents['rows'][$row['id']]['title'] = $row['title'];
    $contents['rows'][$row['id']]['pid'] = array(NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=info_plan&amp;id=" . $row['pid'], $plans[$row['pid']]);
    $contents['rows'][$row['id']]['clid'] = $row['clid'];
    $contents['rows'][$row['id']]['publ_date'] = date("d/m/Y", $row['publ_time']);
    $contents['rows'][$row['id']]['exp_date'] = !empty($row['exp_time']) ? date("d/m/Y", $row['exp_time']) : $lang_module['unlimited'];
    $contents['rows'][$row['id']]['act'] = array(
        'act_' . $row['id'],
        $row['act'],
        "nv_b_chang_act(" . $row['id'] . ",'act_" . $row['id'] . "');"
    );
    $contents['rows'][$row['id']]['view'] = NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=info_banner&amp;id=" . $row['id'];
    $contents['rows'][$row['id']]['edit'] = NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=edit_banner&amp;id=" . $row['id'];
    $contents['rows'][$row['id']]['del'] = "nv_b_del(" . $row['id'] . ");";

    if (!empty($row['clid'])) {
        $array_userids[$row['clid']] = $row['clid'];
    }
}

// Xác định người đăng
if (!empty($array_userids)) {
    $sql = 'SELECT userid, username, md5username FROM ' . NV_USERS_GLOBALTABLE . ' WHERE userid IN(' . implode(',', $array_userids) . ')';
    $result = $db->query($sql);
    while ($row = $result->fetch()) {

        $array_users[$row['userid']] = $row;
    }
}

$content = call_user_func("nv_b_list_theme", $contents, $array_users);

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($content);
include NV_ROOTDIR . '/includes/footer.php';
