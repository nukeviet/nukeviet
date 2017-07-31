<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2017 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 04/18/2017 09:47
 */

if (!defined('NV_IS_FILE_ADMIN')) {
    die('Stop!!!');
}

$id = $nv_Request->get_int('id', 'get', 0);
if (empty($id)) {
    Header('Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=coupons');
    die();
}

if ($nv_Request->isset_request('coupons_history', 'post,get')) {
    $page = $nv_Request->get_int('page', 'get', 1);
    $per_page = 20;
    $base_url = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&coupons_history=1&id=' . $id;

    $array_history = array();

    $db->sqlreset()
      ->select('COUNT(*)')
      ->from($db_config['prefix'] . '_' . $module_data . '_coupons_history t1')
      ->join('INNER JOIN ' . $db_config['prefix'] . '_' . $module_data . '_orders t2 ON t1.order_id = t2.order_id')
      ->where('cid=' . $id);

    $all_page = $db->query($db->sql())->fetchColumn();

    $db->select('*')
      ->order('date_added DESC')
      ->limit($per_page)
      ->offset(($page - 1) * $per_page);

    $_query = $db->query($db->sql());
    while ($row = $_query->fetch()) {
        $array_history[] = $row;
    }
    $generate_page = nv_generate_page($base_url, $all_page, $per_page, $page, true, true, 'nv_urldecode_ajax', 'coupons_history');

    $xtpl = new XTemplate($op . '.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
    $xtpl->assign('LANG', $lang_module);
    $xtpl->assign('MONEY_UNIT', $pro_config['money_unit']);

    if (! empty($array_history)) {
        foreach ($array_history as $history) {
            $history['date_added'] = nv_date('H:i d/m/Y', $history['date_added']);
            $history['order_view'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=or_view&order_id=' . $history['order_id'];
            $xtpl->assign('DATA', $history);
            $xtpl->parse('coupons_history.loop');
        }
    }

    if (!empty($generate_page)) {
        $xtpl->assign('NV_GENERATE_PAGE', $generate_page);
        $xtpl->parse('coupons_history.generate_page');
    }

    $xtpl->parse('coupons_history');
    $contents = $xtpl->text('coupons_history');

    include NV_ROOTDIR . '/includes/header.php';
    echo nv_admin_theme($contents, false);
    include NV_ROOTDIR . '/includes/footer.php';
    die();
}

$array_data = array();
$result = $db->query('SELECT * FROM ' . $db_config['prefix'] . '_' . $module_data . '_coupons WHERE id = ' . $id);
$array_data = $result->fetch();

$result = $db->query('SELECT * FROM ' . $db_config['prefix'] . '_' . $module_data . '_coupons_product WHERE cid = ' . $array_data['id']);
$array_data['product'] = $result->fetch();

if (NV_CURRENTTIME >= $array_data['date_start'] and (empty($array_data['uses_per_coupon']) or $array_data['uses_per_coupon_count'] < $array_data['uses_per_coupon']) and (empty($array_data['date_end']) or NV_CURRENTTIME < $array_data['date_end'])) {
    $array_data['status'] = $lang_module['coupons_active'];
} else {
    $array_data['status'] = $lang_module['coupons_inactive'];
}
$array_data['discount_text'] = $array_data['type'] == 'p' ? '%' : ' ' . $pro_config['money_unit'];
$array_data['date_start'] = !empty($array_data['date_start']) ? nv_date('d/m/Y', $array_data['date_start']) : 'N/A';
$array_data['date_end'] = !empty($array_data['date_end']) ? nv_date('d/m/Y', $array_data['date_end']) : $lang_module['coupons_unlimit'];

$xtpl = new XTemplate($op . '.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('DATA', $array_data);
$xtpl->assign('MONEY_UNIT', $pro_config['money_unit']);
$xtpl->assign('CID', $id);

if (!empty($array_data['product'])) {
    $xtpl->parse('main.product_list');
}

$xtpl->parse('main');
$contents = $xtpl->text('main');

$page_title = $array_data['title'];

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
