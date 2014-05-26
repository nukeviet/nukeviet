<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 12/30/2009 1:31
 */

if( ! defined( 'NV_MAINFILE' ) ) die( 'Stop!!!' );

if( ! nv_admin_checkip() )
{
	nv_info_die( $global_config['site_description'], $lang_global['site_info'], sprintf( $lang_global['admin_ipincorrect'], NV_CLIENT_IP ) . '<meta http-equiv="Refresh" content="5;URL=' . $global_config['site_url'] . '" />' );
}

if( ! nv_admin_checkfirewall() )
{
	// remove non US-ASCII to respect RFC2616
	$server_message = preg_replace( '/[^\x20-\x7e]/i', '', $lang_global['firewallsystem'] );
	if( empty( $server_message ) )
	{
		$server_message = 'Administrators Section';
	}
	header( 'WWW-Authenticate: Basic realm="' . $server_message . '"' );
	header( NV_HEADERSTATUS . ' 401 Unauthorized' );
	if( php_sapi_name() !== 'cgi-fcgi' )
	{
		header( 'status: 401 Unauthorized' );
	}
	nv_info_die( $global_config['site_description'], $lang_global['site_info'], $lang_global['firewallincorrect'] . '<meta http-equiv="Refresh" content="5;URL=' . $global_config['site_url'] . '" />' );
}

$error = '';
$login = '';
$checkss = md5( $global_config['sitekey'] . $client_info['session_id'] );

