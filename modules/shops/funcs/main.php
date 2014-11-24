<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES., JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 3-6-2010 0:14
 */

if( ! defined( 'NV_IS_MOD_SHOPS' ) ) die( 'Stop!!!' ); 

$page_title = $module_info['custom_title'];
$key_words = $module_info['keywords'];

$nv_Request->get_int( 'sorts', 'session', 0 );
$sorts = $nv_Request->get_int( 'sort', 'post', 0 );
$sorts_old = $nv_Request->get_int( 'sorts', 'session', 0 );
$sorts = $nv_Request->get_int( 'sorts', 'post', $sorts_old );

$contents = '';
$cache_file = '';

if( $nv_Request->isset_request( 'changesprice', 'post' ) )
{
	$sorts = $nv_Request->get_int( 'sort', 'post', 0 );
	$nv_Request->set_Session( 'sorts', $sorts, NV_LIVE_SESSION_TIME );
	nv_del_moduleCache( $module_name );
	die( 'OK' );
}

if( ! defined( 'NV_IS_MODADMIN' ) and $page < 5 )
{
	$cache_file = NV_LANG_DATA . '_' . $module_info['template'] . '_' . $op . '_' . $page . '_' . NV_CACHE_PREFIX . '.cache';
	if( ( $cache = nv_get_cache( $module_name, $cache_file ) ) != false )
	{
		$contents = $cache;
	}
}

