<?php

/**
 * @Project NUKEVIET 3.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2012 VINADES.,JSC. All rights reserved
 * @Createdate 3/11/2010 21:1
 */

if( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );

if( ! defined( 'NV_IS_AJAX' ) ) die( 'Wrong URL' );

$id = $nv_Request->get_int( 'id', 'post', 0 );
nv_insert_logs( NV_LANG_DATA, $module_name, 'log_del_client', "clientid " . $id, $admin_info['userid'] );

if( empty( $id ) ) die( 'Stop!!!' );

$sql = "SELECT * FROM `" . NV_BANNERS_CLIENTS_GLOBALTABLE . "` WHERE `id`=" . $id;
$result = $db->sql_query( $sql );
$numrows = $db->sql_numrows( $result );
if( $numrows != 1 ) die( 'Stop!!!' );

$banners_id = array();
$sql = "SELECT `id`, `file_name`,`file_name_tmp` FROM `" . NV_BANNERS_ROWS_GLOBALTABLE . "` WHERE `clid`=" . $id;
$result = $db->sql_query( $sql );

while( $row = $db->sql_fetchrow( $result ) )
{
	if( ! empty( $row['file_name'] ) and is_file( NV_ROOTDIR . '/' . $row['file_name'] ) )
	{
		@nv_deletefile( NV_ROOTDIR . '/' . $row['file_name'] );
	}

	if( ! empty( $row['file_name_tmp'] ) and is_file( NV_ROOTDIR . '/' . $row['file_name_tmp'] ) )
	{
		@nv_deletefile( NV_ROOTDIR . '/' . $row['file_name_tmp'] );
	}
	$banners_id[] = $row['id'];
}

if( ! empty( $banners_id ) )
{
	$banners_id = implode( ",", $banners_id );

	$result = $db->sql_query( "SHOW TABLE STATUS LIKE '" . NV_BANNERS_CLICK_GLOBALTABLE . "\_%'" );
	
	while( $item = $db->sql_fetch_assoc( $result ) )
	{
		$query = "DELETE FROM `" . $item['Name'] . "` WHERE `bid` IN (" . $banners_id . ")";
		$db->sql_query( $query );

		$db->sql_query( "REPAIR TABLE " . $item['Name'] );
		$db->sql_query( "OPTIMIZE TABLE " . $item['Name'] );
	}

	$query = "DELETE FROM `" . NV_BANNERS_CLICK_GLOBALTABLE . "` WHERE `bid` IN (" . $banners_id . ")";
	$db->sql_query( $query );

	$db->sql_query( "REPAIR TABLE " . NV_BANNERS_CLICK_GLOBALTABLE );
	$db->sql_query( "OPTIMIZE TABLE " . NV_BANNERS_CLICK_GLOBALTABLE );

	$query = "DELETE FROM `" . NV_BANNERS_ROWS_GLOBALTABLE . "` WHERE `clid` = " . $id;
	$db->sql_query( $query );

	$db->sql_query( "REPAIR TABLE " . NV_BANNERS_ROWS_GLOBALTABLE );
	$db->sql_query( "OPTIMIZE TABLE " . NV_BANNERS_ROWS_GLOBALTABLE );

	nv_CreateXML_bannerPlan();
}

$query = "DELETE FROM `" . NV_BANNERS_CLIENTS_GLOBALTABLE . "` WHERE `id` = " . $id;
$db->sql_query( $query );

$db->sql_query( "REPAIR TABLE " . NV_BANNERS_CLIENTS_GLOBALTABLE );
$db->sql_query( "OPTIMIZE TABLE " . NV_BANNERS_CLIENTS_GLOBALTABLE );

include ( NV_ROOTDIR . "/includes/header.php" );
echo "OK|client_list|client_list";
include ( NV_ROOTDIR . "/includes/footer.php" );

?>