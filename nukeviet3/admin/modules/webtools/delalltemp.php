<?php

/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES.,JSC. All rights reserved
 * @Createdate 9/9/2010, 6:51
 */

if ( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );

$page_title = $lang_module['sitemap'];

$dir = NV_ROOTDIR . "/" . NV_SESSION_SAVE_PATH;
$files = nv_scandir( $dir, "/^(" . nv_preg_quote( "sess_" ) . ")[a-zA-Z0-9\_\.]+$/" );
foreach ( $files as $file )
{
    if ( $file != "sess_" . $client_info['session_id'] )
    {
        @unlink( $dir . '/' . $file );
    }
}

$log_dir = NV_ROOTDIR . "/" . NV_LOGS_DIR . "/dump_backup";
$files = scandir( $log_dir );
foreach ( $files as $file )
{
    if ( preg_match( "/^([a-zA-Z0-9]+)\_([a-zA-Z0-9\-\_]+)\.(sql|sql\.gz)+$/", $file, $mc ) )
    {
        @unlink( $log_dir . "/" . $file );
    }
}

$dir = NV_ROOTDIR . "/" . NV_TEMP_DIR;
$files = nv_scandir( $dir, "/^(" . nv_preg_quote( NV_TEMPNAM_PREFIX ) . ")[a-zA-Z0-9\_\.]+$/" );
foreach ( $files as $file )
{
    @unlink( $dir . '/' . $file );
}

$error_log_fileext = preg_match( "/[a-z]+/i", NV_LOGS_EXT ) ? NV_LOGS_EXT : 'log';
$error_log_filename = preg_match( "/[a-z0-9\_]+/i", NV_ERRORLOGS_FILENAME ) ? NV_ERRORLOGS_FILENAME : 'error_log';

$dir = NV_ROOTDIR . '/' . NV_LOGS_DIR . '/error_logs';
$files = nv_scandir( $dir, "/^([0-9]{2})\-([0-9]{2})-([0-9]{4})\_(" . $error_log_filename . ")\.(" . $error_log_fileext . ")$/" );
if ( ! empty( $files ) )
{
    foreach ( $files as $file )
    {
        @unlink( $dir . '/' . $file );
    }
}
if ( file_exists( $dir . '/sendmail.log' ) )
{
    @unlink( $dir . '/sendmail.log' );
}

$dir = NV_ROOTDIR . '/' . NV_LOGS_DIR . '/error_logs/old';
$files = nv_scandir( $dir, "/^([0-9]{2})\-([0-9]{2})-([0-9]{4})\_(" . $error_log_filename . ")\.(" . $error_log_fileext . ")$/" );
if ( ! empty( $files ) )
{
    foreach ( $files as $file )
    {
        @unlink( $dir . '/' . $file );
    }
}

$dir = NV_ROOTDIR . '/' . NV_LOGS_DIR . '/error_logs/tmp';
$files = nv_scandir( $dir, "/^([0-9]{2})\-([0-9]{2})-([0-9]{4})\_([a-zA-Z0-9]{32})\.(" . $error_log_fileext . ")$/" );
if ( ! empty( $files ) )
{
    foreach ( $files as $file )
    {
        @unlink( $dir . '/' . $file );
    }
}

$dir = NV_ROOTDIR . '/' . NV_LOGS_DIR . '/error_logs/errors256';
$files = nv_scandir( $dir, "/^([0-9]{2}\-[0-9]{4})\_\_([a-zA-Z0-9]{32})\_\_([a-zA-Z0-9]{32})\.(" . $error_log_fileext . ")$/" );
if ( ! empty( $files ) )
{
    foreach ( $files as $file )
    {
        @unlink( $dir . '/' . $file );
    }
}

nv_delete_all_cache();

include ( NV_ROOTDIR . "/includes/header.php" );
echo nv_admin_theme( $contents );
include ( NV_ROOTDIR . "/includes/footer.php" );
?>