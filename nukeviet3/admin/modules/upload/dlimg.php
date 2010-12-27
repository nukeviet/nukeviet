<?php
/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES.,JSC. All rights reserved
 * @Createdate 2-2-2010 12:55
 */
if ( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );
$path = htmlspecialchars( trim( $nv_Request->get_string( 'path', 'get' ) ), ENT_QUOTES );
$image = htmlspecialchars( trim( $nv_Request->get_string( 'img', 'get' ) ), ENT_QUOTES );
$path_filename = NV_ROOTDIR . '/' . $path . "/" . $image;
if ( file_exists( $path_filename ) && is_file( $path_filename ) && nv_check_allow_upload_dir($path) )
{
	//Download file
    require_once ( NV_ROOTDIR . '/includes/class/download.class.php' );
    $download = new download( $path_filename, NV_ROOTDIR . '/' . $path, basename( $path_filename ) );
    $download->download_file();
    exit();
}
else
{
    echo 'file not exist !';
}
?>