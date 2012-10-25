<?php

/**
 * @Project NUKEVIET 3.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2012 VINADES.,JSC. All rights reserved
 * @Createdate 3-6-2010 0:14
 */

if( ! defined( 'NV_IS_MOD_NEWS' ) ) die( 'Stop!!!' );

if( isset( $array_op[1] ) )
{
	$alias = trim( $array_op[1] );
	$page = ( isset( $array_op[2] ) and substr( $array_op[2], 0, 5 ) == "page-" ) ? intval( substr( $array_op[2], 5 ) ) : 1;

	list( $bid, $page_title, $description, $key_words ) = $db->sql_fetchrow( $db->sql_query( "SELECT `bid`, `title`, `description`, `keywords` FROM `" . NV_PREFIXLANG . "_" . $module_data . "_block_cat` WHERE `alias`=" . $db->dbescape( $alias ) ) );

	if( $bid > 0 )
	{
		$base_url = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=groups/" . $alias;

		$array_mod_title[] = array(
			'catid' => 0,
			'title' => $page_title,
			'link' => $base_url );

		$query = $db->sql_query( "SELECT SQL_CALC_FOUND_ROWS t1.id, t1.catid, t1.admin_id, t1.author, t1.sourceid, t1.addtime, t1.edittime, t1.publtime, t1.title, t1.alias, t1.hometext, t1.homeimgfile, t1.homeimgalt, t1.homeimgthumb, t1.allowed_rating, t1.hitstotal, t1.hitscm, t1.total_rating, t1.click_rating, t1.keywords, t2.weight FROM `" . NV_PREFIXLANG . "_" . $module_data . "_rows` as t1 INNER JOIN `" . NV_PREFIXLANG . "_" . $module_data . "_block` AS t2 ON t1.id = t2.id WHERE t2.bid= " . $bid . " AND t1.status= 1 ORDER BY t2.weight ASC LIMIT " . ( $page - 1 ) * $per_page . "," . $per_page );

		$result_all = $db->sql_query( "SELECT FOUND_ROWS()" );
		list( $all_page ) = $db->sql_fetchrow( $result_all );

		$topic_array = array();
		$end_weight = 0;

		while( $item = $db->sql_fetch_assoc( $query ) )
		{
			$array_img = ( ! empty( $item['homeimgthumb'] ) ) ? explode( "|", $item['homeimgthumb'] ) : $array_img = array( "", "" );

			if( $array_img[0] != "" and file_exists( NV_ROOTDIR . '/' . NV_FILES_DIR . '/' . $module_name . '/' . $array_img[0] ) )
			{
				$item['src'] = NV_BASE_SITEURL . NV_FILES_DIR . '/' . $module_name . '/' . $array_img[0];
			}
			elseif( nv_is_url( $item['homeimgfile'] ) )
			{
				$item['src'] = $item['homeimgfile'];
			}
			elseif( $item['homeimgfile'] != "" and file_exists( NV_UPLOADS_REAL_DIR . '/' . $module_name . '/' . $item['homeimgfile'] ) )
			{
				$item['src'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_name . '/' . $item['homeimgfile'];
			}
			else
			{
				$item['src'] = "";
			}

			$item['alt'] = ! empty( $item['homeimgalt'] ) ? $item['homeimgalt'] : $item['title'];
			$item['width'] = $module_config[$module_name]['homewidth'];

			$end_weight = $item['weight'];

			$item['link'] = $global_array_cat[$item['catid']]['link'] . "/" . $item['alias'] . "-" . $item['id'];
			$topic_array[] = $item;
		}

		$db->sql_freeresult( $query );
		unset( $query, $row );

		$topic_other_array = array();
		$query = $db->sql_query( "SELECT t1.id, t1.catid, t1.addtime, t1.edittime, t1.publtime, t1.title, t1.alias, t1.hitstotal FROM `" . NV_PREFIXLANG . "_" . $module_data . "_rows` as t1 INNER JOIN `" . NV_PREFIXLANG . "_" . $module_data . "_block` AS t2 ON t1.id = t2.id WHERE t2.bid= " . $bid . " AND t2.weight > " . $end_weight . " ORDER BY t2.weight ASC LIMIT 0," . $st_links . "" );

		while( $item = $db->sql_fetch_assoc( $query ) )
		{
			$item['link'] = $global_array_cat[$item['catid']]['link'] . "/" . $item['alias'] . "-" . $item['id'];
			$topic_other_array[] = $item;
		}

		unset( $query, $row, $arr_listcatid );

		$generate_page = nv_alias_page( $page_title, $base_url, $all_page, $per_page, $page );
		$contents = topic_theme( $topic_array, $topic_other_array, $generate_page );
		if( $page > 1 )
		{
			$page_title .= ' ' . NV_TITLEBAR_DEFIS . ' ' . $lang_global['page'] . ' ' . $page;
		}
	}
}
else
{
	$array_cat = array();
	$key = 0;

	$query_cat = $db->sql_query( "SELECT `bid`, `number`, `title`, `alias` FROM `" . NV_PREFIXLANG . "_" . $module_data . "_block_cat` ORDER BY `weight` ASC" );

	while( list( $bid, $numberlink, $btitle, $balias ) = $db->sql_fetchrow( $query_cat, 1 ) )
	{
		$array_cat[$key] = array(
			'catid' => $bid,
			'alias' => '',
			'subcatid' => '',
			'title' => $btitle,
			'link' => NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=groups/" . $balias );

		$query = $db->sql_query( "SELECT t1.id, t1.catid, t1.admin_id, t1.author, t1.sourceid, t1.addtime, t1.edittime, t1.publtime, t1.title, t1.alias, t1.hometext, t1.homeimgfile, t1.homeimgalt, t1.homeimgthumb, t1.allowed_rating, t1.hitstotal, t1.hitscm, t1.total_rating, t1.click_rating, t1.keywords FROM `" . NV_PREFIXLANG . "_" . $module_data . "_rows` as t1 INNER JOIN `" . NV_PREFIXLANG . "_" . $module_data . "_block` AS t2 ON t1.id = t2.id WHERE t2.bid= " . $bid . " AND t1.status= 1 ORDER BY t2.weight ASC LIMIT 0," . $numberlink );

		while( $item = $db->sql_fetch_assoc( $query ) )
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

			$item['alt'] = ! empty( $item['homeimgalt'] ) ? $item['homeimgalt'] : $item['title'];
			$item['width'] = $module_config[$module_name]['homewidth'];

			$item['link'] = $global_array_cat[$item['catid']]['link'] . "/" . $item['alias'] . "-" . $item['id'];
			$array_cat[$key]['content'][] = $item;
		}
		++$key;
	}

	$viewcat = $module_config[$module_name]['indexfile'];

	if( $viewcat != "viewcat_main_left" and $viewcat != "viewcat_main_bottom" )
	{
		$viewcat == "viewcat_main_right";
	}

	$contents = viewsubcat_main( $viewcat, $array_cat );

	$page_title = $module_info['funcs']['groups']['func_custom_name'];
	$key_words = $module_info['keywords'];
}

include ( NV_ROOTDIR . "/includes/header.php" );
echo nv_site_theme( $contents );
include ( NV_ROOTDIR . "/includes/footer.php" );

?>