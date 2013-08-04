<?php

/**
 * @Project NUKEVIET 3.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2012 VINADES.,JSC. All rights reserved
 * @Createdate 2-9-2010 14:43
 */

if( ! defined( 'NV_IS_FILE_ADMIN' ) )
	die( 'Stop!!!' );

$q = $nv_Request->get_title( 'term', 'get', '', 1 );
if( empty( $q ) )
	return;

$sql = "SELECT title, link FROM `" . NV_PREFIXLANG . "_" . $module_data . "_sources` WHERE  `title` LIKE '%" . $db->dblikeescape( $q ) . "%' OR `link` LIKE '%" . $db->dblikeescape( $q ) . "%' ORDER BY `weight` ASC LIMIT 50";
$result = $db->sql_query( $sql );

$array_data = array( );
while( list( $title, $link ) = $db->sql_fetchrow( $result ) )
{
	$array_data[] = array(
		'label' => $title . ': ' . $link,
		'value' => $link
	);
}

header( 'Cache-Control: no-cache, must-revalidate' );
header( 'Content-type: application/json' );

ob_start( 'ob_gzhandler' );
echo json_encode( $array_data );
exit( );
?>