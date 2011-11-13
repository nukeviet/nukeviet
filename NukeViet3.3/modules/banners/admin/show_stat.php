<?php

/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES., JSC. All rights reserved
 * @Createdate 3/18/2010 14:37
 */

if (! defined ( 'NV_IS_FILE_ADMIN' ))
	die ( 'Stop!!!' );
	
if ( $client_info['is_myreferer'] != 1 ) die( 'Wrong URL' );


$id = $nv_Request->get_int ( 'id', 'get', 0 );

if (empty ( $id ))
	die ( 'Stop!!!' );

$query = "SELECT * FROM `" . NV_BANNERS_ROWS_GLOBALTABLE . "` WHERE `id`=" . $id;
$result = $db->sql_query ( $query );
$numrows = $db->sql_numrows ( $result );
if ($numrows != 1)
	die ( 'Stop!!!' );

$row = $db->sql_fetchrow ( $result );

$current_day = date ( "d" );
$current_month = date ( "n" );
$current_year = date ( "Y" );
$publ_day = date ( "d", $row ['publ_time'] );
$publ_month = date ( "n", $row ['publ_time'] );
$publ_year = date ( "Y", $row ['publ_time'] );

$data_month = $nv_Request->get_int ( 'month', 'get' );
if ($nv_Request->isset_request ( 'month', 'get' ) and preg_match ( "/^[0-9]{1,2}$/", $nv_Request->get_int ( 'month', 'get' ) )) {
	$get_month = $nv_Request->get_int ( 'month', 'get' );
	if ($get_month < $current_month) {
		if ($current_year != $publ_year) {
			$data_month = $get_month;
		} elseif ($get_month > $publ_month) {
			$data_month = $get_month;
		}
	}
}

//$table = ( $data_month == $current_month ) ? NV_BANNERS_CLICK_GLOBALTABLE : NV_BANNERS_CLICK_GLOBALTABLE . '_' . $current_year . '_' . str_pad( $get_month, 2, "0", STR_PAD_LEFT );
#begin edit
$table = NV_BANNERS_CLICK_GLOBALTABLE;
#begin edit


$time = mktime ( 0, 0, 0, $data_month, 15, $current_year );
$day_max = ($data_month == $current_month) ? $current_day : date ( "t", $time );
$day_min = ($current_month == $publ_month and $current_year == $publ_year) ? $publ_day : 1;
#begin edit
$maxday = mktime ( 24, 60, 60, $data_month, $day_max, $current_year );
$minday = mktime ( 0, 0, 0, $data_month, $day_min, $current_year );
$sum = $db->sql_numrows ( $db->sql_query ( "SELECT * FROM `" . $table . "` WHERE `bid`=" . $id . " AND `click_time`>=" . $minday . " AND `click_time`<=" . $maxday . "" ) );
#end edit


/*list ( $sum ) = $db->sql_fetchrow ( $db->sql_query ( "SELECT COUNT(*) AS `sum` FROM `" . $table . "` WHERE `bid`=" . $id . " AND `click_day`<=" . $day_max . " AND `click_day`>=" . $day_min ) );
$sum = intval ( $sum );*/

$cts = array ();

