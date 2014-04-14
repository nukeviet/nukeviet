<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 2-2-2010 12:55
 */

if( ! defined( 'NV_IS_FILE_MODULES' ) ) die( 'Stop!!!' );

$page_title = $lang_module['autoinstall_method_packet'];

$xtpl = new XTemplate( 'install_package.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file );

if( $nv_Request->isset_request( NV_OP_VARIABLE, 'post' ) )
{
	$modulename = $nv_Request->get_string( 'modulename', 'post' );
	if( preg_match( $global_config['check_module'], $modulename ) )
	{
		$tempfolder = NV_ROOTDIR . '/' . NV_TEMP_DIR;

		// Module folder
		if( file_exists( NV_ROOTDIR . '/modules/' . $modulename . '/' ) )
		{
			$allowfolder[] = NV_ROOTDIR . '/modules/' . $modulename . '/';
		}
		elseif( file_exists( NV_ROOTDIR . '/' . NV_ADMINDIR . '/' . $modulename . '/' ) )
		{
			$allowfolder[] = NV_ROOTDIR . '/' . NV_ADMINDIR . '/' . $modulename . '/';
			if( file_exists( NV_ROOTDIR . '/js/admin_' . $modulename . '.js' ) )
			{
				$allowfolder[] = NV_ROOTDIR . '/js/admin_' . $modulename . '.js';
			}

			$langs_admin = nv_scandir( NV_ROOTDIR . '/language', '/^[a-z]{2}$/' );
			foreach( $langs_admin as $langi )
			{
				if( file_exists( NV_ROOTDIR . '/language/' . $langi . '/admin_' . $modulename . '.php' ) )
				{
					$allowfolder[] = NV_ROOTDIR . '/language/' . $langi . '/admin_' . $modulename . '.php';
				}
			}
		}

		// Theme folder
		$theme_package = '';
		if( is_dir( NV_ROOTDIR . '/themes/default/modules/' . $modulename ) )
		{
			$theme_package = 'default';
		}
		elseif( is_dir( NV_ROOTDIR . '/themes/' . $global_config['site_theme'] . '/modules/' . $modulename ) )
		{
			$theme_package = $global_config['site_theme'];
		}

		if( ! empty( $theme_package ) )
		{
			$allowfolder[] = NV_ROOTDIR . '/themes/' . $theme_package . '/modules/' . $modulename . '/';

			if( file_exists( NV_ROOTDIR . '/themes/' . $theme_package . '/css/' . $modulename . '.css' ) )
			{
				$allowfolder[] = NV_ROOTDIR . '/themes/' . $theme_package . '/css/' . $modulename . '.css';
			}

			if( file_exists( NV_ROOTDIR . '/themes/' . $theme_package . '/images/' . $modulename . '/' ) )
			{
				$allowfolder[] = NV_ROOTDIR . '/themes/' . $theme_package . '/images/' . $modulename . '/';
			}
		}

		// Admin default theme
		if( file_exists( NV_ROOTDIR . '/themes/admin_default' ) )
		{
			if( file_exists( NV_ROOTDIR . '/themes/admin_default/css/' . $modulename . '.css' ) )
			{
				$allowfolder[] = NV_ROOTDIR . '/themes/admin_default/css/' . $modulename . '.css';
			}

			if( file_exists( NV_ROOTDIR . '/themes/admin_default/images/' . $modulename . '/' ) )
			{
				$allowfolder[] = NV_ROOTDIR . '/themes/admin_default/images/' . $modulename . '/';
			}

			if( file_exists( NV_ROOTDIR . '/themes/admin_default/modules/' . $modulename . '/' ) )
			{
				$allowfolder[] = NV_ROOTDIR . '/themes/admin_default/modules/' . $modulename . '/';
			}
		}

		$file_src = NV_ROOTDIR . '/' . NV_TEMP_DIR . '/' . NV_TEMPNAM_PREFIX . 'module_' . $modulename . '_' . md5( nv_genpass( 10 ) . session_id() ) . '.zip';

		if( file_exists( $file_src ) )
		{
			@nv_deletefile( $file_src );
		}

		require_once NV_ROOTDIR . '/includes/class/pclzip.class.php';
		$zip = new PclZip( $file_src );
		$zip->add( $allowfolder, PCLZIP_OPT_REMOVE_PATH, NV_ROOTDIR );
		$filesize = @filesize( $file_src );
		$file_name = basename( $file_src );

		nv_insert_logs( NV_LANG_DATA, $module_name, $lang_module['autoinstall_method_module'], 'packet ' . basename( $modulename ), $admin_info['userid'] );

		$linkgetfile = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=getfile&amp;mod=nv4_module_' . $modulename . '.zip&amp;checkss=' . md5( $file_name . $client_info['session_id'] . $global_config['sitekey'] ) . '&amp;filename=' . $file_name;

		$xtpl->assign( 'LINKGETFILE', $linkgetfile );
		$xtpl->assign( 'MODULENAME', $modulename );
		$xtpl->assign( 'FILESIZE', nv_convertfromBytes( $filesize ) );

		$xtpl->parse( 'package_complete' );
		$contents = $xtpl->text( 'package_complete' );
	}

	include NV_ROOTDIR . '/includes/header.php';
	echo $contents;
	include NV_ROOTDIR . '/includes/footer.php';
}
else
{
	$op = $nv_Request->get_string( NV_OP_VARIABLE, 'get' );

	$xtpl->assign( 'LANG', $lang_module );
	$xtpl->assign( 'GLANG', $lang_global );
	$xtpl->assign( 'NV_BASE_ADMINURL', NV_BASE_ADMINURL );
	$xtpl->assign( 'NV_BASE_SITEURL', NV_BASE_SITEURL );
	$xtpl->assign( 'NV_NAME_VARIABLE', NV_NAME_VARIABLE );
	$xtpl->assign( 'NV_OP_VARIABLE', NV_OP_VARIABLE );
	$xtpl->assign( 'MODULE_NAME', $module_name );
	$xtpl->assign( 'OP', $op );

	$sql = 'SELECT module_file FROM ' . $db_config['prefix'] . '_setup_modules WHERE title=module_file ORDER BY title ASC';
	$result = $db->query( $sql );
	while( $row = $result->fetch() )
	{
		$xtpl->assign( 'MODULE_FILE', $row['module_file'] );
		$xtpl->parse( 'main.module_file' );
	}

	$modules_admin = nv_scandir( NV_ROOTDIR . '/' . NV_ADMINDIR, $global_config['check_module'] );
	foreach( $modules_admin as $module )
	{
		$xtpl->assign( 'MODULE_FILE', $module );
		$xtpl->parse( 'main.module_file' );
	}
	$xtpl->parse( 'main' );
	$contents = $xtpl->text( 'main' );

	include NV_ROOTDIR . '/includes/header.php';
	echo $contents;
	include NV_ROOTDIR . '/includes/footer.php';
}