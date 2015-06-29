<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 2-10-2010 18:49
 */

if( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );

$bid = $nv_Request->get_int( 'bid', 'post', 0 );

$contents = "NO_" . $bid;
$bid = $db->query( "SELECT bid FROM " . $db_config['prefix'] . "_" . $module_data . "_block_cat WHERE bid=" . intval( $bid ) )->fetchColumn();
if( $bid > 0 )
{
	$sql = "DELETE FROM " . $db_config['prefix'] . "_" . $module_data . "_block_cat WHERE bid=" . $bid;
	if( $db->query( $sql ) )
	{
		$sql = "DELETE FROM " . $db_config['prefix'] . "_" . $module_data . "_block WHERE bid=" . $bid;
		$db->query( $sql );

		nv_fix_block_cat();
		nv_del_moduleCache( $module_name );

		$contents = "OK_" . $bid;
	}
}

include NV_ROOTDIR . '/includes/header.php';
echo $contents;
include NV_ROOTDIR . '/includes/footer.php';