<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 10/03/2010 10:51
 */

if( ! defined( 'NV_IS_MOD_USER' ) ) die( 'Stop!!!' );

if( defined( 'NV_IS_USER' ) or ! $global_config['allowuserlogin'] )
{
	Header( 'Location: ' . nv_url_rewrite( NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name, true ) );
	die();
}

$nv_header = '';
if( $nv_Request->isset_request( 'nv_header', 'post,get' ) )
{
	$nv_header = $nv_Request->get_title( 'nv_header', 'post,get', '' );
	if( $nv_header != md5( $client_info['session_id'] . $global_config['sitekey'] ) ) $nv_header = '';
}

$nv_redirect = '';
if( $nv_Request->isset_request( 'nv_redirect', 'post,get' ) )
{
	$nv_redirect = nv_get_redirect();
}

$gfx_chk = ( in_array( $global_config['gfx_chk'], array(
	2,
	4,
	5,
	7 ) ) ) ? 1 : 0;

/**
 * login_result()
 * 
 * @param mixed $array
 * @return
 */
function signin_result( $array )
{
	global $nv_redirect;

	$array['redirect'] = nv_redirect_decrypt( $nv_redirect );
	$string = json_encode( $array );
	return $string;
}

/**
 * opidr()
 * 
 * @param mixed $openid_info
 * @return void
 */
function opidr( $openid_info )
{
	global $lang_module, $nv_Request, $nv_redirect;

	$nv_Request->unset_request( 'openid_attribs', 'session' );

	$openid_info['redirect'] = nv_redirect_decrypt( $nv_redirect );

	$contents = openid_callback( $openid_info );

	include NV_ROOTDIR . '/includes/header.php';
	echo nv_site_theme( $contents, false );
	include NV_ROOTDIR . '/includes/footer.php';
	exit;
}

/**
 * set_reg_attribs()
 *
 * @param mixed $attribs
 * @return
 */
function set_reg_attribs( $attribs )
{
	global $crypt, $db, $db_config;

	$reg_attribs = array();
	$reg_attribs['server'] = $attribs['server'];
	$reg_attribs['username'] = '';
	$reg_attribs['email'] = $attribs['contact/email'];
	$reg_attribs['first_name'] = '';
	$reg_attribs['last_name'] = '';
	$reg_attribs['gender'] = '';
	$reg_attribs['yim'] = '';
	$reg_attribs['openid'] = $attribs['id'];
	$reg_attribs['opid'] = $crypt->hash( $attribs['id'] );

	$username = explode( '@', $attribs['contact/email'] );
	$username = array_shift( $username );

	if( $attribs['server'] == 'yahoo' )
	{
		$reg_attribs['yim'] = $username;
	}

	$username = str_pad( $username, NV_UNICKMIN, '0', STR_PAD_RIGHT );
	$username = substr( $username, 0, ( NV_UNICKMAX - 2 ) );
	$username2 = $username;
	for( $i = 0; $i < 100; ++$i )
	{
		if( $i > 0 )
		{
			$username2 = $username . str_pad( $i, 2, '0', STR_PAD_LEFT );
		}

		$query = "SELECT userid FROM " . NV_USERS_GLOBALTABLE . " WHERE md5username='" . nv_md5safe( $username2 ) . "'";
		$userid = $db->query( $query )->fetchColumn();
		if( ! $userid )
		{
			$query = "SELECT userid FROM " . NV_USERS_GLOBALTABLE . "_reg WHERE md5username='" . nv_md5safe( $username2 ) . "'";
			$userid = $db->query( $query )->fetchColumn();
			if( ! $userid )
			{
				$reg_attribs['username'] = $username2;
				break;
			}
		}
	}

	if( isset( $attribs['namePerson/first'] ) and ! empty( $attribs['namePerson/first'] ) )
	{
		$reg_attribs['first_name'] = $attribs['namePerson/first'];
	}
	elseif( isset( $attribs['namePerson/friendly'] ) and ! empty( $attribs['namePerson/friendly'] ) )
	{
		$reg_attribs['first_name'] = $attribs['namePerson/friendly'];
	}
	elseif( isset( $attribs['namePerson'] ) and ! empty( $attribs['namePerson'] ) )
	{
		$reg_attribs['first_name'] = $attribs['namePerson'];
	}

	if( isset( $attribs['namePerson/last'] ) and ! empty( $attribs['namePerson/last'] ) )
	{
		$reg_attribs['last_name'] = $attribs['namePerson/last'];
	}

	if( isset( $attribs['person/gender'] ) and ! empty( $attribs['person/gender'] ) )
	{
		$reg_attribs['gender'] = $attribs['person/gender'];
	}

	return $reg_attribs;
}

