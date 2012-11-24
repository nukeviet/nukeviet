<?php

/**
 * @Project NUKEVIET 3.x
 * @Author VINADES (contact@vinades.vn)
 * @Copyright (C) 2012 VINADES. All rights reserved
 * @Createdate Apr 20, 2010 10:47:41 AM
 */

if ( ! defined( 'NV_IS_FILE_SITEINFO' ) ) die( 'Stop!!!' );

$lang_siteinfo = nv_get_lang_module( $mod );

// Tong so file
list( $number ) = $db->sql_fetchrow( $db->sql_query( "SELECT COUNT(*) as number FROM `" . NV_PREFIXLANG . "_" . $mod_data . "` where `status`= 1" ) );
if ( $number > 0 )
{
	$siteinfo[] = array( 
		'key' => $lang_siteinfo['siteinfo_publtime'], 'value' => $number 
	);
}

// Tong so file het han 
list( $number ) = $db->sql_fetchrow( $db->sql_query( "SELECT COUNT(*) as number FROM `" . NV_PREFIXLANG . "_" . $mod_data . "` where `status`= 0" ) );
if ( $number > 0 )
{
	$siteinfo[] = array( 
		'key' => $lang_siteinfo['siteinfo_expired'], 'value' => $number 
	);
}

// Tong so binh luan duoc dang   
list( $number ) = $db->sql_fetchrow( $db->sql_query( "SELECT COUNT(*) as number FROM `" . NV_PREFIXLANG . "_" . $mod_data . "_comments` where `status` = 1" ) );
if ( $number > 0 )
{
	$siteinfo[] = array( 
		'key' => $lang_siteinfo['siteinfo_comment'], 'value' => $number 
	);
}

// So binh luan cho duyet   
list( $number ) = $db->sql_fetchrow( $db->sql_query( "SELECT COUNT(*) as number FROM `" . NV_PREFIXLANG . "_" . $mod_data . "_comments` where `status` = 0" ) );
if ( $number > 0 )
{
	$pendinginfo[] = array( 
		'key' => $lang_siteinfo['siteinfo_comment_pending'], 'value' => $number, 'link' => NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $mod . "&amp;" . NV_OP_VARIABLE . "=comment&amp;status=0"
	);
}

// So file dang cho duyet
$sql = "SELECT COUNT(*) as number FROM `" . NV_PREFIXLANG . "_" . $mod_data . "_tmp`";
$array_data = nv_db_cache( $sql, '', $mod );
$number = isset( $array_data[0]['number'] ) ? intval( $array_data[0]['number'] ) : 0;
if ( $number > 0 )
{
	$pendinginfo[] = array( 
		'key' => $lang_siteinfo['siteinfo_users_send'], 'value' => $number, 'link' => NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $mod . "&amp;" . NV_OP_VARIABLE . "=filequeue"
	);
}

//  So bao cao loi duoc gui toi   
list( $number ) = $db->sql_fetchrow( $db->sql_query( "SELECT COUNT(*) as number FROM `" . NV_PREFIXLANG . "_" . $mod_data . "_report`" ) );
if ( $number > 0 )
{
	$pendinginfo[] = array(
		'key' => $lang_siteinfo['siteinfo_eror'], 'value' => $number, 'link' => NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $mod . "&amp;" . NV_OP_VARIABLE . "=report"
	);
}

?>