<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 10/03/2010 10:51
 */

if( ! defined( 'NV_IS_MOD_USER' ) ) die( 'Stop!!!' );

if( ! defined( 'NV_IS_USER' ) or ! $global_config['allowuserlogin'] )
{
	Header( 'Location: ' . nv_url_rewrite( NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name, true ) );
	die();
}

if( defined( 'NV_IS_USER_FORUM' ) )
{
	require_once NV_ROOTDIR . '/' . DIR_FORUM . '/nukeviet/editinfo.php' ;
	exit();
}

/**
 * nv_check_username_change()
 *
 * @param mixed $login
 * @return
 */
function nv_check_username_change( $login )
{
	global $db, $lang_module, $user_info, $db_config;

	$error = nv_check_valid_login( $login, NV_UNICKMAX, NV_UNICKMIN );
	if( $error != '' ) return preg_replace( '/\&(l|r)dquo\;/', '', strip_tags( $error ) );
	if( "'" . $login . "'" != $db->quote( $login ) )
	{
		return sprintf( $lang_module['account_deny_name'], $login );
	}

	$sql = "SELECT content FROM " . NV_USERS_GLOBALTABLE . "_config WHERE config='deny_name'";
	$result = $db->query( $sql );
	$deny_name = $result->fetchColumn();
	$result->closeCursor();

	if( ! empty( $deny_name ) and preg_match( "/" . $deny_name . "/i", $login ) ) return sprintf( $lang_module['account_deny_name'], $login );

	$sql = "SELECT userid FROM " . NV_USERS_GLOBALTABLE . " WHERE userid!=" . $user_info['userid'] . " AND md5username='" . nv_md5safe( $login ) . "'";
	if( $db->query( $sql )->fetchColumn() ) return sprintf( $lang_module['account_registered_name'], $login );

	$sql = "SELECT userid FROM " . NV_USERS_GLOBALTABLE . "_reg WHERE userid!=" . $user_info['userid'] . " AND md5username='" . nv_md5safe( $login ) . "'";
	if( $db->query( $sql )->fetchColumn() ) return sprintf( $lang_module['account_registered_name'], $login );

	return '';
}

/**
 * nv_check_email_change()
 *
 * @param mixed $email
 * @return
 */
function nv_check_email_change( $email )
{
	global $db, $lang_module, $user_info, $db_config;

	$error = nv_check_valid_email( $email );
	if( $error != '' ) return preg_replace( '/\&(l|r)dquo\;/', '', strip_tags( $error ) );

	$sql = "SELECT content FROM " . NV_USERS_GLOBALTABLE . "_config WHERE config='deny_email'";
	$result = $db->query( $sql );
	$deny_email = $result->fetchColumn();
	$result->closeCursor();

	if( ! empty( $deny_email ) and preg_match( "/" . $deny_email . "/i", $email ) ) return sprintf( $lang_module['email_deny_name'], $email );

	list( $left, $right ) = explode( "@", $email );
	$left = preg_replace( '/[\.]+/', '', $left );
	$pattern = str_split( $left );
	$pattern = implode( ".?", $pattern );
	$pattern = "^" . $pattern . "@" . $right . "$";

	$stmt = $db->prepare( 'SELECT userid FROM ' . NV_USERS_GLOBALTABLE . ' WHERE userid!=' . $user_info['userid'] . ' AND email RLIKE :pattern' );
	$stmt->bindParam( ':pattern', $pattern, PDO::PARAM_STR );
	$stmt->execute();
	if( $stmt->fetchColumn() ) return sprintf( $lang_module['email_registered_name'], $email );

	$stmt = $db->prepare( 'SELECT userid FROM ' . NV_USERS_GLOBALTABLE . '_reg WHERE email RLIKE :pattern' );
	$stmt->bindParam( ':pattern', $pattern, PDO::PARAM_STR );
	$stmt->execute();
	if( $stmt->fetchColumn() ) return sprintf( $lang_module['email_registered_name'], $email );

	$stmt = $db->prepare( 'SELECT userid FROM ' . NV_USERS_GLOBALTABLE . '_openid WHERE userid!=' . $user_info['userid'] . ' AND email RLIKE :pattern' );
	$stmt->bindParam( ':pattern', $pattern, PDO::PARAM_STR );
	$stmt->execute();
	if( $stmt->fetchColumn() ) return sprintf( $lang_module['email_registered_name'], $email );

	return '';
}

