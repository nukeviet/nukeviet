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
$searchs = array( 'http://www.', 'http://', 'https://www.', 'https://' );
$replaces = array();
$q = str_replace( $searchs, $replaces, $q );
if( ! $q ) return;

$db->sqlreset()
	->select( NV_LANG_DATA . '_title, link' )
	->from( $db_config['prefix'] . '_' . $module_data . '_sources' )
	->where( NV_LANG_DATA . '_title LIKE :title OR link LIKE :link' )
	->order( 'weight ASC' )
	->limit( 50 );

$sth = $db->prepare( $db->sql() );
$sth->bindValue( ':title', '%' . $q . '%', PDO::PARAM_STR );
$sth->bindValue( ':link', '%' . $q . '%', PDO::PARAM_STR );
$sth->execute();

$array_data = array();
while( list( $title, $link ) = $sth->fetch( 3 ) )
{
	$array_data[] = array( 'label' => $title, 'value' => $title );
}

header( 'Cache-Control: no-cache, must-revalidate' );
header( 'Content-type: application/json' );

ob_start( 'ob_gzhandler' );
echo json_encode( $array_data );
exit();