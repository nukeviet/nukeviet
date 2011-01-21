<?php

/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES.,JSC. All rights reserved
 * @Createdate 2-2-2010 12:55
 */
if ( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );
$path = nv_check_path_upload( $nv_Request->get_string( 'path', 'post' ) );
if ( ! empty( $path ) && $admin_info['allow_modify_subdirectories'] && nv_check_allow_upload_dir( $path ) && ! in_array( $path, $allow_upload_dir ) )
{
    nv_delete_cache_upload( NV_ROOTDIR . '/' . $path );
    nv_insert_logs( NV_LANG_DATA, $module_name, $lang_module['deletefolder'], $path, $admin_info['userid'] );
    
    nv_deletefile( NV_ROOTDIR . '/' . trim( $path ), true );
}

?>