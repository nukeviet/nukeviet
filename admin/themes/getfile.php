<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 2-2-2010 12:55
 */

if( ! defined( 'NV_IS_FILE_THEMES' ) ) die( 'Stop!!!' );

$filename = $nv_Request->get_title( 'filename', 'get', '' );
$checkss = $nv_Request->get_title( 'checkss', 'get', '' );
$mod = $nv_Request->get_title( 'mod', 'get', '' );

$path_filename = NV_ROOTDIR . '/' . NV_TEMP_DIR . '/' . $filename;

if( ! empty( $mod ) and file_exists( $path_filename ) and $checkss == md5( $filename . $client_info['session_id'] . $global_config['sitekey'] ) )
{
	//Download file
	require_once NV_ROOTDIR . '/includes/class/download.class.php';
	$download = new download( $path_filename, NV_ROOTDIR . '/' . NV_TEMP_DIR, $mod );
	$download->download_file();
	exit();
}
else
{
	$contents = 'file not exist !';

	include NV_ROOTDIR . '/includes/header.php';
	echo nv_admin_theme( $contents );
	include NV_ROOTDIR . '/includes/footer.php';
}