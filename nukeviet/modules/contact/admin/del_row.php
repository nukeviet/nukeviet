<?php

/**
 * @Project NUKEVIET 3.x
 * @Author VINADES (contact@vinades.vn)
 * @Copyright 2010 VINADES. All rights reserved
 * @Createdate Apr 22, 2010 3:00:20 PM
 */

if( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );

$id = $nv_Request->get_int( 'id', 'post', 0 );

if( empty( $id ) ) die( 'NO' );

$sql = "SELECT `id` FROM `" . NV_PREFIXLANG . "_" . $module_data . "_rows` WHERE `id`=" . $id;
$result = $db->sql_query( $sql );

if( $db->sql_numrows( $result ) != 1 ) die( 'NO' );

$sql = "DELETE FROM `" . NV_PREFIXLANG . "_" . $module_data . "_send` WHERE `cid` = " . $id;
$db->sql_query( $sql );

$sql = "DELETE FROM `" . NV_PREFIXLANG . "_" . $module_data . "_rows` WHERE `id` = " . $id;
$db->sql_query( $sql );

nv_insert_logs( NV_LANG_DATA, $module_name, 'log_del_row', "rowid " . $id, $admin_info['userid'] );

if( $db->sql_affectedrows() > 0 )
{
	$db->sql_query( "OPTIMIZE TABLE `" . NV_PREFIXLANG . "_" . $module_data . "_send`" );
	$db->sql_query( "OPTIMIZE TABLE `" . NV_PREFIXLANG . "_" . $module_data . "_rows`" );
}
else
{
	die( 'NO' );
}

nv_del_moduleCache( $module_name );

include ( NV_ROOTDIR . "/includes/header.php" );
echo 'OK';
include ( NV_ROOTDIR . "/includes/footer.php" );

?>