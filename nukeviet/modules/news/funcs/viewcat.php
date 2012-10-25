<?php

/**
 * @Project NUKEVIET 3.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2012 VINADES.,JSC. All rights reserved
 * @Createdate 3-6-2010 0:14
 */

if( ! defined( 'NV_IS_MOD_NEWS' ) ) die( 'Stop!!!' );

$cache_file = "";
$contents = "";
$viewcat = $global_array_cat[$catid]['viewcat'];

$set_view_page = ( $page > 1 and substr( $viewcat, 0, 13 ) == "viewcat_main_" ) ? true : false;

if( ! defined( 'NV_IS_MODADMIN' ) and $page < 5 )
{
	if( $set_view_page )
	{
		$cache_file = NV_LANG_DATA . "_" . $module_name . "_" . $module_info['template'] . "_" . $op . "_" . $catid . "_page_" . $page . "_" . NV_CACHE_PREFIX . ".cache";
	}
	else
	{
		$cache_file = NV_LANG_DATA . "_" . $module_name . "_" . $module_info['template'] . "_" . $op . "_" . $catid . "_" . $page . "_" . NV_CACHE_PREFIX . ".cache";
	}
	if( ( $cache = nv_get_cache( $cache_file ) ) != false )
	{
		$contents = $cache;
	}
}

$page_title = ( ! empty( $global_array_cat[$catid]['titlesite'] ) ) ? $global_array_cat[$catid]['titlesite'] : $global_array_cat[$catid]['title'];
$key_words = $global_array_cat[$catid]['keywords'];
$description = $global_array_cat[$catid]['description'];

