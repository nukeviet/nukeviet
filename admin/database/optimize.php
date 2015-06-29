<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 2-1-2010 21:47
 */

if( ! defined( 'NV_IS_FILE_DATABASE' ) ) die( 'Stop!!!' );

$tables = $nv_Request->get_title( 'tables', 'post' );

if( empty( $tables ) )
{
	$tables = array();
}
else
{
	$tables = explode( ',', $tables );
}

nv_insert_logs( NV_LANG_DATA, $module_name, $lang_module['optimize'], '', $admin_info['userid'] );

$totalfree = 0;
$tabs = array();

$result = $db->query( "SHOW TABLE STATUS LIKE '" . $db_config['prefix'] . "\_%'" );
while( $item = $result->fetch() )
{
	if( empty( $tables ) or ( ! empty( $tables ) and in_array( $item['name'], $tables ) ) )
	{
		$totalfree += $item['data_free'];
		$tabs[] = substr( $item['name'], strlen( $db_config['prefix'] ) + 1 );
		$db->query( 'OPTIMIZE TABLE ' . $item['name'] );
	}
}
$result->closeCursor();

$totalfree = ! empty( $totalfree ) ? nv_convertfromBytes( $totalfree ) : 0;

$content = sprintf( $lang_module['optimize_result'], implode( ', ', $tabs ), $totalfree );

include NV_ROOTDIR . '/includes/header.php';
echo $content;
include NV_ROOTDIR . '/includes/footer.php';