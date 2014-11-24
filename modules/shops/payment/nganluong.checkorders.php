<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES., JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES ., JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Dec 29, 2010 10:42:00 PM
 */

if( ! defined( 'NV_IS_MOD_SHOPS' ) ) die( 'Stop!!!' );

require_once NV_ROOTDIR . "/modules/" . $module_file . "/payment/nganluong.class.php";
$nl = new NL_Checkout( $payment_config['checkout_url'], $payment_config['merchant_site'], $payment_config['secure_pass'] );
$data_orders_return = $nl->checkOrders( $payment_config['public_api_url'], $array_order );

foreach( $data_orders_return as $data_transaction_id )
{
	if( $data_transaction_id['TRANSACTION_ERROR_CODE'] == '00' )
	{
		$order_code = $data_transaction_id['ORDER_CODE'];

		$payment_data = nv_base64_encode( serialize( $data_transaction_id ) );
		$payment_data_old = $array_order[$order_code]['payment_data'];
		if( $payment_data != $payment_data_old )
		{
			$nv_transaction_status = intval( $data_transaction_id['nv_transaction_status'] );
			$payment_amount = intval( $data_transaction_id['AMOUNT'] );
			$payment_time = max( $data_transaction_id['CREATED_TIME'], $data_transaction_id['PAID_TIME'] );
			$order_id = $array_order[$order_code]['order_id'];
			$payment_id = intval( $data_transaction_id['PAYMENT_ID'] );

			$transaction_id = $db->insert_id( "INSERT INTO " . $db_config['prefix'] . "_" . $module_data . "_transaction (transaction_id, transaction_time, transaction_status, order_id, userid, payment, payment_id, payment_time, payment_amount, payment_data) VALUES (NULL, " . NV_CURRENTTIME . ", '" . $nv_transaction_status . "', '" . $order_id . "', '0', '" . $payment . "', '" . $payment_id . "', '" . $payment_time . "', '" . $payment_amount . "', '" . $payment_data . "')" );
			if( $transaction_id > 0 )
			{
				$db->query( "UPDATE " . $db_config['prefix'] . "_" . $module_data . "_orders SET transaction_status=" . $nv_transaction_status . " , transaction_id = " . $transaction_id . " , transaction_count = transaction_count+1 WHERE order_id=" . $order_id );
				$array_update_order[] = $order_code;
			}
		}
	}
}