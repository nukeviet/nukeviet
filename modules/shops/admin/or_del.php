<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 2-10-2010 18:49
 */

if( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );

$order_id = $nv_Request->get_int( 'order_id', 'post,get', 0 );
$checkss = $nv_Request->get_string( 'checkss', 'post,get', 0 );

$contents = "NO_" . $order_id;

if( $order_id > 0 and $checkss == md5( $order_id . $global_config['sitekey'] . session_id() ) )
{
	$result = $db->query( "SELECT * FROM " . $db_config['prefix'] . "_" . $module_data . "_orders WHERE order_id=" . $order_id );
	$data_order = $result->fetch();

	// Cong lai san pham trong kho
	if( $pro_config['active_order_number'] == '0' )
	{
		product_number_order( $data_order['listid'], $data_order['listnum'], "+" );
	}

	$exec = $db->exec( "DELETE FROM " . $db_config['prefix'] . "_" . $module_data . "_orders WHERE order_id=" . $order_id . " AND transaction_status < 1" );
	if( $exec )
	{
		$db->query( "DELETE FROM " . $db_config['prefix'] . "_" . $module_data . "_transaction WHERE order_id=" . $order_id );
		$contents = "OK_" . $order_id;
	}
}
elseif( $nv_Request->isset_request( 'listall', 'post,get' ) )
{
	$listall = $nv_Request->get_string( 'listall', 'post,get' );
	$array_id = explode( ',', $listall );

	foreach( $array_id as $order_i )
	{
		$arr_order_i = explode( "_", $order_i );
		$order_id = intval( $arr_order_i[0] );
		$checkss = trim( $arr_order_i[1] );

		if( $order_id > 0 and $checkss == md5( $order_id . $global_config['sitekey'] . session_id() ) )
		{
			$result = $db->query( "SELECT * FROM " . $db_config['prefix'] . "_" . $module_data . "_orders WHERE order_id=" . $order_id );
			$data_order = $result->fetch();
			$result->closeCursor();

			// Cong lai san pham trong kho
			if( $pro_config['active_order_number'] == '0' )
			{
				product_number_order( $data_order['listid'], $data_order['listnum'], "+" );
			}

			$exec = $db->exec( "DELETE FROM " . $db_config['prefix'] . "_" . $module_data . "_orders WHERE order_id=" . $order_id . " AND transaction_status < 1" );
			if( $exec )
			{
				$db->query( "DELETE FROM " . $db_config['prefix'] . "_" . $module_data . "_transaction WHERE order_id=" . $order_id );
			}
		}
	}
	$contents = "OK_0";
}

nv_del_moduleCache( $module_name );

include NV_ROOTDIR . '/includes/header.php';
echo $contents;
include NV_ROOTDIR . '/includes/footer.php';