<?php

/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES.,JSC. All rights reserved
 * @Createdate 2-2-2010 12:55
 */

if ( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );

$path = htmlspecialchars( trim( $nv_Request->get_string( 'path', 'post' ) ), ENT_QUOTES );

if ( $admin_info['allow_modify_files'] && nv_check_allow_upload_dir( $path ) )
{
    $image = htmlspecialchars( trim( $nv_Request->get_string( 'img', 'post' ) ), ENT_QUOTES );
    $image = basename( $image );

    $newfolder = htmlspecialchars( trim( $nv_Request->get_string( 'folder', 'post' ) ), ENT_QUOTES );
    $newfolder = basename( $newfolder );

    if ( ! empty( $image ) and file_exists( NV_ROOTDIR . '/' . $path . '/' . $image ) and ! empty( $newfolder ) and $newfolder != $path )
    {
        if ( is_dir( NV_ROOTDIR . '/' . $newfolder ) )
        {
            $path2 = NV_ROOTDIR . '/' . $newfolder . '/';
            $image2 = $image;
            $i = 1;
            while ( file_exists( $path2 . $image2 ) )
            {
                $image2 = preg_replace( '/(.*)(\.[a-zA-Z0-9]+)$/', '\1_' . $i . '\2', $image );
                $i++;
            }
            $image2;

            @rename( NV_ROOTDIR . '/' . $path . '/' . $image, $path2 . $image2 );

            $md5_view_image = NV_ROOTDIR . '/files/images/' . md5( $path . '/' . $image ) . "." . nv_getextension( $image );
            if ( file_exists( $md5_view_image ) )
            {
                @nv_deletefile( $md5_view_image );
            }
        }
    }
}

?>