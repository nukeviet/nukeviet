<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES., JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 3-6-2010 0:14
 */

if( ! defined( 'NV_IS_MOD_SHOPS' ) ) die( 'Stop!!!' );

if( empty( $catid ) )
{
	Header( 'Location: ' . nv_url_rewrite( NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name, true ) );
	exit();
}

$page_title = $global_array_cat[$catid]['title'];
$key_words = $global_array_cat[$catid]['keywords'];
$description = $global_array_cat[$catid]['description'];

$contents = '';
$cache_file = '';
$nv_Request->get_int( 'sorts', 'session', 0 );
$sorts = $nv_Request->get_int( 'sort', 'post', 0 );
$sorts_old = $nv_Request->get_int( 'sorts', 'session', 0 );
$sorts = $nv_Request->get_int( 'sorts', 'post', $sorts_old );
if( ! defined( 'NV_IS_MODADMIN' ) and $page < 5 )
{
	$cache_file = NV_LANG_DATA . '_' . $module_info['template'] . '_' . $op . '_' . $catid . '_' . $page . '_' . NV_CACHE_PREFIX . '.cache';
	if( ( $cache = nv_get_cache( $module_name, $cache_file ) ) != false )
	{
		$contents = $cache;
	}
}

if( empty( $contents ) )
{
	$data_content = array();

	$count = 0;
	$link = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=';
	$base_url = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $global_array_cat[$catid]['alias'];
	$orderby = '';
	if( $sorts == 0 )
	{
		$orderby = 'id DESC ';

	}
	elseif( $sorts == 1 )
	{
		$orderby = 'product_price ASC, id DESC ';
	}
	else
	{
		$orderby = ' product_price DESC, id DESC ';
	}
	if( $global_array_cat[$catid]['viewcat'] == 'view_home_cat' and $global_array_cat[$catid]['numsubcat'] > 0 )
	{
		$data_content = array();
		$array_subcatid = explode( ',', $global_array_cat[$catid]['subcatid'] );

		foreach( $array_subcatid as $catid_i )
		{
			$array_info_i = $global_array_cat[$catid_i];

			$array_cat = array();
			$array_cat = GetCatidInParent( $catid_i );

			// Fetch Limit
			$db->sqlreset()->select( 'COUNT(*)' )->from( $db_config['prefix'] . '_' . $module_data . '_rows' )->where( 'listcatid IN (' . implode( ',', $array_cat ) . ') AND status =1 ' );

			$num_pro = $db->query( $db->sql() )->fetchColumn();

			$db->select( 'id, publtime, ' . NV_LANG_DATA . '_title, ' . NV_LANG_DATA . '_alias, ' . NV_LANG_DATA . '_hometext, ' . NV_LANG_DATA . '_address, homeimgalt, homeimgfile, homeimgthumb, product_code, product_price, product_discounts, money_unit, showprice' )->order( $orderby )->limit( $array_info_i['numlinks'] );
			$result = $db->query( $db->sql() );

			$data_pro = array();

			while( list( $id, $publtime, $title, $alias, $hometext, $address, $homeimgalt, $homeimgfile, $homeimgthumb, $product_code, $product_price, $product_discounts, $money_unit, $showprice ) = $result->fetch( 3 ) )
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
					$thumb = NV_BASE_SITEURL . 'themes/' . $module_info['template'] . '/images/' . $module_name . '/no-image.jpg';
				}
				$data_pro[] = array(
					'id' => $id,
					'publtime' => $publtime,
					'title' => $title,
					'alias' => $alias,
					'hometext' => $hometext,
					'address' => $address,
					'homeimgalt' => $homeimgalt,
					'homeimgthumb' => $thumb,
					'product_code' => $product_code,
					'product_price' => $product_price,
					'product_discounts' => $product_discounts,
					'money_unit' => $money_unit,
					'showprice' => $showprice,
					'link_pro' => $link . $global_array_cat[$catid_i]['alias'] . '/' . $alias . '-' . $id . $global_config['rewrite_exturl'],
					'link_order' => $link . 'setcart&amp;id=' . $id
				);
			}

			$data_content[] = array(
				'catid' => $catid_i,
				'title' => $array_info_i['title'],
				'link' => $array_info_i['link'],
				'data' => $data_pro,
				'num_pro' => $num_pro,
				'num_link' => $array_info_i['numlinks']
			);
		}

		if( $page > 1 )
		{
			Header( 'Location: ' . nv_url_rewrite( NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name, true ) );
			exit();
		}

		$contents = call_user_func( 'view_home_cat', $data_content, $sorts );
	}
	else
	{
		// Fetch Limit
		if( $global_array_cat[$catid]['numsubcat'] == 0 )
		{
			$where = ' listcatid=' . $catid;
		}
		else
		{
			$array_cat = array();
			$array_cat = GetCatidInParent( $catid );
			$where = ' listcatid IN (' . implode( ',', $array_cat ) . ')';
		}

		$db->sqlreset()->select( 'COUNT(*)' )->from( $db_config['prefix'] . '_' . $module_data . '_rows' )->where( $where . ' AND status =1 ' );

		$num_items = $db->query( $db->sql() )->fetchColumn();

		$db->select( 'id, listcatid, publtime, ' . NV_LANG_DATA . '_title, ' . NV_LANG_DATA . '_alias, ' . NV_LANG_DATA . '_hometext, ' . NV_LANG_DATA . '_address, homeimgalt, homeimgfile, homeimgthumb, product_code, product_price, product_discounts, money_unit, showprice' )->order( $orderby )->limit( $per_page )->offset( ( $page - 1 ) * $per_page );
		$result = $db->query( $db->sql() );

		$data_content = GetDataIn( $result, $catid );
		$data_content['count'] = $num_items;

		$pages = nv_alias_page( $page_title, $base_url, $num_items, $per_page, $page );

		if( sizeof( $data_content['data'] ) < 1 and $page > 1 )
		{
			Header( 'Location: ' . nv_url_rewrite( NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name, true ) );
			exit();
		}

		$contents = call_user_func( $global_array_cat[$catid]['viewcat'], $data_content, $pages, $sorts );
	}

	if( ! defined( 'NV_IS_MODADMIN' ) and $contents != '' and $cache_file != '' )
	{
		nv_set_cache( $module_name, $cache_file, $contents );
	}
}

if( $page > 1 )
{
	$page_title .= ' ' . NV_TITLEBAR_DEFIS . ' ' . $lang_global['page'] . ' ' . $page;
	$description .= ' ' . $page;
}

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme( $contents );
include NV_ROOTDIR . '/includes/footer.php';