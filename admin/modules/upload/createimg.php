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

if( ! isset( $check_allow_upload_dir['create_file'] ) ) die( "ERROR_" . $lang_module['notlevel'] );

$width = $nv_Request->get_int( 'width', 'post' );
$height = $nv_Request->get_int( 'height', 'post' );
$imagename = htmlspecialchars( trim( $nv_Request->get_string( 'img', 'post' ) ), ENT_QUOTES );
$imagename = basename( $imagename );

$file = preg_replace( '/^(.*)(\.[a-zA-Z]+)$/', '\1_' . $width . '_' . $height . '\2', $imagename );

$i = 1;
while( file_exists( NV_ROOTDIR . '/' . $path . '/' . $file ) )
{
	$file = preg_replace( '/^(.*)(\.[a-zA-Z]+)$/', '\1_' . $width . '_' . $height . '_' . $i . '\2', $imagename );
	++$i;
}

require_once ( NV_ROOTDIR . "/includes/class/image.class.php" );
$createImage = new image( NV_ROOTDIR . '/' . $path . '/' . $imagename, NV_MAX_WIDTH, NV_MAX_HEIGHT );
$createImage->resizeXY( $width, $height );
$createImage->save( NV_ROOTDIR . '/' . $path, $file, 75 );
$createImage->close();

nv_filesList( $path, false, $file );
nv_insert_logs( NV_LANG_DATA, $module_name, $lang_module['upload_createimage'], $path . "/" . $file, $admin_info['userid'] );

echo $file;
exit;

?>