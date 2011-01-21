<?php
/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES.,JSC. All rights reserved
 * @Createdate 2-2-2010 12:55
 */
if ( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );
$path = nv_check_path_upload( $nv_Request->get_string( 'path', 'post' ) );
$newname = change_alias( htmlspecialchars( trim( $nv_Request->get_string( 'newname', 'post' ) ), ENT_QUOTES ) );

$arr_path = explode( '/', $path );
$realfolder = end( $arr_path );
$remainpath = substr( $path, 0, - strlen( $realfolder ) );
$newpath = $remainpath . $newname;
if ( ! in_array( $path, $allow_upload_dir ) && $path != $newname && $admin_info['allow_modify_subdirectories'] && nv_check_allow_upload_dir( $path ) && nv_check_allow_upload_dir( $newpath ) )
{
    nv_delete_cache_upload( NV_ROOTDIR . '/' . $path );
    nv_insert_logs( NV_LANG_DATA, $module_name, $lang_module['renamefolder'], $path . " -> " . $newpath, $admin_info['userid'] );
    @rename( NV_ROOTDIR . '/' . $path, NV_ROOTDIR . '/' . $newpath );
    echo $newpath;
}
else
{
    echo $path;
}

?>