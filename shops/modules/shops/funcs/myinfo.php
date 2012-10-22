<?php

/**
 * @Project NUKEVIET 3.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES., JSC. All rights reserved
 * @Createdate 3-6-2010 0:14
 */

if ( ! defined( 'NV_IS_MOD_SHOPS' ) ) die( 'Stop!!!' );
if ( ! defined( 'NV_IS_AJAX' ) ) die( 'Wrong URL' );

if ( ! isset( $_SESSION[$module_data . '_cart'] ) ) $_SESSION[$module_data . '_cart'] = array();

$id = $nv_Request->get_int( 'id', 'post,get', 1 );
$num = $nv_Request->get_string( 'num', 'post,get', 1 );
$ac = $nv_Request->get_string( 'ac', 'post,get', 0 );

if ( ! is_numeric( $num ) || $num <= 0 )
{
	die( 'ERR_' . $lang_module['cart_set_err'] );
}

if ( $ac == 0 )
{
	if ( $id > 0 )
	{
		$result = $db->sql_query( "SELECT * FROM `" . $db_config['prefix'] . "_" . $module_data . "_rows` WHERE `id` = " . $id );
		$data_content = $db->sql_fetchrow( $result, 2 );
		$price_product_discounts = $data_content['product_price'] - ( $data_content['product_price'] * ( $data_content['product_discounts'] / 100 ) );
		
		if ( $num > $data_content['product_number'] )
		{
			die( 'ERR_' . $lang_module['cart_set_err_num'] );
		}
		
		if ( ! isset( $_SESSION[$module_data . '_cart'][$id] ) )
		{
			$_SESSION[$module_data . '_cart'][$id] = array(
				'num' => $num,
				'order' => 0,
				'price' => $price_product_discounts 
			);
		}
		else
		{
			if ( ( $_SESSION[$module_data . '_cart'][$id]['num'] + $num ) > $data_content['product_number'] )
			{
				die( 'ERR_' . $lang_module['cart_set_err_num'] );
			}
			$_SESSION[$module_data . '_cart'][$id]['num'] = $_SESSION[$module_data . '_cart'][$id]['num'] + $num;
		}
		$contents = sprintf( $lang_module['set_cart_success'], $data_content[NV_LANG_DATA . '_title'] );
		echo 'OK_' . $contents;
	}
	else
	{
		$chk = $nv_Request->get_string( 'chk', 'post', 0 );
		$listid = explode( ",", $chk );
		$i = 0;
		foreach ( $listid as $id )
		{
			$result = $db->sql_query( "SELECT * FROM `" . $db_config['prefix'] . "_" . $module_data . "_rows` WHERE `id` = " . $id . "" );
			$data_content = $db->sql_fetchrow( $result );
			if ( ! isset( $_SESSION[$module_data . '_cart'][$id] ) ) $_SESSION[$module_data . '_cart'][$id] = array( 
				'num' => $num, 'order' => 0, 'price' => $data_content['product_price'] 
			);
			else
			{
				$_SESSION[$module_data . '_cart'][$id]['num'] = $_SESSION[$module_data . '_cart'][$id]['num'] + $num;
			}
			$i ++;
		}
		$contents = sprintf( $lang_module['set_cart_success'], ( $i ) );
		echo 'OK_' . $contents;
	}
}
else
{
	if ( $id > 0 )
	{
		$result = $db->sql_query( "SELECT * FROM `" . $db_config['prefix'] . "_" . $module_data . "_rows` WHERE `id` = " . $id . "" );
		$data_content = $db->sql_fetchrow( $result, 2 );
		if ( $num > $data_content['product_number'] )
		{
			die( 'ERR_' . $lang_module['cart_set_err_num'] );
		}
		if ( isset( $_SESSION[$module_data . '_cart'][$id] ) ) $_SESSION[$module_data . '_cart'][$id]['num'] = $num;
		echo 'OK_' . $lang_module['cart_set_ok'] . $num;
	}
}

?>