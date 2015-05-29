<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES., JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 3-6-2010 0:14
 */

if( !defined( 'NV_IS_MOD_SHOPS' ) )
	die( 'Stop!!!' );

$id_files = $nv_Request->get_int( 'id_files', 'get', 0 );
$id_rows = $nv_Request->get_int( 'id_rows', 'get', 0 );

if( empty( $id_files ) or empty( $id_rows ) ) die( 'NO' );

$result = $db->query( 'SELECT path FROM ' . $db_config['prefix'] . '_' . $module_data . '_files WHERE id=' . $id_files );
list( $path ) = $result->fetch( 3 );

if( !empty( $path ) )
{
	// Cap nhat luot download
	$db->query( 'UPDATE ' . $db_config['prefix'] . '_' . $module_data . '_files_rows SET download_hits=download_hits+1 WHERE id_rows=' . $id_rows . ' AND id_files=' . $id_files );

	require_once NV_ROOTDIR . '/includes/class/download.class.php';
	$download = new download( NV_ROOTDIR . '/' . NV_UPLOADS_DIR . '/' . $module_name . $path, NV_UPLOADS_REAL_DIR );
	$download->download_file();

	exit();
}