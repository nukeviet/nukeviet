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

$contents = "";
$cache_file = "";

if( ! defined( 'NV_IS_MODADMIN' ) and $page < 5 )
{
	$cache_file = NV_LANG_DATA . "_" . $module_name . "_" . $module_info['template'] . "_" . $op . "_" . $page . "_" . NV_CACHE_PREFIX . ".cache";
	if( ( $cache = nv_get_cache( $cache_file ) ) != false )
	{
		$contents = $cache;
	}
}

if( empty( $contents ) )
{
	$data_content = array();
	$base_url = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name;
	$html_pages = "";

	if( $pro_config['home_view'] == "view_home_all" )
	{
		$sql = "SELECT SQL_CALC_FOUND_ROWS `id`, `listcatid`, `publtime`, `" . NV_LANG_DATA . "_title`, `" . NV_LANG_DATA . "_alias`, `" . NV_LANG_DATA . "_hometext`, `" . NV_LANG_DATA . "_address`, `homeimgalt`, `homeimgthumb`, `product_code`, `product_price`, `product_discounts`, `money_unit`, `showprice` FROM `" . $db_config['prefix'] . "_" . $module_data . "_rows` WHERE `inhome`=1 AND `status`=1 ORDER BY `id` DESC LIMIT " . ( ( $page - 1 ) * $per_page ) . "," . $per_page;
		
		$result = $db->sql_query( $sql );
		list( $all_page ) = $db->sql_fetchrow( $db->sql_query( "SELECT FOUND_ROWS()" ) );
		
		while ( list( $id, $listcatid, $publtime, $title, $alias, $hometext, $address, $homeimgalt, $homeimgthumb, $product_code, $product_price, $product_discounts, $money_unit, $showprice ) = $db->sql_fetchrow( $result ) )
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
				"product_code" => $product_code,
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
			if ( $array_info_i['parentid'] == 0 and $array_info_i['inhome'] != 0 )
			{
				$array_cat = array();
				$array_cat = GetCatidInParent( $catid_i, true );
				
				$sql = "SELECT SQL_CALC_FOUND_ROWS `id`, `publtime`, `" . NV_LANG_DATA . "_title`, `" . NV_LANG_DATA . "_alias`, `" . NV_LANG_DATA . "_hometext`, `" . NV_LANG_DATA . "_address`, `homeimgalt`, `homeimgthumb`, `product_code`, `product_price`, `product_discounts`, `money_unit`, `showprice` FROM `" . $db_config['prefix'] . "_" . $module_data . "_rows` WHERE `listcatid` IN (" . implode( ",", $array_cat ) . ") AND `inhome`=1 AND `status`=1 ORDER BY `id` DESC LIMIT 0," . $array_info_i['numlinks'];
			
				$result = $db->sql_query( $sql );
				list( $num_pro ) = $db->sql_fetchrow( $db->sql_query( "SELECT FOUND_ROWS()" ) );
				
				$data_pro = array();
				
				while ( list( $id, $publtime, $title, $alias, $hometext, $address, $homeimgalt, $homeimgthumb, $product_code, $product_price, $product_discounts, $money_unit, $showprice ) = $db->sql_fetchrow( $result ) )
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
	elseif( $pro_config['home_view'] == "view_home_group" )
	{
		$num_links = $pro_config['per_row'] * 3 ;
	
		foreach ( $global_array_group as $groupid_i => $array_info_i )
		{
			if ( $array_info_i['parentid'] == 0 and $array_info_i['inhome'] != 0 )
			{
				$array_group = array();
				$array_group = GetGroupidInParent( $groupid_i, true );

				$sql_regexp = array();
				foreach( $array_group as $_gid )
				{
					$sql_regexp[] = "( `group_id`='" . $_gid . "' OR `group_id` REGEXP '^" . $_gid . "\\\,' OR `group_id` REGEXP '\\\," . $_gid . "\\\,' OR `group_id` REGEXP '\\\," . $_gid . "$' )";
				}
				$sql_regexp = "(" . implode( " OR ", $sql_regexp ) . ")";
				
				$sql = "SELECT SQL_CALC_FOUND_ROWS `id`, `listcatid`, `publtime`, `" . NV_LANG_DATA . "_title`, `" . NV_LANG_DATA . "_alias`, `" . NV_LANG_DATA . "_hometext`, `" . NV_LANG_DATA . "_address`, `homeimgalt`, `homeimgthumb`, `product_code`, `product_price`, `product_discounts`, `money_unit`, `showprice` FROM `" . $db_config['prefix'] . "_" . $module_data . "_rows` WHERE " . $sql_regexp . " AND `inhome`=1 AND `status`=1 ORDER BY `id` DESC LIMIT 0," . $num_links;
			
				$result = $db->sql_query( $sql );
				list( $num_pro ) = $db->sql_fetchrow( $db->sql_query( "SELECT FOUND_ROWS()" ) );
				
				$data_pro = array();
				
				while ( list( $id, $listcatid, $publtime, $title, $alias, $hometext, $address, $homeimgalt, $homeimgthumb, $product_code, $product_price, $product_discounts, $money_unit, $showprice ) = $db->sql_fetchrow( $result ) )
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
						"link_pro" => NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $global_array_cat[$listcatid]['alias'] . "/" . $alias . "-" . $id,
						"link_order" => NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=setcart&amp;id=" . $id,
					);
				}
				
				$data_content[] = array(
					"groupid" => $groupid_i,
					"title" => $array_info_i['title'],
					"link" => $array_info_i['link'],
					'data' => $data_pro,
					'num_pro' => $num_pro,
					"num_link" => $num_links,
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
		exit();
	}

	$contents = call_user_func( $pro_config['home_view'], $data_content, $html_pages );

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