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

$xtpl->assign( 'MODULE_NAME', $module_name );

$xtpl->assign( 'NV_BASE_SITEURL', NV_BASE_SITEURL );
$xtpl->assign( 'NV_BASE_ADMINURL', NV_BASE_ADMINURL );
$xtpl->assign( 'NV_NAME_VARIABLE', NV_NAME_VARIABLE );
$xtpl->assign( 'NV_OP_VARIABLE', NV_OP_VARIABLE );

if( $nv_Request->isset_request( NV_OP_VARIABLE, 'post' ) )
{
	require_once NV_ROOTDIR . '/includes/class/pclzip.class.php';

	if( is_uploaded_file( $_FILES['themefile']['tmp_name'] ) )
	{
		if( move_uploaded_file( $_FILES['themefile']['tmp_name'], $filename ) )
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
				nv_insert_logs( NV_LANG_DATA, $module_name, $lang_module['autoinstall_method_install'], 'file name : ' . basename( $_FILES['themefile']['name'] ), $admin_info['userid'] );

				$filelist = array();
				$validfolder = array();

				$filesize = nv_convertfromBytes( $_FILES['themefile']['size'] );

				$xtpl->assign( 'FILENAME', $_FILES['themefile']['name'] );
				$xtpl->assign( 'FILESIZE', $filesize );

				// Show file and folder
				$xtpl->assign( 'FILENUM', $status['nb'] );
				$xtpl->assign( 'OP', $op );

				$sizeof = sizeof( $list );
				for( $i = 0, $j = 1; $i < $sizeof; ++$i )
				{
					if( ! $list[$i]['folder'] )
					{
						$file = array(
							'stt' => ++$j,
							'filename' => $list[$i]['filename'],
							'size' => nv_convertfromBytes( $list[$i]['size'] )
						);
						$xtpl->assign( 'FILE', $file );
						$xtpl->parse( 'autoinstall_theme_uploadedfile.loop' );
					}
				}
				$xtpl->parse( 'autoinstall_theme_uploadedfile' );
				$contents = $xtpl->text( 'autoinstall_theme_uploadedfile' );
			}
			else
			{
				$xtpl->parse( 'autoinstall_theme_error_invalidfile' );
				$contents = $xtpl->text( 'autoinstall_theme_error_invalidfile' );
			}
		}
		else
		{
			$xtpl->parse( 'autoinstall_theme_error_uploadfile' );
			$contents = $xtpl->text( 'autoinstall_theme_error_uploadfile' );
		}
	}

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