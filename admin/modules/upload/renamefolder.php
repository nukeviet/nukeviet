<?php

/**
 * @Project NUKEVIET 3.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2012 VINADES.,JSC. All rights reserved
 * @Createdate 2-2-2010 12:55
 */

if( ! defined( 'NV_IS_FILE_ADMIN' ) )
	die( 'Stop!!!' );

$path = nv_check_path_upload( $nv_Request->get_string( 'path', 'post' ) );
$newname = nv_string_to_filename( htmlspecialchars( trim( $nv_Request->get_string( 'newname', 'post' ) ), ENT_QUOTES ) );

$check_allow_upload_dir = nv_check_allow_upload_dir( $path );

if( ! isset( $check_allow_upload_dir['rename_dir'] ) or $check_allow_upload_dir['rename_dir'] !== true )
	die( "ERROR_" . $lang_module['notlevel'] );

if( empty( $path ) or $path == NV_UPLOADS_DIR )
	die( "ERROR_" . $lang_module['notlevel'] );

if( empty( $newname ) )
	die( "ERROR_" . $lang_module['rename_nonamefolder'] );

unset( $matches );
preg_match( "/(.*)\/(.*)$/", $path, $matches );
if( ! isset( $matches ) or empty( $matches ) )
	die( "ERROR_" . $lang_module['notlevel'] );

$newpath = $matches[1] . '/' . $newname;
if( is_dir( NV_ROOTDIR . '/' . $newpath ) )
	die( "ERROR_" . $lang_module['folder_exists'] );

if( rename( NV_ROOTDIR . '/' . $path, NV_ROOTDIR . '/' . $newpath ) )
{
	$result = $db->sql_query( "SELECT `did`, `dirname` FROM `" . NV_UPLOAD_GLOBALTABLE . "_dir` WHERE `dirname`='" . $path . "' OR `dirname` LIKE '" . $path . "/%'" );
	while( list( $did, $dirname ) = $db->sql_fetchrow( $result, 1 ) )
	{
		$dirname2 = str_replace( NV_ROOTDIR . '/' . $path, $newpath, NV_ROOTDIR . '/' . $dirname );
		$result_file = $db->sql_query( "SELECT `ext`, `scr`, `title` FROM `" . NV_UPLOAD_GLOBALTABLE . "_file` WHERE `did`='" . $did . "' AND `type` = 'image'" );
		while( list( $ext, $src, $title ) = $db->sql_fetchrow( $result_file, 1 ) )
		{
			$view_image1 = NV_ROOTDIR . "/" . NV_FILES_DIR . "/images/" . md5( $dirname . '/' . $title ) . "." . $ext;
			if( file_exists( $view_image1 ) )
			{
				$src2 = NV_FILES_DIR . "/images/" . md5( $dirname2 . '/' . $title ) . "." . $ext;
				rename( $view_image1, NV_ROOTDIR . "/" . $src2 );
			}
			else
			{
				$src2 = str_replace( NV_ROOTDIR . '/' . $dirname, $dirname2, NV_ROOTDIR . '/' . $dirname . '/' . $title );
			}
			$db->sql_query( "UPDATE `" . NV_UPLOAD_GLOBALTABLE . "_dir` SET `scr` = '" . $src2 . "' WHERE `did` = " . $did . "  AND `title`='" . $title . "'" );
		}
		$db->sql_query( "UPDATE `" . NV_UPLOAD_GLOBALTABLE . "_dir` SET `dirname` = '" . $dirname2 . "' WHERE `did` = " . $did );
	}
	nv_insert_logs( NV_LANG_DATA, $module_name, $lang_module['renamefolder'], $path . " -> " . $newpath, $admin_info['userid'] );
	echo $newpath;
}
else
{
	die( "ERROR_" . $lang_module['rename_error_folder'] );
}
?>