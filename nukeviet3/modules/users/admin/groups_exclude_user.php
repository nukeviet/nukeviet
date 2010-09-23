<?php

/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES.,JSC. All rights reserved
 * @Createdate 2-1-2010 15:7
 */

if ( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );

$group_id = $nv_Request->get_int( 'group_id', 'post' );
$userid = $nv_Request->get_int( 'userid', 'post' );
$query = "SELECT `users` FROM `" . NV_GROUPS_GLOBALTABLE . "` WHERE `group_id`=" . $group_id;
$result = $db->sql_query( $query );
$numrows = $db->sql_numrows( $result );
if ( $numrows != 1 ) die( "NO_" . $group_id . "_" . $userid );

$query2 = "SELECT `in_groups` FROM `" . NV_USERS_GLOBALTABLE . "` WHERE `userid`=" . $userid;
$result2 = $db->sql_query( $query2 );
$numrows2 = $db->sql_numrows( $result2 );
if ( $numrows2 != 1 ) die( "NO_" . $group_id . "_" . $userid );

$row = $db->sql_fetchrow( $result );
$users = trim( $row['users'] );
$users = ! empty( $users ) ? explode( ",", $users ) : array();
$users = array_diff( $users, array( 
    $userid 
) );
$users = array_unique( $users );
sort( $users );
$users = array_values( $users );
$users = ! empty( $users ) ? implode( ",", $users ) : "";

$row2 = $db->sql_fetchrow( $result2 );
$in_groups = trim( $row2['in_groups'] );
$in_groups = ! empty( $in_groups ) ? explode( ",", $in_groups ) : array();
$in_groups = array_diff( $in_groups, array( 
    $group_id 
) );
$in_groups = array_unique( $in_groups );
sort( $in_groups );
$in_groups = array_values( $in_groups );
$in_groups = ! empty( $in_groups ) ? implode( ",", $in_groups ) : "";

$sql = "UPDATE `" . NV_GROUPS_GLOBALTABLE . "` SET `users`=" . $db->dbescape( $users ) . " WHERE `group_id`=" . $group_id;
$db->sql_query( $sql );

$sql = "UPDATE `" . NV_USERS_GLOBALTABLE . "` SET `in_groups`=" . $db->dbescape( $in_groups ) . " WHERE `userid`=" . $userid;
$db->sql_query( $sql );
include ( NV_ROOTDIR . "/includes/header.php" );
echo "OK_" . $group_id . "_" . $userid;
include ( NV_ROOTDIR . "/includes/footer.php" );

?>