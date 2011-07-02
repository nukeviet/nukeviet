<?php

/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES.,JSC. All rights reserved
 * @Createdate 2-1-2010 15:25
 */

if ( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );

$group_id = $nv_Request->get_int( 'group_id', 'post', 0 );
$query = "SELECT `act` FROM `" . NV_GROUPS_GLOBALTABLE . "` WHERE `group_id`=" . $group_id;
$result = $db->sql_query( $query );
$numrows = $db->sql_numrows( $result );
if ( $numrows != 1 )
{
	die( 'NO_' . $group_id );
}

$row = $db->sql_fetchrow( $result );
$act = $row['act'] ? 0 : 1;
$sql = "UPDATE `" . NV_GROUPS_GLOBALTABLE . "` SET `act`=" . $act . " WHERE `group_id`=" . $group_id;
$db->sql_query( $sql );
include ( NV_ROOTDIR . "/includes/header.php" );
echo 'OK_' . $group_id;
include ( NV_ROOTDIR . "/includes/footer.php" );

?>