<?php

/**
 * @Project NUKEVIET 3.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2012 VINADES.,JSC. All rights reserved
 * @Createdate 3/19/2010 12:19
 */

if( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );

$bid = $nv_Request->get_int( 'bid', 'get', 0 );

if( empty( $bid ) ) die( 'Stop!!!' );

$sql = "SELECT * FROM `" . NV_BANNERS_ROWS_GLOBALTABLE . "` WHERE `id`=" . $bid;
$result = $db->sql_query( $sql );
$numrows = $db->sql_numrows( $result );
if( $numrows != 1 ) die( 'Stop!!!' );

$row = $db->sql_fetchrow( $result );

$current_day = date( "d" );
$current_month = date( "n" );
$current_year = date( "Y" );
$publ_day = date( "d", $row['publ_time'] );
$publ_month = date( "n", $row['publ_time'] );
$publ_year = date( "Y", $row['publ_time'] );

$data_month = $current_month;

if( preg_match( "/^[0-9]{1,2}$/", $nv_Request->get_int( 'month', 'get' ) ) )
{
	$post_month = $nv_Request->get_int( 'month', 'get' );
	
	if( $post_month < $current_month )
	{
		if( $current_year != $publ_year )
		{
			$data_month = $post_month;
		}
		elseif( $post_month > $publ_month )
		{
			$data_month = $post_month;
		}
	}
}

$table = NV_BANNERS_CLICK_GLOBALTABLE;

$time = mktime( 0, 0, 0, $data_month, 15, $current_year );
$day_max = ( $data_month == $current_month ) ? $current_day : date( "t", $time );
$day_min = ( $current_month == $publ_month and $current_year == $publ_year ) ? $publ_day : 1;

$sql = "SELECT COUNT(*) FROM `" . $table . "` WHERE `bid`=" . $bid . "";

$base_url = NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=show_list_stat&amp;bid=" . $bid . "&amp;month=" . $data_month;
$caption = sprintf( $lang_module['show_list_stat1'], nv_monthname( $data_month ), $current_year );

$data_ext = $data_val = "";

if( in_array( $nv_Request->get_string( 'ext', 'get', 'no' ), array( 'day', 'country', 'browse', 'os' ) ) )
{
	switch( $nv_Request->get_string( 'ext', 'get' ) )
	{
		case 'day':
			if( $nv_Request->isset_request( 'val', 'get' ) and preg_match( "/^[0-9]+$/", $nv_Request->get_string( 'val', 'get' ) ) and $nv_Request->get_int( 'val', 'get', 0 ) <= $day_max and $nv_Request->get_int( 'val', 'get', 0 ) >= $day_min )
			{
				$data_ext = 'day';
				$data_val = $nv_Request->get_int( 'val', 'get' );
				$maxday = mktime( 24, 60, 60, $data_month, $data_val, $current_year );
				$minday = mktime( 0, 0, 0, $data_month, $data_val, $current_year );
				$sql .= " AND `click_time`>=" . $minday . " AND `click_time`<=" . $maxday;
				$base_url .= "&amp;ext=" . $data_ext . "&amp;val=" . $data_val;
				$caption = sprintf( $lang_module['show_list_stat2'], str_pad( $data_val, 2, "0", STR_PAD_LEFT ), nv_monthname( $data_month ), $current_year );
			}
			break;

		case 'country':
			if( $nv_Request->isset_request( 'val', 'get' ) and ( $nv_Request->get_string( 'val', 'get' ) == 'Unknown' or preg_match( "/^[A-Z]{2}$/", $nv_Request->get_string( 'val', 'get' ) ) ) )
			{
				$data_ext = 'country';
				$data_val = $nv_Request->get_string( 'val', 'get' );
				$sql .= " AND `click_country`=" . $db->dbescape( $data_val );
				$base_url .= "&amp;ext=" . $data_ext . "&amp;val=" . $data_val;
				$caption = sprintf( $lang_module['show_list_stat3'], ( isset( $countries[$data_val] ) ? $countries[$data_val][1] : $data_val ), nv_monthname( $data_month ), $current_year );
			}
			break;

		case 'browse':
			if( $nv_Request->isset_request( 'val', 'get' ) and preg_match( "/^[a-zA-Z0-9]+$/", $nv_Request->get_string( 'val', 'get' ) ) )
			{
				$data_ext = 'browse';
				$data_val = $nv_Request->get_string( 'val', 'get' );
				$sql .= " AND `click_browse_name`=" . $db->dbescape( $data_val );
				$base_url .= "&amp;ext=" . $data_ext . "&amp;val=" . $data_val;
				$caption = sprintf( $lang_module['show_list_stat4'], "{pattern}", nv_monthname( $data_month ), $current_year );
			}
			break;

		case 'os':
			if( $nv_Request->isset_request( 'val', 'get' ) and preg_match( "/^[a-zA-Z0-9-\\s]+$/", $nv_Request->get_string( 'val', 'get' ) ) )
			{
				$data_ext = 'os';
				$data_val = $nv_Request->get_string( 'val', 'get' );
				$sql .= " AND `click_os_name`=" . $db->dbescape( $data_val );
				$base_url .= "&amp;ext=" . $data_ext . "&amp;val=" . $data_val;
				$caption = sprintf( $lang_module['show_list_stat5'], "{pattern}", nv_monthname( $data_month ), $current_year );
			}
			break;
	}
}

list( $all_page ) = $db->sql_fetchrow( $db->sql_query( $sql ) );
if( empty( $all_page ) ) die( 'Wrong URL' );

$page = $nv_Request->get_int( 'page', 'get', 0 );
$per_page = 50;

$sql .= " ORDER BY `click_time` DESC LIMIT " . $page . "," . $per_page;

$sql = preg_replace( "/COUNT\(\*\)/", "*", $sql );
$result = $db->sql_query( $sql );

$contents = array();
$replacement = "";

$a = 0;
while( $row = $db->sql_fetchrow( $result ) )
{
	$contents['rows'][$a][] = nv_date( "d-m-Y H:i", $row['click_time'] );
	$contents['rows'][$a][] = $row['click_ip'];
	$contents['rows'][$a][] = isset( $countries[$row['click_country']] ) ? $countries[$row['click_country']][1] : $row['click_country'];
	$contents['rows'][$a][] = $row['click_browse_name'];
	$contents['rows'][$a][] = $row['click_os_name'];
	$contents['rows'][$a][] = ! empty( $row['click_ref'] ) ? "<a href=\"" . $row['click_ref'] . "\">" . $lang_module['select'] . "</a>" : "";

	if( $data_ext == 'browse' and empty( $replacement ) ) $replacement = $row['click_browse_name'];
	elseif( $data_ext == 'os' and empty( $replacement ) ) $replacement = $row['click_os_name'];

	++$a;
}

if( ! empty( $replacement ) )
{
	$caption = preg_replace( "/\{pattern\}/", $replacement, $caption );
}

$contents['caption'] = $caption;
$contents['thead'] = array( $lang_module['click_date'], $lang_module['click_ip'], $lang_module['click_country'], $lang_module['click_browse'], $lang_module['click_os'], $lang_module['click_ref'] );
$contents['generate_page'] = nv_generate_page( $base_url, $all_page, $per_page, $page, true, true, 'nv_urldecode_ajax', 'statistic' );

$contents = call_user_func( "nv_show_list_stat_theme", $contents );

include ( NV_ROOTDIR . "/includes/header.php" );
echo $contents;
include ( NV_ROOTDIR . "/includes/footer.php" );

?>