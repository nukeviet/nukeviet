<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 2-2-2010 12:55
 */

if( ! defined( 'NV_IS_FILE_THEMES' ) ) die( 'Stop!!!' );

$page_title = $lang_module['autoinstall'];

$filename = NV_ROOTDIR . '/' . NV_TEMP_DIR . '/' . NV_TEMPNAM_PREFIX . 'theme' . md5( $global_config['sitekey'] . session_id() ) . '.zip';

$xtpl = new XTemplate( 'install_theme.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file );
$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign( 'GLANG', $lang_global );
$xtpl->assign( 'NV_BASE_ADMINURL', NV_BASE_ADMINURL );
$xtpl->assign( 'NV_BASE_SITEURL', NV_BASE_SITEURL );
$xtpl->assign( 'NV_NAME_VARIABLE', NV_NAME_VARIABLE );
$xtpl->assign( 'NV_OP_VARIABLE', NV_OP_VARIABLE );
$xtpl->assign( 'MODULE_NAME', $module_name );
$xtpl->assign( 'OP', $op );

if( $nv_Request->isset_request( NV_OP_VARIABLE, 'post' ) or $nv_Request->isset_request( 'downloaded', 'get' ) )
{
	require_once NV_ROOTDIR . '/includes/class/pclzip.class.php';

	$error = '';
	$info = array();

	if( $nv_Request->isset_request( 'downloaded', 'get' ) )
	{
		if( ! file_exists( $filename ) )
		{
			$error = $lang_module['autoinstall_theme_error_downloaded'];
		}
	}
	elseif( is_uploaded_file( $_FILES['themefile']['tmp_name'] ) )
	{
		if( ! move_uploaded_file( $_FILES['themefile']['tmp_name'], $filename ) )
		{
			$error = $lang_module['autoinstall_theme_error_uploadfile'];
		}
		
		nv_insert_logs( NV_LANG_DATA, $module_name, $lang_module['autoinstall_method_install'], 'file name : ' . basename( $_FILES['themefile']['name'] ), $admin_info['userid'] );
		
		if( ! file_exists( $filename ) )
		{
			$error = $lang_module['autoinstall_theme_error_downloaded'];
		}
	}

	// Check file
	if( empty( $error ) )
	{
		$zip = new PclZip( $filename );
		$status = $zip->properties();
		$check_number = 0;

		if( $status['status'] == 'ok' )
		{
			$list = $zip->listContent();

			$theme = '';
			foreach( $list as $file_i )
			{
				if( preg_match( '/^(?!admin\_)([a-zA-Z0-9\-\_]+)\/(theme\.php|config\.ini)$/', $file_i['filename'], $m ) )
				{
					++$check_number;
				}
			}
		}

		if( $check_number == 2 )
		{
			$info['filesize'] = nv_convertfromBytes( filesize( $filename ) );
			$info['filename'] = basename( $filename );
			$info['filenum'] = $status['nb'];
			$info['filelist'] = array();

			$sizeof = sizeof( $list );
			for( $i = 0, $j = 1; $i < $sizeof; ++$i )
			{
				if( ! $list[$i]['folder'] )
				{
					$info['filelist'][] = '[' . $j ++ . '] ' . $list[$i]['filename'] . ' ' . nv_convertfromBytes( $list[$i]['size'] );
				}
			}
		}
		else
		{
			$error = $lang_module['autoinstall_theme_error_invalidfile'];
		}
	}

	if( ! empty( $error ) )
	{
		$xtpl->assign( 'ERROR', $error );
		$xtpl->parse( 'info.error' );
	}
	elseif( ! empty( $info ) )
	{
		$xtpl->assign( 'INFO', $info );

		if( ! empty( $info['filelist'] ) )
		{
			$i = 0;
			foreach( $info['filelist'] as $file )
			{
				$xtpl->assign( 'FILE', $file );
				$xtpl->parse( 'info.fileinfo.file.loop' );
				++$i;
			}

			$xtpl->parse( 'info.fileinfo.file' );
		}
		$xtpl->parse( 'info.fileinfo' );
	}

	$xtpl->parse( 'info' );
	$contents = $xtpl->text( 'info' );

	include NV_ROOTDIR . '/includes/header.php';
	echo nv_admin_theme( $contents );
	include NV_ROOTDIR . '/includes/footer.php';
}
else
{
	$op = $nv_Request->get_string( NV_OP_VARIABLE, 'get', '' );
	$xtpl->assign( 'OP', $op );

	$xtpl->parse( 'main' );
	$contents = $xtpl->text( 'main' );

	include NV_ROOTDIR . '/includes/header.php';
	echo $contents;
	include NV_ROOTDIR . '/includes/footer.php';
}