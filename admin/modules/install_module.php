<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 2-2-2010 12:55
 */

if( ! defined( 'NV_IS_FILE_MODULES' ) ) die( 'Stop!!!' );

$page_title = $lang_module['autoinstall_module_install'];

$filename = NV_ROOTDIR . '/' . NV_TEMP_DIR . '/' . NV_TEMPNAM_PREFIX . 'auto_' . md5( $global_config['sitekey'] . session_id() ) . '.zip';

$xtpl = new XTemplate( 'install_module.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file );
$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign( 'GLANG', $lang_global );
$xtpl->assign( 'NV_BASE_ADMINURL', NV_BASE_ADMINURL );
$xtpl->assign( 'NV_BASE_SITEURL', NV_BASE_SITEURL );
$xtpl->assign( 'NV_NAME_VARIABLE', NV_NAME_VARIABLE );
$xtpl->assign( 'NV_OP_VARIABLE', NV_OP_VARIABLE );
$xtpl->assign( 'MODULE_NAME', $module_name );
$xtpl->assign( 'OP', $op );

if( $nv_Request->isset_request( NV_OP_VARIABLE, 'post' ) )
{
	require_once NV_ROOTDIR . '/includes/class/pclzip.class.php';

	$allowfolder = array( 'modules', 'themes', 'uploads' );

	$error = '';
	$info = array();

	if( is_uploaded_file( $_FILES['modulefile']['tmp_name'] ) )
	{
		if( move_uploaded_file( $_FILES['modulefile']['tmp_name'], $filename ) )
		{
			$zip = new PclZip( $filename );
			$status = $zip->properties();

			if( $status['status'] == 'ok' )
			{
				nv_insert_logs( NV_LANG_DATA, $module_name, $lang_module['autoinstall_method_module'], basename( $_FILES['modulefile']['name'] ), $admin_info['userid'] );

				$validfolder = array();
				$info['filesize'] = nv_convertfromBytes( $_FILES['modulefile']['size'] );
				$info['filename'] = $_FILES['modulefile']['name'];
				$info['filenum'] = $status['nb'];
				$info['filefolder'] = array();
				$info['filelist'] = array();

				// Show file and folder
				$list = $zip->listContent();
				$sizeof = sizeof( $list );

				for( $i = 0, $j = 1; $i < $sizeof; ++$i, ++$j )
				{
					if( ! $list[$i]['folder'] )
					{
						$bytes = nv_convertfromBytes( $list[$i]['size'] );
					}
					else
					{
						$bytes = '';
						$validfolder[] = $list[$i]['filename'];
					}

					$info['filefolder'][] = $list[$i]['filename'];
					$info['filelist'][] = '[' . $j . '] ' . $list[$i]['filename'] . ' ' . $bytes;
				}
			}
			else
			{
				$error = $lang_module['autoinstall_module_error_invalidfile'] . ' <a href="javascript:history.go(-1)">' . $lang_module['back'] . '</a>';
			}
		}
		else
		{
			$error = $lang_module['autoinstall_module_error_uploadfile'];
		}
	}

	if( ! empty( $error ) )
	{
		$xtpl->assign( 'ERROR', $error );
		$xtpl->parse( 'info.error' );
	}

	if( ! empty( $info ) )
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
	$xtpl->parse( 'main' );
	$contents = $xtpl->text( 'main' );

	include NV_ROOTDIR . '/includes/header.php';
	echo( $contents );
	include NV_ROOTDIR . '/includes/footer.php';
}