<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Wed, 27 Jul 2011 14:55:22 GMT
 */

if ( ! defined( 'NV_IS_MOD_LAWS' ) ) die( 'Stop!!!' );

$id = isset( $array_op[1] ) ? intval( $array_op[1] ) : 0;

if ( empty( $id ) )
{
    Header( "Location: " . nv_url_rewrite( NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name, true ) );
    exit();
}

$sql = "SELECT * FROM " . NV_PREFIXLANG . "_" . $module_data . "_signer WHERE id=" . $id;
$result = $db->query( $sql );
$signer = $result->fetch();

if( empty( $signer ) )
{
	Header( "Location: " . nv_url_rewrite( NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name, true ) );
	exit();
}

// Set page title, keywords, description
$page_title = $mod_title = $signer['title'];
$key_words = $module_info['keywords'];
$description = $signer['title'] . " - " . $signer['offices'] . " - " . $signer['positions'];

//
$page = $nv_Request->get_int( 'page', 'get', 0 );
$per_page = $nv_laws_setting['numsub'];
$base_url = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=signer/" . $signer['id'] . "/" . change_alias( $signer['title'] );

$order = $nv_laws_setting['typeview'] ? "ASC" : "DESC";
	
$sql = "SELECT SQL_CALC_FOUND_ROWS * FROM " . NV_PREFIXLANG . "_" . $module_data . "_row WHERE status=1 AND signer=" . $signer['id'] . " ORDER BY addtime " . $order . " LIMIT " . $page . "," . $per_page;

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
	$row['url'] = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=detail/" . $row['alias'];
	$row['stt'] = $stt;
	
	$array_data[] = $row;
	$stt ++;
}

$contents = nv_theme_laws_signer( $array_data, $generate_page, $signer );

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme( $contents );
include NV_ROOTDIR . '/includes/footer.php';