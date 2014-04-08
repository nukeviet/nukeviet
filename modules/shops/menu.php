<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 21-04-2011 11:17
 */

if( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );

$sql = 'SELECT * FROM ' . $db_config['prefix'] . '_' . $module_d . '_catalogs ORDER BY sort ASC';
$result = $db->query( $sql );

while( $row = $result->fetch() )
{
	$t_sp = '';

	if( $row['lev'] > 0 )
	{
		for( $i = 1; $i <= $row['lev']; ++$i )
		{
			$t_sp .= '&nbsp;&nbsp;&nbsp;&nbsp;';
		}
	}

	$arr_cat[$row['catid']] = array(
		'module' => $module,
		'key' => $row['catid'],
		'title' => $t_sp . $row[NV_LANG_DATA . '_title'],
		'alias' => $row[NV_LANG_DATA . '_alias'],
	);
}