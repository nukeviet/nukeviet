<?php
/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES.,JSC. All rights reserved
 * @Createdate 2-2-2010 12:55
 */
if ( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );
$path = htmlspecialchars( trim( $nv_Request->get_string( 'path', 'post' ) ), ENT_QUOTES );
$realfolder = end( explode( '/', $path ) );
$remainpath = substr( $path, 0, - strlen( $realfolder ) );
$newname = htmlspecialchars( trim( $nv_Request->get_string( 'newname', 'post' ) ), ENT_QUOTES );
if ( ! empty( $newname ) && $newname != $path && $newname != NV_UPLOADS_DIR && $admin_info['allow_modify_subdirectories'] && nv_check_allow_upload_dir( $path ) )
{
    @rename( NV_ROOTDIR . '/' . $path, NV_ROOTDIR . '/' . $remainpath . change_alias( $newname ) );
}
echo $remainpath . change_alias( $newname );
?>