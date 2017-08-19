<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2017 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 04/18/2017 09:47
 */

if (! defined('NV_IS_FILE_ADMIN')) {
    die('Stop!!!');
}

$order_id = $nv_Request->get_int('order_id', 'post,get', 0);
$checkss = $nv_Request->get_string('checkss', 'post,get', 0);

$contents = "NO_" . $order_id;

if ($order_id > 0 and $checkss == md5($order_id . $global_config['sitekey'] . session_id())) {
    // Thong tin dat hang chi tiet
    $list_order_i = $listid = $listnum = $listgroup = array();
    $result = $db->query("SELECT * FROM " . $db_config['prefix'] . "_" . $module_data . "_orders_id WHERE order_id=" . $order_id);
    while ($row = $result->fetch()) {
        $list_order_i[] = $row['id'];
        $listid[] = $row['proid'];
        $listnum[] = $row['num'];

        $list = '';
        $result_group = $db->query('SELECT group_id FROM ' . $db_config['prefix'] . '_' . $module_data . '_orders_id_group WHERE order_i=' . $row['id']);
        $group = array();
        while (list($group_id) = $result_group->fetch(3)) {
            $group[] = $group_id;
        }
        asort($group);
        $listgroup[] = implode(',', $group);
    }

    // Cong lai san pham trong kho
    if ($pro_config['active_order_number'] == '0') {
        product_number_order($listid, $listnum, $listgroup, "+");
    }

    // Tru lai so san pham da ban
    product_number_sell($listid, $listnum, "-");

    // Cap nhat lich su su dung ma giam gia
    $num = $db->query('SELECT COUNT(*) FROM ' . $db_config['prefix'] . '_' . $module_data . '_coupons_history WHERE order_id = ' . $order_id)->fetchColumn();
    if ($num > 0) {
        $db->query('UPDATE ' . $db_config['prefix'] . '_' . $module_data . '_coupons SET uses_per_coupon_count = uses_per_coupon_count - 1 WHERE id = ' . $array_coupons['cid']);
        $exec = $db->exec("DELETE FROM " . $db_config['prefix'] . "_" . $module_data . "_coupons_history WHERE order_id=" . $order_id);
    }

    if (!empty($list_order_i)) {
        foreach ($list_order_i as $order_i) {
            $db->query("DELETE FROM " . $db_config['prefix'] . "_" . $module_data . "_orders_id_group WHERE order_i=" . $order_i);
        }
    }
    $exec = $db->exec("DELETE FROM " . $db_config['prefix'] . "_" . $module_data . "_orders_id WHERE order_id=" . $order_id);
    if ($exec) {
        $exec = $db->exec("DELETE FROM " . $db_config['prefix'] . "_" . $module_data . "_orders WHERE order_id=" . $order_id . " AND transaction_status < 1");
        if ($exec) {
            $db->query("DELETE FROM " . $db_config['prefix'] . "_" . $module_data . "_transaction WHERE order_id=" . $order_id);
            $contents = "OK_" . $order_id;
        }
    }
} elseif ($nv_Request->isset_request('listall', 'post,get')) {
    $listall = $nv_Request->get_string('listall', 'post,get');
    $array_id = explode(',', $listall);

    foreach ($array_id as $order_i) {
        $arr_order_i = explode("_", $order_i);
        $order_id = intval($arr_order_i[0]);
        $checkss = trim($arr_order_i[1]);

        if ($order_id > 0 and $checkss == md5($order_id . $global_config['sitekey'] . session_id())) {
            $result = $db->query("SELECT * FROM " . $db_config['prefix'] . "_" . $module_data . "_orders WHERE order_id=" . $order_id);
            $data_order = $result->fetch();
            $result->closeCursor();

            // Thong tin dat hang chi tiet
            $list_order_i = $listid = $listnum = $listgroup = array();
            $result = $db->query("SELECT * FROM " . $db_config['prefix'] . "_" . $module_data . "_orders_id WHERE order_id=" . $order_id);
            while ($row = $result->fetch()) {
                $list_order_i[] = $row['id'];
                $listid[] = $row['proid'];
                $listnum[] = $row['num'];

                $list = '';
                $result_group = $db->query('SELECT group_id FROM ' . $db_config['prefix'] . '_' . $module_data . '_orders_id_group WHERE order_i=' . $row['id']);
                $group = array();
                while (list($group_id) = $result_group->fetch(3)) {
                    $group[] = $group_id;
                }
                asort($group);
                $listgroup[] = implode(',', $group);
            }

            // Cong lai san pham trong kho
            if ($pro_config['active_order_number'] == '0') {
                product_number_order($listid, $listnum, $listgroup, "+");
            }

            // Tru lai so san pham da ban
            product_number_sell($listid, $listnum, "-");

            // Cap nhat lich su su dung ma giam gia
            $num = $db->query('SELECT COUNT(*) FROM ' . $db_config['prefix'] . '_' . $module_data . '_coupons_history WHERE order_id = ' . $order_id)->fetchColumn();
            if ($num > 0) {
                $db->query('UPDATE ' . $db_config['prefix'] . '_' . $module_data . '_coupons SET uses_per_coupon_count = uses_per_coupon_count - 1 WHERE order_id = ' . $order_id);
                $exec = $db->exec("DELETE FROM " . $db_config['prefix'] . "_" . $module_data . "_coupons_history WHERE order_id=" . $order_id);
            }

            if (!empty($list_order_i)) {
                foreach ($list_order_i as $order_i) {
                    $db->query("DELETE FROM " . $db_config['prefix'] . "_" . $module_data . "_orders_id_group WHERE order_i=" . $order_i);
                }
            }
            $exec = $db->exec("DELETE FROM " . $db_config['prefix'] . "_" . $module_data . "_orders_id WHERE order_id=" . $order_id);
            if ($exec) {
                $db->query("DELETE FROM " . $db_config['prefix'] . "_" . $module_data . "_orders_id_group WHERE order_i=" . $order_id);
                $exec = $db->exec("DELETE FROM " . $db_config['prefix'] . "_" . $module_data . "_orders WHERE order_id=" . $order_id . " AND transaction_status < 1");
                if ($exec) {
                    $db->query("DELETE FROM " . $db_config['prefix'] . "_" . $module_data . "_transaction WHERE order_id=" . $order_id);
                }
            }
        }
    }
    $contents = "OK_0";
}

$nv_Cache->delMod($module_name);

include NV_ROOTDIR . '/includes/header.php';
echo $contents;
include NV_ROOTDIR . '/includes/footer.php';