//Dang nhap bang Open ID
$server = $nv_Request->get_string( 'server', 'get', '' );
if( defined( 'NV_OPENID_ALLOWED' ) and $nv_Request->isset_request( 'server', 'get' ) )
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

	if( empty( $attribs ) or $attribs['server'] != $server )
	{
		opidr( array( 'status' => 'error', 'mess' => $lang_module['logged_in_failed'] ) );
		die();
	}

	if( $attribs['result'] == 'cancel' )
	{
		opidr( array( 'status' => 'error', 'mess' => $lang_module['canceled_authentication'] ) );
		die();
	}

	if( $attribs['result'] == 'notlogin' )
	{
		opidr( array( 'status' => 'error', 'mess' => $lang_module['not_logged_in'] ) );
		die();
	}

	$email = ( isset( $attribs['contact/email'] ) and nv_check_valid_email( $attribs['contact/email'] ) == '' ) ? $attribs['contact/email'] : '';
	if( empty( $email ) )
	{
		opidr( array( 'status' => 'error', 'mess' => $lang_module['logged_in_failed'] ) );
		die();
	}

	$opid = $crypt->hash( $attribs['id'] );
	$current_mode = isset( $attribs['current_mode'] ) ? $attribs['current_mode'] : 1;

	/**
	 * Neu da co trong CSDL
	 */
	$stmt = $db->prepare( 'SELECT a.userid AS uid, a.email AS uemail, b.active AS uactive, b.safemode AS safemode FROM ' . NV_USERS_GLOBALTABLE . '_openid a, ' . NV_USERS_GLOBALTABLE . ' b
		WHERE a.opid= :opid
		AND a.email= :email
		AND a.userid=b.userid' );
	$stmt->bindParam( ':opid', $opid, PDO::PARAM_STR );
	$stmt->bindParam( ':email', $email, PDO::PARAM_STR );
	$stmt->execute();
	list( $user_id, $op_email, $user_active, $safemode ) = $stmt->fetch( 3 );
	if( $user_id )
	{
		if( $safemode == 1 )
		{
			opidr( array( 'status' => 'error', 'mess' => $lang_module['safe_deactivate_openidlogin'] ) );
			die();
		}

		if( ! $user_active )
		{
			opidr( array( 'status' => 'error', 'mess' => $lang_module['login_no_active'] ) );
			die();
		}

		if( defined( 'NV_IS_USER_FORUM' ) and file_exists( NV_ROOTDIR . '/' . DIR_FORUM . '/nukeviet/set_user_login.php' ) )
		{
			require_once NV_ROOTDIR . '/' . DIR_FORUM . '/nukeviet/set_user_login.php';
		}
		else
		{
			$query = 'SELECT * FROM ' . NV_USERS_GLOBALTABLE . ' WHERE userid=' . $user_id;
			$row = $db->query( $query )->fetch();
			validUserLog( $row, 1, $opid, $current_mode );
		}

		opidr( array( 'status' => 'success', 'mess' => $lang_module['login_ok'] ) );
		die();
	}

	/**
	 * Neu chua co trong CSDL nhung email da duoc su dung
	 */
	$stmt = $db->prepare( 'SELECT * FROM ' . NV_USERS_GLOBALTABLE . ' WHERE email= :email' );
	$stmt->bindParam( ':email', $email, PDO::PARAM_STR );
	$stmt->execute();
	$nv_row = $stmt->fetch();

	if( ! empty( $nv_row ) )
	{
		if( $nv_row['safemode'] == 1 )
		{
			opidr( array( 'status' => 'error', 'mess' => $lang_module['safe_deactivate_openidreg'] ) );
			die();
		}

		if( ! $nv_row['active'] )
		{
			opidr( array( 'status' => 'error', 'mess' => $lang_module['login_no_active'] ) );
			die();
		}

		if( ! empty( $nv_row['password'] ) )
		{
			if( $nv_Request->isset_request( 'openid_account_confirm', 'post' ) )
			{
				$password = $nv_Request->get_string( 'password', 'post', '' );
				$nv_seccode = $nv_Request->get_title( 'nv_seccode', 'post', '' );

				$check_seccode = ! $gfx_chk ? true : ( nv_capcha_txt( $nv_seccode ) ? true : false );

				$nv_Request->unset_request( 'openid_attribs', 'session' );
				if( defined( 'NV_IS_USER_FORUM' ) and file_exists( NV_ROOTDIR . '/' . DIR_FORUM . '/nukeviet/login.php' ) )
				{
					$nv_username = $nv_row['username'];
					$nv_password = $password;
					$error = "";
					require_once NV_ROOTDIR . '/' . DIR_FORUM . '/nukeviet/login.php';
					if( ! empty( $error ) )
					{
						opidr( array( 'status' => 'error', 'mess' => $lang_module['openid_confirm_failed'] ) );
						die();
					}
				}
				elseif( ! $crypt->validate_password( $password, $nv_row['password'] ) or ! $check_seccode )
				{
					opidr( array( 'status' => 'error', 'mess' => $lang_module['openid_confirm_failed'] ) );
					die();
				}
			}
			else
			{
				$page_title = $lang_module['openid_login'];
				$key_words = $module_info['keywords'];
				$mod_title = $lang_module['openid_login'];

				$contents = openid_account_confirm( $gfx_chk, $attribs );

				include NV_ROOTDIR . '/includes/header.php';
				echo nv_site_theme( $contents, false );
				include NV_ROOTDIR . '/includes/footer.php';
				exit;
			}
		}

		$stmt = $db->prepare( 'INSERT INTO ' . NV_USERS_GLOBALTABLE . '_openid VALUES (' . ( int )$nv_row['userid'] . ', :server, :opid, :email )' );
		$stmt->bindParam( ':server', $attribs['server'], PDO::PARAM_STR );
		$stmt->bindParam( ':opid', $opid, PDO::PARAM_STR );
		$stmt->bindParam( ':email', $email, PDO::PARAM_STR );
		$stmt->execute();
		validUserLog( $nv_row, 1, $opid, $current_mode );

		opidr( array( 'status' => 'success', 'mess' => $lang_module['login_ok'] ) );
		die();
	}

	/**
	 * Neu chua co hoan toan trong CSDL
	 */

	/**
	 * Neu gan OpenID nay vao 1 tai khoan da co
	 */
	if( $nv_Request->isset_request( 'nv_login', 'post' ) )
	{
		$nv_username = $nv_Request->get_title( 'login', 'post', '', 1 );
		$nv_password = $nv_Request->get_title( 'password', 'post', '' );
		$nv_seccode = $nv_Request->get_title( 'nv_seccode', 'post', '' );

		$check_seccode = ! $gfx_chk ? true : ( nv_capcha_txt( $nv_seccode ) ? true : false );

		if( ! $check_seccode )
		{
			opidr( array( 'status' => 'error', 'mess' => $lang_module['securitycodeincorrect'] ) );
			die();
		}

		if( empty( $nv_username ) )
		{
			opidr( array( 'status' => 'error', 'mess' => $lang_global['username_empty'] ) );
			die();
		}

		if( empty( $nv_password ) )
		{
			opidr( array( 'status' => 'error', 'mess' => $lang_module['password_empty'] ) );
			die();
		}

		if( defined( 'NV_IS_USER_FORUM' ) )
		{
			$error = '';
			require_once NV_ROOTDIR . '/' . DIR_FORUM . '/nukeviet/login.php';
			if( ! empty( $error ) )
			{
				opidr( array( 'status' => 'error', 'mess' => $error ) );
				die();
			}
		}
		else
		{
			$error1 = $lang_global['loginincorrect'];

			if( nv_check_valid_email( $nv_username ) == '' )
			{
				// Email login
				$sql = "SELECT * FROM " . NV_USERS_GLOBALTABLE . " WHERE email =" . $db->quote( $nv_username );
				$row = $db->query( $sql )->fetch();
				if( empty( $row ) )
				{
					opidr( array( 'status' => 'error', 'mess' => $lang_global['loginincorrect'] ) );
					die();
				}

				if( $row['email'] != $nv_username )
				{
					opidr( array( 'status' => 'error', 'mess' => $lang_global['loginincorrect'] ) );
					die();
				}
			}
			else
			{
				// Username login
				$sql = "SELECT * FROM " . NV_USERS_GLOBALTABLE . " WHERE md5username ='" . nv_md5safe( $nv_username ) . "'";
				$row = $db->query( $sql )->fetch();
				if( empty( $row ) )
				{
					opidr( array( 'status' => 'error', 'mess' => $lang_global['loginincorrect'] ) );
					die();
				}

				if( $row['username'] != $nv_username )
				{
					opidr( array( 'status' => 'error', 'mess' => $lang_global['loginincorrect'] ) );
					die();
				}
			}

			if( ! $crypt->validate_password( $nv_password, $row['password'] ) )
			{
				opidr( array( 'status' => 'error', 'mess' => $lang_global['loginincorrect'] ) );
				die();
			}

			if( $row['safemode'] == 1 )
			{
				opidr( array( 'status' => 'error', 'mess' => $lang_module['safe_deactivate_openidreg'] ) );
				die();
			}

			if( ! $row['active'] )
			{
				opidr( array( 'status' => 'error', 'mess' => $lang_global['login_no_active'] ) );
				die();
			}

			validUserLog( $row, 1, '' );
		}

		$stmt = $db->prepare( 'INSERT INTO ' . NV_USERS_GLOBALTABLE . '_openid VALUES (' . ( int )$row['userid'] . ', :server, :opid, :email )' );
		$stmt->bindParam( ':server', $attribs['server'], PDO::PARAM_STR );
		$stmt->bindParam( ':opid', $opid, PDO::PARAM_STR );
		$stmt->bindParam( ':email', $email, PDO::PARAM_STR );
		$stmt->execute();

		opidr( array( 'status' => 'success', 'mess' => $lang_module['login_ok'] ) );
		die();
	}

	/**
	 * Neu dang ky moi va cho dang ky khong can kich hoat hoac kich hoat qua email (allowuserreg = 1, 2)
	 * bo qua phuong an kiem tra email
	 * Vi ban than xac thuc cua OpenID da du dieu kien
	 */
	if( $nv_Request->isset_request( 'nv_reg', 'post' ) and ( $global_config['allowuserreg'] == 1 or $global_config['allowuserreg'] == 2 ) )
	{
		$reg_attribs = set_reg_attribs( $attribs );
		if( empty( $reg_attribs['username'] ) )
		{
			opidr( array( 'status' => 'error', 'mess' => $lang_module['logged_in_failed'] ) );
			die();
		}

		$sql = "INSERT INTO " . NV_USERS_GLOBALTABLE . "
				(username, md5username, password, email, first_name, last_name, gender, photo, birthday,  regdate,
				question, answer, passlostkey, view_mail, remember, in_groups,
				active, checknum, last_login, last_ip, last_agent, last_openid, idsite)  VALUES (
				:username,
				:md5username,
				'',
				:email,
				:first_name,
				:last_name,
				:gender,
				'', 0,
				" . NV_CURRENTTIME . ",
				'', '', '', 0, 0, '', 1, '', 0, '', '', '', " . intval( $global_config['idsite'] ) . "
			)";

		$data_insert = array();
		$data_insert['username'] = $reg_attribs['username'];
		$data_insert['md5username'] = nv_md5safe( $reg_attribs['username'] );
		$data_insert['email'] = $reg_attribs['email'];
		$data_insert['first_name'] = $reg_attribs['first_name'];
		$data_insert['last_name'] = $reg_attribs['last_name'];
		$data_insert['gender'] = ! empty( $reg_attribs['gender'] ) ? ucfirst( substr( $reg_attribs['gender'], 0, 1 ) ) : 'N';
		$userid = $db->insert_id( $sql, 'userid', $data_insert );
		if( ! $userid )
		{
			opidr( array( 'status' => 'error', 'mess' => $lang_module['err_no_save_account'] ) );
			die();
		}

		// Cap nhat so thanh vien
		$db->query( 'UPDATE ' . NV_GROUPS_GLOBALTABLE . ' SET numbers = numbers+1 WHERE group_id=4' );

		$query = 'SELECT * FROM ' . NV_USERS_GLOBALTABLE . ' WHERE userid=' . $userid . ' AND active=1';
		$result = $db->query( $query );
		$row = $result->fetch();
		$result->closeCursor();

		// Luu vao bang thong tin tuy chinh
		$query_field = array();
		$query_field['userid'] = $userid;
		$result_field = $db->query( 'SELECT * FROM ' . NV_USERS_GLOBALTABLE . '_field ORDER BY fid ASC' );
		while( $row_f = $result_field->fetch() )
		{
			$query_field[$row_f['field']] = $db->quote( $row_f['default_value'] );
		}
		$db->query( 'INSERT INTO ' . NV_USERS_GLOBALTABLE . '_info (' . implode( ', ', array_keys( $query_field ) ) . ') VALUES (' . implode( ', ', array_values( $query_field ) ) . ')' );

		// Luu vao bang OpenID
		$stmt = $db->prepare( 'INSERT INTO ' . NV_USERS_GLOBALTABLE . '_openid VALUES (' . intval( $row['userid'] ) . ', :server, :opid , :email)' );
		$stmt->bindParam( ':server', $reg_attribs['server'], PDO::PARAM_STR );
		$stmt->bindParam( ':opid', $reg_attribs['opid'], PDO::PARAM_STR );
		$stmt->bindParam( ':email', $reg_attribs['email'], PDO::PARAM_STR );
		$stmt->execute();

		validUserLog( $row, 1, $reg_attribs['opid'], $current_mode );

		opidr( array( 'status' => 'success', 'mess' => $lang_module['login_ok'] ) );
		die();
	}

	/**
	 * Neu dang ky moi va phai qua kiem duyet cua admin (allowuserreg = 3)
	 */
	if( $nv_Request->isset_request( 'nv_reg', 'post' ) and $global_config['allowuserreg'] == 3 )
	{
		$reg_attribs = set_reg_attribs( $attribs );
		if( empty( $reg_attribs['username'] ) )
		{
			opidr( array( 'status' => 'error', 'mess' => $lang_module['logged_in_failed'] ) );
			die();
		}

		$query_field = array();
		$query_field['userid'] = $userid;
		$result_field = $db->query( 'SELECT * FROM ' . NV_USERS_GLOBALTABLE . '_field ORDER BY fid ASC' );
		while( $row_f = $result_field->fetch() )
		{
			$query_field[$row_f['field']] = $db->quote( $row_f['default_value'] );
		}

		$sql = "INSERT INTO " . NV_USERS_GLOBALTABLE . "_reg (username, md5username, password, email, first_name, last_name, regdate, question, answer, checknum, users_info, openid_info) VALUES (
					:username,
					:md5username,
					'',
					:email,
					:first_name,
					:last_name,
					" . NV_CURRENTTIME . ",
					'',
					'',
					'',
					:users_info,
                    :openid_info
				)";
		$data_insert = array();
		$data_insert['username'] = $reg_attribs['username'];
		$data_insert['md5username'] = nv_md5safe( $reg_attribs['username'] );
		$data_insert['email'] = $reg_attribs['email'];
		$data_insert['first_name'] = $reg_attribs['first_name'];
		$data_insert['last_name'] = $reg_attribs['last_name'];
		$data_insert['users_info'] = nv_base64_encode( serialize( $query_field ) );
		$data_insert['openid_info'] = nv_base64_encode( serialize( $reg_attribs ) );
		$userid = $db->insert_id( $sql, 'userid', $data_insert );

		if( ! $userid )
		{
			opidr( array( 'status' => 'error', 'mess' => $lang_module['err_no_save_account'] ) );
			die();
		}

		opidr( array( 'status' => 'success', 'mess' => $lang_module['account_register_to_admin'] ) );
		die();
	}

	$page_title = $lang_global['openid_login'];
	$key_words = $module_info['keywords'];
	$mod_title = $lang_global['openid_login'];

	$contents .= user_openid_login( $gfx_chk, $attribs );

	include NV_ROOTDIR . '/includes/header.php';
	echo nv_site_theme( $contents, false );
	include NV_ROOTDIR . '/includes/footer.php';

	exit();
}

