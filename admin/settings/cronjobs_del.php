<?php

/**
 * @Project NUKEVIET 3.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2012 VINADES.,JSC. All rights reserved
 * @Createdate 2-1-2010 21:38
 */

if( ! defined( 'NV_IS_FILE_SETTINGS' ) ) die( 'Stop!!!' );

$id = $nv_Request->get_int( 'id', 'get', 0 );
$res = false;

if( ! empty( $id ) )
{
	nv_insert_logs( NV_LANG_DATA, $module_name, 'log_cronjob_del', 'id ' . $id, $admin_info['userid'] );

	$sql = 'SELECT act FROM ' . NV_CRONJOBS_GLOBALTABLE . ' WHERE id=' . $id . ' AND is_sys=0';
	if( $db->query( $sql )->rowCount() )
	{
		$res = $db->exec( 'DELETE FROM ' . NV_CRONJOBS_GLOBALTABLE . ' WHERE id = ' . $id );

		$db->exec( 'LOCK TABLE ' . NV_CRONJOBS_GLOBALTABLE . ' WRITE' );
		$db->exec( 'OPTIMIZE TABLE ' . NV_CRONJOBS_GLOBALTABLE );
		$db->exec( 'UNLOCK TABLE ' . NV_CRONJOBS_GLOBALTABLE );
	}
}

$res = $res ? 1 : 2;

include NV_ROOTDIR . '/includes/header.php';
echo $res;
include NV_ROOTDIR . '/includes/footer.php';

?>