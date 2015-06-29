<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 3/27/2010 5:16
 */

if( ! defined( 'NV_MAINFILE' ) ) die( 'Stop!!!' );

if( ! defined( 'NV_IS_CRON' ) ) die( 'Stop!!!' );

/**
 * cron_del_ip_logs()
 *
 * @return
 */
function cron_del_ip_logs()
{
	$result = true;
	$dir = NV_ROOTDIR . '/' . NV_LOGS_DIR . '/ip_logs';

	if( $dh = opendir( $dir ) )
	{
		while( ( $file = readdir( $dh ) ) !== false )
		{
			if( preg_match( "/^([0-9\-]+)\.log$/", $file ) and ( filemtime( $dir . '/' . $file ) + 7200 ) < NV_CURRENTTIME ) //2 gio
			{
				if( ! @unlink( $dir . '/' . $file ) )
				{
					$result = false;
				}
			}
		}

		closedir( $dh );
		clearstatcache();
	}

	return $result;
}