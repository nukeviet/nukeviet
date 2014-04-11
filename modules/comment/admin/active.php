<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 2-9-2010 14:43
 */

if( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );

$status = $nv_Request->get_int( 'active', 'post' );
$listcid = $nv_Request->get_string( 'list', 'post' );

if( ! empty( $listcid ) )
{
	$status = ( $status == 1 ) ? 1 : 0;
	$cid_array = explode( ',', $listcid );
	$cid_array = array_map( 'intval', $cid_array );
	$listcid = implode( ', ', $cid_array );

	if( defined( 'NV_IS_SPADMIN' ) )
	{
		$db->query( 'UPDATE ' . NV_PREFIXLANG . '_comments SET status=' . $status . ' WHERE cid IN (' . $listcid . ')' );
	}
	elseif( ! empty( $site_mod_comm ) )
	{
		$array_mod_name = array();
		foreach( $site_mod_comm as $module_i => $row )
		{
			$array_mod_name[] = "'" . $module_i . "'";
		}
		$db->query( 'UPDATE ' . NV_PREFIXLANG . '_comments SET status=' . $status . ' WHERE cid IN (' . $listcid . ') AND module IN (' . implode( ', ', $array_mod_name ) . ')' );
	}
	else
	{
		Header( 'Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name );
		die();
	}

	$lang_enable = ( $status == 1 ) ? $lang_module['enable'] : $lang_module['disable'];

	nv_insert_logs( NV_LANG_DATA, $module_name, $lang_module['edit_active'] . ': ' . $lang_enable, 'listcid: ' . $listcid, $admin_info['userid'] );

	// Xac dinh ID cac bai viet
	$sql = 'SELECT DISTINCT id, module FROM ' . NV_PREFIXLANG . '_comments WHERE cid in (' . $listcid . ')';
	$query_comments = $db->query( $sql );
	while( $row = $query_comments->fetch() )
	{
		if( isset( $site_mod_comm[$row['module']] ) )
		{
			$mod_info = $site_mod_comm[$row['module']];
			if( file_exists( NV_ROOTDIR . '/modules/' . $mod_info['module_file'] . '/comment.php' ) )
			{
				include NV_ROOTDIR . '/modules/' . $mod_info['module_file'] . '/comment.php';
			}
		}
	}

	echo $lang_module['update_success'];
}
else
{
	Header( 'Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=comment' );
	die();
}