if( empty( $contents ) )
{
	$array_catpage = array();
	$array_cat_other = array();
	$base_url = $global_array_cat[$catid]['link'];

	if( $viewcat == "viewcat_page_new" or $viewcat == "viewcat_page_old" or $set_view_page )
	{
		$st_links = 2 * $st_links;
		$order_by = ( $viewcat == "viewcat_page_new" ) ? "ORDER BY `publtime` DESC" : "ORDER BY `publtime` ASC";
		$sql = "SELECT SQL_CALC_FOUND_ROWS `id`, `listcatid`, `topicid`, `admin_id`, `author`, `sourceid`, `addtime`, `edittime`, `publtime`, `title`, `alias`, `hometext`, `homeimgfile`, `homeimgalt`, `homeimgthumb`, `allowed_rating`, `hitstotal`, `hitscm`, `total_rating`, `click_rating`, `keywords` FROM `" . NV_PREFIXLANG . "_" . $module_data . "_" . $catid . "` WHERE `status`=1 " . $order_by . " LIMIT " . ( $page - 1 ) * $per_page . "," . $per_page;
		$result = $db->sql_query( $sql );

		$result_all = $db->sql_query( "SELECT FOUND_ROWS()" );
		list( $all_page ) = $db->sql_fetchrow( $result_all );

		$end_publtime = 0;
		while( $item = $db->sql_fetch_assoc( $result ) )
		{
			$array_img = ( ! empty( $item['homeimgthumb'] ) ) ? explode( "|", $item['homeimgthumb'] ) : $array_img = array( "", "" );

			if( $array_img[0] != "" and file_exists( NV_ROOTDIR . '/' . NV_FILES_DIR . '/' . $module_name . '/' . $array_img[0] ) )
			{
				$item['imghome'] = NV_BASE_SITEURL . NV_FILES_DIR . '/' . $module_name . '/' . $array_img[0];
			}
			elseif( nv_is_url( $item['homeimgfile'] ) )
			{
				$item['imghome'] = $item['homeimgfile'];
			}
			elseif( $item['homeimgfile'] != "" and file_exists( NV_UPLOADS_REAL_DIR . '/' . $module_name . '/' . $item['homeimgfile'] ) )
			{
				$item['imghome'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_name . '/' . $item['homeimgfile'];
			}
			else
			{
				$item['imghome'] = "";
			}

			$item['link'] = $global_array_cat[$catid]['link'] . "/" . $item['alias'] . "-" . $item['id'];
			$array_catpage[] = $item;
			$end_publtime = $item['publtime'];
		}

		if( $viewcat == "viewcat_page_new" )
		{
			$sql = "SELECT `id`, `listcatid`, `addtime`, `edittime`, `publtime`, `title`, `alias`, `hitstotal` FROM `" . NV_PREFIXLANG . "_" . $module_data . "_" . $catid . "` WHERE `status`=1 AND `publtime` < " . $end_publtime . " " . $order_by . " LIMIT 0," . $st_links;
		}
		else
		{
			$sql = "SELECT `id`, `listcatid`, `addtime`, `edittime`, `publtime`, `title`, `alias`, `hitstotal` FROM `" . NV_PREFIXLANG . "_" . $module_data . "_" . $catid . "` WHERE `status`=1 AND `publtime` > " . $end_publtime . "  " . $order_by . " LIMIT 0," . $st_links;
		}

		$result = $db->sql_query( $sql );

		while( $item = $db->sql_fetch_assoc( $result ) )
		{
			$item['link'] = $global_array_cat[$catid]['link'] . "/" . $item['alias'] . "-" . $item['id'];
			$array_cat_other[] = $item;
		}

		$generate_page = nv_alias_page( $page_title, $base_url, $all_page, $per_page, $page );
		$contents = viewcat_page_new( $array_catpage, $array_cat_other, $generate_page );
	}
	elseif( $viewcat == "viewcat_main_left" or $viewcat == "viewcat_main_right" or $viewcat == "viewcat_main_bottom" )
	{
		$array_catcontent = array();
		$array_subcatpage = array();
		$sql = "SELECT SQL_CALC_FOUND_ROWS `id`, `listcatid`, `topicid`, `admin_id`, `author`, `sourceid`, `addtime`, `edittime`, `publtime`, `title`, `alias`, `hometext`, `homeimgfile`, `homeimgalt`, `homeimgthumb`, `allowed_rating`, `hitstotal`, `hitscm`, `total_rating`, `click_rating`, `keywords` FROM `" . NV_PREFIXLANG . "_" . $module_data . "_" . $catid . "` WHERE `status`=1 ORDER BY `id` DESC LIMIT " . ( $page - 1 ) * $per_page . "," . $per_page;
		$result = $db->sql_query( $sql );

		$result_all = $db->sql_query( "SELECT FOUND_ROWS()" );
		list( $all_page ) = $db->sql_fetchrow( $result_all );

		while( $item = $db->sql_fetch_assoc( $result ) )
		{
			$array_img = ( ! empty( $item['homeimgthumb'] ) ) ? explode( "|", $item['homeimgthumb'] ) : $array_img = array( "", "" );

			if( $array_img[0] != "" and file_exists( NV_ROOTDIR . '/' . NV_FILES_DIR . '/' . $module_name . '/' . $array_img[0] ) )
			{
				$item['imghome'] = NV_BASE_SITEURL . NV_FILES_DIR . '/' . $module_name . '/' . $array_img[0];
			}
			elseif( nv_is_url( $item['homeimgfile'] ) )
			{
				$item['imghome'] = $item['homeimgfile'];
			}
			elseif( $item['homeimgfile'] != "" and file_exists( NV_UPLOADS_REAL_DIR . '/' . $module_name . '/' . $item['homeimgfile'] ) )
			{
				$item['imghome'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_name . '/' . $item['homeimgfile'];
			}
			else
			{
				$item['imghome'] = "";
			}

			$item['link'] = $global_array_cat[$catid]['link'] . "/" . $item['alias'] . "-" . $item['id'];
			$array_catcontent[] = $item;
		}
		unset( $sql, $result );

		$array_cat_other = array();

		if( $global_array_cat[$catid]['subcatid'] != "" )
		{
			$key = 0;
			$array_catid = explode( ",", $global_array_cat[$catid]['subcatid'] );

			foreach( $array_catid as $catid_i )
			{
				$array_cat_other[$key] = $global_array_cat[$catid_i];
				$sql = "SELECT `id`, `listcatid`, `topicid`, `admin_id`, `author`, `sourceid`, `addtime`, `edittime`, `publtime`, `title`, `alias`, `hometext`, `homeimgfile`, `homeimgalt`, `homeimgthumb`, `allowed_rating`, `hitstotal`, `hitscm`, `total_rating`, `click_rating`, `keywords` FROM `" . NV_PREFIXLANG . "_" . $module_data . "_" . $catid_i . "` WHERE `status`=1 ORDER BY `publtime` DESC LIMIT 0 , " . $global_array_cat[$catid_i]['numlinks'];
				$result = $db->sql_query( $sql );

				while( $item = $db->sql_fetch_assoc( $result ) )
				{
					$array_img = ( ! empty( $item['homeimgthumb'] ) ) ? explode( "|", $item['homeimgthumb'] ) : $array_img = array( "", "" );

					if( $array_img[0] != "" and file_exists( NV_ROOTDIR . '/' . NV_FILES_DIR . '/' . $module_name . '/' . $array_img[0] ) )
					{
						$item['imghome'] = NV_BASE_SITEURL . NV_FILES_DIR . '/' . $module_name . '/' . $array_img[0];
					}
					elseif( nv_is_url( $item['homeimgfile'] ) )
					{
						$item['imghome'] = $item['homeimgfile'];
					}
					elseif( $item['homeimgfile'] != "" and file_exists( NV_UPLOADS_REAL_DIR . '/' . $module_name . '/' . $item['homeimgfile'] ) )
					{
						$item['imghome'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_name . '/' . $item['homeimgfile'];
					}
					else
					{
						$item['imghome'] = "";
					}

					$item['link'] = $global_array_cat[$catid_i]['link'] . "/" . $item['alias'] . "-" . $item['id'];
					$array_cat_other[$key]['content'][] = $item;
				}

				unset( $sql, $result );
				++$key;
			}

			unset( $array_catid );
		}

		$generate_page = nv_alias_page( $page_title, $base_url, $all_page, $per_page, $page );
		$contents = viewcat_top( $array_catcontent, $generate_page );
		$contents .= call_user_func( "viewsubcat_main", $viewcat, $array_cat_other );
	}
	elseif( $viewcat == "viewcat_two_column" )
	{
		// Cac bai viet phan dau
		$array_catcontent = array();
		$sql = "SELECT `id`, `listcatid`, `topicid`, `admin_id`, `author`, `sourceid`, `addtime`, `edittime`, `publtime`, `title`, `alias`, `hometext`, `homeimgfile`, `homeimgalt`, `homeimgthumb`, `allowed_rating`, `hitstotal`, `hitscm`, `total_rating`, `click_rating`, `keywords` FROM `" . NV_PREFIXLANG . "_" . $module_data . "_" . $catid . "` WHERE `status`=1 ORDER BY `publtime` DESC LIMIT " . ( $page - 1 ) * $per_page . "," . $per_page;
		$result = $db->sql_query( $sql );

		while( $item = $db->sql_fetch_assoc( $result ) )
		{
			$array_img = ( ! empty( $item['homeimgthumb'] ) ) ? explode( "|", $item['homeimgthumb'] ) : $array_img = array( "", "" );

			if( $array_img[0] != "" and file_exists( NV_ROOTDIR . '/' . NV_FILES_DIR . '/' . $module_name . '/' . $array_img[0] ) )
			{
				$item['imghome'] = NV_BASE_SITEURL . NV_FILES_DIR . '/' . $module_name . '/' . $array_img[0];
			}
			elseif( nv_is_url( $item['homeimgfile'] ) )
			{
				$item['imghome'] = $item['homeimgfile'];
			}
			elseif( $item['homeimgfile'] != "" and file_exists( NV_UPLOADS_REAL_DIR . '/' . $module_name . '/' . $item['homeimgfile'] ) )
			{
				$item['imghome'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_name . '/' . $item['homeimgfile'];
			}
			else
			{
				$item['imghome'] = "";
			}

			$item['link'] = $global_array_cat[$catid]['link'] . "/" . $item['alias'] . "-" . $item['id'];
			$array_catcontent[] = $item;
		}
		unset( $sql, $result );
		// Het cac bai viet phan dau

		// cac bai viet cua cac chu de con
		$key = 0;
		$array_catid = explode( ",", $global_array_cat[$catid]['subcatid'] );

		foreach( $array_catid as $catid_i )
		{
			$array_cat_other[$key] = $global_array_cat[$catid_i];
			$sql = "SELECT `id`, `listcatid`, `topicid`, `admin_id`, `author`, `sourceid`, `addtime`, `edittime`, `publtime`, `title`, `alias`, `hometext`, `homeimgfile`, `homeimgalt`, `homeimgthumb`, `allowed_rating`, `hitstotal`, `hitscm`, `total_rating`, `click_rating`, `keywords` FROM `" . NV_PREFIXLANG . "_" . $module_data . "_" . $catid_i . "` WHERE `status`=1 ORDER BY `publtime` DESC LIMIT 0 , " . $global_array_cat[$catid_i]['numlinks'];
			$result = $db->sql_query( $sql );

			while( $item = $db->sql_fetch_assoc( $result ) )
			{
				$array_img = ( ! empty( $item['homeimgthumb'] ) ) ? explode( "|", $item['homeimgthumb'] ) : $array_img = array( "", "" );

				if( $array_img[0] != "" and file_exists( NV_ROOTDIR . '/' . NV_FILES_DIR . '/' . $module_name . '/' . $array_img[0] ) )
				{
					$item['imghome'] = NV_BASE_SITEURL . NV_FILES_DIR . '/' . $module_name . '/' . $array_img[0];
				}
				elseif( nv_is_url( $item['homeimgfile'] ) )
				{
					$item['imghome'] = $item['homeimgfile'];
				}
				elseif( $item['homeimgfile'] != "" and file_exists( NV_UPLOADS_REAL_DIR . '/' . $module_name . '/' . $item['homeimgfile'] ) )
				{
					$item['imghome'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_name . '/' . $item['homeimgfile'];
				}
				else
				{
					$item['imghome'] = "";
				}

				$item['link'] = $global_array_cat[$catid_i]['link'] . "/" . $item['alias'] . "-" . $item['id'];
				$array_cat_other[$key]['content'][] = $item;
			}

			++$key;
		}

		unset( $sql, $result );
		//Het cac bai viet cua cac chu de con
		$contents = call_user_func( $viewcat, $array_catcontent, $array_cat_other );
	}
	elseif( $viewcat == "viewcat_grid_new" or $viewcat == "viewcat_grid_old" )
	{
		$order_by = ( $viewcat == "viewcat_grid_new" ) ? "ORDER BY `publtime` DESC" : "ORDER BY `publtime` ASC";
		$sql = "SELECT SQL_CALC_FOUND_ROWS `id`, `listcatid`, `topicid`, `admin_id`, `author`, `sourceid`, `addtime`, `edittime`, `publtime`, `title`, `alias`, `hometext`, `homeimgfile`, `homeimgalt`, `homeimgthumb`, `allowed_rating`, `hitstotal`, `hitscm`, `total_rating`, `click_rating`, `keywords` FROM `" . NV_PREFIXLANG . "_" . $module_data . "_" . $catid . "` WHERE `status`=1 " . $order_by . " LIMIT " . ( $page - 1 ) * $per_page . "," . $per_page;
		$result = $db->sql_query( $sql );

		$result_all = $db->sql_query( "SELECT FOUND_ROWS()" );
		list( $all_page ) = $db->sql_fetchrow( $result_all );

		while( $item = $db->sql_fetch_assoc( $result ) )
		{
			$array_img = ( ! empty( $item['homeimgthumb'] ) ) ? explode( "|", $item['homeimgthumb'] ) : $array_img = array( "", "" );

			if( $array_img[0] != "" and file_exists( NV_ROOTDIR . '/' . NV_FILES_DIR . '/' . $module_name . '/' . $array_img[0] ) )
			{
				$item['imghome'] = NV_BASE_SITEURL . NV_FILES_DIR . '/' . $module_name . '/' . $array_img[0];
			}
			elseif( nv_is_url( $item['homeimgfile'] ) )
			{
				$item['imghome'] = $item['homeimgfile'];
			}
			elseif( $item['homeimgfile'] != "" and file_exists( NV_UPLOADS_REAL_DIR . '/' . $module_name . '/' . $item['homeimgfile'] ) )
			{
				$item['imghome'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_name . '/' . $item['homeimgfile'];
			}
			else
			{
				$item['imghome'] = NV_BASE_SITEURL . 'themes/' . $global_config['site_theme'] . '/images/no_image.gif';
			}

			$item['link'] = $global_array_cat[$catid]['link'] . "/" . $item['alias'] . "-" . $item['id'];
			$array_catpage[] = $item;
		}

		$viewcat = "viewcat_grid_new";
		$generate_page = nv_alias_page( $page_title, $base_url, $all_page, $per_page, $page );
		$contents = call_user_func( $viewcat, $array_catpage, $catid, $generate_page );
	}
	elseif( $viewcat == "viewcat_list_new" or $viewcat == "viewcat_list_old" ) // Xem theo tieu de
	{
		$order_by = ( $viewcat == "viewcat_list_new" ) ? "ORDER BY `publtime` DESC" : "ORDER BY `publtime` ASC";
		$sql = "SELECT SQL_CALC_FOUND_ROWS `id`, `listcatid`, `topicid`, `admin_id`, `author`, `sourceid`, `addtime`, `edittime`, `publtime`, `title`, `alias`, `hometext`, `homeimgfile`, `homeimgalt`, `homeimgthumb`, `allowed_rating`, `hitstotal`, `hitscm`, `total_rating`, `click_rating`, `keywords` FROM `" . NV_PREFIXLANG . "_" . $module_data . "_" . $catid . "` WHERE `status`=1 " . $order_by . " LIMIT " . ( $page - 1 ) * $per_page . "," . $per_page;
		$result = $db->sql_query( $sql );

		$result_all = $db->sql_query( "SELECT FOUND_ROWS()" );
		list( $all_page ) = $db->sql_fetchrow( $result_all );

		while( $item = $db->sql_fetch_assoc( $result ) )
		{
			$item['imghome'] = "";
			$item['link'] = $global_array_cat[$catid]['link'] . "/" . $item['alias'] . "-" . $item['id'];
			$array_catpage[] = $item;
		}

		$viewcat = "viewcat_list_new";
		$generate_page = nv_alias_page( $page_title, $base_url, $all_page, $per_page, $page );
		$contents = call_user_func( $viewcat, $array_catpage, $catid, ( $page - 1 ) * $per_page, $generate_page );
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