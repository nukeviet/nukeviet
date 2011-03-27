<?php

/**
 * @Project NUKEVIET 3.0
 * @Author VINADES., JSC (contact@vinades.vn)
 * @Copyright (C) 2011 VINADES ., JSC. All rights reserved
 * @Createdate Mar 27, 2011  2:49:51 PM
 */

if ( ! defined( 'NV_MAINFILE' ) ) die( 'Stop!!!' );

$lu = strlen( NV_BASE_SITEURL );
$request_uri = substr( $_SERVER['REQUEST_URI'], $lu );

if ( ! isset( $global_config['site_home_module'] ) or empty( $global_config['site_home_module'] ) ) $global_config['site_home_module'] = "news";
$home = 0;
$op = 'main';
if ( preg_match( "/^([a-z0-9\-\_\/])+$/i", $request_uri ) )
{
    $array_request_uri = explode( "/", $request_uri );
    if ( preg_match( "/^[a-z]{2}$/", $array_request_uri[0] ) and in_array( $array_request_uri[0], $global_config['allow_adminlangs'] ) )
    {
        $site_lang = $array_request_uri[0];
        if ( isset( $array_request_uri[1] ) and ! empty( $array_request_uri[1] ) )
        {
            $module_name = $array_request_uri[1];
            $lop = strlen( $module_name ) + 4;
            $op = substr( $request_uri, $lop );
        }
        else
        {
            $home = 1;
            $module_name = $global_config['site_home_module'];
        }
    }
    else
    {
        $site_lang = $global_config['site_lang'];
        if ( isset( $array_request_uri[0] ) and ! empty( $array_request_uri[0] ) )
        {
            $module_name = $array_request_uri[0];
            $lop = strlen( $module_name ) + 1;
            $op = substr( $request_uri, $lop );
        }
        else
        {
            $home = 1;
            $module_name = $global_config['site_home_module'];
        }
    }
    if ( empty( $op ) )
    {
        $op = 'main';
    }
}
else
{
    $site_lang = $nv_Request->get_string( NV_LANG_VARIABLE, 'get,post', $global_config['site_lang'] );
    if ( $nv_Request->isset_request( NV_NAME_VARIABLE, 'get' ) || $nv_Request->isset_request( NV_NAME_VARIABLE, 'post' ) )
    {
        $module_name = $nv_Request->get_string( NV_NAME_VARIABLE, 'post,get' );
    }
    else
    {
        $home = 1;
        $module_name = $global_config['site_home_module'];
    }
    $op = $nv_Request->get_string( NV_OP_VARIABLE, 'post,get', $op );
}
$op = preg_replace( "/[\/]+$/", '', $op );

?>