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
	$_query = $db->sql_query( "SELECT `session_id` FROM `" . NV_SESSIONS_GLOBALTABLE . "` WHERE `onl_time` < " . (NV_CURRENTTIME - NV_ONLINE_UPD_TIME) );
	while( $row = $db->sql_fetch_assoc( $_query ) )
	{
		nv_deletefile( NV_ROOTDIR . "/" . NV_SESSION_SAVE_PATH . "/sess_" . $row['session_id'] );
	}
	$db->sql_query( "DELETE FROM `" . NV_SESSIONS_GLOBALTABLE . "` WHERE `onl_time` < " . (NV_CURRENTTIME - NV_ONLINE_UPD_TIME) );
	return true;
}

?>