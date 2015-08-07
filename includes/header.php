<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 31/05/2010, 00:36
 */

if( ( ! defined( 'NV_SYSTEM' ) and ! defined( 'NV_ADMIN' ) ) or ! defined( 'NV_MAINFILE' ) ) die( 'Stop!!!' );

global $global_config, $sys_info, $lang_global, $nv_Request;

//Nen trang
if( $sys_info['zlib_support'] and $global_config['gzip_method'] and ini_get( 'output_handler' ) == '' )
{
	if( strtolower( ini_get( 'zlib.output_compression' ) ) == "on" or ini_get( 'zlib.output_compression' ) == 1 )
	{
		if( $sys_info['ini_set_support'] )
		{
			ini_set( 'zlib.output_compression_level', ZLIB_OUTPUT_COMPRESSION_LEVEL );
		}
	}
	else
	{
		define( "NV_IS_GZIP", true );
	}
}

@Header( 'Content-Type: text/html; charset=' . $global_config['site_charset'] );
@Header( 'Content-Language: ' . $lang_global['Content_Language'] );
@Header( 'Last-Modified: ' . gmdate( 'D, d M Y H:i:s', strtotime( '-1 day' ) ) . " GMT" );
@Header( 'Expires: ' . gmdate( 'D, d M Y H:i:s', NV_CURRENTTIME - 60 ) . " GMT" );

@Header( 'X-Frame-Options: SAMEORIGIN' );
@Header( 'X-Content-Type-Options: nosniff' );
@Header( 'X-XSS-Protection: 1; mode=block' );

$server_software = $nv_Request->get_string( 'SERVER_SOFTWARE', 'server', '' );
if( strstr( $server_software, 'Apache/2' ) )
{
	@Header( 'Cache-Control: no-cache, pre-check=0, post-check=0' );
}
else
{
	@Header( 'Cache-Control: private, pre-check=0, post-check=0, max-age=0' );
}

@Header( 'Pragma: no-cache' );

if( preg_match( '/(Googlebot)/i', NV_USER_AGENT ) )
{
	@Header( 'X-Robots-Tag: index,archive,follow,noodp', true );
}

if( strpos( NV_USER_AGENT, 'MSIE' ) !== false )
{
	@header( 'X-UA-Compatible: IE=edge,chrome=1' );
}

ob_start();