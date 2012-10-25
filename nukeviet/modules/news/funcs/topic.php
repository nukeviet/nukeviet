<?php

/**
 * @Project NUKEVIET 3.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2012 VINADES.,JSC. All rights reserved
 * @Createdate 3-6-2010 0:14
 */

if( ! defined( 'NV_IS_MOD_NEWS' ) ) die( 'Stop!!!' );

$topicalias = isset( $array_op[1] ) ? trim( $array_op[1] ) : '';
$page = ( isset( $array_op[2] ) and substr( $array_op[2], 0, 5 ) == "page-" ) ? intval( substr( $array_op[2], 5 ) ) : 1;

list( $topicid, $page_title, $description, $key_words ) = $db->sql_fetchrow( $db->sql_query( "SELECT `topicid`, `title`, `description`, `keywords` FROM `" . NV_PREFIXLANG . "_" . $module_data . "_topics` WHERE `alias`=" . $db->dbescape( $topicalias ) . "" ) );

if( $topicid > 0 )
{
	$array_mod_title[] = array(
		'catid' => 0,
		'title' => $page_title,
		'link' => NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=topic/" . $topicalias );

	$query = $db->sql_query( "SELECT SQL_CALC_FOUND_ROWS `id`, `catid`, `topicid`, `admin_id`, `author`, `sourceid`, `addtime`, `edittime`, `publtime`, `title`, `alias`, `hometext`, `homeimgfile`, `homeimgalt`, `homeimgthumb`, `allowed_rating`, `hitstotal`, `hitscm`, `total_rating`, `click_rating`, `keywords` FROM `" . NV_PREFIXLANG . "_" . $module_data . "_rows` WHERE `status`=1 AND `topicid` = '" . $topicid . "' ORDER BY `publtime` DESC LIMIT " . ( $page - 1 ) * $per_page . "," . $per_page );
	$result_all = $db->sql_query( "SELECT FOUND_ROWS()" );
	list( $all_page ) = $db->sql_fetchrow( $result_all );

	$topic_array = array();
	$end_publtime = 0;

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

		$end_publtime = $item['publtime'];

		$item['link'] = $global_array_cat[$item['catid']]['link'] . "/" . $item['alias'] . "-" . $item['id'];
		$topic_array[] = $item;
	}
	$db->sql_freeresult( $query );
	unset( $query, $row );

	$topic_other_array = array();
	$query = $db->sql_query( "SELECT `id`, `catid`, `addtime`, `edittime`, `publtime`, `title`, `alias`, `hitstotal` FROM `" . NV_PREFIXLANG . "_" . $module_data . "_rows` WHERE `status`=1 AND `topicid` = " . $topicid . " AND `publtime` < " . $end_publtime . " ORDER BY `publtime` DESC LIMIT 0," . $st_links . "" );

	while( $item = $db->sql_fetch_assoc( $query ) )
	{
		$item['link'] = $global_array_cat[$item['catid']]['link'] . "/" . $item['alias'] . "-" . $item['id'];
		$topic_other_array[] = $item;
	}

	unset( $query, $row, $arr_listcatid );

	$base_url = "" . NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=topic/" . $topicalias;
	$generate_page = nv_alias_page( $page_title, $base_url, $all_page, $per_page, $page );
	$contents = topic_theme( $topic_array, $topic_other_array, $generate_page, $page_title, $description );

	if( $page > 1 )
	{
		$page_title .= ' ' . NV_TITLEBAR_DEFIS . ' ' . $lang_global['page'] . ' ' . $page;
	}
}
else
{
	Header( "Location: " . nv_url_rewrite( NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name, true ) );
	exit();
}

include ( NV_ROOTDIR . "/includes/header.php" );
echo nv_site_theme( $contents );
include ( NV_ROOTDIR . "/includes/footer.php" );

?>