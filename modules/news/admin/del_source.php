<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 2-10-2010 18:49
 */

if( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );
if( ! defined( 'NV_IS_AJAX' ) ) die( 'Wrong URL' );

$sourceid = $nv_Request->get_int( 'sourceid', 'post', 0 );

$contents = "NO_" . $sourceid;
list( $sourceid, $title ) = $db->query( "SELECT sourceid, title FROM " . NV_PREFIXLANG . "_" . $module_data . "_sources WHERE sourceid=" . intval( $sourceid ) )->fetch( 3 );
if( $sourceid > 0 )
{
	nv_insert_logs( NV_LANG_DATA, $module_name, 'log_del_source', $title, $admin_info['userid'] );
	$result = $db->query( "SELECT id, listcatid FROM " . NV_PREFIXLANG . "_" . $module_data . "_rows WHERE sourceid = '" . $sourceid . "'" );
	while( $row = $result->fetch() )
	{
		$arr_catid = explode( ',', $row['listcatid'] );
		foreach( $arr_catid as $catid_i )
		{
			$db->query( "UPDATE " . NV_PREFIXLANG . "_" . $module_data . "_" . $catid_i . " SET sourceid = '0' WHERE id =" . $row['id'] );
		}
		$db->query( "UPDATE " . NV_PREFIXLANG . "_" . $module_data . "_rows SET sourceid = '0' WHERE id =" . $row['id'] );
	}
	$result->closeCursor();
	$db->exec( "DELETE FROM " . NV_PREFIXLANG . "_" . $module_data . "_sources WHERE sourceid=" . $sourceid );
	nv_fix_source();
	nv_del_moduleCache( $module_name );
	$contents = "OK_" . $sourceid;
}

include NV_ROOTDIR . '/includes/header.php';
echo $contents;
include NV_ROOTDIR . '/includes/footer.php';