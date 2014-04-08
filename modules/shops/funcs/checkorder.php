<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES., JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 3-6-2010 0:14
 */

if( ! defined( 'NV_IS_MOD_SHOPS' ) ) die( 'Stop!!!' );
if( ! defined( 'NV_IS_AJAX' ) ) die( 'Wrong URL' );

$array_update_order = array();
$checkss = $nv_Request->get_string( 'checkss', 'get', '' );

if( $checkss == md5( $user_info["userid"] . $global_config['sitekey'] . session_id() ) )
{
	$array_data_payment = array();
	$sql = "SELECT * FROM " . $db_config['prefix'] . "_" . $module_data . "_payment WHERE active=1 ORDER BY weight ASC";
	$result = $db->query( $sql );
	while( $row = $result->fetch() )
	{
		$payment = $row['payment'];
		if( file_exists( NV_ROOTDIR . "/modules/" . $module_file . "/payment/" . $payment . ".config.ini" ) )
		{
			$array_data_payment[$payment] = array(
				'config' => array(),
				'orders_id' => array(),
				'data' => array()
			);
			$array_data_payment[$payment]['config'] = unserialize( nv_base64_decode( $row['config'] ) );
		}
	}
	$array_transaction_status_check = array( 1, 2, 4 );
	$sql = "SELECT t1.order_id, t1.order_code, t2.payment, t2.payment_id, t2.payment_data FROM " . $db_config['prefix'] . "_" . $module_data . "_orders AS t1 INNER JOIN " . $db_config['prefix'] . "_" . $module_data . "_transaction AS t2 ON t1.transaction_id = t2.transaction_id WHERE t1.user_id = " . $user_info["userid"] . " AND t1.transaction_status in (" . implode( ",", $array_transaction_status_check ) . ") ORDER BY t1.order_id DESC ";
	$result = $db->query( $sql );
	while( list( $order_id, $order_code, $payment, $payment_id, $payment_data ) = $result->fetch( 3 ) )
	{
		$array_data_payment[$payment]['data'][$order_code] = array(
			'order_code' => $order_code,
			'payment_id' => $payment_id,
			'order_id' => $order_id,
			'payment_data' => $payment_data
		);
	}

	foreach( $array_data_payment as $payment => $value )
	{
		$array_order = $array_data_payment[$payment]['data'];
		if( ! empty( $array_order ) and file_exists( NV_ROOTDIR . "/modules/" . $module_file . "/payment/" . $payment . ".checkorders.php" ) )
		{
			$data_orders_return = array();
			$payment_config = $array_data_payment[$payment]['config'];
			require_once NV_ROOTDIR . "/modules/" . $module_file . "/payment/" . $payment . ".checkorders.php";
		}
	}

}
if( ! empty( $array_update_order ) )
{
	$title = sprintf( $lang_module['update_order'], implode( ", ", $array_update_order ) );
	$title = str_replace( "_", "#@#", $title );

	$contents = "UPDATE_" . $title;
}
else
{
	$title = str_replace( "_", "#@#", $lang_module['no_update_order'] );
	$contents = "NOUPDATE_" . $title;
}

include NV_ROOTDIR . '/includes/header.php';
echo $contents;
include NV_ROOTDIR . '/includes/footer.php';