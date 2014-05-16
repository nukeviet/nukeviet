<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES., JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 3-6-2010 0:14
 */

if( ! defined( 'NV_IS_MOD_SHOPS' ) ) die( 'Stop!!!' );
if( ! defined( 'NV_IS_AJAX' ) ) die( 'Wrong URL' );

$order_id = $nv_Request->get_int( 'order_id', 'get', 0 );
$checkss = $nv_Request->get_string( 'checkss', 'get', '' );
if( $order_id > 0 and $checkss == md5( $order_id . $global_config['sitekey'] . session_id() ) )
{
	$table_name = $db_config['prefix'] . "_" . $module_data . "_orders";
	$re = $db->query( "SELECT * FROM " . $table_name . " WHERE order_id=" . $order_id );
	$data = $re->fetch();
	if( ! empty( $data ) )
	{
		if( $data['status'] == 0 and $data['status'] == 0 )
		{
			$re = $db->query( "DELETE FROM " . $table_name . " WHERE order_id=" . $order_id );
			echo "OK_" . str_replace( "_", "#@#", $lang_module['del_history_ok'] );
			die();
		}
		else
		{
			echo "ERR_" . str_replace( "_", "#@#", $lang_module['del_history_error_status'] );
			die();
		}
	}
	else
	{
		echo "Error";
		die();
	}
}

echo "ERR_Error";