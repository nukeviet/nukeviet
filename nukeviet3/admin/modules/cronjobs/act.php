<?php

/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES.,JSC. All rights reserved
 * @Createdate 2-1-2010 21:39
 */

if ( ! defined( 'NV_IS_FILE_CRONJOBS' ) ) die( 'Stop!!!' );

$id = $nv_Request->get_int( 'id', 'get', 0 );

if ( ! empty( $id ) )
{
	$query = "SELECT `act` FROM `" . NV_CRONJOBS_GLOBALTABLE . "` WHERE `id`=" . $id . " AND `is_sys`=0";
	$result = $db->sql_query( $query );
	if ( $db->sql_numrows( $result ) == 1 )
	{
		$row = $db->sql_fetchrow( $result );
		$act = intval( $row['act'] );
		$new_act = ( ! empty( $act ) ) ? 0 : 1;
		$sql = "UPDATE `" . NV_CRONJOBS_GLOBALTABLE . "` SET `act`=" . $new_act . " WHERE `id`=" . $id;
		$db->sql_query( $sql );
	}
}
Header( "Location: " . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name );
die();

?>