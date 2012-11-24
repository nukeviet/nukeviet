<?php

/**
 * @Project NUKEVIET 3.1
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2012 VINADES.,JSC. All rights reserved
 * @Createdate 21-04-2011 11:17
 */

if( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );

$sql = "SELECT * FROM `" . NV_PREFIXLANG . "_" . $module_d . "_cat` ORDER BY `order` ASC";
$result = $db->sql_query( $sql );

While( $row = $db->sql_fetchrow( $result ) )
{
	$t_sp = "";
	
	if ($row['lev'] > 0)
	{
		for( $i = 1; $i <= $row['lev']; ++ $i )
		{
			$t_sp .= '&nbsp;&nbsp;&nbsp;&nbsp;';
		}
	}
	
	$arr_cat[$row['catid']] = array(
		'module' => $module, //
		'key' => $row['catid'], //
		'title' => $t_sp . $row['title'], //
		'alias' => $row['alias'],  //
	);
}

?>