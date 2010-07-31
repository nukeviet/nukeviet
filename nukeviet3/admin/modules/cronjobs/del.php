<?php

/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES.,JSC. All rights reserved
 * @Createdate 2-1-2010 21:38
 */

if ( ! defined( 'NV_IS_FILE_CRONJOBS' ) ) die( 'Stop!!!' );

$id = $nv_Request->get_int( 'id', 'get', 0 );
$res = false;

if ( ! empty( $id ) )
{
	$query = "SELECT `act` FROM `" . NV_CRONJOBS_GLOBALTABLE . "` WHERE `id`=" . $id . " AND `is_sys`=0";
	$result = $db->sql_query( $query );
	if ( $db->sql_numrows( $result ) == 1 )
	{
		$query = "DELETE FROM `" . NV_CRONJOBS_GLOBALTABLE . "` WHERE `id` = " . $id;
		$res = $db->sql_query( $query );
		$db->sql_query( "LOCK TABLE " . NV_CRONJOBS_GLOBALTABLE . " WRITE" );
		$db->sql_query( "REPAIR TABLE " . NV_CRONJOBS_GLOBALTABLE );
		$db->sql_query( "OPTIMIZE TABLE " . NV_CRONJOBS_GLOBALTABLE );
		$db->sql_query( "UNLOCK TABLE " . NV_CRONJOBS_GLOBALTABLE );
	}
}

$res = $res ? 1 : 2;

include ( NV_ROOTDIR . "/includes/header.php" );
echo $res;
include ( NV_ROOTDIR . "/includes/footer.php" );

?>