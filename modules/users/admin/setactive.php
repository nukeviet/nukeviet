<?php

/**
 * @Project NUKEVIET 3.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2012 VINADES.,JSC. All rights reserved
 * @Createdate 2-9-2010 14:43
 */

if( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );

if( ! defined( 'NV_IS_AJAX' ) ) die( 'Wrong URL' );

$userid = $nv_Request->get_int( 'userid', 'post', 0 );

if( ! $userid or $admin_info['admin_id'] == $userid )
{
	die( "NO" );
}

$sql = "SELECT a.lev, b.username, b.active, b.idsite FROM `" . NV_AUTHORS_GLOBALTABLE . "` a, `" . $db_config['dbsystem'] . "`.`" . NV_USERS_GLOBALTABLE . "` b WHERE a.admin_id=" . $userid . " AND a.admin_id=b.userid";
$query = $db->sql_query( $sql );
$numrows = $db->sql_numrows( $query );
if( ! $numrows )
{
	$level = 0;
	$sql = "SELECT username, active, idsite FROM `" . $db_config['dbsystem'] . "`.`" . NV_USERS_GLOBALTABLE . "` WHERE `userid`=" . $userid;
	$query = $db->sql_query( $sql );
	list( $username, $active, $idsite ) = $db->sql_fetchrow( $query );
}
else
{
	list( $level, $username, $active, $idsite ) = $db->sql_fetchrow( $query );
	$level = ( int )$level;
}

if( empty( $level ) or $admin_info['level'] < $level )
{
	if( $global_config['idsite'] > 0 AND $idsite != $global_config['idsite'] )
	{
		die( "NO" );
	}
	$active = $active ? 0 : 1;
	$sql = "UPDATE `" . $db_config['dbsystem'] . "`.`" . NV_USERS_GLOBALTABLE . "` SET `active`=" . $active . " WHERE `userid`=" . $userid;
	$result = $db->sql_query( $sql );

	$note = ( $active ) ? $lang_module['active_users'] : $lang_module['unactive_users'];
	nv_insert_logs( NV_LANG_DATA, $module_name, $note, 'userid: ' . $userid . ' - username: ' . $username, $admin_info['userid'] );
	echo "OK";
}

?>