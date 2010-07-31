<?php

/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @copyright 2010
 * @createdate 1/23/2010 16:10
 */

if ( ! defined( 'NV_MAINFILE' ) ) die( 'Stop!!!' );

if ( $sys_info['allowed_set_time_limit'] )
{
	set_time_limit( 0 );
}

$cron_query = "SELECT * FROM `" . NV_CRONJOBS_GLOBALTABLE . "` WHERE `act`=1 AND `start_time` <= '" . NV_CURRENTTIME . "' ORDER BY `is_sys` DESC";
$cron_result = $db->sql_query( $cron_query );
$cron_numrows = $db->sql_numrows( $cron_result );

if ( ! empty( $cron_numrows ) )
{
	while ( $cron_row = $db->sql_fetchrow( $cron_result ) )
	{
		$cron_allowed = false;
		if ( empty( $cron_row['interval'] ) )
		{
			$cron_allowed = true;
		}
		else
		{
			$interval = $cron_row['interval'] * 60;
			$current_time = $cron_row['start_time'] + floor( ( NV_CURRENTTIME - $cron_row['start_time'] ) / $interval ) * $interval;
			if ( $cron_row['last_time'] < $current_time )
			{
				$cron_allowed = true;
			}
		}

		if ( $cron_allowed )
		{
            if ( ! defined( 'NV_IS_CRON' ) ) define( "NV_IS_CRON", true );
			if ( ! empty( $cron_row['run_file'] ) and preg_match( "/^([a-zA-Z0-9\_\.]+)\.php$/", $cron_row['run_file'] ) and file_exists( NV_ROOTDIR . '/includes/cronjobs/' . $cron_row['run_file'] ) )
			{
				require_once ( NV_ROOTDIR . '/includes/cronjobs/' . $cron_row['run_file'] );
			}
			if ( ! function_exists( $cron_row['run_func'] ) )
			{
				$sql = "UPDATE `" . NV_CRONJOBS_GLOBALTABLE . "` SET `act`=0, `last_time`=" . NV_CURRENTTIME . ", `last_result`=0 WHERE `id`=" . $cron_row['id'];
				$db->sql_query( $sql );
				continue;
			}
			$params = ( ! empty( $cron_row['params'] ) ) ? array_map( "trim", explode( ",", $cron_row['params'] ) ) : array();
			$result2 = call_user_func_array( $cron_row['run_func'], $params );
			if ( ! $result2 )
			{
				$sql = "UPDATE `" . NV_CRONJOBS_GLOBALTABLE . "` SET `act`=0, `last_time`=" . NV_CURRENTTIME . ", `last_result`=0 WHERE `id`=" . $cron_row['id'];
				$db->sql_query( $sql );
			}
			else
			{
				if ( $cron_row['del'] )
				{
					$sql = "DELETE FROM `" . NV_CRONJOBS_GLOBALTABLE . "` WHERE `id` = " . $cron_row['id'];
					$db->sql_query( $sql );
				} elseif ( empty( $cron_row['interval'] ) )
				{
					$sql = "UPDATE `" . NV_CRONJOBS_GLOBALTABLE . "` SET `act`=0, `last_time`=" . NV_CURRENTTIME . ", `last_result`=1 WHERE `id`=" . $cron_row['id'];
					$db->sql_query( $sql );
				}
				else
				{
					$sql = "UPDATE `" . NV_CRONJOBS_GLOBALTABLE . "` SET `last_time`=" . NV_CURRENTTIME . ", `last_result`=1 WHERE `id`=" . $cron_row['id'];
					$db->sql_query( $sql );
				}
			}
		}
	}
}

$image = imagecreate( 1, 1 );
Header( "Content-type: image/jpg" );
ImageJPEG( $image, '', 0 );
ImageDestroy( $image );
die();

?>