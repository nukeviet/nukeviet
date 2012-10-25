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
	$temp = explode( "_", $filename );
	
	nv_insert_logs( NV_LANG_DATA, $module_name, $lang_global['delete'] . " " . strtolower( $lang_module['file_backup'] ), "File name: " . end( $temp ), $admin_info['userid'] );

	nv_deletefile( $path_filename );
	
	Header( 'Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=file&rand=' . nv_genpass() );
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