<?php

/**
 * @Project NUKEVIET 3.1
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2012 VINADES.,JSC. All rights reserved
 * @Createdate 20-03-2011 20:08
 */

if( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );

if( ! defined( 'NV_IS_AJAX' ) ) die( 'Wrong URL' );

$id = $nv_Request->get_int( 'id', 'post', 0 );
$mid = $nv_Request->get_int( 'mid', 'post', 0 );
$parentid = $nv_Request->get_int( 'parentid', 'post', 0 );

if( empty( $id ) ) die( 'NO_' . $id );

$sql = "SELECT `title` FROM `" . NV_PREFIXLANG . "_" . $module_data . "_rows` WHERE `id`=" . $id . " AND `parentid`=" . $parentid;
$result = $db->sql_query( $sql );

if( $db->sql_numrows( $result ) != 1 ) die( 'NO_' . $id );
nv_insert_logs( NV_LANG_DATA, $module_name, 'Delete menu item', "Item ID  " . $id, $admin_info['userid'] );

$sql = "DELETE FROM `" . NV_PREFIXLANG . "_" . $module_data . "_rows` WHERE `id`=" . $id . " AND `parentid`=" . $parentid;
$db->sql_query( $sql );

if( $db->sql_affectedrows() > 0 )
{
	nv_del_moduleCache( $module_name );
	nv_fix_cat_order( $mid );

	// Cap nhat cho bo menu
	$arr_block = array();
	$sql = "SELECT `id` FROM `" . NV_PREFIXLANG . "_" . $module_data . "_rows` WHERE `mid`= " . $mid;
	$result = $db->sql_query( $sql );
	while( $row = $db->sql_fetchrow( $result ) )
	{
		$arr_block[] = $row['id'];
	}
	$sql = "UPDATE `" . NV_PREFIXLANG . "_" . $module_data . "_menu` SET `menu_item`= '" . implode( ",", $arr_block ) . "' WHERE `id`=" . $mid;
	$db->sql_query( $sql );

	// Cap nhat cho menu cha
	if( $parentid > 0 )
	{
		$sql = "SELECT `subitem` FROM `" . NV_PREFIXLANG . "_" . $module_data . "_rows` WHERE `id`=" . $parentid;
		$result = $db->sql_query( $sql );
		if( $db->sql_numrows( $result ) == 1 )
		{
			list( $subitem ) = $db->sql_fetchrow( $result );
			$subitem = implode( ',', array_diff( array_filter( array_unique( explode( ',', $subitem ) ) ), array( $id ) ) );

			$sql = "UPDATE `" . NV_PREFIXLANG . "_" . $module_data . "_rows` SET `subitem`=" . $db->dbescape( $subitem ) . " WHERE `id`=" . $parentid;
			$db->sql_query( $sql );
		}
	}
}
else
{
	die( 'NO_' . $id );
}

include ( NV_ROOTDIR . "/includes/header.php" );
echo 'OK_' . $id . '_' . $mid . '_' . $parentid;
include ( NV_ROOTDIR . "/includes/footer.php" );

?>