<?php

/**
 * @Project NUKEVIET 3.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES.,JSC. All rights reserved
 * @Createdate 2-9-2010 14:43
 */

if( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );

$q = filter_text_input( 'q', 'get', "", 1 );
$searchs = array( 'http://www.', 'http://', 'https://www.', 'https://' );
$replaces = array();
$q = str_replace( $searchs, $replaces, $q );
if( ! $q ) return;

$sql = "SELECT `title`, `link` FROM `" . NV_PREFIXLANG . "_" . $module_data . "_sources` WHERE `title` LIKE '%" . $db->dblikeescape( $q ) . "%' OR `link` LIKE '%" . $db->dblikeescape( $q ) . "%' ORDER BY `weight` ASC";
$result = $db->sql_query( $sql );
while( list( $title, $link ) = $db->sql_fetchrow( $result ) )
{
	echo "" . $title . "|" . $link . "\n";
}

?>