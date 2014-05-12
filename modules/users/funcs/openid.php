<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 25/7/2010, 18:36
 */

if( ! defined( 'NV_IS_MOD_USER' ) ) die( 'Stop!!!' );

if( ! defined( 'NV_IS_USER' ) or ! $global_config['allowuserlogin'] or ! defined( 'NV_OPENID_ALLOWED' ) )
{
	Header( 'Location: ' . nv_url_rewrite( NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name, true ) );
	die();
}
$page_title = $mod_title = $lang_module['openid_administrator'];
$key_words = $module_info['keywords'];

if( $nv_Request->isset_request( 'del', 'get' ) )
{
	$openid_del = $nv_Request->get_typed_array( 'openid_del', 'post', 'string', '' );
	if( ! empty( $openid_del ) )
	{
		foreach( $openid_del as $opid )
		{
			if( ! empty( $opid ) and ( empty( $user_info['current_openid'] ) or ( ! empty( $user_info['current_openid'] ) and $user_info['current_openid'] != $opid ) ) )
			{
				$stmt = $db->prepare( 'DELETE FROM ' . NV_USERS_GLOBALTABLE . '_openid WHERE opid= :opid' );
				$stmt->bindParam( ':opid', $opid, PDO::PARAM_STR );
				$stmt->execute();
			}
		}
	}
	Header( 'Location: ' . nv_url_rewrite( NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=openid', true ) );
	die();
}

if( $nv_Request->isset_request( 'server', 'get' ) )
{
	$server = $nv_Request->get_string( 'server', 'get', '' );
	if( ! empty( $server ) and isset( $openid_servers[$server] ) )
	{
		if( $server == 'facebook' )
		{
			include NV_ROOTDIR . '/modules/' . $module_file . '/facebook.auth.class.php' ;
			$FaceBookAuth = new FaceBookAuth( $global_config['facebook_client_id'], $global_config['facebook_client_secret'], NV_MY_DOMAIN . NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=openid&server=' . $server );

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
						$nv_Request->set_Session( 'openid_error', 1 );
						header( 'Location: ' . nv_url_rewrite( NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=openid', true ) );
						die();
					}
					else
					{
						$data = $FaceBookAuth->GraphBase( $code );

						if( ! $data->verified )
						{
							$nv_Request->set_Session( 'openid_error', 2 );
							header( 'Location: ' . nv_url_rewrite( NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=openid', true ) );
							die();
						}
						else
						{
							$openid = sprintf( $openid_servers[$server]['identity'], $data->id );
							$attribs = $FaceBookAuth->getAttributes( $data, $openid_servers[$server]['required'] );

							$email = ( isset( $attribs['contact/email'] ) and nv_check_valid_email( $attribs['contact/email'] ) == '' ) ? $attribs['contact/email'] : '';
							if( empty( $openid ) or empty( $email ) )
							{
								$nv_Request->set_Session( 'openid_error', 3 );
								header( 'Location: ' . nv_url_rewrite( NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=openid', true ) );
								die();
							}

							$opid = $crypt->hash( $openid );

							$stmt = $db->prepare( 'SELECT COUNT(*) FROM ' . NV_USERS_GLOBALTABLE . '_openid WHERE opid= :opid' );
							$stmt->bindParam( ':opid', $opid, PDO::PARAM_STR );
							$stmt->execute();
							$count = $stmt->fetchColumn();
							if( $count )
							{
								$nv_Request->set_Session( 'openid_error', 4 );
								header( 'Location: ' . nv_url_rewrite( NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=openid', true ) );
								die();
							}

							$stmt = $db->prepare( 'SELECT COUNT(*) FROM ' . NV_USERS_GLOBALTABLE . ' WHERE userid!=' . $user_info['userid'] . ' AND email= :email' );
							$stmt->bindParam( ':email', $email, PDO::PARAM_STR );
							$stmt->execute();
							$count = $stmt->fetchColumn();
							if( $count )
							{
								$nv_Request->set_Session( 'openid_error', 5 );
								header( 'Location: ' . nv_url_rewrite( NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=openid', true ) );
								die();
							}

							if( $global_config['allowuserreg'] == 2 or $global_config['allowuserreg'] == 3 )
							{
								$query = 'SELECT COUNT(*) AS count FROM ' . NV_USERS_GLOBALTABLE . '_reg WHERE email= :email';
								if( $global_config['allowuserreg'] == 2 )
								{
									$query .= ' AND regdate>' . ( NV_CURRENTTIME - 86400 );
								}
								$stmt = $db->prepare( $query ) ;
								$stmt->bindParam( ':email', $email, PDO::PARAM_STR );
								$stmt->execute();
								$count = $stmt->fetchColumn();
								if( $count )
								{
									$nv_Request->set_Session( 'openid_error', 6 );
									header( 'Location: ' . nv_url_rewrite( NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=openid', true ) );
									die();
								}
							}

							$stmt = $db->prepare( 'INSERT INTO ' . NV_USERS_GLOBALTABLE . '_openid VALUES (' . $user_info['userid'] . ', :openid, :opid, :email )' );
							$stmt->bindParam( ':openid', $openid, PDO::PARAM_STR );
							$stmt->bindParam( ':opid', $opid, PDO::PARAM_STR );
							$stmt->bindParam( ':email', $email, PDO::PARAM_STR );
							$stmt->execute();

							nv_insert_logs( NV_LANG_DATA, $module_name, $lang_module['openid_add'], $user_info['username'] . ' | ' . $client_info['ip'] . ' | ' . $opid, 0 );

							header( 'Location: ' . nv_url_rewrite( NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=openid', true ) );
							die();
						}
					}
				}
				else
				{
					Header( 'Location: ' . nv_url_rewrite( NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name, true ) );
					die();
				}
			}
			else
			{
				$scope = 'email';
				// Yeu cau them email cho phu hop voi NukeViet
				header( 'Location: ' . $FaceBookAuth->GetOAuthDialogUrl( $checksess, $scope ) );
				die();
			}
		}
		else
		{
			include_once NV_ROOTDIR . '/includes/class/openid.class.php' ;
			$openid_class = new LightOpenID();

			if( $nv_Request->isset_request( 'openid_mode', 'get' ) )
			{
				$openid_mode = $nv_Request->get_string( 'openid_mode', 'get', '' );

				if( $openid_mode == 'cancel' )
				{
					$nv_Request->set_Session( 'openid_error', 1 );
					header( 'Location: ' . nv_url_rewrite( NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=openid', true ) );
					die();
				}
				elseif( ! $openid_class->validate() )
				{
					$nv_Request->set_Session( 'openid_error', 2 );
					header( 'Location: ' . nv_url_rewrite( NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=openid', true ) );
					die();
				}
				else
				{
					$openid = $openid_class->identity;
					$attribs = $openid_class->getAttributes();
					$email = ( isset( $attribs['contact/email'] ) and nv_check_valid_email( $attribs['contact/email'] ) == '' ) ? $attribs['contact/email'] : '';
					if( empty( $openid ) or empty( $email ) )
					{
						$nv_Request->set_Session( 'openid_error', 3 );
						header( 'Location: ' . nv_url_rewrite( NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=openid', true ) );
						die();
					}

					$opid = $crypt->hash( $openid );

					$stmt = $db->prepare( 'SELECT COUNT(*) FROM ' . NV_USERS_GLOBALTABLE . '_openid WHERE opid= :opid ' );
					$stmt->bindParam( ':opid', $opid, PDO::PARAM_STR );
					$stmt->execute();
					$count = $stmt->fetchColumn();
					if( $count )
					{
						$nv_Request->set_Session( 'openid_error', 4 );
						header( 'Location: ' . nv_url_rewrite( NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=openid', true ) );
						die();
					}

					$stmt = $db->prepare( 'SELECT COUNT(*) FROM ' . NV_USERS_GLOBALTABLE . ' WHERE userid!=' . $user_info['userid'] . ' AND email= :email ' );
					$stmt->bindParam( ':email', $email, PDO::PARAM_STR );
					$stmt->execute();
					$count = $stmt->fetchColumn();
					if( $count )
					{
						$nv_Request->set_Session( 'openid_error', 5 );
						header( 'Location: ' . nv_url_rewrite( NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=openid', true ) );
						die();
					}

					if( $global_config['allowuserreg'] == 2 or $global_config['allowuserreg'] == 3 )
					{
						$query = 'SELECT COUNT(*) FROM ' . NV_USERS_GLOBALTABLE . '_reg WHERE email= :email ';
						if( $global_config['allowuserreg'] == 2 )
						{
							$query .= ' AND regdate>' . ( NV_CURRENTTIME - 86400 );
						}
						$stmt = $db->prepare( $query ) ;
						$stmt->bindParam( ':email', $email, PDO::PARAM_STR );
						$stmt->execute();
						$count = $stmt->fetchColumn();
						if( $count )
						{
							$nv_Request->set_Session( 'openid_error', 6 );
							header( 'Location: ' . nv_url_rewrite( NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=openid', true ) );
							die();
						}
					}

					$stmt = $db->prepare( 'INSERT INTO ' . NV_USERS_GLOBALTABLE . '_openid VALUES (' . $user_info['userid'] . ', :openid, :opid, :email )' );
					$stmt->bindParam( ':openid', $openid, PDO::PARAM_STR );
					$stmt->bindParam( ':opid', $opid, PDO::PARAM_STR );
					$stmt->bindParam( ':email', $email, PDO::PARAM_STR );
					$stmt->execute();

					nv_insert_logs( NV_LANG_DATA, $module_name, $lang_module['openid_add'], $user_info['username'] . ' | ' . $client_info['ip'] . ' | ' . $opid, 0 );

					header( 'Location: ' . nv_url_rewrite( NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=openid', true ) );
					die();
				}
			}
			else
			{
				$openid_class->identity = $openid_servers[$server]['identity'];
				$openid_class->required = array_values( $openid_servers[$server]['required'] );
				header( 'Location: ' . $openid_class->authUrl() );
				die();
			}
		}
	}
}

$data = array();
$data['openid_list'] = array();
$sql = 'SELECT * FROM ' . NV_USERS_GLOBALTABLE . '_openid WHERE userid=' . $user_info['userid'];
$query = $db->query( $sql );
while( $row = $query->fetch() )
{
	$server = parse_url( $row['openid'] );

	$data['openid_list'][] = array(
		'opid' => $row['opid'],
		'openid' => $row['openid'],
		'server' => $server['host'],
		'email' => $row['email'],
		'disabled' => ( ( ! empty( $user_info['current_openid'] ) and $user_info['current_openid'] == $row['opid'] ) ? ' disabled="disabled"' : '' )
	);
}

$error = $nv_Request->get_int( 'openid_error', 'session', 0 );
$nv_Request->unset_request( 'openid_error', 'session' );

switch( $error )
{
	case 1:
		$data['info'] = '<div style="color:#fb490b;">' . $lang_module['canceled_authentication'] . '</div>';
		break;

	case 2:
		$data['info'] = '<div style="color:#fb490b;">' . $lang_module['not_logged_in'] . '</div>';
		break;

	case 3:
		$data['info'] = '<div style="color:#fb490b;">' . $lang_module['logged_in_failed'] . '</div>';
		break;

	case 4:
		$data['info'] = '<div style="color:#fb490b;">' . $lang_module['openid_is_exists'] . '</div>';
		break;

	case 5:
	case 6:
		$data['info'] = '<div style="color:#fb490b;">' . $lang_module['email_is_exists'] . '</div>';
		break;

	default:
		$data['info'] = $lang_module['openid_add_new'];
}

$contents = user_openid_administrator( $data );

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme( $contents );
include NV_ROOTDIR . '/includes/footer.php';