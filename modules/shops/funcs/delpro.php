<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES., JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 3-6-2010 0:14
 */

if ( ! defined( 'NV_IS_MOD_SHOPS' ) ) die( 'Stop!!!' );
if ( ! defined( 'NV_IS_AJAX' ) ) die( 'Wrong URL' );

$id = $nv_Request->get_int( 'id', 'post,get', 0 );

$contents = "NO_" . $lang_module['profile_del_error'];

list( $id, $user_id ) = $db->query( "SELECT `id` ,`user_id` FROM `" . $db_config ['prefix'] . "_" . $module_data . "_rows` WHERE `id`=" . intval( $id ) )->fetch( 3 );
if( $id > 0 and $user_id == $user_info ['userid'] )
{
	$sql = "DELETE FROM `" . $db_config ['prefix'] . "_" . $module_data . "_rows` WHERE `id`=" . $id;
	if ( $db->exec( $sql ) )
	{
		$contents = "OK_" . $id;
	}
}
echo $contents;

?>