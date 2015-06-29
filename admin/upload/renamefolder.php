<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 2-2-2010 12:55
 */

if( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );

$path = nv_check_path_upload( $nv_Request->get_string( 'path', 'post' ) );
$newname = nv_string_to_filename( htmlspecialchars( trim( $nv_Request->get_string( 'newname', 'post' ) ), ENT_QUOTES ) );

$check_allow_upload_dir = nv_check_allow_upload_dir( $path );

if( ! isset( $check_allow_upload_dir['rename_dir'] ) or $check_allow_upload_dir['rename_dir'] !== true ) die( 'ERROR_' . $lang_module['notlevel'] );

if( empty( $path ) or $path == NV_UPLOADS_DIR ) die( 'ERROR_' . $lang_module['notlevel'] );

if( empty( $newname ) ) die( 'ERROR_' . $lang_module['rename_nonamefolder'] );

unset( $matches );
preg_match( '/(.*)\/([a-z0-9\-\_]+)$/i', $path, $matches );
if( ! isset( $matches ) or empty( $matches ) ) die( 'ERROR_' . $lang_module['notlevel'] );

$newpath = $matches[1] . '/' . $newname;
if( is_dir( NV_ROOTDIR . '/' . $newpath ) ) die( 'ERROR_' . $lang_module['folder_exists'] );

if( rename( NV_ROOTDIR . '/' . $path, NV_ROOTDIR . '/' . $newpath ) )
{
	$action = 0;
	if( preg_match( '/^' . nv_preg_quote( NV_UPLOADS_DIR ) . '\/([a-z0-9\-\_\/]+)$/i', $path, $m1 ) and preg_match( '/^' . nv_preg_quote( NV_UPLOADS_DIR ) . '\/([a-z0-9\-\_\/]+)$/i', $newpath, $m2 ) )
	{
		rename( NV_ROOTDIR . '/' . NV_FILES_DIR . '/' . $m1[1], NV_ROOTDIR . '/' . NV_FILES_DIR . '/' . $m2[1] );
		$action = 1;
		$dir_replace1 = NV_FILES_DIR . '/' . $m1[1] . '/';
		$dir_replace2 = NV_FILES_DIR . '/' . $m2[1] . '/';
	}

	$result = $db->query( "SELECT did, dirname FROM " . NV_UPLOAD_GLOBALTABLE . "_dir WHERE dirname='" . $path . "' OR dirname LIKE '" . $path . "/%'" );
	while( list( $did, $dirname ) = $result->fetch( 3 ) )
	{
		$dirname2 = str_replace( NV_ROOTDIR . '/' . $path, $newpath, NV_ROOTDIR . '/' . $dirname );
		$result_file = $db->query( "SELECT src, title FROM " . NV_UPLOAD_GLOBALTABLE . "_file WHERE did=" . $did . " AND type = 'image'" );
		while( list( $src, $title ) = $result_file->fetch( 3 ) )
		{
			if( $action )
			{
				$src2 = preg_replace( '/^' . nv_preg_quote( $dir_replace1 ) . '/', $dir_replace2, $src );
			}
			else
			{
				$src2 = preg_replace( '/^' . nv_preg_quote( $dirname ) . '/', $dirname2, $src );
			}
			$db->query( "UPDATE " . NV_UPLOAD_GLOBALTABLE . "_file SET src = '" . $src2 . "' WHERE did = " . $did . " AND title='" . $title . "'" );
		}
		$db->query( "UPDATE " . NV_UPLOAD_GLOBALTABLE . "_dir SET dirname = '" . $dirname2 . "' WHERE did = " . $did );
	}
	nv_insert_logs( NV_LANG_DATA, $module_name, $lang_module['renamefolder'], $path . ' -> ' . $newpath, $admin_info['userid'] );
	echo $newpath;
}
else
{
	die( 'ERROR_' . $lang_module['rename_error_folder'] );
}