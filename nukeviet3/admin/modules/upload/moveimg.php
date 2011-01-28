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
if ( ! isset( $check_allow_upload_dir['move_file'] ) ) die( "ERROR_" . $lang_module['notlevel'] );

$newfolder = nv_check_path_upload( $nv_Request->get_string( 'newpath', 'post' ) );
$check_allow_upload_dir = nv_check_allow_upload_dir( $newfolder );
if ( ! isset( $check_allow_upload_dir['create_file'] ) ) die( "ERROR_" . $lang_module['notlevel'] );

$image = htmlspecialchars( trim( $nv_Request->get_string( 'file', 'post' ) ), ENT_QUOTES );
$image = basename( $image );
if ( empty( $image ) or ! is_file( NV_ROOTDIR . '/' . $path . '/' . $image ) ) die( "ERROR_" . $lang_module['errorNotSelectFile'] );

$mirror = $nv_Request->get_int( 'mirror', 'post', 0 );

$file = $image;
$i = 1;
while ( file_exists( NV_ROOTDIR . '/' . $newfolder . '/' . $file ) )
{
    $file = preg_replace( '/(.*)(\.[a-zA-Z0-9]+)$/', '\1_' . $i . '\2', $image );
    $i++;
}

if ( ! nv_copyfile( NV_ROOTDIR . '/' . $path . '/' . $image, NV_ROOTDIR . '/' . $newfolder . '/' . $file ) ) die( "ERROR_" . $lang_module['errorNotCopyFile'] );

$results = array();
$md5 = md5( $newfolder );
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
$results[$file][2] = "file";

$filesize = @filesize( NV_ROOTDIR . '/' . $newfolder . '/' . $file );
$results[$file][3] = nv_convertfromBytes( $filesize );

$results[$file][4] = NV_BASE_SITEURL . 'images/file.gif';
$results[$file][5] = 32;
$results[$file][6] = 32;
$results[$file][7] = "|";

if ( in_array( $matches[2], $array_images ) )
{
    $size = @getimagesize( NV_ROOTDIR . '/' . $newfolder . '/' . $file );
    $results[$file][2] = "image";
    $results[$file][4] = NV_BASE_SITEURL . $newfolder . '/' . $file;
    $results[$file][5] = $size[0];
    $results[$file][6] = $size[1];
    $results[$file][7] = $size[0] . "|" . $size[1];

    if ( $size[0] > 80 or $size[1] > 80 )
    {
        if ( ( $_src = nv_get_viewImage( $newfolder . '/' . $file, 80, 80 ) ) !== false )
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
} elseif ( in_array( $matches[2], $array_flash ) )
{
    $results[$file][2] = "flash";
    $results[$file][4] = NV_BASE_SITEURL . 'images/flash.gif';

    if ( $matches[2] == "swf" )
    {
        $size = @getimagesize( NV_ROOTDIR . '/' . $newfolder . '/' . $file );
        if ( isset( $size, $size[0], $size[1] ) )
        {
            $results[$file][7] = $size[0] . "|" . $size[1];
        }
    }
} elseif ( in_array( $matches[2], $array_archives ) )
{
    $results[$file][4] = NV_BASE_SITEURL . 'images/zip.gif';
} elseif ( in_array( $matches[2], $array_documents ) )
{
    $results[$file][4] = NV_BASE_SITEURL . 'images/doc.gif';
}

$results[$file][8] = $admin_info['userid'];
$results[$file][9] = NV_CURRENTTIME;

ksort( $results );
file_put_contents( $tempFile, serialize( $results ) );

if ( ! $mirror )
{
    $results = array();
    $md5 = md5( $path );
    $tempFile = NV_ROOTDIR . "/" . NV_FILES_DIR . "/dcache/" . $md5;
    if ( file_exists( $tempFile ) )
    {
        $results = file_get_contents( $tempFile );
        $results = unserialize( $results );
    }

    unset( $results[$image] );
    file_put_contents( $tempFile, serialize( $results ) );

    $md5_view_image = NV_ROOTDIR . '/' . NV_FILES_DIR . '/images/' . md5( $path . '/' . $image ) . "." . $matches[2];
    if ( file_exists( $md5_view_image ) )
    {
        @nv_deletefile( $md5_view_image );
    }

    @nv_deletefile( NV_ROOTDIR . '/' . $path . '/' . $image );
}

nv_insert_logs( NV_LANG_DATA, $module_name, $lang_module['move'], $path . '/' . $image . " -> " . $newfolder . '/' . $file, $admin_info['userid'] );

echo $file;
exit;

?>