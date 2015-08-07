<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 10-5-2010 0:14
 */

if( ! defined( 'NV_IS_MOD_NEWS' ) ) die( 'Stop!!!' );

function GetSourceNews( $sourceid )
{
	global $db, $module_data;

	if( $sourceid > 0 )
	{
		$sql = 'SELECT title FROM ' . NV_PREFIXLANG . '_' . $module_data . '_sources WHERE sourceid = ' . $sourceid;
		$re = $db->query( $sql );

		if( list( $title ) = $re->fetch( 3 ) )
		{
			return $title;
		}
	}
	return '-/-';
}

function BoldKeywordInStr( $str, $keyword )
{
	$str = nv_clean60( $str, 300 );
	if( ! empty( $keyword ) )
	{
		$tmp = explode( ' ', $keyword );
		foreach( $tmp as $k )
		{
			$tp = strtolower( $k );
			$str = str_replace( $tp, '<span class="keyword">' . $tp . '</span>', $str );
			$tp = strtoupper( $k );
			$str = str_replace( $tp, '<span class="keyword">' . $tp . '</span>', $str );
			$k[0] = strtoupper( $k[0] );
			$str = str_replace( $k, '<span class="keyword">' . $k . '</span>', $str );
		}
	}
	return $str;
}

$key = $nv_Request->get_title( 'q', 'get', '' );
$key = str_replace( '+', ' ', $key );
$key = nv_substr( $key , 0, NV_MAX_SEARCH_LENGTH );
$keyhtml = nv_htmlspecialchars( $key );

$page = $nv_Request->get_int( 'page', 'get', 1 );
$from_date = $nv_Request->get_title( 'from_date', 'get', '', 0 );
$to_date = $nv_Request->get_title( 'to_date', 'get', '', 0 );
$catid = $nv_Request->get_int( 'catid', 'get', 0 );
$check_num = $nv_Request->get_title( 'choose', 'get', 1, 1 );
$date_array['from_date'] = preg_replace( '/[^0-9]/', '.', urldecode( $from_date ) );
$date_array['to_date'] = preg_replace( '/[^0-9]/', '.', urldecode( $to_date ) );

$base_url_rewrite = $request_uri = urldecode( $_SERVER['REQUEST_URI'] );
if( empty( $catid ) )
{
	$base_url_rewrite = str_replace('&catid=' . $catid, '', $base_url_rewrite );
}
if( preg_match( '/^([0-9]{1,2})\.([0-9]{1,2})\.([0-9]{4})$/', $date_array['from_date'] ) )
{
	$base_url_rewrite = str_replace('&from_date=' . $from_date, '&from_date=' . $date_array['from_date'], $base_url_rewrite );
}
else
{
	$base_url_rewrite = str_replace('&from_date=' . $from_date, '', $base_url_rewrite );
}
if( preg_match( '/^([0-9]{1,2})\.([0-9]{1,2})\.([0-9]{4})$/', $date_array['to_date'] ) )
{
	$base_url_rewrite = str_replace('&to_date=' . $to_date, '&to_date=' . $date_array['to_date'], $base_url_rewrite );
}
else
{
	$base_url_rewrite = str_replace('&to_date=' . $to_date, '', $base_url_rewrite );
}
$base_url_rewrite = nv_url_rewrite($base_url_rewrite , true );

if( $request_uri != $base_url_rewrite and NV_MAIN_DOMAIN . $request_uri != $base_url_rewrite )
{
	header( 'Location: ' . $base_url_rewrite );
	die();
}

$array_cat_search = array();
foreach( $global_array_cat as $arr_cat_i )
{
	$array_cat_search[$arr_cat_i['catid']] = array(
		'catid' => $arr_cat_i['catid'],
		'title' => $arr_cat_i['title'],
		'select' => ( $arr_cat_i['catid'] == $catid ) ? 'selected' : ''
	);
}

$array_cat_search[0]['title'] = $lang_module['search_all'];

