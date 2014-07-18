<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES., JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 3-6-2010 0:14
 */

if( ! defined( 'NV_IS_MOD_SHOPS' ) ) die( 'Stop!!!' );

$data_content = array();
$link = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=';

if( $nv_Request->get_int( 'save', 'post', 0 ) == 1 )
{
	// Set cart to order
	$listproid = $nv_Request->get_array( 'listproid', 'post', '' );
	if( ! empty( $listproid ) )
	{
		foreach( $listproid as $pro_id => $number )
		{
			if( ! empty( $_SESSION[$module_data . '_cart'][$pro_id] ) and $number >= 0 )
			{
				$_SESSION[$module_data . '_cart'][$pro_id]['num'] = $number;
			}
		}
	}
}

$array_error_product_number = array();

if( ! empty( $_SESSION[$module_data . '_cart'] ) )
{
	$arrayid = array();
	foreach( $_SESSION[$module_data . '_cart'] as $pro_id => $pro_info )
	{
		$arrayid[] = $pro_id;
	}
	if( ! empty( $arrayid ) )
	{
		$listid = implode( ',', $arrayid );

		$sql = 'SELECT t1.id, t1.listcatid, t1.publtime, t1.' . NV_LANG_DATA . '_title, t1.' . NV_LANG_DATA . '_alias, t1.' . NV_LANG_DATA . '_hometext, t1.homeimgalt, t1.homeimgfile, t1.homeimgthumb, t1.product_number, t1.product_price, t1.discount_id, t2.' . NV_LANG_DATA . '_title, t1.money_unit FROM ' . $db_config['prefix'] . '_' . $module_data . '_rows AS t1 LEFT JOIN ' . $db_config['prefix'] . '_' . $module_data . '_units AS t2 ON t1.product_unit = t2.id WHERE t1.id IN (' . $listid . ') AND t1.status =1';
		$result = $db->query( $sql );

		while( list( $id, $listcatid, $publtime, $title, $alias, $hometext, $homeimgalt, $homeimgfile, $homeimgthumb, $product_number, $product_price, $discount_id, $unit, $money_unit ) = $result->fetch( 3 ) )
		{
			if( $homeimgthumb == 1 )//image thumb
			{
				$thumb = NV_BASE_SITEURL . NV_FILES_DIR . '/' . $module_name . '/' . $homeimgfile;
			}
			elseif( $homeimgthumb == 2 )//image file
			{
				$thumb = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_name . '/' . $homeimgfile;
			}
			elseif( $homeimgthumb == 3 )//image url
			{
				$thumb = $homeimgfile;
			}
			else//no image
			{
				$thumb = NV_BASE_SITEURL . 'themes/' . $module_info['template'] . '/images/' . $module_file . '/no-image.jpg';
			}
			
			$group = $_SESSION[$module_data . '_cart'][$id]['group'];

			$number = $_SESSION[$module_data . '_cart'][$id]['num'];
			if( $number > $product_number and $number > 0 and empty( $pro_config['active_order_number'] ) )
			{
				$number = $_SESSION[$module_data . '_cart'][$id]['num'] = $product_number;
				$array_error_product_number[] = sprintf( $lang_module['product_number_max'], $title, $product_number );
			}

			if( $pro_config['active_price'] == '0' )
			{
				$discount_id = $product_price = 0;
			}

			$data_content[] = array(
				'id' => $id,
				'publtime' => $publtime,
				'title' => $title,
				'alias' => $alias,
				'hometext' => $hometext,
				'homeimgalt' => $homeimgalt,
				'homeimgthumb' => $thumb,
				'product_price' => $product_price,
				'discount_id' => $discount_id,
				'product_unit' => $unit,
				'money_unit' => $money_unit,
				'group' => $group,
				'link_pro' => $link . $global_array_cat[$listcatid]['alias'] . '/' . $alias . '-' . $id . $global_config['rewrite_exturl'],
				'num' => $number,
				'link_remove' => $link . 'remove&id=' . $id
			);
			$_SESSION[$module_data . '_cart'][$id]['order'] = 1;
		}

		if( empty( $array_error_product_number ) and $nv_Request->isset_request( 'cart_order', 'post' ) )
		{
			Header( 'Location: ' . nv_url_rewrite( NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=order', true ) );
			exit();
		}
	}
}
else
{
	Header( 'Location: ' . nv_url_rewrite( NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name, true ) );
	exit();
}

$page_title = $lang_module['cart_title'];

$contents = call_user_func( 'cart_product', $data_content, $array_error_product_number );

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme( $contents );
include NV_ROOTDIR . '/includes/footer.php';