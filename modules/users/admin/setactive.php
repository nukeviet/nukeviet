<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 2-9-2010 14:43
 */

if( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );

if( ! defined( 'NV_IS_AJAX' ) ) die( 'Wrong URL' );

$userid = $nv_Request->get_int( 'userid', 'post', 0 );

if( ! $userid or $admin_info['admin_id'] == $userid )
{
	die( 'NO' );
}

$sql = "SELECT a.lev, b.username, b.active, b.idsite FROM " . NV_AUTHORS_GLOBALTABLE . " a, " . NV_USERS_GLOBALTABLE . " b WHERE a.admin_id=" . $userid . " AND a.admin_id=b.userid";
$row = $db->query( $sql )->fetch( 3 );
if( empty( $row ) )
{
	$level = 0;
	$sql = "SELECT username, active, idsite FROM " . NV_USERS_GLOBALTABLE . " WHERE userid=" . $userid;
	list( $username, $active, $idsite ) = $db->query( $sql )->fetch( 3 );
}
else
{
	list( $level, $username, $active, $idsite ) = $row;
	$level = ( int )$level;
}

if( empty( $level ) or $admin_info['level'] < $level )
{
	if( $global_config['idsite'] > 0 and $idsite != $global_config['idsite'] )
	{
		die( 'NO' );
	}
	$active = $active ? 0 : 1;
	$sql = "UPDATE " . NV_USERS_GLOBALTABLE . " SET active=" . $active . " WHERE userid=" . $userid;
	$result = $db->query( $sql );

	$note = ( $active ) ? $lang_module['active_users'] : $lang_module['unactive_users'];
	nv_insert_logs( NV_LANG_DATA, $module_name, $note, 'userid: ' . $userid . ' - username: ' . $username, $admin_info['userid'] );
	echo "OK";
}