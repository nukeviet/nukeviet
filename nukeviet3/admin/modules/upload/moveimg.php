<?php

/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES.,JSC. All rights reserved
 * @Createdate 2-2-2010 12:55
 */

if ( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );

$path = nv_check_path_upload( $nv_Request->get_string( 'path', 'post' ) );

if ( $admin_info['allow_modify_files'] && nv_check_allow_upload_dir( $path ) )
{
    $image = htmlspecialchars( trim( $nv_Request->get_string( 'img', 'post' ) ), ENT_QUOTES );
    $image = basename( $image );
    
    $newfolder = nv_check_path_upload( $nv_Request->get_string( 'folder', 'post' ) );
    if ( ! empty( $image ) and file_exists( NV_ROOTDIR . '/' . $path . '/' . $image ) and ! empty( $newfolder ) and $newfolder != $path )
    {
        if ( is_dir( NV_ROOTDIR . '/' . $newfolder ) )
        {
            if ( nv_check_allow_upload_dir( $newfolder ) )
            {
                $image2 = $image;
                $i = 1;
                while ( file_exists( NV_ROOTDIR . '/' . $newfolder . '/' . $image2 ) )
                {
                    $image2 = preg_replace( '/(.*)(\.[a-zA-Z0-9]+)$/', '\1_' . $i . '\2', $image );
                    $i ++;
                }
                $image2;
                @rename( NV_ROOTDIR . '/' . $path . '/' . $image, NV_ROOTDIR . '/' . $newfolder . '/' . $image2 );
                nv_insert_logs( NV_LANG_DATA, $module_name, $lang_module['move'], $path . '/' . $image . " -> " . $newfolder . '/' . $image2, $admin_info['userid'] );
            }
            $md5_view_image = NV_ROOTDIR . '/' . NV_FILES_DIR . '/images/' . md5( $path . '/' . $image ) . "." . nv_getextension( $image );
            if ( file_exists( $md5_view_image ) )
            {
                @nv_deletefile( $md5_view_image );
            }
        }
    }
}

?>