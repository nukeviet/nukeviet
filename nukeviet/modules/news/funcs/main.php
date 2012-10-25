<?php

/**
 * @Project NUKEVIET 3.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2012 VINADES.,JSC. All rights reserved
 * @Createdate 3-6-2010 0:14
 */

if( ! defined( 'NV_IS_MOD_NEWS' ) ) die( 'Stop!!!' );

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
	$viewcat = $module_config[$module_name]['indexfile'];
	$array_catpage = array();
	$array_cat_other = array();
	$st_links = $st_links;
	$base_url = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=main";

	if( $viewcat == "viewcat_page_new" or $viewcat == "viewcat_page_old" )
	{
		$order_by = ( $viewcat == "viewcat_page_new" ) ? "ORDER BY `publtime` DESC" : "ORDER BY `publtime` ASC";

		$sql = "SELECT SQL_CALC_FOUND_ROWS `id`, `catid`, `listcatid`, `topicid`, `admin_id`, `author`, `sourceid`, `addtime`, `edittime`, `publtime`, `title`, `alias`, `hometext`, `homeimgfile`, `homeimgalt`, `homeimgthumb`, `allowed_rating`, `hitstotal`, `hitscm`, `total_rating`, `click_rating`, `keywords` FROM `" . NV_PREFIXLANG . "_" . $module_data . "_rows` WHERE `status`= 1 AND `inhome`='1' " . $order_by . " LIMIT  " . ( $page - 1 ) * $per_page . "," . $per_page;
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

			$item['link'] = $global_array_cat[$item['catid']]['link'] . "/" . $item['alias'] . "-" . $item['id'];
			$array_catpage[] = $item;
			$end_publtime = $item['publtime'];
		}

		if( $viewcat == "viewcat_page_new" )
		{
			$sql = "SELECT `id`, `catid`, `addtime`, `edittime`, `publtime`, `title`, `alias`, `hitstotal` FROM `" . NV_PREFIXLANG . "_" . $module_data . "_rows` WHERE `status`= 1 AND `inhome`='1' AND `publtime` < " . $end_publtime . " " . $order_by . " LIMIT 0," . $st_links;
		}
		else
		{
			$sql = "SELECT `id`, `catid`, `addtime`, `edittime`, `publtime`, `title`, `alias`, `hitstotal` FROM `" . NV_PREFIXLANG . "_" . $module_data . "_rows` WHERE `status`= 1 AND `inhome`='1' AND `publtime` > " . $end_publtime . "  " . $order_by . " LIMIT 0," . $st_links;
		}

		$result = $db->sql_query( $sql );

		while( $item = $db->sql_fetch_assoc( $result ) )
		{
			$item['link'] = $global_array_cat[$item['catid']]['link'] . "/" . $item['alias'] . "-" . $item['id'];
			$array_cat_other[] = $item;
		}

		$viewcat = "viewcat_page_new";
		$generate_page = nv_alias_page( $page_title, $base_url, $all_page, $per_page, $page );
		$contents = call_user_func( $viewcat, $array_catpage, $array_cat_other, $generate_page );
	}
	elseif( $viewcat == "viewcat_main_left" or $viewcat == "viewcat_main_right" or $viewcat == "viewcat_main_bottom" )
	{
		$array_cat = array();

		$key = 0;
		foreach( $global_array_cat as $_catid => $array_cat_i )
		{
			if( $array_cat_i['parentid'] == 0 and $array_cat_i['inhome'] == 1 )
			{
				$array_cat[$key] = $array_cat_i;
				$sql = "SELECT `id`, `listcatid`, `topicid`, `admin_id`, `author`, `sourceid`, `addtime`, `edittime`, `publtime`, `title`, `alias`, `hometext`, `homeimgfile`, `homeimgalt`, `homeimgthumb`, `allowed_rating`, `hitstotal`, `hitscm`, `total_rating`, `click_rating`, `keywords` FROM `" . NV_PREFIXLANG . "_" . $module_data . "_" . $_catid . "` WHERE `status`= 1 AND `inhome`='1' ORDER BY `publtime` DESC LIMIT 0 , " . $array_cat_i['numlinks'];
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

					$item['link'] = $array_cat_i['link'] . "/" . $item['alias'] . "-" . $item['id'];
					$array_cat[$key]['content'][] = $item;
				}

				++$key;
			}
		}

		$contents = viewsubcat_main( $viewcat, $array_cat );
	}
	elseif( $viewcat == "viewcat_two_column" )
	{
		// Cac bai viet phan dau
		$array_content = $array_catpage = array();

		// cac bai viet cua cac chu de con
		$key = 0;
		foreach( $global_array_cat as $_catid => $array_cat_i )
		{
			if( $array_cat_i['parentid'] == 0 and $array_cat_i['inhome'] == 1 )
			{
				$array_catpage[$key] = $array_cat_i;
				$sql = "SELECT `id`, `listcatid`, `topicid`, `admin_id`, `author`, `sourceid`, `addtime`, `edittime`, `publtime`, `title`, `alias`, `hometext`, `homeimgfile`, `homeimgalt`, `homeimgthumb`, `allowed_rating`, `hitstotal`, `hitscm`, `total_rating`, `click_rating`, `keywords` FROM `" . NV_PREFIXLANG . "_" . $module_data . "_" . $_catid . "` WHERE `status`= 1 AND `inhome`='1' ORDER BY `publtime` DESC LIMIT 0 , " . $array_cat_i['numlinks'];
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

					$item['link'] = $array_cat_i['link'] . "/" . $item['alias'] . "-" . $item['id'];
					$array_catpage[$key]['content'][] = $item;
				}
			}

			++$key;
		}
		unset( $sql, $result );
		//Het cac bai viet cua cac chu de con
		$contents = viewcat_two_column( $array_content, $array_catpage );
	}
	elseif( $viewcat == "viewcat_grid_new" or $viewcat == "viewcat_grid_old" )
	{
		$order_by = ( $viewcat == "viewcat_grid_new" ) ? "ORDER BY `publtime` DESC" : "ORDER BY `publtime` ASC";
		$sql = "SELECT SQL_CALC_FOUND_ROWS `id`, `catid`, `topicid`, `admin_id`, `author`, `sourceid`, `addtime`, `edittime`, `publtime`, `title`, `alias`, `hometext`, `homeimgfile`, `homeimgalt`, `homeimgthumb`, `allowed_rating`, `hitstotal`, `hitscm`, `total_rating`, `click_rating`, `keywords` FROM `" . NV_PREFIXLANG . "_" . $module_data . "_rows` WHERE `status`= 1 AND `inhome`='1' " . $order_by . " LIMIT  " . ( $page - 1 ) * $per_page . "," . $per_page;
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

			$item['link'] = $global_array_cat[$item['catid']]['link'] . "/" . $item['alias'] . "-" . $item['id'];
			$array_catpage[] = $item;
		}

		$viewcat = "viewcat_grid_new";
		$generate_page = nv_alias_page( $page_title, $base_url, $all_page, $per_page, $page );
		$contents = call_user_func( $viewcat, $array_catpage, 0, $generate_page );
	}
	elseif( $viewcat == "viewcat_list_new" or $viewcat == "viewcat_list_old" ) // Xem theo tieu de
	{
		$order_by = ( $viewcat == "viewcat_list_new" ) ? "ORDER BY `publtime` DESC" : "ORDER BY `publtime` ASC";
		$sql = "SELECT SQL_CALC_FOUND_ROWS `id`, `catid`, `topicid`, `admin_id`, `author`, `sourceid`, `addtime`, `edittime`, `publtime`, `title`, `alias`, `hometext`, `homeimgfile`, `homeimgalt`, `homeimgthumb`, `allowed_rating`, `hitstotal`, `hitscm`, `total_rating`, `click_rating`, `keywords` FROM `" . NV_PREFIXLANG . "_" . $module_data . "_rows` WHERE `status`= 1 AND `inhome`='1' " . $order_by . " LIMIT  " . ( $page - 1 ) * $per_page . "," . $per_page;
		$result = $db->sql_query( $sql );

		$result_all = $db->sql_query( "SELECT FOUND_ROWS()" );
		list( $all_page ) = $db->sql_fetchrow( $result_all );

		while( $item = $db->sql_fetch_assoc( $result ) )
		{
			$item['imghome'] = "";

			$item['link'] = $global_array_cat[$item['catid']]['link'] . "/" . $item['alias'] . "-" . $item['id'];
			$array_catpage[] = $item;
		}

		$viewcat = "viewcat_list_new";
		$generate_page = nv_alias_page( $page_title, $base_url, $all_page, $per_page, $page );
		$contents = call_user_func( $viewcat, $array_catpage, 0, ( $page - 1 ) * $per_page, $generate_page );
	}

	if( ! defined( 'NV_IS_MODADMIN' ) and $contents != "" and $cache_file != "" )
	{
		nv_set_cache( $cache_file, $contents );
	}
}

if( $page > 1 )
{
	$page_title .= ' ' . NV_TITLEBAR_DEFIS . ' ' . $lang_global['page'] . ' ' . $page;
}

include ( NV_ROOTDIR . "/includes/header.php" );
echo nv_site_theme( $contents );
include ( NV_ROOTDIR . "/includes/footer.php" );

?>