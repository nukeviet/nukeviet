<?php

/**
 * @Project NUKEVIET 3.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2012 VINADES.,JSC. All rights reserved
 * @Createdate 3/7/2010 12:55
 */

if( ! defined( 'NV_IS_FILE_MODULES' ) ) die( 'Stop!!!' );

$func_id = $nv_Request->get_int( 'id', 'post', 0 );
$content = 'ERR_' . $func_id;

if( $func_id > 0 )
{
	$sql = "SELECT `in_submenu` FROM `" . NV_MODFUNCS_TABLE . "` WHERE `func_id`=" . $func_id;
	$result = $db->sql_query( $sql );
	
	if( $db->sql_numrows( $result ) == 1 )
	{
		$row = $db->sql_fetchrow( $result );

		$in_submenu = $row['in_submenu'] ? 0 : 1;
		$sql = "UPDATE `" . NV_MODFUNCS_TABLE . "` SET `in_submenu`=" . $in_submenu . " WHERE `func_id`=" . $func_id;
		$db->sql_query( $sql );
		nv_del_moduleCache( 'modules' );
		$content = 'OK_' . $func_id;
	}
}

include ( NV_ROOTDIR . "/includes/header.php" );
echo $content;
include ( NV_ROOTDIR . "/includes/footer.php" );

?>