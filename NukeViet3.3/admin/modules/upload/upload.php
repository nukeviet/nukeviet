<?php

/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2011 VINADES.,JSC. All rights reserved
 * @Createdate 24/1/2011, 1:33
 */

if ( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );

$path = nv_check_path_upload( $nv_Request->get_string( 'path', 'post', NV_UPLOADS_DIR ) );
$check_allow_upload_dir = nv_check_allow_upload_dir( $path );
if ( ! isset( $check_allow_upload_dir['upload_file'] ) ) die( "ERROR_" . $lang_module['notlevel'] );

if ( ! isset( $_FILES, $_FILES['fileupload'], $_FILES['fileupload']['tmp_name'] ) and ! $nv_Request->isset_request( 'fileurl', 'post' ) ) die( "ERROR_" . $lang_module['uploadError1'] );
if ( ! isset( $_FILES ) and ! nv_is_url( $nv_Request->get_string( 'fileurl', 'post' ) ) ) die( "ERROR_" . $lang_module['uploadError2'] );

require_once ( NV_ROOTDIR . "/includes/class/upload.class.php" );
$upload = new upload( $admin_info['allow_files_type'], $global_config['forbid_extensions'], $global_config['forbid_mimes'], NV_UPLOAD_MAX_FILESIZE, NV_MAX_WIDTH, NV_MAX_HEIGHT );

if ( is_uploaded_file( $_FILES['fileupload']['tmp_name'] ) )
{
    $upload_info = $upload->save_file( $_FILES['fileupload'], NV_ROOTDIR . '/' . $path, false );
}
else
{
    $urlfile = trim( $nv_Request->get_string( 'fileurl', 'post' ) );
    $upload_info = $upload->save_urlfile( $urlfile, NV_ROOTDIR . '/' . $path, false );
}

if ( ! empty( $upload_info['error'] ) )
{
    die( "ERROR_" . $upload_info['error'] );
}

nv_filesList( $path, false, $upload_info['basename'] );
nv_insert_logs( NV_LANG_DATA, $module_name, $lang_module['upload_file'], $path . "/" . $upload_info['basename'], $admin_info['userid'] );

echo $upload_info['basename'];
exit;

?>