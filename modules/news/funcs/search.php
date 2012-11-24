<?php

/**
 * @Project NUKEVIET 3.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2012 VINADES.,JSC. All rights reserved
 * @Createdate 10-5-2010 0:14
 */

if( ! defined( 'NV_IS_MOD_NEWS' ) ) die( 'Stop!!!' );

function GetSourceNews( $sourceid )
{
	global $db, $module_data;

	if( $sourceid > 0 )
	{
		$sql = "SELECT title FROM `" . NV_PREFIXLANG . "_" . $module_data . "_sources` WHERE sourceid = '" . $sourceid . "'";
		$re = $db->sql_query( $sql );

		if( list( $title ) = $db->sql_fetchrow( $re ) )
		{
			return $title;
		}
	}
	return "-/-";
}

function BoldKeywordInStr( $str, $keyword )
{
	$str = nv_clean60( $str, 300 );
	$tmp = explode( " ", $keyword );

	foreach( $tmp as $k )
	{
		$tp = strtolower( $k );
		$str = str_replace( $tp, "<span class=\"keyword\">" . $tp . "</span>", $str );
		$tp = strtoupper( $k );
		$str = str_replace( $tp, "<span class=\"keyword\">" . $tp . "</span>", $str );
		$k[0] = strtoupper( $k[0] );
		$str = str_replace( $k, "<span class=\"keyword\">" . $k . "</span>", $str );
	}

	return $str;
}

$key = filter_text_input( 'q', 'get', '', 1, 1000 );
$pages = $nv_Request->get_int( 'page', 'get', 0 );
$from_date = filter_text_input( 'from_date', 'get', '', 1, 1000 );
$to_date = filter_text_input( 'to_date', 'get', '', 1, 100 );
$catid = $nv_Request->get_int( 'catid', 'get', 0 );
$check_num = filter_text_input( 'choose', 'get', 1, 1, 1 );
$date_array['from_date'] = $from_date;
$date_array['to_date'] = $to_date;
$per_pages = 20;

if( $key == '' ) $key = isset( $_SESSION["keyword"] ) ? $_SESSION["keyword"] : '';
$array_cat_search = array();

foreach( $global_array_cat as $arr_cat_i )
{
	$array_cat_search[$arr_cat_i['catid']] = array(
		'catid' => $arr_cat_i['catid'],
		'title' => $arr_cat_i['title'],
		'select' => ( $arr_cat_i['catid'] == $catid ) ? "selected" : "" );
}

$array_cat_search[0]['title'] = $lang_module['search_all'];

$contents = call_user_func( "search_theme", $key, $check_num, $date_array, $array_cat_search );
$where = "";
$tbl_src = "";

