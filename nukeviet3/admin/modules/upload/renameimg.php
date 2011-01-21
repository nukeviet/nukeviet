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

    $newname = htmlspecialchars( trim( $nv_Request->get_string( 'name', 'post' ) ), ENT_QUOTES );
    $newname = basename( $newname );

    if ( ! empty( $image ) and file_exists( NV_ROOTDIR . '/' . $path . '/' . $image ) and ! empty( $newname ) )
    {
        $path2 = NV_ROOTDIR . '/' . $path . '/';
        $ext = nv_getextension( $image );
        $newname = nv_string_to_filename( $newname ) . "." . $ext;

        $newname2 = $newname;
        $i = 1;
        while ( file_exists( $path2 . $newname2 ) )
        {
            $newname2 = preg_replace( '/(.*)(\.[a-zA-Z0-9]+)$/', '\1_' . $i . '\2', $newname );
            $i++;
        }
        $newname = $newname2;

        if ( $image != $newname )
        {
            @rename( $path2 . $image, $path2 . $newname );
			nv_insert_logs( NV_LANG_DATA, $module_name, $lang_module['rename'], $path . $image ." -> " . $path . $newname, $admin_info['userid'] );
            $md5_view_image = NV_ROOTDIR . '/files/images/' . md5( $path . '/' . $image ) . "." . $ext;
            if ( file_exists( $md5_view_image ) )
            {
                @nv_deletefile( $md5_view_image );
            }
            echo $newname;
        }
    }
}

?>