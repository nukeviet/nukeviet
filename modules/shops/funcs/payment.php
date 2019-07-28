<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC <contact@vinades.vn>
 * @Copyright (C) 2017 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 04/18/2017 09:47
 */

if (!defined('NV_IS_MOD_SHOPS')) {
    die('Stop!!!');
}

$page_title = $lang_module['order_view'];

$order_id = $nv_Request->get_int('order_id', 'get', 0);
$checkss = $nv_Request->get_string('checkss', 'get', '');
if ($order_id > 0 and $checkss == md5($order_id . $global_config['sitekey'] . session_id())) {
    $data_pro = array();
    $link = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=';

    // Thong tin don hang
    $result = $db->query('SELECT * FROM ' . $db_config['prefix'] . '_' . $module_data . '_orders WHERE order_id=' . $order_id);
    if ($result->rowCount() == 0) {
        nv_redirect_location(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name);

    }
    $data = $result->fetch();

    // Thong tin van chuyen
    $result = $db->query('SELECT * FROM ' . $db_config['prefix'] . '_' . $module_data . '_orders_shipping WHERE order_id = ' . $data['order_id']);
    $data_shipping = $result->fetch();

    $result = $db->query('SELECT amount FROM ' . $db_config['prefix'] . '_' . $module_data . '_coupons_history WHERE order_id=' . $data['order_id']);
    $data['coupons'] = $result->fetch();

    if (empty($data)) {
        nv_redirect_location(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=cart', true);
    }

    // Sua don hang
    if ($nv_Request->isset_request('edit', 'get')) {
        if ($data['transaction_status'] != 4) {
            $_SESSION[$module_data . '_order_info'] = array(
                'order_id' => $data['order_id'],
                'order_code' => $data['order_code'],
                'money_unit' => $data['unit_total'],
                'order_name' => $data['order_name'],
                'order_email' => $data['order_email'],
                'order_address' => $data['order_address'],
                'order_phone' => $data['order_phone'],
                'order_note' => $data['order_note'],
                'unit_total' => $data['unit_total'],
                'order_url' => $link . 'payment&amp;order_id=' . $data['order_id'] . '&amp;checkss=' . $checkss,
                'order_edit' => $link . 'payment&amp;unedit&amp;order_id=' . $data['order_id'] . '&amp;checkss=' . $checkss,
                'checked' => 1
            );
        }
        nv_redirect_location(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=cart', true);
    }

    // Huy sua don hang
    if ($nv_Request->isset_request('unedit', 'get')) {
        if (isset($_SESSION[$module_data . '_cart'])) {
            unset($_SESSION[$module_data . '_cart']);
        }

        if (isset($_SESSION[$module_data . '_order_info'])) {
            unset($_SESSION[$module_data . '_order_info']);
        }
        nv_redirect_location(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=payment&order_id=' . $data['order_id'] . '&checkss=' . $checkss);
    }

    if (!empty($_SESSION[$module_data . '_order_info'])) {
        $lang_module['order_edit'] = $lang_module['order_unedit'];
    }

    // Thong tin chi tiet mat hang trong don hang
    $listid = $listnum = $listprice = $listgroup = $slistgroup = array();
    $result = $db->query('SELECT * FROM ' . $db_config['prefix'] . '_' . $module_data . '_orders_id WHERE order_id=' . $order_id);
    while ($row = $result->fetch()) {
        $listid[] = $row['proid'];
        $listnum[] = $row['num'];
        $listprice[] = $row['price'];

        $result_group = $db->query('SELECT group_id FROM ' . $db_config['prefix'] . '_' . $module_data . '_orders_id_group WHERE order_i=' . $row['id']);
        $group = array();
        while (list($group_id) = $result_group->fetch(3)) {
            $group[] = $group_id;
        }
        $listgroup[] = $group;
        $slistgroup[] = implode(",", $group);
    }
    $i = 0;
    foreach ($listid as $proid) {
        if (empty($listprice[$i])) {
            $listprice[$i] = 0;
        }
        if (empty($listnum[$i])) {
            $listnum[$i] = 0;
        }
        if (!isset($listgroup[$i])) {
            $listgroup[$i] = '';
        }

        $temppro[$proid] = array(
            'price' => $listprice[$i],
            'num' => $listnum[$i],
            'group' => $listgroup[$i]);

        $arrayid[] = $proid;
        $i++;
    }


    if (!empty($arrayid)) {
        $templistid = implode(',', $arrayid);

        foreach ($slistgroup as $list) {
            $product_group = array();
            if (!empty($list))
                $product_group = explode(',', $list);
            $sql = 'SELECT t1.id, t1.listcatid, t1.publtime, t1.' . NV_LANG_DATA . '_title, t1.' . NV_LANG_DATA . '_alias, t1.' . NV_LANG_DATA . '_hometext, t2.' . NV_LANG_DATA . '_title, t1.money_unit FROM ' . $db_config['prefix'] . '_' . $module_data . '_rows AS t1, ' . $db_config['prefix'] . '_' . $module_data . '_units AS t2, ' . $db_config['prefix'] . '_' . $module_data . '_orders_id AS t3  WHERE t1.product_unit = t2.id AND t1.id = t3.proid AND t1.id IN (' . $templistid . ') AND listgroupid=' . $db->quote($list) . ' AND t3.order_id=' . $order_id . ' AND t1.status =1';
            $result = $db->query($sql);
            while (list($id, $listcatid, $publtime, $title, $alias, $hometext, $unit, $money_unit) = $result->fetch(3)) {
                $price = nv_get_price($id, $pro_config['money_unit'], $temppro[$id]['num'], true);
                $data_pro[] = array(
                    'id' => $id,
                    'publtime' => $publtime,
                    'title' => $title,
                    'alias' => $alias,
                    'hometext' => $hometext,
                    'product_price' => $price['sale'],
                    'product_unit' => $unit,
                    'money_unit' => $money_unit,
                    'product_group' => $product_group,
                    'link_pro' => $link . $global_array_shops_cat[$listcatid]['alias'] . '/' . $alias . $global_config['rewrite_exturl'],
                    'product_number' => $temppro[$id]['num']
                );
            }
        }

    }

    // Kiểm tra hỗ trợ thanh toán trực tuyến
    $payment_supported = '';
    $intro_pay = '';
    if (isset($site_mods['wallet']) and file_exists(NV_ROOTDIR . '/modules/wallet/wallet.class.php')) {
        $payment_supported = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;order_id=' . $order_id . '&amp;payment=1&amp;checksum=' . md5($order_id . $global_config['sitekey']);
    }

    if (intval($data['transaction_status']) == -1) {
        $intro_pay = $lang_module['payment_none_pay'];
    } elseif ($data['transaction_status'] == 0) {
        // Get content intro
        $content_file = NV_ROOTDIR . '/' . NV_DATADIR . '/' . NV_LANG_DATA . '_' . $module_data . '_docpay_content.txt';
        if (file_exists($content_file)) {
            $intro_pay = file_get_contents($content_file);
            $intro_pay = nv_editor_br2nl($intro_pay);
        }
    } elseif ($data['transaction_status'] == 1 and $data['transaction_id'] > 0) {
        if ($nv_Request->isset_request('cancel', 'get')) {
            // Khi chọn hình thức thanh toán khác thì vẫn giữ lại giao dịch trước để kiểm tra, không xóa
            //$db->query('DELETE FROM ' . $db_config['prefix'] . '_' . $module_data . '_transaction WHERE transaction_id = ' . $data['transaction_id']);

            // Cập nhật lại đơn hàng là chưa thanh toán để chọn hình thức khác
            $db->query('UPDATE ' . $db_config['prefix'] . '_' . $module_data . '_orders SET transaction_status=0, transaction_id = 0, transaction_count = transaction_count + 1 WHERE order_id=' . $order_id);
            nv_redirect_location(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=payment&order_id=' . $order_id . '&checkss=' . $checkss);
        }

        $intro_pay = sprintf($lang_module['order_by_payment']);
    }
    if ($data['transaction_status'] == 4) {
        $data['transaction_name'] = $lang_module['history_payment_yes'];
    } elseif ($data['transaction_status'] == 3) {
        $data['transaction_name'] = $lang_module['history_payment_cancel'];
    } elseif ($data['transaction_status'] == 2) {
        $data['transaction_name'] = $lang_module['history_payment_check'];
    } elseif ($data['transaction_status'] == 1) {
        $data['transaction_name'] = $lang_module['history_payment_send'];
    } elseif ($data['transaction_status'] == 0) {
        $data['transaction_name'] = $lang_module['history_payment_no'];
    } elseif ($data['transaction_status'] == -1) {
        $data['transaction_name'] = $lang_module['history_payment_wait'];
    } else {
        $data['transaction_name'] = 'ERROR';
    }

    // Lay so diem tich luy cua khach
    $point = 0;
    if (!empty($user_info)) {
        $result = $db->query('SELECT point_total FROM ' . $db_config['prefix'] . '_' . $module_data . '_point WHERE userid = ' . $user_info['userid']);
        if ($result) {
            $point = $result->fetchColumn();
        }
    }

    $contents = call_user_func('payment', $data, $data_pro, $data_shipping, $payment_supported, $intro_pay, $point);

    include NV_ROOTDIR . '/includes/header.php';
    echo nv_site_theme($contents);
    include NV_ROOTDIR . '/includes/footer.php';
} elseif ($order_id > 0 and $nv_Request->isset_request('payment', 'get') and $nv_Request->isset_request('checksum', 'get')) {
    $checksum = $nv_Request->get_string('checksum', 'get');

    // Thong tin don hang
    $result = $db->query('SELECT * FROM ' . $db_config['prefix'] . '_' . $module_data . '_orders WHERE order_id=' . $order_id);
    $data = $result->fetch();

    if (empty($data)) {
        nv_redirect_location(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=cart', true);
    }

    // Thong tin chi tiet mat hang trong don hang
    $listid = $listnum = $listprice = $listgroup = array();
    $result = $db->query('SELECT * FROM ' . $db_config['prefix'] . '_' . $module_data . '_orders_id WHERE order_id=' . $order_id);
    while ($row = $result->fetch()) {
        $listid[] = $row['proid'];
        $listnum[] = $row['num'];
        $listprice[] = $row['price'];

        $result_group = $db->query('SELECT group_id FROM ' . $db_config['prefix'] . '_' . $module_data . '_orders_id_group WHERE order_i=' . $row['id']);
        $group = array();
        while (list($group_id) = $result_group->fetch(3)) {
            $group[] = $group_id;
        }
        $listgroup[] = $group;
    }

    // Lấy dữ liệu trả về
    if ($nv_Request->isset_request('worderid,wchecksum', 'get') and intval($data['transaction_status']) == 1 and isset($site_mods['wallet']) and file_exists(NV_ROOTDIR . '/modules/wallet/wallet.class.php')) {
        require_once NV_ROOTDIR . '/modules/wallet/wallet.class.php';
        $wallet = new nukeviet_wallet();

        $worderid = $nv_Request->get_title('worderid', 'get', '');
        $wchecksum = $nv_Request->get_title('wchecksum', 'get', '');
        $link_back = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op . '&order_id=' . $order_id . '&checkss=' . md5($order_id . $global_config['sitekey'] . session_id());

        // Lấy ra giao dịch
        $transaction = $db->query("SELECT * FROM " . $db_config['prefix'] . "_" . $module_data . "_transaction WHERE transaction_id=" . $worderid)->fetch();

        // Giao dịch không khớp với
        if ($transaction['order_id'] != $order_id) {
            redict_link('Data error!!!!', $lang_module['cart_back'], $link_back);
        }

        $paid = $wallet->getOrderPaid($module_name, $worderid, $wchecksum);
        if (empty($paid)) {
            nv_redirect_location($link_back);
        }

        // Chuẩn hóa trạng thái giao dịch theo phong cách của shops
        $nv_transaction_status = 1; // Đang thực hiện giao dịch
        if ($paid[0] == 0) {
            $nv_transaction_status = 1;
        } elseif ($paid[0] == 1) {
            $nv_transaction_status = 1;
        } elseif ($paid[0] == 2) {
            $nv_transaction_status = 2;
        } elseif ($paid[0] == 3) {
            $nv_transaction_status = 3;
        } elseif ($paid[0] == 4) {
            $nv_transaction_status = 4;
        }

        // Cập nhật giao dịch
        $check = $db->exec("UPDATE " . $db_config['prefix'] . "_" . $module_data . "_transaction SET
            transaction_status=" . $nv_transaction_status . ",
            payment_time=" . $paid[1] . "
        WHERE transaction_id=" . $worderid);
        if (!$check) {
            redict_link($lang_module['payment_error_update'], $lang_module['cart_back'], $link_back);
        }

        // Cập nhật đơn hàng
        $check = $db->exec("UPDATE " . $db_config['prefix'] . "_" . $module_data . "_orders SET
            transaction_status=" . $nv_transaction_status . ",
            transaction_id=" . $worderid . "
        WHERE order_id=" . $transaction['order_id']);
        if (!$check) {
            redict_link($lang_module['payment_error_update'], $lang_module['cart_back'], $link_back);
        }

        if ($nv_transaction_status == 4) {
            // Cập nhật điểm tích lũy
            UpdatePoint($data);

            $nv_redirect = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=history";
            $contents = redict_link($lang_module['payment_complete'], $lang_module['back_history'], $nv_redirect);

            $message = $lang_module['payment_complete'];
        } else {
            $message = $lang_module['payment_notcomplete'];
        }

        $nv_Cache->delMod($module_name);
        redict_link($message, $lang_module['cart_back'], $link_back);
    }

    $url = '';
    if (isset($data['transaction_status']) and intval($data['transaction_status']) == 0 and $checksum == md5($order_id . $global_config['sitekey']) and isset($site_mods['wallet']) and file_exists(NV_ROOTDIR . '/modules/wallet/wallet.class.php')) {
        require_once NV_ROOTDIR . '/modules/wallet/wallet.class.php';
        $wallet = new nukeviet_wallet();

        // Cập nhật lại đơn hàng, khởi tạo giao dịch mới
        $transaction_status = 1;
        $payment_id = 0;
        $payment_amount = 0;
        $payment_data = '';

        $userid = (defined('NV_IS_USER')) ? $user_info['userid'] : 0;

        $transaction_id = $db->insert_id("INSERT INTO " . $db_config['prefix'] . "_" . $module_data . "_transaction (
            transaction_id, transaction_time, transaction_status, order_id, userid, payment, payment_id, payment_time, payment_amount, payment_data
        ) VALUES (
            NULL, " . NV_CURRENTTIME . ", '" . $transaction_status . "', '" . $order_id . "', '" . $userid . "',
            'wallet', '" . $payment_id . "', " . NV_CURRENTTIME . ", '" . $payment_amount . "', '" . $payment_data . "'
        )");

        $db->query('UPDATE ' . $db_config['prefix'] . '_' . $module_data . '_orders SET
            transaction_status=' . $transaction_status . ' ,
            transaction_id = ' . $transaction_id . ' ,
            transaction_count = 1
        WHERE order_id=' . $order_id);

        $url_back = array(
            'op' => $op,
            'querystr' => 'order_id=' . $order_id . '&payment=1&wpreturn=1&checksum=' . md5($order_id . $global_config['sitekey'])
        );
        $url_admin = array(
            'op' => 'order',
            'querystr' => 'order_id=' . $order_id . '&updateorder=1&checksum=' . md5($order_id . $global_config['sitekey'])
        );

        $data = array(
            'modname' => $module_name, // Module thanh toán
            'id' => $transaction_id, // ID đơn hàng
            'order_object' => $lang_module['cart_title'], // Loại đối tượng được mua ví dụ: Ứng dụng, sản phẩm, giỏ hàng...
            'order_name' => $data['order_code'], //
            'money_amount' => $data['order_total'],
            'money_unit' => $data['unit_total'],
            'url_back' => $url_back,
            'url_admin' => $url_admin

        );
        $payment_info = $wallet->getInfoPayment($data);

        if ($payment_info['status'] !== 'SUCCESS') {
            $link_back = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op . '&order_id=' . $order_id . '&checkss=' . md5($order_id . $global_config['sitekey'] . session_id());
            redict_link($payment_info['message'], $lang_module['cart_back'], nv_url_rewrite($link_back));
        }

        $url = $payment_info['url'];
    } elseif ($result->rowCount() > 0) {
        $url = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op . '&order_id=' . $order_id . '&checkss=' . md5($order_id . $global_config['sitekey'] . session_id());
    } else {
        $url = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA;
    }
    nv_redirect_location($url);
} else {
    nv_redirect_location(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA);
}
