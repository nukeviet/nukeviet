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
if (! defined('NV_IS_AJAX')) {
    die('Wrong URL');
}

$order_id = $nv_Request->get_int('order_id', 'get', 0);
$checkss = $nv_Request->get_string('checkss', 'get', '');
if ($order_id > 0 and $checkss == md5($order_id . $global_config['sitekey'] . session_id())) {
    $table_name = $db_config['prefix'] . "_" . $module_data . "_orders";
    $re = $db->query("SELECT * FROM " . $table_name . " WHERE order_id=" . $order_id);
    $data = $re->fetch();
    if (! empty($data)) {
        if ($data['status'] == 0 and $data['status'] == 0) {
            // Xoa don hang
            $db->query("DELETE FROM " . $table_name . " WHERE order_id=" . $order_id);

            // Thong tin dat hang chi tiet
            $list_order_i = $listid = $listnum = $listgroup = array();
            $result = $db->query("SELECT * FROM " . $table_name . "_id WHERE order_id=" . $order_id);
            while ($row = $result->fetch()) {
                $list_order_i[] = $row['id'];
                $listid[] = $row['proid'];
                $listnum[] = $row['num'];

                $list = '';
                $result_group = $db->query('SELECT group_id FROM ' . $table_name . '_id_group WHERE order_i=' . $row['id']);
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

            // Xoa chi tiet don hang
            $db->query("DELETE FROM " . $table_name . "_id WHERE order_id=" . $order_id);

            echo "OK_" . str_replace("_", "#@#", $lang_module['del_history_ok']);
            die();
        } else {
            echo "ERR_" . str_replace("_", "#@#", $lang_module['del_history_error_status']);
            die();
        }
    } else {
        echo "Error";
        die();
    }
}

echo "ERR_Error";
