<?php

/**
 * @Project NUKEVIET 3.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES., JSC. All rights reserved
 * @Createdate 3-6-2010 0:14
 */

if ( ! defined( 'NV_IS_MOD_SHOPS' ) ) die( 'Stop!!!' );

if ( ! defined( 'NV_IS_USER' ) )
{
	$redirect = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=cart";
	Header( "Location: " . NV_BASE_SITEURL . "index.php?" . NV_NAME_VARIABLE . "=users&" . NV_OP_VARIABLE . "=login&nv_redirect=" . nv_base64_encode( $redirect ) );
	die();
}
$contents = "";

$link1 = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name;
$link = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=";
$action = 0;
$post_order = $nv_Request->get_int( 'postorder', 'post', 0 );
$error = array();

$data_order = array( 
	"user_id" => $user_info["userid"],
	"order_name" => ( ! empty( $user_info["full_name"] ) ) ? $user_info["full_name"] : $user_info["username"],
	"order_email" => $user_info["email"],
	"order_address" => $user_info["location"],
	"order_phone" => $user_info["telephone"],
	"order_note" => "",
	"listid" => "",
	"listnum" => "",
	"listprice" => "",
	"admin_id" => 0,
	"shop_id" => 0,
	"who_is" => 0,
	"unit_total" => $pro_config['money_unit'],
	"order_total" => 0,
	"order_time" => NV_CURRENTTIME 
);

if ( $post_order == 1 )
{
	$listid = "";
	$listnum = "";
	$listprice = "";
	$i = 0;
	$total = 0;
	foreach ( $_SESSION[$module_data . '_cart'] as $pro_id => $info )
	{
		if ( $pro_config['active_price'] == '0' ) { $info['price'] = 0; }
		if ( $_SESSION[$module_data . '_cart'][$pro_id]['order'] == 1 )
		{
			if ( $i == 0 )
			{
				$listid .= $pro_id;
				$listnum .= $info['num'];
				$listprice .= $info['price'];
			}
			else
			{
				$listid .= "|" . $pro_id;
				$listnum .= "|" . $info['num'];
				$listprice .= "|" . $info['price'];
			}
			$total = $total + ( ( int )$info['num'] * ( double )$info['price'] );
			$i ++;
		}
	}
	
	$data_order['order_name'] = filter_text_input( 'order_name', 'post', '', 1, 200 );
	$data_order['order_email'] = filter_text_input( 'order_email', 'post', '', 1, 250 );
	$data_order['order_address'] = filter_text_input( 'order_address', 'post', '', 1 );
	$data_order['order_phone'] = filter_text_input( 'order_phone', 'post', '', 1, 20 );
	$data_order['order_note'] = filter_text_input( 'order_note', 'post', '', 1, 2000 );
	$check = $nv_Request->get_int( 'check', 'post', 0 );
	
	$data_order['listid'] = $listid;
	$data_order['listnum'] = $listnum;
	$data_order['listprice'] = $listprice;
	$data_order['order_total'] = $total;

	if ( empty( $data_order['order_name'] ) ) $error['order_name'] = $lang_module['order_name_err'];
	elseif ( nv_check_valid_email( $data_order['order_email'] ) != "" ) $error['order_email'] = $lang_module['order_email_err'];
	elseif ( empty( $data_order['order_phone'] ) ) $error['order_phone'] = $lang_module['order_phone_err'];
	elseif ( empty( $data_order['order_address'] ) ) $error['order_address'] = $lang_module['order_address_err'];
	elseif ( $check == 0 ) $error['order_check'] = $lang_module['order_check_err'];
	
	if ( empty( $error ) and $i > 0 )
	{
		$result = $db->sql_query( "SHOW TABLE STATUS WHERE `Name`='" . $db_config['prefix'] . "_" . $module_data . "_orders'" );
		$item = $db->sql_fetch_assoc( $result );
		$db->sql_freeresult( $result );
		
		$order_code = vsprintf( $pro_config['format_order_id'], $item['Auto_increment'] );
		$transaction_status = ( empty( $pro_config['auto_check_order'] ) ) ? - 1 : 0;
		
		$sql = "INSERT INTO `" . $db_config['prefix'] . "_" . $module_data . "_orders` (
			`order_id`, `lang`, `order_code`, `order_name`, `order_email`, `order_address`, `order_phone`, `order_note`, `listid`, `listnum`, `listprice`, 
			`user_id`, `admin_id`, `shop_id`, `who_is`, `unit_total`, `order_total`, `order_time`, `postip`, `view`, 
			`transaction_status`, `transaction_id`, `transaction_count`
		) VALUES (
			NULL , '" . NV_LANG_DATA . "', " . $db->dbescape_string( $order_code ) . ", " . $db->dbescape_string( $data_order['order_name'] ) . ", " . $db->dbescape_string( $data_order['order_email'] ) . ", 
			" . $db->dbescape_string( $data_order['order_address'] ) . "," . $db->dbescape_string( $data_order['order_phone'] ) . ", 
			" . $db->dbescape_string( $data_order['order_note'] ) . ", " . $db->dbescape_string( $data_order['listid'] ) . ", 
			" . $db->dbescape_string( $data_order['listnum'] ) . ", " . $db->dbescape_string( $data_order['listprice'] ) . ", 
			" . intval( $data_order['user_id'] ) . ", " . intval( $data_order['admin_id'] ) . ", " . intval( $data_order['shop_id'] ) . ", 
			" . intval( $data_order['who_is'] ) . ", " . $db->dbescape_string( $data_order['unit_total'] ) . ", " . doubleval( $data_order['order_total'] ) . ", 
			" . intval( $data_order['order_time'] ) . "," . $db->dbescape( $client_info['ip'] ) . " ,0," . $transaction_status . ",0,0
		)";
		
		$order_id = $db->sql_query_insert_id( $sql );
		
		if ( $order_id > 0 )
		{
			// Neu tat chuc nang dat hang vo han thi tru so sp trong kho
			if ( $pro_config['active_order_number'] == '0' )
			{
				product_number_order( $data_order['listid'], $data_order['listnum'] );
			}

			$order_code2 = vsprintf( $pro_config['format_order_id'], $order_id );
			if ( $order_code != $order_code2 )
			{
				$db->sql_query( "UPDATE `" . $db_config['prefix'] . "_" . $module_data . "_orders` SET `order_code`=" . $db->dbescape_string( $order_code2 ) . "  WHERE `order_id`=" . $order_id );
			}
			
			// Gui email thong bao don hang
			$data_order['id'] = $order_id;
			$data_order['order_code'] = $order_code2;
			
			// Thong tin san pham dat hang
			$listid = explode( "|", $data_order['listid'] );
			$listnum = explode( "|", $data_order['listnum'] );
			$listprice = explode( "|", $data_order['listprice'] );
			$data_pro = array();
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
						"product_number" => $temppro[$id]['num'] 
					);
				}
			}
			
			$lang_module['order_email_noreply'] = sprintf( $lang_module['order_email_noreply'], $global_config['site_url'], $global_config['site_url'] );
			$lang_module['order_email_thanks'] = sprintf( $lang_module['order_email_thanks'], $global_config['site_url'] );
			$email_contents = call_user_func( "email_new_order", $data_order, $data_pro );
			
			nv_sendmail( array( $global_config['site_name'], $global_config['site_email'] ), $data_order['order_email'], sprintf( $lang_module['order_email_title'], $module_info['custom_title'], $data_order['order_code'] ), $email_contents );
			
			// Chuyen trang xem thong tin don hang vua dat
			$checkss = md5( $order_id . $global_config['sitekey'] . session_id() );
			unset( $_SESSION[$module_data . '_cart'] );
			
			Header( "Location: " . NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=payment&order_id=" . $order_id . "&checkss=" . $checkss );
			
			$action = 1;
		}
	}
}

