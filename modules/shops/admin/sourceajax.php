<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 2-9-2010 14:43
 */

if( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );

$q = $nv_Request->get_title( 'q', 'get', '', 1 );
$searchs = array( 'http://www.', 'http://', 'https://www.', 'https://' );
$replaces = array();
$q = str_replace( $searchs, $replaces, $q );
if( ! $q ) return;

$sql = "SELECT `title`, `link` FROM `" . NV_PREFIXLANG . "_" . $module_data . "_sources` WHERE `title` LIKE '%" . $db->dblikeescape( $q ) . "%' OR `link` LIKE '%" . $db->dblikeescape( $q ) . "%' ORDER BY `weight` ASC";
$result = $db->query( $sql );
while( list( $title, $link ) = $result->fetch( 3 ) )
{
	echo "" . $title . "|" . $link . "\n";
}

?>