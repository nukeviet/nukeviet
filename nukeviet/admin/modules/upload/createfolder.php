<?php

/**
 * @Project NUKEVIET 3.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2012 VINADES.,JSC. All rights reserved
 * @Createdate 2-2-2010 12:55
 */

if( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );

$path = nv_check_path_upload( $nv_Request->get_string( 'path', 'post' ) );
$newname = nv_string_to_filename( htmlspecialchars( trim( $nv_Request->get_string( 'newname', 'post' ) ), ENT_QUOTES ) );

$check_allow_upload_dir = nv_check_allow_upload_dir( $path );

if( ! isset( $check_allow_upload_dir['create_dir'] ) or $check_allow_upload_dir['create_dir'] !== true ) die( "ERROR_" . $lang_module['notlevel'] );
if( empty( $path ) ) die( "ERROR_" . $lang_module['notlevel'] );
if( empty( $newname ) ) die( "ERROR_" . $lang_module['name_nonamefolder'] );

$newpath = $path . '/' . $newname;
if( is_dir( NV_ROOTDIR . '/' . $newpath ) ) die( "ERROR_" . $lang_module['folder_exists'] );

$n_dir = nv_mkdir( NV_ROOTDIR . '/' . $path, $newname );

if( ! empty( $n_dir[0] ) )
{
	nv_loadUploadDirList( false );
	nv_insert_logs( NV_LANG_DATA, $module_name, $lang_module['createfolder'], $newpath, $admin_info['userid'] );
	echo $path . '/' . $newname;
	exit;
}
else
{
	die( "ERROR_" . $n_dir[1] );
}

?>