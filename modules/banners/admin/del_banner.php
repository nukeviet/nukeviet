<?php

/**
 * @Project NUKEVIET 3.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2012 VINADES.,JSC. All rights reserved
 * @Createdate 2-9-2010 14:43
 */

if( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );
$id = $nv_Request->get_int( 'id', 'post,get' );

$sql = "SELECT * FROM `" . NV_BANNERS_ROWS_GLOBALTABLE . "` WHERE `id`=" . $id;
$result = $db->sql_query( $sql );

if( $db->sql_numrows( $result ) )
{
	$row = $db->sql_fetchrow( $result );
	
	if( ! empty( $row['file_name'] ) )
	{
		nv_deletefile( NV_UPLOADS_REAL_DIR . "/" . NV_BANNER_DIR . "/" . $row['file_name'], false );
	}
	
	$sql = "DELETE FROM `" . NV_BANNERS_ROWS_GLOBALTABLE . "` WHERE id='$id'";
	$result1 = $db->sql_query( $sql );
	
	$sql = "DELETE FROM `" . NV_BANNERS_CLICK_GLOBALTABLE . "` WHERE bid='$id'";
	$result = $db->sql_query( $sql );

	nv_CreateXML_bannerPlan();

	nv_insert_logs( NV_LANG_DATA, $module_name, 'log_del_banner', "bannerid " . $id, $admin_info['userid'] );
	echo $lang_module['delfile_success'];
}
else
{
	echo $lang_module['delfile_error'];
}

?>