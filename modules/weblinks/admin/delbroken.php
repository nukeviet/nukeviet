<?php

/**
 * @Project NUKEVIET 3.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2012 VINADES.,JSC. All rights reserved
 * @Createdate 2-9-2010 14:43
 */

if( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );

$id = $nv_Request->get_array( 'idcheck', 'post' );

nv_insert_logs( NV_LANG_DATA, $module_name, 'log_del_broken', "id " . $id, $admin_info['userid'] );

foreach( $id as $value )
{
	$sql = "DELETE FROM `" . NV_PREFIXLANG . "_" . $module_data . "_report` WHERE `id`=" . $value;
	$db->sql_query( $sql );
}

Header( "Location: " . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=brokenlink" );
exit();

?>