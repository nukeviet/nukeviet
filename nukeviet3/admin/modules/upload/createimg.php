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
    $imagename = htmlspecialchars( trim( $nv_Request->get_string( 'img', 'post' ) ), ENT_QUOTES );
    $width = $nv_Request->get_int( 'width', 'post' );
    $height = $nv_Request->get_int( 'height', 'post' );
    require_once ( NV_ROOTDIR . "/includes/class/image.class.php" );
    $image = new image( NV_ROOTDIR . '/' . $path . '/' . $imagename, NV_MAX_WIDTH, NV_MAX_HEIGHT );
    $image->resizeXY( $width, $height );
    
    $new_imagename = preg_replace( '/(.*)(\.[a-zA-Z]+)$/', '\1_' . $width . '_' . $height . '\2', $imagename );
    
    $i = 1;
    while ( file_exists( NV_ROOTDIR . '/' . $path . '/' . $new_imagename ) )
    {
        $new_imagename = preg_replace( '/(.*)(\.[a-zA-Z]+)$/', '\1_' . $width . '_' . $height . '_' . $i . '\2', $imagename );
        $i ++;
    }
    $image->save( NV_ROOTDIR . '/' . $path, $new_imagename );
    //$image_info = $image->create_Image_info;
    $image->close();
    echo $new_imagename;
}

?>