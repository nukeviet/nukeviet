<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 3/25/2010 21:7
 */

if( ! defined( 'NV_IS_MOD_BANNERS' ) ) die( 'Stop!!!' );

global $global_config, $module_name, $module_info, $lang_module, $banner_client_info;

if( defined( 'NV_IS_BANNER_CLIENT' ) )
{
	$type = $nv_Request->get_title( 'type', 'post,get', 'country', 1 );
	$month = $nv_Request->get_int( 'month', 'post,get' );
	$ads = $nv_Request->get_int( 'ads', 'post,get' );
	$year = ( int )date( 'Y' );
	$month_array = array(
		'1' => 31,
		'3' => 31,
		'4' => 30,
		'5' > 31,
		'6' => 30,
		'7' => 31,
		'8' => 31,
		'9' => 30,
		'10' => 31,
		'11' => 30,
		'12' => 31
	);
	$month_array['2'] = ( ( $year % 100 == 0 ) && ( $year % 400 == 0 ) ) ? 29 : 28;
	$firstdate = mktime( 0, 0, 0, $month, 1, $year );
	$enddate = mktime( 24, 60, 60, $month, $month_array[$month], $year );
	$onetype = '';

	switch( $type )
	{
		case 'country':
			$onetype = 'click_country';
			break;
		case 'browser':
			$onetype = 'click_browse_name';
			break;
		case 'os':
			$onetype = 'click_os_name';
			break;
		case 'date':
			$onetype = 'click_time';
			break;
	}

	$process = $data = array();

	require_once NV_ROOTDIR . '/includes/class/geturl.class.php';
	$geturl = new UrlGetContents();

	$result = $db->query( "SELECT a." . $onetype . " FROM " . NV_BANNERS_GLOBALTABLE. "_click a INNER JOIN " . NV_BANNERS_GLOBALTABLE. "_rows b ON a.bid=b.id WHERE b.clid= " . $banner_client_info['id'] . " AND a.click_time <= " . $enddate . " AND a.click_time >= " . $firstdate . " AND a.bid=" . $ads . " ORDER BY click_time ASC" );
	while( $row = $result->fetch() )
	{
		if( $type == 'date' )
		{
			$row[$onetype] = date( 'd/m', $row[$onetype] );
		}
		$data[] = $row[$onetype];
	}
	if( sizeof( $data ) > 0)
	{
		$statics = array_count_values( $data );

		foreach( $statics as $country => $quantity )
		{
			if( $type == 'date' )
			{
				$process[$country . '(' . $quantity . ' click)'] = $quantity;
			}
			else
			{
				$process[$country . '(' . round( ( ( intval( $quantity ) * 100 ) / $total ), 2 ) . '%)'] = round( ( ( intval( $quantity ) * 100 ) / $total ), 2 );
			}
		}

		# google chart intergrated :|
		$imagechart = 'http://chart.apis.google.com/chart?chs=700x350&cht=p3&chco=7777CC|76A4FB|3399CC|3366CC|000000|7D5F5F|A94A4A|13E9E9|526767|DBD6D6&chd=t:';
		$imagechart .= implode( ',', array_values( $process ) );
		$imagechart .= '&chl=';
		$imagechart .= implode( '|', array_keys( $process ) );
		$imagechart .= '&chtt=Banner Stats';
		$imagechart = str_replace( ' ', '%20', $imagechart );
		header( "Content-type: image/png" );
		echo $geturl->get( $imagechart );
	}
	else
	{
		$my_img = imagecreate( 700, 80 );
		$background = imagecolorallocate( $my_img, 255, 255, 255 );
		$text_colour = imagecolorallocate( $my_img, 0, 0, 0 );
		$line_colour = imagecolorallocate( $my_img, 128, 255, 0 );
		imagestring( $my_img, 4, 30, 25, "no data", $text_colour );
		imagesetthickness( $my_img, 5 );
		imageline( $my_img, 30, 45, 165, 45, $line_colour );

		header( "Content-type: image/png" );
		imagepng( $my_img );
		imagecolordeallocate( $line_color );
		imagecolordeallocate( $text_color );
		imagecolordeallocate( $background );
		imagedestroy( $my_img );
	}
}