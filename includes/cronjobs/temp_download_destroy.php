<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 1-27-2010 5:25
 */

if( ! defined( 'NV_MAINFILE' ) ) die( 'Stop!!!' );

if( ! defined( 'NV_IS_CRON' ) ) die( 'Stop!!!' );

function cron_auto_del_temp_download()
{
	$dir = NV_ROOTDIR . "/" . NV_TEMP_DIR;
	$result = true;

	if( $dh = opendir( $dir ) )
	{
		while( ( $file = readdir( $dh ) ) !== false )
		{
			if( preg_match( "/^(" . nv_preg_quote( NV_TEMPNAM_PREFIX ) . ")[a-zA-Z0-9\_\.]+$/", $file ) )
			{
				if( ( filemtime( $dir . '/' . $file ) + 600 ) < NV_CURRENTTIME )
				{
					if( is_file( $dir . '/' . $file ) )
					{
						if( ! @unlink( $dir . '/' . $file ) )
						{
							$result = false;
						}
					}
					else
					{
						$rt = nv_deletefile( $dir . '/' . $file, true );
						if( $rt[0] == 0 )
						{
							$result = false;
						}
					}
				}
			}
		}

		closedir( $dh );
		clearstatcache();
	}

	return $result;
}