$contents = call_user_func( 'search_theme', $key, $check_num, $date_array, $array_cat_search );
$where = '';
$tbl_src = '';
if( empty( $key ) AND ( $catid == 0 ) AND empty( $from_date ) AND empty( $to_date ) )
{
	$contents .= '<div class="alert alert-danger">'.$lang_module['empty_data_search'].'</div>';
}
else {

	$base_url_rewrite = nv_url_rewrite( NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=search&amp;q='. $key , true );
	$canonicalUrl = NV_MY_DOMAIN . $base_url_rewrite;

	$dbkey = $db->dblikeescape( $key );
	$dbkeyhtml = $db->dblikeescape( $keyhtml );

	if( $check_num == 1 )
	{
		$tbl_src = ' LEFT JOIN ' . NV_PREFIXLANG . '_' . $module_data . '_bodytext tb2 ON ( tb1.id = tb2.id ) ';
		$where = "AND ( tb1.title LIKE '%" . $dbkeyhtml . "%' OR tb1.hometext LIKE '%" . $dbkey . "%' OR tb2.bodytext LIKE '%" . $dbkey . "%' ) ";
	}
	elseif( $check_num == 2 )
	{
		$where = "AND ( tb1.author LIKE '%" . $dbkeyhtml . "%' ) ";
	}
	elseif( $check_num == 3 )
	{
		$qurl = $key;
		$url_info = @parse_url( $qurl );
		if( isset( $url_info['scheme'] ) and isset( $url_info['host'] ) )
		{
			$qurl = $url_info['scheme'] . '://' . $url_info['host'];
		}
		$where = "AND (tb1.sourceid IN (SELECT sourceid FROM " . NV_PREFIXLANG . "_" . $module_data . "_sources WHERE title like '%" . $db->dblikeescape( $dbkey ) . "%' OR link like '%" . $db->dblikeescape( $qurl ) . "%'))";
	}
	else
	{
		$qurl = $key;
		$url_info = @parse_url( $qurl );
		if( isset( $url_info['scheme'] ) and isset( $url_info['host'] ) )
		{
			$qurl = $url_info['scheme'] . '://' . $url_info['host'];
		}
		$tbl_src = ' LEFT JOIN ' . NV_PREFIXLANG . '_' . $module_data . '_bodytext tb2 ON ( tb1.id = tb2.id )';
		$where = " AND ( tb1.title LIKE '%" . $dbkeyhtml . "%' OR tb1.hometext LIKE '%" . $dbkey . "%' ";
		$where .= " OR tb1.author LIKE '%" . $dbkeyhtml . "%' OR tb2.bodytext LIKE '%" . $dbkey . "%') OR (tb1.sourceid IN (SELECT sourceid FROM " . NV_PREFIXLANG . "_" . $module_data . "_sources WHERE title like '%" . $db->dblikeescape( $dbkey ) . "%' OR link like '%" . $db->dblikeescape( $qurl ) . "%'))";
	}

	if( preg_match( '/^([0-9]{1,2})\.([0-9]{1,2})\.([0-9]{4})$/', $to_date, $m ) )
	{
		$where .= ' AND publtime <=' . mktime( 23, 59, 59, $m[2], $m[1], $m[3] );
	}
	if( preg_match( '/^([0-9]{1,2})\.([0-9]{1,2})\.([0-9]{4})$/', $from_date, $m ) )
	{
		$where .= ' AND publtime >=' . mktime( 0, 0, 0, $m[2], $m[1], $m[3] );
	}

	if( $catid > 0 )
	{
		$table_search = NV_PREFIXLANG . '_' . $module_data . '_' . $catid;
	}
	else
	{
		$table_search = NV_PREFIXLANG . '_' . $module_data . '_rows';
	}

	$db->sqlreset()
		->select( 'COUNT(*)' )
		->from( $table_search . ' as tb1 ' . $tbl_src )
		->where( 'tb1.status=1 ' . $where );

	$numRecord = $db->query( $db->sql() )->fetchColumn();

	$db->select( 'tb1.id,tb1.title,tb1.alias,tb1.catid,tb1.hometext,tb1.author,tb1.publtime,tb1.homeimgfile, tb1.homeimgthumb,tb1.sourceid' )
		->order( 'tb1.publtime DESC' )
		->limit( $per_page )
		->offset( ( $page - 1 ) * $per_page );

	$result = $db->query( $db->sql() );

	$array_content = array();
	$show_no_image = $module_config[$module_name]['show_no_image'];

	while( list( $id, $title, $alias, $catid, $hometext, $author, $publtime, $homeimgfile, $homeimgthumb, $sourceid ) = $result->fetch( 3 ) )
	{
		if( $homeimgthumb == 1 ) //image thumb
		{
			$img_src = NV_BASE_SITEURL . NV_FILES_DIR . '/' . $module_upload . '/' . $homeimgfile;
		}
		elseif( $homeimgthumb == 2 ) //image file
		{
			$img_src = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_upload . '/' . $homeimgfile;
		}
		elseif( $homeimgthumb == 3 ) //image url
		{
			$img_src = $homeimgfile;
		}
		elseif( ! empty( $show_no_image ) ) //no image
		{
			$img_src = NV_BASE_SITEURL . $show_no_image;
		}
		else
		{
			$img_src = '';
		}
		$array_content[] = array(
			'id' => $id,
			'title' => $title,
			'alias' => $alias,
			'catid' => $catid,
			'hometext' => $hometext,
			'author' => $author,
			'publtime' => $publtime,
			'homeimgfile' => $img_src,
			'sourceid' => $sourceid
		);
	}

	$contents .= search_result_theme( $key, $numRecord, $per_page, $page, $array_content, $catid );
}

if( empty( $key ) )
{
	$page_title = $lang_module['search_title'] . ' ' . NV_TITLEBAR_DEFIS . ' ' . $module_info['custom_title'];
}
else
{
	$page_title = $key . ' ' . NV_TITLEBAR_DEFIS . ' ' . $lang_module['search_title'];
	if( $page > 2)
	{
		$page_title .= ' ' . NV_TITLEBAR_DEFIS . ' ' . $lang_global['page'] . ' ' . $page;
	}
	$page_title .=' ' . NV_TITLEBAR_DEFIS . ' ' . $module_info['custom_title'];
}

$key_words = $description = 'no';
$mod_title = isset( $lang_module['main_title'] ) ? $lang_module['main_title'] : $module_info['custom_title'];

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme( $contents );
include NV_ROOTDIR . '/includes/footer.php';