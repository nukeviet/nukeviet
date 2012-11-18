<?php

/**
 * @Project NUKEVIET 3.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES., JSC. All rights reserved
 * @Createdate 3-6-2010 0:14
 */

if ( ! defined( 'NV_IS_MOD_SHOPS' ) ) die( 'Stop!!!' );

if( empty( $catid ) )
{
	Header( "Location: " . nv_url_rewrite( NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name, true ) );
	exit();
}

$page_title = $global_array_cat[$catid]['title'];
$key_words = $global_array_cat[$catid]['keywords'];
$description = $global_array_cat[$catid]['description'];

$contents = "";
$cache_file = "";

if( ! defined( 'NV_IS_MODADMIN' ) and $page < 5 )
{
	$cache_file = NV_LANG_DATA . "_" . $module_name . "_" . $module_info['template'] . "_" . $op . "_" . $catid . "_" . $page . "_" . NV_CACHE_PREFIX . ".cache";
	if( ( $cache = nv_get_cache( $cache_file ) ) != false )
	{
		$contents = $cache;
	}
}

if( empty( $contents ) )
{
	$data_content = array();

	$count = 0;
	$link = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=";
	$base_url = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $global_array_cat[$catid]['alias'];

	if ( $global_array_cat[$catid]['viewcat'] == "view_home_cat" and $global_array_cat[$catid]['numsubcat'] > 0 )
	{
		$data_content = array();
		$array_subcatid = explode( ",", $global_array_cat[$catid]['subcatid'] );
		
		foreach ( $array_subcatid as $catid_i )
		{
			$array_info_i = $global_array_cat[$catid_i];
			
			$array_cat = array();
			$array_cat = GetCatidInParent( $catid_i );
			
			$sql = "SELECT SQL_CALC_FOUND_ROWS `id`, `publtime`, `" . NV_LANG_DATA . "_title`, `" . NV_LANG_DATA . "_alias`, `" . NV_LANG_DATA . "_hometext`, `" . NV_LANG_DATA . "_address`, `homeimgalt`, `homeimgthumb`, `product_code`, `product_price`, `product_discounts`, `money_unit`, `showprice` FROM `" . $db_config['prefix'] . "_" . $module_data . "_rows` WHERE `listcatid` IN (" . implode( ",", $array_cat ) . ") AND `status`=1 ORDER BY `id` DESC LIMIT 0," . $array_info_i['numlinks'];
			$result = $db->sql_query( $sql );
			
			$data_pro = array();
			list( $num_pro ) = $db->sql_fetchrow( $db->sql_query( "SELECT FOUND_ROWS()" ) );
			
			while ( list( $id, $publtime, $title, $alias, $hometext, $address, $homeimgalt, $homeimgthumb, $product_code, $product_price, $product_discounts, $money_unit,$showprice ) = $db->sql_fetchrow( $result ) )
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
					"product_code" => $product_code,
					"product_price" => $product_price,
					"product_discounts" => $product_discounts,
					"money_unit" => $money_unit,
					"showprice" => $showprice,
					"link_pro" => $link . $global_array_cat[$catid_i]['alias'] . "/" . $alias . "-" . $id,
					"link_order" => $link . "setcart&amp;id=" . $id 
				);
			}
			$data_content[] = array( 
				"catid" => $catid_i,
				"title" => $array_info_i['title'],
				"link" => $array_info_i['link'],
				"data" => $data_pro,
				"num_pro" => $num_pro,
				"num_link" => $array_info_i['numlinks'] 
			);
		}
		
		if( $page > 1 )
		{
			Header( "Location: " . nv_url_rewrite( NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name, true ) );
			exit();
		}
		
		$contents = call_user_func( 'view_home_cat', $data_content );
	}
	else
	{
		$sql = "SELECT SQL_CALC_FOUND_ROWS `id`, `listcatid`, `publtime`, `" . NV_LANG_DATA . "_title`, `" . NV_LANG_DATA . "_alias`, `" . NV_LANG_DATA . "_hometext`, `" . NV_LANG_DATA . "_address`, `homeimgalt`, `homeimgthumb`, `product_code`, `product_price`, `product_discounts`, `money_unit`, `showprice` FROM `" . $db_config['prefix'] . "_" . $module_data . "_rows` WHERE";
		if ( $global_array_cat[$catid]['numsubcat'] == 0 )
		{
			$sql .= " `listcatid`=" . $catid;
		}
		else
		{
			$array_cat = array();
			$array_cat = GetCatidInParent( $catid );
			$sql .= " `listcatid` IN (" . implode( ",", $array_cat ) . ")";
		}
		$sql .= " AND `status`=1 ORDER BY `id` DESC LIMIT " . ( ( $page - 1 ) * $per_page ) . "," . $per_page;
		$result = $db->sql_query( $sql );

		list( $all_page ) = $db->sql_fetchrow( $db->sql_query( "SELECT FOUND_ROWS()" ) );
		
		$data_content = GetDataIn( $result, $catid );
		$data_content['count'] = $all_page;
		
		$pages = nv_alias_page( $page_title, $base_url, $all_page, $per_page, $page );
		
		if( sizeof( $data_content['data'] ) < 1 and $page > 1 )
		{
			Header( "Location: " . nv_url_rewrite( NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name, true ) );
			exit();
		}
		
		$contents = call_user_func( $global_array_cat[$catid]['viewcat'], $data_content, $pages );
	}
	
	if( ! defined( 'NV_IS_MODADMIN' ) and $contents != "" and $cache_file != "" )
	{
		nv_set_cache( $cache_file, $contents );
	}
}

if( $page > 1 )
{
	$page_title .= ' ' . NV_TITLEBAR_DEFIS . ' ' . $lang_global['page'] . ' ' . $page;
	$description .= ' ' . $page;
}

include ( NV_ROOTDIR . "/includes/header.php" );
echo nv_site_theme( $contents );
include ( NV_ROOTDIR . "/includes/footer.php" );

?>