$array_gfx_chk = array( 1, 5, 6, 7 );
if( in_array( $global_config['gfx_chk'], $array_gfx_chk ) )
{
	$global_config['gfx_chk'] = 1;
}
else
{
	$global_config['gfx_chk'] = 0;
}
$admin_login_redirect = $nv_Request->get_string( 'admin_login_redirect', 'session', '' );
if( $nv_Request->isset_request( 'nv_login,nv_password', 'post' ) and $nv_Request->get_title( 'checkss', 'post' ) == $checkss)
{
	$nv_username = $nv_Request->get_title( 'nv_login', 'post', '', 1 );
	$nv_password = $nv_Request->get_title( 'nv_password', 'post', '' );
	if( $global_config['gfx_chk'] == 1 )
	{
		$nv_seccode = $nv_Request->get_title( 'nv_seccode', 'post', '' );
	}
	if( empty( $nv_username ) )
	{
		$error = $lang_global['username_empty'];
	}
	elseif( empty( $nv_password ) )
	{
		$error = $lang_global['password_empty'];
	}
	elseif( $global_config['gfx_chk'] == 1 and ! nv_capcha_txt( $nv_seccode ) )
	{
		$error = $lang_global['securitycodeincorrect'];
	}
	else
	{
		if( defined( 'NV_IS_USER_FORUM' ) )
		{
			define( 'NV_IS_MOD_USER', true );
			require_once NV_ROOTDIR . '/' . DIR_FORUM . '/nukeviet/login.php';
			if( empty( $nv_username ) ) $nv_username = $nv_Request->get_title( 'nv_login', 'post', '', 1 );
			if( empty( $nv_password ) ) $nv_password = $nv_Request->get_title( 'nv_password', 'post', '' );
		}

		$userid = 0;
		$row = $db->query( "SELECT userid, username, password FROM " . NV_USERS_GLOBALTABLE . " WHERE md5username ='" . nv_md5safe( $nv_username ) . "'" )->fetch();
		if( empty( $row ) )
		{
			nv_insert_logs( NV_LANG_DATA, 'login', '[' . $nv_username . '] ' . $lang_global['loginsubmit'] . ' ' . $lang_global['fail'], ' Client IP:' . NV_CLIENT_IP, 0 );
		}
		else
		{
			if( $row['username'] == $nv_username and $crypt->validate( $nv_password, $row['password'] ) )
			{
				$userid = $row['userid'];
			}
		}
		$error = $lang_global['loginincorrect'];
		if( $userid > 0 )
		{
			$row = $db->query( 'SELECT t1.admin_id as admin_id, t1.lev as admin_lev, t1.last_agent as admin_last_agent, t1.last_ip as admin_last_ip, t1.last_login as admin_last_login, t2.password as admin_pass FROM ' . NV_AUTHORS_GLOBALTABLE . ' t1 INNER JOIN ' . NV_USERS_GLOBALTABLE . ' t2 ON t1.admin_id = t2.userid WHERE t1.admin_id = ' . $userid . ' AND t1.lev!=0 AND t1.is_suspend=0 AND t2.active=1' )->fetch();
			if( ! empty( $row ) )
			{
				$admin_lev = intval( $row['admin_lev'] );

				if( ! defined( 'ADMIN_LOGIN_MODE' ) ) define( 'ADMIN_LOGIN_MODE', 3 );
				if( ADMIN_LOGIN_MODE == 2 and ! in_array( $admin_lev, array( 1, 2 ) ) )
				{
					$error = $lang_global['admin_access_denied2'];
				}
				elseif( ADMIN_LOGIN_MODE == 1 and $admin_lev != 1 )
				{
					$error = $lang_global['admin_access_denied1'];
				}
				else
				{
					nv_insert_logs( NV_LANG_DATA, 'login', '[' . $nv_username . '] ' . $lang_global['loginsubmit'], ' Client IP:' . NV_CLIENT_IP, 0 );
					$admin_id = intval( $row['admin_id'] );
					$checknum = nv_genpass( 10 );
					$checknum = $crypt->hash( $checknum );
					$array_admin = array(
						'admin_id' => $admin_id,
						'checknum' => $checknum,
						'current_agent' => NV_USER_AGENT,
						'last_agent' => $row['admin_last_agent'],
						'current_ip' => NV_CLIENT_IP,
						'last_ip' => $row['admin_last_ip'],
						'current_login' => NV_CURRENTTIME,
						'last_login' => intval( $row['admin_last_login'] )
					);
					$admin_serialize = serialize( $array_admin );

					$sth = $db->prepare( 'UPDATE ' . NV_AUTHORS_GLOBALTABLE . ' SET check_num = :check_num, last_login = ' . NV_CURRENTTIME . ', last_ip = :last_ip, last_agent = :last_agent WHERE admin_id=' . $admin_id );
					$sth->bindValue( ':check_num', $checknum, PDO::PARAM_STR );
					$sth->bindValue( ':last_ip', NV_CLIENT_IP, PDO::PARAM_STR );
					$sth->bindValue( ':last_agent', NV_USER_AGENT, PDO::PARAM_STR );
					$sth->execute();

					$nv_Request->set_Session( 'admin', $admin_serialize );
					$nv_Request->set_Session( 'online', '1|' . NV_CURRENTTIME . '|' . NV_CURRENTTIME . '|0' );
					define( 'NV_IS_ADMIN', true );

					$redirect = NV_BASE_SITEURL . NV_ADMINDIR;
					if( ! empty( $admin_login_redirect ) )
					{
						$redirect = $admin_login_redirect;
						$nv_Request->unset_request( 'admin_login_redirect', 'session' );
					}
					$error = '';
					nv_info_die( $global_config['site_description'], $lang_global['site_info'], $lang_global['admin_loginsuccessfully'] . " \n <meta http-equiv=\"refresh\" content=\"3;URL=" . $redirect . "\" />" );
					die();
				}

			}
			else
			{
				nv_insert_logs( NV_LANG_DATA, 'login', '[ ' . $nv_username . ' ] ' . $lang_global['loginsubmit'] . ' ' . $lang_global['fail'], ' Client IP:' . NV_CLIENT_IP, 0 );
			}
		}
	}
}
else
{
	if( empty( $admin_login_redirect ) )
	{
		$nv_Request->set_Session( 'admin_login_redirect', $nv_Request->request_uri );
	}
	$nv_username = '';
}

if( file_exists( NV_ROOTDIR . '/language/' . NV_LANG_INTERFACE . '/admin_global.php' ) )
{
	require_once NV_ROOTDIR . '/language/' . NV_LANG_INTERFACE . '/admin_global.php';
}
elseif( file_exists( NV_ROOTDIR . '/language/en/admin_global.php' ) )
{
	require_once NV_ROOTDIR . '/language/en/admin_global.php';
}

$info = ( ! empty( $error ) ) ? '<div class="error">' . $error . '</div>' : '<div class="normal">' . $lang_global['logininfo'] . '</div>';
$size = @getimagesize( NV_ROOTDIR . '/' . $global_config['site_logo'] );

$dir_template = '';
if( file_exists( NV_ROOTDIR . '/themes/' . $global_config['admin_theme'] . '/system/login.tpl' ) )
{
	$dir_template = NV_ROOTDIR . "/themes/" . $global_config['admin_theme'] . "/system";
}
else
{
	$dir_template = NV_ROOTDIR . "/themes/admin_default/system";
	$global_config['admin_theme'] = "admin_default";
}

