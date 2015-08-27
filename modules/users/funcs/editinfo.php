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
	require_once NV_ROOTDIR . '/' . DIR_FORUM . '/nukeviet/editinfo.php';
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

	if( ! empty( $deny_name ) and preg_match( '/' . $deny_name . '/i', $login ) ) return sprintf( $lang_module['account_deny_name'], $login );

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

	list( $left, $right ) = explode( '@', $email );
	$left = preg_replace( '/[\.]+/', '', $left );
	$pattern = str_split( $left );
	$pattern = implode( '.?', $pattern );
	$pattern = '^' . $pattern . '@' . $right . '$';

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

function get_field_config()
{
	global $db;

	$array_field_config = array();

	$result_field = $db->query( 'SELECT * FROM ' . NV_USERS_GLOBALTABLE . '_field WHERE user_editable = 1 ORDER BY weight ASC' );
	while( $row_field = $result_field->fetch() )
	{
		$language = unserialize( $row_field['language'] );
		$row_field['title'] = ( isset( $language[NV_LANG_DATA] ) ) ? $language[NV_LANG_DATA][0] : $row_field['field'];
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

	return $array_field_config;
}

function opidr( $openid_info )
{
	global $lang_module;

	if( $openid_info == 1 ) $openid_info = array( 'status' => 'error', 'mess' => $lang_module['canceled_authentication'] );
	elseif( $openid_info == 2 ) $openid_info = array( 'status' => 'error', 'mess' => $lang_module['not_logged_in'] );
	elseif( $openid_info == 3 ) $openid_info = array( 'status' => 'error', 'mess' => $lang_module['logged_in_failed'] );
	elseif( $openid_info == 4 ) $openid_info = array( 'status' => 'error', 'mess' => $lang_module['openid_is_exists'] );
	elseif( $openid_info == 5 or $openid_info == 6 ) $openid_info = array( 'status' => 'error', 'mess' => $lang_module['email_is_exists'] );
	else  $openid_info = array( 'status' => 'success', 'mess' => $lang_module['openid_added'] );
	$contents = openid_callback( $openid_info );

	include NV_ROOTDIR . '/includes/header.php';
	echo nv_site_theme( $contents, false );
	include NV_ROOTDIR . '/includes/footer.php';
	exit;
}

function nv_groups_list_pub2()
{
	global $db, $global_config;

	$groups_list = array();

	$resul = $db->query( 'SELECT group_id, title, description, exp_time, publics, numbers FROM ' . NV_GROUPS_GLOBALTABLE . ' WHERE act=1 AND (idsite = ' . $global_config['idsite'] . ' OR (idsite =0 AND siteus = 1)) ORDER BY idsite, weight' );
	while( $row = $resul->fetch() )
	{
		if( $row['publics'] and ( $row['exp_time'] == 0 or $row['exp_time'] > NV_CURRENTTIME ) )
		{
			$groups_list[$row['group_id']] = $row;
		}
	}

	return $groups_list;
}

$array_data = array();
$array_data['checkss'] = md5( $client_info['session_id'] . $global_config['sitekey'] );

$checkss = $nv_Request->get_title( 'checkss', 'post', '' );

$sql = 'SELECT * FROM ' . NV_USERS_GLOBALTABLE . ' WHERE userid=' . $user_info['userid'];
$query = $db->query( $sql );
$row = $query->fetch();

//Tat safemode
if( ( int )$row['safemode'] > 0 )
{
	$type = $nv_Request->get_title( 'type', 'post', '' );

	if( $checkss == $array_data['checkss'] and $type == 'safe_deactivate' )
	{
		$nv_password = $nv_Request->get_title( 'nv_password', 'post', '' );

		if( ! empty( $row['password'] ) and ! $crypt->validate_password( $nv_password, $row['password'] ) )
		{
			die( json_encode( array(
				'status' => 'error',
				'input' => 'nv_password',
				'mess' => $lang_global['incorrect_password'] ) ) );
		}

		if( $nv_Request->isset_request( 'resend', 'post' ) )
		{
			$ss_safesend = $nv_Request->get_int( 'safesend', 'session', 0 );
			if( $ss_safesend < NV_CURRENTTIME )
			{
				$name = $global_config['name_show'] ? array( $row['first_name'], $row['last_name'] ) : array( $row['last_name'], $row['first_name'] );
				$name = array_filter( $name );
				$name = implode( ' ', $name );
				$sitename = '<a href="' . NV_MY_DOMAIN . NV_BASE_SITEURL . '">' . $global_config['site_name'] . '</a>';
				$message = sprintf( $lang_module['safe_send_content'], $name, $sitename, $row['safekey'] );
				@nv_sendmail( $global_config['site_email'], $row['email'], $lang_module['safe_send_subject'], $message );

				$ss_safesend = NV_CURRENTTIME + 600;
				$nv_Request->set_Session( 'safesend', $ss_safesend );
			}

			$ss_safesend = ceil( ( $ss_safesend - NV_CURRENTTIME ) / 60 );

			die( json_encode( array(
				'status' => 'ok',
				'input' => '',
				'mess' => sprintf( $lang_module['safe_send_ok'], $ss_safesend ) ) ) );
		}

		$safe_key = nv_substr( $nv_Request->get_title( 'safe_key', 'post', '', 1 ), 0, 32 );

		if( empty( $row['safekey'] ) or $safe_key != $row['safekey'] )
		{
			die( json_encode( array(
				'status' => 'error',
				'input' => 'safe_key',
				'mess' => $lang_module['verifykey_error'] ) ) );
		}

		$stmt = $db->prepare( "UPDATE " . NV_USERS_GLOBALTABLE . " SET safemode=0, safekey='' WHERE userid=" . $user_info['userid'] );
		$stmt->execute();

		die( json_encode( array(
			'status' => 'ok',
			'input' => nv_url_rewrite( NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=editinfo', true ),
			'mess' => $lang_module['safe_deactivate_ok'] ) ) );
	}

	$array_data['safeshow'] = ( isset( $array_op[1] ) and $array_op[1] == 'safeshow' ) ? true : false;

	$contents = safe_deactivate( $array_data );

	include NV_ROOTDIR . '/includes/header.php';
	echo nv_site_theme( $contents );
	include NV_ROOTDIR . '/includes/footer.php';
	exit;
}

$array_data['allowmailchange'] = $global_config['allowmailchange'];
$array_data['allowloginchange'] = ( $global_config['allowloginchange'] or ( ! empty( $row['last_openid'] ) and empty( $user_info['last_login'] ) and empty( $user_info['last_agent'] ) and empty( $user_info['last_ip'] ) and empty( $user_info['last_openid'] ) ) ) ? 1 : 0;

$array_field_config = get_field_config();
$groups_list = array();

$types = array(
	'basic',
	'avatar',
	'password',
	'question' );
if( $array_data['allowloginchange'] ) $types[] = 'username';
if( $array_data['allowmailchange'] ) $types[] = 'email';
if( defined( 'NV_OPENID_ALLOWED' ) ) $types[] = 'openid';
if( $global_config['allowuserpublic'] )
{
	$groups_list = nv_groups_list_pub2();
	if( ! empty( $groups_list ) ) $types[] = 'group';
}
if( ! empty( $array_field_config ) ) $types[] = 'others';
$types[] = 'safemode';

$array_data['type'] = ( isset( $array_op[1] ) and ! empty( $array_op[1] ) and in_array( $array_op[1], $types ) ) ? $array_op[1] : 'basic';

//OpenID add
if( in_array( 'openid', $types ) and $nv_Request->isset_request( 'server', 'get' ) )
{
	$server = $nv_Request->get_string( 'server', 'get', '' );
	$result = $nv_Request->isset_request( 'result', 'get' );

	if( empty( $server ) or ! in_array( $server, $global_config['openid_servers'] ) or ! $result )
	{
		header( 'Location: ' . NV_BASE_SITEURL );
		die();
	}

	$attribs = $nv_Request->get_string( 'openid_attribs', 'session', '' );
	$attribs = ! empty( $attribs ) ? unserialize( $attribs ) : array();

	$email = ( isset( $attribs['contact/email'] ) and nv_check_valid_email( $attribs['contact/email'] ) == '' ) ? $attribs['contact/email'] : '';
	if( empty( $email ) )
	{
		opidr( 3 );
		die();
	}

	$opid = $crypt->hash( $attribs['id'] );

	$stmt = $db->prepare( 'SELECT COUNT(*) FROM ' . NV_USERS_GLOBALTABLE . '_openid WHERE opid= :opid ' );
	$stmt->bindParam( ':opid', $opid, PDO::PARAM_STR );
	$stmt->execute();
	$count = $stmt->fetchColumn();
	if( $count )
	{
		opidr( 4 );
		die();
	}

	$stmt = $db->prepare( 'SELECT COUNT(*) FROM ' . NV_USERS_GLOBALTABLE . ' WHERE userid!=' . $user_info['userid'] . ' AND email= :email ' );
	$stmt->bindParam( ':email', $email, PDO::PARAM_STR );
	$stmt->execute();
	$count = $stmt->fetchColumn();
	if( $count )
	{
		opidr( 5 );
		die();
	}

	if( $global_config['allowuserreg'] == 2 or $global_config['allowuserreg'] == 3 )
	{
		$query = 'SELECT COUNT(*) FROM ' . NV_USERS_GLOBALTABLE . '_reg WHERE email= :email ';
		if( $global_config['allowuserreg'] == 2 )
		{
			$query .= ' AND regdate>' . ( NV_CURRENTTIME - 86400 );
		}
		$stmt = $db->prepare( $query );
		$stmt->bindParam( ':email', $email, PDO::PARAM_STR );
		$stmt->execute();
		$count = $stmt->fetchColumn();
		if( $count )
		{
			opidr( 6 );
			die();
		}
	}

	$stmt = $db->prepare( 'INSERT INTO ' . NV_USERS_GLOBALTABLE . '_openid VALUES (' . $user_info['userid'] . ', :openid, :opid, :email )' );
	$stmt->bindParam( ':openid', $server, PDO::PARAM_STR );
	$stmt->bindParam( ':opid', $opid, PDO::PARAM_STR );
	$stmt->bindParam( ':email', $email, PDO::PARAM_STR );
	$stmt->execute();

	nv_insert_logs( NV_LANG_DATA, $module_name, $lang_module['openid_add'], $user_info['username'] . ' | ' . $client_info['ip'] . ' | ' . $opid, 0 );

	opidr( 1000 );
	die();
}

//Basic
if( $checkss == $array_data['checkss'] and $array_data['type'] == 'basic' )
{
	$array_data['first_name'] = nv_substr( $nv_Request->get_title( 'first_name', 'post', '', 1 ), 0, 255 );
	$array_data['last_name'] = nv_substr( $nv_Request->get_title( 'last_name', 'post', '', 1 ), 0, 255 );
	$array_data['gender'] = nv_substr( $nv_Request->get_title( 'gender', 'post', '', 1 ), 0, 1 );
	$array_data['birthday'] = nv_substr( $nv_Request->get_title( 'birthday', 'post', '', 0 ), 0, 10 );
	$array_data['view_mail'] = ( int )$nv_Request->get_bool( 'view_mail', 'post', false );

	if( $array_data['gender'] != 'M' and $array_data['gender'] != 'F' ) $array_data['gender'] = '';

	if( preg_match( '/^([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{4})$/', $array_data['birthday'], $m ) )
	{
		$array_data['birthday'] = mktime( 0, 0, 0, $m[2], $m[1], $m[3] );
	}
	else
	{
		$array_data['birthday'] = 0;
	}

	if( empty( $array_data['first_name'] ) )
	{
		$array_data['first_name'] = ! empty( $row['first_name'] ) ? $row['first_name'] : $row['username'];
	}

	$stmt = $db->prepare( 'UPDATE ' . NV_USERS_GLOBALTABLE . ' SET first_name= :first_name, last_name= :last_name, gender= :gender, birthday=' . $array_data['birthday'] . ', view_mail=' . $array_data['view_mail'] . ' WHERE userid=' . $user_info['userid'] );

	$stmt->bindParam( ':first_name', $array_data['first_name'], PDO::PARAM_STR );
	$stmt->bindParam( ':last_name', $array_data['last_name'], PDO::PARAM_STR );
	$stmt->bindParam( ':gender', $array_data['gender'], PDO::PARAM_STR );
	$stmt->execute();

	die( json_encode( array(
		'status' => 'ok',
		'input' => nv_url_rewrite( NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=editinfo/basic', true ),
		'mess' => $lang_module['editinfo_ok'] ) ) );
}
//Avatar
elseif( $checkss == $array_data['checkss'] and $array_data['type'] == 'avatar' )
{

}
//Username
elseif( $checkss == $array_data['checkss'] and $array_data['type'] == 'username' )
{
	$nv_username = nv_substr( $nv_Request->get_title( 'username', 'post', '', 1 ), 0, NV_UNICKMAX );
	$nv_password = $nv_Request->get_title( 'password', 'post', '' );

	if( empty( $nv_password ) or ! $crypt->validate_password( $nv_password, $row['password'] ) )
	{
		die( json_encode( array(
			'status' => 'error',
			'input' => 'password',
			'mess' => $lang_global['incorrect_password'] ) ) );
	}

	if( $nv_username != $row['username'] )
	{
		$checkusername = nv_check_username_change( $nv_username );
		if( ! empty( $checkusername ) )
		{
			die( json_encode( array(
				'status' => 'error',
				'input' => 'username',
				'mess' => $checkusername ) ) );
		}
	}

	$md5_username = nv_md5safe( $nv_username );

	$stmt = $db->prepare( 'UPDATE ' . NV_USERS_GLOBALTABLE . ' SET username= :username, md5username= :md5username WHERE userid=' . $user_info['userid'] );
	$stmt->bindParam( ':username', $nv_username, PDO::PARAM_STR );
	$stmt->bindParam( ':md5username', $md5_username, PDO::PARAM_STR );
	$stmt->execute();

	$name = $global_config['name_show'] ? array( $row['first_name'], $row['last_name'] ) : array( $row['last_name'], $row['first_name'] );
	$name = array_filter( $name );
	$name = implode( ' ', $name );
	$sitename = '<a href="' . NV_MY_DOMAIN . NV_BASE_SITEURL . '">' . $global_config['site_name'] . '</a>';
	$message = sprintf( $lang_module['edit_mail_content'], $name, $sitename, $lang_global['username'], $nv_username );
	@nv_sendmail( $global_config['site_email'], $row['email'], $lang_module['edit_mail_subject'], $message );

	die( json_encode( array(
		'status' => 'ok',
		'input' => nv_url_rewrite( NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=editinfo/username', true ),
		'mess' => $lang_module['editinfo_ok'] ) ) );
}
//Email
elseif( $checkss == $array_data['checkss'] and $array_data['type'] == 'email' )
{
	$nv_email = nv_substr( $nv_Request->get_title( 'email', 'post', '', 1 ), 0, 100 );
	$nv_password = $nv_Request->get_title( 'password', 'post', '' );
	$nv_verikeysend = ( int )$nv_Request->get_bool( 'vsend', 'post', false );
	if( empty( $nv_password ) or ! $nv_Request->get_bool( 'verikey', 'session' ) ) $nv_verikeysend = 1;

	if( $nv_email == $row['email'] )
	{
		die( json_encode( array(
			'status' => 'error',
			'input' => 'email',
			'mess' => $lang_module['email_not_change'] ) ) );
	}

	if( ! empty( $row['password'] ) and ! $crypt->validate_password( $nv_password, $row['password'] ) )
	{
		die( json_encode( array(
			'status' => 'error',
			'input' => 'password',
			'mess' => $lang_global['incorrect_password'] ) ) );
	}

	$checkemail = nv_check_email_change( $nv_email );
	if( ! empty( $checkemail ) )
	{
		die( json_encode( array(
			'status' => 'error',
			'input' => 'email',
			'mess' => $checkemail ) ) );
	}

	if( $nv_verikeysend )
	{
		$p = 0;
		$verikey = '';

		if( $nv_Request->get_bool( 'verikey', 'session' ) )
		{
			$ss_verifykey = $nv_Request->get_title( 'verikey', 'session', '' );
			$ss_verifykey = explode( '|', $ss_verifykey );
			if( ( int )$ss_verifykey[0] > NV_CURRENTTIME )
			{
				die( json_encode( array(
					'status' => 'error',
					'input' => 'verifykey',
					'mess' => sprintf( $lang_module['verifykey_issend'], ceil( ( ( int )$ss_verifykey[0] - NV_CURRENTTIME ) / 60 ) ) ) ) );
			}
			else
			{
				$p = ( int )$ss_verifykey[1];
				$verikey = $ss_verifykey[2];
				$nv_Request->set_Session( 'verikey', ( NV_CURRENTTIME + 300 ) . '|' . $p . '|' . $verikey );
			}
		}

		if( empty( $p ) or empty( $verikey ) )
		{
			$rand = rand( NV_UPASSMIN, NV_UPASSMAX );
			if( $rand < 6 ) $rand = 6;
			$p = NV_CURRENTTIME + 86400;
			$verikey = md5( $row['userid'] . $nv_email . nv_genpass( $rand ) . $global_config['sitekey'] );
			$nv_Request->set_Session( 'verikey', ( NV_CURRENTTIME + 300 ) . '|' . $p . '|' . $verikey );
		}

		$p = nv_date( 'H:i d/m/Y', $p );
		$name = $global_config['name_show'] ? array( $row['first_name'], $row['last_name'] ) : array( $row['last_name'], $row['first_name'] );
		$name = array_filter( $name );
		$name = implode( ' ', $name );
		$sitename = '<a href="' . NV_MY_DOMAIN . NV_BASE_SITEURL . '">' . $global_config['site_name'] . '</a>';
		$message = sprintf( $lang_module['email_active_info'], $name, $sitename, $verikey, $p );
		@nv_sendmail( $global_config['site_email'], $nv_email, $lang_module['email_active'], $message );

		die( json_encode( array(
			'status' => 'error',
			'input' => 'verifykey',
			'mess' => $lang_module['email_active_mes'] ) ) );
	}
	else
	{
		$nv_verifykey = $nv_Request->get_title( 'verifykey', 'post', '' );

		if( empty( $nv_verifykey ) )
		{
			die( json_encode( array(
				'status' => 'error',
				'input' => 'verifykey',
				'mess' => $lang_module['verifykey_empty'] ) ) );
		}

		$ss_verifykey = $nv_Request->get_title( 'verikey', 'session', '' );
		$ss_verifykey = explode( '|', $ss_verifykey );

		if( ( int )$ss_verifykey[1] < NV_CURRENTTIME )
		{
			$nv_Request->unset_request( 'verifykey', 'session' );

			die( json_encode( array(
				'status' => 'error',
				'input' => 'verifykey',
				'mess' => $lang_module['verifykey_exp'] ) ) );
		}

		if( $nv_verifykey != $ss_verifykey[2] )
		{
			die( json_encode( array(
				'status' => 'error',
				'input' => 'verifykey',
				'mess' => $lang_module['verifykey_error'] ) ) );
		}

		$nv_Request->unset_request( 'verifykey', 'session' );

		$stmt = $db->prepare( 'UPDATE ' . NV_USERS_GLOBALTABLE . ' SET email= :email WHERE userid=' . $user_info['userid'] );
		$stmt->bindParam( ':email', $nv_email, PDO::PARAM_STR );
		$stmt->execute();

		$name = $global_config['name_show'] ? array( $row['first_name'], $row['last_name'] ) : array( $row['last_name'], $row['first_name'] );
		$name = array_filter( $name );
		$name = implode( ' ', $name );
		$sitename = '<a href="' . NV_MY_DOMAIN . NV_BASE_SITEURL . '">' . $global_config['site_name'] . '</a>';
		$message = sprintf( $lang_module['edit_mail_content'], $name, $sitename, $lang_global['email'], $nv_email );
		@nv_sendmail( $global_config['site_email'], $nv_email, $lang_module['edit_mail_subject'], $message );

		die( json_encode( array(
			'status' => 'ok',
			'input' => nv_url_rewrite( NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=editinfo/email', true ),
			'mess' => $lang_module['editinfo_ok'] ) ) );
	}
}
//Password
elseif( $checkss == $array_data['checkss'] and $array_data['type'] == 'password' )
{
	$nv_password = $nv_Request->get_title( 'nv_password', 'post', '' );
	$new_password = $nv_Request->get_title( 'new_password', 'post', '' );
	$re_password = $nv_Request->get_title( 're_password', 'post', '' );

	if( ! empty( $row['password'] ) and ! $crypt->validate_password( $nv_password, $row['password'] ) )
	{
		die( json_encode( array(
			'status' => 'error',
			'input' => 'password',
			'mess' => $lang_global['incorrect_password'] ) ) );
	}

	if( ( $check_new_password = nv_check_valid_pass( $new_password, NV_UPASSMAX, NV_UPASSMIN ) ) != '' )
	{
		die( json_encode( array(
			'status' => 'error',
			'input' => 'new_password',
			'mess' => $check_new_password ) ) );
	}

	if( $new_password != $re_password )
	{
		die( json_encode( array(
			'status' => 'error',
			'input' => 're_password',
			'mess' => $lang_global['passwordsincorrect'] ) ) );
	}

	$re_password = $crypt->hash_password( $new_password, $global_config['hashprefix'] );

	$stmt = $db->prepare( 'UPDATE ' . NV_USERS_GLOBALTABLE . ' SET password= :password WHERE userid=' . $user_info['userid'] );
	$stmt->bindParam( ':password', $re_password, PDO::PARAM_STR );
	$stmt->execute();

	$name = $global_config['name_show'] ? array( $row['first_name'], $row['last_name'] ) : array( $row['last_name'], $row['first_name'] );
	$name = array_filter( $name );
	$name = implode( ' ', $name );
	$sitename = '<a href="' . NV_MY_DOMAIN . NV_BASE_SITEURL . '">' . $global_config['site_name'] . '</a>';
	$message = sprintf( $lang_module['edit_mail_content'], $name, $sitename, $lang_global['password'], $new_password );
	@nv_sendmail( $global_config['site_email'], $row['email'], $lang_module['edit_mail_subject'], $message );

	die( json_encode( array(
		'status' => 'ok',
		'input' => nv_url_rewrite( NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=editinfo/password', true ),
		'mess' => $lang_module['editinfo_ok'] ) ) );
}
//Question
elseif( $checkss == $array_data['checkss'] and $array_data['type'] == 'question' )
{
	$your_question = nv_substr( $nv_Request->get_title( 'your_question', 'post', '', 1 ), 0, 255 );
	$answer = nv_substr( $nv_Request->get_title( 'answer', 'post', '', 1 ), 0, 255 );
	$nv_password = $nv_Request->get_title( 'nv_password', 'post', '' );

	if( empty( $your_question ) )
	{
		die( json_encode( array(
			'status' => 'error',
			'input' => 'your_question',
			'mess' => $lang_module['your_question_empty'] ) ) );
	}

	if( empty( $answer ) )
	{
		die( json_encode( array(
			'status' => 'error',
			'input' => 'answer',
			'mess' => $lang_module['answer_empty'] ) ) );
	}

	if( empty( $nv_password ) or ! $crypt->validate_password( $nv_password, $row['password'] ) )
	{
		die( json_encode( array(
			'status' => 'error',
			'input' => 'nv_password',
			'mess' => $lang_global['incorrect_password'] ) ) );
	}

	$stmt = $db->prepare( 'UPDATE ' . NV_USERS_GLOBALTABLE . ' SET question= :question, answer= :answer WHERE userid=' . $user_info['userid'] );
	$stmt->bindParam( ':question', $your_question, PDO::PARAM_STR );
	$stmt->bindParam( ':answer', $answer, PDO::PARAM_STR );
	$stmt->execute();

	die( json_encode( array(
		'status' => 'ok',
		'input' => 'ok',
		'mess' => $lang_module['change_question_ok'] ) ) );
}
//OpeniD Del
elseif( $checkss == $array_data['checkss'] and $array_data['type'] == 'openid' )
{
	$openid_del = $nv_Request->get_typed_array( 'openid_del', 'post', 'string', '' );
	$openid_del = array_filter( $openid_del );
	if( empty( $openid_del ) )
	{
		die( json_encode( array(
			'status' => 'error',
			'input' => '',
			'mess' => $lang_module['openid_choose'] ) ) );
	}

	foreach( $openid_del as $opid )
	{
		if( ! empty( $opid ) and ( empty( $user_info['current_openid'] ) or ( ! empty( $user_info['current_openid'] ) and $user_info['current_openid'] != $opid ) ) )
		{
			$stmt = $db->prepare( 'DELETE FROM ' . NV_USERS_GLOBALTABLE . '_openid WHERE opid= :opid' );
			$stmt->bindParam( ':opid', $opid, PDO::PARAM_STR );
			$stmt->execute();
		}
	}

	die( json_encode( array(
		'status' => 'ok',
		'input' => nv_url_rewrite( NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=editinfo/openid', true ),
		'mess' => $lang_module['openid_deleted'] ) ) );
}
//Groups
elseif( $checkss == $array_data['checkss'] and $array_data['type'] == 'group' )
{
	$array_old_groups = array();
	$result_gru = $db->query( 'SELECT group_id FROM ' . NV_GROUPS_GLOBALTABLE . '_users WHERE userid=' . $user_info['userid'] );
	while( $row_gru = $result_gru->fetch() )
	{
		$array_old_groups[] = $row_gru['group_id'];
	}

	$in_groups = $nv_Request->get_typed_array( 'in_groups', 'post', 'int' );
	$in_groups = array_intersect( $in_groups, array_keys( $groups_list ) );
	$in_groups_hiden = array_diff( $array_old_groups, array_keys( $groups_list ) );
	$in_groups = array_unique( array_merge( $in_groups, $in_groups_hiden ) );

	$in_groups_del = array_diff( $array_old_groups, $in_groups );
	if( ! empty( $in_groups_del ) )
	{
		foreach( $in_groups_del as $gid )
		{
			nv_groups_del_user( $gid, $user_info['userid'] );
		}
	}

	$in_groups_add = array_diff( $in_groups, $array_old_groups );
	if( ! empty( $in_groups_add ) )
	{
		foreach( $in_groups_add as $gid )
		{
			nv_groups_add_user( $gid, $user_info['userid'] );
		}
	}

	$db->query( "UPDATE " . NV_USERS_GLOBALTABLE . " SET in_groups='" . implode( ',', $in_groups ) . "' WHERE userid=" . $user_info['userid'] );
	die( json_encode( array(
		'status' => 'ok',
		'input' => nv_url_rewrite( NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=editinfo/group', true ),
		'mess' => $lang_module['in_group_ok'] ) ) );
}
//Others
elseif( $checkss == $array_data['checkss'] and $array_data['type'] == 'others' )
{
	$query_field = array();
	$userid = $user_info['userid'];
	$custom_fields = $nv_Request->get_array( 'custom_fields', 'post' );
	require NV_ROOTDIR . '/modules/users/fields.check.php';

	$db->query( 'UPDATE ' . NV_USERS_GLOBALTABLE . '_info SET ' . implode( ', ', $query_field ) . ' WHERE userid=' . $user_info['userid'] );

	die( json_encode( array(
		'status' => 'ok',
		'input' => nv_url_rewrite( NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=editinfo/others', true ),
		'mess' => $lang_module['editinfo_ok'] ) ) );
}
//Bat safemode
elseif( $checkss == $array_data['checkss'] and $array_data['type'] == 'safemode' )
{
	$nv_password = $nv_Request->get_title( 'nv_password', 'post', '' );
	if( empty( $nv_password ) or ! $crypt->validate_password( $nv_password, $row['password'] ) )
	{
		die( json_encode( array(
			'status' => 'error',
			'input' => 'nv_password',
			'mess' => $lang_global['incorrect_password'] ) ) );
	}

	if( $nv_Request->isset_request( 'resend', 'post' ) )
	{
		if( empty( $row['safekey'] ) )
		{
			$rand = rand( NV_UPASSMIN, NV_UPASSMAX );
			if( $rand < 6 ) $rand = 6;
			$row['safekey'] = md5( nv_genpass( $rand ) );

			$stmt = $db->prepare( 'UPDATE ' . NV_USERS_GLOBALTABLE . ' SET safekey= :safekey WHERE userid=' . $user_info['userid'] );
			$stmt->bindParam( ':safekey', $row['safekey'], PDO::PARAM_STR );
			$stmt->execute();
			$nv_Request->set_Session( 'safesend', 0 );
		}

		$ss_safesend = $nv_Request->get_int( 'safesend', 'session', 0 );
		if( $ss_safesend < NV_CURRENTTIME )
		{
			$name = $global_config['name_show'] ? array( $row['first_name'], $row['last_name'] ) : array( $row['last_name'], $row['first_name'] );
			$name = array_filter( $name );
			$name = implode( ' ', $name );
			$sitename = '<a href="' . NV_MY_DOMAIN . NV_BASE_SITEURL . '">' . $global_config['site_name'] . '</a>';
			$message = sprintf( $lang_module['safe_send_content'], $name, $sitename, $row['safekey'] );
			@nv_sendmail( $global_config['site_email'], $row['email'], $lang_module['safe_send_subject'], $message );

			$ss_safesend = NV_CURRENTTIME + 600;
			$nv_Request->set_Session( 'safesend', $ss_safesend );
		}

		$ss_safesend = ceil( ( $ss_safesend - NV_CURRENTTIME ) / 60 );

		die( json_encode( array(
			'status' => 'ok',
			'input' => '',
			'mess' => sprintf( $lang_module['safe_send_ok'], $ss_safesend ) ) ) );
	}

	$safe_key = nv_substr( $nv_Request->get_title( 'safe_key', 'post', '', 1 ), 0, 32 );

	if( empty( $row['safekey'] ) or $safe_key != $row['safekey'] )
	{
		die( json_encode( array(
			'status' => 'error',
			'input' => 'safe_key',
			'mess' => $lang_module['verifykey_error'] ) ) );
	}

	$stmt = $db->prepare( 'UPDATE ' . NV_USERS_GLOBALTABLE . ' SET safemode=1, safekey= :safekey WHERE userid=' . $user_info['userid'] );
	$stmt->bindParam( ':safekey', $row['safekey'], PDO::PARAM_STR );
	$stmt->execute();

	die( json_encode( array(
		'status' => 'ok',
		'input' => nv_url_rewrite( NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=editinfo', true ),
		'mess' => $lang_module['safe_activate_ok'] ) ) );
}

$page_title = $mod_title = $lang_module['editinfo_pagetitle'];
$key_words = $module_info['keywords'];

if( ! defined( 'NV_EDITOR' ) )
{
	define( 'NV_EDITOR', 'ckeditor' );
}
require_once NV_ROOTDIR . '/' . NV_EDITORSDIR . '/' . NV_EDITOR . '/nv.php';

$sql = 'SELECT * FROM ' . NV_USERS_GLOBALTABLE . '_info WHERE userid=' . $user_info['userid'];
$result = $db->query( $sql );
$custom_fields = $result->fetch();

$array_data['username'] = $row['username'];
$array_data['email'] = $row['email'];
$array_data['first_name'] = $row['first_name'];
$array_data['last_name'] = $row['last_name'];
$array_data['gender'] = $row['gender'];
$array_data['birthday'] = ! empty( $row['birthday'] ) ? date( 'd/m/Y', $row['birthday'] ) : '';
$array_data['view_mail'] = $row['view_mail'] ? ' selected="selected"' : '';
$array_data['photo'] = ( ! empty( $row['photo'] ) and file_exists( NV_ROOTDIR . '/' . $row['photo'] ) ) ? NV_BASE_SITEURL . $row['photo'] : "";

if( empty( $array_data['photo'] ) )
{
	$array_data['photo'] = NV_BASE_SITEURL . 'themes/' . $module_info['template'] . '/images/' . $module_file . '/no_avatar.png';
	$array_data['photoWidth'] = 80;
	$array_data['photoHeight'] = 80;
	$array_data['imgDisabled'] = " disabled=\"disabled\"";
}
else
{
	$size = @getimagesize( NV_ROOTDIR . '/' . $row['photo'] );
	$array_data['photoWidth'] = $size[0];
	$array_data['photoHeight'] = $size[1];
	$array_data['imgDisabled'] = '';
}

// Gender data
$array_data['gender_array'] = array(
	'N' => array(
		'value' => 'N',
		'title' => 'N/A',
		'selected' => '' ),
	'M' => array(
		'value' => 'M',
		'title' => $lang_module['male'],
		'selected' => ( $array_data['gender'] == 'M' ? ' selected="selected"' : '' ) ),
	'F' => array(
		'value' => 'F',
		'title' => $lang_module['female'],
		'selected' => ( $array_data['gender'] == 'F' ? ' selected="selected"' : '' ) )
		);

$data_questions = array();
$sql = "SELECT qid, title FROM " . NV_USERS_GLOBALTABLE . "_question WHERE lang='" . NV_LANG_DATA . "' ORDER BY weight ASC";
$result = $db->query( $sql );
while( $row2 = $result->fetch() )
{
	$data_questions[$row2['qid']] = array( 'qid' => $row2['qid'], 'title' => $row2['title'] );
}

$data_openid = array();
if( in_array( 'openid', $types ) )
{
	$sql = 'SELECT * FROM ' . NV_USERS_GLOBALTABLE . '_openid WHERE userid=' . $user_info['userid'];
	$query = $db->query( $sql );
	while( $row3 = $query->fetch() )
	{
		$data_openid[] = array(
			'opid' => $row3['opid'],
			'openid' => $row3['openid'],
			'email' => $row3['email'],
			'disabled' => ( ( ! empty( $user_info['current_openid'] ) and $user_info['current_openid'] == $row3['opid'] ) ? true : false ) );
	}
}

$groups = array();
if( in_array( 'group', $types ) )
{
	$my_groups = array();
	$result_gru = $db->query( 'SELECT group_id FROM ' . NV_GROUPS_GLOBALTABLE . '_users WHERE userid=' . $user_info['userid'] );
	while( $row_gru = $result_gru->fetch() )
	{
		$my_groups[] = $row_gru['group_id'];
	}

	foreach( $groups_list as $gid => $gvalues )
	{
		$groups[$gid] = $gvalues;
		$groups[$gid]['checked'] = ( ! empty( $my_groups ) and in_array( $gid, $my_groups ) ) ? " checked=\"checked\"" : "";
	}
}

$pass_empty = empty( $row['password'] ) ? true : false;

$contents = user_info( $array_data, $array_field_config, $custom_fields, $types, $data_questions, $data_openid, $groups, $pass_empty );

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme( $contents );
include NV_ROOTDIR . '/includes/footer.php';