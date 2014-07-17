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

if( ! isset( $_SESSION[$module_data . '_cart'] ) ) $_SESSION[$module_data . '_cart'] = array();

$id = $nv_Request->get_int( 'id', 'post,get', 1 );
$group = $nv_Request->get_string( 'group', 'post,get', '' );
$num = $nv_Request->get_int( 'num', 'post,get', 1 );
$ac = $nv_Request->get_string( 'ac', 'post,get', 0 );
$contents_msg = "";
//nv_insert_logs( NV_LANG_DATA, $module_name, 'gdhfth', $num, $admin_info['userid'] );
if( ! is_numeric( $num ) || $num < 0 )
{
	$contents_msg = 'ERR_' . $lang_module['cart_set_err'];
}
else
{
	if( $ac == 0 )
	{
		if( $id > 0 )
		{
			$result = $db->query( "SELECT * FROM " . $db_config['prefix'] . "_" . $module_data . "_rows WHERE id = " . $id );
			$data_content = $result->fetch();

			if( $num > $data_content['product_number'] and empty( $pro_config['active_order_number'] ) )
			{
				$contents_msg = 'ERR_' . $lang_module['cart_set_err_num'];
			}
			else
			{
				$update_cart = true;
				if( ! isset( $_SESSION[$module_data . '_cart'][$id] ) )
				{
					$_SESSION[$module_data . '_cart'][$id] = array(
						'num' => $num,
						'order' => 0,
						'price' => $data_content['product_price'],
						'money_unit' => $data_content['money_unit'],
						'discount_id' => $data_content['discount_id'],
						'store' => $data_content['product_number'],
						'group' => $group
					);
				}
				else
				{
					if( ( $_SESSION[$module_data . '_cart'][$id]['num'] + $num ) > $data_content['product_number'] and empty( $pro_config['active_order_number'] ) )
					{
						$contents_msg = 'ERR_' . $lang_module['cart_set_err_num'] . ': ' . $data_content['product_number'];
						$update_cart = false;
					}
					else
					{
						$_SESSION[$module_data . '_cart'][$id]['num'] = $_SESSION[$module_data . '_cart'][$id]['num'] + $num;
					}
				}
				if( $update_cart )
				{
					$title = str_replace( "_", "#@#", $data_content[NV_LANG_DATA . '_title'] );
					$contents = sprintf( $lang_module['set_cart_success'], $title );
					$contents_msg = 'OK_' . $contents;
				}
			}
		}
	}
	else
	{
		if( $id > 0 )
		{
			$result = $db->query( "SELECT * FROM " . $db_config['prefix'] . "_" . $module_data . "_rows WHERE id = " . $id );
			$data_content = $result->fetch();

			if( $num > $data_content['product_number'] and empty( $pro_config['active_order_number'] ) )
			{
				$contents_msg = 'ERR_' . $lang_module['cart_set_err_num'] . ': ' . $data_content['product_number'];
			}
			else
			{
				if( isset( $_SESSION[$module_data . '_cart'][$id] ) ) $_SESSION[$module_data . '_cart'][$id]['num'] = $num;
				$contents_msg = 'OK_' . $lang_module['cart_set_ok'] . $num;
			}
		}
	}
}

include NV_ROOTDIR . '/includes/header.php';
echo nv_unhtmlspecialchars( $contents_msg );
include NV_ROOTDIR . '/includes/footer.php';