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
nv_insert_logs( NV_LANG_DATA, $module_name, 'log_del_about', "aboutid  " . $id, $admin_info['userid'] );
$query = "DELETE FROM `" . NV_PREFIXLANG . "_" . $module_data . "` WHERE `id` = " . $id;
$db->sql_query( $query );
if ( $db->sql_affectedrows() > 0 )
{
    $query = "SELECT `id` FROM `" . NV_PREFIXLANG . "_" . $module_data . "` ORDER BY `weight` ASC";
    $result = $db->sql_query( $query );
    $weight = 0;
    while ( $row = $db->sql_fetchrow( $result ) )
    {
        ++$weight;
        $sql = "UPDATE `" . NV_PREFIXLANG . "_" . $module_data . "` SET `weight`=" . $weight . " WHERE `id`=" . $row['id'];
        $db->sql_query( $sql );
    }
	nv_del_moduleCache( $module_name );
}
else
{
    die( 'NO_' . $id );
}

include ( NV_ROOTDIR . "/includes/header.php" );
echo 'OK_' . $id;
include ( NV_ROOTDIR . "/includes/footer.php" );

?>