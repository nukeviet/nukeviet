<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 31/05/2010, 00:36
 */

if( ! defined( 'NV_MAINFILE' ) ) die( 'Stop!!!' );

if( empty( $global_config['site_lang'] ) or ! preg_match( '/^[a-z]{2}$/', $global_config['site_lang'] ) or ! file_exists( NV_ROOTDIR . '/language/' . $global_config['site_lang'] . '/global.php' ) )
{
	if( ! file_exists( NV_ROOTDIR . '/language/en/global.php' ) ) trigger_error( 'Error! Lang file is absent!', 256 );
	$global_config['site_lang'] = 'en';
}
if( empty( $global_config['lang_multi'] ) )
{
	define( 'NV_LANG_INTERFACE', $global_config['site_lang'] );
	define( 'NV_LANG_DATA', $global_config['site_lang'] );
}
elseif( defined( 'NV_ADMIN' ) )
{
	$cookie = $nv_Request->get_string( 'data_lang', 'cookie' );
	$site_lang = $nv_Request->get_string( NV_LANG_VARIABLE, 'get,post' );

	if( preg_match( '/^[a-z]{2}$/', $site_lang ) and file_exists( NV_ROOTDIR . '/language/' . $site_lang . '/global.php' ) )
	{
		if( $site_lang != $cookie ) $nv_Request->set_Cookie( 'data_lang', $site_lang, NV_LIVE_COOKIE_TIME );

		define( 'NV_LANG_DATA', $site_lang );
	}
	elseif( preg_match( '/^[a-z]{2}$/', $cookie ) and file_exists( NV_ROOTDIR . '/language/' . $cookie . '/global.php' ) )
	{
		define( 'NV_LANG_DATA', $cookie );
	}
	else
	{
		$nv_Request->set_Cookie( 'data_lang', $global_config['site_lang'], NV_LIVE_COOKIE_TIME );

		define( 'NV_LANG_DATA', $global_config['site_lang'] );
	}

	$cookie = $nv_Request->get_string( 'int_lang', 'cookie' );
	$langinterface = $nv_Request->get_string( 'langinterface', 'get,post', '' );

	if( preg_match( '/^[a-z]{2}$/', $langinterface ) and file_exists( NV_ROOTDIR . '/language/' . $langinterface . '/global.php' ) )
	{
		if( $langinterface != $cookie ) $nv_Request->set_Cookie( 'int_lang', $langinterface, NV_LIVE_COOKIE_TIME );

		define( 'NV_LANG_INTERFACE', $langinterface );
	}
	elseif( preg_match( '/^[a-z]{2}$/', $cookie ) and file_exists( NV_ROOTDIR . '/language/' . $cookie . '/global.php' ) )
	{
		define( 'NV_LANG_INTERFACE', $cookie );
	}
	else
	{
		$nv_Request->set_Cookie( 'int_lang', $global_config['site_lang'], NV_LIVE_COOKIE_TIME );

		define( 'NV_LANG_INTERFACE', $global_config['site_lang'] );
	}

	unset( $cookie, $site_lang, $langinterface );
}
else
{
	$cookie = $nv_Request->get_string( 'u_lang', 'cookie' );
	$site_lang = $nv_Request->get_string( NV_LANG_VARIABLE, 'get,post' );

	if( ! empty( $site_lang ) and ( in_array( $site_lang, $global_config['allow_sitelangs'] ) ) and file_exists( NV_ROOTDIR . '/language/' . $site_lang . '/global.php' ) )
	{
		if( $site_lang != $cookie ) $nv_Request->set_Cookie( 'u_lang', $site_lang, NV_LIVE_COOKIE_TIME );
	}
	elseif( preg_match( '/^[a-z]{2}$/', $cookie ) and ( in_array( $cookie, $global_config['allow_sitelangs'] ) ) and file_exists( NV_ROOTDIR . '/language/' . $cookie . '/global.php' ) )
	{
		$site_lang = $cookie;
	}
	else
	{
		$site_lang = $global_config['site_lang'];

		if( $global_config['lang_geo'] )
		{
			$config_geo = array();

			include NV_ROOTDIR . '/' . NV_DATADIR . '/config_geo.php' ;

			if( isset( $config_geo[$client_info['country']] ) )
			{
				$site_lang = $config_geo[$client_info['country']];
			}
		}

		$nv_Request->set_Cookie( 'u_lang', $site_lang, NV_LIVE_COOKIE_TIME );
	}

	define( 'NV_LANG_INTERFACE', $site_lang );
	define( 'NV_LANG_DATA', $site_lang );
	unset( $cookie, $site_lang );
}