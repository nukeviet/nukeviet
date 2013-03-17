<?php

/**
 * @Project NUKEVIET 3.x
 * @Author VINADES (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES. All rights reserved
 * @Createdate Apr 20, 2010 10:47:41 AM
 */

if( ! defined( 'NV_IS_FILE_SITEINFO' ) ) die( 'Stop!!!' );

$lang_siteinfo = nv_get_lang_module( $mod );

// Tong so san pham
list( $number ) = $db->sql_fetchrow( $db->sql_query( "SELECT COUNT(*) as number FROM `" . $db_config['prefix'] . "_" . $mod_data . "_rows` where `status`= 1 AND `publtime` < " . NV_CURRENTTIME . " AND (`exptime`=0 OR `exptime`>" . NV_CURRENTTIME . ")" ) );
if( $number > 0 )
{
	$siteinfo[] = array( 'key' => $lang_siteinfo['siteinfo_publtime'], 'value' => $number );
}

// So san pham cho dang
list( $number ) = $db->sql_fetchrow( $db->sql_query( "SELECT COUNT(*) as number FROM `" . $db_config['prefix'] . "_" . $mod_data . "_rows` where `status`= 0 AND `publtime` < " . NV_CURRENTTIME . " AND (`exptime`=0 OR `exptime`>" . NV_CURRENTTIME . ")" ) );
if( $number > 0 )
{
	$pendinginfo[] = array( 'key' => $lang_siteinfo['siteinfo_pending'], 'value' => $number );
}

// So san pham da het han
list( $number ) = $db->sql_fetchrow( $db->sql_query( "SELECT COUNT(*) as number FROM `" . $db_config['prefix'] . "_" . $mod_data . "_rows` where `exptime` > 0 AND `exptime`<" . NV_CURRENTTIME ) );
if( $number > 0 )
{
	$siteinfo[] = array( 'key' => $lang_siteinfo['siteinfo_expired'], 'value' => $number );
}

// So san pham sap het han
list( $number ) = $db->sql_fetchrow( $db->sql_query( "SELECT COUNT(*) as number FROM `" . $db_config['prefix'] . "_" . $mod_data . "_rows` where `status` = 1 AND `exptime`>" . NV_CURRENTTIME ) );
if( $number > 0 )
{
	$pendinginfo[] = array( 'key' => $lang_siteinfo['siteinfo_exptime'], 'value' => $number );
}

// Tong so binh luan duoc dang
list( $number ) = $db->sql_fetchrow( $db->sql_query( "SELECT COUNT(*) as number FROM `" . $db_config['prefix'] . "_" . $mod_data . "_comments_" . NV_LANG_DATA . "` where `status` = 1" ) );
if( $number > 0 )
{
	$siteinfo[] = array( 'key' => $lang_siteinfo['siteinfo_comment'], 'value' => $number );
}

// So binh luan cho duyet
list( $number ) = $db->sql_fetchrow( $db->sql_query( "SELECT COUNT(*) as number FROM `" . $db_config['prefix'] . "_" . $mod_data . "_comments_" . NV_LANG_DATA . "` where `status` = 0" ) );
if( $number > 0 )
{
	$pendinginfo[] = array( 'key' => $lang_siteinfo['siteinfo_comment_pending'], 'value' => $number );
}

// So don dat hang
list( $number ) = $db->sql_fetchrow( $db->sql_query( "SELECT COUNT(*) as number FROM `" . $db_config['prefix'] . "_" . $mod_data . "_orders`" ) );
if( $number > 0 )
{
	$siteinfo[] = array( 'key' => $lang_siteinfo['siteinfo_order'], 'value' => $number );
}

// So don dat hang chua xem
list( $number ) = $db->sql_fetchrow( $db->sql_query( "SELECT COUNT(*) as number FROM `" . $db_config['prefix'] . "_" . $mod_data . "_orders` where `view` = 0 " ) );
if( $number > 0 )
{
	$pendinginfo[] = array( 'key' => $lang_siteinfo['siteinfo_order_noview'], 'value' => $number );
}

?>