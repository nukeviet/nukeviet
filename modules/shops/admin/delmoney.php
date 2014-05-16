<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 2-10-2010 18:49
 */

if( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );

$id = $nv_Request->get_int( 'id', 'post,get', 0 );
$contents = "NO_" . $id;

if( $id > 0 )
{
	$sql = "DELETE FROM " . $db_config['prefix'] . "_" . $module_data . "_money_" . NV_LANG_DATA . " WHERE id=" . $id;
	if( $db->query( $sql ) )
	{
		$contents = "OK_" . $id;
	}
}
else
{
	$listall = $nv_Request->get_string( 'listall', 'post,get' );
	$array_id = explode( ',', $listall );
	$array_id = array_map( "intval", $array_id );

	foreach( $array_id as $id )
	{
		if( $id > 0 )
		{
			$sql = "DELETE FROM " . $db_config['prefix'] . "_" . $module_data . "_money_" . NV_LANG_DATA . " WHERE id=" . $id;
			$db->query( $sql );
		}
	}

	$contents = "OK_0";
}

nv_del_moduleCache( $module_name );

include NV_ROOTDIR . '/includes/header.php';
echo $contents;
include NV_ROOTDIR . '/includes/footer.php';