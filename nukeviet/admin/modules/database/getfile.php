<?php

/**
 * @Project NUKEVIET 3.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2012 VINADES.,JSC. All rights reserved
 * @Createdate 2-2-2010 12:55
 */

if( ! defined( 'NV_IS_FILE_DATABASE' ) ) die( 'Stop!!!' );

$filename = filter_text_input( 'filename', 'get', '' );
$checkss = filter_text_input( 'checkss', 'get', '' );
$path_filename = NV_ROOTDIR . "/" . NV_LOGS_DIR . "/dump_backup/" . $filename;

if( file_exists( $path_filename ) and $checkss == md5( $filename . $client_info['session_id'] . $global_config['sitekey'] ) )
{
	nv_insert_logs( NV_LANG_DATA, $module_name, $lang_module['download'], "File name: " . basename( $filename ), $admin_info['userid'] );

	//Download file
	require_once ( NV_ROOTDIR . '/includes/class/download.class.php' );
	
	$name = basename( $path_filename );
	$name_arr = explode( "_", $name );
	
	if( sizeof( $name_arr ) > 1 and strlen( $name_arr[0] ) == 32 )
	{
		$name = substr( $name, 33 );
	}

	$download = new download( $path_filename, NV_ROOTDIR . "/" . NV_LOGS_DIR . "/dump_backup", $name );
	$download->download_file();
	exit();
}
else
{
	$contents = 'File not exist !';

	include ( NV_ROOTDIR . "/includes/header.php" );
	echo nv_admin_theme( $contents );
	include ( NV_ROOTDIR . "/includes/footer.php" );
}

?>