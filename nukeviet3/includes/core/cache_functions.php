<?php

/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES.,JSC. All rights reserved
 * @Createdate 1/9/2010, 3:21
 */

if ( ! defined( 'NV_MAINFILE' ) ) die( 'Stop!!!' );

/**
 * nv_delete_cache()
 * 
 * @param mixed $pattern
 * @return
 */
function nv_delete_cache( $pattern )
{
    $files = nv_scandir( NV_ROOTDIR . "/" . NV_CACHEDIR, $pattern );

    if ( ! empty( $files ) )
    {
        foreach ( $files as $f )
        {
            nv_deletefile( NV_ROOTDIR . "/" . NV_CACHEDIR . "/" . $f, true );
        }
    }
}

/**
 * nv_delete_all_cache()
 * 
 * @return
 */
function nv_delete_all_cache()
{
    $pattern = "/(.*)\.cache/";
    nv_delete_cache( $pattern );
}

/**
 * nv_get_cache()
 * 
 * @param mixed $filename
 * @return
 */
function nv_get_cache( $filename )
{
    if ( empty( $filename ) or ! preg_match( "/(.*)\.cache/", $filename ) ) return false;
    $filename = basename( $filename );
    if ( ! file_exists( NV_ROOTDIR . "/" . NV_CACHEDIR . "/" . $filename ) ) return false;

    return file_get_contents( NV_ROOTDIR . "/" . NV_CACHEDIR . "/" . $filename );
}

/**
 * nv_set_cache()
 * 
 * @param mixed $filename
 * @param mixed $content
 * @return
 */
function nv_set_cache( $filename, $content )
{
    if ( empty( $filename ) or ! preg_match( "/(.*)\.cache/", $filename ) ) return false;
    $filename = basename( $filename );

    return file_put_contents( NV_ROOTDIR . "/" . NV_CACHEDIR . "/" . $filename, $content, LOCK_EX );
}

?>