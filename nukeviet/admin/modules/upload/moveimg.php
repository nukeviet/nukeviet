<?php

/**
 * @Project NUKEVIET 3.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2012 VINADES.,JSC. All rights reserved
 * @Createdate 2-2-2010 12:55
 */

if( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );

$path = nv_check_path_upload( $nv_Request->get_string( 'path', 'post' ) );
$check_allow_upload_dir = nv_check_allow_upload_dir( $path );

if( ! isset( $check_allow_upload_dir['move_file'] ) ) die( "ERROR_" . $lang_module['notlevel'] );

$newfolder = nv_check_path_upload( $nv_Request->get_string( 'newpath', 'post' ) );
$check_allow_upload_dir = nv_check_allow_upload_dir( $newfolder );
if( ! isset( $check_allow_upload_dir['create_file'] ) ) die( "ERROR_" . $lang_module['notlevel'] );

$image = htmlspecialchars( trim( $nv_Request->get_string( 'file', 'post' ) ), ENT_QUOTES );
$image = basename( $image );
if( empty( $image ) or ! is_file( NV_ROOTDIR . '/' . $path . '/' . $image ) ) die( "ERROR_" . $lang_module['errorNotSelectFile'] );

$mirror = $nv_Request->get_int( 'mirror', 'post', 0 );

$file = $image;
$i = 1;
while( file_exists( NV_ROOTDIR . '/' . $newfolder . '/' . $file ) )
{
	$file = preg_replace( '/(.*)(\.[a-zA-Z0-9]+)$/', '\1_' . $i . '\2', $image );
	++$i;
}

if( ! nv_copyfile( NV_ROOTDIR . '/' . $path . '/' . $image, NV_ROOTDIR . '/' . $newfolder . '/' . $file ) ) die( "ERROR_" . $lang_module['errorNotCopyFile'] );

nv_filesList( $newfolder, false, $file );

if( ! $mirror )
{
	@nv_deletefile( NV_ROOTDIR . '/' . $path . '/' . $image );

	$md5_view_image = NV_ROOTDIR . '/' . NV_FILES_DIR . '/images/' . md5( $path . '/' . $image ) . "." . nv_getextension( $image );
	if( file_exists( $md5_view_image ) )
	{
		@nv_deletefile( $md5_view_image );
	}

	nv_filesList( $path, false, '', $image );
}

nv_insert_logs( NV_LANG_DATA, $module_name, $lang_module['move'], $path . '/' . $image . " -> " . $newfolder . '/' . $file, $admin_info['userid'] );

echo $file;
exit;

?>