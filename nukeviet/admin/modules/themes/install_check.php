<?php

/**
 * @Project NUKEVIET 3.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2012 VINADES.,JSC. All rights reserved
 * @Createdate 2-2-2010 12:55
 */

if( ! defined( 'NV_IS_FILE_THEMES' ) ) die( 'Stop!!!' );

$filename = NV_ROOTDIR . '/' . NV_TEMP_DIR . '/' . NV_TEMPNAM_PREFIX . 'theme' . md5( $global_config['sitekey'] . session_id() ) . '.zip';

$xtpl = new XTemplate( "install_check.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file );
$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign( 'GLANG', $lang_global );

$xtpl->assign( 'NV_BASE_SITEURL', NV_BASE_SITEURL );
$xtpl->assign( 'NV_BASE_ADMINURL', NV_BASE_ADMINURL );
$xtpl->assign( 'NV_NAME_VARIABLE', NV_NAME_VARIABLE );
$xtpl->assign( 'NV_OP_VARIABLE', NV_OP_VARIABLE );

$xtpl->assign( 'MODULE_NAME', $module_name );
$xtpl->assign( 'OP', $op );

if( file_exists( $filename ) )
{
	require_once NV_ROOTDIR . '/includes/class/pclzip.class.php';
	$zip = new PclZip( $filename );
	$ziplistContent = $zip->listContent();
	$overwrite = $nv_Request->get_string( 'overwrite', 'get', '' );
	
	$errorfile = array();
	
	if( $overwrite != md5( $filename . $global_config['sitekey'] . session_id() ) )
	{
		foreach( $ziplistContent as $array_file )
		{
			//check exist file on system
			if( empty( $array_file['folder'] ) and file_exists( NV_ROOTDIR . '/themes/' . trim( $array_file['filename'] ) ) )
			{
				$errorfile[] = $array_file['filename'];
			}
		}
	}
	
	if( ! empty( $errorfile ) )
	{
		// File da ton tai tren he thong
		$xtpl->assign( 'OVERWRITE', md5( $filename . $global_config['sitekey'] . session_id() ) );
		
		foreach( $errorfile as $i => $file )
		{
			$xtpl->assign( 'FILE', $file );
			$xtpl->assign( 'CLASS', $i % 2 ? ' class="second"' : '' );
			$xtpl->parse( 'exists.loop' );
		}
		
		$xtpl->parse( 'exists' );
		$contents = $xtpl->text( 'exists' );
		
		include ( NV_ROOTDIR . "/includes/header.php" );
		echo $contents;
		include ( NV_ROOTDIR . "/includes/footer.php" );
		exit();
	}
	else
	{
		$temp_extract_dir = NV_TEMP_DIR . '/' . md5( $filename . $global_config['sitekey'] . session_id() );
		
		if( NV_ROOTDIR . '/' . $temp_extract_dir )
		{
			nv_deletefile( NV_ROOTDIR . '/' . $temp_extract_dir, true );
		}

		$ftp_check_login = 0;
		if( $sys_info['ftp_support'] and intval( $global_config['ftp_check_login'] ) == 1 )
		{
			$ftp_server = nv_unhtmlspecialchars( $global_config['ftp_server'] );
			$ftp_port = intval( $global_config['ftp_port'] );
			$ftp_user_name = nv_unhtmlspecialchars( $global_config['ftp_user_name'] );
			$ftp_user_pass = nv_unhtmlspecialchars( $global_config['ftp_user_pass'] );
			$ftp_path = nv_unhtmlspecialchars( $global_config['ftp_path'] );
			// set up basic connection
			$conn_id = ftp_connect( $ftp_server, $ftp_port, 10 );
			// login with username and password
			$login_result = ftp_login( $conn_id, $ftp_user_name, $ftp_user_pass );
			if( ( ! $conn_id ) || ( ! $login_result ) )
			{
				$ftp_check_login = 3;
			}
			elseif( ftp_chdir( $conn_id, $ftp_path ) )
			{
				$ftp_check_login = 1;
			}
			else
			{
				$ftp_check_login = 2;
			}
		}
		
		if( $ftp_check_login == 1 )
		{
			ftp_mkdir( $conn_id, $temp_extract_dir );
			
			if( substr( $sys_info['os'], 0, 3 ) != 'WIN' ) ftp_chmod( $conn_id, 0777, $temp_extract_dir );
			
			foreach( $ziplistContent as $array_file )
			{
				if( ! empty( $array_file['folder'] ) and ! file_exists( NV_ROOTDIR . '/' . $temp_extract_dir . '/' . $array_file['filename'] ) )
				{
					$cp = "";
					$e = explode( "/", $array_file['filename'] );
					foreach( $e as $p )
					{
						if( ! empty( $p ) and ! is_dir( NV_ROOTDIR . '/' . $temp_extract_dir . '/' . $cp . $p ) )
						{
							ftp_mkdir( $conn_id, $temp_extract_dir . '/' . $cp . $p );
							if( substr( $sys_info['os'], 0, 3 ) != 'WIN' ) ftp_chmod( $conn_id, 0777, $temp_extract_dir . '/' . $cp . $p );
						}
						$cp .= $p . '/';
					}
				}
			}
		}

		$no_extract = array();
		$extract = $zip->extract( PCLZIP_OPT_PATH, NV_ROOTDIR . '/' . $temp_extract_dir );
		
		foreach( $extract as $extract_i )
		{
			$filename_i = str_replace( NV_ROOTDIR, "", str_replace( '\\', '/', $extract_i['filename'] ) );
			
			if( $extract_i['status'] != 'ok' and $extract_i['status'] != 'already_a_directory' )
			{
				$no_extract[] = $filename_i;
			}
		}
		
		if( empty( $no_extract ) )
		{
			$error_create_folder = array();
			
			foreach( $ziplistContent as $array_file )
			{
				$dir_name = "";
				
				if( ! empty( $array_file['folder'] ) and ! file_exists( NV_ROOTDIR . '/' . $array_file['filename'] ) )
				{
					$dir_name = $array_file['filename'];
				}
				elseif( ! file_exists( NV_ROOTDIR . '/' . dirname( $array_file['filename'] ) ) )
				{
					$dir_name = dirname( $array_file['filename'] );
				}
				
				if( ! empty( $dir_name ) )
				{
					$cp = "";
					$e = explode( "/", $dir_name );
					
					foreach( $e as $p )
					{
						if( ! empty( $p ) and ! is_dir( NV_ROOTDIR . '/themes/' . $cp . $p ) )
						{
							if( ! ( $ftp_check_login == 1 and ftp_mkdir( $conn_id, 'themes/' . $cp . $p ) ) )
							{
								@mkdir( NV_ROOTDIR . '/themes/' . $cp . $p );
							}
							
							if( ! is_dir( NV_ROOTDIR . '/themes/' . $cp . $p ) )
							{
								$error_create_folder[] = 'themes/' . $cp . $p;
							}
						}
						
						$cp .= $p . '/';
					}
				}
			}
			
			$error_create_folder = array_unique( $error_create_folder );
			
			if( ! empty( $error_create_folder ) )
			{
				// Xuat cac thu muc khong the tao
				
				asort( $error_create_folder );
				
				foreach( $error_create_folder as $i => $folder )
				{
					$xtpl->assign( 'FOLDER', $folder );
					$xtpl->assign( 'CLASS', $i % 2 ? ' class="second"' : '' );
					$xtpl->parse( 'error_create_folder.loop' );
				}
				
				$xtpl->parse( 'error_create_folder' );
				$contents = $xtpl->text( 'error_create_folder' );
				
				include ( NV_ROOTDIR . "/includes/header.php" );
				echo $contents;
				include ( NV_ROOTDIR . "/includes/footer.php" );
				exit();
			}
			else
			{
				$error_move_folder = array();
				
				foreach( $ziplistContent as $array_file )
				{
					if( empty( $array_file['folder'] ) )
					{
						if( file_exists( NV_ROOTDIR . '/themes/' . $array_file['filename'] ) )
						{
							if( ! ( $ftp_check_login == 1 and ftp_delete( $conn_id, 'themes/' . $array_file['filename'] ) ) )
							{
								nv_deletefile( NV_ROOTDIR . '/themes/' . $array_file['filename'] );
							}
						}
						
						if( ! ( $ftp_check_login == 1 and ftp_rename( $conn_id, $temp_extract_dir . '/' . $array_file['filename'], 'themes/' . $array_file['filename'] ) ) )
						{
							@rename( NV_ROOTDIR . '/' . $temp_extract_dir . '/' . $array_file['filename'], NV_ROOTDIR . '/themes/' . $array_file['filename'] );
						}
						
						if( file_exists( NV_ROOTDIR . '/' . $temp_extract_dir . '/' . $array_file['filename'] ) )
						{
							$error_move_folder[] = $array_file['filename'];
						}
					}
				}
				
				if( empty( $error_move_folder ) )
				{
					// Giai nen hoan tat
					
					nv_deletefile( $filename );
					nv_deletefile( NV_ROOTDIR . '/' . $temp_extract_dir, true );
					
					$theme = substr( $ziplistContent[0]['filename'], 0, -1 );
					$nv_redirect = NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=setuplayout&selectthemes=' . $theme;
					
					$xtpl->assign( 'NV_REDIRECT', $nv_redirect );
					
					$xtpl->parse( 'complete' );
					$contents = $xtpl->text( 'complete' );
					
					include ( NV_ROOTDIR . "/includes/header.php" );
					echo $contents;
					include ( NV_ROOTDIR . "/includes/footer.php" );
					exit();
				}
				else
				{
					// Xuat cac thu muc khong the di chuyen file qua
					asort( $error_move_folder );
					
					foreach( $error_move_folder as $i => $folder )
					{
						$xtpl->assign( 'FOLDER', $folder );
						$xtpl->assign( 'CLASS', $i % 2 ? ' class="second"' : '' );
						$xtpl->parse( 'error_move_folder.loop' );
					}
					
					$xtpl->parse( 'error_move_folder' );
					$contents = $xtpl->text( 'error_move_folder' );
					
					include ( NV_ROOTDIR . "/includes/header.php" );
					echo $contents;
					include ( NV_ROOTDIR . "/includes/footer.php" );
					exit();
				}
			}
		}
		else
		{
			// Khong the giai nen
			$xtpl->parse( 'nounzip' );
			$contents = $xtpl->text( 'nounzip' );
			
			include ( NV_ROOTDIR . "/includes/header.php" );
			echo $contents;
			include ( NV_ROOTDIR . "/includes/footer.php" );
			exit();
		}
	}
}

?>