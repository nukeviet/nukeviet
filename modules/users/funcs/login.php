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

$gfx_chk = ( in_array( $global_config['gfx_chk'], array( 2, 4, 5, 7 ) ) ) ? 1 : 0;

/**
 * openidLogin_Res0()
 * Function hien thi cac thong bao loi cua OpenID
 *
 * @param mixed $info
 * @return
 */
function openidLogin_Res0( $info )
{
	global $page_title, $key_words, $mod_title, $module_name, $module_info, $lang_module, $nv_redirect;

	$page_title = $lang_module['openid_login'];
	$key_words = $module_info['keywords'];
	$mod_title = $lang_module['openid_login'];
	$contents = user_info_exit( $info );
	$nv_redirect = ! empty( $nv_redirect ) ? nv_base64_decode( $nv_redirect ) : NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name;
	$contents .= '<meta http-equiv="refresh" content="3;url=' . nv_url_rewrite( $nv_redirect ) . '" />';
	include NV_ROOTDIR . '/includes/header.php';
	echo nv_site_theme( $contents );
	include NV_ROOTDIR . '/includes/footer.php';
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
	$reg_attribs['full_name'] = '';
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

	if( isset( $attribs['namePerson'] ) and ! empty( $attribs['namePerson'] ) )
	{
		$reg_attribs['full_name'] = $attribs['namePerson'];
	}
	elseif( isset( $attribs['namePerson/friendly'] ) and ! empty( $attribs['namePerson/friendly'] ) )
	{
		$reg_attribs['full_name'] = $attribs['namePerson/friendly'];
	}
	elseif( isset( $attribs['namePerson/first'] ) and ! empty( $attribs['namePerson/first'] ) )
	{
		$reg_attribs['full_name'] = $attribs['namePerson/first'];
	}

	if( isset( $attribs['namePerson/last'] ) and ! empty( $attribs['namePerson/last'] ) )
	{
		if( ! empty( $reg_attribs['full_name'] ) )
		{
			$reg_attribs['full_name'] = $attribs['namePerson/last'] . ' ' . $reg_attribs['full_name'];
		}
		else
		{
			$reg_attribs['full_name'] = $attribs['namePerson/last'];
		}
	}

	if( isset( $attribs['person/gender'] ) and ! empty( $attribs['person/gender'] ) )
	{
		$reg_attribs['gender'] = $attribs['person/gender'];
	}

	return $reg_attribs;
}

/**
 * openidLogin_Res1()
 * Function thuc hien khi OpenID duoc nhan dien
 *
 * @param mixed $attribs
 * @return
 */
