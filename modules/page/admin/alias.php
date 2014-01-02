<?php

/**
 * @Project NUKEVIET 3.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2012 VINADES.,JSC. All rights reserved
 * @Createdate 2-10-2010 18:49
 */

if( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );

$title = $nv_Request->get_title( 'title', 'post', '' );
$id = $nv_Request->get_int( 'id', 'post', 0 );

$alias = change_alias( $title );

$number = $db->query( "SELECT COUNT(*) FROM " . NV_PREFIXLANG . "_" . $module_data . " WHERE id !=" . $id . " AND alias = " . $db->dbescape( $alias ) . "" )->fetchColumn();

if( intval( $number ) > 0 )
{
	$result = $db->query( "SHOW TABLE STATUS WHERE name='" . NV_PREFIXLANG . "_" . $module_data . "'" );
	$item = $result->fetch();
	$result->closeCursor();
	if( isset( $item['auto_increment'] ) )
	{
		$alias = $alias . "-" . $item['auto_increment'];
	}
	else
	{
		$weight = $db->query( "SELECT COUNT(*) FROM " . NV_PREFIXLANG . "_" . $module_data . "" )->fetchColumn();
		$weight = intval( $weight ) + 1;
		$alias = $alias . "-" . $weight;
	}
}

include NV_ROOTDIR . '/includes/header.php';
echo $alias;
include NV_ROOTDIR . '/includes/footer.php';

?>