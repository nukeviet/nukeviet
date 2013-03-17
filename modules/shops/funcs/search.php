<?php

/**
 * @Project NUKEVIET 3.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES., JSC. All rights reserved
 * @Createdate 10-5-2010 0:14
 */

if ( ! defined( 'NV_IS_MOD_SHOPS' ) ) die( 'Stop!!!' );

function BoldKeywordInStr ( $str, $keyword )
{
	$tmp = explode( " ", $keyword );
	foreach ( $tmp as $k )
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
$from_date = filter_text_input( 'from_date', 'get', '', 1, 1000 );
$to_date = filter_text_input( 'to_date', 'get', '', 1, 100 );
$catid = $nv_Request->get_int( 'catid', 'get', 0 );
$check_num = filter_text_input( 'choose', 'get', 1, 1, 1 );
$pages = filter_text_input( 'page', 'get', 0, 1, 1000 );
$date_array['from_date'] = $from_date;
$date_array['to_date'] = $to_date;
$per_pages = 20;

$array_cat_search = array();
$array_cat_search[0] = array( 
	'catid' => 0,
	'title' => $lang_module['search_all'],
	'select' => ( 0 == $catid ) ? "selected" : "",
	'xtitle' => '' 
);

foreach ( $global_array_cat as $arr_cat_i )
{
	$xtitle = "";
	if ( $arr_cat_i['lev'] > 0 )
	{
		for ( $i = 1; $i <= $arr_cat_i['lev']; $i ++ )
		{
			$xtitle .= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
		}
	}
	
	$array_cat_search[$arr_cat_i['catid']] = array( 
		'catid' => $arr_cat_i['catid'],
		'title' => $arr_cat_i['title'],
		'select' => ( $arr_cat_i['catid'] == $catid ) ? "selected" : "",
		'xtitle' => $xtitle 
	);
}

$contents = call_user_func( "search_theme", $key, $check_num, $date_array, $array_cat_search );

$where = "";
$tbl_src = "";

if ( strlen( $key ) >= NV_MIN_SEARCH_LENGTH )
{
	$dbkey = $db->dblikeescape( $key );
	$where = "AND ( `product_code` LIKE '%" . $dbkey . "%' OR " . NV_LANG_DATA . "_title LIKE '%" . $dbkey . "%' OR " . NV_LANG_DATA . "_bodytext LIKE '%" . $dbkey . "%' OR " . NV_LANG_DATA . "_keywords LIKE '%" . $dbkey . "%' ) ";
	
	if ( $catid != 0 )
	{
		if ( $global_array_cat[$catid]['numsubcat'] == 0 )
		{
			$where .= "AND `listcatid`=" . $catid;
		}
		else
		{
			$array_cat = array();
			$array_cat = GetCatidInParent( $catid );
			$where .= "AND `listcatid` IN (" . implode( ",", $array_cat ) . ")";
		}
	}
	
	if ( $to_date != "" )
	{
		preg_match( "/^([0-9]{1,2})\.([0-9]{1,2})\.([0-9]{4})$/", $to_date, $m );
		$tdate = mktime( 0, 0, 0, $m[2], $m[1], $m[3] );
		preg_match( "/^([0-9]{1,2})\.([0-9]{1,2})\.([0-9]{4})$/", $from_date, $m );
		$fdate = mktime( 0, 0, 0, $m[2], $m[1], $m[3] );
		$where .= " AND ( `publtime` < $fdate AND `publtime` >= $tdate  ) ";
	}
	
	$table_search = $db_config['prefix'] . "_" . $module_data . "_rows";
	
	$sql = " SELECT SQL_CALC_FOUND_ROWS `id`, `" . NV_LANG_DATA . "_title`, `" . NV_LANG_DATA . "_alias`, `listcatid`, `" . NV_LANG_DATA . "_hometext`, `publtime`, `homeimgfile`, `homeimgthumb`, `source_id` FROM `" . $table_search . "` WHERE `status`=1 " . $where . " ORDER BY `id` DESC LIMIT " . $pages . "," . $per_pages;
	
	$result = $db->sql_query( $sql );
	$result_all = $db->sql_query( "SELECT FOUND_ROWS()" );
	list( $numRecord ) = $db->sql_fetchrow( $result_all );
	
	$array_content = array();
	$url_link = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=";
	
	while ( list( $id, $title, $alias, $listcatid, $hometext, $publtime, $homeimgfile, $homeimgthumb, $sourceid ) = $db->sql_fetchrow( $result ) )
	{
		$thumb = explode( "|", $homeimgthumb );
		if ( ! empty( $thumb[0] ) && ! nv_is_url( $thumb[0] ) )
		{
			$thumb[0] = NV_BASE_SITEURL . NV_UPLOADS_DIR . "/" . $module_name . "/" . $thumb[0];
		}
		else
		{
			$thumb[0] = NV_BASE_SITEURL . "themes/" . $module_info['template'] . "/images/" . $module_name . "/no-image.jpg";
		}
		
		$array_content[] = array( 
			"id" => $id,
			"title" => $title,
			"alias" => $alias,
			"listcatid" => $listcatid,
			"hometext" => $hometext,
			"publtime" => $publtime,
			"homeimgthumb" => $thumb[0],
			"sourceid" => $sourceid 
		);
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
$mod_title = $lang_module['main_title'];

include ( NV_ROOTDIR . "/includes/header.php" );
echo nv_site_theme( $contents );
include ( NV_ROOTDIR . "/includes/footer.php" );

?>