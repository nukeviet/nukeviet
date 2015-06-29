<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 2-10-2010 18:49
 */

if( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );

$sourceid = $nv_Request->get_int( 'sourceid', 'post', 0 );

$contents = "NO_" . $sourceid;
$sourceid = $db->query( "SELECT sourceid FROM " . $db_config['prefix'] . "_" . $module_data . "_sources WHERE sourceid=" . $sourceid )->fetchColumn();
if( $sourceid > 0 )
{
	$check_rows = $db->query( "SELECT COUNT(*) FROM " . $db_config['prefix'] . "_" . $module_data . "_rows WHERE source_id =" . $sourceid )->fetchColumn();

	if( $check_rows > 0 )
	{
		$contents = "ERR_" . sprintf( $lang_module['delsource_msg_rows'], $check_rows );
	}
	else
	{
		$sql = "DELETE FROM " . $db_config['prefix'] . "_" . $module_data . "_sources WHERE sourceid=" . $sourceid;
		if( $db->query( $sql ) )
		{
			nv_fix_source();
			nv_del_moduleCache( $module_name );
			$contents = "OK_" . $sourceid;
		}
	}
}

include NV_ROOTDIR . '/includes/header.php';
echo $contents;
include NV_ROOTDIR . '/includes/footer.php';