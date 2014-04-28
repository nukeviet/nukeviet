<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 12/29/2009 4:15
 */

if( ! defined( 'NV_MAINFILE' ) ) die( 'Stop!!!' );

$user_info = array();

if( defined( 'NV_IS_ADMIN' ) )
{
	$user_info = $admin_info;

	define( 'NV_IS_USER', true );
}
elseif( defined( 'NV_IS_USER_FORUM' ) )
{
	require_once NV_ROOTDIR . '/' . DIR_FORUM . '/nukeviet/is_user.php';

	if( isset( $user_info['userid'] ) and $user_info['userid'] > 0 )
	{
		$_sql = 'SELECT userid, username, email, full_name, gender, photo, birthday, regdate,
			view_mail, remember, in_groups, last_login AS current_login, last_agent AS current_agent, last_ip AS current_ip, last_openid, password
			FROM ' . NV_USERS_GLOBALTABLE . ' WHERE userid = ' . intval( $user_info['userid'] ) . ' AND active=1';

		$user_info = $db->query( $_sql )->fetch();
		if( ! empty( $user_info ) )
		{
			define( 'NV_IS_USER', true );

			if( empty( $user_info['full_name'] ) ) $user_info['full_name'] = $user_info['username'];

			$user_info['in_groups'] = nv_user_groups( $user_info['in_groups'] );
			$user_info['st_login'] = ! empty( $user_info['password'] ) ? true : false;
			$user_info['current_mode'] = 1;
			$user_info['valid_question'] = true;

			unset( $user_info['password'] );
		}
		else
		{
			$user_info = array();
		}
	}
}
else
{
	if( $nv_Request->get_bool( 'nvloginhash', 'cookie', false ) )
	{
		$user = $nv_Request->get_string( 'nvloginhash', 'cookie', '' );

		if( ! empty( $user ) and $global_config['allowuserlogin'] )
		{
			$user = unserialize( nv_base64_decode( $user ) );
			$strlen = ( NV_CRYPT_SHA1 == 1 ) ? 40 : 32;

			if( isset( $user['userid'] ) and is_numeric( $user['userid'] ) and $user['userid'] > 0 )
			{
				if( isset( $user['checknum'] ) and preg_match( '/^[a-z0-9]{' . $strlen . '}$/', $user['checknum'] ) )
				{
					$_sql = 'SELECT userid, username, email, full_name, gender, photo, birthday, regdate,
						view_mail, remember, in_groups, checknum, last_agent AS current_agent, last_ip AS current_ip, last_login AS current_login,
						last_openid AS current_openid, password, question, answer
						FROM ' . NV_USERS_GLOBALTABLE . ' WHERE userid = ' . $user['userid'] . ' AND active=1';

					$user_info = $db->query( $_sql )->fetch();
					if( ! empty( $user_info ) )
					{
						if( strcasecmp( $user['checknum'], $user_info['checknum'] ) == 0 and 						//checknum
						isset( $user['current_agent'] ) and ! empty( $user['current_agent'] ) and strcasecmp( $user['current_agent'], $user_info['current_agent'] ) == 0 and 						//user_agent
						isset( $user['current_ip'] ) and ! empty( $user['current_ip'] ) and strcasecmp( $user['current_ip'], $user_info['current_ip'] ) == 0 and 						//current IP
						isset( $user['current_login'] ) and ! empty( $user['current_login'] ) and strcasecmp( $user['current_login'], intval( $user_info['current_login'] ) ) == 0 ) //current login
						{
							if( empty( $user_info['full_name'] ) ) $user_info['full_name'] = $user_info['username'];

							$user_info['in_groups'] = nv_user_groups( $user_info['in_groups'] );
							$user_info['last_login'] = intval( $user['last_login'] );
							$user_info['last_agent'] = $user['last_agent'];
							$user_info['last_ip'] = $user['last_ip'];
							$user_info['last_openid'] = $user['last_openid'];
							$user_info['st_login'] = ! empty( $user_info['password'] ) ? true : false;
							$user_info['valid_question'] = ( ! empty( $user_info['question'] ) and ! empty( $user_info['answer'] ) ) ? true : false;
							$user_info['current_mode'] = ! empty( $user_info['current_openid'] ) ? 2 : 1;

							unset( $user_info['checknum'], $user_info['password'], $user_info['question'], $user_info['answer'] );

							if( ! empty( $user_info['current_openid'] ) )
							{
								$sth = $db->prepare( 'SELECT openid, email FROM ' . NV_USERS_GLOBALTABLE . '_openid WHERE opid= :current_openid' );
								$sth->bindParam( ':current_openid', $user_info['current_openid'], PDO::PARAM_STR );
								$sth->execute();
								$row = $sth->fetch();

								if( empty( $row ) )
								{
									$user_info = array();
								}
								else
								{
									$user_info['openid_id'] = $row['openid'];
									$user_info['openid_email'] = $row['email'];

									$user_info['openid_server'] = parse_url( $user_info['openid_id'] );
									$user_info['openid_server'] = preg_replace( '/^([w]{3})\./', '', $user_info['openid_server']['host'] );
								}
							}
						}
					}
				}
			}
		}

		if( ! empty( $user_info ) and isset( $user_info['userid'] ) and $user_info['userid'] > 0 )
		{
			define( 'NV_IS_USER', true );
		}
		else
		{
			$nv_Request->unset_request( 'nvloginhash', 'cookie' );
			$user_info = array();
		}
	}

	unset( $user, $strlen, $_sql );
}