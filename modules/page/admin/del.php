<?php

/**
 * @Project NUKEVIET 3.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2012 VINADES.,JSC. All rights reserved
 * @Createdate 2-1-2010 15:23
 */

if( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );

$id = $nv_Request->get_int( 'id', 'post', 0 );

if( empty( $id ) ) die( 'NO_' . $id );

$sql = "SELECT title FROM " . NV_PREFIXLANG . "_" . $module_data . " WHERE id=" . $id;
$result = $db->query( $sql );

if( $result->rowCount() != 1 ) die( 'NO_' . $id );

nv_insert_logs( NV_LANG_DATA, $module_name, 'Delete', "ID: " . $id, $admin_info['userid'] );

$sql = "DELETE FROM " . NV_PREFIXLANG . "_" . $module_data . " WHERE id = " . $id;
if( $db->exec( $sql ) )
{
	$sql = "SELECT id FROM " . NV_PREFIXLANG . "_" . $module_data . " ORDER BY weight ASC";
	$result = $db->query( $sql );
	$weight = 0;
	while( $row = $result->fetch() )
	{
		++$weight;
		$sql = "UPDATE " . NV_PREFIXLANG . "_" . $module_data . " SET weight=" . $weight . " WHERE id=" . $row['id'];
		$db->query( $sql );
	}
	nv_del_moduleCache( $module_name );
}
else
{
	die( 'NO_' . $id );
}

include NV_ROOTDIR . '/includes/header.php';
echo 'OK_' . $id;
include NV_ROOTDIR . '/includes/footer.php';

?>