<?php

/**
 * @Project NUKEVIET 3.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2012 VINADES.,JSC. All rights reserved
 * @createdate 10/03/2010 10:51
 */

if( ! defined( 'NV_IS_MOD_USER' ) )
{
	die( 'Stop!!!' );
}

if( defined( 'NV_IS_USER_FORUM' ) )
{
	Header( "Location: " . nv_url_rewrite( NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name, true ) );
	die();
}

$userid = $nv_Request->get_int( 'userid', 'get', '', 1 );
$checknum = $nv_Request->get_title( 'checknum', 'get', '', 1 );

if( empty( $userid ) or empty( $checknum ) )
{
	Header( "Location: " . nv_url_rewrite( NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name, true ) );
	die();
}

$del = NV_CURRENTTIME - 86400;
$sql = "DELETE FROM `" . $db_config['dbsystem'] . "`.`" . NV_USERS_GLOBALTABLE . "_reg` WHERE `regdate` < " . $del;
$db->sql_query( $sql );

$sql = "SELECT * FROM `" . $db_config['dbsystem'] . "`.`" . NV_USERS_GLOBALTABLE . "_reg` WHERE `userid`=" . $userid;
$result = $db->sql_query( $sql );
$numrows = $db->sql_numrows( $result );

if( $numrows != 1 )
{
	Header( "Location: " . nv_url_rewrite( NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name, true ) );
	die();
}
$page_title = $mod_title = $lang_module['register'];
$key_words = $module_info['keywords'];

$row = $db->sql_fetchrow( $result );

$check_update_user = false;
$is_change_email = false;

if( $checknum == $row['checknum'] )
{
	if( empty( $row['password'] ) and substr( $row['username'], 0, 20 ) == 'CHANGE_EMAIL_USERID_' )
	{
		$is_change_email = true;

		$userid_change_email = intval( substr( $row['username'], 20 ) );
		$sql = "UPDATE `" . $db_config['dbsystem'] . "`.`" . NV_USERS_GLOBALTABLE . "` SET `email`=" . $db->dbescape_string( $row['email'] ) . " WHERE `userid`=" . $userid_change_email;
		$db->sql_query( $sql );
		if( $db->sql_affectedrows() )
		{
			$db->sql_query( "DELETE FROM `" . $db_config['dbsystem'] . "`.`" . NV_USERS_GLOBALTABLE . "_reg` WHERE `userid`=" . $db->dbescape( $userid ) );
			$check_update_user = true;
		}
	}
	elseif( ! defined( 'NV_IS_USER' ) and $global_config['allowuserreg'] == 2 )
	{
		$sql = "INSERT INTO `" . $db_config['dbsystem'] . "`.`" . NV_USERS_GLOBALTABLE . "` (
					`userid`, `username`, `md5username`, `password`, `email`, `full_name`, `gender`, `photo`, `birthday`, `regdate`,
					`question`, `answer`, `passlostkey`, `view_mail`, `remember`, `in_groups`,
					`active`, `checknum`, `last_login`, `last_ip`, `last_agent`, `last_openid`, `idsite`) VALUES (
					NULL,
					" . $db->dbescape( $row['username'] ) . ",
					" . $db->dbescape( nv_md5safe( $row['username'] ) ) . ",
					" . $db->dbescape( $row['password'] ) . ",
					" . $db->dbescape( $row['email'] ) . ",
					" . $db->dbescape( $row['full_name'] ) . ",
					'', '', 0,
					" . $db->dbescape( $row['regdate'] ) . ",
					" . $db->dbescape( $row['question'] ) . ",
					" . $db->dbescape( $row['answer'] ) . ",
					'', 1, 1, '', 1, '', 0, '', '', '', ".$global_config['idsite'].")";
		$userid = $db->sql_query_insert_id( $sql );
		if( $userid )
		{
			$users_info = unserialize( nv_base64_decode( $row['users_info'] ) );
			$query_field = array();
			$query_field['`userid`'] = $userid;
			$result_field = $db->sql_query( "SELECT * FROM `" . $db_config['dbsystem'] . "`.`" . NV_USERS_GLOBALTABLE . "_field` ORDER BY `fid` ASC" );
			while( $row_f = $db->sql_fetch_assoc( $result_field ) )
			{
				$query_field["`" . $row_f['field'] . "`"] = ( isset( $users_info["`" . $row_f['field'] . "`"] ) ) ? $users_info["`" . $row_f['field'] . "`"] : $db->dbescape( $row_f['default_value'] );
			}

			if( $db->sql_query( "INSERT INTO `" . $db_config['dbsystem'] . "`.`" . NV_USERS_GLOBALTABLE . "_info` (" . implode( ', ', array_keys( $query_field ) ) . ") VALUES (" . implode( ', ', array_values( $query_field ) ) . ")" ) )
			{
				$db->sql_query( "DELETE FROM `" . $db_config['dbsystem'] . "`.`" . NV_USERS_GLOBALTABLE . "_reg` WHERE `userid`=" . $row['userid'] );
				$check_update_user = true;

				nv_insert_logs( NV_LANG_DATA, $module_name, $lang_module['account_active_log'], $row['username'] . " | " . $client_info['ip'], 0 );
			}
			else
			{
				$db->sql_query( "DELETE FROM `" . $db_config['dbsystem'] . "`.`" . NV_USERS_GLOBALTABLE . "` WHERE `userid`=" . $row['userid'] );
			}
		}
	}
}

if( $check_update_user )
{
	if( $is_change_email )
	{
		$info = $lang_module['account_change_mail_ok'] . "<br /><br />\n";
	}
	else
	{
		$info = $lang_module['account_active_ok'] . "<br /><br />\n";
	}
}
else
{
	if( $is_change_email )
	{
		$info = $lang_module['account_active_error'] . "<br /><br />\n";
	}
	else
	{
		$info = $lang_module['account_change_mail_error'] . "<br /><br />\n";
	}
}
$info .= "<img border=\"0\" src=\"" . NV_BASE_SITEURL . "images/load_bar.gif\"><br /><br />\n";
$info .= "[<a href=\"" . NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "\">" . $lang_module['redirect_to_login'] . "</a>]";

$contents = user_info_exit( $info );
$contents .= "<meta http-equiv=\"refresh\" content=\"5;url=" . nv_url_rewrite( NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name, true ) . "\" />";

include ( NV_ROOTDIR . '/includes/header.php' );
echo nv_site_theme( $contents );
include ( NV_ROOTDIR . '/includes/footer.php' );

?>