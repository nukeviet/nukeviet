<?php

/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES.,JSC. All rights reserved
 * @Createdate 31/05/2010, 00:36
 */

if ( ! defined( 'NV_MAINFILE' ) ) die( 'Stop!!!' );

if ( empty( $global_config['site_lang'] ) or ! preg_match( "/^[a-z]{2}$/", $global_config['site_lang'] ) or ! file_exists( NV_ROOTDIR . "/language/" . $global_config['site_lang'] . "/global.php" ) )
{
    if ( ! file_exists( NV_ROOTDIR . "/language/en/global.php" ) ) trigger_error( "Error! Lang file is absent!", 256 );
    $global_config['site_lang'] = "en";
}

if ( defined( 'NV_ADMIN' ) and $global_config['lang_multi'] )
{
    $cookie = $nv_Request->get_string( 'data_lang', 'cookie' );
    if ( empty( $cookie ) or ! preg_match( "/^[a-z]{2}$/", $cookie ) or ! file_exists( NV_ROOTDIR . "/language/" . $cookie . "/global.php" ) ) $cookie = '';
    
    $site_lang = $nv_Request->get_string( NV_LANG_VARIABLE, 'get,post', '' );
    
    if ( preg_match( "/^[a-z]{2}$/", $site_lang ) and file_exists( NV_ROOTDIR . "/language/" . $site_lang . "/global.php" ) )
    {
        if ( $site_lang != $cookie ) $nv_Request->set_Cookie( 'data_lang', $site_lang, NV_LIVE_COOKIE_TIME );
        define( 'NV_LANG_DATA', $site_lang );
    }
    elseif ( ! empty( $cookie ) )
    {
        define( 'NV_LANG_DATA', $cookie );
    }
    else
    {
        $nv_Request->set_Cookie( 'data_lang', $global_config['site_lang'], NV_LIVE_COOKIE_TIME );
        define( 'NV_LANG_DATA', $global_config['site_lang'] );
    }
    
    $cookie = $nv_Request->get_string( 'int_lang', 'cookie' );
    if ( empty( $cookie ) or ! preg_match( "/^[a-z]{2}$/", $cookie ) or ! file_exists( NV_ROOTDIR . "/language/" . $cookie . "/global.php" ) ) $cookie = '';
    
    $langinterface = $nv_Request->get_string( 'langinterface', 'get,post', '' );
    
    if ( preg_match( "/^[a-z]{2}$/", $langinterface ) and file_exists( NV_ROOTDIR . "/language/" . $langinterface . "/global.php" ) )
    {
        if ( $langinterface != $cookie ) $nv_Request->set_Cookie( 'int_lang', $langinterface, NV_LIVE_COOKIE_TIME );
        define( 'NV_LANG_INTERFACE', $langinterface );
    }
    elseif ( ! empty( $cookie ) )
    {
        define( 'NV_LANG_INTERFACE', $cookie );
    }
    else
    {
        $nv_Request->set_Cookie( 'int_lang', $global_config['site_lang'], NV_LIVE_COOKIE_TIME );
        define( 'NV_LANG_INTERFACE', $global_config['site_lang'] );
    }
    unset( $cookie, $site_lang, $langinterface );
}
else
{
    $site_lang = $nv_Request->get_string( NV_LANG_VARIABLE, 'get,post', $global_config['site_lang'] );
    if ( ( in_array( $site_lang, $global_config['allow_sitelangs'] ) ) and file_exists( NV_ROOTDIR . "/language/" . $site_lang . "/global.php" ) )
    {
        define( 'NV_LANG_INTERFACE', $site_lang );
        define( 'NV_LANG_DATA', $site_lang );
    }
    else
    {
        define( 'NV_LANG_INTERFACE', $global_config['site_lang'] );
        define( 'NV_LANG_DATA', $global_config['site_lang'] );
        $my_server_port = ( $_SERVER['SERVER_PORT'] == "80" ) ? "" : ( ":" . $_SERVER['SERVER_PORT'] );
        $my_server_protocol = strtolower( preg_replace( '/^([^\/]+)\/*(.*)$/', '\\1', $_SERVER['SERVER_PROTOCOL'] ) );
        $my_server_protocol .= ( isset( $_SERVER['HTTPS'] ) and $_SERVER['HTTPS'] == "on" ) ? "s" : "";
        Header( "Location: " . $my_server_protocol . '://' . $_SERVER['SERVER_NAME'] . $my_server_port );
        exit();
    }
}

?>