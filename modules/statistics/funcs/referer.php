<?php

/**
 * @Project NUKEVIET 3.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2012 VINADES.,JSC. All rights reserved
 * @Createdate 17/6/2010, 11:25
 */

if( ! defined( 'NV_IS_MOD_STATISTICS' ) ) die( 'Stop!!!' );

$page_title = $lang_module['refererbymonth'];
$key_words = $module_info['keywords'];
$mod_title = $lang_module['refererbymonth'];

$host = $nv_Request->get_string( 'host', 'get', '' );

if( ! isset( $host ) or ! preg_match( "/^[0-9a-z]([-.]?[0-9a-z])*.[a-z]{2,4}$/", $host ) )
{
	Header( "Location: " . NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name );
	die();
}

$sql = "SELECT `month01`,`month02`,`month03`,`month04`,`month05`,`month06`,`month07`,`month08`,`month09`,`month10`,`month11`,`month12`
FROM `" . NV_REFSTAT_TABLE . "` WHERE `host`=" . $db->dbescape( $host );
$result = $db->sql_query( $sql );
$numrows = $db->sql_numrows( $result );
if( empty( $numrows ) )
{
	Header( "Location: " . NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name );
	die();
}

list( $month01, $month02, $month03, $month04, $month05, $month06, $month07, $month08, $month09, $month10, $month11, $month12 ) = $db->sql_fetchrow( $result );

$contents = "";

$current_month_num = date( 'n', NV_CURRENTTIME );

$cts = array();
$cts['caption'] = sprintf( $lang_module['refererbymonth2'], $host, date( 'Y', NV_CURRENTTIME ) );
$cts['rows'] = array();
$cts['rows']['Jan'] = array( 'fullname' => $lang_global['january'], 'count' => $month01 );
$cts['rows']['Feb'] = array( 'fullname' => $lang_global['february'], 'count' => $month02 );
$cts['rows']['Mar'] = array( 'fullname' => $lang_global['march'], 'count' => $month03 );
$cts['rows']['Apr'] = array( 'fullname' => $lang_global['april'], 'count' => $month04 );
$cts['rows']['May'] = array( 'fullname' => $lang_global['may'], 'count' => $month05 );
$cts['rows']['Jun'] = array( 'fullname' => $lang_global['june'], 'count' => $month06 );
$cts['rows']['Jul'] = array( 'fullname' => $lang_global['july'], 'count' => $month07 );
$cts['rows']['Aug'] = array( 'fullname' => $lang_global['august'], 'count' => $month08 );
$cts['rows']['Sep'] = array( 'fullname' => $lang_global['september'], 'count' => $month09 );
$cts['rows']['Oct'] = array( 'fullname' => $lang_global['october'], 'count' => $month10 );
$cts['rows']['Nov'] = array( 'fullname' => $lang_global['november'], 'count' => $month11 );
$cts['rows']['Dec'] = array( 'fullname' => $lang_global['december'], 'count' => $month12 );

$a = 1;
$total = 0;
foreach( $cts['rows'] as $key => $month )
{
	if( $a > $current_month_num )
	{
		$cts['rows'][$key]['count'] = 0;
	}
	else
	{
		$total = $total + $month['count'];
	}
	++$a;
}

if( $total )
{
	$cts['current_month'] = date( 'M', NV_CURRENTTIME );
	$cts['max'] = max( $month01, $month02, $month03, $month04, $month05, $month06, $month07, $month08, $month09, $month10, $month11, $month12 );
	$cts['total'] = array( $lang_global['total'], $total );
}

$contents = call_user_func( "referer" );

include ( NV_ROOTDIR . "/includes/header.php" );
echo nv_site_theme( $contents );
include ( NV_ROOTDIR . "/includes/footer.php" );

?>