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

if( ! isset( $check_allow_upload_dir['rename_file'] ) ) die( "ERROR_" . $lang_module['notlevel'] );

$file = htmlspecialchars( trim( $nv_Request->get_string( 'file', 'post' ) ), ENT_QUOTES );
$file = basename( $file );

if( empty( $file ) or ! is_file( NV_ROOTDIR . '/' . $path . '/' . $file ) ) die( "ERROR_" . $lang_module['errorNotSelectFile'] );

$newname = htmlspecialchars( trim( $nv_Request->get_string( 'newname', 'post' ) ), ENT_QUOTES );
$newname = nv_string_to_filename( basename( $newname ) );

if( empty( $newname ) ) die( "ERROR_" . $lang_module['rename_noname'] );

$ext = nv_getextension( $file );
$newname = $newname . "." . $ext;

$newname2 = $newname;

$i = 1;
while( file_exists( NV_ROOTDIR . '/' . $path . '/' . $newname2 ) )
{
	$newname2 = preg_replace( '/(.*)(\.[a-zA-Z0-9]+)$/', '\1_' . $i . '\2', $newname );
	++$i;
}

$newname = $newname2;
if( ! @rename( NV_ROOTDIR . '/' . $path . '/' . $file, NV_ROOTDIR . '/' . $path . '/' . $newname ) ) die( "ERROR_" . $lang_module['errorNotRenameFile'] );

nv_filesList( $path, false, $newname, $file );
nv_insert_logs( NV_LANG_DATA, $module_name, $lang_module['rename'], $path . '/' . $file . " -> " . $path . '/' . $newname, $admin_info['userid'] );
$md5_view_image = NV_ROOTDIR . '/' . NV_FILES_DIR . '/images/' . md5( $path . '/' . $file ) . "." . $ext;

if( file_exists( $md5_view_image ) )
{
	@nv_deletefile( $md5_view_image );
}

echo $newname;
exit;

?>