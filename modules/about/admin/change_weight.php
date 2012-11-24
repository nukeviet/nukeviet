<?php

/**
 * @Project NUKEVIET 3.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2012 VINADES.,JSC. All rights reserved
 * @Createdate 2-10-2010 18:49
 */

if( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );

$id = $nv_Request->get_int( 'id', 'post', 0 );
if( empty( $id ) ) die( "NO_" . $id );

$sql = "SELECT `weight` FROM `" . NV_PREFIXLANG . "_" . $module_data . "` WHERE `id`=" . $id;
$result = $db->sql_query( $sql );
$numrows = $db->sql_numrows( $result );
if( $numrows != 1 ) die( 'NO_' . $id );

$new_weight = $nv_Request->get_int( 'new_weight', 'post', 0 );
if( empty( $new_weight ) ) die( 'NO_' . $mod );

$sql = "SELECT `id` FROM `" . NV_PREFIXLANG . "_" . $module_data . "` WHERE `id`!=" . $id . " ORDER BY `weight` ASC";
$result = $db->sql_query( $sql );

$weight = 0;
while( $row = $db->sql_fetchrow( $result ) )
{
	++$weight;
	if( $weight == $new_weight ) ++$weight;
	
	$sql = "UPDATE `" . NV_PREFIXLANG . "_" . $module_data . "` SET `weight`=" . $weight . " WHERE `id`=" . $row['id'];
	$db->sql_query( $sql );
}

$sql = "UPDATE `" . NV_PREFIXLANG . "_" . $module_data . "` SET `weight`=" . $new_weight . " WHERE `id`=" . $id;
$db->sql_query( $sql );

nv_del_moduleCache( $module_name );

include ( NV_ROOTDIR . "/includes/header.php" );
echo 'OK_' . $id;
include ( NV_ROOTDIR . "/includes/footer.php" );

?>