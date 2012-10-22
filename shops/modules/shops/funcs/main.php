<?php

/**
 * @Project NUKEVIET 3.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES., JSC. All rights reserved
 * @Createdate 3-6-2010 0:14
 */

if ( ! defined( 'NV_IS_MOD_SHOPS' ) ) die( 'Stop!!!' );

$page_title = $module_info['custom_title'];
$key_words = $module_info['keywords'];

$data_content = array();
$base_url = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name;
$html_pages = "";

if( $pro_config['home_view'] == "view_home_all" )
{
	$sql = "SELECT SQL_CALC_FOUND_ROWS `id`, `listcatid`, `publtime`, `" . NV_LANG_DATA . "_title`, `" . NV_LANG_DATA . "_alias`, `" . NV_LANG_DATA . "_hometext`, `" . NV_LANG_DATA . "_address`, `homeimgalt`, `homeimgthumb`, `product_price`, `product_discounts`, `money_unit`, `showprice` FROM `" . $db_config['prefix'] . "_" . $module_data . "_rows` WHERE `inhome`=1 AND `status`=1 AND `publtime` < " . NV_CURRENTTIME . " AND (`exptime`=0 OR `exptime`>" . NV_CURRENTTIME . ") ORDER BY `id` DESC LIMIT " . ( ( $page - 1 ) * $per_page ) . "," . $per_page;
	
	$result = $db->sql_query( $sql );
	list( $all_page ) = $db->sql_fetchrow( $db->sql_query( "SELECT FOUND_ROWS()" ) );
	
	while ( list( $id, $listcatid, $publtime, $title, $alias, $hometext, $address, $homeimgalt, $homeimgthumb, $product_price, $product_discounts, $money_unit, $showprice ) = $db->sql_fetchrow( $result ) )
	{
		$thumb = explode( "|", $homeimgthumb );
		if ( ! empty( $thumb[0] ) and ! nv_is_url( $thumb[0] ) )
		{
			$thumb[0] = NV_BASE_SITEURL . NV_UPLOADS_DIR . "/" . $module_name . "/" . $thumb[0];
		}
		else
		{
			$thumb[0] = NV_BASE_SITEURL . "themes/" . $module_info['template'] . "/images/" . $module_file . "/no-image.jpg";
		}
		$data_content[] = array( 
			"id" => $id,
			"publtime" => $publtime,
			"title" => $title,
			"alias" => $alias,
			"hometext" => $hometext,
			"address" => $address,
			"homeimgalt" => $homeimgalt,
			"homeimgthumb" => $thumb[0],
			"product_price" => $product_price,
			"product_discounts" => $product_discounts,
			"money_unit" => $money_unit,
			"showprice" => $showprice,
			"link_pro" => NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $global_array_cat[$listcatid]['alias'] . "/" . $alias . "-" . $id,
			"link_order" => NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=setcart&amp;id=" . $id,
		);
	}
	
	if( empty( $data_content ) and $page > 1 )
	{
		Header( "Location: " . nv_url_rewrite( NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name, true ) );
		exit();
	}
	
	$html_pages = nv_alias_page( $page_title, $base_url, $all_page, $per_page, $page );
}
elseif( $pro_config['home_view'] == "view_home_cat" )
{
	foreach ( $global_array_cat as $catid_i => $array_info_i )
	{
		if ( $array_info_i['parentid'] == 0 )
		{
			$array_cat = array();
			$array_cat = GetCatidInParent( $catid_i );
			
			$sql = "SELECT SQL_CALC_FOUND_ROWS `id`, `publtime`, `" . NV_LANG_DATA . "_title`, `" . NV_LANG_DATA . "_alias`, `" . NV_LANG_DATA . "_hometext`, `" . NV_LANG_DATA . "_address`, `homeimgalt`, `homeimgthumb`, `product_price`, `product_discounts`, `money_unit`, `showprice` FROM `" . $db_config['prefix'] . "_" . $module_data . "_rows` WHERE `listcatid` IN (" . implode( ",", $array_cat ) . ") AND `inhome`=1 AND `status`=1 AND `publtime` < " . NV_CURRENTTIME . " AND ( `exptime`=0 OR `exptime` > " . NV_CURRENTTIME . ") ORDER BY `id` DESC LIMIT 0," . $array_info_i['numlinks'];
		
			$result = $db->sql_query( $sql );
			list( $num_pro ) = $db->sql_fetchrow( $db->sql_query( "SELECT FOUND_ROWS()" ) );
			
			$data_pro = array();
			
			while ( list( $id, $publtime, $title, $alias, $hometext, $address, $homeimgalt, $homeimgthumb, $product_price, $product_discounts, $money_unit, $showprice ) = $db->sql_fetchrow( $result ) )
			{
				$thumb = explode( "|", $homeimgthumb );
				if ( ! empty( $thumb[0] ) and ! nv_is_url( $thumb[0] ) )
				{
					$thumb[0] = NV_BASE_SITEURL . NV_UPLOADS_DIR . "/" . $module_name . "/" . $thumb[0];
				}
				else
				{
					$thumb[0] = NV_BASE_SITEURL . "themes/" . $module_info['template'] . "/images/" . $module_file . "/no-image.jpg";
				}
				
				$data_pro[] = array( 
					"id" => $id,
					"publtime" => $publtime,
					"title" => $title,
					"alias" => $alias,
					"hometext" => $hometext,
					"address" => $address,
					"homeimgalt" => $homeimgalt,
					"homeimgthumb" => $thumb[0],
					"product_price" => $product_price,
					"product_discounts" => $product_discounts,
					"money_unit" => $money_unit,
					"showprice" => $showprice,
					"link_pro" => NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $global_array_cat[$catid_i]['alias'] . "/" . $alias . "-" . $id,
					"link_order" => NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=setcart&amp;id=" . $id,
				);
			}
			
			$data_content[] = array(
				"catid" => $catid_i,
				"title" => $array_info_i['title'],
				"link" => $array_info_i['link'],
				'data' => $data_pro,
				'num_pro' => $num_pro,
				"num_link" => $array_info_i['numlinks'],
			);
		}
	}
	
	if( $page > 1 )
	{
		Header( "Location: " . nv_url_rewrite( NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name, true ) );
		exit();
	}
}
else
{
	include ( NV_ROOTDIR . "/includes/header.php" );
	echo nv_site_theme( "" );
	include ( NV_ROOTDIR . "/includes/footer.php" );
}

if( $page > 1 )
{
	$page_title .= ' ' . NV_TITLEBAR_DEFIS . ' ' . $lang_global['page'] . ' ' . $page;
	$description .= ' ' . $page;
}

$contents = call_user_func( $pro_config['home_view'], $data_content, $html_pages );

include ( NV_ROOTDIR . "/includes/header.php" );
echo nv_site_theme( $contents );
include ( NV_ROOTDIR . "/includes/footer.php" );

?>