<?php

/**
 * @Project NUKEVIET 3.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2012 VINADES.,JSC. All rights reserved
 * @Createdate 2-2-2010 12:55
 */

if( ! defined( 'NV_IS_FILE_MODULES' ) ) die( 'Stop!!!' );

$filename = filter_text_input( 'filename', 'get', '' );
$checkss = filter_text_input( 'checkss', 'get', '' );
$mod = filter_text_input( 'mod', 'get', '' );
$path_filename = NV_ROOTDIR . "/" . NV_TEMP_DIR . "/" . $filename;

if( ! empty( $mod ) and file_exists( $path_filename ) and $checkss == md5( $filename . $client_info['session_id'] . $global_config['sitekey'] ) )
{
	//Download file
	require_once ( NV_ROOTDIR . '/includes/class/download.class.php' );
	$download = new download( $path_filename, NV_ROOTDIR . "/" . NV_TEMP_DIR, $mod );
	$download->download_file();
	exit();
}
else
{
	$contents = 'file not exist !';
	include ( NV_ROOTDIR . "/includes/header.php" );
	echo nv_admin_theme( $contents );
	include ( NV_ROOTDIR . "/includes/footer.php" );
}

?>