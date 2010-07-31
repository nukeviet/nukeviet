<?php

/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES.,JSC. All rights reserved
 * @Createdate 1-27-2010 5:25
 */

if ( ! defined( 'NV_MAINFILE' ) ) die( 'Stop!!!' );

if ( ! defined( 'NV_IS_CRON' ) ) die( 'Stop!!!' );

function cron_auto_del_temp_download()
{
	$dir = NV_ROOTDIR . "/" . NV_TEMP_DIR;
	$files = nv_scandir( $dir, "/^(" . nv_preg_quote( NV_TEMPNAM_PREFIX ) . ")[a-zA-Z0-9\_\.]+$/" );
	$result = true;

	if ( ! empty( $files ) )
	{
		foreach ( $files as $file )
		{
			if ( ( filemtime( $dir . '/' . $file ) + 600 ) < NV_CURRENTTIME )
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