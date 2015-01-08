<?php

/**
 * @Project NUKEVIET 3.x
 * @Author VINADES., JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES ., JSC. All rights reserved
 * @Createdate Dec 29, 2010  10:42:00 PM 
 */

if ( ! defined( 'NV_IS_MOD_SHOPS' ) ) die( 'Stop!!!' );
die('dsf');
foreach( $array_order as $order_code => $order_data )
{
	$payment_data = unserialize( nv_base64_decode( $order_data['payment_data'] ) );
	$vpc_MerchTxnRef = $payment_data['vpc_MerchTxnRef'];

	$url = $payment_config['QueryDR_url'] . "?vpc_Version=" . $payment_config['vpc_Version'] . "&vpc_Command=queryDR&vpc_Merchant=" . $payment_config['vpc_Merchant'] . "&vpc_AccessCode=" . $payment_config['vpc_AccessCode'] . "&vpc_MerchTxnRef=" . $vpc_MerchTxnRef . "&vpc_User=" . $payment_config['vpc_User'] . "&vpc_Password=" . $payment_config['vpc_Password'];

	$return = file_get_contents( $url );
	$data = explode( "&", urldecode( $return ) );
	$array = array();
	foreach( $data as $data_i )
	{
		$data_i = array_map( "trim", explode( "=", $data_i ) );
		$array[$data_i[0]] = $data_i[1];
	}

	if( $array['vpc_DRExists'] == "Y" )
	{
		if( $array['vpc_TxnResponseCode'] == '0' )
		{
			$nv_transaction_status = 4; // Giao dich thanh cong
		}
		else
		{
			$nv_transaction_status = 3; // Giao dich bi huy
		}
		
		$transaction_i = array();
		$transaction_i['nv_transaction_status'] = $nv_transaction_status;
		$transaction_i['amount'] = round( ( int )$array['vpc_Amount'] / 100 );
		$transaction_i['created_time'] = $payment_data['created_time'];
		$transaction_i['vpc_MerchTxnRef'] = $array['vpc_MerchTxnRef'];

		$payment_data_new = nv_base64_encode( serialize( $transaction_i ) );
		$payment_data_old = $order_data['payment_data'];
		
		if ( $payment_data_new != $payment_data_old )
		{
			$payment_amount = $transaction_i['amount'];
			$payment_time = $payment_data['created_time'];
			$order_id = $array_order[$order_code]['order_id'];
			$payment_id = intval( $array['vpc_TransactionNo'] );
						
			$transaction_id = $db->insert_id( "INSERT INTO " . $db_config['prefix'] . "_" . $module_data . "_transaction (transaction_id, transaction_time, transaction_status, order_id, userid, payment, payment_id, payment_time, payment_amount, payment_data) VALUES (NULL, " . NV_CURRENTTIME . ", '" . $nv_transaction_status . "', '" . $order_id . "', '0', '" . $payment . "', '" . $payment_id . "', '" . $payment_time . "', '" . $payment_amount . "', '" . $payment_data_new. "')" );
			if ( $transaction_id > 0 )
			{				
				$db->query( "UPDATE " . $db_config['prefix'] . "_" . $module_data . "_orders SET transaction_status=" . $nv_transaction_status . " , transaction_id = " . $transaction_id . " , transaction_count = transaction_count+1 WHERE order_id=" . $order_id );
				$array_update_order[] = $order_code;
			}
		}
	}
}

?>