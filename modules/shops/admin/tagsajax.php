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
	->select(NV_LANG_DATA . '_keywords')
	->from( $db_config['prefix'] . '_' . $module_data . '_tags')
	->where( NV_LANG_DATA . '_alias LIKE :alias OR ' . NV_LANG_DATA . '_keywords LIKE :keywords' )
	->order( NV_LANG_DATA . '_alias ASC' )
	->limit( 50 );

$sth = $db->prepare( $db->sql() );
$sth->bindValue( ':alias','%' . $q . '%', PDO::PARAM_STR );
$sth->bindValue( ':keywords','%' . $q . '%', PDO::PARAM_STR );
$sth->execute();

$array_data = array();
while( list( $keywords ) = $sth->fetch( 3 ) )
{
	$keywords = explode( ',', $keywords );
	foreach ( $keywords as $_keyword )
	{
		$array_data[] = str_replace('-', ' ', $_keyword) ;
	}
}

header( 'Cache-Control: no-cache, must-revalidate' );
header( 'Content-type: application/json' );

ob_start( 'ob_gzhandler' );
echo json_encode( $array_data );
exit();