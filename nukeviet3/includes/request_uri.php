<?php

/**
 * @Project NUKEVIET 3.0
 * @Author VINADES., JSC (contact@vinades.vn)
 * @Copyright (C) 2011 VINADES ., JSC. All rights reserved
 * @Createdate Apr 5, 2011  11:29:47 AM
 */

if ( ! defined( 'NV_MAINFILE' ) ) die( 'Stop!!!' );

$base_siteurl = pathinfo( $_SERVER['PHP_SELF'], PATHINFO_DIRNAME );
if ( $base_siteurl == '\\' or $base_siteurl == '/' ) $base_siteurl = '';
if ( ! empty( $base_siteurl ) ) $base_siteurl = str_replace( '\\', '/', $base_siteurl );
if ( ! empty( $base_siteurl ) ) $base_siteurl = preg_replace( "/[\/]+$/", '', $base_siteurl );
if ( ! empty( $base_siteurl ) ) $base_siteurl = preg_replace( "/^[\/]*(.*)$/", '/\\1', $base_siteurl );
$base_siteurl = $base_siteurl . "/";

if ( preg_match( "/^" . preg_quote( $base_siteurl, "/" ) . "([a-z0-9\-\_\.\/]+)" . preg_quote( $global_config['rewrite_endurl'], "/" ) . "$/i", $_SERVER['REQUEST_URI'], $matches ) )
{
    $request_uri_array = explode( "/", $matches[1], 3 );
    if ( in_array( $request_uri_array[0], array_keys( $language_array ) ) )
    {
        $_GET[NV_LANG_VARIABLE] = $request_uri_array[0];
        if ( isset( $request_uri_array[1] ) and ! empty( $request_uri_array[1] ) )
        {
            $_GET[NV_NAME_VARIABLE] = $request_uri_array[1];
            if ( isset( $request_uri_array[2] ) and ! empty( $request_uri_array[2] ) )
            {
                $_GET[NV_OP_VARIABLE] = $request_uri_array[2];
            }
        }
    }
    elseif ( ! empty( $request_uri_array[0] ) )
    {
        $_GET[NV_NAME_VARIABLE] = $request_uri_array[0];
        if ( isset( $request_uri_array[1] ) and ! empty( $request_uri_array[1] ) )
        {
            $lop = strlen( $request_uri_array[0] ) + 1;
            $_GET[NV_OP_VARIABLE] = substr( $matches[1], $lop );
        }
    }
    unset( $request_uri_array, $matches );
}

?>