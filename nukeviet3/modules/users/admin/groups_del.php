<?php

/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES.,JSC. All rights reserved
 * @Createdate 2-1-2010 15:23
 */

if ( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );

$group_id = $nv_Request->get_int( 'group_id', 'post' );

$query = "SELECT `users` FROM `" . NV_GROUPS_GLOBALTABLE . "` WHERE `group_id`=" . $group_id;
$result = $db->sql_query( $query );
$numrows = $db->sql_numrows( $result );
if ( $numrows != 1 ) die( 'NO_' . $group_id );

$row = $db->sql_fetchrow( $result );
$users = $row['users'];
if ( ! empty( $users ) )
{
    $query = "SELECT `userid`, `in_groups` FROM `" . NV_USERS_GLOBALTABLE . "` WHERE `userid` IN (" . $users . ")";
    $result = $db->sql_query( $query );
    while ( $row = $db->sql_fetchrow( $result ) )
    {
        $in_groups = $row['in_groups'];
        if ( ! empty( $in_groups ) )
        {
            $in_groups = explode( ",", $in_groups );
            $in_groups = array_diff( $in_groups, array( 
                $group_id 
            ) );
            $in_groups = ( ! empty( $in_groups ) ) ? implode( ",", $in_groups ) : "";
            $sql = "UPDATE `" . NV_USERS_GLOBALTABLE . "` SET `in_groups`=" . $db->dbescape( $in_groups ) . " WHERE `userid`=" . $row['userid'];
            $db->sql_query( $sql );
        }
    }
}

$db->sql_query( "DELETE FROM `" . NV_GROUPS_GLOBALTABLE . "` WHERE `group_id` = " . $group_id );
$db->sql_query( "LOCK TABLE " . NV_GROUPS_GLOBALTABLE . " WRITE" );
$db->sql_query( "REPAIR TABLE " . NV_GROUPS_GLOBALTABLE );
$db->sql_query( "OPTIMIZE TABLE " . NV_GROUPS_GLOBALTABLE );
$db->sql_query( "UNLOCK TABLE " . NV_GROUPS_GLOBALTABLE );

include ( NV_ROOTDIR . "/includes/header.php" );
echo 'OK_' . $group_id;
include ( NV_ROOTDIR . "/includes/footer.php" );

?>