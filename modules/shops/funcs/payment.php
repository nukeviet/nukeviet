<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2017 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 04/18/2017 09:47
 */

if( !defined( 'NV_IS_MOD_SHOPS' ) )
{
	die( 'Stop!!!' );
}

$page_title = $lang_module['order_view'];

$order_id = $nv_Request->get_int( 'order_id', 'get', 0 );
$checkss = $nv_Request->get_string( 'checkss', 'get', '' );
if( $order_id > 0 and $checkss == md5( $order_id . $global_config['sitekey'] . session_id( ) ) )
{
	$data_pro = array( );
	$link = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=';

	// Thong tin don hang
	$result = $db->query( 'SELECT * FROM ' . $db_config['prefix'] . '_' . $module_data . '_orders WHERE order_id=' . $order_id );
	if( $result->rowCount( ) == 0 )
	{
		Header( 'Location: ' . NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name );
		die( );
	}
	$data = $result->fetch( );

	// Thong tin van chuyen
	$result = $db->query( 'SELECT * FROM ' . $db_config['prefix'] . '_' . $module_data . '_orders_shipping WHERE order_id = ' . $data['order_id'] );
	$data_shipping = $result->fetch( );

	$result = $db->query( 'SELECT amount FROM ' . $db_config['prefix'] . '_' . $module_data . '_coupons_history WHERE order_id=' . $data['order_id'] );
	$data['coupons'] = $result->fetch( );

	if( empty( $data ) )
	{
		Header( 'Location: ' . nv_url_rewrite( NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=cart', true ) );
		die( );
	}

	// Sua don hang
	if( $nv_Request->isset_request( 'edit', 'get' ) )
	{
		if( $data['transaction_status'] != 4 )
		{
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
		Header( 'Location: ' . nv_url_rewrite( NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=cart', true ) );
		die( );
	}

	// Huy sua don hang
	if( $nv_Request->isset_request( 'unedit', 'get' ) )
	{
		if( isset( $_SESSION[$module_data . '_cart'] ) )
		{
			unset( $_SESSION[$module_data . '_cart'] );
		}

		if( isset( $_SESSION[$module_data . '_order_info'] ) )
		{
			unset( $_SESSION[$module_data . '_order_info'] );
		}
		Header( 'Location: ' . NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=payment&order_id=' . $data['order_id'] . '&checkss=' . $checkss );
		die( );
	}

	if( !empty( $_SESSION[$module_data . '_order_info'] ) )
	{
		$lang_module['order_edit'] = $lang_module['order_unedit'];
	}

	// Thong tin chi tiet mat hang trong don hang
	$listid = $listnum = $listprice = $listgroup = $slistgroup = array( );
	$result = $db->query( 'SELECT * FROM ' . $db_config['prefix'] . '_' . $module_data . '_orders_id WHERE order_id=' . $order_id );
	while( $row = $result->fetch( ) )
	{
		$listid[] = $row['proid'];
		$listnum[] = $row['num'];
		$listprice[] = $row['price'];

		$result_group = $db->query( 'SELECT group_id FROM ' . $db_config['prefix'] . '_' . $module_data . '_orders_id_group WHERE order_i=' . $row['id'] );
		$group = array( );
		while( list( $group_id ) = $result_group->fetch( 3 ) )
		{
			$group[] = $group_id;
		}
		$listgroup[] = $group;
		$slistgroup[] = implode( ",", $group );
	}
	$i = 0;
	foreach( $listid as $proid )
	{
		if( empty( $listprice[$i] ) )
		{
			$listprice[$i] = 0;
		}
		if( empty( $listnum[$i] ) )
		{
			$listnum[$i] = 0;
		}
		if( !isset( $listgroup[$i] ) )
		{
			$listgroup[$i] = '';
		}

		$temppro[$proid] = array(
			'price' => $listprice[$i],
			'num' => $listnum[$i],
			'group' => $listgroup[$i]
		);

		$arrayid[] = $proid;
		$i++;
	}


	if( !empty( $arrayid ) )
	{
		$templistid = implode( ',', $arrayid );


		foreach( $slistgroup as $list )
		{
			$product_group= array();
			if(!empty($list)) $product_group=explode(',',$list);
			$sql = 'SELECT t1.id, t1.listcatid, t1.publtime, t1.' . NV_LANG_DATA . '_title, t1.' . NV_LANG_DATA . '_alias, t1.' . NV_LANG_DATA . '_hometext, t2.' . NV_LANG_DATA . '_title, t1.money_unit, t1.discount_id FROM ' . $db_config['prefix'] . '_' . $module_data . '_rows AS t1, ' . $db_config['prefix'] . '_' . $module_data . '_units AS t2, ' . $db_config['prefix'] . '_' . $module_data . '_orders_id AS t3  WHERE t1.product_unit = t2.id AND t1.id = t3.proid AND t1.id IN (' . $templistid . ') AND listgroupid=' . $db->quote( $list ) . ' AND t3.order_id=' . $order_id.' AND t1.status =1';
			$result = $db->query( $sql );
			while( list( $id, $listcatid, $publtime, $title, $alias, $hometext, $unit, $money_unit, $discount_id ) = $result->fetch( 3 ) )
			{
				$price = nv_get_price( $id, $pro_config['money_unit'], $temppro[$id]['num'], true );
				$data_pro[] = array(
					'id' => $id,
					'publtime' => $publtime,
					'title' => $title,
					'alias' => $alias,
					'hometext' => $hometext,
					'product_price' => $price['sale'],
					'product_unit' => $unit,
					'money_unit' => $money_unit,
					'discount_id' => $discount_id,
					'product_group' => $product_group,
					'link_pro' => $link . $global_array_shops_cat[$listcatid]['alias'] . '/' . $alias . $global_config['rewrite_exturl'],
					'product_number' => $temppro[$id]['num']
				);
			}
		}

	}

	// Xay dung cac url thanh toan truc tuyen
	$url_checkout = array( );
	$intro_pay = '';

	if( intval( $data['transaction_status'] ) == -1 )
	{
		$intro_pay = $lang_module['payment_none_pay'];
	}
	elseif( $data['transaction_status'] == 0 )
	{
		$sql = 'SELECT * FROM ' . $db_config['prefix'] . '_' . $module_data . '_payment WHERE active=1 ORDER BY weight ASC';
		$result = $db->query( $sql );

		while( $row = $result->fetch( ) )
		{
			if( file_exists( NV_ROOTDIR . '/modules/' . $module_file . '/payment/' . $row['payment'] . '.checkout_url.php' ) )
			{
				$payment_config = unserialize( nv_base64_decode( $row['config'] ) );
				$payment_config['paymentname'] = $row['paymentname'];
				$payment_config['domain'] = $row['domain'];

				$images_button = $row['images_button'];

				$url = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;order_id=' . $order_id . '&amp;payment=' . $row['payment'] . '&amp;checksum=' . md5( $order_id . $row['payment'] . $global_config['sitekey'] . session_id( ) );

				if( !empty( $images_button ) and file_exists( NV_UPLOADS_REAL_DIR . '/' . $module_upload . '/' . $images_button ) )
				{
					$images_button = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_upload . '/' . $images_button;
				}

				$url_checkout[] = array(
					'name' => $row['paymentname'],
					'url' => $url,
					'images_button' => $images_button
				);
			}
		}

		// Get content intro
		$content_file = NV_ROOTDIR . '/' . NV_DATADIR . '/' . NV_LANG_DATA . '_' . $module_data . '_docpay_content.txt';
		if( file_exists( $content_file ) )
		{
			$intro_pay = file_get_contents( $content_file );
			$intro_pay = nv_editor_br2nl( $intro_pay );
		}
	}
	elseif( $data['transaction_status'] == 1 and $data['transaction_id'] > 0 )
	{
		if( $nv_Request->isset_request( 'cancel', 'get' ) )
		{
			$db->query( 'DELETE FROM ' . $db_config['prefix'] . '_' . $module_data . '_transaction WHERE transaction_id = ' . $data['transaction_id'] );
			$db->query( 'UPDATE ' . $db_config['prefix'] . '_' . $module_data . '_orders SET transaction_status=0, transaction_id = 0, transaction_count = 0 WHERE order_id=' . $order_id );
			Header( 'Location: ' . NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=payment&order_id=' . $order_id . '&checkss=' . $checkss );
			die( );
		}

		$payment = $db->query( 'SELECT payment FROM ' . $db_config['prefix'] . '_' . $module_data . '_transaction WHERE transaction_id=' . $data['transaction_id'] )->fetchColumn( );
		$config = $db->query( "SELECT * FROM " . $db_config['prefix'] . "_" . $module_data . "_payment WHERE payment = '" . $payment . "'" )->fetch( );
		$intro_pay = sprintf( $lang_module['order_by_payment'], $config['domain'], $config['paymentname'] );
	}
	if( $data['transaction_status'] == 4 )
	{
		$data['transaction_name'] = $lang_module['history_payment_yes'];
	}
	elseif( $data['transaction_status'] == 3 )
	{
		$data['transaction_name'] = $lang_module['history_payment_cancel'];
	}
	elseif( $data['transaction_status'] == 2 )
	{
		$data['transaction_name'] = $lang_module['history_payment_check'];
	}
	elseif( $data['transaction_status'] == 1 )
	{
		$data['transaction_name'] = $lang_module['history_payment_send'];
	}
	elseif( $data['transaction_status'] == 0 )
	{
		$data['transaction_name'] = $lang_module['history_payment_no'];
	}
	elseif( $data['transaction_status'] == -1 )
	{
		$data['transaction_name'] = $lang_module['history_payment_wait'];
	}
	else
	{
		$data['transaction_name'] = 'ERROR';
	}

	// Lay so diem tich luy cua khach
	$point = 0;
	if( !empty( $user_info ) )
	{
		$result = $db->query( 'SELECT point_total FROM ' . $db_config['prefix'] . '_' . $module_data . '_point WHERE userid = ' . $user_info['userid'] );
		if( $result )
		{
			$point = $result->fetchColumn( );
		}
	}

	$contents = call_user_func( 'payment', $data, $data_pro, $data_shipping, $url_checkout, $intro_pay, $point );

	include NV_ROOTDIR . '/includes/header.php';
	echo nv_site_theme( $contents );
	include NV_ROOTDIR . '/includes/footer.php';
}
elseif( $order_id > 0 and $nv_Request->isset_request( 'payment', 'get' ) and $nv_Request->isset_request( 'checksum', 'get' ) )
{
	$checksum = $nv_Request->get_string( 'checksum', 'get' );
	$payment = $nv_Request->get_string( 'payment', 'get' );

	// Thong tin don hang
	$result = $db->query( 'SELECT * FROM ' . $db_config['prefix'] . '_' . $module_data . '_orders WHERE order_id=' . $order_id );
	$data = $result->fetch( );

	if( empty( $data ) )
	{
		Header( 'Location: ' . nv_url_rewrite( NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=cart', true ) );
		die( );
	}

	// Thong tin chi tiet mat hang trong don hang
	$listid = $listnum = $listprice = $listgroup = array( );
	$result = $db->query( 'SELECT * FROM ' . $db_config['prefix'] . '_' . $module_data . '_orders_id WHERE order_id=' . $order_id );
	while( $row = $result->fetch( ) )
	{
		$listid[] = $row['proid'];
		$listnum[] = $row['num'];
		$listprice[] = $row['price'];

		$result_group = $db->query( 'SELECT group_id FROM ' . $db_config['prefix'] . '_' . $module_data . '_orders_id_group WHERE order_i=' . $row['id'] );
		$group = array( );
		while( list( $group_id ) = $result_group->fetch( 3 ) )
		{
			$group[] = $group_id;
		}
		$listgroup[] = $group;
	}

	if( isset( $data['transaction_status'] ) and intval( $data['transaction_status'] ) == 0 and preg_match( '/^[a-zA-Z0-9_]+$/', $payment ) and $checksum == md5( $order_id . $payment . $global_config['sitekey'] . session_id( ) ) and file_exists( NV_ROOTDIR . '/modules/' . $module_file . '/payment/' . $payment . '.checkout_url.php' ) )
	{
		$config = $db->query( "SELECT * FROM " . $db_config['prefix'] . "_" . $module_data . "_payment WHERE payment = '" . $payment . "'" )->fetch( );
		$payment_config = unserialize( nv_base64_decode( $config['config'] ) );

		// Cap nhat cong thanh toan
		$transaction_status = 1;
		$payment_id = 0;
		$payment_amount = 0;
		$payment_data = '';

		$userid = (defined( 'NV_IS_USER' )) ? $user_info['userid'] : 0;

		$transaction_id = $db->insert_id( "INSERT INTO " . $db_config['prefix'] . "_" . $module_data . "_transaction (transaction_id, transaction_time, transaction_status, order_id, userid, payment, payment_id, payment_time, payment_amount, payment_data) VALUES (NULL, " . NV_CURRENTTIME . ", '" . $transaction_status . "', '" . $order_id . "', '" . $userid . "', '" . $payment . "', '" . $payment_id . "', " . NV_CURRENTTIME . ", '" . $payment_amount . "', '" . $payment_data . "')" );

		$db->query( 'UPDATE ' . $db_config['prefix'] . '_' . $module_data . '_orders SET transaction_status=' . $transaction_status . ' , transaction_id = ' . $transaction_id . ' , transaction_count = 1 WHERE order_id=' . $order_id );

		require_once NV_ROOTDIR . '/modules/' . $module_file . '/payment/' . $payment . '.checkout_url.php';
	}
	elseif( $result->rowCount( ) > 0 )
	{
		$url = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op . '&order_id=' . $order_id . '&checkss=' . md5( $order_id . $global_config['sitekey'] . session_id( ) );
	}
	else
	{
		$url = NV_BASE_SITEURL;
	}
	Header( 'Location: ' . $url );
	die( );
}
else
{
	Header( 'Location: ' . NV_BASE_SITEURL );
	die( );
}
