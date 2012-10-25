<?php

/**
 * @Project NUKEVIET 3.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2012 VINADES.,JSC. All rights reserved
 * @Createdate 16/6/2010, 10:23
 */

if( ! defined( 'NV_IS_MOD_STATISTICS' ) ) die( 'Stop!!!' );

$page_title = $lang_module['referer'];
$key_words = $module_info['keywords'];
$mod_title = $lang_module['referer'];

//*
$sql = "SELECT COUNT(*), SUM(`total`), MAX(`total`) FROM `" . NV_REFSTAT_TABLE . "`";
$result = $db->sql_query( $sql );
list( $all_page, $total, $max ) = $db->sql_fetchrow( $result );

if( $all_page )
{
	$page = $nv_Request->get_int( 'page', 'get', 0 );
	$per_page = 50;
	$base_url = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=allreferers";

	$sql = "SELECT `host`,`total`, `last_update` FROM `" . NV_REFSTAT_TABLE . "` WHERE `total`!=0 ORDER BY `total` DESC LIMIT " . $page . "," . $per_page;
	$result = $db->sql_query( $sql );

	$host_list = array();
	while( list( $host, $count, $last_visit ) = $db->sql_fetchrow( $result ) )
	{
		$last_visit = ! empty( $last_visit ) ? nv_date( "l, d F Y H:i", $last_visit ) : "";
		$bymonth = "<a href=\"" . NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=referer&amp;host=" . $host . "\">" . $lang_module['statbymoth2'] . "</a>\n";
		$host_list[$host] = array(
			$count,
			$last_visit,
			$bymonth );
	}

	if( ! empty( $host_list ) )
	{
		$cts = array();
		$cts['thead'] = array(
			$lang_module['referer'],
			$lang_module['hits'],
			$lang_module['last_visit']
		);
		$cts['rows'] = $host_list;
		$cts['max'] = $max;
		$cts['generate_page'] = nv_generate_page( $base_url, $all_page, $per_page, $page );
	}
}

$contents = call_user_func( "allreferers" );
//*/
//$contents = "REFER TMH";
include ( NV_ROOTDIR . "/includes/header.php" );
echo nv_site_theme( $contents );
include ( NV_ROOTDIR . "/includes/footer.php" );

?>