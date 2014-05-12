<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 21-04-2011 11:17
 */

if( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );

$sql = 'SELECT * FROM ' . NV_PREFIXLANG . '_' . $mod_data . '_department WHERE act = 1 ORDER BY full_name ASC';
$result = $db->query( $sql );
While( $row = $result->fetch() )
{
	$array_item[$row['id']] = array(
		'key' => $row['id'],
		'title' => $row['full_name'],
		'alias' => $row['id']
	);
}