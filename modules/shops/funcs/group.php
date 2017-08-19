<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2017 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 04/18/2017 09:47
 */

if (! defined('NV_IS_MOD_SHOPS')) {
    die('Stop!!!');
}

if (empty($groupid)) {
    Header('Location: ' . nv_url_rewrite(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name, true));
    exit();
}

$page_title = $lang_module['group_title'];
if (preg_match('/^page\-([0-9]+)$/', (isset($array_op[2]) ? $array_op[2] : ''), $m)) {
    $page = ( int )$m[1];
}

$page_title = $global_array_group[$groupid]['title'];
$key_words = $global_array_group[$groupid]['keywords'];
$description = $global_array_group[$groupid]['description'];
$data_content = array();
$chirld_groupid = GetGroupidInParent($groupid, 1);

$nv_Request->get_int('sorts', 'session', 0);
$sorts = $nv_Request->get_int('sort', 'post', 0);
$sorts_old = $nv_Request->get_int('sorts', 'session', $pro_config['sortdefault']);
$sorts = $nv_Request->get_int('sorts', 'post', $sorts_old);

$nv_Request->get_string('viewtype', 'session', '');
$viewtype = $nv_Request->get_string('viewtype', 'post', '');
$viewtype_old = $nv_Request->get_string('viewtype', 'session', '');
$viewtype = $nv_Request->get_string('viewtype', 'post', $viewtype_old);
if (!empty($viewtype)) {
    $global_array_group[$groupid]['viewgroup'] = $viewtype;
}

$compare_id = $nv_Request->get_string($module_data . '_compare_id', 'session', '');
$compare_id = unserialize($compare_id);

$link = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=';
$base_url = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=group/' . $global_array_group[$groupid]['alias'];

$array_pro_id = array();
$_sql = 'SELECT pro_id FROM ' . $db_config['prefix'] . '_' . $module_data . '_group_items WHERE group_id IN (' . implode(',', $chirld_groupid) . ')';
$_query = $db->query($_sql);
while (list($pro_id) = $_query->fetch(3)) {
    $array_pro_id[] = $pro_id;
}
$array_pro_id = array_unique($array_pro_id);
$array_pro_id = !empty($array_pro_id) ? implode(',', $array_pro_id) : 0;

// Fetch Limit
$db->sqlreset()
    ->select('COUNT(*)')
    ->from($db_config['prefix'] . '_' . $module_data . '_rows')
    ->where('status=1 AND id IN ( ' . $array_pro_id . ' )');

$num_items = $db->query($db->sql())->fetchColumn();

$db->select('id, listcatid, publtime, ' . NV_LANG_DATA . '_title, ' . NV_LANG_DATA . '_alias, ' . NV_LANG_DATA . '_hometext, homeimgalt, homeimgfile, homeimgthumb, product_code, product_number, product_price, money_unit, discount_id, showprice, ' . NV_LANG_DATA . '_gift_content, gift_from, gift_to')
    ->order('id DESC')
    ->limit($per_page)
    ->offset(($page - 1) * $per_page);

$result = $db->query($db->sql());

$data_content = GetDataInGroup($result, $groupid);
$data_content['count'] = $num_items;

if (sizeof($data_content['data']) < 1 and $page > 1) {
    Header('Location: ' . nv_url_rewrite(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name, true));
    exit();
}

$pages = nv_alias_page($page_title, $base_url, $num_items, $per_page, $page);

if ($page > 1) {
    $page_title .= ' ' . NV_TITLEBAR_DEFIS . ' ' . $lang_global['page'] . ' ' . $page;
    $description .= ' ' . $page;
}

$contents = call_user_func($global_array_group[$groupid]['viewgroup'], $data_content, $compare_id, $pages, $sorts, $viewtype);

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
