<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 17/6/2010, 11:25
 */

if( ! defined( 'NV_IS_MOD_STATISTICS' ) ) die( 'Stop!!!' );

$host = $nv_Request->get_string( 'host', 'get', '' );

if( ! isset( $host ) or ! preg_match( '/^[0-9a-z]([-.]?[0-9a-z])*.[a-z]{2,4}$/', $host ) )
{
	Header( 'Location: ' . nv_url_rewrite( NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name, true ) );
	die();
}

$sth = $db->prepare( 'SELECT * FROM ' . NV_REFSTAT_TABLE . ' WHERE host= :host' );
$sth->bindParam( ':host', $host, PDO::PARAM_STR );
$sth->execute();

$row = $sth->fetch();
if( empty( $row ) )
{
	Header( 'Location: ' . nv_url_rewrite( NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name, true ) );
	die();
}

$contents = '';
$current_month_num = date( 'n', NV_CURRENTTIME );

$page_title = sprintf( $lang_module['refererbymonth2'], $host, date( 'Y', NV_CURRENTTIME ) );
$key_words = $module_info['keywords'];
$mod_title = $lang_module['refererbymonth'];

$cts = array();
$cts['caption'] = $page_title;
$cts['rows'] = array();
$cts['rows']['Jan'] = array( 'fullname' => $lang_global['january'], 'count' => $row['month01'] );
$cts['rows']['Feb'] = array( 'fullname' => $lang_global['february'], 'count' => $row['month02'] );
$cts['rows']['Mar'] = array( 'fullname' => $lang_global['march'], 'count' => $row['month03'] );
$cts['rows']['Apr'] = array( 'fullname' => $lang_global['april'], 'count' => $row['month04'] );
$cts['rows']['May'] = array( 'fullname' => $lang_global['may'], 'count' => $row['month05'] );
$cts['rows']['Jun'] = array( 'fullname' => $lang_global['june'], 'count' => $row['month06'] );
$cts['rows']['Jul'] = array( 'fullname' => $lang_global['july'], 'count' => $row['month07'] );
$cts['rows']['Aug'] = array( 'fullname' => $lang_global['august'], 'count' => $row['month08'] );
$cts['rows']['Sep'] = array( 'fullname' => $lang_global['september'], 'count' => $row['month09'] );
$cts['rows']['Oct'] = array( 'fullname' => $lang_global['october'], 'count' => $row['month10'] );
$cts['rows']['Nov'] = array( 'fullname' => $lang_global['november'], 'count' => $row['month11'] );
$cts['rows']['Dec'] = array( 'fullname' => $lang_global['december'], 'count' => $row['month12'] );

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
	$cts['total'] = array( $lang_global['total'], number_format( $total ) );
}

$contents = nv_theme_statistics_referer( $cts, $total );

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme( $contents );
include NV_ROOTDIR . '/includes/footer.php';