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
if( ! isset( $check_allow_upload_dir['delete_file'] ) ) die( "ERROR_" . $lang_module['notlevel'] );

$file = htmlspecialchars( trim( $nv_Request->get_string( 'file', 'post' ) ), ENT_QUOTES );
$file = basename( $file );
if( empty( $file ) or ! is_file( NV_ROOTDIR . '/' . $path . '/' . $file ) ) die( "ERROR_" . $lang_module['errorNotSelectFile'] );

@nv_deletefile( NV_ROOTDIR . '/' . $path . '/' . $file );

$md5_view_image = NV_ROOTDIR . "/" . NV_FILES_DIR . "/images/" . md5( $path . '/' . $file ) . "." . nv_getextension( $file );
if( file_exists( $md5_view_image ) )
{
	@nv_deletefile( $md5_view_image );
}

nv_filesList( $path, false, '', $file );
nv_insert_logs( NV_LANG_DATA, $module_name, $lang_module['upload_delfile'], $path . '/' . $file, $admin_info['userid'] );

echo "OK";

?>