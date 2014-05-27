<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 12/29/2009 2:39
 */

if( ! defined( 'NV_MAINFILE' ) ) die( 'Stop!!!' );

$admin_cookie = $nv_Request->get_string( 'admin', 'session' );
$admin_online = $nv_Request->get_string( 'online', 'session' );

if( ! empty( $admin_cookie ) )
{
	if( empty( $admin_online ) )
	{
		$nv_Request->unset_request( 'admin,online', 'session' );
		$info = 'Hacking attempt';
		$info .= '<meta http-equiv="Refresh" content="5;URL=' . NV_BASE_SITEURL . '" />';
		die( $info );
	}

	if( ! nv_admin_checkip() )
	{
		$nv_Request->unset_request( 'admin,online', 'session' );
		$info = 'Note: You are not signed in as admin!<br />Your IP address is incorrect!';
		$info .= '<meta http-equiv="Refresh" content="5;URL=' . NV_BASE_SITEURL . '" />';
		die( $info );
	}

	if( defined( 'NV_ADMIN' ) )
	{
		if( ! nv_admin_checkfirewall() )
		{
			$nv_Request->unset_request( 'admin,online', 'session' );
			$info = 'Note: You are not signed in as admin!<br />This Firewall system does not accept your login information!';
			$info .= '<meta http-equiv="Refresh" content="5;URL=' . NV_BASE_SITEURL . '" />';
			die( $info );
		}
	}

	$admin_info = nv_admin_checkdata( $admin_cookie );

	if( $admin_info == array() )
	{
		$nv_Request->unset_request( 'admin,online', 'session' );
		$info = 'Note: You are not signed in as admin!<br />Session Expired! Please Re-Login!';
		$info .= '<meta http-equiv="Refresh" content="5;URL=' . NV_BASE_SITEURL . '" />';
		die( $info );
	}

	//Admin thoat
	if( $nv_Request->isset_request( 'second', 'get' ) and $nv_Request->get_string( 'second', 'get' ) == 'admin_logout' )
	{
		if( defined( 'NV_IS_USER_FORUM' ) )
		{
			define( 'NV_IS_MOD_USER', true );
			require_once NV_ROOTDIR . '/' . DIR_FORUM . '/nukeviet/logout.php';
		}
		else
		{
			$nv_Request->unset_request( 'nvloginhash', 'cookie' );
		}
		require_once NV_ROOTDIR . '/includes/core/admin_logout.php';
	}

	define( 'NV_IS_ADMIN', true );
	$admin_info['in_groups'][] = 3;

	if( $admin_info['level'] == 1 or $admin_info['level'] == 2 )
	{
		define( 'NV_IS_SPADMIN', true );
		$admin_info['in_groups'][] = 2;
	}

	if( $admin_info['level'] == 1 and $global_config['idsite'] == 0 )
	{
		define( 'NV_IS_GODADMIN', true );
		$admin_info['in_groups'][] = 1;
	}

	if( ! defined( 'ADMIN_LOGIN_MODE' ) ) define( 'ADMIN_LOGIN_MODE', 3 );
	if( ADMIN_LOGIN_MODE == 2 and ! defined( 'NV_IS_SPADMIN' ) )
	{
		$nv_Request->unset_request( 'admin,online', 'session' );
		$info = 'Note: Access denied in Admin Panel!<br />Only God-Admin and Super-Admin has access in Admin Panel!';
		$info .= '<meta http-equiv="Refresh" content="5;URL=' . NV_BASE_SITEURL . '" />';
		die( $info );
	}

	if( ADMIN_LOGIN_MODE == 1 and ! defined( 'NV_IS_GODADMIN' ) )
	{
		$nv_Request->unset_request( 'admin,online', 'session' );
		$info = 'Note: Access denied in Admin Panel!<br />Only God-Admin has access in Admin Panel!';
		$info .= '<meta http-equiv="Refresh" content="5;URL=' . NV_BASE_SITEURL . '" />';
		die( $info );
	}

	if( ! empty( $admin_info['editor'] ) )
	{
		if( file_exists( NV_ROOTDIR . '/' . NV_EDITORSDIR . '/' . $admin_info['editor'] . '/nv.php' ) )
		{
			if( ! defined( 'NV_EDITOR' ) ) define( 'NV_EDITOR', $admin_info['editor'] );
			if( ! defined( 'NV_IS_' . strtoupper( $admin_info['editor'] ) ) ) define( 'NV_IS_' . strtoupper( $admin_info['editor'] ), true );
		}
	}

	if( ! empty( $admin_info['allow_files_type'] ) )
	{
		if( ! defined( 'NV_ALLOW_FILES_TYPE' ) ) define( 'NV_ALLOW_FILES_TYPE', implode( '|', array_intersect( $global_config['file_allowed_ext'], $admin_info['allow_files_type'] ) ) );
		if( ! defined( 'NV_ALLOW_UPLOAD_FILES' ) ) define( 'NV_ALLOW_UPLOAD_FILES', true );
	}

	if( ! empty( $admin_info['allow_modify_files'] ) )
	{
		if( ! defined( 'NV_ALLOW_MODIFY_FILES' ) ) define( 'NV_ALLOW_MODIFY_FILES', true );
	}

	if( ! empty( $admin_info['allow_create_subdirectories'] ) )
	{
		if( ! defined( 'NV_ALLOW_CREATE_SUBDIRECTORIES' ) ) define( 'NV_ALLOW_CREATE_SUBDIRECTORIES', true );
	}

	if( ! empty( $admin_info['allow_modify_subdirectories'] ) )
	{
		if( ! defined( 'NV_ALLOW_MODIFY_SUBDIRECTORIES' ) ) define( 'NV_ALLOW_MODIFY_SUBDIRECTORIES', true );
	}

	$admin_online = explode( '|', $admin_online );
	$admin_info['checkpass'] = intval( $admin_online[0] );
	$admin_info['last_online'] = intval( $admin_online[2] );
	$admin_info['checkhits'] = intval( $admin_online[3] );
	if( $admin_info['checkpass'] )
	{
		if( ( NV_CURRENTTIME - $admin_info['last_online'] ) > $global_config['admin_check_pass_time'] ) $admin_info['checkpass'] = 0;
	}

	$nv_Request->set_Session( 'online', $admin_info['checkpass'] . '|' . $admin_info['last_online'] . '|' . NV_CURRENTTIME . '|' . $admin_info['checkhits'] );

	if( empty( $admin_info['checkpass'] ) )
	{
		if( ! $nv_Request->isset_request( NV_ADMINRELOGIN_VARIABLE, 'get' ) or $nv_Request->get_int( NV_ADMINRELOGIN_VARIABLE, 'get' ) != 1 )
		{
			// check selfurl cronjobs
			if( $nv_Request->get_string( 'second', 'get' ) == 'cronjobs' )
			{
				$client_info['selfurl'] = $nv_Request->my_current_domain . NV_BASE_ADMINURL;
			}

			$nv_Request->set_Session( 'admin_relogin_redirect', $client_info['selfurl'] );
			Header( 'Location: ' . $global_config['site_url'] . '/index.php?' . NV_ADMINRELOGIN_VARIABLE . '=1' );
			exit();
		}
	}
}

unset( $admin_cookie, $admin_online );