<?php

/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES.,JSC. All rights reserved
 * @Createdate 2-9-2010 14:43
 */

if ( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );

if ( ! defined( 'NV_IS_AJAX' ) ) die( 'Wrong URL' );

$userid = $nv_Request->get_int( 'userid', 'post', 0 );

if ( ! $userid )
{
    die( "NO" );
}

$sql = "SELECT a.lev, b.active FROM `" . NV_AUTHORS_GLOBALTABLE . "` a, `" . NV_USERS_GLOBALTABLE . "` b WHERE a.admin_id=" . $userid . " AND a.admin_id=b.userid";
$query = $db->sql_query( $sql );
$numrows = $db->sql_numrows( $query );
if ( ! $numrows )
{
    $level = 0;
    $sql = "SELECT active FROM `" . NV_USERS_GLOBALTABLE . "` WHERE `userid`=" . $userid;
    $query = $db->sql_query( $sql );
    list( $active ) = $db->sql_fetchrow( $query );
}
else
{
    list( $level, $active ) = $db->sql_fetchrow( $query );
    $level = ( int )$level;
}

if ( empty( $level ) or $admin_info['level'] < $level )
{
    $active = $active ? 0 : 1;
    $sql = "UPDATE `" . NV_USERS_GLOBALTABLE . "` SET `active`=" . $active . " WHERE `userid`=" . $userid;
    $result = $db->sql_query( $sql );
    echo "OK";
}

?>