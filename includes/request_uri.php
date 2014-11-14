<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES ., JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Apr 5, 2011 11:29:47 AM
 */

if( ! defined( 'NV_MAINFILE' ) ) die( 'Stop!!!' );

$base_siteurl = pathinfo( $_SERVER['PHP_SELF'], PATHINFO_DIRNAME );

if( $base_siteurl == DIRECTORY_SEPARATOR ) $base_siteurl = '';

if( ! empty( $base_siteurl ) ) $base_siteurl = str_replace( DIRECTORY_SEPARATOR, '/', $base_siteurl );
if( ! empty( $base_siteurl ) ) $base_siteurl = preg_replace( '/[\/]+$/', '', $base_siteurl );
if( ! empty( $base_siteurl ) ) $base_siteurl = preg_replace( '/^[\/]*(.*)$/', '/\\1', $base_siteurl );
if( ! empty( $base_siteurl ) ) $base_siteurl = preg_replace( '#/index\.php(.*)$#', '', $base_siteurl );

$base_siteurl .= '/';
$base_siteurl_quote = nv_preg_quote( $base_siteurl );

$request_uri = preg_replace( '/(' . $base_siteurl_quote . ')index\.php\//', '\\1', $_SERVER['REQUEST_URI'] );

if( $global_config['rewrite_endurl'] != $global_config['rewrite_exturl'] and preg_match( '/^' . $base_siteurl_quote . '([a-z0-9\-]+)' . nv_preg_quote( $global_config['rewrite_exturl'] ) . '$/i', $request_uri, $matches ) )
{
	$_GET[NV_NAME_VARIABLE] = 'page';
	$_GET[NV_OP_VARIABLE] = $matches[1];
}
elseif( preg_match( '/^' . $base_siteurl_quote . '([a-z0-9\-\_\.\/\+]+)(' . nv_preg_quote( $global_config['rewrite_endurl'] ) . '|' . nv_preg_quote( $global_config['rewrite_exturl'] ) . ')$/i', $request_uri, $matches ) )
{
	if( $matches[2] == $global_config['rewrite_exturl'] ) define( 'NV_REWRITE_EXTURL', true );

	$request_uri_array = explode( '/', $matches[1], 3 );

	if( in_array( $request_uri_array[0], array_keys( $language_array ) ) )
	{
		$_GET[NV_LANG_VARIABLE] = $request_uri_array[0];

		if( isset( $request_uri_array[1]{0} ) )
		{
			$_GET[NV_NAME_VARIABLE] = $request_uri_array[1];

			if( isset( $request_uri_array[2]{0} ) )
			{
				$_GET[NV_OP_VARIABLE] = $request_uri_array[2];
			}
		}
	}
	elseif( isset( $request_uri_array[0]{0} ) )
	{
		$_GET[NV_NAME_VARIABLE] = $request_uri_array[0];

		if( isset( $request_uri_array[1]{0} ) )
		{
			$lop = strlen( $request_uri_array[0] ) + 1;
			$_GET[NV_OP_VARIABLE] = substr( $matches[1], $lop );
		}
	}
}
elseif( preg_match( '/<(.*)s(.*)c(.*)r(.*)i(.*)p(.*)t(.*)>/i', urldecode( $request_uri ) ) )
{
	header( 'HTTP/1.1 301 Moved Permanently' );
	Header( 'Location: ' . $base_siteurl );
	die();
}
elseif( isset( $_GET[NV_OP_VARIABLE] ) )
{
	if( preg_match( '/([a-z0-9\-\_\.\/]+)' . nv_preg_quote( $global_config['rewrite_exturl'] ) . '$/i', $_GET[NV_OP_VARIABLE], $matches ) )
	{
		$_GET[NV_OP_VARIABLE] = $matches[1];

		define( 'NV_REWRITE_EXTURL', true );
	}
}
else
{
	if( preg_match( '/^(' . $base_siteurl_quote . '([a-z0-9\-\_\.\/]+)(' . nv_preg_quote( $global_config['rewrite_endurl'] ) . '|' . nv_preg_quote( $global_config['rewrite_exturl'] ) . '))\?(.*)$/i', $request_uri, $matches ) )
	{
		header( 'HTTP/1.1 301 Moved Permanently' );
		Header( 'Location: ' . $matches[1] );
		die();
	}
	elseif( ! empty( $global_config['rewrite_op_mod'] ) and preg_match( '/^' . $base_siteurl_quote . 'tag\/([^\'\?\&]+)$/i', $request_uri, $matches ) )
	{
		$_GET[NV_NAME_VARIABLE] = $global_config['rewrite_op_mod'];
		$_GET[NV_OP_VARIABLE] = 'tag';
		$_GET['alias'] = urldecode( $matches[1] );
	}
	elseif( $global_config['rewrite_optional'] and preg_match( '/^' . $base_siteurl_quote . '([a-z0-9\-]+)\/tag\/([^\'\?\&]+)$/i', $request_uri, $matches ) )
	{
		$_GET[NV_NAME_VARIABLE] = $matches[1];
		$_GET[NV_OP_VARIABLE] = 'tag';
		$_GET['alias'] = urldecode( $matches[2] );
	}
	elseif( preg_match( '/^' . $base_siteurl_quote . '([a-z]{2}+)\/([a-z0-9\-]+)\/tag\/([^\'\?\&]+)$/i', $request_uri, $matches ) )
	{
		$_GET[NV_LANG_VARIABLE] = $matches[1];
		$_GET[NV_NAME_VARIABLE] = $matches[2];
		$_GET[NV_OP_VARIABLE] = 'tag';
		$_GET['alias'] = urldecode( $matches[3] );
	}
}
unset( $base_siteurl, $request_uri, $request_uri_array, $matches, $lop );