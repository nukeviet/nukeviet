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