if( isset( $key{NV_MIN_SEARCH_LENGTH - 1} ) )
{
	$dbkey = $db->dblikeescape( $key );

	if( $check_num == 1 )
	{
		$tbl_src = " LEFT JOIN `" . NV_PREFIXLANG . "_" . $module_data . "_bodytext` as tb2 ON ( tb1.id =  tb2.id ) ";
		$where = "AND ( tb1.title LIKE '%" . $dbkey . "%' OR tb1.keywords LIKE '%" . $dbkey . "%' OR tb2.bodytext LIKE '%" . $dbkey . "%' ) ";
	}
	elseif( $check_num == 2 )
	{
		$where = "AND ( tb1.author LIKE '%" . $dbkey . "%' ) ";
	}
	elseif( $check_num == 3 )
	{
		$where = "AND (tb1.sourcetext LIKE '%" . $dbkey . "%')";
	}
	else
	{
		$tbl_src = " LEFT JOIN `" . NV_PREFIXLANG . "_" . $module_data . "_bodytext` as tb2 ON ( tb1.id =  tb2.id )";
		$where = " AND ( tb1.title LIKE '%" . $dbkey . "%' OR tb1.keywords LIKE '%" . $dbkey . "%' ";
		$where .= " OR tb1.author LIKE '%" . $dbkey . "%' OR tb1.sourcetext LIKE '%" . $dbkey . "%' OR tb2.bodytext LIKE '%" . $dbkey . "%')";
	}

	if( $to_date != "" )
	{
		preg_match( "/^([0-9]{1,2})\.([0-9]{1,2})\.([0-9]{4})$/", $to_date, $m );
		$tdate = mktime( 0, 0, 0, $m[2], $m[1], $m[3] );
		preg_match( "/^([0-9]{1,2})\.([0-9]{1,2})\.([0-9]{4})$/", $from_date, $m );
		$fdate = mktime( 0, 0, 0, $m[2], $m[1], $m[3] );
		$where .= " AND ( `publtime` < $fdate AND `publtime` >= $tdate  ) ";
	}

	if( $catid > 0 )
	{
		$table_search = NV_PREFIXLANG . "_" . $module_data . "_" . $catid;
	}
	else
	{
		$table_search = NV_PREFIXLANG . "_" . $module_data . "_rows";
	}

	$sql = " SELECT SQL_CALC_FOUND_ROWS tb1.id,tb1.title,tb1.alias,tb1.catid,tb1.hometext,tb1.author,tb1.publtime,tb1.homeimgfile, tb1.homeimgthumb,tb1.sourceid
	FROM `" . $table_search . "` as tb1 " . $tbl_src . " 
	WHERE tb1.status=1 " . $where . " ORDER BY tb1.id DESC LIMIT " . $pages . "," . $per_pages;

	$result = $db->sql_query( $sql );
	$result_all = $db->sql_query( "SELECT FOUND_ROWS()" );
	list( $numRecord ) = $db->sql_fetchrow( $result_all );

	$array_content = array();
	$url_link = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=";

	while( list( $id, $title, $alias, $catid, $hometext, $author, $publtime, $homeimgfile, $homeimgthumb, $sourceid ) = $db->sql_fetchrow( $result ) )
	{
		if( ! empty( $homeimgthumb ) )
		{
			$array_img = explode( "|", $homeimgthumb );
		}
		else
		{
			$array_img = array( "", "" );
		}

		$img_src = "";

		if( $array_img[0] != "" and file_exists( NV_ROOTDIR . '/' . NV_FILES_DIR . '/' . $module_name . '/' . $array_img[0] ) )
		{
			$img_src = NV_BASE_SITEURL . NV_FILES_DIR . '/' . $module_name . '/' . $array_img[0];
		}
		elseif( $homeimgfile != "" and file_exists( NV_UPLOADS_REAL_DIR . '/' . $module_name . '/' . $homeimgfile ) )
		{
			$img_src = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_name . '/' . $homeimgfile;
		}
		elseif( nv_is_url( $homeimgfile ) )
		{
			$img_src = $homeimgfile;
		}

		$array_content[] = array(
			"id" => $id,
			"title" => $title,
			"alias" => $alias,
			"catid" => $catid,
			"hometext" => $hometext,
			"author" => $author,
			"publtime" => $publtime,
			"homeimgfile" => $img_src,
			"sourceid" => $sourceid );
	}

	$contents .= call_user_func( "search_result_theme", $key, $numRecord, $per_pages, $pages, $array_content, $url_link, $catid );
}

if( empty( $key ) )
{
	$page_title = $module_info['custom_title'];
}
else
{
	$page_title = $key . ' ' . NV_TITLEBAR_DEFIS . ' ' . $lang_module['search_title'] . ' ' . NV_TITLEBAR_DEFIS . ' ' . $module_info['custom_title'];
}

$key_words = $module_info['keywords'];
$mod_title = isset( $lang_module['main_title'] ) ? $lang_module['main_title'] : $module_info['custom_title'];

include ( NV_ROOTDIR . "/includes/header.php" );
echo nv_site_theme( $contents );
include ( NV_ROOTDIR . "/includes/footer.php" );

?>