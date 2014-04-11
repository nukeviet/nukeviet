<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 2-1-2010 21:38
 */

if( ! defined( 'NV_IS_FILE_SETTINGS' ) ) die( 'Stop!!!' );

$id = $nv_Request->get_int( 'id', 'get', 0 );
$res = false;

if( ! empty( $id ) )
{
	nv_insert_logs( NV_LANG_DATA, $module_name, 'log_cronjob_del', 'id ' . $id, $admin_info['userid'] );

	$sql = 'SELECT COUNT(*) FROM ' . NV_CRONJOBS_GLOBALTABLE . ' WHERE id=' . $id . ' AND is_sys=0';
	if( $db->query( $sql )->fetchColumn() )
	{
		$res = $db->exec( 'DELETE FROM ' . NV_CRONJOBS_GLOBALTABLE . ' WHERE id = ' . $id );

		$db->query( 'OPTIMIZE TABLE ' . NV_CRONJOBS_GLOBALTABLE );
	}
}

$res = $res ? 1 : 2;

include NV_ROOTDIR . '/includes/header.php';
echo $res;
include NV_ROOTDIR . '/includes/footer.php';