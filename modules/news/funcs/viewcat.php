<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 3-6-2010 0:14
 */

if( ! defined( 'NV_IS_MOD_NEWS' ) ) die( 'Stop!!!' );

$cache_file = '';
$contents = '';
$viewcat = $global_array_cat[$catid]['viewcat'];

$set_view_page = ( $page > 1 and substr( $viewcat, 0, 13 ) == 'viewcat_main_' ) ? true : false;

if( ! defined( 'NV_IS_MODADMIN' ) and $page < 5 )
{
	if( $set_view_page )
	{
		$cache_file = NV_LANG_DATA . '_' . $module_info['template'] . '_' . $op . '_' . $catid . '_page_' . $page . '_' . NV_CACHE_PREFIX . '.cache';
	}
	else
	{
		$cache_file = NV_LANG_DATA . '_' . $module_info['template'] . '_' . $op . '_' . $catid . '_' . $page . '_' . NV_CACHE_PREFIX . '.cache';
	}
	if( ( $cache = nv_get_cache( $module_name, $cache_file ) ) != false )
	{
		$contents = $cache;
	}
}

$page_title = ( ! empty( $global_array_cat[$catid]['titlesite'] ) ) ? $global_array_cat[$catid]['titlesite'] : $global_array_cat[$catid]['title'];
$key_words = $global_array_cat[$catid]['keywords'];
$description = $global_array_cat[$catid]['description'];
if( ! empty($global_array_cat[$catid]['image']))
{
	$meta_property['og:image'] = NV_MY_DOMAIN . NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_name . '/' . $global_array_cat[$catid]['image'];
}

