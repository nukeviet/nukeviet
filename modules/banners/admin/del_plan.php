<?php

/**
 * @Project NUKEVIET 3.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2012 VINADES.,JSC. All rights reserved
 * @Createdate 3/12/2010 23:10
 */

if( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );

if( ! defined( 'NV_IS_AJAX' ) ) die( 'Wrong URL' );

$id = $nv_Request->get_int( 'id', 'post', 0 );

if( empty( $id ) ) die( 'Stop!!!' );

nv_insert_logs( NV_LANG_DATA, $module_name, 'log_del_plan', "planid " . $id, $admin_info['userid'] );

$sql = "SELECT `act` FROM `" . NV_BANNERS_GLOBALTABLE. "_plans` WHERE `id`=" . $id;
$result = $db->sql_query( $sql );
$numrows = $db->sql_numrows( $result );
if( $numrows != 1 ) die( 'Stop!!!' );

$banners_id = array();
$sql = "SELECT `id`, `file_name`, `imageforswf` FROM `" . NV_BANNERS_GLOBALTABLE. "_rows` WHERE `pid`=" . $id;
$result = $db->sql_query( $sql );

while( $row = $db->sql_fetchrow( $result ) )
{
	if( ! empty( $row['file_name'] ) and is_file( NV_UPLOADS_REAL_DIR . '/' . NV_BANNER_DIR . '/' . $row['file_name'] ) )
	{
		@nv_deletefile( NV_UPLOADS_REAL_DIR . '/' . NV_BANNER_DIR . '/' . $row['file_name'] );
	}
	if( ! empty( $row['imageforswf'] ) and is_file( NV_UPLOADS_REAL_DIR . '/' . NV_BANNER_DIR . '/' . $row['imageforswf'] ) )
	{
		@nv_deletefile( NV_UPLOADS_REAL_DIR . '/' . NV_BANNER_DIR . '/' . $row['imageforswf'] );
	}
	$banners_id[] = $row['id'];
}

if( ! empty( $banners_id ) )
{
	$banners_id = implode( ",", $banners_id );

	$sql = "DELETE FROM `" . NV_BANNERS_GLOBALTABLE. "_click` WHERE `bid` IN (" . $banners_id . ")";
	$db->sql_query( $sql );

	$db->sql_query( "REPAIR TABLE `" . NV_BANNERS_GLOBALTABLE. "_click`" );
	$db->sql_query( "OPTIMIZE TABLE `" . NV_BANNERS_GLOBALTABLE. "_click`" );

	$sql = "DELETE FROM `" . NV_BANNERS_GLOBALTABLE. "_rows` WHERE `pid` = " . $id;
	$db->sql_query( $sql );

	$db->sql_query( "REPAIR TABLE `" . NV_BANNERS_GLOBALTABLE. "_rows`" );
	$db->sql_query( "OPTIMIZE TABLE `" . NV_BANNERS_GLOBALTABLE. "_rows`" );
}

$sql = "DELETE FROM `" . NV_BANNERS_GLOBALTABLE. "_plans` WHERE `id` = " . $id;
$db->sql_query( $sql );

$db->sql_query( "REPAIR TABLE `" . NV_BANNERS_GLOBALTABLE. "_plans`" );
$db->sql_query( "OPTIMIZE TABLE `" . NV_BANNERS_GLOBALTABLE. "_plans`" );

nv_CreateXML_bannerPlan();

include ( NV_ROOTDIR . '/includes/header.php' );
echo "OK|plans_list|plans_list";
include ( NV_ROOTDIR . '/includes/footer.php' );

?>