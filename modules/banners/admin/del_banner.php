<?php

/**
 * @Project NUKEVIET 3.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2012 VINADES.,JSC. All rights reserved
 * @Createdate 2-9-2010 14:43
 */

if( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );
$id = $nv_Request->get_int( 'id', 'post,get' );

$sql = "SELECT * FROM " . NV_BANNERS_GLOBALTABLE. "_rows WHERE id=" . $id;
$result = $db->query( $sql );

if( $result->rowCount() )
{
	$row = $result->fetch();

	if( ! empty( $row['file_name'] ) and file_exists( NV_UPLOADS_REAL_DIR . "/" . NV_BANNER_DIR . "/" . $row['file_name'] ) )
	{
		nv_deletefile( NV_UPLOADS_REAL_DIR . "/" . NV_BANNER_DIR . "/" . $row['file_name'], false );
	}

	if( ! empty( $row['imageforswf'] ) and file_exists( NV_UPLOADS_REAL_DIR . "/" . NV_BANNER_DIR . "/" . $row['imageforswf'] ) )
	{
		nv_deletefile( NV_UPLOADS_REAL_DIR . "/" . NV_BANNER_DIR . "/" . $row['imageforswf'], false );
	}
	$sql = "DELETE FROM " . NV_BANNERS_GLOBALTABLE. "_rows WHERE id='$id'";
	$result1 = $db->query( $sql );

	$sql = "DELETE FROM " . NV_BANNERS_GLOBALTABLE. "_click WHERE bid='$id'";
	$result = $db->query( $sql );

	nv_CreateXML_bannerPlan();

	nv_insert_logs( NV_LANG_DATA, $module_name, 'log_del_banner', "bannerid " . $id, $admin_info['userid'] );
	if( defined( 'NV_IS_AJAX' ) )
	{
		 echo $lang_module['delfile_success'];
	}
	else
	{
		Header( 'Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=banners_list' );
		die();
	}
}
else
{
	echo $lang_module['delfile_error'];
}

?>