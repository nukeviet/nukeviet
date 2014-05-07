<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 3-6-2010 0:14
 */

if( ! defined( 'NV_IS_MOD_NEWS' ) ) die( 'Stop!!!' );

$page_title = $module_info['custom_title'];
$key_words = $module_info['keywords'];

$contents = '';
$cache_file = '';

$base_url = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name;
$base_url_rewrite = nv_url_rewrite( $base_url, true );
$request_uri = $_SERVER['REQUEST_URI'];
if( ! ( $home OR $request_uri == $base_url_rewrite OR $request_uri == $base_url_rewrite . 'page-' . $page . '/' ) )
{
	$redirect = '<meta http-equiv="Refresh" content="3;URL=' . $base_url_rewrite . '" />';
	nv_info_die( $lang_global['error_404_title'], $lang_global['error_404_title'], $lang_global['error_404_content'] . $redirect );
}
if( ! defined( 'NV_IS_MODADMIN' ) and $page < 5 )
{
	$cache_file = NV_LANG_DATA . '_' . $module_info['template'] . '-' . $op . '-' . $page . '-' . NV_CACHE_PREFIX . '.cache';
	if( ( $cache = nv_get_cache( $module_name, $cache_file ) ) != false )
	{
		$contents = $cache;
	}
}

if( empty( $contents ) )
{
	$viewcat = $module_config[$module_name]['indexfile'];
	$show_no_image = $module_config[$module_name]['show_no_image'];
	$array_catpage = array();
	$array_cat_other = array();

	if( $viewcat == 'viewcat_page_new' or $viewcat == 'viewcat_page_old' )
	{
		$order_by = ( $viewcat == 'viewcat_page_new' ) ? 't1.publtime DESC' : 't1.publtime ASC';
		$db->sqlreset()
			->select( 'COUNT(*)' )
			->from( NV_PREFIXLANG . '_' . $module_data . '_rows t1' )
			->where( 't1.status= 1 AND t1.inhome=1' );

		$num_items = $db->query( $db->sql() )->fetchColumn();

		$db->select( 't1.id, t1.catid, t1.listcatid, t1.topicid, t1.admin_id, t1.author, t1.sourceid, t1.addtime, t1.edittime, t1.publtime, t1.title, t1.alias, t1.hometext, t1.homeimgfile, t1.homeimgalt, t1.homeimgthumb, t1.allowed_rating, t1.hitstotal, t1.hitscm, t1.total_rating, t1.click_rating, t2.newday' )
			->join( 'INNER JOIN ' . NV_PREFIXLANG . '_' . $module_data . '_cat t2 ON t1.catid = t2.catid' )
			->order( $order_by )
			->limit( $per_page )
			->offset( ( $page - 1 ) * $per_page );

		$end_publtime = 0;

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

			$item['link'] = $global_array_cat[$item['catid']]['link'] . '/' . $item['alias'] . '-' . $item['id'] . $global_config['rewrite_exturl'];
			$array_catpage[] = $item;
			$end_publtime = $item['publtime'];
		}

		$db->sqlreset()
			->select('t1.id, t1.catid, t1.addtime, t1.edittime, t1.publtime, t1.title, t1.alias, t1.hitstotal, t2.newday')
			->from( NV_PREFIXLANG . '_' . $module_data . '_rows t1' )
			->join( 'INNER JOIN ' . NV_PREFIXLANG . '_' . $module_data . '_cat t2 ON t1.catid = t2.catid' );

		if( $viewcat == 'viewcat_page_new' )
		{
			$db->where( 't1.status= 1 AND t1.inhome=1 AND t1.publtime < ' . $end_publtime );
		}
		else
		{
			$db->where( 't1.status= 1 AND t1.inhome=1 AND t1.publtime > ' . $end_publtime );
		}
		$db->order( $order_by )->limit( $st_links );

		$result = $db->query( $db->sql() );
		while( $item = $result->fetch() )
		{
			$item['link'] = $global_array_cat[$item['catid']]['link'] . '/' . $item['alias'] . '-' . $item['id'] . $global_config['rewrite_exturl'];
			$array_cat_other[] = $item;
		}

		$viewcat = 'viewcat_page_new';
		$generate_page = nv_alias_page( $page_title, $base_url, $num_items, $per_page, $page );
		$contents = call_user_func( $viewcat, $array_catpage, $array_cat_other, $generate_page );
	}
	elseif( $viewcat == 'viewcat_main_left' or $viewcat == 'viewcat_main_right' or $viewcat == 'viewcat_main_bottom' )
	{
		$array_cat = array();

		$key = 0;
		$db->sqlreset()
			->select('t1.id, t1.listcatid, t1.topicid, t1.admin_id, t1.author, t1.sourceid, t1.addtime, t1.edittime, t1.publtime, t1.title, t1.alias, t1.hometext, t1.homeimgfile, t1.homeimgalt, t1.homeimgthumb, t1.allowed_rating, t1.hitstotal, t1.hitscm, t1.total_rating, t1.click_rating, t2.newday' )
			->join( 'INNER JOIN ' . NV_PREFIXLANG . '_' . $module_data . '_cat t2 ON t1.catid = t2.catid' )
			->where( 't1.status= 1 AND t1.inhome=1' )
			->order( 't1.publtime DESC' );

		foreach( $global_array_cat as $_catid => $array_cat_i )
		{
			if( $array_cat_i['parentid'] == 0 and $array_cat_i['inhome'] == 1 )
			{
				$array_cat[$key] = $array_cat_i;

				$result = $db->query( $db->from( NV_PREFIXLANG . '_' . $module_data . '_' . $_catid . ' t1' )->limit( $array_cat_i['numlinks'] )->sql() );
				while( $item = $result->fetch() )
				{
					if( $item['homeimgthumb'] == 1 )
					{
						$item['imghome'] = NV_BASE_SITEURL . NV_FILES_DIR . '/' . $module_name . '/' . $item['homeimgfile'];
					}
					elseif( $item['homeimgthumb'] == 2 )
					{
						$item['imghome'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_name . '/' . $item['homeimgfile'];
					}
					elseif( $item['homeimgthumb'] == 3 )
					{
						$item['imghome'] = $item['homeimgfile'];
					}
					elseif( ! empty( $show_no_image ) )
					{
						$item['imghome'] = NV_BASE_SITEURL . $show_no_image;
					}
					else
					{
						$item['imghome'] = '';
					}


					$item['link'] = $array_cat_i['link'] . '/' . $item['alias'] . '-' . $item['id'] . $global_config['rewrite_exturl'];
					$array_cat[$key]['content'][] = $item;
				}

				++$key;
			}
		}

		$contents = viewsubcat_main( $viewcat, $array_cat );
	}
	elseif( $viewcat == 'viewcat_two_column' )
	{
		// Cac bai viet phan dau
		$array_content = $array_catpage = array();

		// cac bai viet cua cac chu de con
		$key = 0;

		$db->sqlreset()
			->select('t1.id, t1.listcatid, t1.topicid, t1.admin_id, t1.author, t1.sourceid, t1.addtime, t1.edittime, t1.publtime, t1.title, t1.alias, t1.hometext, t1.homeimgfile, t1.homeimgalt, t1.homeimgthumb, t1.allowed_rating, t1.hitstotal, t1.hitscm, t1.total_rating, t1.click_rating, t2.newday')
			->join( 'INNER JOIN ' . NV_PREFIXLANG . '_' . $module_data . '_cat t2 ON t1.catid = t2.catid' )
			->where( 't1.status= 1 AND t1.inhome=1' )
			->order( 't1.publtime DESC' );
		foreach( $global_array_cat as $_catid => $array_cat_i )
		{
			if( $array_cat_i['parentid'] == 0 and $array_cat_i['inhome'] == 1 )
			{
				$array_catpage[$key] = $array_cat_i;
				$result = $db->query( $db->from( NV_PREFIXLANG . '_' . $module_data . '_' . $_catid . ' t1' )->limit($array_cat_i['numlinks'])->sql() );

				while( $item = $result->fetch() )
				{
					if( $item['homeimgthumb'] == 1 )
					{
						$item['imghome'] = NV_BASE_SITEURL . NV_FILES_DIR . '/' . $module_name . '/' . $item['homeimgfile'];
					}
					elseif( $item['homeimgthumb'] == 2 )
					{
						$item['imghome'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_name . '/' . $item['homeimgfile'];
					}
					elseif( $item['homeimgthumb'] == 3 )
					{
						$item['imghome'] = $item['homeimgfile'];
					}
					elseif( !empty( $show_no_image ) )
					{
						$item['imghome'] = NV_BASE_SITEURL . $show_no_image;
					}
					else
					{
						$item['imghome'] = '';
					}

					$item['link'] = $array_cat_i['link'] . '/' . $item['alias'] . '-' . $item['id'] . $global_config['rewrite_exturl'];
					$array_catpage[$key]['content'][] = $item;
				}
			}

			++$key;
		}
		unset( $sql, $result );
		//Het cac bai viet cua cac chu de con
		$contents = viewcat_two_column( $array_content, $array_catpage );
	}
	elseif( $viewcat == 'viewcat_grid_new' or $viewcat == 'viewcat_grid_old' )
	{
		$order_by = ( $viewcat == 'viewcat_grid_new' ) ? ' t1.publtime DESC' : ' t1.publtime ASC';
		$db->sqlreset()
			->select( 'COUNT(*) ')
			->from( NV_PREFIXLANG . '_' . $module_data . '_rows t1' )
			->where( 't1.status= 1 AND t1.inhome=1' );

		$num_items = $db->query( $db->sql() )->fetchColumn();

		$db->select( 't1.id, t1.catid, t1.topicid, t1.admin_id, t1.author, t1.sourceid, t1.addtime, t1.edittime, t1.publtime, t1.title, t1.alias, t1.hometext, t1.homeimgfile, t1.homeimgalt, t1.homeimgthumb, t1.allowed_rating, t1.hitstotal, t1.hitscm, t1.total_rating, t1.click_rating, t2.newday' )
			->join( 'INNER JOIN ' . NV_PREFIXLANG . '_' . $module_data . '_cat t2 ON t1.catid = t2.catid' )
			->order( $order_by )
			->limit($per_page )
			->offset( ( $page - 1 ) * $per_page );

		$result = $db->query( $db->sql() );
		while( $item = $result->fetch() )
		{
			if( $item['homeimgthumb'] == 1 )
			{
				$item['imghome'] = NV_BASE_SITEURL . NV_FILES_DIR . '/' . $module_name . '/' . $item['homeimgfile'];
			}
			elseif( $item['homeimgthumb'] == 2 )
			{
				$item['imghome'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_name . '/' . $item['homeimgfile'];
			}
			elseif( $item['homeimgthumb'] == 3 )
			{
				$item['imghome'] = $item['homeimgfile'];
			}
			elseif( !empty( $show_no_image ) )
			{
				$item['imghome'] = NV_BASE_SITEURL . $show_no_image;
			}
			else
			{
				$item['imghome'] = '';
			}

			$item['link'] = $global_array_cat[$item['catid']]['link'] . '/' . $item['alias'] . '-' . $item['id'] . $global_config['rewrite_exturl'];
			$array_catpage[] = $item;
		}

		$viewcat = 'viewcat_grid_new';
		$generate_page = nv_alias_page( $page_title, $base_url, $num_items, $per_page, $page );
		$contents = call_user_func( $viewcat, $array_catpage, 0, $generate_page );
	}
	elseif( $viewcat == 'viewcat_list_new' or $viewcat == 'viewcat_list_old' ) // Xem theo tieu de
	{
		$order_by = ( $viewcat == 'viewcat_list_new' ) ? 't1.publtime DESC' : 't1.publtime ASC';

		$db->sqlreset()
			->select( 'COUNT(*) ')
			->from( NV_PREFIXLANG . '_' . $module_data . '_rows t1' )
			->where( 't1.status= 1 AND t1.inhome=1' );

		$num_items = $db->query( $db->sql() )->fetchColumn();

		$db->select( 't1.id, t1.catid, t1.topicid, t1.admin_id, t1.author, t1.sourceid, t1.addtime, t1.edittime, t1.publtime, t1.title, t1.alias, t1.hometext, t1.homeimgfile, t1.homeimgalt, t1.homeimgthumb, t1.allowed_rating, t1.hitstotal, t1.hitscm, t1.total_rating, t1.click_rating, t2.newday' )
			->join( 'INNER JOIN ' . NV_PREFIXLANG . '_' . $module_data . '_cat t2 ON t1.catid = t2.catid' )
			->order( $order_by )
			->limit($per_page )
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

			$item['link'] = $global_array_cat[$item['catid']]['link'] . '/' . $item['alias'] . '-' . $item['id'] . $global_config['rewrite_exturl'];
			$array_catpage[] = $item;
		}

		$viewcat = 'viewcat_list_new';
		$generate_page = nv_alias_page( $page_title, $base_url, $num_items, $per_page, $page );
		$contents = call_user_func( $viewcat, $array_catpage, 0, ( $page - 1 ) * $per_page, $generate_page );
	}

	if( ! defined( 'NV_IS_MODADMIN' ) and $contents != '' and $cache_file != '' )
	{
		nv_set_cache( $module_name, $cache_file, $contents );
	}
}

if( $page > 1 )
{
	$page_title .= ' ' . NV_TITLEBAR_DEFIS . ' ' . $lang_global['page'] . ' ' . $page;
}

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme( $contents );
include NV_ROOTDIR . '/includes/footer.php';