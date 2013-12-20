<?php

/**
 * @Project NUKEVIET 3.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2012 VINADES.,JSC. All rights reserved
 * @Createdate 1-27-2010 5:25
 */

if( ! defined( 'NV_MAINFILE' ) ) die( 'Stop!!!' );

if( ! defined( 'NV_IS_CRON' ) ) die( 'Stop!!!' );

/**
 * cron_online_expired_del()
 *
 * @return
 */
function cron_online_expired_del()
{
	global $db;
	$db->exec( 'DELETE FROM `' . NV_SESSIONS_GLOBALTABLE . '` WHERE `onl_time` < ' . (NV_CURRENTTIME - NV_ONLINE_UPD_TIME) );

	$dir = NV_ROOTDIR . '/' . NV_SESSION_SAVE_PATH;
	if( $dh = opendir( $dir ) )
	{
		$timedel = NV_CURRENTTIME - 86400;
		while( ($file = readdir( $dh )) !== false )
		{
			if( preg_match( '/^sess\_([a-z0-9]+)$/', $file ) AND filemtime( $dir . '/' . $file ) < $timedel )
			{
				unlink( $dir . '/' . $file );
			}
		}
		closedir( $dh );
	}
	return true;
}

?>