$array_field_config = array();
$result_field = $db->query( 'SELECT * FROM ' . NV_USERS_GLOBALTABLE . '_field WHERE user_editable = 1 ORDER BY weight ASC' );
while( $row_field = $result_field->fetch() )
{
	$language = unserialize( $row_field['language'] );
	$row_field['title'] = ( isset( $language[NV_LANG_DATA] ) ) ? $language[NV_LANG_DATA][0] : $row['field'];
	$row_field['description'] = ( isset( $language[NV_LANG_DATA] ) ) ? nv_htmlspecialchars( $language[NV_LANG_DATA][1] ) : '';
	if( ! empty( $row_field['field_choices'] ) ) $row_field['field_choices'] = unserialize( $row_field['field_choices'] );
	elseif( ! empty( $row_field['sql_choices'] ) )
	{
		$row_field['sql_choices'] = explode( '|', $row_field['sql_choices'] );
		$query = 'SELECT ' . $row_field['sql_choices'][2] . ', ' . $row_field['sql_choices'][3] . ' FROM ' . $row_field['sql_choices'][1];
		$result = $db->query( $query );
		$weight = 0;
		while( list( $key, $val ) = $result->fetch( 3 ) )
		{
			$row_field['field_choices'][$key] = $val;
		}
	}
	$array_field_config[] = $row_field;
}

if( defined( 'NV_EDITOR' ) )
{
	require_once NV_ROOTDIR . '/' . NV_EDITORSDIR . '/' . NV_EDITOR . '/nv.php' ;
}

$sql = 'SELECT * FROM ' . NV_USERS_GLOBALTABLE . ' WHERE userid=' . $user_info['userid'];
$query = $db->query( $sql );
$row = $query->fetch();

$array_data = array();
$info = '';
$array_data['checkss'] = md5( $client_info['session_id'] . $global_config['sitekey'] );
$checkss = $nv_Request->get_title( 'checkss', 'post', '' );

