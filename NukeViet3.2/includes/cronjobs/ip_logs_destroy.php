<?php

/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES., JSC. All rights reserved
 * @Createdate 3/27/2010 5:16
 */

if ( ! defined( 'NV_MAINFILE' ) ) die( 'Stop!!!' );

if ( ! defined( 'NV_IS_CRON' ) ) die( 'Stop!!!' );

function cron_del_ip_logs()
{
	$dir = NV_ROOTDIR . '/' . NV_LOGS_DIR . '/ip_logs';
	$files = nv_scandir( $dir, "/^([0-9]+)\." . NV_LOGS_EXT . "$/" );
	$result = true;

	if ( ! empty( $files ) )
	{
		foreach ( $files as $file )
		{
			if ( ( filemtime( $dir . '/' . $file ) + 7200 ) < NV_CURRENTTIME )//2 gio
			{
				if ( ! @unlink( $dir . '/' . $file ) )
				{
					$result = false;
				}
			}
			clearstatcache();
		}
	}

	return $result;
}

?>