<?php

/**
 * @Project NUKEVIET 3.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2012 VINADES.,JSC. All rights reserved
 * @Createdate 31/05/2010, 00:36
 */

if( ! defined( 'NV_ADMIN' ) or ! defined( 'NV_MAINFILE' ) or ! defined( 'NV_IS_MODADMIN' ) ) die( 'Stop!!!' );

$error = "";

$page_title = $lang_module['ftp_config'];

$xtpl = new XTemplate( "ftp.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file );
$xtpl->assign( 'LANG', $lang_module );

if( $sys_info['ftp_support'] )
{
	$array_config = array();

	$array_config['ftp_server'] = filter_text_input( 'ftp_server', 'post', $global_config['ftp_server'], 1, 255 );
	$array_config['ftp_port'] = filter_text_input( 'ftp_port', 'post', $global_config['ftp_port'], 1, 255 );
	$array_config['ftp_user_name'] = filter_text_input( 'ftp_user_name', 'post', $global_config['ftp_user_name'], 1, 255 );
	$array_config['ftp_user_pass'] = filter_text_input( 'ftp_user_pass', 'post', $global_config['ftp_user_pass'], 1, 255 );
	$array_config['ftp_path'] = filter_text_input( 'ftp_path', 'post', $global_config['ftp_path'], 1, 255 );
	$array_config['ftp_check_login'] = $global_config['ftp_check_login'];

	// Tu dong nhan dang Remove Path
	if( $nv_Request->isset_request( 'tetectftp', 'post' ) )
	{
		$ftp_server = nv_unhtmlspecialchars( filter_text_input( 'ftp_server', 'post', '', 1, 255 ) );
		$ftp_port = intval( filter_text_input( 'ftp_port', 'post', '21', 1, 255 ) );
		$ftp_user_name = nv_unhtmlspecialchars( filter_text_input( 'ftp_user_name', 'post', '', 1, 255 ) );
		$ftp_user_pass = nv_unhtmlspecialchars( filter_text_input( 'ftp_user_pass', 'post', '', 1, 255 ) );

		if( ! $ftp_server or ! $ftp_user_name or ! $ftp_user_pass )
		{
			die( 'ERROR|' . $lang_module['ftp_error_full'] );
		}
		
		if( ! defined( 'NV_FTP_CLASS' ) ) require( NV_ROOTDIR . '/includes/class/ftp.class.php' );
		if( ! defined( 'NV_BUFFER_CLASS' ) ) require( NV_ROOTDIR . '/includes/class/buffer.class.php' );
		
		$ftp = new NVftp( $ftp_server, $ftp_user_name, $ftp_user_pass, array( 'timeout' => 10 ), $ftp_port );
		
		if( ! empty( $ftp->error ) )
		{
			$ftp->close();
			die( 'ERROR|' . (string)$ftp->error );
		}
		else
		{
			$list_valid = array( NV_CACHEDIR, NV_DATADIR, "images", "includes", "js", "language", NV_LOGS_DIR, "modules", NV_SESSION_SAVE_PATH, "themes", NV_TEMP_DIR, NV_UPLOADS_DIR );
		
			$ftp_root = $ftp->detectFtpRoot( $list_valid, NV_ROOTDIR );
			
			if( $ftp_root === false )
			{
				$ftp->close();
				die( 'ERROR|' . ( empty( $ftp->error ) ? $lang_module['ftp_error_detect_root'] : (string)$ftp->error ) );
			}
			
			$ftp->close();
			die( 'OK|'. $ftp_root );
		}
		
		$ftp->close();
		die( 'ERROR|' . $lang_module['ftp_error_detect_root'] );
	}

	if( $nv_Request->isset_request( 'ftp_server', 'post' ) )
	{
		$array_config['ftp_check_login'] = 0;
		
		if( ! empty( $array_config['ftp_server'] ) and ! empty( $array_config['ftp_user_name'] ) and ! empty( $array_config['ftp_user_pass'] ) )
		{
			$ftp_server = nv_unhtmlspecialchars( $array_config['ftp_server'] );
			$ftp_port = intval( $array_config['ftp_port'] );
			$ftp_user_name = nv_unhtmlspecialchars( $array_config['ftp_user_name'] );
			$ftp_user_pass = nv_unhtmlspecialchars( $array_config['ftp_user_pass'] );
			$ftp_path = nv_unhtmlspecialchars( $array_config['ftp_path'] );

			if( ! defined( 'NV_FTP_CLASS' ) ) require( NV_ROOTDIR . '/includes/class/ftp.class.php' );
			
			$ftp = new NVftp( $ftp_server, $ftp_user_name, $ftp_user_pass, array( 'timeout' => 10 ), $ftp_port );

			if( ! empty( $ftp->error ) )
			{
				$array_config['ftp_check_login'] = 3;
				$error = ( string ) $ftp->error;
			}
			elseif( $ftp->chdir( $ftp_path ) === false )
			{
				$array_config['ftp_check_login'] = 2;
				$error = $lang_global['ftp_error_path'];
			}
			else
			{			
				$check_files = array( NV_CACHEDIR, NV_DATADIR, "images", "includes", "index.php", "js", "language", NV_LOGS_DIR, "mainfile.php", "modules", NV_SESSION_SAVE_PATH, "themes", NV_TEMP_DIR, NV_UPLOADS_DIR );
					
				$list_files = $ftp->listDetail( $ftp_path, 'all' );
				
				$a = 0;
				if( ! empty( $list_files ) )
				{
					foreach( $list_files as $filename )
					{
						if( in_array( $filename['name'], $check_files ) )
						{
							++ $a;
						}
					}	
				}
					
				if( $a == sizeof( $check_files ) )
				{
					$array_config['ftp_check_login'] = 1;
				}
				else
				{
					$array_config['ftp_check_login'] = 2;
					$error = $lang_global['ftp_error_path'];
				}
			}
			
			$ftp->close();
		}

		foreach( $array_config as $config_name => $config_value )
		{
			$db->sql_query( "UPDATE `" . NV_CONFIG_GLOBALTABLE . "` 
			SET `config_value`=" . $db->dbescape_string( $config_value ) . " 
			WHERE `config_name` = " . $db->dbescape_string( $config_name ) . " 
			AND `lang` = 'sys' AND `module`='global' 
			LIMIT 1" );
		}
		
		nv_save_file_config_global();
		
		if( empty( $error ) )
		{
			Header( 'Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op . '&rand=' . nv_genpass() );
			exit();
		}
	}

	$xtpl->assign( 'VALUE', $array_config );
	$xtpl->assign( 'DETECT_FTP', NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op );

	if( ! empty( $error ) )
	{
		$xtpl->assign( 'ERROR', $error );
		$xtpl->parse( 'main.error' );
	}

	$xtpl->parse( 'main' );
	$contents = $xtpl->text( 'main' );
}
else
{
	$xtpl->parse( 'no_support' );
	$contents = $xtpl->text( 'no_support' );
}

include ( NV_ROOTDIR . "/includes/header.php" );
echo nv_admin_theme( $contents );
include ( NV_ROOTDIR . "/includes/footer.php" );

?>