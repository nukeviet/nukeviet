<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 4/13/2010 2:24
 */

if( ! defined( 'NV_MAINFILE' ) ) die( 'Stop!!!' );

if( ! defined( 'NV_IS_CRON' ) ) die( 'Stop!!!' );

/**
 * cron_auto_sendmail_error_log()
 *
 * @return
 */
function cron_auto_sendmail_error_log()
{
	global $global_config, $lang_global;

	$result = true;

	$error_log_fileext = preg_match( "/[a-z]+/i", NV_LOGS_EXT ) ? NV_LOGS_EXT : 'log';
	$file = NV_ROOTDIR . '/' . NV_LOGS_DIR . '/error_logs/sendmail.' . $error_log_fileext;

	if( file_exists( $file ) and filesize( $file ) > 0 )
	{
		$result = nv_sendmail( array( $global_config['site_name'], $global_config['site_email'] ), $global_config['error_send_email'], sprintf( $lang_global['error_sendmail_subject'], $global_config['site_name'] ), $lang_global['error_sendmail_content'], $file );

		if( $result )
		{
			if( ! @unlink( $file ) )
			{
				$result = false;
			}
		}
	}

	return $result;
}