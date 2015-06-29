<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES (contact@vinades.vn)
 * @Copyright 2014 VINADES. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Apr 21, 2010 4:22:24 PM
 */

if( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );

$t = $nv_Request->get_int( 't', 'get', 0 );

nv_insert_logs( NV_LANG_DATA, $module_name, 'log_del', 'id ' . $t, $admin_info['userid'] );

if( $t == 3 )
{
	$db->query( 'TRUNCATE TABLE ' . NV_PREFIXLANG . '_' . $module_data . '_send' );
	$db->query( 'TRUNCATE TABLE ' . NV_PREFIXLANG . '_' . $module_data . '_reply' );
}
elseif( $t == 2 )
{
	$sends = $nv_Request->get_array( 'sends', 'post', array() );

	if( ! empty( $sends ) )
	{
		$in = implode( ',', $sends );
		$db->query( 'DELETE FROM ' . NV_PREFIXLANG . '_' . $module_data . '_send WHERE id IN (' . $in . ')' );
		$db->query( 'DELETE FROM ' . NV_PREFIXLANG . '_' . $module_data . '_reply WHERE id IN (' . $in . ')' );
	}
}
else
{
	$id = $nv_Request->get_int( 'id', 'get', 0 );

	if( $id )
	{
		$db->query( 'DELETE FROM ' . NV_PREFIXLANG . '_' . $module_data . '_send WHERE id = ' . $id );
		$db->query( 'DELETE FROM ' . NV_PREFIXLANG . '_' . $module_data . '_reply WHERE id = ' . $id );
	}
}

nv_del_moduleCache( $module_name );

Header( 'Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name );
die();