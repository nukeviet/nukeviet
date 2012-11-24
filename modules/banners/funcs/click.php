<?php

/**
 * @Project NUKEVIET 3.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2012 VINADES.,JSC. All rights reserved
 * @Createdate 3-6-2010 0:19
 */

if( ! defined( 'NV_IS_MOD_BANNERS' ) ) die( 'Stop!!!' );

global $client_info;

$bot_name = ( $client_info['is_bot'] and ! empty( $client_info['bot_info']['name'] ) ) ? $client_info['bot_info']['name'] : "Not_bot";
$browser = ( $client_info['is_mobile'] ) ? "Mobile" : $client_info['browser']['key'];

$links = NV_MY_DOMAIN;
$id = $nv_Request->get_int( 'id', 'get', 0 );

if( $id > 0 )
{
	list( $id, $click_url ) = $db->sql_fetchrow( $db->sql_query( "SELECT id, click_url FROM `" . NV_BANNERS_ROWS_GLOBALTABLE . "` WHERE id='$id' AND act='1'" ) );

	if( $id > 0 and ! empty( $click_url ) )
	{
		$links = $click_url;
		$time_set = $nv_Request->get_int( $module_name . '_clickid_' . $id, 'cookie', 0 );
	
		if( $time_set == 0 )
		{
			$nv_Request->set_Cookie( $module_name . '_clickid_' . $id, 3600, NV_LIVE_COOKIE_TIME );
			$db->sql_query( "UPDATE " . NV_BANNERS_ROWS_GLOBALTABLE . " SET hits_total=hits_total+1 WHERE id='" . $id . "'" );
			$sql = "INSERT INTO " . NV_BANNERS_CLICK_GLOBALTABLE . " (`bid`, `click_time`, `click_day`, `click_ip`, `click_country`, `click_browse_key`, `click_browse_name`, `click_os_key`, `click_os_name`, `click_ref`) 
            		VALUES ('" . $id . "', UNIX_TIMESTAMP( ), '0', '" . $client_info['ip'] . "', '" . $client_info['country'] . "', '', '" . $browser . "', '','" . $client_info['client_os']['name'] . "','" . $client_info['referer'] . "');";
			$db->sql_query( $sql );
		}
	}
}

echo '<script type="text/javascript">';
echo '		window.location.href="' . $links . '";';
echo '</script>';
echo '<noscript>';
echo '		<meta http-equiv="refresh" content="0;url=' . $links . '" />';
echo '</noscript>';

?>