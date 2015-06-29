<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 4/11/2010 20:40
 */

if( ! defined( 'NV_MAINFILE' ) ) die( 'Stop!!!' );

if( ! defined( 'NV_IS_CRON' ) ) die( 'Stop!!!' );

/**
 * cron_auto_del_error_log()
 *
 * @return
 */
function cron_auto_del_error_log()
{
	$result = true;

	$day_mktime = mktime( 0, 0, 0, date( "n", NV_CURRENTTIME ), date( "j", NV_CURRENTTIME ), date( "Y", NV_CURRENTTIME ) );
	$month = date( "m-Y", NV_CURRENTTIME );
	$error_log_fileext = preg_match( "/[a-z]+/i", NV_LOGS_EXT ) ? NV_LOGS_EXT : 'log';

	$error_log_filename = preg_match( "/[a-z0-9\_]+/i", NV_ERRORLOGS_FILENAME ) ? NV_ERRORLOGS_FILENAME : 'error_log';
	$dir = NV_ROOTDIR . '/' . NV_LOGS_DIR . '/error_logs/old';

	if( $dh = opendir( $dir ) )
	{
		while( ( $file = readdir( $dh ) ) !== false )
		{
			if( preg_match( "/^([0-9]{2})\-([0-9]{2})-([0-9]{4})\_(" . $error_log_filename . ")\.(" . $error_log_fileext . ")$/", $file, $m ) )
			{
				$old_day_mktime = mktime( 0, 0, 0, $m[2], $m[1], $m[3] );
				if( $old_day_mktime + 864000 < $day_mktime )
				{
					if( ! @unlink( $dir . '/' . $file ) )
					{
						$result = false;
					}
				}
			}
		}
		closedir( $dh );
	}

	$dir = NV_ROOTDIR . '/' . NV_LOGS_DIR . '/error_logs';

	if( $dh = opendir( $dir ) )
	{
		while( ( $file = readdir( $dh ) ) !== false )
		{
			if( preg_match( "/^([0-9]{2})\-([0-9]{2})-([0-9]{4})\_(" . $error_log_filename . ")\.(" . $error_log_fileext . ")$/", $file, $m ) )
			{
				$old_day_mktime = mktime( 0, 0, 0, $m[2], $m[1], $m[3] );
				if( $old_day_mktime != $day_mktime )
				{
					@rename( $dir . '/' . $file, $dir . '/old/' . $file );
				}
			}
		}
		closedir( $dh );
	}

	$dir = NV_ROOTDIR . '/' . NV_LOGS_DIR . '/error_logs/tmp';

	if( $dh = opendir( $dir ) )
	{
		while( ( $file = readdir( $dh ) ) !== false )
		{
			if( preg_match( "/^([0-9]{2})\-([0-9]{2})-([0-9]{4})\_([a-zA-Z0-9]{32})\.(" . $error_log_fileext . ")$/", $file, $m ) )
			{
				$old_day_mktime = mktime( 0, 0, 0, $m[2], $m[1], $m[3] );
				if( $old_day_mktime < $day_mktime )
				{
					if( ! @unlink( $dir . '/' . $file ) )
					{
						$result = false;
					}
				}
			}
		}
		closedir( $dh );
	}

	$dir = NV_ROOTDIR . '/' . NV_LOGS_DIR . '/error_logs/errors256';

	if( $dh = opendir( $dir ) )
	{
		while( ( $file = readdir( $dh ) ) !== false )
		{
			if( preg_match( "/^([0-9]{2}\-[0-9]{4})\_\_([a-zA-Z0-9]{32})\_\_([a-zA-Z0-9]{32})\.(" . $error_log_fileext . ")$/", $file, $mc ) )
			{
				if( $m[1] != $month )
				{
					if( ! @unlink( $dir . '/' . $file ) )
					{
						$result = false;
					}
				}
			}
		}
		closedir( $dh );
	}

	clearstatcache();
	return $result;
}