<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 2-2-2010 12:55
 */

if( ! defined( 'NV_IS_FILE_MODULES' ) ) die( 'Stop!!!' );

$error = '';
$info_error = array();
$info_error['errorfile'] = array();
$info_error['errorfolder'] = array();

$allowfolder = array( 'themes', 'modules', 'uploads', 'includes/blocks' );

$filename = NV_ROOTDIR . '/' . NV_TEMP_DIR . '/' . NV_TEMPNAM_PREFIX . 'auto_' . md5( $global_config['sitekey'] . session_id() ) . '.zip';

if( file_exists( $filename ) )
{
	require_once NV_ROOTDIR . '/includes/class/pclzip.class.php';

	$zip = new PclZip( $filename );
	$ziplistContent = $zip->listContent();

	$overwrite = $nv_Request->get_string( 'overwrite', 'get', '' );

	if( $overwrite != md5( $filename . $global_config['sitekey'] . session_id() ) )
	{
		foreach( $ziplistContent as $array_file )
		{
			//Check exist file on system
			if( empty( $array_file['folder'] ) and file_exists( NV_ROOTDIR . '/' . trim( $array_file['filename'] ) ) )
			{
				$info_error['errorfile'][] = $array_file['filename'];
			}

			//Check valid folder structure nukeviet (modules, themes, uploads)
			$folder = explode( '/', $array_file['filename'] );

			if( ! in_array( $folder[0], $allowfolder ) and ! in_array( $folder[0] . '/' . $folder[1], $allowfolder ) )
			{
				$info_error['errorfolder'][] = $array_file['filename'];
			}
		}
	}

	if( ! $info_error['errorfile'] and ! $info_error['errorfolder'] )
	{
		$temp_extract_dir = NV_TEMP_DIR . '/' . md5( $filename . $global_config['sitekey'] . session_id() );

		$no_extract = array();
		$error_create_folder = array();
		$error_move_folder = array();

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
					$cp = '';
					$e = explode( '/', $array_file['filename'] );

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

		$extract = $zip->extract( PCLZIP_OPT_PATH, NV_ROOTDIR . '/' . $temp_extract_dir );

		foreach( $extract as $extract_i )
		{
			$filename_i = str_replace( NV_ROOTDIR, '', str_replace( '\\', '/', $extract_i['filename'] ) );

			if( $extract_i['status'] != 'ok' and $extract_i['status'] != 'already_a_directory' )
			{
				$no_extract[] = $filename_i;
			}
		}

		if( empty( $no_extract ) )
		{
			foreach( $ziplistContent as $array_file )
			{
				$dir_name = '';

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
					$cp = '';
					$e = explode( '/', $dir_name );

					foreach( $e as $p )
					{
						if( ! empty( $p ) and ! is_dir( NV_ROOTDIR . '/' . $cp . $p ) )
						{
							if( ! ( $ftp_check_login == 1 and ftp_mkdir( $conn_id, $cp . $p ) ) )
							{
								@mkdir( NV_ROOTDIR . '/' . $cp . $p );
							}
							if( ! is_dir( NV_ROOTDIR . '/' . $cp . $p ) )
							{
								$error_create_folder[] = $cp . $p;
								break;
							}
						}

						$cp .= $p . '/';
					}
				}
			}

			$error_create_folder = array_unique( $error_create_folder );

			if( empty( $error_create_folder ) )
			{
				foreach( $ziplistContent as $array_file )
				{
					if( empty( $array_file['folder'] ) )
					{
						if( file_exists( NV_ROOTDIR . '/' . $array_file['filename'] ) )
						{
							if( ! ( $ftp_check_login == 1 and ftp_delete( $conn_id, $array_file['filename'] ) ) )
							{
								nv_deletefile( NV_ROOTDIR . '/' . $array_file['filename'] );
							}
						}

						if( ! ( $ftp_check_login == 1 and ftp_rename( $conn_id, $temp_extract_dir . '/' . $array_file['filename'], $array_file['filename'] ) ) )
						{
							@rename( NV_ROOTDIR . '/' . $temp_extract_dir . '/' . $array_file['filename'], NV_ROOTDIR . '/' . $array_file['filename'] );
						}

						if( file_exists( NV_ROOTDIR . '/' . $temp_extract_dir . '/' . $array_file['filename'] ) )
						{
							$error_move_folder[] = $array_file['filename'];
						}
					}
				}

				if( empty( $error_move_folder ) )
				{
					nv_deletefile( $filename );
					nv_deletefile( NV_ROOTDIR . '/' . $temp_extract_dir, true );
				}
			}

			if( $ftp_check_login > 0 )
			{
				ftp_close( $conn_id );
			}
		}

		$xtpl = new XTemplate( 'install_check.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file );
		$xtpl->assign( 'LANG', $lang_module );
		$xtpl->assign( 'GLANG', $lang_global );
		$xtpl->assign( 'NV_BASE_SITEURL', NV_BASE_SITEURL );
		$xtpl->assign( 'NV_BASE_ADMINURL', NV_BASE_ADMINURL );
		$xtpl->assign( 'NV_NAME_VARIABLE', NV_NAME_VARIABLE );
		$xtpl->assign( 'NV_OP_VARIABLE', NV_OP_VARIABLE );
		$xtpl->assign( 'MODULE_NAME', $module_name );
		$xtpl->assign( 'CHECKSESS', md5( $filename . $global_config['sitekey'] . session_id() ) );

		if( ! empty( $no_extract ) )
		{
			$i = 0;
			foreach( $no_extract as $tmp )
			{
				$xtpl->assign( 'FILENAME', $tmp );
				$xtpl->parse( 'complete.no_extract.loop' );
				++$i;
			}

			$xtpl->parse( 'complete.no_extract' );
		}
		elseif( ! empty( $error_create_folder ) )
		{
			$i = 0;
			asort( $error_create_folder );

			foreach( $error_create_folder as $tmp )
			{
				$xtpl->assign( 'FILENAME', $tmp );
				$xtpl->parse( 'complete.error_create_folder.loop' );
				++$i;
			}

			$xtpl->parse( 'complete.error_create_folder' );
		}
		elseif( ! empty( $error_move_folder ) )
		{
			$i = 0;
			asort( $error_move_folder );

			foreach( $error_move_folder as $tmp )
			{
				$xtpl->assign( 'FILENAME', $tmp );
				$xtpl->parse( 'complete.error_move_folder.loop' );
				++$i;
			}

			$xtpl->parse( 'complete.error_move_folder' );
		}
		else
		{
			$xtpl->assign( 'URL_GO', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=setup' );
			$xtpl->parse( 'complete.ok' );
		}

		$xtpl->parse( 'complete' );
		$contents = $xtpl->text( 'complete' );

		include NV_ROOTDIR . '/includes/header.php';
		echo ( $contents );
		include NV_ROOTDIR . '/includes/footer.php';
		exit();
	}
}
else
{
	$error = $lang_module['autoinstall_module_error_uploadfile'];
}

$xtpl = new XTemplate( 'install_check.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file );
$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign( 'GLANG', $lang_global );
$xtpl->assign( 'NV_BASE_SITEURL', NV_BASE_SITEURL );
$xtpl->assign( 'NV_BASE_ADMINURL', NV_BASE_ADMINURL );
$xtpl->assign( 'NV_NAME_VARIABLE', NV_NAME_VARIABLE );
$xtpl->assign( 'NV_OP_VARIABLE', NV_OP_VARIABLE );
$xtpl->assign( 'MODULE_NAME', $module_name );
$xtpl->assign( 'CHECKSESS', md5( $filename . $global_config['sitekey'] . session_id() ) );

if( ! empty( $error ) )
{
	$xtpl->assign( 'ERROR', $error );
	$xtpl->parse( 'main.error' );
}

if( ! empty( $info_error['errorfile'] ) or ! empty( $info_error['errorfolder'] ) )
{
	$xtpl->parse( 'main.infoerror' );
}

if( ! empty( $info_error['errorfile'] ) )
{
	$i = 0;
	foreach( $info_error['errorfile'] as $tmp )
	{
		$xtpl->assign( 'FILENAME', $tmp );
		$xtpl->parse( 'main.errorfile.loop' );
		++$i;
	}
	$xtpl->parse( 'main.errorfile' );
}

if( ! empty( $info_error['errorfolder'] ) )
{
	$i = 0;
	foreach( $info_error['errorfolder'] as $tmp )
	{
		$xtpl->assign( 'FILENAME', $tmp );
		$xtpl->parse( 'main.errorfolder.loop' );
		++$i;
	}
	$xtpl->parse( 'main.errorfolder' );
}

$xtpl->parse( 'main' );
$contents = $xtpl->text( 'main' );

include NV_ROOTDIR . '/includes/header.php';
echo ( $contents );
include NV_ROOTDIR . '/includes/footer.php';