<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES (contact@vinades.vn)
 * @Copyright 2014 VINADES. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Apr 22, 2010 3:00:20 PM
 */

if( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );

$id = $nv_Request->get_int( 'id', 'post', 0 );

$sql = 'SELECT id FROM ' . NV_PREFIXLANG . '_' . $module_data . '_rows WHERE id=' . $id;
$id = $db->query( $sql )->fetchColumn();

if( empty( $id ) ) die( 'NO' );

$sql1 = 'DELETE FROM ' . NV_PREFIXLANG . '_' . $module_data . '_send WHERE cid = ' . $id;

$sql2 = 'DELETE FROM ' . NV_PREFIXLANG . '_' . $module_data . '_rows WHERE id = ' . $id;

if( $db->exec( $sql1 ) AND $db->exec( $sql2 ) )
{
	nv_insert_logs( NV_LANG_DATA, $module_name, 'log_del_row', 'rowid ' . $id, $admin_info['userid'] );

	$db->exec( 'OPTIMIZE TABLE ' . NV_PREFIXLANG . '_' . $module_data . '_send' );
	$db->exec( 'OPTIMIZE TABLE ' . NV_PREFIXLANG . '_' . $module_data . '_rows' );
}
else
{
	die( 'NO' );
}

nv_del_moduleCache( $module_name );

include NV_ROOTDIR . '/includes/header.php';
echo 'OK';
include NV_ROOTDIR . '/includes/footer.php';

?>