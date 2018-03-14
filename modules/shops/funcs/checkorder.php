<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC <contact@vinades.vn>
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

$checkss = $nv_Request->get_title('checkss', 'post', '');
$order_id = $nv_Request->get_int('id', 'post', 0);

$json = array(
    'status' => 'NOCHANGE',
    'message' => $lang_module['no_update_order'],
    'link' => ''
);

if ($checkss == md5($order_id . $global_config['sitekey'] . session_id())) {
    $sql = "SELECT * FROM " . $db_config['prefix'] . "_" . $module_data . "_orders WHERE order_id=" . $order_id;
    $order_data = $db->query($sql)->fetch();

    if (!empty($order_data)) {
        $sql = "SELECT * FROM " . $db_config['prefix'] . "_" . $module_data . "_transaction WHERE transaction_id=" . $order_data['transaction_id'];
        $transaction_data = $db->query($sql)->fetch();
        if (!empty($transaction_data) and isset($site_mods['wallet']) and file_exists(NV_ROOTDIR . '/modules/wallet/wallet.class.php')) {
            require_once NV_ROOTDIR . '/modules/wallet/wallet.class.php';
            $wallet = new nukeviet_wallet();

            $data = array(
                'modname' => $module_name, // Module thanh toán
                'id' => $order_data['transaction_id'] // ID đơn hàng
            );
            $checkPayment = $wallet->checkInfoPayment($data);
            if ($checkPayment['status'] == 'SUCCESS') {
                // Chuẩn hóa status theo phong cách của shops
                $nv_transaction_status = 1; // Đang thực hiện giao dịch
                if ($checkPayment['data'][0] == 0) {
                    $nv_transaction_status = 1;
                } elseif ($checkPayment['data'][0] == 1) {
                    $nv_transaction_status = 1;
                } elseif ($checkPayment['data'][0] == 2) {
                    $nv_transaction_status = 2;
                } elseif ($checkPayment['data'][0] == 3) {
                    $nv_transaction_status = 3;
                } elseif ($checkPayment['data'][0] == 4) {
                    $nv_transaction_status = 4;
                }

                if ($nv_transaction_status != $transaction_data['transaction_status']) {
                    $json['status'] = 'CHANGED';
                    $json['message'] = $lang_module['update_order'];
                    $json['link'] = nv_url_rewrite(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=payment&order_id=' . $order_id . '&checkss=' . md5($order_id . $global_config['sitekey'] . session_id()), true);

                    // Cập nhật giao dịch
                    $check = $db->exec("UPDATE " . $db_config['prefix'] . "_" . $module_data . "_transaction SET
                        transaction_status=" . $nv_transaction_status . ",
                        payment_time=" . $checkPayment['data'][1] . "
                    WHERE transaction_id=" . $order_data['transaction_id']);

                    // Cập nhật đơn hàng
                    $check = $db->exec("UPDATE " . $db_config['prefix'] . "_" . $module_data . "_orders SET
                        transaction_status=" . $nv_transaction_status . ",
                        transaction_id=" . $order_data['transaction_id'] . "
                    WHERE order_id=" . $order_id);

                    if ($nv_transaction_status == 4) {
                        // Cập nhật điểm tích lũy
                        UpdatePoint($order_data);
                    }
                    $nv_Cache->delMod($module_name);
                }
            }
        }
    }
}

nv_jsonOutput($json);
