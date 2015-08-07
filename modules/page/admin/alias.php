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
$id = $nv_Request->get_int( 'id', 'post', 0 );

$alias = change_alias( $title );

$stmt = $db->prepare( 'SELECT COUNT(*) FROM ' . NV_PREFIXLANG . '_' . $module_data . ' WHERE id !=' . $id . ' AND alias = :alias' );
$stmt->bindParam( ':alias', $alias, PDO::PARAM_STR );
$stmt->execute();

if( $stmt->fetchColumn() )
{
	$weight = $db->query( 'SELECT MAX(id) FROM ' . NV_PREFIXLANG . '_' . $module_data )->fetchColumn();
	$weight = intval( $weight ) + 1;
	$alias = $alias . '-' . $weight;
}

include NV_ROOTDIR . '/includes/header.php';
echo $alias;
include NV_ROOTDIR . '/includes/footer.php';