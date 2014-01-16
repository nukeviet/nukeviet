<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 2-9-2010 14:43
 */

if( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );

$listcid = $nv_Request->get_string( 'list', 'post,get' );

if( ! empty( $listcid ) )
{
	$cid_array = explode( ',', $listcid );
	$cid_array = array_map( 'intval', $cid_array );

	// Xac dinh ID cac bai viet
	$sql = 'SELECT DISTINCT id FROM ' . NV_PREFIXLANG . '_' . $module_data . '_comments WHERE cid in (' . implode( ',', $cid_array ) . ')';
	$query = $db->query( $sql );
	$array_id = array();
	while( list( $id ) = $query->fetch( 3 ) )
	{
		$array_id[] = $id;
	}
	// Het Xac dinh ID cac bai viet

	// Xac dinh cac chu de bai viet
	$array_listcatid = array();
	$query = $db->query( 'SELECT id, listcatid FROM ' . NV_PREFIXLANG . '_' . $module_data . '_rows WHERE id in (' . implode( ',', $array_id ) . ')' );
	while( list( $id, $listcatid ) = $query->fetch( 3 ) )
	{
		$array_listcatid[$id] = explode( ',', $listcatid );
	}

	$db->query( 'DELETE FROM ' . NV_PREFIXLANG . '_' . $module_data . '_comments WHERE cid IN (' . implode( ',', $cid_array ) . ')' );

	foreach( $array_id as $id )
	{
		$numf = $db->query( 'SELECT COUNT(*) FROM ' . NV_PREFIXLANG . '_' . $module_data . '_comments where id= ' . $id . ' AND status=1' )->fetchColumn();
		$db->query( 'UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_rows SET hitscm=' . $numf . ' WHERE id=' . $id );

		$array_catid = $array_listcatid[$id];
		foreach( $array_catid as $catid_i )
		{
			$db->query( 'UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_' . $catid_i . ' SET hitscm=' . $numf . ' WHERE id=' . $id );
		}
	}

	nv_insert_logs( NV_LANG_DATA, $module_name, 'log_del_comment', 'listcid ' . $listcid, $admin_info['userid'] );

	echo $lang_module['comment_delete_success'];
}
else
{
	Header( 'Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=comment' );
	die();
}

?>