<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES., JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 3-6-2010 0:14
 */

if ( $_SERVER['REMOTE_ADDR'] == '117.6.64.27' or $_SERVER['REMOTE_ADDR'] == '123.30.51.48' )
{
	define( 'NV_SYSTEM', true );

	require_once str_replace( '\\\\', '/', dirname( __file__ ) ) . '/mainfile.php';
	require_once NV_ROOTDIR . "/includes/core/user_functions.php";
	require_once NV_ROOTDIR . "/includes/class/nusoap.php";

	$module_name = "shops";
	$payment = "nganluong";

	$site_mods = nv_site_mods();
	if ( isset( $site_mods[$module_name] ) )
	{
		$module_info = $site_mods[$module_name];
		$module_file = $module_info['module_file'];
		$module_data = $module_info['module_data'];

		$sql = "SELECT * FROM " . $db_config['prefix'] . "_" . $module_data . "_payment WHERE payment = '" . $payment . "'";
		$config = $db->query( $sql )->fetch();

		$payment_config = unserialize( nv_base64_decode( $config['config'] ) );

		require_once NV_ROOTDIR . "/modules/" . $module_file . "/payment/nganluong.class.php";
	}
	else
	{
		$module_info = $module_data = $module_file = $pro_config = "";
	}

	function UpdateOrder ( $transaction_info, $order_code, $payment_id, $payment_type, $secure_code )
	{
		global $db, $db_config, $module_data, $payment, $payment_config;
		$payment_id = intval( $payment_id );
		$payment_type = intval( $payment_type );
		// Kiểm tra chuỗi bảo mật
		$secure_code_new = md5( $transaction_info . ' ' . $order_code . ' ' . $payment_id . ' ' . $payment_type . ' ' . $payment_config['secure_pass'] );

		if ( $secure_code_new != $secure_code )
		{
			file_put_contents( NV_ROOTDIR . '/logs/data_logs/nl_err_' . date( "Ymd" ) . '.log', "UpdateOrder Sai mã bảo mật \r\n", FILE_APPEND );
			file_put_contents( NV_ROOTDIR . '/logs/data_logs/nl_err_' . date( "Ymd" ) . '.log', serialize( $HTTP_RAW_POST_DATA ) . " \r\n", FILE_APPEND );
			return - 1; // Sai mã bảo mật
		}
		elseif ( ! empty( $module_data ) ) // Thanh toán thành công
		{
			$nl = new NL_Checkout( $payment_config['checkout_url'], $payment_config['merchant_site'], $payment_config['secure_pass'] );
			$transaction_i = $nl->checkOrder( $payment_config['public_api_url'], $order_code, $payment_id );
			if ( $transaction_i !== false )
			{
				$order_id = $db->query( "SELECT order_id FROM " . $db_config['prefix'] . "_" . $module_data . "_orders WHERE order_code=" . $db->quote( $order_code ) )->fetchColumn();
				if ( $order_id > 0 )
				{
					$error_update = false;
					$payment_data = nv_base64_encode( serialize( $transaction_i ) );
					$db->sqlreset()
						->select( 'payment_data' )
						->from( $db_config['prefix'] . "_" . $module_data . "_transaction" )
						->where( "payment='" . $payment . "' AND payment_id=" . $payment_id )
						->order( 'transaction_id DESC' )
						->limit( 1 );

					$payment_data_old = $db->query( $db->sql() )->fetchColumn();
					if ( $payment_data != $payment_data_old )
					{
						$nv_transaction_status = intval( $transaction_i['nv_transaction_status'] );
						$payment_amount = intval( $transaction_i['AMOUNT'] );
						$payment_time = max( $transaction_i['CREATED_TIME'], $transaction_i['PAID_TIME'] );

						$transaction_id = $db->insert_id( "INSERT INTO " . $db_config['prefix'] . "_" . $module_data . "_transaction (transaction_id, transaction_time, transaction_status, order_id, userid, payment, payment_id, payment_time, payment_amount, payment_data) VALUES (NULL, " . NV_CURRENTTIME . ", '" . $nv_transaction_status . "', '" . $order_id . "', '0', '" . $payment . "', '" . $payment_id . "', '" . $payment_time . "', '" . $payment_amount . "', '" . $payment_data . "')" );
						if ( $transaction_id > 0 )
						{
							$db->query( "UPDATE " . $db_config['prefix'] . "_" . $module_data . "_orders SET transaction_status=" . $nv_transaction_status . " , transaction_id = " . $transaction_id . " , transaction_count = transaction_count+1 WHERE order_id=" . $order_id );
						}
						else
						{
							return 0;
						}
					}
				}
			}
		}
		return 1;
	}

	//function RefundOrder ( $transaction_info, $order_code, $payment_id, $refund_payment_id, $refund_amount, $refund_type, $refund_description, $secure_code )
	function RefundOrder( $transaction_info,$order_code,$payment_id,$refund_payment_id,$secure_code )
	{
		global $db, $db_config, $module_data, $payment, $payment_config, $HTTP_RAW_POST_DATA;
		$return = 0;
		file_put_contents( NV_ROOTDIR . '/logs/data_logs/nl_err_' . date( "Ymd" ) . '.log', $transaction_info . '--->' . $order_code . '--->' . $payment_id . '--->' . $refund_payment_id . '--->' . $secure_code, FILE_APPEND );
		// Kiểm tra chuỗi bảo mật
		$secure_code_new = md5( $transaction_info.' '.$order_code.' '.$payment_id.' '.$refund_payment_id.' '. $payment_config['secure_pass'] );
		if ( $secure_code_new != $secure_code )
		{
			file_put_contents( NV_ROOTDIR . '/logs/data_logs/nl_err_' . date( "Ymd" ) . '.log', "RefundOrder Sai mã bảo mật \r\n", FILE_APPEND );
			file_put_contents( NV_ROOTDIR . '/logs/data_logs/nl_err_' . date( "Ymd" ) . '.log', serialize( $HTTP_RAW_POST_DATA ) . " \r\n", FILE_APPEND );
		}
		elseif ( ! empty( $module_data ) ) // Trường hợp hòan trả thành công
		{
			// Lập trình thông báo hoàn trả thành công và cập nhật hóa đơn
			$order_id = $db->query( "SELECT order_id FROM " . $db_config['prefix'] . "_" . $module_data . "_orders WHERE order_code=" . $db->quote( $order_code ) )->fetchColumn();
			if ( $order_id > 0 )
			{
				/*
				$input_data = array( 
					'transaction_info' => $transaction_info, 'order_code' => $order_code, 'payment_id' => $payment_id, 'refund_payment_id' => $refund_payment_id, 'refund_amount' => $refund_amount, 'refund_type' => $refund_type, 'refund_description' => $refund_description, 'secure_code' => $secure_code 
				);
				*/

				$payment_id_old = intval( $payment_id );
				$payment_id = intval( $refund_payment_id );
				$payment_amount = 0;//intval( $refund_amount );
				$nv_transaction_status = 3;
				$payment_time = NV_CURRENTTIME;
				$payment_data = nv_base64_encode( serialize( $input_data ) );

				$sql = "INSERT INTO " . $db_config['prefix'] . "_" . $module_data . "_transaction (transaction_id, transaction_time, transaction_status, order_id, userid, payment, payment_id, payment_time, payment_amount, payment_data) VALUES (NULL, " . NV_CURRENTTIME . ", '" . $nv_transaction_status . "', '" . $order_id . "', '0', '" . $payment . "', '" . $payment_id . "', '" . $payment_time . "', '" . $payment_amount . "', '" . $payment_data . "')";
				$transaction_id = $db->insert_id( $sql );
				if ( $transaction_id > 0 )
				{
					$db->query( "UPDATE " . $db_config['prefix'] . "_" . $module_data . "_orders SET transaction_status=" . $nv_transaction_status . " , transaction_id = " . $transaction_id . " , transaction_count = transaction_count+1 WHERE order_id=" . $order_id );
				}
				else
				{
					file_put_contents( NV_ROOTDIR . '/logs/data_logs/nl_err_' . date( "Ymd" ) . '.log', "ERROR SQL: " . $sql . " \r\n", FILE_APPEND );
				}
				$return = 1;
			} 
		}
		return $return;
	}

	// Khai bao chung WebService
	$server = new nusoap_server();
	$server->configureWSDL( 'NV3_SHOP_SERVICE', $global_config['site_url'] . '/service_shops_nganluong.php?wsdl' );
	// Khai bao cac Function
	$server->register( 'UpdateOrder', array(
		'transaction_info' => 'xsd:string', 'order_code' => 'xsd:string', 'payment_id' => 'xsd:string', 'payment_type' => 'xsd:string', 'secure_code' => 'xsd:string'
	), array( 'result' => 'xsd:int' ) );
	$server->register( 'RefundOrder', array(
		'transaction_info' => 'xsd:string', 'order_code' => 'xsd:string', 'payment_id' => 'xsd:string', 'refund_payment_id' => 'xsd:string', 'secure_code' => 'xsd:string'
	), array( 'result' => 'xsd:int' ) );

	// $server->register( 'RefundOrder', array( 
		// 'transaction_info' => 'xsd:string', 'order_code' => 'xsd:string', 'payment_id' => 'xsd:string', 'refund_payment_id' => 'xsd:string', 'refund_amount' => 'xsd:string', 'refund_type' => 'xsd:string', 'refund_description' => 'xsd:string', 'secure_code' => 'xsd:string' 
	// ), array( 
		// 'result' => 'xsd:int' 
	// ) );

	// Khoi tao Webservice
	$HTTP_RAW_POST_DATA = ( isset( $HTTP_RAW_POST_DATA ) ) ? $HTTP_RAW_POST_DATA : '';
	$server->service( $HTTP_RAW_POST_DATA );
}
else
{
	header( "HTTP/1.1 404 Not Found" );
	header( 'Content-Length: 0' );
	exit();
}