<?php

/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES.,JSC. All rights reserved
 * @Createdate 2-1-2010 15:23
 */

if ( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );

$id = $nv_Request->get_int( 'id', 'post', 0 );

if ( empty( $id ) ) die( 'NO_' . $id );

$query = "SELECT `title` FROM `" . NV_PREFIXLANG . "_" . $module_data . "` WHERE `id`=" . $id;
$result = $db->sql_query( $query );
$numrows = $db->sql_numrows( $result );
if ( $numrows != 1 ) die( 'NO_' . $id );

$query = "DELETE FROM `" . NV_PREFIXLANG . "_" . $module_data . "` WHERE `id` = " . $id;
$db->sql_query( $query );
if ( $db->sql_affectedrows() > 0 )
{
    $db->sql_query( "LOCK TABLE `" . NV_PREFIXLANG . "_" . $module_data . "` WRITE" );
    $db->sql_query( "REPAIR TABLE `" . NV_PREFIXLANG . "_" . $module_data . "`" );
    $db->sql_query( "OPTIMIZE TABLE `" . NV_PREFIXLANG . "_" . $module_data . "`" );
    $db->sql_query( "UNLOCK TABLE `" . NV_PREFIXLANG . "_" . $module_data . "`" );
}
else
{
    die( 'NO_' . $id );
}

include ( NV_ROOTDIR . "/includes/header.php" );
echo 'OK_' . $id;
include ( NV_ROOTDIR . "/includes/footer.php" );

?>