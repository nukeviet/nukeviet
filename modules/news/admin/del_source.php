<?php

/**
 * @Project NUKEVIET 3.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2012 VINADES.,JSC. All rights reserved
 * @Createdate 2-10-2010 18:49
 */

if( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );
if( ! defined( 'NV_IS_AJAX' ) ) die( 'Wrong URL' );

$sourceid = $nv_Request->get_int( 'sourceid', 'post', 0 );

$contents = "NO_" . $sourceid;
list( $sourceid, $title ) = $db->sql_fetchrow( $db->sql_query( "SELECT `sourceid`, `title` FROM `" . NV_PREFIXLANG . "_" . $module_data . "_sources` WHERE `sourceid`=" . intval( $sourceid ) . "" ) );
if( $sourceid > 0 )
{
	nv_insert_logs( NV_LANG_DATA, $module_name, 'log_del_source', $title, $admin_info['userid'] );
	$query = $db->sql_query( "SELECT id, listcatid FROM `" . NV_PREFIXLANG . "_" . $module_data . "_rows` WHERE `sourceid` = '" . $sourceid . "'" );
	while( $row = $db->sql_fetchrow( $query ) )
	{
		$arr_catid = explode( ",", $row['listcatid'] );
		foreach( $arr_catid as $catid_i )
		{
			$db->sql_query( "UPDATE `" . NV_PREFIXLANG . "_" . $module_data . "_" . $catid_i . "` SET `sourceid` = '0' WHERE `id` =" . $row['id'] );
		}
		$db->sql_query( "UPDATE `" . NV_PREFIXLANG . "_" . $module_data . "_rows` SET `sourceid` = '0' WHERE `id` =" . $row['id'] );
	}
	$db->sql_freeresult();
	$db->sql_query( "DELETE FROM `" . NV_PREFIXLANG . "_" . $module_data . "_sources` WHERE `sourceid`=" . $sourceid . "" );
	nv_fix_source();
	nv_del_moduleCache( $module_name );
	$contents = "OK_" . $sourceid;
}

include ( NV_ROOTDIR . "/includes/header.php" );
echo $contents;
include ( NV_ROOTDIR . "/includes/footer.php" );

?>