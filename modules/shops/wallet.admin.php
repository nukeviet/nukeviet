<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC <contact@vinades.vn>
 * @Copyright (C) 2017 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 04/18/2017 09:47
 */

if (!defined('NV_IS_WALLET_ADMIN')) {
    die('Stop!!!');
}

/*
 * Các biến hỗ trợ
 * $module_name
 * $module_info
 * $module_file
 * $module_data
 * $module_upload
 * $order_info thông tin về trạng thái thanh toán, id của đơn hàng
 */
require NV_ROOTDIR . '/modules/' . $module_file . '/global.functions.php';

// Xác định trạng thái đơn hàng hiện tại, nếu không bằng 4 thì mới cho cập nhật
$sql = "SELECT * FROM " . $db_config['prefix'] . "_" . $module_data . "_orders WHERE order_id=" . $order_info['order_id'];
$order = $db->query($sql)->fetch();
if (!empty($order) and isAllowedUpdateOrder($order['transaction_status'])) {
    // Lưu giao dịch mới
    // FIXME

    // Cập nhật trạng thái đơn hàng
    // FIXME
}
