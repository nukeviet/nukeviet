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
$per_page = $nv_laws_setting['nummain'];
$base_url = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name;

$order = $nv_laws_setting['typeview'] ? "ASC" : "DESC";

$sql = "SELECT SQL_CALC_FOUND_ROWS * FROM " . NV_PREFIXLANG . "_" . $module_data . "_row WHERE status=1 ORDER BY addtime " . $order . " LIMIT " . $page . "," . $per_page;

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
		echo nv_site_theme( '' );
		include NV_ROOTDIR . '/includes/footer.php';
		exit();
	}
}

$generate_page = nv_generate_page( $base_url, $all_page, $per_page, $page );

$array_data = array();
$stt = $page + 1;
while ( $row = $result->fetch() )
{
	$row['areatitle'] = $nv_laws_listarea[$row['aid']]['title'];
	$row['subjecttitle'] = $nv_laws_listsubject[$row['sid']]['title'];
	$row['cattitle'] = $nv_laws_listcat[$row['cid']]['title'];
	$row['url'] = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=detail/" . $row['alias'];
	$row['stt'] = $stt;

	if( $nv_laws_setting['down_in_home'] )
	{
		// File download
		if( ! empty( $row['files'] ) )
		{
			$row['files'] = explode( ",", $row['files'] );
			$files = $row['files'];
			$row['files'] = array();

			foreach( $files as $id => $file )
			{
				$file_title = basename( $file );
				$row['files'][] = array(
					"title" => $file_title,
					"title0" => nv_clean60( $file_title, 10 ),
					"url" => NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=detail/" . $row['alias'] . "&amp;download=1&amp;id=" . $id
				);
			}
		}
	}

	$array_data[] = $row;
	$stt ++;
}

$contents = nv_theme_laws_main( $array_data, $generate_page );

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme( $contents );
include NV_ROOTDIR . '/includes/footer.php';