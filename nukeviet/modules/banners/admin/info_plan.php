<?php

/**
 * @Project NUKEVIET 3.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2012 VINADES.,JSC. All rights reserved
 * @Createdate 3/13/2010 0:3
 */

if( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );

$id = $nv_Request->get_int( 'id', 'get', 0 );

if( empty( $id ) )
{
	Header( "Location: " . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name );
	die();
}

$sql = "SELECT `title` FROM `" . NV_BANNERS_PLANS_GLOBALTABLE . "` WHERE `id`=" . $id;
$result = $db->sql_query( $sql );
$numrows = $db->sql_numrows( $result );

if( $numrows != 1 )
{
	Header( "Location: " . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name );
	die();
}

$row = $db->sql_fetchrow( $result );

$page_title = $lang_module['info_plan'];

$contents = array();
$contents['containerid'] = array( 'plan_info', 'banners_list' );
$contents['aj'] = array( "nv_plan_info(" . $id . ", 'plan_info');", "nv_show_banners_list('banners_list', 0, " . $id . ", 0);" );

$contents = call_user_func( "nv_info_plan_theme", $contents );
$set_active_op = "plans_list";

include ( NV_ROOTDIR . "/includes/header.php" );
echo nv_admin_theme( $contents );
include ( NV_ROOTDIR . "/includes/footer.php" );

?>