<?php

/**
 * @Project NUKEVIET 3.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2012 VINADES.,JSC. All rights reserved
 * @Createdate 2-2-2010 12:55
 */

if( ! defined( 'NV_IS_FILE_THEMES' ) ) die( 'Stop!!!' );

$page_title = $lang_module['autoinstall_method_packet'];

/**
 * getDirectoryTree()
 *
 * @param mixed $outerDir
 * @param mixed $basepath
 * @return
 */
function getDirectoryTree( $outerDir, $basepath )
{
	$dirs = array_diff( scandir( $outerDir ), array( ".", ".." ) );
	$dir_array = array();

	foreach( $dirs as $d ) $dir_array[] = is_dir( $outerDir . "/" . $d ) ? getDirectoryTree( $outerDir . "/" . $d, $filters ) : $dir_array[] = $basepath . $d;

	return $dir_array;
}

$xtpl = new XTemplate( "package_theme_module.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file );
$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign( 'GLANG', $lang_global );

if( $nv_Request->isset_request( 'op', 'post' ) )
{
	$themename = $nv_Request->get_string( 'themename', 'post' );
	$modulename = $nv_Request->get_string( 'modulename', 'post' );

	if( preg_match( $global_config['check_module'], $modulename ) and ( preg_match( $global_config['check_theme'], $themename ) or preg_match( $global_config['check_theme_mobile'], $themename ) ) )
	{
		$allowfolder = array();
		$allowfolder[] = NV_ROOTDIR . '/themes/' . $themename . '/modules/' . $modulename . '/';

		if( file_exists( NV_ROOTDIR . '/themes/' . $themename . '/css/' . $modulename . '.css' ) )
		{
			$allowfolder[] = NV_ROOTDIR . '/themes/' . $themename . '/css/' . $modulename . '.css';
		}

		if( file_exists( NV_ROOTDIR . '/themes/' . $themename . '/images/' . $modulename . '/' ) )
		{
			$allowfolder[] = NV_ROOTDIR . '/themes/' . $themename . '/images/' . $modulename . '/';
		}

		$file_src = NV_ROOTDIR . '/' . NV_TEMP_DIR . '/' . NV_TEMPNAM_PREFIX . 'theme_' . $themename . '_' . $modulename . '_' . md5( nv_genpass( 10 ) . session_id() ) . '.zip';

		require_once NV_ROOTDIR . '/includes/class/pclzip.class.php';
		$zip = new PclZip( $file_src );
		$zip->create( $allowfolder, PCLZIP_OPT_REMOVE_PATH, NV_ROOTDIR . '/themes' );

		$filesize = filesize( $file_src );
		$file_name = basename( $file_src );

		nv_insert_logs( NV_LANG_DATA, $module_name, $lang_module['autoinstall_method_packet_module'], 'file name : ' . $themename . '_' . $modulename . ".zip", $admin_info['userid'] );

		$linkgetfile = NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=getfile&amp;mod=nv3_theme_" . $themename . "_" . $modulename . ".zip&amp;checkss=" . md5( $file_name . $client_info['session_id'] . $global_config['sitekey'] ) . "&amp;filename=" . $file_name;

		$xtpl->assign( 'LINKGETFILE', $linkgetfile );
		$xtpl->assign( 'THEMENAME', $themename );
		$xtpl->assign( 'MODULENAME', $modulename );
		$xtpl->assign( 'FILESIZE', nv_convertfromBytes( $filesize ) );

		$xtpl->parse( 'complete' );
		$contents = $xtpl->text( 'complete' );
	}
	include ( NV_ROOTDIR . "/includes/header.php" );
	echo $contents;
	include ( NV_ROOTDIR . "/includes/footer.php" );
}
else
{
	$op = $nv_Request->get_string( NV_OP_VARIABLE, 'get', '' );

	$theme_list = nv_scandir( NV_ROOTDIR . "/themes", array( $global_config['check_theme'], $global_config['check_theme_mobile'] ) );
	foreach( $theme_list as $themes_i )
	{
		if( file_exists( NV_ROOTDIR . '/themes/' . $themes_i . '/config.ini' ) )
		{
			$xtpl->assign( 'THEME', $themes_i );
			$xtpl->parse( 'main.theme' );
		}
	}

	$sql = "SELECT `module_file`, `custom_title` FROM `" . NV_MODULES_TABLE . "` ORDER BY `weight` ASC";
	$result = $db->sql_query( $sql );

	while( $row = $db->sql_fetchrow( $result ) )
	{
		$xtpl->assign( 'MODULE', array( 'module_file' => $row['module_file'], 'custom_title' => $row['custom_title'] ) );
		$xtpl->parse( 'main.module' );
	}

	$xtpl->assign( 'NV_BASE_ADMINURL', NV_BASE_ADMINURL );
	$xtpl->assign( 'NV_NAME_VARIABLE', NV_NAME_VARIABLE );
	$xtpl->assign( 'NV_OP_VARIABLE', NV_OP_VARIABLE );

	$xtpl->assign( 'MODULE_NAME', $module_name );
	$xtpl->assign( 'OP', $op );

	$xtpl->parse( 'main' );
	$contents = $xtpl->text( 'main' );

	include ( NV_ROOTDIR . "/includes/header.php" );
	echo $contents;
	include ( NV_ROOTDIR . "/includes/footer.php" );
}

?>