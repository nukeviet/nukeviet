<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 2-2-2010 12:55
 */

if( ! defined( 'NV_IS_FILE_SITEINFO' ) ) die( 'Stop!!!' );

if( defined( 'NV_IS_GODADMIN' ) )
{
	$array_dir = array( NV_DATADIR, NV_LOGS_DIR, NV_LOGS_DIR . '/data_logs', NV_LOGS_DIR . '/dump_backup', NV_LOGS_DIR . '/error_logs', NV_LOGS_DIR . '/error_logs/errors256', NV_LOGS_DIR . '/error_logs/old', NV_LOGS_DIR . '/error_logs/tmp', NV_LOGS_DIR . '/ip_logs', NV_LOGS_DIR . '/ref_logs', NV_LOGS_DIR . '/voting_logs', NV_CACHEDIR, NV_UPLOADS_DIR, NV_TEMP_DIR, NV_FILES_DIR, NV_FILES_DIR . '/css' );
	if( NV_SESSION_SAVE_PATH != '' )
	{
		$array_dir[] = NV_SESSION_SAVE_PATH;
	}
	
	$error = array();
	$ftp_check_login = 0;

	if( ! empty( $global_config['ftp_server'] ) and ! empty( $global_config['ftp_user_name'] ) and ! empty( $global_config['ftp_user_pass'] ) )
	{
		$conn_id = ftp_connect( $global_config['ftp_server'], $global_config['ftp_port'], 10 );
		$login_result = ftp_login( $conn_id, $global_config['ftp_user_name'], $global_config['ftp_user_pass'] );

		if( ( ! $conn_id ) || ( ! $login_result ) )
		{
			$error[] = $lang_module['checkchmod_error_account'];
		}
		elseif( ftp_chdir( $conn_id, $global_config['ftp_path'] ) )
		{
			$ftp_check_login = 1;
		}
		else
		{
			$error[] = $lang_module['checkchmod_error_path'];
		}
	}

	foreach( $array_dir as $dir )
	{
		if( $ftp_check_login == 1 )
		{
			if( ! is_dir( NV_ROOTDIR . '/' . $dir ) )
			{
				ftp_mkdir( $conn_id, $dir );
			}

			if( ! is_writable( NV_ROOTDIR . '/' . $dir ) )
			{
				nv_chmod_dir( $conn_id, $dir, true );
			}
		}
		else
		{
			//try chmod unix command
			if( ! chmod( NV_ROOTDIR . '/' . $dir, 0777 ) )
			{
				$error[] = $lang_module['checkchmod_error_unable_chmod'] . $dir;
			}
		}
	}

	if( ! empty( $error ) )
	{
		echo implode( '', $error );
	}
	else
	{
		echo $lang_module['checkchmod_success'];
	}
}