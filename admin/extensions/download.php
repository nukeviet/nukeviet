<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 2-1-2010 22:5
 */

if( ! defined( 'NV_IS_FILE_EXTENSIONS' ) ) die( 'Stop!!!' );

$contents = 'OK';

$request = array();
$request['id'] = $nv_Request->get_int( 'id', 'post', 0 );
$request['fid'] = $nv_Request->get_int( 'fid', 'post', 0 );

// Fixed request
$request['lang'] = NV_LANG_INTERFACE;
$request['basever'] = $global_config['version'];

if( empty( $request['id'] ) or empty( $request['fid'] ) )
{
	$contents = $lang_module['download_error_preparam'];
}
else
{
	$contents = 'Develop Debug';
}

include NV_ROOTDIR . '/includes/header.php';
echo $contents;
include NV_ROOTDIR . '/includes/footer.php';