if( empty( $contents ) )
{
	$array_catpage = array();
	$array_cat_other = array();
	$base_url = $global_array_cat[$catid]['link'];
	$show_no_image = $module_config[$module_name]['show_no_image'];

	if( $viewcat == 'viewcat_page_new' or $viewcat == 'viewcat_page_old' or $set_view_page )
	{
		$st_links = 2 * $st_links;
		$order_by = ( $viewcat == 'viewcat_page_new' ) ? 't1.publtime DESC' : 't1.publtime ASC';

		$db->sqlreset()
			->select( 'COUNT(*)' )
			->from( NV_PREFIXLANG . '_' . $module_data . '_' . $catid . ' t1' )
			->where( 'status=1' );

		$num_items = $db->query( $db->sql() )->fetchColumn();

		$db->select( 't1.id, t1.listcatid, t1.topicid, t1.admin_id, t1.author, t1.sourceid, t1.addtime, t1.edittime, t1.publtime, t1.title, t1.alias, t1.hometext, t1.homeimgfile, t1.homeimgalt, t1.homeimgthumb, t1.allowed_rating, t1.hitstotal, t1.hitscm, t1.total_rating, t1.click_rating, t2.newday' )
			->join( 'INNER JOIN ' . NV_PREFIXLANG . '_' . $module_data . '_cat t2 ON t1.catid = t2.catid' )
			->order( $order_by )
			->limit( $per_page )
			->offset( ( $page - 1 ) * $per_page );
		$result = $db->query( $db->sql() );
		$end_publtime = 0;
		while( $item = $result->fetch() )
		{
			if( $item['homeimgthumb'] == 1 ) //image thumb
			{
				$item['imghome'] = NV_BASE_SITEURL . NV_FILES_DIR . '/' . $module_name . '/' . $item['homeimgfile'];
			}
			elseif( $item['homeimgthumb'] == 2 ) //image file
			{
				$item['imghome'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_name . '/' . $item['homeimgfile'];
			}
			elseif( $item['homeimgthumb'] == 3 ) //image url
			{
				$item['imghome'] = $item['homeimgfile'];
			}
			elseif( ! empty( $show_no_image ) ) //no image
			{
				$item['imghome'] = NV_BASE_SITEURL . $show_no_image;
			}
			else
			{
				$item['imghome'] = '';
			}

			$item['link'] = $global_array_cat[$catid]['link'] . '/' . $item['alias'] . '-' . $item['id'] . $global_config['rewrite_exturl'];
			$array_catpage[] = $item;
			$end_publtime = $item['publtime'];
		}

		$db->sqlreset()
			->select( 't1.id, t1.listcatid, t1.addtime, t1.edittime, t1.publtime, t1.title, t1.alias, t1.hitstotal, t2.newday' )
			->from( NV_PREFIXLANG . '_' . $module_data . '_' . $catid . ' t1' )
			->join( 'INNER JOIN ' . NV_PREFIXLANG . '_' . $module_data . '_cat t2 ON t1.catid = t2.catid' )
			->order( $order_by )
			->limit( $st_links );
		if( $viewcat == 'viewcat_page_new' )
		{
			$db->where( 'status=1 AND publtime < ' . $end_publtime );
		}
		else
		{
			$db->where( 'status=1 AND publtime > ' . $end_publtime );
		}
		$result = $db->query( $db->sql() );
		while( $item = $result->fetch() )
		{
			$item['link'] = $global_array_cat[$catid]['link'] . '/' . $item['alias'] . '-' . $item['id'] . $global_config['rewrite_exturl'];
			$array_cat_other[] = $item;
		}

		$generate_page = nv_alias_page( $page_title, $base_url, $num_items, $per_page, $page );
		$contents = viewcat_page_new( $array_catpage, $array_cat_other, $generate_page );
	}
	elseif( $viewcat == 'viewcat_main_left' or $viewcat == 'viewcat_main_right' or $viewcat == 'viewcat_main_bottom' )
	{
		$array_catcontent = array();
		$array_subcatpage = array();

		$db->sqlreset()
			->select( 'COUNT(*)' )
			->from( NV_PREFIXLANG . '_' . $module_data . '_' . $catid . ' t1' )
			->where( 'status=1' );

		$num_items = $db->query( $db->sql() )->fetchColumn();

		$db->select( 't1.id, t1.listcatid, t1.topicid, t1.admin_id, t1.author, t1.sourceid, t1.addtime, t1.edittime, t1.publtime, t1.title, t1.alias, t1.hometext, t1.homeimgfile, t1.homeimgalt, t1.homeimgthumb, t1.allowed_rating, t1.hitstotal, t1.hitscm, t1.total_rating, t1.click_rating, t2.newday' )
			->join( 'INNER JOIN ' . NV_PREFIXLANG . '_' . $module_data . '_cat t2 ON t1.catid = t2.catid' )
			->order( 't1.id DESC' )
			->limit( $per_page )
			->offset( ( $page - 1 ) * $per_page );

		$result = $db->query( $db->sql() );
		while( $item = $result->fetch() )
		{
			if( $item['homeimgthumb'] == 1 ) //image thumb
			{
				$item['imghome'] = NV_BASE_SITEURL . NV_FILES_DIR . '/' . $module_name . '/' . $item['homeimgfile'];
			}
			elseif( $item['homeimgthumb'] == 2 ) //image file
			{
				$item['imghome'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_name . '/' . $item['homeimgfile'];
			}
			elseif( $item['homeimgthumb'] == 3 ) //image url
			{
				$item['imghome'] = $item['homeimgfile'];
			}
			elseif( !empty( $show_no_image ) ) //no image
			{
				$item['imghome'] = NV_BASE_SITEURL . $show_no_image;
			}
			else
			{
				$item['imghome'] = '';
			}

			$item['link'] = $global_array_cat[$catid]['link'] . '/' . $item['alias'] . '-' . $item['id'] . $global_config['rewrite_exturl'];
			$array_catcontent[] = $item;
		}
		unset( $sql, $result );

		$array_cat_other = array();

		if( $global_array_cat[$catid]['subcatid'] != '' )
		{
			$key = 0;
			$array_catid = explode( ',', $global_array_cat[$catid]['subcatid'] );

			foreach( $array_catid as $catid_i )
			{
				$array_cat_other[$key] = $global_array_cat[$catid_i];
				$db->sqlreset()
					->select( 't1.id, t1.catid, t1.listcatid, t1.topicid, t1.admin_id, t1.author, t1.sourceid, t1.addtime, t1.edittime, t1.publtime, t1.title, t1.alias, t1.hometext, t1.homeimgfile, t1.homeimgalt, t1.homeimgthumb, t1.allowed_rating, t1.hitstotal, t1.hitscm, t1.total_rating, t1.click_rating, t2.newday' )
					->from( NV_PREFIXLANG . '_' . $module_data . '_' . $catid_i . ' t1' )
					->join( 'INNER JOIN ' . NV_PREFIXLANG . '_' . $module_data . '_cat t2 ON t1.catid = t2.catid' )
					->where( 't1.status=1' )
					->order( 't1.publtime DESC' )
					->limit( $global_array_cat[$catid_i]['numlinks'] );
				$result = $db->query( $db->sql() );

				while( $item = $result->fetch() )
				{
					if( $item['homeimgthumb'] == 1 ) //image thumb
					{
						$item['imghome'] = NV_BASE_SITEURL . NV_FILES_DIR . '/' . $module_name . '/' . $item['homeimgfile'];
					}
					elseif( $item['homeimgthumb'] == 2 ) //image file
					{
						$item['imghome'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_name . '/' . $item['homeimgfile'];
					}
					elseif( $item['homeimgthumb'] == 3 ) //image url
					{
						$item['imghome'] = $item['homeimgfile'];
					}
					elseif( ! empty( $show_no_image ) ) //no image
					{
						$item['imghome'] = NV_BASE_SITEURL . $show_no_image;
					}
					else
					{
						$item['imghome'] = '';
					}

					$item['link'] = $global_array_cat[$catid_i]['link'] . '/' . $item['alias'] . '-' . $item['id'] . $global_config['rewrite_exturl'];
					$array_cat_other[$key]['content'][] = $item;
				}

				unset( $sql, $result );
				++$key;
			}

			unset( $array_catid );
		}

		$generate_page = nv_alias_page( $page_title, $base_url, $num_items, $per_page, $page );
		$contents = viewcat_top( $array_catcontent, $generate_page );
		$contents .= call_user_func( 'viewsubcat_main', $viewcat, $array_cat_other );
	}
	elseif( $viewcat == 'viewcat_two_column' )
	{
		// Cac bai viet phan dau
		$array_catcontent = array();

		$db->sqlreset()
			->select( 't1.id, t1.listcatid, t1.topicid, t1.admin_id, t1.author, t1.sourceid, t1.addtime, t1.edittime, t1.publtime, t1.title, t1.alias, t1.hometext, t1.homeimgfile, t1.homeimgalt, t1.homeimgthumb, t1.allowed_rating, t1.hitstotal, t1.hitscm, t1.total_rating, t1.click_rating, t2.newday' )
			->from( NV_PREFIXLANG . '_' . $module_data . '_' . $catid . ' t1' )
			->join( 'INNER JOIN ' . NV_PREFIXLANG . '_' . $module_data . '_cat t2 ON t1.catid = t2.catid' )
			->where( 't1.status=1' )
			->order( 't1.publtime DESC' )
			->limit( $per_page )
			->offset( ( $page - 1 ) * $per_page );

		$result = $db->query( $db->sql() );
		while( $item = $result->fetch() )
		{
			if( $item['homeimgthumb'] == 1 ) //image thumb
			{
				$item['imghome'] = NV_BASE_SITEURL . NV_FILES_DIR . '/' . $module_name . '/' . $item['homeimgfile'];
			}
			elseif( $item['homeimgthumb'] == 2 ) //image file
			{
				$item['imghome'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_name . '/' . $item['homeimgfile'];
			}
			elseif( $item['homeimgthumb'] == 3 ) //image url
			{
				$item['imghome'] = $item['homeimgfile'];
			}
			elseif( ! empty( $show_no_image ) ) //no image
			{
				$item['imghome'] = NV_BASE_SITEURL . $show_no_image;
			}
			else
			{
				$item['imghome'] = '';
			}
			$item['link'] = $global_array_cat[$catid]['link'] . '/' . $item['alias'] . '-' . $item['id'] . $global_config['rewrite_exturl'];
			$array_catcontent[] = $item;
		}
		unset( $sql, $result );
		// Het cac bai viet phan dau

		// cac bai viet cua cac chu de con
		$key = 0;
		$array_catid = explode( ',', $global_array_cat[$catid]['subcatid'] );

		foreach( $array_catid as $catid_i )
		{
			$array_cat_other[$key] = $global_array_cat[$catid_i];

			$db->sqlreset()
				->select( 't1.id, t1.listcatid, t1.topicid, t1.admin_id, t1.author, t1.sourceid, t1.addtime, t1.edittime, t1.publtime, t1.title, t1.alias, t1.hometext, t1.homeimgfile, t1.homeimgalt, t1.homeimgthumb, t1.allowed_rating, t1.hitstotal, t1.hitscm, t1.total_rating, t1.click_rating, t2.newday' )
				->from( NV_PREFIXLANG . '_' . $module_data . '_' . $catid_i . ' t1' )
				->join( 'INNER JOIN ' . NV_PREFIXLANG . '_' . $module_data . '_cat t2 ON t1.catid = t2.catid' )
				->where( 't1.status=1' )
				->order( 't1.publtime DESC' )
				->limit( $global_array_cat[$catid_i]['numlinks'] );
			$result = $db->query( $db->sql() );
			while( $item = $result->fetch() )
			{
				if( $item['homeimgthumb'] == 1 ) //image thumb
				{
					$item['imghome'] = NV_BASE_SITEURL . NV_FILES_DIR . '/' . $module_name . '/' . $item['homeimgfile'];
				}
				elseif( $item['homeimgthumb'] == 2 ) //image file
				{
					$item['imghome'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_name . '/' . $item['homeimgfile'];
				}
				elseif( $item['homeimgthumb'] == 3 ) //image url
				{
					$item['imghome'] = $item['homeimgfile'];
				}
				elseif( ! empty( $show_no_image ) ) //no image
				{
					$item['imghome'] = NV_BASE_SITEURL . $show_no_image;
				}
				else
				{
					$item['imghome'] = '';
				}
				$item['link'] = $global_array_cat[$catid_i]['link'] . '/' . $item['alias'] . '-' . $item['id'] . $global_config['rewrite_exturl'];
				$array_cat_other[$key]['content'][] = $item;
			}

			++$key;
		}

		unset( $sql, $result );
		//Het cac bai viet cua cac chu de con
		$contents = call_user_func( $viewcat, $array_catcontent, $array_cat_other );
	}
	elseif( $viewcat == 'viewcat_grid_new' or $viewcat == 'viewcat_grid_old' )
	{
		$order_by = ( $viewcat == 'viewcat_grid_new' ) ? 't1.publtime DESC' : 't1.publtime ASC';

		$db->sqlreset()
			->select( 'COUNT(*)' )
			->from( NV_PREFIXLANG . '_' . $module_data . '_' . $catid . ' t1' )
			->where( 't1.status=1' );

		$num_items = $db->query( $db->sql() )->fetchColumn();

		$db->select( 't1.id, t1.listcatid, t1.topicid, t1.admin_id, t1.author, t1.sourceid, t1.addtime, t1.edittime, t1.publtime, t1.title, t1.alias, t1.hometext, t1.homeimgfile, t1.homeimgalt, t1.homeimgthumb, t1.allowed_rating, t1.hitstotal, t1.hitscm, t1.total_rating, t1.click_rating, t2.newday' )
			->join( 'INNER JOIN ' . NV_PREFIXLANG . '_' . $module_data . '_cat t2 ON t1.catid = t2.catid' )
			->order( $order_by )
			->limit( $per_page )
			->offset( ( $page - 1 ) * $per_page );

		$result = $db->query( $db->sql() );
		while( $item = $result->fetch() )
		{
			if( $item['homeimgthumb'] == 1 ) //image thumb
			{
				$item['imghome'] = NV_BASE_SITEURL . NV_FILES_DIR . '/' . $module_name . '/' . $item['homeimgfile'];
			}
			elseif( $item['homeimgthumb'] == 2 ) //image file
			{
				$item['imghome'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_name . '/' . $item['homeimgfile'];
			}
			elseif( $item['homeimgthumb'] == 3 ) //image url
			{
				$item['imghome'] = $item['homeimgfile'];
			}
			elseif( !empty( $show_no_image ) ) //no image
			{
				$item['imghome'] = NV_BASE_SITEURL . $show_no_image;
			}
			else
			{
				$item['imghome'] = '';
			}
			$item['link'] = $global_array_cat[$catid]['link'] . '/' . $item['alias'] . '-' . $item['id'] . $global_config['rewrite_exturl'];
			$array_catpage[] = $item;
		}

		$viewcat = 'viewcat_grid_new';
		$generate_page = nv_alias_page( $page_title, $base_url, $num_items, $per_page, $page );
		$contents = call_user_func( $viewcat, $array_catpage, $catid, $generate_page );
	}
	elseif( $viewcat == 'viewcat_list_new' or $viewcat == 'viewcat_list_old' ) // Xem theo tieu de
	{
		$order_by = ( $viewcat == 'viewcat_list_new' ) ? 't1.publtime DESC' : 't1.publtime ASC';

		$db->sqlreset()
			->select( 'COUNT(*)' )
			->from( NV_PREFIXLANG . '_' . $module_data . '_' . $catid . ' t1' )
			->where( 't1.status=1' );

		$num_items = $db->query( $db->sql() )->fetchColumn();

		$db->select( 't1.id, t1.listcatid, t1.topicid, t1.admin_id, t1.author, t1.sourceid, t1.addtime, t1.edittime, t1.publtime, t1.title, t1.alias, t1.hometext, t1.homeimgfile, t1.homeimgalt, t1.homeimgthumb, t1.allowed_rating, t1.hitstotal, t1.hitscm, t1.total_rating, t1.click_rating, t2.newday' )
			->join( 'INNER JOIN ' . NV_PREFIXLANG . '_' . $module_data . '_cat t2 ON t1.catid = t2.catid' )
			->order( $order_by )
			->limit( $per_page )
			->offset( ( $page - 1 ) * $per_page );
		$result = $db->query( $db->sql() );
		while( $item = $result->fetch() )
		{
			$item['imghome'] = '';
			$item['link'] = $global_array_cat[$catid]['link'] . '/' . $item['alias'] . '-' . $item['id'] . $global_config['rewrite_exturl'];
			$array_catpage[] = $item;
		}

		$viewcat = 'viewcat_list_new';
		$generate_page = nv_alias_page( $page_title, $base_url, $num_items, $per_page, $page );
		$contents = call_user_func( $viewcat, $array_catpage, $catid, ( $page - 1 ) * $per_page, $generate_page );
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