<?php

/**
 * @Project NUKEVIET 3.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2012 VINADES.,JSC. All rights reserved
 * @Createdate 2-10-2010 18:49
 */

if( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );

$title = $nv_Request->get_title( 'title', 'post', '' );
$alias = change_alias( $title );

$id = $nv_Request->get_int( 'id', 'post', 0 );
$mod = $nv_Request->get_string( 'mod', 'post', '' );

if( $mod == "cat" )
{
	$tab = NV_PREFIXLANG . "_" . $module_data . "_cat";
	$nb = $db->query( "SELECT COUNT(*) FROM " . $tab . " WHERE catid!=" . $id . " AND alias=" . $db->dbescape( $alias ) )->fetchColumn();
	if( ! empty( $nb ) )
	{
		$result = $db->query( "SHOW TABLE STATUS WHERE name=" . $db->dbescape( $tab ) );
		$item = $result->fetch();
		$result->closeCursor();

		$alias .= "-" . $item['auto_increment'];
	}
}
elseif( $mod == "topics" )
{
	$tab = NV_PREFIXLANG . "_" . $module_data . "_topics";
	$nb = $db->query( "SELECT COUNT(*) FROM " . $tab . " WHERE topicid!=" . $id . " AND alias=" . $db->dbescape( $alias ) )->fetchColumn();
	if( ! empty( $nb ) )
	{
		$result = $db->query( "SHOW TABLE STATUS WHERE name=" . $db->dbescape( $tab ) );
		$item = $result->fetch();
		$result->closeCursor();

		$alias .= "-" . $item['auto_increment'];
	}
}
elseif( $mod == "blockcat" )
{
	$tab = NV_PREFIXLANG . "_" . $module_data . "_block_cat";
	$nb = $db->query( "SELECT COUNT(*) FROM " . $tab . " WHERE bid!=" . $id . " AND alias=" . $db->dbescape( $alias ) )->fetchColumn();
	if( ! empty( $nb ) )
	{
		$result = $db->query( "SHOW TABLE STATUS WHERE name=" . $db->dbescape( $tab ) );
		$item = $result->fetch();
		$result->closeCursor();

		$alias .= "-" . $item['auto_increment'];
	}
}

include NV_ROOTDIR . '/includes/header.php';
echo $alias;
include NV_ROOTDIR . '/includes/footer.php';

?>