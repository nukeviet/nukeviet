<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Wed, 27 Jul 2011 14:55:22 GMT
 */

if ( ! defined( 'NV_IS_MOD_LAWS' ) ) die( 'Stop!!!' );

$page_title = $module_info['custom_title'];
$key_words = $module_info['keywords'];

//
$page = $nv_Request->get_int( 'page', 'get', 0 );
$per_page = $nv_laws_setting['numsub'];
$base_url = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $op;
$sql = "SELECT SQL_CALC_FOUND_ROWS * FROM " . NV_PREFIXLANG . "_" . $module_data . "_row t1 INNER JOIN " . NV_PREFIXLANG . "_" . $module_data . "_row_area t2 WHERE status=1";

$key = nv_substr( $nv_Request->get_title( 'q', 'get', '', 1 ), 0, NV_MAX_SEARCH_LENGTH);

$sfrom = nv_substr( $nv_Request->get_title( 'sfrom', 'get', '' ), 0, 10);
$sto = nv_substr( $nv_Request->get_title( 'sto', 'get', '' ), 0, 10);

$area = $nv_Request->get_int( 'area', 'get', 0 );
$cat = $nv_Request->get_int( 'cat', 'get', 0 );
$subject = $nv_Request->get_int( 'subject', 'get', 0 );
$sstatus = $nv_Request->get_int( 'status', 'get', 0 );
$ssigner = $nv_Request->get_int( 'signer', 'get', 0 );

unset( $m );
if ( preg_match( "/^([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{4})$/", $sfrom, $m ) )
{
	$sfrom1 = mktime( 0, 0, 0, $m[2], $m[1], $m[3] );
}
else
{
	$sfrom1 = 0;
}

unset( $m );
if ( preg_match( "/^([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{4})$/", $sto, $m ) )
{
	$sto1 = mktime( 0, 0, 0, $m[2], $m[1], $m[3] );
}
else
{
	$sto1 = 0;
}

$search = false;
if( ! empty( $key ) or ! empty( $area ) or ! empty( $cat ) or ! empty( $subject ) or ! empty( $sstatus ) or ! empty( $ssigner ) or ! empty( $sfrom1 ) or ! empty( $sto1 ) )
{
	$search = true;

	if( ! empty( $key ) )
	{
		$dbkey = $db->dblikeescape( $key );
		$base_url .= "&amp;q=" . $key;
		$sql .= " AND ( title LIKE '%" . $dbkey . "%' OR introtext LIKE '%" . $dbkey . "%' OR code LIKE '%" . $dbkey . "%' OR bodytext LIKE '%" . $dbkey . "%' )";
	}

	if( ! empty( $area ) )
	{
		$base_url .= "&amp;area=" . $area;

		$tmp = $nv_laws_listarea[$area];
		$in = "";
		if( empty( $tmp['subcats'] ) )
		{
			$in = " t2.area_id=" . $area;
		}
		else
		{
			$in = $tmp['subcats'];
			$in[] = $area;
			$in = " AND t2.area_id IN(" . implode( ",", $in ) . ")";
		}

		$sql .= $in;
	}

	if( ! empty( $cat ) )
	{
		$base_url .= "&amp;cat=" . $cat;

		$tmp = $nv_laws_listcat[$cat];
		$in = "";
		if( empty( $tmp['subcats'] ) )
		{
			$in = " AND cid=" . $cat;
		}
		else
		{
			$in = $tmp['subcats'];
			$in[] = $cat;
			$in = " AND cid IN(" . implode( ",", $in ) . ")";
		}

		$sql .= $in;
	}

	if( ! empty( $subject ) )
	{
		$sql .= " AND sid=" . $subject;
		$base_url .= "&amp;subject=" . $subject;
	}

	if( ! empty( $sfrom1 ) )
	{
		$sql .= " AND publtime>=" . $sfrom1;
		$base_url .= "&amp;sfrom=" . $sfrom;
	}

	if( ! empty( $sto1 ) )
	{
		$sql .= " AND publtime<=" . $sto1;
		$base_url .= "&amp;sto=" . $sto;
	}

	if( ! empty( $sstatus ) )
	{
		if( $sstatus == 1 )
		{
			$sql .= " AND ( exptime=0 OR exptime>=" . NV_CURRENTTIME . ")";
			$base_url .= "&amp;status=" . $sstatus;
		}
		else
		{
			$sql .= " AND ( exptime!=0 AND exptime<" . NV_CURRENTTIME . ")";
			$base_url .= "&amp;status=" . $sstatus;
		}
	}
}

if( ! $search )
{
    include NV_ROOTDIR . '/includes/header.php';
    echo nv_site_theme( nv_theme_laws_search( array(), "", 0 ) );
    include NV_ROOTDIR . '/includes/footer.php';
    exit();
}

$order = $nv_laws_setting['typeview'] ? "ASC" : "DESC";

$sql .= " ORDER BY addtime " . $order . " LIMIT " . $page . "," . $per_page;

$result = $db->query( $sql );
$query = $db->query( "SELECT FOUND_ROWS()" );
$all_page = $query->fetchColumn();

if ( ! $all_page or $page >= $all_page )
{
	if ( $nv_Request->isset_request( 'page', 'get' ) )
	{
		Header( "Location: " . nv_url_rewrite( NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name, true ) );
		exit();
	}
	else
	{
		include NV_ROOTDIR . '/includes/header.php';
		echo nv_site_theme( nv_theme_laws_search( array(), "", 0 ) );
		include NV_ROOTDIR . '/includes/footer.php';
		exit();
	}
}

$generate_page = nv_generate_page( $base_url, $all_page, $per_page, $page );

$array_data = array();
$stt = $page + 1;
while ( $row = $result->fetch() )
{
	$row['areatitle'] = array();
	$_result = $db->query( 'SELECT area_id FROM ' . NV_PREFIXLANG . '_' . $module_data . '_row_area WHERE row_id=' . $row['id'] );
	while( list( $area_id ) = $_result->fetch( 3 ) )
	{
		$row['areatitle'][] = $nv_laws_listarea[$area_id]['title'];
	}
	$row['areatitle'] = !empty( $row['areatitle'] ) ? implode( ', ', $row['areatitle'] ) : '';
	$row['subjecttitle'] = $nv_laws_listsubject[$row['sid']]['title'];
	$row['cattitle'] = $nv_laws_listcat[$row['cid']]['title'];
	$row['url'] = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=detail/" . $row['alias'];
	$row['stt'] = $stt;

	$array_data[] = $row;
	$stt ++;
}

$contents = nv_theme_laws_search( $array_data, $generate_page, $all_page );

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme( $contents );
include NV_ROOTDIR . '/includes/footer.php';