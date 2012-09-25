<?php

/**
 * @Project NUKEVIET 3.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES.,JSC. All rights reserved
 * @Createdate 2-10-2010 18:49
 */

if( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );

$table_name = $db_config['prefix'] . "_" . $module_data . "_orders";
$contents = $lang_module['order_submit_pay_error'];

$order_id = $nv_Request->get_int( 'order_id', 'get', 0 );
$save = $nv_Request->get_string( 'save', 'post,get', '' );

$result = $db->sql_query( "SELECT *  FROM `" . $table_name . "` WHERE `order_id`=" . $order_id );
$data_content = $db->sql_fetchrow( $result, 2 );

if( empty( $data_content ) )
{
	$contents = $lang_module['order_submit_pay_error'];
}

if( $save == 1 )
{
	/* transaction_status: Trang thai giao dich:
	-1 - Giao dich cho duyet
	0 - Giao dich moi tao
	1 - Chua thanh toan; 
	2 - Da thanh toan, dang bi tam giu; 
	3 - Giao dich bi huy; 
	4 - Giao dich da hoan thanh thanh cong (truong hop thanh toan ngay hoac thanh toan tam giu nhung nguoi mua da phe chuan)
	*/

	$transaction_status = 4;
	$payment_id = 0;
	$payment_amount = 0;
	$payment_data = "";
	$payment = "";
	$userid = $admin_info['userid'];
	
	$transaction_id = $db->sql_query_insert_id( "INSERT INTO `" . $db_config['prefix'] . "_" . $module_data . "_transaction` (`transaction_id`, `transaction_time`, `transaction_status`, `order_id`, `userid`, `payment`, `payment_id`, `payment_time`, `payment_amount`, `payment_data`) VALUES (NULL, UNIX_TIMESTAMP(), '" . $transaction_status . "', '" . $order_id . "', '" . $userid . "', '" . $payment . "', '" . $payment_id . "', UNIX_TIMESTAMP(), '" . $payment_amount . "', '" . $payment_data . "')" );
	
	if( $transaction_id > 0 )
	{
		$db->sql_query( "UPDATE `" . $db_config['prefix'] . "_" . $module_data . "_orders` SET `transaction_status`=" . $transaction_status . ", `transaction_id`=" . $transaction_id . ", `transaction_count`=`transaction_count`+1 WHERE `order_id`=" . $order_id );
		
		nv_insert_logs( NV_LANG_DATA, $module_name, 'Log payment product', "ID: " . $id_pro, $admin_info['userid'] );
	}
	
	$contents = $lang_module['order_submit_pay_ok'];
	
	nv_del_moduleCache( $module_name );
}

include ( NV_ROOTDIR . "/includes/header.php" );
echo $contents;
include ( NV_ROOTDIR . "/includes/footer.php" );

?>