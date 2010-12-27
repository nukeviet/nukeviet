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
    $newfolder = htmlspecialchars( trim( $nv_Request->get_string( 'folder', 'post' ) ), ENT_QUOTES );
    if ( ! empty( $newfolder ) && $path != $newfolder )
    {
        @rename( NV_ROOTDIR . '/' . $path . '/' . $image, NV_ROOTDIR . '/' . $newfolder . '/' . $image );
    }
}
?>