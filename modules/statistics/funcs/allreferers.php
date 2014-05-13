<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 16/6/2010, 10:23
 */

if( ! defined( 'NV_IS_MOD_STATISTICS' ) ) die( 'Stop!!!' );

$page_title = $lang_module['referer'];
$key_words = $module_info['keywords'];
$mod_title = $lang_module['referer'];

$sql = 'SELECT COUNT(*), SUM(total), MAX(total) FROM ' . NV_REFSTAT_TABLE;
$result = $db->query( $sql );
list( $num_items, $total, $max ) = $result->fetch( 3 );

if( $num_items )
{
	$page = $nv_Request->get_int( 'page', 'get', 1 );
	$per_page = 50;
	$base_url = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $module_info['alias']['allreferers'];

	$db->sqlreset()
		->select( 'host, total, last_update' )
		->from( NV_REFSTAT_TABLE )
		->where( 'total!=0' )
		->order( 'total DESC' )
		->limit( $per_page )
		->offset( ( $page - 1 ) * $per_page );
	$result = $db->query( $db->sql() );

	$host_list = array();
	while( list( $host, $count, $last_visit ) = $result->fetch( 3 ) )
	{
		$last_visit = ! empty( $last_visit ) ? nv_date( 'l, d F Y H:i', $last_visit ) : '';
		$bymonth = '<a href="' . NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $module_info['alias']['referer'] . '&amp;host=' . $host . '">' . $lang_module['statbymoth2'] . '</a>';
		$host_list[$host] = array( $count, $last_visit, $bymonth );
	}

	if( ! empty( $host_list ) )
	{
		$cts = array();
		$cts['thead'] = array( $lang_module['referer'], $lang_module['hits'], $lang_module['last_visit'] );
		$cts['rows'] = $host_list;
		$cts['max'] = $max;
		$cts['generate_page'] = nv_generate_page( $base_url, $num_items, $per_page, $page );
	}
	if( $page > 1 )
	{
		$page_title .= ' ' . NV_TITLEBAR_DEFIS . ' ' . $lang_global['page'] . ' ' . $page;
	}
	$contents = nv_theme_statistics_allreferers( $num_items, $cts, $host_list );
}

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme( $contents );
include NV_ROOTDIR . '/includes/footer.php';