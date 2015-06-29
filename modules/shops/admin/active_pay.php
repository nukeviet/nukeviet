<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 2-10-2010 18:49
 */

if( !defined( 'NV_IS_FILE_ADMIN' ) )
	die( 'Stop!!!' );

$table_name = $db_config['prefix'] . '_' . $module_data . '_orders';
$contents = $lang_module['order_submit_pay_error'];

$order_id = $nv_Request->get_int( 'order_id', 'get', 0 );
$save = $nv_Request->get_string( 'save', 'post,get', '' );
$action = $nv_Request->get_string( 'action', 'post,get', '' );

$result = $db->query( 'SELECT * FROM ' . $table_name . ' WHERE order_id=' . $order_id );
$data_content = $result->fetch( );

// Thong tin chi tiet mat hang trong don hang
$listid = $listnum = $listprice = $listgroup = array();
$result = $db->query( 'SELECT * FROM ' . $db_config['prefix'] . '_' . $module_data . '_orders_id WHERE order_id=' . $order_id );
while( $row = $result->fetch() )
{
	$listid[] = $row['proid'];
	$listnum[] = $row['num'];
	$listprice[] = $row['price'];

	$result_group = $db->query( 'SELECT group_id FROM ' . $db_config['prefix'] . '_' . $module_data . '_orders_id_group WHERE order_i=' . $row['id'] );
	$group = array();
	while( list( $group_id ) = $result_group->fetch( 3 ) )
	{
		$group[] = $group_id;
	}
	$listgroup[] = $group;
}

$data_pro = array();
$i = 0;
foreach( $listid as $id )
{
	$sql = 'SELECT t1.id, t1.listcatid, t1.product_code, t1.publtime, t1.' . NV_LANG_DATA . '_title, t1.' . NV_LANG_DATA . '_alias, t1.product_price,t2.' . NV_LANG_DATA . '_title FROM ' . $db_config['prefix'] . '_' . $module_data . '_units AS t2, ' . $db_config['prefix'] . '_' . $module_data . '_rows AS t1 WHERE t1.product_unit = t2.id AND t1.id =' . $id . ' AND t1.status =1 AND t1.publtime < ' . NV_CURRENTTIME . ' AND (t1.exptime=0 OR t1.exptime>' . NV_CURRENTTIME . ')';
	$result = $db->query( $sql );
	if( $result->rowCount() )
	{
		list( $id, $_catid, $product_code, $publtime, $title, $alias, $product_price, $unit ) = $result->fetch( 3 );
		$data_pro[] = array(
			'id' => $id,
			'publtime' => $publtime,
			'title' => $title,
			'alias' => $alias,
			'product_price' => $listprice[$i],
			'product_price_total' => $listprice[$i] * $listnum[$i],
			'product_code' => $product_code,
			'product_unit' => $unit,
			'link_pro' => $link . $global_array_shops_cat[$_catid]['alias'] . '/' . $alias . '-' . $id,
			'product_number' => $listnum[$i],
			'product_group' => isset( $listgroup[$i] ) ? $listgroup[$i] : ''
		);
		++$i;
	}
}

if( empty( $data_content ) or empty( $action ) )
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

	if( $action == 'unpay' )
	{
		$db->query( 'DELETE FROM ' . $db_config['prefix'] . '_' . $module_data . '_transaction WHERE transaction_id = ' . $data_content['transaction_id'] );
		$db->query( "UPDATE " . $db_config['prefix'] . "_" . $module_data . "_orders SET transaction_status=0, transaction_id=0, transaction_count=transaction_count-1 WHERE order_id=" . $order_id );

		// Cap nhat diem tich luy
		UpdatePoint( $data_content, false );

		nv_insert_logs( NV_LANG_DATA, $module_name, 'Drop payment product', "Order code: " . $data_content['order_code'], $admin_info['userid'] );

		$contents = $lang_module['order_submit_unpay_ok'];

		nv_del_moduleCache( $module_name );
	}
	elseif( $action == 'pay' )
	{

		$transaction_status = 4;
		$payment_id = 0;
		$payment_amount = $data_content['order_total'];
		$payment_data = '';
		$payment = '';
		$userid = $admin_info['userid'];

		$transaction_id = $db->insert_id( "INSERT INTO " . $db_config['prefix'] . "_" . $module_data . "_transaction (transaction_id, transaction_time, transaction_status, order_id, userid, payment, payment_id, payment_time, payment_amount, payment_data) VALUES (NULL, " . NV_CURRENTTIME . ", '" . $transaction_status . "', '" . $order_id . "', '" . $userid . "', '" . $payment . "', '" . $payment_id . "', " . NV_CURRENTTIME . ", '" . $payment_amount . "', '" . $payment_data . "')" );

		if( $transaction_id > 0 )
		{
			$db->query( "UPDATE " . $db_config['prefix'] . "_" . $module_data . "_orders SET transaction_status=" . $transaction_status . ", transaction_id=" . $transaction_id . ", transaction_count=transaction_count+1 WHERE order_id=" . $order_id );

			//Cap nhat diem tich luy
			UpdatePoint( $data_content );

			nv_insert_logs( NV_LANG_DATA, $module_name, 'Log payment product', "Order code: " . $data_content['order_code'], $admin_info['userid'] );
		}

		// Gửi mail xác nhận thanh toán
		$content = '';
		$email_contents_table = call_user_func( 'email_new_order_payment', $content, $data_content, $data_pro, true );

		$checkss = md5( $order_id . $global_config['sitekey'] . session_id( ) );
		$review_url = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=payment&order_id=' . $order_id . '&checkss=' . $checkss;

		$replace_data = array(
			'order_code' => $data_content['order_code'],
			'order_name' => $data_content['order_name'],
			'order_email' => $data_content['order_email'],
			'order_phone' => $data_content['order_phone'],
			'order_address' => $data_content['order_address'],
			'order_note' => $data_content['order_note'],
			'order_total' => nv_number_format( $data_content['order_total'] ),
			'unit_total' => $data_content['unit_total'],
			'dateup' => nv_date( "d-m-Y", $data_content['order_time'] ),
			'moment' => nv_date( "H:i", $data_content['order_time'] ),
			'review_url' => '<a href="' . $global_config['site_url'] . $review_url . '">' . $lang_module['here'] . '</a>',
			'table_product' => $email_contents_table,
			'site_url' => $global_config['site_url'],
			'site_name' => $global_config['site_name'],
		);

		$content_file = NV_ROOTDIR . '/' . NV_DATADIR . '/' . NV_LANG_DATA . '_' . $module_data . '_order_payment_content.txt';
		if( file_exists( $content_file ) )
		{
			$content = file_get_contents( $content_file );
			if( empty( $content ) )
			{
				$content = $lang_module['order_email_payment'];
			}
			$content = nv_editor_br2nl( $content );
		}
		else
		{
			$content = $lang_module['order_email_payment'];
		}

		foreach( $replace_data as $key => $value )
		{
			$content = str_replace( '{' . $key . '}', $value, $content );
		}

		$email_contents = call_user_func( 'email_new_order_payment', $content, $data_content, $data_pro );
		nv_sendmail( array(
			$global_config['site_name'],
			$global_config['site_email']
		), $data_content['order_email'], sprintf( $lang_module['document_payment_email_order_payment'], $module_info['custom_title'], $data_content['order_code'] ), $email_contents );

		$contents = $lang_module['order_submit_pay_ok'];

		nv_del_moduleCache( $module_name );
	}
}
die( $contents );
