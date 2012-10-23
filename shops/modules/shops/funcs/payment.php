<?php

/**
 * @Project NUKEVIET 3.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES., JSC. All rights reserved
 * @Createdate 3-6-2010 0:14
 */

if ( ! defined( 'NV_IS_MOD_SHOPS' ) ) die( 'Stop!!!' );

$page_title = $lang_module['order_view'];

$order_id = $nv_Request->get_int( 'order_id', 'get', 0 );
$checkss = $nv_Request->get_string( 'checkss', 'get', '' );
if ( $order_id > 0 and $checkss == md5( $order_id . $global_config['sitekey'] . session_id() ) )
{
	$link = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=";
	
	$result = $db->sql_query( "SELECT * FROM `" . $db_config['prefix'] . "_" . $module_data . "_orders` WHERE order_id=" . $order_id );
	$data = $db->sql_fetchrow( $result );
	
	if ( empty( $data ) )
	{
		Header( "Location: " . nv_url_rewrite( NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=cart", true ) );
		die();
	}
	
	$listid = explode( "|", $data['listid'] );
	$listnum = explode( "|", $data['listnum'] );
	$listprice = explode( "|", $data['listprice'] );
	$temppro = array();
	
	$i = 0;
	foreach ( $listid as $proid )
	{
		if ( empty( $listprice[$i] ) ) $listprice[$i] = 0;
		if ( empty( $listnum[$i] ) ) $listnum[$i] = 0;
		
		$temppro[$proid] = array( 
			"price" => $listprice[$i],
			"num" => $listnum[$i] 
		);
		
		$arrayid[] = $proid;
		$i ++;
	}
	
	if ( ! empty( $arrayid ) )
	{
		$templistid = implode( ",", $arrayid );
		
		$sql = "SELECT t1.id, t1.listcatid, t1.publtime, t1." . NV_LANG_DATA . "_title, t1." . NV_LANG_DATA . "_alias, t1." . NV_LANG_DATA . "_note, t1." . NV_LANG_DATA . "_hometext, t2." . NV_LANG_DATA . "_title, t1.money_unit  FROM `" . $db_config['prefix'] . "_" . $module_data . "_rows` AS t1 LEFT JOIN `" . $db_config['prefix'] . "_" . $module_data . "_units` AS t2 ON t1.product_unit = t2.id WHERE  t1.id IN (" . $templistid . ")  AND t1.status=1";
		
		$result = $db->sql_query( $sql );
		while ( list( $id, $listcatid, $publtime, $title, $alias, $note, $hometext, $unit, $money_unit ) = $db->sql_fetchrow( $result ) )
		{
			$data_pro[] = array( 
				"id" => $id,
				"publtime" => $publtime,
				"title" => $title,
				"alias" => $alias,
				"product_note" => $note,
				"hometext" => $hometext,
				"product_price" => $temppro[$id]['price'],
				"product_unit" => $unit,
				"money_unit" => $money_unit,
				"link_pro" => $link . $global_array_cat[$listcatid]['alias'] . "/" . $alias . "-" . $id,
				"product_number" => $temppro[$id]['num'] 
			);
		}
	}
	
	// Xay dung cac url thanh toan truc tuyen
	$url_checkout = array();
	$intro_pay = "";
	
	if ( intval( $data['transaction_status'] ) == - 1 )
	{
		$intro_pay = $lang_module['payment_none_pay'];
	}
	elseif ( $data['transaction_status'] == 0 )
	{
		$sql = "SELECT * FROM `" . $db_config['prefix'] . "_" . $module_data . "_payment` WHERE `active`=1 ORDER BY `weight` ASC";
		$result = $db->sql_query( $sql );
		
		while ( $row = $db->sql_fetchrow( $result ) )
		{
			if ( file_exists( NV_ROOTDIR . "/modules/" . $module_file . "/payment/" . $row['payment'] . ".checkout_url.php" ) )
			{
				$payment_config = unserialize( nv_base64_decode( $row['config'] ) );
				$payment_config['paymentname'] = $row['paymentname'];
				$payment_config['domain'] = $row['domain'];
				
				$images_button = $row['images_button'];
				
				$url = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $op . "&amp;order_id=" . $order_id . "&amp;payment=" . $row['payment'] . "&amp;checksum=" . md5( $order_id . $row['payment'] . $global_config['sitekey'] . session_id() );
				
				if ( ! empty( $images_button ) and file_exists( NV_UPLOADS_REAL_DIR . "/" . $module_name . "/" . $images_button ) )
				{
					$images_button = NV_BASE_SITEURL . NV_UPLOADS_DIR . "/" . $module_name . "/" . $images_button;
				}
				
				$url_checkout[] = array( 
					"name" => $row['paymentname'],
					"url" => $url,
					"images_button" => $images_button 
				);
			}
		}
		
		// Get content intro 
		$content_file = NV_ROOTDIR . '/' . NV_DATADIR . '/' . NV_LANG_DATA . '_' . $module_data . '_content.txt';
		if ( ! empty( $url_checkout ) and file_exists( $content_file ) )
		{
			$intro_pay = file_get_contents( $content_file );
			$intro_pay = nv_editor_br2nl( $intro_pay );
		}
	}
	elseif ( $data['transaction_status'] == 1 and $data['transaction_id'] > 0 )
	{
		list( $payment ) = $db->sql_fetchrow( $db->sql_query( "SELECT `payment` FROM `" . $db_config['prefix'] . "_" . $module_data . "_transaction` WHERE `transaction_id`=" . $data['transaction_id'] ) );
		$config = $db->sql_fetchrow( $db->sql_query( "SELECT * FROM `" . $db_config['prefix'] . "_" . $module_data . "_payment` WHERE `payment` = '" . $payment . "'" ) );
		$intro_pay = sprintf( $lang_module['order_by_payment'], $config['domain'], $config['paymentname'] );
	}
	if ( $data['transaction_status'] == 4 )
	{
		$data['transaction_name'] = $lang_module['history_payment_yes'];
	}
	elseif ( $data['transaction_status'] == 3 )
	{
		$data['transaction_name'] = $lang_module['history_payment_cancel'];
	}
	elseif ( $data['transaction_status'] == 2 )
	{
		$data['transaction_name'] = $lang_module['history_payment_check'];
	}
	elseif ( $data['transaction_status'] == 1 )
	{
		$data['transaction_name'] = $lang_module['history_payment_send'];
	}
	elseif ( $data['transaction_status'] == 0 )
	{
		$data['transaction_name'] = $lang_module['history_payment_no'];
	}
	elseif ( $data['transaction_status'] == - 1 )
	{
		$data['transaction_name'] = $lang_module['history_payment_wait'];
	}
	else
	{
		$data['transaction_name'] = "ERROR";
	}
	
	$contents = call_user_func( "payment", $data, $data_pro, $url_checkout, $intro_pay );
	
	include ( NV_ROOTDIR . "/includes/header.php" );
	echo nv_site_theme( $contents );
	include ( NV_ROOTDIR . "/includes/footer.php" );
}
elseif ( $order_id > 0 and $nv_Request->isset_request( 'payment', 'get' ) and $nv_Request->isset_request( 'checksum', 'get' ) )
{
	$checksum = $nv_Request->get_string( 'checksum', 'get' );
	$payment = $nv_Request->get_string( 'payment', 'get' );
	
	$result = $db->sql_query( "SELECT * FROM `" . $db_config['prefix'] . "_" . $module_data . "_orders` WHERE order_id=" . $order_id );
	$data = $db->sql_fetchrow( $result );
	
	if ( isset( $data['transaction_status'] ) and intval( $data['transaction_status'] ) == 0 and preg_match( "/^[a-zA-Z0-9]+$/", $payment ) and $checksum == md5( $order_id . $payment . $global_config['sitekey'] . session_id() ) and file_exists( NV_ROOTDIR . "/modules/" . $module_file . "/payment/" . $payment . ".checkout_url.php" ) )
	{
		$config = $db->sql_fetchrow( $db->sql_query( "SELECT * FROM `" . $db_config['prefix'] . "_" . $module_data . "_payment` WHERE `payment` = '" . $payment . "'" ) );
		$payment_config = unserialize( nv_base64_decode( $config['config'] ) );
		
		// Cap nhat cong thanh toan
		$transaction_status = 1;
		$payment_id = 0;
		$payment_amount = 0;
		$payment_data = "";
		
		$userid = ( defined( 'NV_IS_USER' ) ) ? $user_info['userid'] : 0;
		
		$transaction_id = $db->sql_query_insert_id( "INSERT INTO `" . $db_config['prefix'] . "_" . $module_data . "_transaction` (`transaction_id`, `transaction_time`, `transaction_status`, `order_id`, `userid`, `payment`, `payment_id`, `payment_time`, `payment_amount`, `payment_data`) VALUES (NULL, UNIX_TIMESTAMP(), '" . $transaction_status . "', '" . $order_id . "', '" . $userid . "', '" . $payment . "', '" . $payment_id . "', UNIX_TIMESTAMP(), '" . $payment_amount . "', '" . $payment_data . "')" );
		
		$db->sql_query( "UPDATE `" . $db_config['prefix'] . "_" . $module_data . "_orders` SET transaction_status=" . $transaction_status . " , transaction_id = " . $transaction_id . " , transaction_count = 1 WHERE `order_id`=" . $order_id );
		
		require_once ( NV_ROOTDIR . "/modules/" . $module_file . "/payment/" . $payment . ".checkout_url.php" );
	}
	else if ( $db->sql_numrows( $result ) > 0 )
	{
		$url = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=" . $op . "&order_id=" . $order_id . "&checkss=" . md5( $order_id . $global_config['sitekey'] . session_id() );
	}
	else
	{
		$url = NV_BASE_SITEURL;
	}
	Header( "Location: " . $url );
	die();
}
else
{
	Header( "Location: " . NV_BASE_SITEURL );
	die();
}

?>