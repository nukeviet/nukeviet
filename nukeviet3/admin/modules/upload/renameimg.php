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
if ( ! isset( $check_allow_upload_dir['rename_file'] ) ) die( "ERROR_" . $lang_module['notlevel'] );

$file = htmlspecialchars( trim( $nv_Request->get_string( 'file', 'post' ) ), ENT_QUOTES );
$file = basename( $file );
if ( empty( $file ) or ! is_file( NV_ROOTDIR . '/' . $path . '/' . $file ) ) die( "ERROR_" . $lang_module['errorNotSelectFile'] );

$newname = htmlspecialchars( trim( $nv_Request->get_string( 'newname', 'post' ) ), ENT_QUOTES );
$newname = nv_string_to_filename( basename( $newname ) );
if ( empty( $newname ) ) die( "ERROR_" . $lang_module['rename_noname'] );

$ext = nv_getextension( $file );
$newname = $newname . "." . $ext;

$newname2 = $newname;
$i = 1;
while ( file_exists( NV_ROOTDIR . '/' . $path . '/' . $newname2 ) )
{
    $newname2 = preg_replace( '/(.*)(\.[a-zA-Z0-9]+)$/', '\1_' . $i . '\2', $newname );
    $i++;
}
$newname = $newname2;
if ( ! @rename( NV_ROOTDIR . '/' . $path . '/' . $file, NV_ROOTDIR . '/' . $path . '/' . $newname ) ) die( "ERROR_" . $lang_module['errorNotRenameFile'] );

$results = array();
$md5 = md5( $path );
$tempFile = NV_ROOTDIR . "/" . NV_FILES_DIR . "/dcache/" . $md5;
if ( file_exists( $tempFile ) )
{
    $results = file_get_contents( $tempFile );
    $results = unserialize( $results );
}

unset( $results[$file] );

$results[$newname] = array();
$results[$newname][0] = $newname;

$max = 16;
preg_match( "/^(.+)\.([a-zA-Z0-9]+)$/", $newname, $matches );
if ( strlen( $newname ) > $max )
{
    $results[$newname][0] = substr( $matches[1], 0, ( $max - 3 - strlen( $matches[2] ) ) ) . "..." . $matches[2];
}

$results[$newname][1] = $matches[2];
$results[$newname][2] = "file";

$filesize = @filesize( NV_ROOTDIR . '/' . $path . '/' . $newname );
$results[$newname][3] = nv_convertfromBytes( $filesize );

$results[$newname][4] = NV_BASE_SITEURL . 'images/file.gif';
$results[$newname][5] = 32;
$results[$newname][6] = 32;
$results[$newname][7] = "|";

if ( in_array( $matches[2], $array_images ) )
{
    $size = @getimagesize( NV_ROOTDIR . '/' . $path . '/' . $newname );
    $results[$newname][2] = "image";
    $results[$newname][4] = NV_BASE_SITEURL . $path . '/' . $newname;
    $results[$newname][5] = $size[0];
    $results[$newname][6] = $size[1];
    $results[$newname][7] = $size[0] . "|" . $size[1];

    if ( $size[0] > 80 or $size[1] > 80 )
    {
        if ( ( $_src = nv_get_viewImage( $path . '/' . $newname, 80, 80 ) ) !== false )
        {
            $results[$newname][4] = NV_BASE_SITEURL . $_src[0];
            $results[$newname][5] = $_src[1];
            $results[$newname][6] = $_src[2];
        }
        else
        {
            if ( $results[$newname][5] > 80 )
            {
                $results[$newname][6] = round( 80 / $results[$newname][5] * $results[$newname][6] );
                $results[$newname][5] = 80;
            }

            if ( $results[$newname][6] > 80 )
            {
                $results[$newname][5] = round( 80 / $results[$newname][6] * $results[$newname][5] );
                $results[$newname][6] = 80;
            }
        }
    }
} elseif ( in_array( $matches[2], $array_flash ) )
{
    $results[$newname][2] = "flash";
    $results[$newname][4] = NV_BASE_SITEURL . 'images/flash.gif';

    if ( $matches[2] == "swf" )
    {
        $size = @getimagesize( NV_ROOTDIR . '/' . $path . '/' . $newname );
        if ( isset( $size, $size[0], $size[1] ) )
        {
            $results[$newname][7] = $size[0] . "|" . $size[1];
        }
    }
} elseif ( in_array( $matches[2], $array_archives ) )
{
    $results[$newname][4] = NV_BASE_SITEURL . 'images/zip.gif';
} elseif ( in_array( $matches[2], $array_documents ) )
{
    $results[$newname][4] = NV_BASE_SITEURL . 'images/doc.gif';
}

$results[$newname][8] = $admin_info['userid'];
$results[$newname][9] = NV_CURRENTTIME;

ksort( $results );
file_put_contents( $tempFile, serialize( $results ) );

nv_insert_logs( NV_LANG_DATA, $module_name, $lang_module['rename'], $path . '/' . $file . " -> " . $path . '/' . $newname, $admin_info['userid'] );
$md5_view_image = NV_ROOTDIR . '/' . NV_FILES_DIR . '/images/' . md5( $path . '/' . $file ) . "." . $ext;
if ( file_exists( $md5_view_image ) )
{
    @nv_deletefile( $md5_view_image );
}

echo $newname;
exit;

?>