// Thay doi cau hoi - cau tra loi du phong
if( $nv_Request->isset_request( 'changequestion', 'get' ) )
{
	$oldpassword = $row['password'];
	$oldquestion = $row['question'];
	$oldanswer = $row['answer'];

	$page_title = $mod_title = $lang_module['change_question_pagetitle'];
	$key_words = $module_info['keywords'];

	$array_data['your_question'] = $oldquestion;
	$array_data['answer'] = $oldanswer;
	$array_data['nv_password'] = $nv_Request->get_title( 'nv_password', 'post', '' );
	$array_data['send'] = $nv_Request->get_bool( 'send', 'post', false );

	$step = 1;
	$error = '';

	if( empty( $oldpassword ) )
	{
		$step = 2;
	}
	else
	{
		if( $checkss == $array_data['checkss'] )
		{
			if( $crypt->validate( $array_data['nv_password'], $oldpassword ) or $array_data['nv_password'] == md5( $oldpassword ) )
			{
				$step = 2;

				if( ! isset( $array_data['nv_password']
				{
					31}
				) )
				{
					$array_data['nv_password'] = md5( $crypt->hash( $array_data['nv_password'] ) );
				}
			}
			else
			{
				$step = 1;
				$error = $lang_global['incorrect_password'];
			}
		}
	}

	if( $step == 2 )
	{
		if( $array_data['send'] )
		{
			$array_data['your_question'] = nv_substr( $nv_Request->get_title( 'your_question', 'post', '', 1 ), 0, 255 );
			$array_data['answer'] = nv_substr( $nv_Request->get_title( 'answer', 'post', '', 1 ), 0, 255 );

			if( empty( $array_data['your_question'] ) )
			{
				$error = $lang_module['your_question_empty'];
			}
			elseif( empty( $array_data['answer'] ) )
			{
				$error = $lang_module['answer_empty'];
			}
			else
			{
				$stmt = $db->prepare( 'UPDATE ' . NV_USERS_GLOBALTABLE . '
					SET question= :question, answer= :answer
					WHERE userid=' . $user_info['userid'] );
				$stmt->bindParam( ':question', $array_data['your_question'], PDO::PARAM_STR );
				$stmt->bindParam( ':answer', $array_data['answer'], PDO::PARAM_STR );
				$stmt->execute();

				$contents = user_info_exit( $lang_module['change_question_ok'] );
				$contents .= "<meta http-equiv=\"refresh\" content=\"2;url=" . nv_url_rewrite( NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name, true ) . "\" />";

				include NV_ROOTDIR . '/includes/header.php';
				echo nv_site_theme( $contents );
				include NV_ROOTDIR . '/includes/footer.php';
				exit();
			}
		}
	}

	$array_data['step'] = $step;
	$array_data['info'] = empty( $error ) ? $lang_module['changequestion_step' . $array_data['step']] : "<span style=\"color:#fb490b;\">" . $error . "</span>";

	if( $step == 2 )
	{
		$array_data['questions'] = array();
		$array_data['questions'][] = $lang_module['select_question'];
		$sql = "SELECT title FROM " . NV_USERS_GLOBALTABLE . "_question WHERE lang='" . NV_LANG_DATA . "' ORDER BY weight ASC";
		$result = $db->query( $sql );
		while( $row = $result->fetch() )
		{
			$array_data['questions'][$row['title']] = $row['title'];
		}
	}

	$contents = user_changequestion( $array_data );

	include NV_ROOTDIR . '/includes/header.php';
	echo nv_site_theme( $contents );
	include NV_ROOTDIR . '/includes/footer.php';
	exit();
}
else
{
	$sql = 'SELECT * FROM ' . NV_USERS_GLOBALTABLE . '_info WHERE userid=' . $user_info['userid'];
	$result = $db->query( $sql );
	$custom_fields = $result->fetch();
}

// Thay doi thong tin khac
$page_title = $mod_title = $lang_module['editinfo_pagetitle'];
$key_words = $module_info['keywords'];

$array_data['username'] = $row['username'];
$array_data['email'] = $row['email'];

$array_data['allowmailchange'] = $global_config['allowmailchange'];
$array_data['allowloginchange'] = ( $global_config['allowloginchange'] or ( ! empty( $row['last_openid'] ) and empty( $user_info['last_login'] ) and empty( $user_info['last_agent'] ) and empty( $user_info['last_ip'] ) and empty( $user_info['last_openid'] ) ) ) ? 1 : 0;

if( $checkss == $array_data['checkss'] )
{
	$error = array();

	$array_data['full_name'] = nv_substr( $nv_Request->get_title( 'full_name', 'post', '', 1 ), 0, 255 );
	$array_data['gender'] = nv_substr( $nv_Request->get_title( 'gender', 'post', '', 1 ), 0, 1 );
	$array_data['photo'] = nv_substr( $nv_Request->get_title( 'avatar', 'post', '', 1 ), 0, 255 );
	$array_data['birthday'] = nv_substr( $nv_Request->get_title( 'birthday', 'post', '', 0 ), 0, 10 );
	$array_data['view_mail'] = $nv_Request->get_int( 'view_mail', 'post', 0 );
	$array_data['photo_delete'] = $nv_Request->get_int( 'photo_delete', 'post', 0 );

	if( $array_data['allowloginchange'] )
	{
		$array_data['username'] = nv_substr( $nv_Request->get_title( 'username', 'post', '', 1 ), 0, NV_UNICKMAX );
		if( $array_data['username'] != $row['username'] )
		{
			$checkusername = nv_check_username_change( $array_data['username'] );
			if( $checkusername != '' )
			{
				$array_data['username'] = $row['username'];
				$error[] = $checkusername;
			}
		}
	}

	if( empty( $array_data['full_name'] ) )
	{
		$array_data['full_name'] = $row['full_name'];
		$error[] = $lang_module['name'];
		if( empty( $array_data['full_name'] ) )
		{
			$array_data['full_name'] = $row['username'];
		}
	}

	if( $array_data['gender'] != 'M' and $array_data['gender'] != 'F' ) $array_data['gender'] = '';

	if( preg_match( '/^([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{4})$/', $array_data['birthday'], $m ) )
	{
		$array_data['birthday'] = mktime( 0, 0, 0, $m[2], $m[1], $m[3] );
	}
	else
	{
		$array_data['birthday'] = 0;
	}

	if( ! empty( $array_data['yim'] ) and ! preg_match( '/^([a-zA-Z0-9\_\.]+)$/', $array_data['yim'] ) )
	{
		$array_data['yim'] = $row['yim'];
		$error[] = $lang_module['yahoo'];
	}

	if( $array_data['gender'] == 'N' ) $array_data['gender'] = '';

	if( $array_data['view_mail'] != 1 ) $array_data['view_mail'] = 0;

	if( $array_data['allowmailchange'] )
	{
		$email_new = nv_substr( $nv_Request->get_title( 'email', 'post', '', 1 ), 0, 100 );
		if( $email_new != $row['email'] )
		{
			$checknum = nv_genpass( 10 );
			$checknum = md5( $checknum . $email_new );
			$md5_username = nv_md5safe( $array_data['username'] );

			$stmt = $db->prepare( 'DELETE FROM ' . NV_USERS_GLOBALTABLE . '_reg WHERE md5username= :md5username' );
			$stmt->bindParam( ':md5username', $md5username, PDO::PARAM_STR );
			$stmt->execute();
			$error_email_change = nv_check_email_change( $email_new );
			if( ! empty( $error_email_change ) )
			{
				$error[] = $error_email_change;
			}
			else
			{
				$sql = "INSERT INTO " . NV_USERS_GLOBALTABLE . "_reg (username, md5username, password, email, full_name, regdate, question, answer, checknum, users_info) VALUES (
					'CHANGE_EMAIL_USERID_" . $user_info['userid'] . "',
					:md5_username,
					'',
					:email_new,
					'',
					" . NV_CURRENTTIME . ",
					'',
					'',
					:checknum,
					'' )";

				$data_insert = array();
				$data_insert['md5_username'] = $md5_username;
				$data_insert['email_new'] = $email_new;
				$data_insert['checknum'] = $checknum;

				$userid_check = $db->insert_id( $sql, 'userid', $data_insert );

				if( $userid_check > 0 )
				{
					$subject = $lang_module['email_active'];
					$message = sprintf( $lang_module['email_active_info'], $array_data['full_name'], $array_data['username'], NV_MY_DOMAIN . NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=active&userid=" . $userid_check . "&checknum=" . $checknum, nv_date( "H:i d/m/Y", NV_CURRENTTIME + 86400 ), $global_config['site_name'] );
					$message .= "<br /><br />------------------------------------------------<br /><br />";
					if( NV_LANG_DATA == 'vi' ) $message .= nv_EncString( $message );
					$send = nv_sendmail( $global_config['site_email'], $email_new, $subject, $message );
					if( $send )
					{
						$error[] = $lang_module['email_active_mes'];
					}
					else
					{
						$error[] = $lang_module['email_active_error_mail'];
					}
				}
			}
		}
	}

	// Check photo
	if( $array_data['photo_delete'] or empty( $row['photo'] ) )
	{
		if( ! empty( $array_data['photo'] ) )
		{
			$tmp_photo = NV_ROOTDIR . '/' . NV_TEMP_DIR . '/' . $array_data['photo'];

			if( ! file_exists( $tmp_photo ) )
			{
				$array_data['photo'] = '';
				$error[] = $lang_module['avata_news_not_exists'];
			}
			else
			{
				$new_photo_name = $array_data['photo'];
				$new_photo_path = NV_UPLOADS_REAL_DIR . '/' . $module_name . '/';

				$new_photo_name2 = $new_photo_name;
				$i = 1;
				while( file_exists( $new_photo_path . $new_photo_name2 ) )
				{
					$new_photo_name2 = preg_replace( '/(.*)(\.[a-zA-Z0-9]+)$/', '\1_' . $i . '\2', $new_photo_name );
					++ $i;
				}
				$new_photo = $new_photo_path . $new_photo_name2;

				if( nv_copyfile( $tmp_photo, $new_photo ) )
				{
					$array_data['photo'] = substr( $new_photo, strlen( NV_ROOTDIR . '/' ) );
				}
				else
				{
					$array_data['photo'] = '';
					$error[] = $lang_module['avata_news_copy_error'];
				}

				nv_deletefile( $tmp_photo );
			}
		}

		// Delete old photo
		if( $array_data['photo_delete'] and ! empty( $row['photo'] ) and file_exists( NV_ROOTDIR . '/' . $row['photo'] ) )
		{
			nv_deletefile( NV_ROOTDIR . '/' . $row['photo'] );
		}
	}
	else
	{
		$array_data['photo'] = $row['photo'];

		if( ! empty( $array_data['photo'] ) )
		{
			if( ! file_exists( NV_ROOTDIR . '/' . $array_data['photo'] ) )
			{
				$array_data['photo'] = '';
				$error[] = $lang_module['avata_old_not_exists'];
			}
		}
	}

	$stmt = $db->prepare( 'UPDATE ' . NV_USERS_GLOBALTABLE . ' SET
		username= :username,
		md5username= :md5username,
		email= :email,
		full_name= :full_name,
		gender= :gender,
		photo= :photo,
		birthday= :birthday,
		view_mail= :view_mail
		WHERE userid=' . $user_info['userid'] );

	$md5username = nv_md5safe( $array_data['username'] );
	$photo = nv_unhtmlspecialchars( $array_data['photo'] );

	$stmt->bindParam( ':username', $array_data['username'], PDO::PARAM_STR );
	$stmt->bindParam( ':md5username', $md5username, PDO::PARAM_STR );
	$stmt->bindParam( ':email', $array_data['email'], PDO::PARAM_STR );
	$stmt->bindParam( ':full_name', $array_data['full_name'], PDO::PARAM_STR );
	$stmt->bindParam( ':gender', $array_data['gender'], PDO::PARAM_STR );
	$stmt->bindParam( ':photo', $photo, PDO::PARAM_STR );
	$stmt->bindParam( ':birthday', $array_data['birthday'], PDO::PARAM_STR );
	$stmt->bindParam( ':view_mail', $array_data['view_mail'], PDO::PARAM_STR );
	$stmt->execute();

	$info = $lang_module['editinfo_ok'];
	$sec = 3;
	if( ! empty( $error ) )
	{
		$error = implode( '<br />', $error );
		$info = $info . ', ' . sprintf( $lang_module['editinfo_error'], '<span style="color:#fb490b;">' . $error . '</span>' );
		$sec = 5;
	}
	$query_field = array();
	if( ! empty( $array_field_config ) )
	{
		$userid = $user_info['userid'];
		$error = '';
		$custom_fields = $nv_Request->get_array( 'custom_fields', 'post' );
		require NV_ROOTDIR . '/modules/users/fields.check.php';
		if( empty( $error ) )
		{
			$db->query( 'UPDATE ' . NV_USERS_GLOBALTABLE . '_info SET ' . implode( ', ', $query_field ) . ' WHERE userid=' . $user_info['userid'] );
			$contents = user_info_exit( $info );
			$contents .= '<meta http-equiv="refresh" content="' . $sec . ';url=' . nv_url_rewrite( NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name, true ) . '" />';

			include NV_ROOTDIR . '/includes/header.php';
			echo nv_site_theme( $contents );
			include NV_ROOTDIR . '/includes/footer.php';
		}
		else
		{
			$info = $error;
		}
	}
	else
	{
		$contents = user_info_exit( $info );
		$contents .= '<meta http-equiv="refresh" content="' . $sec . ';url=' . nv_url_rewrite( NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name, true ) . '" />';

		include NV_ROOTDIR . '/includes/header.php';
		echo nv_site_theme( $contents );
		include NV_ROOTDIR . '/includes/footer.php';
	}
}
else
{
	$array_data['full_name'] = $row['full_name'];
	$array_data['gender'] = $row['gender'];
	$array_data['birthday'] = ! empty( $row['birthday'] ) ? date( 'd/m/Y', $row['birthday'] ) : '';
	$array_data['view_mail'] = intval( $row['view_mail'] );
	$array_data['photo'] = $row['photo'];
	$array_data['photo_delete'] = 0;
}

// Checked viewmail
$array_data['view_mail'] = $array_data['view_mail'] ? ' selected="selected"' : '';

// Gender data
$array_data['gender_array'] = array();
$array_data['gender_array']['N'] = array(
	'value' => 'N',
	'title' => 'N/A',
	'selected' => ''
);
$array_data['gender_array']['M'] = array(
	'value' => 'M',
	'title' => $lang_module['male'],
	'selected' => ( $array_data['gender'] == 'M' ? ' selected="selected"' : '' )
);
$array_data['gender_array']['F'] = array(
	'value' => 'F',
	'title' => $lang_module['female'],
	'selected' => ( $array_data['gender'] == 'F' ? ' selected="selected"' : '' )
);

// Check photo path
if( ! empty( $array_data['photo'] ) )
{
	if( file_exists( NV_ROOTDIR . '/' . $array_data['photo'] ) )
	{
		$array_data['photo'] = NV_BASE_SITEURL . $array_data['photo'];
	}
	else
	{
		$array_data['photo'] = '';
	}
}

$contents = user_info( $array_data, $array_field_config, $custom_fields, $info );

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme( $contents );
include NV_ROOTDIR . '/includes/footer.php';