//Dang nhap kieu thong thuong
if( $nv_Request->isset_request( 'nv_login', 'post' ) )
{
	$nv_username = nv_substr( $nv_Request->get_title( 'nv_login', 'post', '', 1 ), 0, 100 );
	$nv_password = $nv_Request->get_title( 'nv_password', 'post', '' );
	$nv_seccode = $nv_Request->get_title( 'nv_seccode', 'post', '' );

	$check_seccode = ! $gfx_chk ? true : ( nv_capcha_txt( $nv_seccode ) ? true : false );

	if( ! $check_seccode )
	{
		die( signin_result( array(
			'status' => 'error',
			'input' => 'nv_seccode',
			'mess' => $lang_global['securitycodeincorrect'] ) ) );
	}

	if( empty( $nv_username ) )
	{
		die( signin_result( array(
			'status' => 'error',
			'input' => 'nv_login',
			'mess' => $lang_global['username_empty'] ) ) );
	}

	if( empty( $nv_password ) )
	{
		die( signin_result( array(
			'status' => 'error',
			'input' => 'nv_password',
			'mess' => $lang_global['password_empty'] ) ) );
	}

	if( defined( 'NV_IS_USER_FORUM' ) )
	{
		$error = '';
		require_once NV_ROOTDIR . '/' . DIR_FORUM . '/nukeviet/login.php';
		if( ! empty( $error ) )
		{
			die( signin_result( array(
				'status' => 'error',
				'input' => 'nv_login',
				'mess' => $error ) ) );
		}
	}
	else
	{
		$error1 = $lang_global['loginincorrect'];

		if( nv_check_valid_email( $nv_username ) == '' )
		{
			// Email login
			$sql = "SELECT * FROM " . NV_USERS_GLOBALTABLE . " WHERE email =" . $db->quote( $nv_username );
			$login_email = true;
		}
		else
		{
			// Username login
			$sql = "SELECT * FROM " . NV_USERS_GLOBALTABLE . " WHERE md5username ='" . nv_md5safe( $nv_username ) . "'";
			$login_email = false;
		}

		$row = $db->query( $sql )->fetch();

		if( ! empty( $row ) )
		{
			if( ( ( $row['username'] == $nv_username and $login_email == false ) or ( $row['email'] == $nv_username and $login_email == true ) ) and $crypt->validate_password( $nv_password, $row['password'] ) )
			{
				if( ! $row['active'] )
				{
					$error1 = $lang_module['login_no_active'];
				}
				else
				{
					$error1 = '';
					validUserLog( $row, 1, '' );
				}
			}
		}

		if( ! empty( $error1 ) )
		{
			die( signin_result( array(
				'status' => 'error',
				'input' => '',
				'mess' => $error1 ) ) );
		}
	}

	die( signin_result( array(
		'status' => 'ok',
		'input' => '',
		'mess' => $lang_module['login_ok'] ) ) );
}

if( $nv_Request->get_int( 'nv_ajax', 'post', 0 ) == 1 ) die( user_login( true ) );

$page_title = $lang_module['login'];
$key_words = $module_info['keywords'];
$mod_title = $lang_module['login'];

$contents = user_login();

$full = empty( $nv_redirect ) && empty( $nv_header );

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme( $contents, $full );
include NV_ROOTDIR . '/includes/footer.php';
