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

// Tong so bai viet
$number = $db->query( 'SELECT COUNT(*) FROM ' . NV_PREFIXLANG . '_' . $mod_data . '_rows WHERE status= 1 AND publtime < ' . NV_CURRENTTIME . ' AND (exptime=0 OR exptime>' . NV_CURRENTTIME . ')' )->fetchColumn();
if( $number > 0 )
{
	$siteinfo[] = array( 'key' => $lang_siteinfo['siteinfo_publtime'], 'value' => $number );
}

//So bai viet thanh vien gui toi
if( ! empty( $site_mods[$mod]['admins'] ) )
{
	$admins_module = explode( ',', $site_mods[$mod]['admins'] );
}
else
{
	$admins_module = array();
}
$result = $db->query( 'SELECT admin_id FROM ' . NV_AUTHORS_GLOBALTABLE . ' WHERE lev=1 OR lev=2' );
while( $row = $result->fetch() )
{
	$admins_module[] = $row['admin_id'];
}
$number = $db->query( 'SELECT COUNT(*) FROM ' . NV_PREFIXLANG . '_' . $mod_data . '_rows WHERE admin_id NOT IN (' . implode( ',', $admins_module ) . ')' )->fetchColumn();
if( $number > 0 )
{
	$siteinfo[] = array( 'key' => $lang_siteinfo['siteinfo_users_send'], 'value' => $number );
}

// So bai viet cho dang tu dong
$number = $db->query( 'SELECT COUNT(*) FROM ' . NV_PREFIXLANG . '_' . $mod_data . '_rows WHERE status= 1 AND publtime > ' . NV_CURRENTTIME . ' AND (exptime=0 OR exptime>' . NV_CURRENTTIME . ')' )->fetchColumn();
if( $number > 0 )
{
	$siteinfo[] = array( 'key' => $lang_siteinfo['siteinfo_pending'], 'value' => $number );
}

// So bai viet da het han
$number = $db->query( 'SELECT COUNT(*) FROM ' . NV_PREFIXLANG . '_' . $mod_data . '_rows WHERE exptime > 0 AND exptime<' . NV_CURRENTTIME )->fetchColumn();
if( $number > 0 )
{
	$siteinfo[] = array( 'key' => $lang_siteinfo['siteinfo_expired'], 'value' => $number );
}

// So bai viet sap het han
$number = $db->query( 'SELECT COUNT(*) FROM ' . NV_PREFIXLANG . '_' . $mod_data . '_rows WHERE status = 1 AND exptime>' . NV_CURRENTTIME )->fetchColumn();
if( $number > 0 )
{
	$siteinfo[] = array( 'key' => $lang_siteinfo['siteinfo_exptime'], 'value' => $number );
}

// Tong so binh luan duoc dang
$number = $db->query( 'SELECT COUNT(*) FROM ' . NV_PREFIXLANG . '_comments WHERE module=' . $db->quote( $mod ) . ' AND status = 1' )->fetchColumn();
if( $number > 0 )
{
	$siteinfo[] = array( 'key' => $lang_siteinfo['siteinfo_comment'], 'value' => $number );
}

// So binh luan cho duyet
$number = $db->query( 'SELECT COUNT(*) FROM ' . NV_PREFIXLANG . '_comments WHERE module=' . $db->quote( $mod ) . ' AND status = 0' )->fetchColumn();
if( $number > 0 )
{
	$pendinginfo[] = array( 'key' => $lang_siteinfo['siteinfo_comment_pending'], 'value' => $number );
}