<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES., JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 3-6-2010 0:14
 */
if( ! defined( 'NV_IS_MOD_SHOPS' ) ) die( 'Stop!!!' );

$page_title = $module_info['custom_title'];
$key_words = $module_info['keywords'];

if( !$pro_config['point_active'] )
{
	Header( 'Location: ' . nv_url_rewrite( NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name, true ) );
	die();
}

if( !defined( 'NV_IS_USER' ) )
{
	$redirect = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=point';
	Header( 'Location: ' . NV_BASE_SITEURL . 'index.php?' . NV_NAME_VARIABLE . '=users&' . NV_OP_VARIABLE . '=login&nv_redirect=' . nv_base64_encode( $redirect ) );
	die( );
}

$data_content = array();
$point = 0;
$per_page = 20;
$page = $nv_Request->get_int( 'page', 'get', 1 );
$base_url = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op;

// Lay so diem hien tai cua khach hang
$result = $db->query( 'SELECT point_total FROM ' . $db_config['prefix'] . '_' . $module_data . '_point WHERE userid = ' . $user_info['userid'] );
if( $result->rowCount() > 0 )
{
	$point = $result->fetchColumn();
	$money = $point * $pro_config['point_conversion'];
}

if( $nv_Request->isset_request( 'paypoint', 'get' ) )
{
	$order_id = $nv_Request->get_int( 'order_id', 'get', 0 );
	$checkss = $nv_Request->get_title( 'checkss', 'get', '' );
	if( empty( $order_id ) or $checkss != md5( $client_info['session_id'] . $global_config['sitekey'] . $order_id ) )
	{
		die( 'NO_' . $lang_module['payment_erorr'] );
	}
	else
	{
		// Lay thong tin don hang
		$result = $db->query( 'SELECT * FROM ' . $db_config['prefix'] . '_' . $module_data . '_orders WHERE order_id=' . $order_id );
		$order_data = $result->fetch();
		$order_point = round( $order_data['order_total'] / $pro_config['point_conversion'] );
		if( empty( $order_data ) )
		{
			die( 'NO_' . $lang_module['payment_erorr'] );
		}
		elseif( $point < $order_point )
		{
			die( 'NO_' . $lang_module['point_payment_error_money'] );
		}
		else
		{
			$transaction_status = 4;
			$payment_id = 0;
			$payment_amount = 0;
			$payment_data = '';
			$payment = '';
			$userid = $user_info['userid'];

			$transaction_id = $db->insert_id( "INSERT INTO " . $db_config['prefix'] . "_" . $module_data . "_transaction (transaction_id, transaction_time, transaction_status, order_id, userid, payment, payment_id, payment_time, payment_amount, payment_data) VALUES (NULL, " . NV_CURRENTTIME . ", '" . $transaction_status . "', '" . $order_id . "', '" . $userid . "', '" . $payment . "', '" . $payment_id . "', " . NV_CURRENTTIME . ", '" . $payment_amount . "', '" . $payment_data . "')" );

			if( $transaction_id > 0 )
			{
				$db->query( "UPDATE " . $db_config['prefix'] . "_" . $module_data . "_orders SET transaction_status=" . $transaction_status . ", transaction_id=" . $transaction_id . ", transaction_count=transaction_count+1 WHERE order_id=" . $order_id );

				// Cap nhat diem tich luy
				UpdatePoint( $order_data );

				$db->query( "UPDATE " . $db_config['prefix'] . "_" . $module_data . "_point SET point_total=point_total - " . $order_point . " WHERE userid=" . $userid );
				$db->query( "INSERT INTO " . $db_config['prefix'] . "_" . $module_data . "_point_history(userid, order_id, point, time) VALUES (" . $userid . ", " . $order_id . ", -" . $order_point . ", " . NV_CURRENTTIME . ")" );
			}
			nv_del_moduleCache( $module_name );
			die( 'OK_' . $lang_module['payment_complete'] );
		}
	}
}

$data_content['point'] = $point;
$data_content['money'] = $point * $pro_config['point_conversion'];
$data_content['money'] = nv_number_format( $data_content['money'], nv_get_decimals( $pro_config['money_unit'] ) );
$data_content['money_unit'] = $pro_config['money_unit'];

// Lich su thuc hien
$db->sqlreset()
  ->select( 'COUNT(*)' )
  ->from( $db_config['prefix'] . '_' . $module_data . '_point_history t1' )
  ->join( 'INNER JOIN ' . $db_config['prefix'] . '_' . $module_data . '_orders t2 ON t1.order_id = t2.order_id' )
  ->where( 'userid = ' . $user_info['userid'] );

$all_page = $db->query( $db->sql() )->fetchColumn();

$db->select( 't1.*, t2.order_code' )
  ->order( 'id DESC' )
  ->limit( $per_page )
  ->offset( ( $page - 1 ) * $per_page );

$_query = $db->query( $db->sql() );
$link_module = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name;
while( $row = $_query->fetch() )
{
	$checkss = md5( $row['order_id'] . $global_config['sitekey'] . session_id() );
	$row['link'] = $link_module . "&amp;" . NV_OP_VARIABLE . "=payment&amp;order_id=" . $row['order_id'] . "&checkss=" . $checkss;
	$data_content['history'][] = $row;
}

$generate_page = nv_generate_page( $base_url, $all_page, $per_page, $page );

$contents = call_user_func( 'point_info', $data_content, $generate_page );

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme( $contents );
include NV_ROOTDIR . '/includes/footer.php';