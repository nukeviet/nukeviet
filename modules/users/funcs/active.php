<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 10/03/2010 10:51
 */

if( ! defined( 'NV_IS_MOD_USER' ) )
{
	die( 'Stop!!!' );
}

if( defined( 'NV_IS_USER_FORUM' ) )
{
	Header( 'Location: ' . nv_url_rewrite( NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name, true ) );
	die();
}

$userid = $nv_Request->get_int( 'userid', 'get', '', 1 );
$checknum = $nv_Request->get_title( 'checknum', 'get', '', 1 );

if( empty( $userid ) or empty( $checknum ) )
{
	Header( 'Location: ' . nv_url_rewrite( NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name, true ) );
	die();
}

$del = NV_CURRENTTIME - 86400;
$sql = 'DELETE FROM ' . NV_USERS_GLOBALTABLE . '_reg WHERE regdate < ' . $del;
$db->query( $sql );

$sql = 'SELECT * FROM ' . NV_USERS_GLOBALTABLE . '_reg WHERE userid=' . $userid;
$row = $db->query( $sql )->fetch();
if( empty( $row ) )
{
	Header( 'Location: ' . nv_url_rewrite( NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name, true ) );
	die();
}
$page_title = $mod_title = $lang_module['register'];
$key_words = $module_info['keywords'];

$check_update_user = false;
$is_change_email = false;

if( $checknum == $row['checknum'] )
{
	if( empty( $row['password'] ) and substr( $row['username'], 0, 20 ) == 'CHANGE_EMAIL_USERID_' )
	{
		$is_change_email = true;

		$userid_change_email = intval( substr( $row['username'], 20 ) );
		$stmt = $db->prepare( 'UPDATE ' . NV_USERS_GLOBALTABLE . ' SET email= :email WHERE userid=' . $userid_change_email );
		$stmt->bindParam( ':email', $row['email'], PDO::PARAM_STR );
		if( $stmt->execute() )
		{
			$stmt = $db->prepare( 'DELETE FROM ' . NV_USERS_GLOBALTABLE . '_reg WHERE userid= :userid' );
			$stmt->bindParam( ':userid', $userid, PDO::PARAM_STR );
			$stmt->execute();
			$check_update_user = true;
		}
	}
	elseif( ! defined( 'NV_IS_USER' ) and $global_config['allowuserreg'] == 2 )
	{
		$sql = "INSERT INTO " . NV_USERS_GLOBALTABLE . " (
					username, md5username, password, email, full_name, gender, photo, birthday, regdate,
					question, answer, passlostkey, view_mail, remember, in_groups,
					active, checknum, last_login, last_ip, last_agent, last_openid, idsite) VALUES (
					:username,
					:md5_username,
					:password,
					:email,
					:full_name,
					'', '', 0,
					:regdate,
					:question,
					:answer,
					'', 1, 1, '', 1, '', 0, '', '', '', ".$global_config['idsite'].")";

		$data_insert = array();
		$data_insert['username'] = $row['username'];
		$data_insert['md5_username'] = nv_md5safe( $row['username'] );
		$data_insert['password'] = $row['password'];
		$data_insert['email'] = $row['email'];
		$data_insert['full_name'] = $row['full_name'];
		$data_insert['regdate'] = $row['regdate'];
		$data_insert['question'] = $row['question'];
		$data_insert['answer'] = $row['answer'];
		$userid = $db->insert_id( $sql, 'userid', $data_insert );
		if( $userid )
		{
			$db->query( 'UPDATE ' . NV_GROUPS_GLOBALTABLE . ' SET numbers = numbers+1 WHERE group_id=4' );

			$users_info = unserialize( nv_base64_decode( $row['users_info'] ) );
			$query_field = array();
			$query_field['userid'] = $userid;
			$result_field = $db->query( 'SELECT * FROM ' . NV_USERS_GLOBALTABLE . '_field ORDER BY fid ASC' );
			while( $row_f = $result_field->fetch() )
			{
				$query_field[$row_f['field']] = ( isset( $users_info[$row_f['field']] ) ) ? $users_info[$row_f['field']] : $db->quote( $row_f['default_value'] );
			}

			if( $db->exec( 'INSERT INTO ' . NV_USERS_GLOBALTABLE . '_info (' . implode( ', ', array_keys( $query_field ) ) . ') VALUES (' . implode( ', ', array_values( $query_field ) ) . ')' ) )
			{
				$db->query( 'UPDATE ' . NV_GROUPS_GLOBALTABLE . ' SET numbers = numbers+1 WHERE group_id=4' );
				$db->query( 'DELETE FROM ' . NV_USERS_GLOBALTABLE . '_reg WHERE userid=' . $row['userid'] );
				$check_update_user = true;

				nv_insert_logs( NV_LANG_DATA, $module_name, $lang_module['account_active_log'], $row['username'] . ' | ' . $client_info['ip'], 0 );
			}
			else
			{
				$db->query( 'DELETE FROM ' . NV_USERS_GLOBALTABLE . ' WHERE userid=' . $row['userid'] );
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
$contents .= "<meta http-equiv=\"refresh\" content=\"5;url=" . nv_url_rewrite( NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name, true ) . "\" />";

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme( $contents );
include NV_ROOTDIR . '/includes/footer.php';