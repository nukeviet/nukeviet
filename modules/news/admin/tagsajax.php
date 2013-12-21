<?php

/**
 * @Project NUKEVIET 3.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2012 VINADES.,JSC. All rights reserved
 * @Createdate 2-9-2010 14:43
 */

if( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );

$q = $nv_Request->get_title( 'term', 'get', '', 1 );
if( empty( $q ) ) return;

$sql = "SELECT alias FROM `" . NV_PREFIXLANG . "_" . $module_data . "_tags` WHERE `alias` LIKE '%" . $db->dblikeescape( $q ) . "%' OR `keywords` LIKE '%" . $db->dblikeescape( $q ) . "%' ORDER BY `alias` ASC LIMIT 50";
$result = $db->sql_query( $sql );

$array_data = array();
while( list( $alias ) = $db->sql_fetchrow( $result, 1 ) )
{
	$array_data[] = str_replace('-', ' ', $alias) ;
}

header( 'Cache-Control: no-cache, must-revalidate' );
header( 'Content-type: application/json' );

ob_start( 'ob_gzhandler' );
echo json_encode( $array_data );
exit();

?>