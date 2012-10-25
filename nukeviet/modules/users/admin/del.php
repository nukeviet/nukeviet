<?php

/**
 * @Project NUKEVIET 3.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2012 VINADES.,JSC. All rights reserved
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

$sql = "SELECT `username`, `full_name`, `email`, `photo`, `in_groups` FROM `" . NV_USERS_GLOBALTABLE . "` WHERE `userid`=" . $userid;
$query = $db->sql_query( $sql );
$numrows = $db->sql_numrows( $query );
if ( $numrows != 1 )
{
    die( "NO" );
}

list( $username, $full_name, $email, $photo, $in_groups ) = $db->sql_fetchrow( $query );

$userdelete = ( ! empty( $full_name ) ) ? $full_name . " (" . $username . ")" : $username;

$sql = "DELETE FROM `" . NV_USERS_GLOBALTABLE . "` WHERE `userid`=" . $userid;
$result = $db->sql_query( $sql );

if ( ! $result )
{
    die( "NO" );
}

$sql = "DELETE FROM `" . NV_USERS_GLOBALTABLE . "_openid` WHERE `userid`=" . $userid;
$result = $db->sql_query( $sql );

if ( !empty($in_groups) )
{
    $result = $db->sql_query("SELECT `group_id`, `users` FROM `" . NV_GROUPS_GLOBALTABLE . "` WHERE `group_id` IN (" . $in_groups . ")");
    while ( list( $group_id, $users ) = $db->sql_fetchrow($result) )
    {
        $users = "," . $users . ",";
        $users = str_replace("," . $userid . ",", ",", $users);
        $users = trim($users, ",");
        $db->sql_query("UPDATE `" . NV_GROUPS_GLOBALTABLE . "` SET `users` = '" . $users . "' WHERE `group_id`=" . $group_id);
    }
}

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