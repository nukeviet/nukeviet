<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 10/03/2010 10:51
 */

if( ! defined( 'NV_IS_MOD_USER' ) ) die( 'Stop!!!' );

if( defined( 'NV_IS_USER' ) )
{
	Header( 'Location: ' . nv_url_rewrite( NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name, true ) );
	die();
}

if( defined( 'NV_IS_USER_FORUM' ) )
{
	require_once NV_ROOTDIR . '/' . DIR_FORUM . '/nukeviet/lostpass.php' ;
	exit();
}

$page_title = $mod_title = $lang_module['lostpass_page_title'];
$key_words = $module_info['keywords'];

$u = $nv_Request->get_int( 'u', 'post,get' );
$k = $nv_Request->get_string( 'k', 'post,get' );
if( ! empty( $u ) and ! empty( $k ) )
{
	$contents = $lang_module['lostpass_active_error_link'];
	$sql = 'SELECT * FROM ' . NV_USERS_GLOBALTABLE . ' WHERE userid=' . $u;
	$row = $db->query( $sql )->fetch();
	if( ! empty( $row ) )
	{
		if( ! empty( $row['passlostkey'] ) and $k == md5( $row['userid'] . $row['passlostkey'] . $global_config['sitekey'] ) )
		{
			$pass_new = $nv_Request->get_string( 'pass_new', 'post' );
			$pass_new_re = $nv_Request->get_string( 'pass_new_re', 'post' );
			if( $pass_new == $pass_new_re and !empty( $pass_new ) )
			{
				$password = $crypt->hash_password( $pass_new, $global_config['hashprefix'] );
				$db->query( "UPDATE " . NV_USERS_GLOBALTABLE . " SET password=" . $db->quote( $password ) . ", passlostkey='' WHERE userid=" . $u );
				$contents = $lang_module['change_pass_ok'];
				$contents .= '<meta http-equiv="refresh" content="10;url=' . nv_url_rewrite( NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name, true ) . '" />';
			}
			else
			{
				$contents = user_lostpass_new( $row['userid'], $k );
			}
		}
	}
}
else
{
	$data = array();
	$data['checkss'] = md5( $client_info['session_id'] . $global_config['sitekey'] );
	$data['userField'] = nv_substr( $nv_Request->get_title( 'userField', 'post', '', 1 ), 0, 100 );
	$data['answer'] = nv_substr( $nv_Request->get_title( 'answer', 'post', '', 1 ), 0, 255 );
	$data['send'] = $nv_Request->get_bool( 'send', 'post', false );
	$data['nv_seccode'] = $nv_Request->get_title( 'nv_seccode', 'post', '' );
	$data['nv_redirect'] = $nv_Request->get_title( 'nv_redirect', 'get, post', '' );

	$checkss = $nv_Request->get_title( 'checkss', 'post', '' );
	$seccode = $nv_Request->get_string( 'lostpass_seccode', 'session', '' );

	$step = 1;
	$error = $question = '';

	if( $checkss == $data['checkss'] )
	{
		if( ( ! empty( $seccode ) and md5( $data['nv_seccode'] ) == $seccode ) or nv_capcha_txt( $data['nv_seccode'] ) )
		{
			if( ! empty( $data['userField'] ) )
			{
				$check_email = nv_check_valid_email( $data['userField'] );
				if( empty( $check_email ) )
				{
					$sql = 'SELECT * FROM ' . NV_USERS_GLOBALTABLE . ' WHERE email= :userField AND active=1';
					$userField = $data['userField'];
				}
				else
				{
					$sql = 'SELECT * FROM ' . NV_USERS_GLOBALTABLE . ' WHERE md5username=:userField AND active=1';
					$userField = nv_md5safe( $data['userField']);
				}
				$stmt = $db->prepare( $sql ) ;
				$stmt->bindParam( ':userField', $userField, PDO::PARAM_STR );
				$stmt->execute();
				$row = $stmt->fetch();
				if( ! empty( $row ) )
				{
					$step = 2;
					if( empty( $seccode ) )
					{
						$nv_Request->set_Session( 'lostpass_seccode', md5( $data['nv_seccode'] ) );
					}

					$question = $row['question'];

					$info = '';
					if( ! empty( $row['opid'] ) and empty( $row['password'] ) )
					{
						$info = $lang_module['openid_lostpass_info'];
					}
					elseif( $global_config['allowquestion'] and ( empty( $row['question'] ) or empty( $row['answer'] ) ) )
					{
						$info = $lang_module['lostpass_question_empty'];
					}

					if( ! empty( $info ) )
					{
						$nv_Request->unset_request( 'lostpass_seccode', 'session' );

						$contents = user_info_exit( $info );
						$contents .= '<meta http-equiv="refresh" content="15;url=' . nv_url_rewrite( NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=lostpass', true ) . '" />';

						include NV_ROOTDIR . '/includes/header.php';
						echo nv_site_theme( $contents );
						include NV_ROOTDIR . '/includes/footer.php';
						exit();
					}
					if( $global_config['allowquestion'] == 0 )
					{
						$data['send'] = 1;
						$data['answer'] = $row['answer'];
					}

					if( $data['send'] )
					{
						if( $data['answer'] == $row['answer'] )
						{
							$nv_Request->unset_request( 'lostpass_seccode', 'session' );

							$rand = rand( NV_UPASSMIN, NV_UPASSMAX );
							if ( $rand < 6) $rand = 6;
							$password_new = nv_genpass( $rand );

							$passlostkey = md5( $row['userid'] . $password_new . $global_config['sitekey'] );
							$k = md5( $row['userid'] . $passlostkey . $global_config['sitekey'] );

							$subject = sprintf( $lang_module['lostpass_email_subject'], $global_config['site_name'] );
							$link_lostpass_content_email = NV_MY_DOMAIN . NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op . '&u=' . $row['userid'] . '&k=' . $k;

							$row['full_name'] = nv_show_name_user( $row['first_name'], $row['last_name'], $row['username'] );
							$message = sprintf( $lang_module['lostpass_email_content'], $row['full_name'], $global_config['site_name'], $link_lostpass_content_email, $row['username'] );

							$ok = nv_sendmail( $global_config['site_email'], $row['email'], $subject, $message );
							if( $ok )
							{
								$sql = "UPDATE " . NV_USERS_GLOBALTABLE . " SET passlostkey='" . $passlostkey . "' WHERE userid=" . $row['userid'];
								$db->query( $sql );
								if( ! empty( $check_email ) )
								{
									$row['email'] = substr( $row['email'], 0, 3 ) . '***' . substr( $row['email'], -6 );
								}
								$info = sprintf( $lang_module['lostpass_content_mess'], $row['email'] );
							}
							else
							{
								$info = $lang_global['error_sendmail'];
							}

							$nv_redirect = ! empty( $data['nv_redirect'] ) ? nv_base64_decode( $data['nv_redirect'] ) : nv_url_rewrite( NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name, true );
							$contents = user_info_exit( $info );
							$contents .= '<meta http-equiv="refresh" content="10;url=' . $nv_redirect . '" />';

							include NV_ROOTDIR . '/includes/header.php';
							echo nv_site_theme( $contents );
							include NV_ROOTDIR . '/includes/footer.php';
							exit();
						}
						else
						{
							$step = 2;
							$error = $lang_module['answer_failed'];
						}
					}
				}
				else
				{
					$step = 1;
					$nv_Request->unset_request( 'lostpass_seccode', 'session' );
					$error = $lang_module['lostpass_no_info2'];
				}
			}
			else
			{
				$step = 1;
				$nv_Request->unset_request( 'lostpass_seccode', 'session' );
				$error = $lang_module['lostpass_no_info1'];
			}
		}
		else
		{
			$step = 1;
			$nv_Request->unset_request( 'lostpass_seccode', 'session' );
			$error = $lang_global['securitycodeincorrect'];
		}
	}

	$data['step'] = $step;
	$data['info'] = empty( $error ) ? $lang_module['step' . $data['step']] : '<span style="color:#fb490b;">' . $error . '</span>';
	$contents = user_lostpass( $data, $question );
}

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme( $contents );
include NV_ROOTDIR . '/includes/footer.php';