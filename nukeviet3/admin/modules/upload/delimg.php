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
    if ( ! empty( $image ) and file_exists( NV_ROOTDIR . '/' . $path . '/' . $image ) )
    {
        @nv_deletefile( NV_ROOTDIR . '/' . $path . '/' . $image );
        nv_insert_logs( NV_LANG_DATA, $module_name, $lang_module['upload_delfile'], $path . '/' . $image, $admin_info['userid'] );
        $md5_view_image = NV_ROOTDIR . "/'.NV_FILES_DIR.'/images/" . md5( $path . '/' . $image ) . "." . nv_getextension( $image );
        if ( file_exists( $md5_view_image ) )
        {
            @nv_deletefile( $md5_view_image );
        }
    }
}

?>