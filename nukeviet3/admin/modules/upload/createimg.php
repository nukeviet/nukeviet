<?php

/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES.,JSC. All rights reserved
 * @Createdate 2-2-2010 12:55
 */

if ( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );

$path = nv_check_path_upload( $nv_Request->get_string( 'path', 'post' ) );
$check_allow_upload_dir = nv_check_allow_upload_dir( $path );

if ( ! isset( $check_allow_upload_dir['create_file'] ) ) die( "ERROR_" . $lang_module['notlevel'] );

$width = $nv_Request->get_int( 'width', 'post' );
$height = $nv_Request->get_int( 'height', 'post' );
$imagename = htmlspecialchars( trim( $nv_Request->get_string( 'img', 'post' ) ), ENT_QUOTES );
$imagename = basename( $imagename );

$file = preg_replace( '/^(.*)(\.[a-zA-Z]+)$/', '\1_' . $width . '_' . $height . '\2', $imagename );
$i = 1;
while ( file_exists( NV_ROOTDIR . '/' . $path . '/' . $file ) )
{
    $file = preg_replace( '/^(.*)(\.[a-zA-Z]+)$/', '\1_' . $width . '_' . $height . '_' . $i . '\2', $imagename );
    $i++;
}

require_once ( NV_ROOTDIR . "/includes/class/image.class.php" );
$createImage = new image( NV_ROOTDIR . '/' . $path . '/' . $imagename, NV_MAX_WIDTH, NV_MAX_HEIGHT );
$createImage->resizeXY( $width, $height );
$createImage->save( NV_ROOTDIR . '/' . $path, $file, 75 );
$createImage->close();

$results = array();
$md5 = md5( $path );
$tempFile = NV_ROOTDIR . "/" . NV_FILES_DIR . "/dcache/" . $md5;
if ( file_exists( $tempFile ) )
{
    $results = file_get_contents( $tempFile );
    $results = unserialize( $results );
}

$results[$file] = array();
$results[$file][0] = $file;

$max = 16;
preg_match( "/^(.+)\.([a-zA-Z0-9]+)$/", $file, $matches );
if ( strlen( $file ) > $max )
{
    $results[$file][0] = substr( $matches[1], 0, ( $max - 3 - strlen( $matches[2] ) ) ) . "..." . $matches[2];
}

$results[$file][1] = $matches[2];
$results[$file][2] = "image";

$filesize = @filesize( NV_ROOTDIR . '/' . $path . '/' . $file );
$results[$file][3] = nv_convertfromBytes( $filesize );
$results[$file][4] = NV_BASE_SITEURL . $path . '/' . $file;

$size = @getimagesize( NV_ROOTDIR . '/' . $path . '/' . $file );
$results[$file][5] = $size[0];
$results[$file][6] = $size[1];
$results[$file][7] = $size[0] . "|" . $size[1];

if ( $size[0] > 80 or $size[1] > 80 )
{
    if ( ( $_src = nv_get_viewImage( $path . '/' . $file, 80, 80 ) ) !== false )
    {
        $results[$file][4] = NV_BASE_SITEURL . $_src[0];
        $results[$file][5] = $_src[1];
        $results[$file][6] = $_src[2];
    }
    else
    {
        if ( $results[$file][5] > 80 )
        {
            $results[$file][6] = round( 80 / $results[$file][5] * $results[$file][6] );
            $results[$file][5] = 80;
        }

        if ( $results[$file][6] > 80 )
        {
            $results[$file][5] = round( 80 / $results[$file][6] * $results[$file][5] );
            $results[$file][6] = 80;
        }
    }
}

$results[$file][8] = $admin_info['userid'];
$results[$file][9] = NV_CURRENTTIME;

ksort( $results );
file_put_contents( $tempFile, serialize( $results ) );

nv_insert_logs( NV_LANG_DATA, $module_name, $lang_module['upload_createimage'], $path . "/" . $file, $admin_info['userid'] );

echo $file;
exit;

?>