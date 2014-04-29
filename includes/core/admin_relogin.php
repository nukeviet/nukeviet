<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @copyright 2010
 * @License GNU/GPL version 2 or any later version
 * @Createdate 1/10/2010 9:3
 */

if( ! defined( 'NV_MAINFILE' ) ) die( 'Stop!!!' );

if( defined( 'NV_IS_ADMIN' ) )
{
	if( empty( $admin_info['checkpass'] ) )
	{
		if( $nv_Request->isset_request( NV_ADMINRELOGIN_VARIABLE, 'get' ) and $nv_Request->get_int( NV_ADMINRELOGIN_VARIABLE, 'get' ) == 1 )
		{
			$redirect = $nv_Request->get_string( 'admin_relogin_redirect', 'session' );
			$check_hits = $admin_info['checkhits'];
			++$check_hits;
			$nv_Request->set_Session( 'online', '0|' . $admin_info['last_online'] . '|' . NV_CURRENTTIME . '|' . $check_hits );

			$error = '';
			$password = '';
			if( $nv_Request->get_int( 'save', 'post' ) == '1' )
			{
				if( $client_info['is_myreferer'] != 1 ) trigger_error( 'Wrong URL', 256 );
				$nv_password = $nv_Request->get_title( 'nv_password', 'post', '', '' );
				if( empty( $nv_password ) )
				{
					$error = $lang_global['password_empty'];
				}
				else
				{
					if( defined( 'NV_IS_USER_FORUM' ) )
					{
						$nv_username = $admin_info['username'];
						define( 'NV_IS_MOD_USER', true );
						nv_insert_logs( NV_LANG_DATA, 'login', '[' . $nv_username . '] ' . strtolower( $lang_global['loginsubmit'] ), ' Client IP:' . NV_CLIENT_IP, 0 );
						require_once NV_ROOTDIR . '/' . DIR_FORUM . '/nukeviet/login.php';
					}

					$result = $db->query( 'SELECT t1.admin_id as admin_id, t1.lev as admin_lev, t1.last_agent as admin_last_agent, t1.last_ip as admin_last_ip, t1.last_login as admin_last_login, t2.password as admin_pass FROM ' . NV_AUTHORS_GLOBALTABLE . ' t1 INNER JOIN ' . NV_USERS_GLOBALTABLE . ' t2 ON t1.admin_id = t2.userid WHERE t1.admin_id = ' . $admin_info['admin_id'] . ' AND t1.lev!=0 AND t1.is_suspend=0 AND t2.active=1' );
					$row = $result->fetch();
					$result->closeCursor();

					if( ! $crypt->validate( $nv_password, $row['admin_pass'] ) )
					{
						$error = $lang_global['incorrect_password'];
					}
					else
					{
						$nv_Request->set_Session( 'online', '1|' . NV_CURRENTTIME . '|' . NV_CURRENTTIME . '|0' );
						$nv_Request->unset_request( 'admin_login_redirect', 'session' );

						if( ! empty( $redirect ) and nv_is_myreferer( $redirect ) == 1 )
						{
							Header( 'Location: ' . nv_url_rewrite( $redirect, true ) );
							exit();
						}
						else
						{
							Header( 'Location: ' . NV_BASE_ADMINURL );
							exit();
						}
					}
				}
			}
			if( $check_hits > $global_config['adminrelogin_max'] )
			{
				$nv_Request->unset_request( 'admin,online', 'session' );
				$nv_Request->unset_request( 'admin_relogin_redirect', 'session' );

				if( ! empty( $redirect ) and nv_is_myreferer( $redirect ) == 1 )
				{
					$server_name = preg_replace( '/^www\./', '', nv_getenv( 'HTTP_HOST' ) );
					$nohttp_redirect = preg_replace( array( '/^[a-zA-Z]+\:\/\//', '/www\./' ), array( '', '' ), $redirect );
					if( ! preg_match( '/^' . preg_quote( $server_name ) . '\/' . preg_quote( NV_ADMINDIR ) . '/', $nohttp_redirect ) )
					{
						Header( 'Location: ' . $redirect );
						exit();
					}
				}
				Header( 'Location: ' . NV_BASE_SITEURL );
				die();
			}

			$info = ( ! empty( $error ) ) ? '<div class="error">' . sprintf( $lang_global['relogin_error_info'], $error, ( $global_config['adminrelogin_max'] - $check_hits + 1 ) ) . '</div>' : '<div class="normal">' . sprintf( $lang_global['relogin_info'], $global_config['adminrelogin_max'] - $check_hits + 1 ) . '</div>';
			$size = @getimagesize( NV_ROOTDIR . '/' . $global_config['site_logo'] );
			if( $size[0] > 490 )
			{
				$size[1] = ceil( 490 * $size[1] / $size[0] );
				$size[0] = 490;
			}

			$dir_template = '';
			if( file_exists( NV_ROOTDIR . '/themes/' . $global_config['admin_theme'] . '/system/relogin.tpl' ) )
			{
				$dir_template = NV_ROOTDIR . '/themes/' . $global_config['admin_theme'] . '/system';
			}
			else
			{
				$dir_template = NV_ROOTDIR . '/themes/admin_default/system';
				$global_config['admin_theme'] = 'admin_default';
			}
			$xtpl = new XTemplate( 'relogin.tpl', $dir_template );

			$xtpl->assign( 'NV_TITLEBAR_DEFIS', NV_TITLEBAR_DEFIS );
			$xtpl->assign( 'CHARSET', $global_config['site_charset'] );
			$xtpl->assign( 'SITE_NAME', $global_config['site_name'] );
			$xtpl->assign( 'PAGE_TITLE', $lang_global['admin_page'] );
			$xtpl->assign( 'ADMIN_THEME', $global_config['admin_theme'] );
			$xtpl->assign( 'SITELANG', NV_LANG_INTERFACE );
			$xtpl->assign( 'NV_BASE_SITEURL', NV_BASE_SITEURL );
			$xtpl->assign( 'LOGO_SRC', NV_BASE_SITEURL . $global_config['site_logo'] );
			$xtpl->assign( 'LOGO_WIDTH', $size[0] );
			$xtpl->assign( 'LOGO_HEIGHT', $size[1] );
			$xtpl->assign( 'LOGIN_TITLE', $lang_global['adminlogin'] );
			$xtpl->assign( 'LOGIN_INFO', $info );
			$xtpl->assign( 'N_PASSWORD', $lang_global['password'] );
			$xtpl->assign( 'N_SUBMIT', $lang_global['loginsubmit'] );

			$xtpl->assign( 'NV_LOGOUT', $lang_global['admin_logout_title'] );

			$xtpl->parse( 'main' );
			include NV_ROOTDIR . '/includes/header.php';
			$xtpl->out( 'main' );
			include NV_ROOTDIR . '/includes/footer.php';
		}
	}
}