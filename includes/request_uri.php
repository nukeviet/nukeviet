<?php

/**
 * @Project NUKEVIET 3.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2012 VINADES ., JSC. All rights reserved
 * @Createdate Apr 5, 2011  11:29:47 AM
 */

if( ! defined( 'NV_MAINFILE' ) ) die( 'Stop!!!' );

$base_siteurl = pathinfo( $_SERVER['PHP_SELF'], PATHINFO_DIRNAME );

if( $base_siteurl == DIRECTORY_SEPARATOR ) $base_siteurl = '';

if( ! empty( $base_siteurl ) ) $base_siteurl = str_replace( DIRECTORY_SEPARATOR, '/', $base_siteurl );
if( ! empty( $base_siteurl ) ) $base_siteurl = preg_replace( "/[\/]+$/", '', $base_siteurl );
if( ! empty( $base_siteurl ) ) $base_siteurl = preg_replace( "/^[\/]*(.*)$/", '/\\1', $base_siteurl );
if( ! empty( $base_siteurl ) ) $base_siteurl = preg_replace( "#/index\.php(.*)$#", '', $base_siteurl );

$base_siteurl .= "/";

$request_uri = preg_replace( "/(" . nv_preg_quote( $base_siteurl ) . ")index\.php\//", "\\1", $_SERVER['REQUEST_URI'] );

if( preg_match( "/^" . nv_preg_quote( $base_siteurl ) . "([a-z0-9\-\_\.\/]+)(" . nv_preg_quote( $global_config['rewrite_endurl'] ) . "|" . nv_preg_quote( $global_config['rewrite_exturl'] ) . ")$/i", $request_uri, $matches ) )
{
	if( $matches[2] == $global_config['rewrite_exturl'] ) define( 'NV_REWRITE_EXTURL', true );

	$request_uri_array = explode( "/", $matches[1], 3 );

	if( in_array( $request_uri_array[0], array_keys( $language_array ) ) )
	{
		$_GET[NV_LANG_VARIABLE] = $request_uri_array[0];
	
		if( isset( $request_uri_array[1] ) and ! empty( $request_uri_array[1] ) )
		{
			$_GET[NV_NAME_VARIABLE] = $request_uri_array[1];
		
			if( isset( $request_uri_array[2] ) and ! empty( $request_uri_array[2] ) )
			{
				$_GET[NV_OP_VARIABLE] = $request_uri_array[2];
			}
		}
	}
	elseif( ! empty( $request_uri_array[0] ) )
	{
		$_GET[NV_NAME_VARIABLE] = $request_uri_array[0];
	
		if( isset( $request_uri_array[1] ) and ! empty( $request_uri_array[1] ) )
		{
			$lop = strlen( $request_uri_array[0] ) + 1;
			$_GET[NV_OP_VARIABLE] = substr( $matches[1], $lop );
		}
	}
}
elseif( preg_match( "/<(.*)s(.*)c(.*)r(.*)i(.*)p(.*)t(.*)>/i", urldecode( $request_uri ) ) )
{
	header( 'HTTP/1.1 301 Moved Permanently' );
	Header( "Location: " . $base_siteurl );
	die();
}
elseif( isset( $_GET[NV_OP_VARIABLE] ) )
{
	if( preg_match( "/([a-z0-9\-\_\.\/]+)" . nv_preg_quote( $global_config['rewrite_exturl'] ) . "$/i", $_GET[NV_OP_VARIABLE], $matches ) )
	{
		$_GET[NV_OP_VARIABLE] = $matches[1];
	
		define( 'NV_REWRITE_EXTURL', true );
	}
}

unset( $base_siteurl, $request_uri, $request_uri_array, $matches, $lop );

?>