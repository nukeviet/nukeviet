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

if( ! $userid )
{
	die( "NO" );
}

$sql = "SELECT * FROM `" . NV_AUTHORS_GLOBALTABLE . "` WHERE `admin_id`=" . $userid;
$query = $db->sql_query( $sql );
$numrows = $db->sql_numrows( $query );
if( $numrows )
{
	die( "NO" );
}

$sql = "SELECT `username`, `full_name`, `email`, `photo`, `idsite` FROM `" . $db_config['dbsystem'] . "`.`" . NV_USERS_GLOBALTABLE . "` WHERE `userid`=" . $userid;
$query = $db->sql_query( $sql );
$numrows = $db->sql_numrows( $query );
if( $numrows != 1 )
{
	die( "NO" );
}

list( $username, $full_name, $email, $photo, $idsite ) = $db->sql_fetchrow( $query );

if( $global_config['idsite'] > 0 AND $idsite != $global_config['idsite'] )
{
	die( "NO" );
}

$query = $db->sql_query( "SELECT * FROM `" . $db_config['dbsystem'] . "`.`" . NV_GROUPS_GLOBALTABLE . "_users` WHERE `group_id` IN (1,2,3) AND `userid`=" . $userid );
if( $db->sql_numrows( $query ) )
{
	die( "ERROR_" . $lang_module['delete_group_system'] );
}
else
{
	$userdelete = ( ! empty( $full_name ) ) ? $full_name . " (" . $username . ")" : $username;

	$result = $db->sql_query( "DELETE FROM `" . $db_config['dbsystem'] . "`.`" . NV_USERS_GLOBALTABLE . "` WHERE `userid`=" . $userid );
	if( ! $result )
	{
		die( "NO" );
	}

	$db->sql_query( "UPDATE `" . $db_config['dbsystem'] . "`.`" . NV_GROUPS_GLOBALTABLE . "` SET `number` = `number`-1 WHERE `group_id` IN (SELECT `group_id` FROM `" . $db_config['dbsystem'] . "`.`" . NV_GROUPS_GLOBALTABLE . "_users` WHERE `userid`=" . $userid . ")" );
	$db->sql_query( "DELETE FROM `" . $db_config['dbsystem'] . "`.`" . NV_GROUPS_GLOBALTABLE . "_users` WHERE `userid`=" . $userid );
	$db->sql_query( "DELETE FROM `" . $db_config['dbsystem'] . "`.`" . NV_USERS_GLOBALTABLE . "_openid` WHERE `userid`=" . $userid );
	$db->sql_query( "DELETE FROM `" . $db_config['dbsystem'] . "`.`" . NV_USERS_GLOBALTABLE . "_info` WHERE `userid`=" . $userid );

	nv_insert_logs( NV_LANG_DATA, $module_name, 'log_del_user', "userid " . $userid, $admin_info['userid'] );

	if( ! empty( $photo ) and is_file( NV_ROOTDIR . '/' . $photo ) )
	{
		@nv_deletefile( NV_ROOTDIR . '/' . $photo );
	}

	$subject = $lang_module['delconfirm_email_title'];
	$message = sprintf( $lang_module['delconfirm_email_content'], $userdelete, $global_config['site_name'] );
	$message = str_replace( "\n", "<br />", $message );
	$message .= "<br /><br />------------------------------------------------<br /><br />";
	$message .= nv_EncString( $message );
	nv_sendmail( $global_config['site_email'], $email, $subject, $message );
	die( "OK" );
}

?>