$xtpl = new XTemplate( "login.tpl", $dir_template );
$xtpl->assign( 'CHARSET', $global_config['site_charset'] );
$xtpl->assign( 'SITE_NAME', $global_config['site_name'] );
$xtpl->assign( 'PAGE_TITLE', $lang_global['admin_page'] );
$xtpl->assign( 'ADMIN_THEME', $global_config['admin_theme'] );
$xtpl->assign( 'SITELANG', NV_LANG_INTERFACE );
$xtpl->assign( 'NV_BASE_SITEURL', NV_BASE_SITEURL );
$xtpl->assign( 'NV_BASE_ADMINURL', NV_BASE_ADMINURL );
$xtpl->assign( 'CHECK_SC', ( $global_config['gfx_chk'] == 1 ) ? 1 : 0 );
$xtpl->assign( 'LOGIN_TITLE', $lang_global['adminlogin'] );
$xtpl->assign( 'LOGIN_INFO', $info );
$xtpl->assign( 'N_LOGIN', $lang_global['username'] );
$xtpl->assign( 'N_PASSWORD', $lang_global['password'] );
$xtpl->assign( 'SITEURL', $global_config['site_url'] );
$xtpl->assign( 'N_SUBMIT', $lang_global['loginsubmit'] );
$xtpl->assign( 'NV_COOKIE_PREFIX', $global_config['cookie_prefix'] );
$xtpl->assign( 'CHECKSS', $checkss );
$xtpl->assign( 'NV_TITLEBAR_DEFIS', NV_TITLEBAR_DEFIS );

$xtpl->assign( 'LOGIN_ERROR_SECURITY', addslashes( sprintf( $lang_global['login_error_security'], NV_GFX_NUM ) ) );

$xtpl->assign( 'V_LOGIN', $nv_username );
$xtpl->assign( 'LANGINTERFACE', $lang_global['langinterface'] );

if( isset( $size[1] ) )
{
	if( $size[0] > 490 )
	{
		$size[1] = ceil( 490 * $size[1] / $size[0] );
		$size[0] = 490;
	}
	$xtpl->assign( 'LOGO', NV_BASE_SITEURL . $global_config['site_logo'] );
	$xtpl->assign( 'WIDTH', $size[0] );
	$xtpl->assign( 'HEIGHT', $size[1] );

	if( isset( $size['mime'] ) and $size['mime'] == 'application/x-shockwave-flash' )
	{
		$xtpl->parse( 'main.swf' );
	}
	else
	{
		$xtpl->parse( 'main.image' );
	}
}
$xtpl->assign( 'LANGLOSTPASS', $lang_global['lostpass'] );
$xtpl->assign( 'LINKLOSTPASS', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . $global_config['site_lang'] . '&amp;' . NV_NAME_VARIABLE . '=users&amp;' . NV_OP_VARIABLE . '=lostpass' );

if( $global_config['gfx_chk'] == 1 )
{
	$xtpl->assign( 'CAPTCHA_REFRESH', $lang_global['captcharefresh'] );
	$xtpl->assign( 'CAPTCHA_REFR_SRC', NV_BASE_SITEURL . 'images/refresh.png' );
	$xtpl->assign( 'N_CAPTCHA', $lang_global['securitycode'] );
	$xtpl->assign( 'GFX_NUM', NV_GFX_NUM );
	$xtpl->assign( 'GFX_WIDTH', NV_GFX_WIDTH );
	$xtpl->assign( 'GFX_HEIGHT', NV_GFX_HEIGHT );
	$xtpl->parse( 'main.captcha' );
}
if( $global_config['lang_multi'] == 1 )
{
	foreach( $global_config['allow_adminlangs'] as $lang_i )
	{
		if( file_exists( NV_ROOTDIR . '/language/' . $lang_i . '/global.php' ) and file_exists( NV_ROOTDIR . '/language/' . $lang_i . '/admin_global.php' ) )
		{
			$xtpl->assign( 'LANGOP', NV_BASE_ADMINURL . 'index.php?langinterface=' . $lang_i );
			$xtpl->assign( 'LANGTITLE', $lang_global['langinterface'] );
			$xtpl->assign( 'SELECTED', ( $lang_i == NV_LANG_INTERFACE ) ? "selected='selected'" : "" );
			$xtpl->assign( 'LANGVALUE', $language_array[$lang_i]['name'] );
			$xtpl->parse( 'main.lang_multi.option' );
		}
	}
	$xtpl->parse( 'main.lang_multi' );
}
$xtpl->parse( 'main' );

$global_config['mudim_active'] = 0;

include NV_ROOTDIR . '/includes/header.php';
$xtpl->out( 'main' );
include NV_ROOTDIR . '/includes/footer.php';