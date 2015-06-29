<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 3/11/2010 23:18
 */

if( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );

$id = $nv_Request->get_int( 'id', 'get', 0 );

$sql = 'SELECT full_name FROM ' . NV_BANNERS_GLOBALTABLE. '_clients WHERE id=' . $id;
$row = $db->query( $sql )->fetch();
if( empty( $row ) )
{
	Header( 'Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name );
	die();
}

$page_title = $lang_module['info_client_title'];

$contents = array();
$contents['containerid'] = array( 'client_info', 'banners_list' );
$contents['aj'] = array( "nv_client_info(" . $id . ", 'client_info');", "nv_show_banners_list('banners_list', " . $id . ", 0, 1);" );

$contents = call_user_func( 'nv_info_client_theme', $contents );

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme( $contents );
include NV_ROOTDIR . '/includes/footer.php';