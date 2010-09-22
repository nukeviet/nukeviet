<?php
/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES.,JSC. All rights reserved
 * @Createdate 2-9-2010 14:43
 */
if ( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );

$imgfolder = htmlspecialchars( trim( $nv_Request->get_string( 'currentpath', 'post,get' ) ), ENT_QUOTES );
if ( is_uploaded_file( $_FILES['upload']['tmp_name'] ) && in_array( NV_UPLOADS_DIR, explode( '/', $imgfolder ) ) )
{
    $CKEditorFuncNum = $nv_Request->get_string( 'CKEditorFuncNum', 'post,get', 0 );
    $type = $nv_Request->get_string( 'type', 'post,get' );
    if ( $type == "image" and in_array( 'images', $admin_info['allow_files_type'] ) )
    {
        $admin_info['allow_files_type'] = array( 
            'images' 
        );
    }
    elseif ( $type == "flash" and in_array( 'flash', $admin_info['allow_files_type'] ) )
    {
        $admin_info['allow_files_type'] = array( 
            'flash' 
        );
    }
    require_once ( NV_ROOTDIR . "/includes/class/upload.class.php" );
    $upload = new upload( $admin_info['allow_files_type'], $global_config['forbid_extensions'], $global_config['forbid_mimes'], NV_UPLOAD_MAX_FILESIZE, NV_MAX_WIDTH, NV_MAX_HEIGHT );
    $upload_info = $upload->save_file( $_FILES['upload'], NV_ROOTDIR . '/' . $imgfolder, false );
    if ( ! empty( $upload_info['error'] ) )
    {
        echo "<script type=\"text/javascript\">window.parent.CKEDITOR.tools.callFunction(" . $CKEditorFuncNum . ", '', '" . $upload_info['error'] . "');</script>";
    }
    else
    {
        echo "<script type=\"text/javascript\">window.parent.CKEDITOR.tools.callFunction(" . $CKEditorFuncNum . ", '" . NV_BASE_SITEURL . $imgfolder . "/" . $upload_info['basename'] . "', '');</script>";
    }
}
?>