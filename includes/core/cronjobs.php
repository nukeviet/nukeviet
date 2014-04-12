<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @copyright 2010
 * @License GNU/GPL version 2 or any later version
 * @Createdate 1/23/2010 16:10
 */

if( ! defined( 'NV_MAINFILE' ) ) die( 'Stop!!!' );

$cron_result = $db->query( 'SELECT * FROM ' . $db_config['dbsystem'] . '.' . NV_CRONJOBS_GLOBALTABLE . ' WHERE act=1 AND start_time <= ' . NV_CURRENTTIME . ' ORDER BY is_sys DESC' );
while( $cron_row = $cron_result->fetch() )
{
	$cron_allowed = false;
	if( empty( $cron_row['inter_val'] ) )
	{
		$cron_allowed = true;
	}
	else
	{
		$interval = $cron_row['inter_val'] * 60;
		if( $cron_row['last_time'] + $interval < NV_CURRENTTIME )
		{
			$cron_allowed = true;
		}
	}

	if( $cron_allowed )
	{
		if( $sys_info['allowed_set_time_limit'] )
		{
			set_time_limit( 0 );
		}

		if( ! empty( $cron_row['run_file'] ) and preg_match( '/^([a-zA-Z0-9\-\_\.]+)\.php$/', $cron_row['run_file'] ) and file_exists( NV_ROOTDIR . '/includes/cronjobs/' . $cron_row['run_file'] ) )
		{
			if( ! defined( 'NV_IS_CRON' ) ) define( 'NV_IS_CRON', true );
			require_once NV_ROOTDIR . '/includes/cronjobs/' . $cron_row['run_file'];
		}
		if( ! nv_function_exists( $cron_row['run_func'] ) )
		{
			$db->query( 'UPDATE ' . $db_config['dbsystem'] . '.' . NV_CRONJOBS_GLOBALTABLE . ' SET act=0, last_time=' . NV_CURRENTTIME . ', last_result=0 WHERE id=' . $cron_row['id'] );
			continue;
		}

		$check_run_cronjobs = NV_ROOTDIR . '/' . NV_LOGS_DIR . '/data_logs/cronjobs_' . md5( $cron_row['run_file'] . $cron_row['run_func'] . $global_config['sitekey'] ) . '.txt';
		$p = NV_CURRENTTIME - 300;
		if( file_exists( $check_run_cronjobs ) and @filemtime( $check_run_cronjobs ) > $p )
		{
			continue;
		}
		file_put_contents( $check_run_cronjobs, '' );

		$params = ( ! empty( $cron_row['params'] ) ) ? array_map( 'trim', explode( ',', $cron_row['params'] ) ) : array();
		$result2 = call_user_func_array( $cron_row['run_func'], $params );
		if( ! $result2 )
		{
			$db->query( 'UPDATE ' . $db_config['dbsystem'] . '.' . NV_CRONJOBS_GLOBALTABLE . ' SET act=0, last_time=' . NV_CURRENTTIME . ', last_result=0 WHERE id=' . $cron_row['id'] );
		}
		else
		{
			if( $cron_row['del'] )
			{
				$db->query( 'DELETE FROM ' . $db_config['dbsystem'] . '.' . NV_CRONJOBS_GLOBALTABLE . ' WHERE id = ' . $cron_row['id'] );
			}
			elseif( empty( $cron_row['inter_val'] ) )
			{
				$db->query( 'UPDATE ' . $db_config['dbsystem'] . '.' . NV_CRONJOBS_GLOBALTABLE . ' SET act=0, last_time=' . NV_CURRENTTIME . ', last_result=1 WHERE id=' . $cron_row['id'] );
			}
			else
			{
				$db->query( 'UPDATE ' . $db_config['dbsystem'] . '.' . NV_CRONJOBS_GLOBALTABLE . ' SET last_time=' . NV_CURRENTTIME . ', last_result=1 WHERE id=' . $cron_row['id'] );

				$cronjobs_next_time = NV_CURRENTTIME + $interval;
				if( $db->exec( "UPDATE " . NV_CONFIG_GLOBALTABLE . " SET config_value = '" . $cronjobs_next_time . "' WHERE lang = '" . NV_LANG_DATA . "' AND module = 'global' AND config_name = 'cronjobs_next_time' AND (config_value < '" . NV_CURRENTTIME . "' OR config_value > '" . $cronjobs_next_time . "')") )
				{
					nv_del_moduleCache( 'settings' );
				}
			}
		}
		unlink( $check_run_cronjobs );
		clearstatcache();
	}
}


$image = imagecreate( 1, 1 );
Header( 'Content-type: image/jpg' );
imagejpeg( $image, null, 80 );
imagedestroy( $image );
die();