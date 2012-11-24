<?php

/**
 * @Project NUKEVIET 3.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2012 VINADES.,JSC. All rights reserved
 * @Createdate 1-27-2010 5:25
 */

if ( ! defined( 'NV_MAINFILE' ) ) die( 'Stop!!!' );

if ( ! defined( 'NV_IS_CRON' ) ) die( 'Stop!!!' );

/**
 * cron_online_expired_del()
 * 
 * @return
 */
function cron_online_expired_del()
{
	global $db;

	$query = "DELETE FROM `" . NV_SESSIONS_GLOBALTABLE . "` WHERE `onl_time` < " . (NV_CURRENTTIME - NV_ONLINE_UPD_TIME);
	$db->sql_query( $query );
	return true;
}

?>