function openidLogin_Res1( $attribs )
{
	global $page_title, $key_words, $mod_title, $db, $crypt, $nv_Request, $lang_module, $lang_global, $module_name, $module_info, $global_config, $gfx_chk, $nv_redirect, $op, $db_config;
	$email = ( isset( $attribs['contact/email'] ) and nv_check_valid_email( $attribs['contact/email'] ) == '' ) ? $attribs['contact/email'] : '';
	if( empty( $email ) )
	{
		$nv_Request->unset_request( 'openid_attribs', 'session' );
		openidLogin_Res0( $lang_module['logged_in_failed'] );
		die();
	}
	$opid = $crypt->hash( $attribs['id'] );

	$stmt = $db->prepare( 'SELECT a.userid AS uid, a.email AS uemail, b.active AS uactive FROM ' . NV_USERS_GLOBALTABLE . '_openid a, ' . NV_USERS_GLOBALTABLE . ' b
		WHERE a.opid= :opid
		AND a.email= :email
		AND a.userid=b.userid'
	);
	$stmt->bindParam( ':opid', $opid, PDO::PARAM_STR );
	$stmt->bindParam( ':email', $email, PDO::PARAM_STR );
	$stmt->execute();
	list( $user_id, $op_email, $user_active ) = $stmt->fetch( 3 );
	if( $user_id )
	{
		$nv_Request->unset_request( 'openid_attribs', 'session' );

		if( $op_email != $email )
		{
			openidLogin_Res0( $lang_module['not_logged_in'] );
			die();
		}

		if( ! $user_active )
		{
			openidLogin_Res0( $lang_module['login_no_active'] );
			die();
		}

		if( defined( 'NV_IS_USER_FORUM' ) and file_exists( NV_ROOTDIR . '/' . DIR_FORUM . '/nukeviet/set_user_login.php' ) )
		{
			require_once NV_ROOTDIR . '/' . DIR_FORUM . '/nukeviet/set_user_login.php' ;

			if( defined( 'NV_IS_USER_LOGIN_FORUM_OK' ) )
			{
				$nv_redirect = ! empty( $nv_redirect ) ? nv_base64_decode( $nv_redirect ) : NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name;
			}
			else
			{
				$nv_redirect = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name;
			}
		}
		else
		{
			$query = 'SELECT * FROM ' . NV_USERS_GLOBALTABLE . ' WHERE userid=' . $user_id;
			$row = $db->query( $query )->fetch();
			if( ! empty( $row ) )
			{
				validUserLog( $row, 1, $opid );
				$nv_redirect = ! empty( $nv_redirect ) ? nv_base64_decode( $nv_redirect ) : NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name;
			}
			else
			{
				$nv_redirect = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name;
			}
		}
		Header( 'Location: ' . nv_url_rewrite( $nv_redirect, true ) );
		die();
	}

	$stmt = $db->prepare( 'SELECT * FROM ' . NV_USERS_GLOBALTABLE . ' WHERE email= :email' );
	$stmt->bindParam( ':email', $email, PDO::PARAM_STR );
	$stmt->execute();
	$nv_row = $stmt->fetch();

	if( ! empty( $nv_row ) )
	{
		$login_allowed = false;

		if( empty( $nv_row['password'] ) )
		{
			$nv_Request->unset_request( 'openid_attribs', 'session' );
			$login_allowed = true;
		}

		if( $nv_Request->isset_request( 'openid_account_confirm', 'post' ) )
		{
			$password = $nv_Request->get_string( 'password', 'post', '' );
			$nv_seccode = $nv_Request->get_title( 'nv_seccode', 'post', '' );
			$nv_seccode = ! $gfx_chk ? 1 : ( nv_capcha_txt( $nv_seccode ) ? 1 : 0 );

			$nv_Request->unset_request( 'openid_attribs', 'session' );
			if( defined( 'NV_IS_USER_FORUM' ) and file_exists( NV_ROOTDIR . '/' . DIR_FORUM . '/nukeviet/login.php' ) )
			{
				$nv_username = $nv_row['username'];
				$nv_password = $password;
				require_once NV_ROOTDIR . '/' . DIR_FORUM . '/nukeviet/login.php' ;
				if( empty( $error ) )
				{
					$login_allowed = true;
				}
				else
				{
					openidLogin_Res0( $lang_module['openid_confirm_failed'] );
					die();
				}
			}
			else
			{

				if( $crypt->validate( $password, $nv_row['password'] ) and $nv_seccode )
				{
					$login_allowed = true;
				}
				else
				{
					openidLogin_Res0( $lang_module['openid_confirm_failed'] );
					die();
				}
			}
		}
		if( $login_allowed )
		{
			$stmt = $db->prepare( 'INSERT INTO ' . NV_USERS_GLOBALTABLE . '_openid VALUES (' . intval( $nv_row['userid'] ) . ', :id, :opid, :email )' );
			$stmt->bindParam( ':id', $attribs['id'], PDO::PARAM_STR );
			$stmt->bindParam( ':opid',$opid , PDO::PARAM_STR );
			$stmt->bindParam( ':email', $email, PDO::PARAM_STR );
			$stmt->execute();
			if( intval( $nv_row['active'] ) != 1 )
			{
				openidLogin_Res0( $lang_module['login_no_active'] );
			}
			else
			{
				validUserLog( $nv_row, 1, $opid );
				Header( 'Location: ' . nv_url_rewrite( NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name, true ) );
			}
			die();
		}
		$page_title = $lang_module['openid_login'];
		$key_words = $module_info['keywords'];
		$mod_title = $lang_module['openid_login'];

		$lang_module['login_info'] = sprintf( $lang_module['openid_confirm_info'], $email );
		$contents = openid_account_confirm( $gfx_chk, $attribs );

		include NV_ROOTDIR . '/includes/header.php';
		echo nv_site_theme( $contents );
		include NV_ROOTDIR . '/includes/footer.php';
		exit();
	}

	if( $global_config['allowuserreg'] == 2 or $global_config['allowuserreg'] == 3 )
	{
		$query = 'SELECT * FROM ' . NV_USERS_GLOBALTABLE . '_reg WHERE email= :email' ;
		if( $global_config['allowuserreg'] == 2 )
		{
			$query .= ' AND regdate>' . ( NV_CURRENTTIME - 86400 );
		}

		$stmt = $db->prepare( $query ) ;
		$stmt->bindParam( ':email', $email, PDO::PARAM_STR );
		$stmt->execute();
		$row = $stmt->fetch();

		if( ! empty( $row ) )
		{
			if( $global_config['allowuserreg'] == 2 )
			{
				if( $nv_Request->isset_request( 'openid_active_confirm', 'post' ) )
				{
					$nv_Request->unset_request( 'openid_attribs', 'session' );

					$password = $nv_Request->get_string( 'password', 'post', '' );
					$nv_seccode = $nv_Request->get_title( 'nv_seccode', 'post', '' );
					$nv_seccode = ! $gfx_chk ? 1 : ( nv_capcha_txt( $nv_seccode ) ? 1 : 0 );

					if( $crypt->validate( $password, $row['password'] ) and $nv_seccode )
					{
						$reg_attribs = set_reg_attribs( $attribs );

						$sql = "INSERT INTO " . NV_USERS_GLOBALTABLE . " (
							username, md5username, password, email, full_name, gender, photo, birthday, regdate,
							question, answer, passlostkey, view_mail, remember, in_groups,
							active, checknum, last_login, last_ip, last_agent, last_openid, idsite) VALUES (
							:username,
							:md5username,
							:password,
							:email,
							:full_name,
							:gender,
							'', 0,
							:regdate,
							:question,
							:answer,
							'', 1, 1, '', 1, '', 0, '', '', '', " . $global_config['idsite'] . ")";

						$data_insert = array();
						$data_insert['username'] = $row['username'];
						$data_insert['md5username'] = nv_md5safe( $row['username'] );
						$data_insert['password'] = $row['password'];
						$data_insert['email'] = $row['email'];
						$data_insert['full_name'] = ( ! empty( $row['full_name'] ) ? $row['full_name'] : $reg_attribs['full_name'] );
						$data_insert['gender'] = $reg_attribs['gender'];
						$data_insert['regdate'] = $row['regdate'];
						$data_insert['question'] = $row['question'];
						$data_insert['answer'] = $row['answer'];
						$userid = $db->insert_id( $sql, 'userid', $data_insert );

						if( ! $userid )
						{
							openidLogin_Res0( $lang_module['account_active_error'] );
							die();
						}

						$db->query( 'UPDATE ' . NV_GROUPS_GLOBALTABLE . ' SET numbers = numbers+1 WHERE group_id=4' );

						$stmt = $db->prepare( 'DELETE FROM ' . NV_USERS_GLOBALTABLE . '_reg WHERE userid= :userid' );
						$stmt->bindParam( ':userid', $row['userid'], PDO::PARAM_STR );
						$stmt->execute();

						$stmt = $db->prepare( 'INSERT INTO ' . NV_USERS_GLOBALTABLE . '_openid VALUES (' . $userid . ', :openid, :opid, :email )' );
						$stmt->bindParam( ':openid', $attribs['id'], PDO::PARAM_STR );
						$stmt->bindParam( ':opid', $opid, PDO::PARAM_STR );
						$stmt->bindParam( ':email', $email, PDO::PARAM_STR );
						$stmt->execute();

						$query = 'SELECT * FROM ' . NV_USERS_GLOBALTABLE . ' WHERE userid=' . $userid;
						$result = $db->query( $query );
						$row = $result->fetch();

						validUserLog( $row, 1, $opid );

						$info = $lang_module['account_active_ok'] . "<br /><br />\n";
						$info .= "<img border=\"0\" src=\"" . NV_BASE_SITEURL . "images/load_bar.gif\"><br /><br />\n";
						$info .= '[<a href="' . NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '">' . $lang_module['redirect_to_home'] . '</a>]';
						$contents = user_info_exit( $info );
						$contents .= '<meta http-equiv="refresh" content="2;url=' . nv_url_rewrite( NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name, true ) . '" />';

						include NV_ROOTDIR . '/includes/header.php';
						echo nv_site_theme( $contents );
						include NV_ROOTDIR . '/includes/footer.php';
						exit();
					}
					else
					{
						openidLogin_Res0( $lang_module['openid_confirm_failed'] );
						die();
					}
				}

				$page_title = $mod_title = $lang_module['openid_activate_account'];
				$key_words = $module_info['keywords'];

				$lang_module['login_info'] = sprintf( $lang_module['openid_active_confirm_info'], $email );

				$contents = openid_active_confirm( $gfx_chk, $attribs );

				include NV_ROOTDIR . '/includes/header.php';
				echo nv_site_theme( $contents );
				include NV_ROOTDIR . '/includes/footer.php';
				exit();
			}
			else
			{
				$nv_Request->unset_request( 'openid_attribs', 'session' );
				openidLogin_Res0( $lang_module['account_register_to_admin'] );
				die();
			}
		}
	}

	$option = $nv_Request->get_int( 'option', 'get', 0 );

	if( ! $global_config['allowuserreg'] )
	{
		$option = 3;
	}

	$contents = '';
	$page_title = $lang_module['openid_login'];

	if( $option == 3 )
	{
		$error = '';

		if( $nv_Request->isset_request( 'nv_login', 'post' ) )
		{
			$nv_username = $nv_Request->get_title( 'nv_login', 'post', '', 1 );
			$nv_password = $nv_Request->get_title( 'nv_password', 'post', '' );
			$nv_seccode = $nv_Request->get_title( 'nv_seccode', 'post', '' );

			$check_seccode = ! $gfx_chk ? true : ( nv_capcha_txt( $nv_seccode ) ? true : false );

			if( ! $check_seccode )
			{
				$error = $lang_global['securitycodeincorrect'];
			}
			elseif( empty( $nv_username ) )
			{
				$error = $lang_global['username_empty'];
			}
			elseif( empty( $nv_password ) )
			{
				$error = $lang_global['password_empty'];
			}
			else
			{
				if( defined( 'NV_IS_USER_FORUM' ) )
				{
					require_once NV_ROOTDIR . '/' . DIR_FORUM . '/nukeviet/login.php' ;
				}
				else
				{
					$error = $lang_global['loginincorrect'];

					$sql = "SELECT * FROM " . NV_USERS_GLOBALTABLE . " WHERE md5username ='" . nv_md5safe( $nv_username ) . "'";
					$row = $db->query( $sql )->fetch();
					if( ! empty( $row ) )
					{
						if( $row['username'] == $nv_username and $crypt->validate( $nv_password, $row['password'] ) )
						{
							if( ! $row['active'] )
							{
								$error = $lang_module['login_no_active'];
							}
							else
							{
								$error = '';
								$stmt = $db->prepare( 'INSERT INTO ' . NV_USERS_GLOBALTABLE . '_openid VALUES (' . intval( $row['userid'] ) . ', :openid, :opid, :email )' );
								$stmt->bindParam( ':openid', $attribs['id'], PDO::PARAM_STR );
								$stmt->bindParam( ':opid', $opid, PDO::PARAM_STR );
								$stmt->bindParam( ':email', $email, PDO::PARAM_STR );
								$stmt->execute();
								validUserLog( $row, 1, $opid );
							}
						}
					}
				}
			}

			if( empty( $error ) )
			{
				$nv_Request->unset_request( 'openid_attribs', 'session' );

				$nv_redirect = ! empty( $nv_redirect ) ? nv_base64_decode( $nv_redirect ) : NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name;
				$info = $lang_module['login_ok'] . "<br /><br />\n";
				$info .= "<img border=\"0\" src=\"" . NV_BASE_SITEURL . "images/load_bar.gif\"><br /><br />\n";
				$info .= '[<a href="' . $nv_redirect . '">' . $lang_module['redirect_to_back'] . '</a>]';
				$contents .= user_info_exit( $info );
				$contents .= '<meta http-equiv="refresh" content="2;url=' . nv_url_rewrite( $nv_redirect, true ) . '" />';

				include NV_ROOTDIR . '/includes/header.php';
				echo nv_site_theme( $contents );
				include NV_ROOTDIR . '/includes/footer.php';
				exit();
			}

			$array_login = array(
				'nv_login' => $nv_username,
				'nv_password' => $nv_password,
				'nv_redirect' => $nv_redirect,
				'login_info' => '<span style="color:#fb490b;">' . $error . '</span>'
			);
		}
		else
		{
			$array_login = array(
				'nv_login' => '',
				'nv_password' => '',
				'login_info' => $lang_module['openid_note1'],
				'nv_redirect' => $nv_redirect
			);
		}

		$contents .= user_openid_login( $gfx_chk, $array_login, $attribs );

		include NV_ROOTDIR . '/includes/header.php';
		echo nv_site_theme( $contents );
		include NV_ROOTDIR . '/includes/footer.php';
		exit();
	}
	elseif( $option == 1 or $option == 2 )
	{
		$nv_Request->unset_request( 'openid_attribs', 'session' );

		$reg_attribs = set_reg_attribs( $attribs );
		if( empty( $reg_attribs['username'] ) )
		{
			openidLogin_Res0( $lang_module['logged_in_failed'] );
			die();
		}

		if( $option == 2 )
		{
			$sql = "INSERT INTO " . NV_USERS_GLOBALTABLE . "
				(username, md5username, password, email, full_name, gender, photo, birthday,
				regdate, question, answer, passlostkey,
				view_mail, remember, in_groups, active, checknum, last_login, last_ip, last_agent, last_openid, idsite)
				VALUES (
				:username,
				:md5username,
				'',
				:email,
				:full_name,
				:gender,
				'', 0, " . NV_CURRENTTIME . ",
				'', '', '', 0, 0, '', 1, '', 0, '', '', '', " . $global_config['idsite'] . "
				)";

			$data_insert = array();
			$data_insert['username'] = $reg_attribs['username'];
			$data_insert['md5username'] = nv_md5safe( $reg_attribs['username'] );
			$data_insert['email'] = $reg_attribs['email'];
			$data_insert['full_name'] = $reg_attribs['full_name'];
			$data_insert['gender'] = ucfirst( $reg_attribs['gender'] ? $reg_attribs['gender']{0} : '' );
			$userid = $db->insert_id( $sql, 'userid', $data_insert );

			if( ! $userid )
			{
				openidLogin_Res0( $lang_module['err_no_save_account'] );
				die();
			}

			$db->query( 'UPDATE ' . NV_GROUPS_GLOBALTABLE . ' SET numbers = numbers+1 WHERE group_id=4' );

			$query = 'SELECT * FROM ' . NV_USERS_GLOBALTABLE . ' WHERE userid=' . $userid . ' AND active=1';
			$result = $db->query( $query );
			$row = $result->fetch();
			$result->closeCursor();

			$stmt = $db->prepare( 'INSERT INTO ' . NV_USERS_GLOBALTABLE . '_openid VALUES (' . intval( $row['userid'] ) . ', :openid, :opid , :email)' );
			$stmt->bindParam( ':openid', $reg_attribs['openid'], PDO::PARAM_STR );
			$stmt->bindParam( ':opid', $reg_attribs['opid'], PDO::PARAM_STR );
			$stmt->bindParam( ':email', $reg_attribs['email'], PDO::PARAM_STR );
			$stmt->execute();

			validUserLog( $row, 1, $reg_attribs['opid'] );
			$nv_redirect = ! empty( $nv_redirect ) ? nv_base64_decode( $nv_redirect ) : NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name;

			Header( 'Location: ' . nv_url_rewrite( $nv_redirect, true ) );
			exit();
		}
		else
		{
			$reg_attribs = serialize( $reg_attribs );
			$nv_Request->set_Session( 'reg_attribs', $reg_attribs );

			Header( 'Location: ' . nv_url_rewrite( NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=register&openid=1&nv_redirect=' . $nv_redirect, true ) );
			exit();
		}
	}
	$array_user_login = array();
	if( ! defined( 'NV_IS_USER_FORUM' ) )
	{
		$array_user_login[] = array( 'title' => $lang_module['openid_note3'], 'link' => NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=login&amp;server=' . $attribs['server'] . '&amp;result=1&amp;option=1&amp;nv_redirect=' . $nv_redirect );
		$array_user_login[] = array( 'title' => $lang_module['openid_note4'], 'link' => NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=login&amp;server=' . $attribs['server'] . '&amp;result=1&amp;option=2&amp;nv_redirect=' . $nv_redirect );
	}
	else
	{
		$array_user_login[] = array( 'title' => $lang_module['openid_note6'], 'link' => NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=register&amp;nv_redirect=' . $nv_redirect );
	}
	$array_user_login[] = array( 'title' => $lang_module['openid_note5'], 'link' => NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=login&amp;server=' . $attribs['server'] . '&amp;result=1&amp;option=3&amp;nv_redirect=' . $nv_redirect );

	$page_title = $lang_module['openid_login'];
	$key_words = $module_info['keywords'];
	$mod_title = $lang_module['openid_login'];

	$contents .= user_openid_login2( $attribs, $array_user_login );

	include NV_ROOTDIR . '/includes/header.php';
	echo nv_site_theme( $contents );
	include NV_ROOTDIR . '/includes/footer.php';
	exit();
}

$nv_redirect = $nv_Request->get_title( 'nv_redirect', 'post,get', '' );

//Dang nhap bang Open ID
if( defined( 'NV_OPENID_ALLOWED' ) )
{
	$server = $nv_Request->get_string( 'server', 'get', '' );
	if( ! empty( $server ) and isset( $openid_servers[$server] ) )
	{
		if( $server == 'facebook' )
		{
			include NV_ROOTDIR . '/modules/' . $module_file . '/facebook.auth.class.php' ;
			$FaceBookAuth = new FaceBookAuth( $global_config['facebook_client_id'], $global_config['facebook_client_secret'], NV_MY_DOMAIN . NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=login&server=' . $server );

			$state = $nv_Request->get_string( 'state', 'get', '' );
			$checksess = md5( $global_config['sitekey'] . session_id() );

			if( ! empty( $state ) )
			{
				if( $checksess == $state )
				{
					$code = $nv_Request->get_string( 'code', 'get', '' );
					$error = $nv_Request->get_string( 'error', 'get', '' );

					if( $error )
					{
						$attribs = array( 'result' => 'cancel' );
					}
					else
					{
						$data = $FaceBookAuth->GraphBase( $code );

						if( ! $data->verified )
						{
							$attribs = array( 'result' => 'notlogin' );
						}
						else
						{
							$attribs = array(
								'result' => 'is_res',
								'id' => sprintf( $openid_servers[$server]['identity'], $data->id ),
								'server' => $server
							) + $FaceBookAuth->getAttributes( $data, $openid_servers[$server]['required'] );
						}
					}

					$attribs = serialize( $attribs );
					$nv_Request->set_Session( 'openid_attribs', $attribs );
					Header( 'Location: ' . NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=login&server=' . $server . '&result=1&nv_redirect=' . $nv_redirect );
					exit();
				}
				else
				{
					Header( 'Location: ' . nv_url_rewrite( NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name, true ) );
					die();
				}
			}

			if( ! $nv_Request->isset_request( 'result', 'get' ) )
			{
				$scope = 'email';
				// Yeu cau them email cho phu hop voi NukeViet
				header( 'Location: ' . $FaceBookAuth->GetOAuthDialogUrl( $checksess, $scope ) );
				die();
			}

			$openid_attribs = $nv_Request->get_string( 'openid_attribs', 'session', '' );
			$openid_attribs = ! empty( $openid_attribs ) ? unserialize( $openid_attribs ) : array();

			if( empty( $openid_attribs ) or $openid_attribs['server'] != $server )
			{
				$nv_Request->unset_request( 'openid_attribs', 'session' );
				$nv_redirect = ! empty( $nv_redirect ) ? nv_base64_decode( $nv_redirect ) : NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name;
				Header( 'Location: ' . nv_url_rewrite( $nv_redirect ) );
				die();
			}

			if( $openid_attribs['result'] == 'cancel' )
			{
				$nv_Request->unset_request( 'openid_attribs', 'session' );
				openidLogin_Res0( $lang_module['canceled_authentication'] );
			}
			elseif( $openid_attribs['result'] == 'notlogin' )
			{
				$nv_Request->unset_request( 'openid_attribs', 'session' );
				openidLogin_Res0( $lang_module['not_logged_in'] );
			}
			else
			{
				openidLogin_Res1( $openid_attribs );
			}
			exit();
		}
		else
		{
			include_once NV_ROOTDIR . '/includes/class/openid.class.php' ;
			$openid = new LightOpenID();

			if( $nv_Request->isset_request( 'openid_mode', 'get' ) )
			{
				$openid_mode = $nv_Request->get_string( 'openid_mode', 'get', '' );

				if( $openid_mode == 'cancel' )
				{
					$attribs = array( 'result' => 'cancel' );
				}
				elseif( ! $openid->validate() )
				{
					$attribs = array( 'result' => 'notlogin' );
				}
				else
				{
					$attribs = array(
						'result' => 'is_res',
						'id' => $openid->identity,
						'server' => $server
					) + $openid->getAttributes();
				}

				$attribs = serialize( $attribs );
				$nv_Request->set_Session( 'openid_attribs', $attribs );
				Header( 'Location: ' . NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=login&server=' . $server . '&result=1&nv_redirect=' . $nv_redirect );
				exit();
			}

			if( ! $nv_Request->isset_request( 'result', 'get' ) )
			{
				$openid->identity = $openid_servers[$server]['identity'];
				$openid->required = array_values( $openid_servers[$server]['required'] );
				header( 'Location: ' . $openid->authUrl() );
				die();
			}

			$openid_attribs = $nv_Request->get_string( 'openid_attribs', 'session', '' );
			$openid_attribs = ! empty( $openid_attribs ) ? unserialize( $openid_attribs ) : array();

			if( empty( $openid_attribs ) or $openid_attribs['server'] != $server )
			{
				$nv_Request->unset_request( 'openid_attribs', 'session' );
				$nv_redirect = ! empty( $nv_redirect ) ? nv_base64_decode( $nv_redirect ) : NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name;
				Header( 'Location: ' . nv_url_rewrite( $nv_redirect ) );
				die();
			}

			if( $openid_attribs['result'] == 'cancel' )
			{
				$nv_Request->unset_request( 'openid_attribs', 'session' );
				openidLogin_Res0( $lang_module['canceled_authentication'] );
			}
			elseif( $openid_attribs['result'] == 'notlogin' )
			{
				$nv_Request->unset_request( 'openid_attribs', 'session' );
				openidLogin_Res0( $lang_module['not_logged_in'] );
			}
			else
			{
				openidLogin_Res1( $openid_attribs );
			}
			exit();
		}
	}
}

//Dang nhap kieu thong thuong
$page_title = $lang_module['login'];
$key_words = $module_info['keywords'];
$mod_title = $lang_module['login'];

$contents = '';
$error = '';
if( $nv_Request->isset_request( 'nv_login', 'post' ) )
{
	$nv_username = $nv_Request->get_title( 'nv_login', 'post', '', 1 );
	$nv_password = $nv_Request->get_title( 'nv_password', 'post', '' );
	$nv_seccode = $nv_Request->get_title( 'nv_seccode', 'post', '' );

	$check_seccode = ! $gfx_chk ? true : ( nv_capcha_txt( $nv_seccode ) ? true : false );

	if( ! $check_seccode )
	{
		$error = $lang_global['securitycodeincorrect'];
	}
	elseif( empty( $nv_username ) )
	{
		$error = $lang_global['username_empty'];
	}
	elseif( empty( $nv_password ) )
	{
		$error = $lang_global['password_empty'];
	}
	else
	{
		if( defined( 'NV_IS_USER_FORUM' ) )
		{
			require_once NV_ROOTDIR . '/' . DIR_FORUM . '/nukeviet/login.php' ;
		}
		else
		{
			$error = $lang_global['loginincorrect'];

			$sql = "SELECT * FROM " . NV_USERS_GLOBALTABLE . " WHERE md5username ='" . nv_md5safe( $nv_username ) . "'";
			$row = $db->query( $sql )->fetch();
			if( ! empty( $row ) )
			{
				if( $row['username'] == $nv_username and $crypt->validate( $nv_password, $row['password'] ) )
				{
					if( ! $row['active'] )
					{
						$error = $lang_module['login_no_active'];
					}
					else
					{
						$error = '';
						validUserLog( $row, 1, '' );
					}
				}
			}
		}
	}

	if( empty( $error ) )
	{
		$nv_redirect = ! empty( $nv_redirect ) ? nv_base64_decode( $nv_redirect ) : NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name;
		$info = $lang_module['login_ok'] . "<br /><br />\n";
		$info .= "<img border=\"0\" src=\"" . NV_BASE_SITEURL . "images/load_bar.gif\"><br /><br />\n";
		$info .= '[<a href="' . $nv_redirect . '">' . $lang_module['redirect_to_back'] . '</a>]';
		$contents .= user_info_exit( $info );
		$contents .= '<meta http-equiv="refresh" content="2;url=' . nv_url_rewrite( $nv_redirect ) . '" />';

		include NV_ROOTDIR . '/includes/header.php';
		echo nv_site_theme( $contents );
		include NV_ROOTDIR . '/includes/footer.php';
		exit();
	}
	$lang_module['login_info'] = '<span style="color:#fb490b;">' . $error . '</span>';
	$array_login = array(
		'nv_login' => $nv_username,
		'nv_password' => $nv_password,
		'nv_redirect' => $nv_redirect
	);
}
else
{
	$array_login = array(
		'nv_login' => '',
		'nv_password' => '',
		'nv_redirect' => $nv_redirect
	);
}

$array_login['openid_info'] = $lang_module['what_is_openid'];
if( $global_config['allowuserreg'] == 2 )
{
	$array_login['openid_info'] .= '<br />' . $lang_module['or_activate_account'];
}

$contents .= user_login( $gfx_chk, $array_login );

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme( $contents );
include NV_ROOTDIR . '/includes/footer.php';