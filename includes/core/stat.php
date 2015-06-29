<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 12/29/2009 20:7
 */

if( ! defined( 'NV_MAINFILE' ) ) die( 'Stop!!!' );

function nv_stat_update()
{
	global $db, $client_info, $global_config;

	$last_update = $db->query( "SELECT c_count FROM " . NV_COUNTER_GLOBALTABLE . " WHERE c_type = 'c_time' AND c_val= 'last'" )->fetchColumn();

	if( NV_SITE_TIMEZONE_NAME == $global_config['statistics_timezone'] )
	{
		$last_year = date( 'Y', $last_update );
		$last_month = date( 'M', $last_update );
		$last_day = date( 'd', $last_update );

		$current_year = date( 'Y', NV_CURRENTTIME );
		$current_month = date( 'M', NV_CURRENTTIME );
		$current_day = date( 'd', NV_CURRENTTIME );
		$current_hour = date( 'H', NV_CURRENTTIME );
		$current_week = date( 'l', NV_CURRENTTIME );
	}
	else
	{
		date_default_timezone_set( $global_config['statistics_timezone'] );
		$last_year = date( 'Y', $last_update );
		$last_month = date( 'M', $last_update );
		$last_day = date( 'd', $last_update );

		$current_year = date( 'Y', NV_CURRENTTIME );
		$current_month = date( 'M', NV_CURRENTTIME );
		$current_day = date( 'd', NV_CURRENTTIME );
		$current_hour = date( 'H', NV_CURRENTTIME );
		$current_week = date( 'l', NV_CURRENTTIME );
		date_default_timezone_set( NV_SITE_TIMEZONE_NAME );
	}

	if( $last_year != $current_year )
	{
		$db->query( "UPDATE " . NV_COUNTER_GLOBALTABLE . " SET c_count= 0, " . NV_LANG_DATA . "_count= 0 WHERE (c_type='month' OR c_type='day' OR c_type='hour')" );
	}
	elseif( $last_month != $current_month )
	{
		$db->query( "UPDATE " . NV_COUNTER_GLOBALTABLE . " SET c_count= 0, " . NV_LANG_DATA . "_count= 0 WHERE (c_type='day' OR c_type='hour')" );
	}
	elseif( $last_day != $current_day )
	{
		$db->query( "UPDATE " . NV_COUNTER_GLOBALTABLE . " SET c_count= 0, " . NV_LANG_DATA . "_count= 0 WHERE c_type='hour'" );
	}

	$bot_name = ( $client_info['is_bot'] and ! empty( $client_info['bot_info']['name'] ) ) ? $client_info['bot_info']['name'] : '';
	$browser = $client_info['browser']['key'];
	if( $browser == 'Unknown' )
	{
		if( $client_info['is_mobile'] )
		{
			$browser = 'Mobile';
		}
		elseif( !empty( $bot_name ) )
		{
			$browser = 'bots';
		}
	}

	$sth = $db->prepare( "UPDATE " . NV_COUNTER_GLOBALTABLE . " SET last_update=" . NV_CURRENTTIME . ", c_count=c_count + 1, " . NV_LANG_DATA . "_count= " . NV_LANG_DATA . "_count + 1 WHERE
		(c_type='total' AND c_val='hits') OR
		(c_type='year' AND c_val='" . $current_year . "') OR
		(c_type='month' AND c_val='" . $current_month . "') OR
		(c_type='day' AND c_val='" . $current_day . "') OR
		(c_type='dayofweek' AND c_val='" . $current_week . "') OR
		(c_type='hour' AND c_val='" . $current_hour . "') OR
		(c_type='bot' AND c_val= :bot_name) OR
		(c_type='browser' AND c_val= :browser) OR
		(c_type='os' AND c_val= :client_os) OR
		(c_type='country' AND c_val= :country)"
	);
	$sth->bindParam( ':bot_name', $bot_name, PDO::PARAM_STR );
	$sth->bindParam( ':browser', $browser, PDO::PARAM_STR );
	$sth->bindParam( ':client_os', $client_info['client_os']['key'], PDO::PARAM_STR );
	$sth->bindParam( ':country', $client_info['country'], PDO::PARAM_STR );
	$sth->execute();

	$db->query( "UPDATE " . NV_COUNTER_GLOBALTABLE . " SET c_count= " . NV_CURRENTTIME . " WHERE c_type='c_time' AND c_val= 'last'" );
}

nv_stat_update();
$nv_Request->set_Cookie( 'statistic_' . NV_LANG_DATA, NV_CURRENTTIME, 1800 );// Đếm lại sau 30 phút khách truy cập không hoạt động