$ext = in_array ( $nv_Request->get_string ( 'ext', 'get', 'no' ), array ('country', 'browse', 'os' ) ) ? $nv_Request->get_string ( 'ext', 'get' ) : "day";
if ($ext == 'country') {
	#begin edit
	$query = "SELECT `click_country`  FROM `" . $table . "` WHERE `bid`=" . $id . " AND `click_time`>=" . $minday . " AND `click_time`<=" . $maxday . " ORDER BY `click_country` DESC";
	#end edit
	//$query = "SELECT COUNT(*) AS `click_count`, `click_country` FROM `" . $table . "` WHERE `bid`=" . $id . " AND `click_day`<=" . $day_max . " AND `click_day`>=" . $day_min . " GROUP BY `click_country` ORDER BY `click_country` ASC";
	$result = $db->sql_query ( $query );
	$unknown = 0;
	if (! empty ( $result )) {
		$countries = array ();
		include (NV_ROOTDIR . "/includes/countries.php");
		#begin edit
		$result = $db->sql_query ( $query );
		$bd = array ();
		if (! empty ( $result )) {
			while ( $row = $db->sql_fetchrow ( $result ) ) {
				$bd [$row ['click_country']] = $bd [$row ['click_country']] + 1;
			}
		}
		foreach ( $bd as $shortname => $click_count ) {
			$country = $shortname;
			if (preg_match ( "/^[A-Z]{2}$/", $country )) {
				$key = "nv_show_list_stat(" . $id . "," . $data_month . ",'" . $ext . "','" . $country . "','statistic',0);";
				$cts [$key] [0] = isset ( $countries [$country] ) ? $countries [$country] [1] : $country;
				$cts [$key] [1] = ($sum > 0) ? round ( $click_count * 100 / $sum, 1 ) : 0;
				$cts [$key] [2] = $click_count;
			} else {
				$unknown += $click_count;
			}
		}
		#end edit		
		/*		while ( $row = $db->sql_fetchrow ( $result ) ) {
			$country = $row ['click_country'];
			if (preg_match ( "/^[A-Z]{2}$/", $country )) {
				$key = "nv_show_list_stat(" . $id . "," . $data_month . ",'" . $ext . "','" . $country . "','statistic',0);";
				$cts [$key] [0] = isset ( $countries [$country] ) ? $countries [$country] [1] : $country;
				$cts [$key] [1] = ($sum > 0) ? round ( $row ['click_count'] * 100 / $sum ) : 0;
				$cts [$key] [2] = $row ['click_count'];
			} else {
				$unknown += $row ['click_count'];
			}
		}*/
		if (! empty ( $unknown )) {
			$key = "nv_show_list_stat(" . $id . "," . $data_month . ",'" . $ext . "','Unknown','statistic',0);";
			$cts [$key] [0] = $lang_module ['unknown'];
			$cts [$key] [1] = ($sum > 0) ? round ( $unknown * 100 / $sum ) : 0;
			$cts [$key] [2] = $unknown;
		}
	}
	$caption = sprintf ( $lang_module ['info_stat_bycountry_caption'], nv_monthname ( $data_month ), $current_year );
} elseif ($ext == 'browse') {
	
	$query = "SELECT `click_browse_name` FROM `" . $table . "` WHERE `bid`=" . $id . " AND `click_time`>=" . $minday . " AND `click_time`<=" . $maxday . " ORDER BY `click_country` DESC";
	
	//$query = "SELECT COUNT(*) AS `click_count`, `click_browse_key`, `click_browse_name` FROM `" . $table . "` WHERE `bid`=" . $id . " AND `click_day`<=" . $day_max . " AND `click_day`>=" . $day_min . " GROUP BY `click_browse_key` ORDER BY `click_browse_name`";
	$result = $db->sql_query ( $query );
	$bd = array ();
	if (! empty ( $result )) {
		while ( $row = $db->sql_fetchrow ( $result ) ) {
			$bd [$row ['click_browse_name']] = $bd [$row ['click_browse_name']] + 1;
		}
	}
	$unknown = 0;
	foreach ( $bd as $shortname => $click_count ) {
		if (trim ( $shortname ) != "Unknown") {
			$key = "nv_show_list_stat(" . $id . "," . $data_month . ",'" . $ext . "','" . $shortname . "','statistic',0);";
			$cts [$key] [0] = $shortname;
			$cts [$key] [1] = ($sum > 0) ? round ( $click_count * 100 / $sum, 1 ) : 0;
			$cts [$key] [2] = $click_count;
		} else {
			$unknown += $click_count;
		}
	}
	if (! empty ( $unknown )) {
		$key = "nv_show_list_stat(" . $id . "," . $data_month . ",'" . $ext . "','Unknown','statistic',0);";
		$cts [$key] [0] = $lang_module ['unknown'];
		$cts [$key] [1] = ($sum > 0) ? round ( $unknown * 100 / $sum ) : 0;
		$cts [$key] [2] = $unknown;
	}
	/*	if (! empty ( $result )) {
		while ( $row = $db->sql_fetchrow ( $result ) ) {
			$browse_name = $row ['click_browse_name'];
			$browse_key = $row ['click_browse_key'];
			if ($browse_key != "Unknown") {
				$key = "nv_show_list_stat(" . $id . "," . $data_month . ",'" . $ext . "','" . $browse_key . "','statistic',0);";
				$cts [$key] [0] = $browse_name;
				$cts [$key] [1] = ($sum > 0) ? round ( $row ['click_count'] * 100 / $sum ) : 0;
				$cts [$key] [2] = $row ['click_count'];
			} else {
				$unknown += $row ['click_count'];
			}
		}
		if (! empty ( $unknown )) {
			$key = "nv_show_list_stat(" . $id . "," . $data_month . ",'" . $ext . "','Unknown','statistic',0);";
			$cts [$key] [0] = $lang_module ['unknown'];
			$cts [$key] [1] = ($sum > 0) ? round ( $unknown * 100 / $sum ) : 0;
			$cts [$key] [2] = $unknown;
		}
	}*/
	$caption = sprintf ( $lang_module ['info_stat_bybrowse_caption'], nv_monthname ( $data_month ), $current_year );
} elseif ($ext == 'os') {
	$query = "SELECT `click_os_name` FROM `" . $table . "` WHERE `bid`=" . $id . " AND `click_time`>=" . $minday . " AND `click_time`<=" . $maxday . " ORDER BY `click_os_name` DESC";
	//$query = "SELECT COUNT(*) AS `click_count`, `click_os_key`, `click_os_name` FROM `" . $table . "` WHERE `bid`=" . $id . " AND `click_day`<=" . $day_max . " AND `click_day`>=" . $day_min . " GROUP BY `click_os_key` ORDER BY `click_os_name`";
	$result = $db->sql_query ( $query );
	$bd = array ();
	if (! empty ( $result )) {
		while ( $row = $db->sql_fetchrow ( $result ) ) {
			$bd [$row ['click_os_name']] = $bd [$row ['click_os_name']] + 1;
		}
	}

	$unknown = 0;
	foreach ( $bd as $shortname => $click_count ) {
		$os_key = $os_name = $shortname;
		if (preg_match ( "/^Robot\:/", $os_name )) {
			$key = "nv_show_list_stat(" . $id . "," . $data_month . ",'" . $ext . "','" . $os_key . "','statistic',0);";
			$robots [$key] [0] = $os_name;
			$robots [$key] [1] = ($sum > 0) ? round ( $click_count * 100 / $sum, 1 ) : 0;
			$robots [$key] [2] = $click_count;
		} elseif ($os_key != "Unspecified") {
			$key = "nv_show_list_stat(" . $id . "," . $data_month . ",'" . $ext . "','" . $os_key . "','statistic',0);";
			$cts [$key] [0] = $os_name;
			$cts [$key] [1] = ($sum > 0) ? round ( $click_count * 100 / $sum, 1 ) : 0;
			$cts [$key] [2] = $click_count;
		} else {
			$unknown += $click_count;
		}
	}
	if (! empty ( $robots ))
		$cts = array_merge ( $cts, $robots );
	if (! empty ( $unknown )) {
		$key = "nv_show_list_stat(" . $id . "," . $data_month . ",'" . $ext . "','Unspecified','statistic',0);";
		$cts [$key] [0] = $lang_module ['unknown'];
		$cts [$key] [1] = ($sum > 0) ? round ( $unknown * 100 / $sum ) : 0;
		$cts [$key] [2] = $unknown;
	}
	/*	$unknown = 0;
	$robots = array ();
	if (! empty ( $result )) {
		while ( $row = $db->sql_fetchrow ( $result ) ) {
			$os_name = $row ['click_os_name'];
			$os_key = $row ['click_os_key'];
			if (preg_match ( "/^Robot\:/", $os_name )) {
				$key = "nv_show_list_stat(" . $id . "," . $data_month . ",'" . $ext . "','" . $os_key . "','statistic',0);";
				$robots [$key] [0] = $os_name;
				$robots [$key] [1] = ($sum > 0) ? round ( $row ['click_count'] * 100 / $sum ) : 0;
				$robots [$key] [2] = $row ['click_count'];
			} elseif ($os_key != "Unspecified") {
				$key = "nv_show_list_stat(" . $id . "," . $data_month . ",'" . $ext . "','" . $os_key . "','statistic',0);";
				$cts [$key] [0] = $os_name;
				$cts [$key] [1] = ($sum > 0) ? round ( $row ['click_count'] * 100 / $sum ) : 0;
				$cts [$key] [2] = $row ['click_count'];
			} else {
				$unknown += $row ['click_count'];
			}
		}
		if (! empty ( $robots ))
			$cts = array_merge ( $cts, $robots );
		if (! empty ( $unknown )) {
			$key = "nv_show_list_stat(" . $id . "," . $data_month . ",'" . $ext . "','Unspecified','statistic',0);";
			$cts [$key] [0] = $lang_module ['unknown'];
			$cts [$key] [1] = ($sum > 0) ? round ( $unknown * 100 / $sum ) : 0;
			$cts [$key] [2] = $unknown;
		}
	}*/
	$caption = sprintf ( $lang_module ['info_stat_byos_caption'], nv_monthname ( $data_month ), $current_year );
} else {
	//$query = "SELECT COUNT(*) AS `click_count`, `click_day`  FROM `" . $table . "` WHERE `bid`=" . $id . " AND `click_day`<=" . $day_max . " AND `click_day`>=" . $day_min . " GROUP BY `click_day` ORDER BY `click_day` DESC";
	#begin edit
	$query = "SELECT `click_time`  FROM `" . $table . "` WHERE `bid`=" . $id . " AND `click_time`>=" . $minday . " AND `click_time`<=" . $maxday . " ORDER BY `click_time` DESC";
	#end edit
	/*	$result = $db->sql_query( $query );
	$bd = array();
	if ( ! empty( $result ) )
	{
		while ( $row = $db->sql_fetchrow( $result ) )
		{
			$bd[$row['click_day']] = $row['click_count'];
		}
	}*/
	#begin edit
	$result = $db->sql_query ( $query );
	$bd = array ();
	if (! empty ( $result )) {
		while ( $row = $db->sql_fetchrow ( $result ) ) {
			$bd [date ( 'd', $row ['click_time'] )] = $bd [date ( 'd', $row ['click_time'] )] + 1;
		}
	}
	#end edit
	/*	for($i = $day_max; $i >= $day_min; --$i) {
		$c = isset ( $bd [$i] ) ? $bd [$i] : 0;
		$key = isset ( $bd [$i] ) ? "nv_show_list_stat(" . $id . "," . $data_month . ",'day','" . $i . "','statistic',0);" : $i;
		$cts [$key] [0] = str_pad ( $i, 2, "0", STR_PAD_LEFT ) . " " . nv_date ( "F Y", $time );
		$cts [$key] [1] = ($sum > 0) ? round ( $c * 100 / $sum ) : 0;
		$cts [$key] [2] = $c;
	}*/
	#begin edit
	for($i = $day_max; $i >= $day_min; --$i) {
		$c = isset ( $bd [$i] ) ? $bd [$i] : 0;
		$key = isset ( $bd [$i] ) ? "nv_show_list_stat(" . $id . "," . $data_month . ",'day','" . $i . "','statistic',0);" : $i;
		$cts [$key] [0] = str_pad ( $i, 2, "0", STR_PAD_LEFT ) . " " . nv_date ( "F Y", $time );
		$cts [$key] [1] = round ( ($bd [$i] * 100) / $sum, 1 );
		$cts [$key] [2] = $bd [$i];
	}
	#end edit
	$caption = sprintf ( $lang_module ['info_stat_byday_caption'], nv_monthname ( $data_month ), $current_year );
}

$contents = call_user_func ( "nv_show_stat_theme", array ($caption, $sum, $cts ) );

include (NV_ROOTDIR . "/includes/header.php");
echo $contents;
include (NV_ROOTDIR . "/includes/footer.php");

?>