if ( $action == 0 )
{
	$page_title = $lang_module['cart_check_cart'];

	$i = 0;
	$arrayid = array();
	foreach ( $_SESSION[$module_data . '_cart'] as $pro_id => $pro_info )
	{
		$arrayid[] = $pro_id;
	}
	
	if ( ! empty( $arrayid ) )
	{
		$listid = implode( ",", $arrayid );
		
		$sql = "SELECT t1.id, t1.listcatid, t1.publtime, t1." . NV_LANG_DATA . "_title, t1." . NV_LANG_DATA . "_alias, t1." . NV_LANG_DATA . "_note, t1." . NV_LANG_DATA . "_hometext, t1.homeimgalt, t1.homeimgthumb, t1.product_price,t1.product_discounts,t2." . NV_LANG_DATA . "_title, t1.money_unit  FROM `" . $db_config['prefix'] . "_" . $module_data . "_rows` AS t1 LEFT JOIN `" . $db_config['prefix'] . "_" . $module_data . "_units` AS t2 ON t1.product_unit = t2.id WHERE  t1.id IN (" . $listid . ") AND t1.status=1";
		$result = $db->sql_query( $sql );
		
		while ( list( $id, $listcatid, $publtime, $title, $alias, $note, $hometext, $homeimgalt, $homeimgthumb, $product_price, $product_discounts, $unit, $money_unit ) = $db->sql_fetchrow( $result ) )
		{
			$thumb = explode( "|", $homeimgthumb );
			if ( ! empty( $thumb[0] ) && ! nv_is_url( $thumb[0] ) )
			{
				$thumb[0] = NV_BASE_SITEURL . NV_UPLOADS_DIR . "/" . $module_name . "/" . $thumb[0];
			}
			else
			{
				$thumb[0] = NV_BASE_SITEURL . "themes/" . $module_info['template'] . "/images/" . $module_file . "/no-image.jpg";
			}
			if ( $pro_config['active_price'] == '0' )
			{
				$product_discounts = $product_price = 0;
			}
			
			$data_content[] = array( 
				"id" => $id,
				"publtime" => $publtime,
				"title" => $title,
				"alias" => $alias,
				"note" => $note,
				"hometext" => $hometext,
				"homeimgalt" => $homeimgalt,
				"homeimgthumb" => $thumb[0],
				"product_price" => $product_price,
				"product_discounts" => $product_discounts,
				"product_unit" => $unit,
				"money_unit" => $money_unit,
				"link_pro" => $link . $global_array_cat[$listcatid]['alias'] . "/" . $alias . "-" . $id,
				"num" => $_SESSION[$module_data . '_cart'][$id]['num'] 
			);
			$i ++;
		}
	}
	
	if ( $i == 0 )
	{
		Header( "Location: " . nv_url_rewrite( NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=cart", true ) );
		exit();
	}
	else
	{
		$contents = call_user_func( "uers_order", $data_content, $data_order, $error );
	}
}

include ( NV_ROOTDIR . "/includes/header.php" );
echo nv_site_theme( $contents );
include ( NV_ROOTDIR . "/includes/footer.php" );

?>