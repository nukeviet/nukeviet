<?php

/**
 * @Project NUKEVIET 3.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2012 VINADES.,JSC. All rights reserved
 * @Createdate 2-1-2010 21:47
 */

if( ! defined( 'NV_IS_FILE_DATABASE' ) ) die( 'Stop!!!' );

$tables = filter_text_input( 'tables', 'post' );

if( empty( $tables ) )
{
	$tables = array();
}
else
{
	$tables = explode( ",", $tables );
}

nv_insert_logs( NV_LANG_DATA, $module_name, $lang_module['optimize'], "", $admin_info['userid'] );

$totalfree = 0;
$tabs = array();
$result = $db->sql_query( "SHOW TABLE STATUS LIKE '" . $db_config['prefix'] . "\_%'" );

while( $item = $db->sql_fetch_assoc( $result ) )
{
	if( empty( $tables ) or ( ! empty( $tables ) and in_array( $item['Name'], $tables ) ) )
	{
		$totalfree += $item['Data_free'];
		$tabs[] = substr( $item['Name'], strlen( $db_config['prefix'] ) + 1 );
		$db->sql_query( "LOCK TABLE " . $item['Name'] . " WRITE" );
		$db->sql_query( "REPAIR TABLE " . $item['Name'] );
		$db->sql_query( "OPTIMIZE TABLE " . $item['Name'] );
		$db->sql_query( "UNLOCK TABLE " . $item['Name'] );
	}
}
$db->sql_freeresult( $result );

$totalfree = ! empty( $totalfree ) ? nv_convertfromBytes( $totalfree ) : 0;

$content = sprintf( $lang_module['optimize_result'], implode( ", ", $tabs ), $totalfree );

include ( NV_ROOTDIR . "/includes/header.php" );
echo $content;
include ( NV_ROOTDIR . "/includes/footer.php" );

?>