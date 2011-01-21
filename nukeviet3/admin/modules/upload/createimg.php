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
    $imagename = htmlspecialchars( trim( $nv_Request->get_string( 'img', 'post' ) ), ENT_QUOTES );
    $imagename = basename( $imagename );
    
    $width = $nv_Request->get_int( 'width', 'post' );
    $height = $nv_Request->get_int( 'height', 'post' );
    
    if ( ! empty( $imagename ) and file_exists( NV_ROOTDIR . '/' . $path . '/' . $imagename ) and $width >= 10 and $height >= 10 )
    {
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
        $image->save( NV_ROOTDIR . '/' . $path, $new_imagename, 75 );
        //$image_info = $image->create_Image_info;
        nv_insert_logs( NV_LANG_DATA, $module_name, $lang_module['upload_createimage'], $path . "/" . $new_imagename, $admin_info['userid'] );
        $image->close();
        echo $new_imagename;
    }
}

?>