<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 2-10-2010 18:49
 */

if( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );

$title = $nv_Request->get_title( 'title', 'post', '' );
$alias = change_alias( $title );

$id = $nv_Request->get_int( 'id', 'post', 0 );
$mod = $nv_Request->get_string( 'mod', 'post', '' );

if( $mod == 'cat' )
{
	$tab = NV_PREFIXLANG . '_' . $module_data . '_cat';
	$stmt = $db->prepare( 'SELECT COUNT(*) FROM ' . $tab . ' WHERE catid!=' . $id . ' AND alias= :alias' );
	$stmt->bindParam( ':alias', $alias, PDO::PARAM_STR );
	$stmt->execute();
	$nb = $stmt->fetchColumn();
	if( ! empty( $nb ) )
	{
		$nb = $db->query( 'SELECT MAX(catid) FROM ' . $tab )->fetchColumn();

		$alias .= '-' . ( intval( $nb ) + 1 );
	}
}
elseif( $mod == 'topics' )
{
	$tab = NV_PREFIXLANG . '_' . $module_data . '_topics';
	$stmt = $db->prepare( 'SELECT COUNT(*) FROM ' . $tab . ' WHERE topicid!=' . $id . ' AND alias= :alias' );
	$stmt->bindParam( ':alias', $alias, PDO::PARAM_STR );
	$stmt->execute();
	$nb = $stmt->fetchColumn();
	if( ! empty( $nb ) )
	{
		$nb = $db->query( 'SELECT MAX(topicid) FROM ' . $tab )->fetchColumn();

		$alias .= '-' . ( intval( $nb ) + 1 );
	}
}
elseif( $mod == 'blockcat' )
{
	$tab = NV_PREFIXLANG . '_' . $module_data . '_block_cat';
	$stmt = $db->prepare( 'SELECT COUNT(*) FROM ' . $tab . ' WHERE bid!=' . $id . ' AND alias= :alias' );
	$stmt->bindParam( ':alias', $alias, PDO::PARAM_STR );
	$stmt->execute();
	$nb = $stmt->fetchColumn();
	if( ! empty( $nb ) )
	{
		$nb = $db->query( 'SELECT MAX(bid) FROM ' . $tab )->fetchColumn();

		$alias .= '-' . ( intval( $nb ) + 1 );
	}
}

include NV_ROOTDIR . '/includes/header.php';
echo $alias;
include NV_ROOTDIR . '/includes/footer.php';