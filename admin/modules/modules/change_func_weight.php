<?php

/**
 * @Project NUKEVIET 3.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2012 VINADES.,JSC. All rights reserved
 * @Createdate 3/7/2010 2:9
 */

if( ! defined( 'NV_IS_FILE_MODULES' ) ) die( 'Stop!!!' );

$fid = $nv_Request->get_int( 'fid', 'post', 0 );

if( empty( $fid ) ) die( "NO|" . $fid );

$sql = "SELECT `in_module` FROM `" . NV_MODFUNCS_TABLE . "` WHERE `func_id`=" . $fid;
$result = $db->sql_query( $sql );
$numrows = $db->sql_numrows( $result );

if( $numrows != 1 ) die( 'NO|' . $fid );

$row = $db->sql_fetchrow( $result );

$new_weight = $nv_Request->get_int( 'new_weight', 'post', 0 );

if( empty( $new_weight ) ) die( 'NO|' . $fid );

$sql = "UPDATE `" . NV_MODFUNCS_TABLE . "` SET `subweight`='0' WHERE `in_module`=" . $db->dbescape( $row['in_module'] ) . " AND `show_func` = '0'";
$db->sql_query( $sql );

$sql = "SELECT `func_id` FROM `" . NV_MODFUNCS_TABLE . "` WHERE `in_module`=" . $db->dbescape( $row['in_module'] ) . " AND func_id!=" . $fid . " AND `show_func` = '1' ORDER BY `subweight` ASC";
$result = $db->sql_query( $sql );

$weight = 0;
while( $row = $db->sql_fetchrow( $result ) )
{
	++$weight;
	
	if( $weight == $new_weight ) ++$weight;
	
	$sql = "UPDATE `" . NV_MODFUNCS_TABLE . "` SET `subweight`=" . $weight . " WHERE `func_id`=" . $row['func_id'];
	$db->sql_query( $sql );
}

$sql = "UPDATE `" . NV_MODFUNCS_TABLE . "` SET `subweight`=" . $new_weight . " WHERE `func_id`=" . $fid;
$db->sql_query( $sql );
nv_del_moduleCache( 'modules' );

include ( NV_ROOTDIR . "/includes/header.php" );
echo 'OK|show_funcs';
include ( NV_ROOTDIR . "/includes/footer.php" );

?>