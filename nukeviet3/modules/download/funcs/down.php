<?php

/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES., JSC. All rights reserved
 * @Createdate 3-6-2010 0:30
 */

if ( ! defined( 'NV_IS_MOD_DOWNLOAD' ) ) die( 'Stop!!!' );

if ( ! $nv_Request->isset_request( 'session_files', 'session' ) )
{
    die( 'Wrong URL' );
}

$session_files = $nv_Request->get_string( 'session_files', 'session', '' );

if ( empty( $session_files ) )
{
    die( 'Wrong URL' );
}

$session_files = unserialize( $session_files );

if ( $nv_Request->isset_request( 'code', 'get' ) )
{
    $code = $nv_Request->get_string( 'code', 'get', '' );

    if ( empty( $code ) or ! preg_match( "/^([a-z0-9]{32})$/i", $code ) or ! isset( $session_files['linkdirect'][$code] ) or ! nv_check_url( $session_files['linkdirect'][$code]['link'] ) )
    {
        die( 'Wrong URL' );
    }

    $sql = "UPDATE `" . NV_PREFIXLANG . "_" . $module_data . "` SET `download_hits`=download_hits+1 WHERE `id`=" . intval( $session_files['linkdirect'][$code]['id'] );
    $db->sql_query( $sql );

    $content = "<br /><img border=\"0\" src=\"" . NV_BASE_SITEURL . "images/load_bar.gif\"><br /><br />\n";
    $content .= sprintf( $lang_module['download_wait2'], $session_files['linkdirect'][$code]['link'] );
    $content .= "<meta http-equiv=\"refresh\" content=\"5;url=" . $session_files['linkdirect'][$code]['link'] . "\" />";

    nv_info_die( $lang_module['download_detail'], $lang_module['download_wait'], $content );

    die();
}

if ( ! $nv_Request->isset_request( 'file', 'get' ) )
{
    die( 'Wrong URL' );
}

$file = $nv_Request->get_string( 'file', 'get', '' );

if ( empty( $file ) )
{
    die( 'Wrong URL' );
}

if ( ! isset( $session_files['fileupload'][$file] ) )
{
    die( 'Wrong URL' );
}

if ( ! file_exists( $session_files['fileupload'][$file]['src'] ) )
{
    die( 'Wrong URL' );
}

if ( ! isset( $session_files['fileupload'][$file]['id'] ) )
{
    die( 'Wrong URL' );
}

$sql = "UPDATE `" . NV_PREFIXLANG . "_" . $module_data . "` SET `download_hits`=download_hits+1 WHERE `id`=" . intval( $session_files['fileupload'][$file]['id'] );
$db->sql_query( $sql );

$upload_dir = "files";
$is_zip = false;

$sql = "SELECT `config_name`, `config_value` FROM `" . NV_PREFIXLANG . "_" . $module_data . "_config` WHERE `config_name`='upload_dir' OR `config_name`='is_zip'";
$result = $db->sql_query( $sql );
while ( $row = $db->sql_fetchrow( $result ) )
{
    if ( $row['config_name'] == 'upload_dir' )
    {
        $upload_dir = $row['config_value'];
    } elseif ( $row['config_name'] == 'is_zip' )
    {
        $is_zip = ( bool )$row['config_value'];
    }
}

if ( $is_zip )
{

    $upload_dir = NV_UPLOADS_REAL_DIR . '/' . $module_name . '/' . $upload_dir;

    $subfile = nv_pathinfo_filename( $file );
    $tem_file = NV_ROOTDIR . '/' . NV_TEMP_DIR . '/' . NV_TEMPNAM_PREFIX . $subfile;

    if ( file_exists( $tem_file ) )
    {
        @nv_deletefile( $tem_file );
    }
    require_once ( NV_ROOTDIR . '/includes/class/pclzip.class.php' );
    $zip = new PclZip( $tem_file );
    $zip->add( $session_files['fileupload'][$file]['src'], PCLZIP_OPT_REMOVE_PATH, $upload_dir );

    if ( isset( $global_config['site_logo'] ) and ! empty( $global_config['site_logo'] ) and file_exists( NV_ROOTDIR . '/images/' . $global_config['site_logo'] ) )
    {
        $zip->add( NV_ROOTDIR . '/images/' . $global_config['site_logo'], PCLZIP_OPT_REMOVE_PATH, NV_ROOTDIR . '/images' );
    }

    if ( file_exists( NV_ROOTDIR . '/' . NV_DATADIR . '/README.txt' ) )
    {
        $zip->add( NV_ROOTDIR . '/' . NV_DATADIR . '/README.txt', PCLZIP_OPT_REMOVE_PATH, NV_ROOTDIR . '/' . NV_DATADIR );
    }

    $filesize = @filesize( $tem_file );

    header( "Pragma: public" );
    header( "Expires: 0" );
    header( "Cache-Control: must-revalidate, post-check=0, pre-check=0" );
    header( "Cache-Control: private", false );
    header( "Content-Type: application/zip" );

    header( "Content-Disposition: attachment; filename=\"" . $subfile . ".zip\";" );
    header( "Content-Transfer-Encoding: binary" );
    header( "Content-Length: " . $filesize );
    readfile( $tem_file );

    exit();
}

header( "Pragma: public" );
header( "Expires: 0" );
header( "Cache-Control: must-revalidate, post-check=0, pre-check=0" );
header( "Cache-Control: private", false );

if ( ! empty( $session_files['fileupload'][$file]['mime'] ) )
{
    header( "Content-Type: " . $session_files['fileupload'][$file]['mime'] );
}

header( "Content-Disposition: attachment; filename=\"" . $file . "\";" );
header( "Content-Transfer-Encoding: binary" );

if ( $session_files['fileupload'][$file]['size'] )
{
    header( "Content-Length: " . $session_files['fileupload'][$file]['size'] );
}

readfile( $session_files['fileupload'][$file]['src'] );

exit();

?>