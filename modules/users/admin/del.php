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

$sql = 'SELECT admin_id FROM ' . NV_AUTHORS_GLOBALTABLE . ' WHERE admin_id=' . $userid;
$admin_id = $db->query( $sql )->fetchColumn();
if( $admin_id )
{
	die( 'NO' );
}

$sql = 'SELECT username, full_name, email, photo, idsite FROM ' . NV_USERS_GLOBALTABLE . ' WHERE userid=' . $userid;
$row = $db->query( $sql )->fetch( 3 );
if( empty( $row ) )
{
	die( 'NO' );
}

list( $username, $full_name, $email, $photo, $idsite ) = $row;

if( $global_config['idsite'] > 0 and $idsite != $global_config['idsite'] )
{
	die( 'NO' );
}

$query = $db->query( 'SELECT COUNT(*) FROM ' . NV_GROUPS_GLOBALTABLE . '_users WHERE group_id IN (1,2,3) AND userid=' . $userid );
if( $query->fetchColumn() )
{
	die( 'ERROR_' . $lang_module['delete_group_system'] );
}
else
{
	$userdelete = ( ! empty( $full_name ) ) ? $full_name . ' (' . $username . ')' : $username;

	$result = $db->exec( 'DELETE FROM ' . NV_USERS_GLOBALTABLE . ' WHERE userid=' . $userid );
	if( ! $result )
	{
		die( 'NO' );
	}

	$db->query( 'UPDATE ' . NV_GROUPS_GLOBALTABLE . ' SET numbers = numbers-1 WHERE group_id IN (SELECT group_id FROM ' . NV_GROUPS_GLOBALTABLE . '_users WHERE userid=' . $userid . ')' );
	$db->query( 'UPDATE ' . NV_GROUPS_GLOBALTABLE . ' SET numbers = numbers-1 WHERE group_id=4' );
	$db->query( 'DELETE FROM ' . NV_GROUPS_GLOBALTABLE . '_users WHERE userid=' . $userid );
	$db->query( 'DELETE FROM ' . NV_USERS_GLOBALTABLE . '_openid WHERE userid=' . $userid );
	$db->query( 'DELETE FROM ' . NV_USERS_GLOBALTABLE . '_info WHERE userid=' . $userid );

	nv_insert_logs( NV_LANG_DATA, $module_name, 'log_del_user', 'userid ' . $userid, $admin_info['userid'] );

	if( ! empty( $photo ) and is_file( NV_ROOTDIR . '/' . $photo ) )
	{
		@nv_deletefile( NV_ROOTDIR . '/' . $photo );
	}

	$subject = $lang_module['delconfirm_email_title'];
	$message = sprintf( $lang_module['delconfirm_email_content'], $userdelete, $global_config['site_name'] );
	$message = nl2br( $message );
	$message .= '<br /><br />------------------------------------------------<br /><br />';
	$message .= nv_EncString( $message );
	nv_sendmail( $global_config['site_email'], $email, $subject, $message );
	die( 'OK' );
}