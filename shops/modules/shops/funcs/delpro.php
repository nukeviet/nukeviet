<?php

/**
 * @Project NUKEVIET 3.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES., JSC. All rights reserved
 * @Createdate 3-6-2010 0:14
 */

if ( ! defined( 'NV_IS_MOD_SHOPS' ) ) die( 'Stop!!!' );
if ( ! defined( 'NV_IS_AJAX' ) ) die( 'Wrong URL' );

$id = $nv_Request->get_int( 'id', 'post,get', 0 );

$contents = "NO_" . $lang_module['profile_del_error'];

list( $id, $user_id ) = $db->sql_fetchrow( $db->sql_query( "SELECT `id` ,`user_id` FROM `" . $db_config ['prefix'] . "_" . $module_data . "_rows` WHERE `id`=" . intval( $id ) ) );
if( $id > 0 and $user_id == $user_info ['userid'] )
{
	$sql = "DELETE FROM `" . $db_config ['prefix'] . "_" . $module_data . "_rows` WHERE `id`=" . $id;
	if ( $db->sql_query( $sql ) )
	{
		$db->sql_freeresult();
		$contents = "OK_" . $id;
	}
}
echo $contents;

?>