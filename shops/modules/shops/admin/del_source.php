<?php

/**
 * @Project NUKEVIET 3.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES.,JSC. All rights reserved
 * @Createdate 2-10-2010 18:49
 */

if( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );

$sourceid = $nv_Request->get_int( 'sourceid', 'post', 0 );

$contents = "NO_" . $sourceid;
list( $sourceid ) = $db->sql_fetchrow( $db->sql_query( "SELECT `sourceid` FROM `" . $db_config['prefix'] . "_" . $module_data . "_sources` WHERE `sourceid`=" . $sourceid ) );
if( $sourceid > 0 )
{
	list( $check_rows ) = $db->sql_fetchrow( $db->sql_query( "SELECT COUNT(*) FROM `" . $db_config['prefix'] . "_" . $module_data . "_rows` WHERE `source_id` =" . $sourceid ) );
	
	if( $check_rows > 0 )
	{
		$contents = "ERR_" . sprintf( $lang_module['delsource_msg_rows'], $check_rows );
	}
	else
	{
		$sql = "DELETE FROM `" . $db_config['prefix'] . "_" . $module_data . "_sources` WHERE `sourceid`=" . $sourceid;
		if( $db->sql_query( $sql ) )
		{
			$db->sql_freeresult();
			nv_fix_source();
			nv_del_moduleCache( $module_name );
			$contents = "OK_" . $sourceid;
		}
	}
}

include ( NV_ROOTDIR . "/includes/header.php" );
echo $contents;
include ( NV_ROOTDIR . "/includes/footer.php" );

?>