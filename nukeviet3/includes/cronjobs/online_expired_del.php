<?php

/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES.,JSC. All rights reserved
 * @Createdate 1-27-2010 5:25
 */

if ( ! defined( 'NV_MAINFILE' ) ) die( 'Stop!!!' );

if ( ! defined( 'NV_IS_CRON' ) ) die( 'Stop!!!' );

function cron_online_expired_del()
{
	global $db;

	$query = "DELETE FROM `" . NV_SESSIONS_GLOBALTABLE . "` WHERE `onl_time` < " . NV_DEL_ONLINE_TIME;
	$db->sql_query( $query );
	return true;
}

?>