if( empty( $contents ) )
{
	$data_content = array();
	$html_pages = '';
	$orderby = '';
	if( $sorts == 0 )
	{
		$orderby = ' id DESC ';

	}
	elseif( $sorts == 1 )
	{
		$orderby = ' t1.product_price ASC, t1.id DESC ';
	}
	else
	{
		$orderby = ' t1.product_price DESC, t1.id DESC ';
	}

	if( $pro_config['home_view'] == 'view_home_all' )
	{
		// Fetch Limit
		$db->sqlreset()->select( 'COUNT(*)' )->from( $db_config['prefix'] . '_' . $module_data . '_rows t1' )->where( 't1.inhome=1 AND t1.status =1 ' );

		$num_items = $db->query( $db->sql() )->fetchColumn();

		$db->select( 't1.id, t1.listcatid, t1.publtime, t1.' . NV_LANG_DATA . '_title, t1.' . NV_LANG_DATA . '_alias, t1.' . NV_LANG_DATA . '_hometext, t1.homeimgalt, t1.homeimgfile, t1.homeimgthumb, t1.product_code, t1.product_number, t1.product_price, t1.money_unit, t1.discount_id, t1.showprice, t2.newday' )
			->join( 'INNER JOIN ' . $db_config['prefix'] . '_' . $module_data . '_catalogs t2 ON t2.catid = t1.listcatid' )
			->order( $orderby )
			->limit( $per_page )
			->offset( ( $page - 1 ) * $per_page );

		$result = $db->query( $db->sql() );

		while( list( $id, $listcatid, $publtime, $title, $alias, $hometext, $homeimgalt, $homeimgfile, $homeimgthumb, $product_code, $product_number, $product_price, $money_unit, $discount_id, $showprice, $newday ) = $result->fetch( 3 ) )
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

			$data_content[] = array(
				'id' => $id,
				'publtime' => $publtime,
				'title' => $title,
				'alias' => $alias,
				'hometext' => $hometext,
				'homeimgalt' => $homeimgalt,
				'homeimgthumb' => $thumb,
				'product_price' => $product_price,
				'product_number' => $product_number,
				'product_code' => $product_code,
				'discount_id' => $discount_id,
				'money_unit' => $money_unit,
				'showprice' => $showprice,
				'newday' => $newday,
				'link_pro' => NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $global_array_cat[$listcatid]['alias'] . '/' . $alias . '-' . $id . $global_config['rewrite_exturl'],
				'link_order' => NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=setcart&amp;id=' . $id
			);
		}

		if( empty( $data_content ) and $page > 1 )
		{
			Header( 'Location: ' . nv_url_rewrite( NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name, true ) );
			exit();
		}

		$base_url = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name;
		$html_pages = nv_alias_page( $page_title, $base_url, $num_items, $per_page, $page );
	}
	elseif( $pro_config['home_view'] == 'view_home_cat' )
	{
		foreach( $global_array_cat as $catid_i => $array_info_i )
		{
			if( $array_info_i['parentid'] == 0 and $array_info_i['inhome'] != 0 )
			{
				$array_cat = array();
				$array_cat = GetCatidInParent( $catid_i, true );

				// Fetch Limit
				$db->sqlreset()->select( 'COUNT(*)' )->from( $db_config['prefix'] . '_' . $module_data . '_rows t1' )->where( 'listcatid IN (' . implode( ',', $array_cat ) . ') AND t1.inhome=1 AND t1.status =1' );

				$num_pro = $db->query( $db->sql() )->fetchColumn();

				$db->select( 't1.id, t1.publtime, t1.' . NV_LANG_DATA . '_title, t1.' . NV_LANG_DATA . '_alias, t1.' . NV_LANG_DATA . '_hometext, t1.homeimgalt, t1.homeimgfile, t1.homeimgthumb, t1.product_code, t1.product_number, t1.product_price, t1.money_unit, t1.discount_id, t1.showprice, t2.newday' )
					->join( 'INNER JOIN ' . $db_config['prefix'] . '_' . $module_data . '_catalogs t2 ON t2.catid = t1.listcatid' )
					->order( 't1.id DESC' )
					->limit( $array_info_i['numlinks'] );

				$result = $db->query( $db->sql() );
				$data_pro = array();

				while( list( $id, $publtime, $title, $alias, $hometext, $homeimgalt, $homeimgfile, $homeimgthumb, $product_code, $product_number, $product_price, $money_unit, $discount_id, $showprice, $newday ) = $result->fetch( 3 ) )
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

					$data_pro[] = array(
						'id' => $id,
						'publtime' => $publtime,
						'title' => $title,
						'alias' => $alias,
						'hometext' => $hometext,
						'homeimgalt' => $homeimgalt,
						'homeimgthumb' => $thumb,
						'product_code' => $product_code,
						'product_number' => $product_number,
						'product_price' => $product_price,
						'discount_id' => $discount_id,
						'money_unit' => $money_unit,
						'showprice' => $showprice,
						'newday' => $newday,
						'link_pro' => NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $global_array_cat[$catid_i]['alias'] . '/' . $alias . '-' . $id . $global_config['rewrite_exturl'],
						'link_order' => NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=setcart&amp;id=' . $id
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
		}

		if( $page > 1 )
		{
			Header( 'Location: ' . nv_url_rewrite( NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name, true ) );
			exit();
		}
	}
	elseif( $pro_config['home_view'] == 'view_home_group' )
	{
		$num_links = $pro_config['per_row'] * 3;

		foreach( $global_array_group as $groupid_i => $array_info_i )
		{
			if( $array_info_i['parentid'] == 0 and $array_info_i['inhome'] != 0 )
			{
				$array_group = array();
				$array_group = GetGroupidInParent( $groupid_i, true );

				$sql_regexp = array();
				foreach( $array_group as $_gid )
				{
					$sql_regexp[] = "( t3.group_id='" . $_gid . "' )";
				}
				$sql_regexp = "(" . implode( " OR ", $sql_regexp ) . ")";

				// Fetch Limit
				$db->sqlreset()->select( 'DISTINCT id' )
					->from( $db_config['prefix'] . '_' . $module_data . '_rows t1' )
					->join( 'INNER JOIN ' . $db_config['prefix'] . '_' . $module_data . '_items_group t3 ON t3.pro_id = t1.id' )
					->where( $sql_regexp . ' AND t1.inhome=1 AND t1.status =1' );

				$num_pro = $db->query( $db->sql() )->rowCount();

				$db->select( 'DISTINCT t1.id, t1.listcatid, t1.publtime, t1.' . NV_LANG_DATA . '_title, t1.' . NV_LANG_DATA . '_alias, t1.' . NV_LANG_DATA . '_hometext, t1.homeimgalt, t1.homeimgfile, t1.homeimgthumb, t1.product_code, t1.product_number, t1.product_price, t1.money_unit, t1.discount_id, t1.showprice, t2.newday' )
					->join( 'INNER JOIN ' . $db_config['prefix'] . '_' . $module_data . '_catalogs t2 ON t2.catid = t1.listcatid INNER JOIN ' . $db_config['prefix'] . '_' . $module_data . '_items_group t3 ON t3.pro_id = t1.id' )
					->order( 't1.id DESC' )
					->limit( $num_links );

				$result = $db->query( $db->sql() );

				$data_pro = array();

				while( list( $id, $listcatid, $publtime, $title, $alias, $hometext, $homeimgalt, $homeimgfile, $homeimgthumb, $product_code, $product_number, $product_price, $money_unit, $discount_id, $showprice, $newday ) = $result->fetch( 3 ) )
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

					$data_pro[] = array(
						'id' => $id,
						'publtime' => $publtime,
						'title' => $title,
						'alias' => $alias,
						'hometext' => $hometext,
						'homeimgalt' => $homeimgalt,
						'homeimgthumb' => $thumb,
						'product_code' => $product_code,
						'product_number' => $product_number,
						'product_price' => $product_price,
						'discount_id' => $discount_id,
						'money_unit' => $money_unit,
						'showprice' => $showprice,
						'newday' => $newday,
						'link_pro' => NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $global_array_cat[$listcatid]['alias'] . '/' . $alias . '-' . $id . $global_config['rewrite_exturl'],
						'link_order' => NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=setcart&amp;id=' . $id
					);
				}

				$data_content[] = array(
					'groupid' => $groupid_i,
					'title' => $array_info_i['title'],
					'link' => $array_info_i['link'],
					'data' => $data_pro,
					'num_pro' => $num_pro,
					'num_link' => $num_links
				);
			}
		}

		if( $page > 1 )
		{
			Header( 'Location: ' . nv_url_rewrite( NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name, true ) );
			exit();
		}
	}
	else
	{
		include NV_ROOTDIR . '/includes/header.php';
		echo nv_site_theme( '' );
		include NV_ROOTDIR . '/includes/footer.php';
		exit();
	}

	$contents = call_user_func( $pro_config['home_view'], $data_content, $html_pages, $sorts );

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