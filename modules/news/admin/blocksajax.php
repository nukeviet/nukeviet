<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 2-9-2010 14:43
 */

if( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );

$q = $nv_Request->get_title( 'term', 'get', '', 1 );
if( empty( $q ) ) return;

$db->sqlreset()
	->select('bid, title')
	->from( NV_PREFIXLANG . '_' . $module_data . '_block_cat')
	->where( 'alias LIKE :alias OR title LIKE :title' )
	->order( 'alias ASC' )
	->limit( 50 );

$sth = $db->prepare( $db->sql() );
$sth->bindValue( ':alias','%' . $q . '%', PDO::PARAM_STR );
$sth->bindValue( ':title','%' . $q . '%', PDO::PARAM_STR );
$sth->execute();

$array_data = array();
while( list( $bid, $title ) = $sth->fetch( 3 ) )
{
	$array_data[] = array( 'key' => $bid, 'value' => $title );
}

header( 'Cache-Control: no-cache, must-revalidate' );
header( 'Content-type: application/json' );

ob_start( 'ob_gzhandler' );
echo json_encode( $array_data );
exit();