<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 10/03/2010 10:51
 */

if( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );

$sql = "SELECT id, title, alias FROM " . NV_PREFIXLANG . "_" . $module_d . " ORDER BY weight ASC";
$result = $db->query( $sql );

While( $row = $result->fetch() )
{
	
	$arr_cat[$row['id']] = array(
		'module' => $module,
		'key' => $row['id'],
		'title' => $row['title'],
		'alias' => $row['alias'],
	);
}