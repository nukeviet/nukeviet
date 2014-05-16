<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Apr 20, 2010 10:47:41 AM
 */

if( ! defined( 'NV_IS_FILE_SITEINFO' ) ) die( 'Stop!!!' );

$lang_siteinfo = nv_get_lang_module( $mod );

// Tong so san pham
$number = $db->query( 'SELECT COUNT(*) FROM ' . $db_config['prefix'] . '_' . $mod_data . '_rows where status = 1 AND publtime < ' . NV_CURRENTTIME . ' AND (exptime=0 OR exptime>' . NV_CURRENTTIME . ')' )->fetchColumn();
if( $number > 0 )
{
	$siteinfo[] = array( 'key' => $lang_siteinfo['siteinfo_publtime'], 'value' => $number );
}

// So san pham cho dang
$number = $db->query( 'SELECT COUNT(*) FROM ' . $db_config['prefix'] . '_' . $mod_data . '_rows where status = 0 AND publtime < ' . NV_CURRENTTIME . ' AND (exptime=0 OR exptime>' . NV_CURRENTTIME . ')' )->fetchColumn();
if( $number > 0 )
{
	$pendinginfo[] = array( 'key' => $lang_siteinfo['siteinfo_pending'], 'value' => $number );
}

// So san pham da het han
$number = $db->query( 'SELECT COUNT(*) FROM ' . $db_config['prefix'] . '_' . $mod_data . '_rows where exptime > 0 AND exptime<' . NV_CURRENTTIME )->fetchColumn();
if( $number > 0 )
{
	$siteinfo[] = array( 'key' => $lang_siteinfo['siteinfo_expired'], 'value' => $number );
}

// So san pham sap het han
$number = $db->query( 'SELECT COUNT(*) FROM ' . $db_config['prefix'] . '_' . $mod_data . '_rows where status = 1 AND exptime>' . NV_CURRENTTIME )->fetchColumn();
if( $number > 0 )
{
	$pendinginfo[] = array( 'key' => $lang_siteinfo['siteinfo_exptime'], 'value' => $number );
}

// Tong so binh luan duoc dang
$number = $db->query( 'SELECT COUNT(*) FROM ' . NV_PREFIXLANG . '_comments WHERE module=' . $db->quote( $module_name ) . ' AND status = 1' )->fetchColumn();
if( $number > 0 )
{
	$siteinfo[] = array( 'key' => $lang_siteinfo['siteinfo_comment'], 'value' => $number );
}

// So binh luan cho duyet
$number = $db->query( 'SELECT COUNT(*) FROM ' . NV_PREFIXLANG . '_comments WHERE module=' . $db->quote( $module_name ) . ' AND status = 0' )->fetchColumn();
if( $number > 0 )
{
	$pendinginfo[] = array( 'key' => $lang_siteinfo['siteinfo_comment_pending'], 'value' => $number );
}

// So don dat hang
$number = $db->query( 'SELECT COUNT(*) FROM ' . $db_config['prefix'] . '_' . $mod_data . '_orders' )->fetchColumn();
if( $number > 0 )
{
	$siteinfo[] = array( 'key' => $lang_siteinfo['siteinfo_order'], 'value' => $number );
}

// So don dat hang chua xem
$number = $db->query( 'SELECT COUNT(*) as number FROM ' . $db_config['prefix'] . '_' . $mod_data . '_orders where order_view = 0 ' )->fetchColumn();
if( $number > 0 )
{
	$pendinginfo[] = array( 'key' => $lang_siteinfo['siteinfo_order_noview'], 'value' => $number );
}