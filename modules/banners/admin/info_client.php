<?php

/**
 * @Project NUKEVIET 3.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2012 VINADES.,JSC. All rights reserved
 * @Createdate 3/11/2010 23:18
 */

if( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );

$id = $nv_Request->get_int( 'id', 'get', 0 );

if( empty( $id ) )
{
	Header( "Location: " . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name );
	die();
}

$sql = "SELECT `full_name` FROM `" . NV_BANNERS_CLIENTS_GLOBALTABLE . "` WHERE `id`=" . $id;
$result = $db->sql_query( $sql );
$numrows = $db->sql_numrows( $result );

if( $numrows != 1 )
{
	Header( "Location: " . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name );
	die();
}

$row = $db->sql_fetchrow( $result );

$page_title = $lang_module['info_client_title'];

$contents = array();
$contents['containerid'] = array( 'client_info', 'banners_list' );
$contents['aj'] = array( "nv_client_info(" . $id . ", 'client_info');", "nv_show_banners_list('banners_list', " . $id . ", 0, 1);" );

$contents = call_user_func( "nv_info_client_theme", $contents );

include ( NV_ROOTDIR . "/includes/header.php" );
echo nv_admin_theme( $contents );
include ( NV_ROOTDIR . "/includes/footer.php" );

?>