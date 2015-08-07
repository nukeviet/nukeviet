<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 2-1-2010 21:39
 */

if( ! defined( 'NV_IS_FILE_SETTINGS' ) ) die( 'Stop!!!' );

$id = $nv_Request->get_int( 'id', 'get', 0 );

if( ! empty( $id ) )
{
	nv_insert_logs( NV_LANG_DATA, $module_name, 'log_cronjob_atc', 'id ' . $id, $admin_info['userid'] );

	$sql = 'SELECT act FROM ' . NV_CRONJOBS_GLOBALTABLE . ' WHERE id=' . $id . ' AND (is_sys=0 OR act=0)';
	$row = $db->query( $sql )->fetch();

	if( ! empty( $row ) )
	{
		$act = intval( $row['act'] );
		$new_act = ( ! empty( $act ) ) ? 0 : 1;
		$db->query( 'UPDATE ' . NV_CRONJOBS_GLOBALTABLE . ' SET act=' . $new_act . ' WHERE id=' . $id );
	}
}

Header( 'Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=cronjobs' );
die();