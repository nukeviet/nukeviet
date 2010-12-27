<?php

/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES.,JSC. All rights reserved
 * @Createdate 2-9-2010 14:43
 */

if ( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );

if ( ! defined( 'NV_IS_AJAX' ) ) die( 'Wrong URL' );

$userid = $nv_Request->get_int( 'userid', 'post', 0 );

if ( ! $userid )
{
    die( "NO" );
}

$sql = "SELECT * FROM `" . NV_AUTHORS_GLOBALTABLE . "` WHERE `admin_id`=" . $userid;
$query = $db->sql_query( $sql );
$numrows = $db->sql_numrows( $query );
if ( $numrows )
{
    die( "NO" );
}

$sql = "SELECT `username`, `full_name`, `email`, `photo` FROM `" . NV_USERS_GLOBALTABLE . "` WHERE `userid`=" . $userid;
$query = $db->sql_query( $sql );
$numrows = $db->sql_numrows( $query );
if ( $numrows != 1 )
{
    die( "NO" );
}

list( $username, $full_name, $email, $photo ) = $db->sql_fetchrow( $query );

$userdelete = ( ! empty( $full_name ) ) ? $full_name . " (" . $username . ")" : $username;

$sql = "DELETE FROM `" . NV_USERS_GLOBALTABLE . "` WHERE `userid`=" . $userid;
$result = $db->sql_query( $sql );

if ( ! $result )
{
    die( "NO" );
}

$sql = "DELETE FROM `" . NV_USERS_GLOBALTABLE . "_openid` WHERE `userid`=" . $userid;
$result = $db->sql_query( $sql );
nv_insert_logs( NV_LANG_DATA, $module_name, 'log_del_user', "userid ".$userid, $admin_info['userid'] );

if ( ! empty( $photo ) and is_file( NV_ROOTDIR . '/' . $photo ) )
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

?>