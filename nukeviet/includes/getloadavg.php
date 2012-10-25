<?php

/**
 * @Project NUKEVIET 3.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2012 VINADES.,JSC. All rights reserved
 * @Createdate 12/6/2011, 16:39
 */

if( ! defined( 'NV_MAINFILE' ) ) die( 'Stop!!!' );

/**
 * get_server_load()
 * 
 * @return
 */
function get_server_load()
{
	$disable_functions = ( ini_get( "disable_functions" ) != "" and ini_get( "disable_functions" ) != false ) ? array_map( 'trim', preg_split( "/[\s,]+/", ini_get( "disable_functions" ) ) ) : array();
	
	if( extension_loaded( 'suhosin' ) )
	{
		$disable_functions = array_merge( $disable_functions, array_map( 'trim', preg_split( "/[\s,]+/", ini_get( "suhosin.executor.func.blacklist" ) ) ) );
	}

	$os = strtoupper( ( function_exists( 'php_uname' ) and ! in_array( 'php_uname', $disable_functions ) and strtoupper( php_uname( 's' ) ) != '' ) ? php_uname( 's' ) : PHP_OS );

	if( strtolower( substr( $os, 0, 3 ) ) === 'win' )
	{
		if( function_exists( "passthru" ) )
		{
			ob_start();
			passthru( 'typeperf -sc 1 "\processor(_total)\% processor time"', $status );
			$content = ob_get_contents();
			ob_end_clean();
		
			if( $status === 0 )
			{
				if( preg_match( "/\,\"([0-9]+\.[0-9]+)\"/", $content, $load ) )
				{
					return $load[1];
				}
			}
		}

		return 'unknown';
	}

	if( function_exists( "sys_getloadavg" ) )
	{
		$load = sys_getloadavg();
		return $load[0];
	}

	if( @file_exists( '/proc/loadavg' ) )
	{
		$load = @file_get_contents( '/proc/loadavg' );
		$serverload = explode( ' ', $load );
		$serverload[0] = round( $serverload[0], 4 );
		if( ! $serverload )
		{
			$load = @exec( 'uptime' );
			$load = split( 'load averages?: ', $load );
			$serverload = explode( ',', $load[1] );
		}
	}
	else
	{
		$load = @exec( 'uptime' );
		$load = split( 'load averages?: ', $load );
		$serverload = explode( ',', $load[1] );
	}

	$returnload = trim( $serverload[0] );
	
	if( ! $returnload )
	{
		$returnload = 'unknown';
	}

	return $returnload;
}

$load = get_server_load();

if( is_float( $load ) and $load > 80 )
{
	header( 'HTTP/1.1 503 Too busy, try again later' );
	die( 'Server too busy. Please try again later.' );
}

?>