<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 16/6/2010, 10:23
 */

if( ! defined( 'NV_IS_MOD_STATISTICS' ) ) die( 'Stop!!!' );

$page_title = $lang_module['country'];
$key_words = $module_info['keywords'];
$mod_title = $lang_module['country'];

$sql = "SELECT COUNT(*), MAX(c_count) FROM " . NV_COUNTER_TABLE . " WHERE c_type='country' AND c_count!=0";
$result = $db->query( $sql );
list( $all_page, $max ) = $result->fetch( 3 );

if( $all_page )
{
	$page = $nv_Request->get_int( 'page', 'get', 0 );
	$per_page = 50;
	$base_url = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $module_info['alias']['allcountries'];

	$db->sqlreset()
		->select( 'c_val,c_count, last_update' )
		->from( NV_COUNTER_TABLE )
		->where( "c_type='country' AND c_count!=0" )
		->order( 'c_count DESC' )
		->limit( $per_page )
		->offset( $page );
	$result = $db->query( $db->sql() );

	$countries_list = array();
	while( list( $country, $count, $last_visit ) = $result->fetch( 3 ) )
	{
		$fullname = isset( $countries[$country] ) ? $countries[$country][1] : $lang_module['unknown'];
		$last_visit = ! empty( $last_visit ) ? nv_date( 'l, d F Y H:i', $last_visit ) : '';
		$countries_list[$country] = array( $fullname, $count, $last_visit );
	}

	if( ! empty( $countries_list ) )
	{
		$cts = array();
		$cts['thead'] = array( $lang_module['country'], $lang_module['hits'], $lang_module['last_visit'] );
		$cts['rows'] = $countries_list;
		$cts['max'] = $max;
		$cts['generate_page'] = nv_generate_page( $base_url, $all_page, $per_page, $page );
	}
	if( $page > 1 )
	{
		$page_title .= ' ' . NV_TITLEBAR_DEFIS . ' ' . $lang_global['page'] . ' ' . $page;
	}
}

$contents = allcountries();

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme( $contents );
include NV_ROOTDIR . '/includes/footer.php';

?>