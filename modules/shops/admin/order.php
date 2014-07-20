<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 2-9-2010 14:43
 */

if( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );

$page_title = $lang_module['order_title'];
$table_name = $db_config['prefix'] . "_" . $module_data . "_orders";

$checkss = $nv_Request->get_string( 'checkss', 'get', '' );
$where = '';
$search = array();
if( $checkss == md5( session_id() ) )
{
	$search['order_code'] = $nv_Request->get_string( 'order_code', 'get', '' );
	$search['date_from'] = $nv_Request->get_string( 'from', 'get', '' );
	$search['date_to'] = $nv_Request->get_string( 'to', 'get', '' );
	$search['order_email'] = $nv_Request->get_string( 'order_email', 'get', '' );
	$search['order_payment'] = $nv_Request->get_string( 'order_payment', 'get', '' );
	
	if( ! empty( $search['order_code'] ) )
	{
		$where .= ' AND order_code like "%' . $search['order_code'] . '%"';
	}

	if( ! empty( $search['date_from'] ) )
	{
		if( ! empty( $search['date_from'] ) and preg_match( '/^([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{4})$/', $search['date_from'], $m ) )
		{
			$search['date_from'] = mktime( 0, 0, 0, $m[2], $m[1], $m[3] );
		}
		else
		{
			$search['date_from' ] = NV_CURRENTTIME;
		}
		$where .= ' AND order_time >= ' . $search['date_from'] . '';
	}
	
	if( ! empty( $search['date_to'] ) )
	{
		if( ! empty( $search['date_to'] ) and preg_match( '/^([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{4})$/', $search['date_to'], $m ) )
		{
			$search['date_to'] = mktime( 23, 59, 59, $m[2], $m[1], $m[3] );
		}
		else
		{
			$search['date_to' ] = NV_CURRENTTIME;
		}
		$where .= ' AND order_time <= ' . $search['date_to'] . '';
	}
	
	if( ! empty( $search['order_email'] ) )
	{
		$where .= ' AND order_email like "%' . $search['order_email'] . '%"';
	}
	
	if( $search['order_payment'] != '' )
	{
		$where .= ' AND transaction_status  = ' . $search['order_payment'] . '';
	}
}

$transaction_status = array(
	'4' => $lang_module['history_payment_yes'],
	'3' => $lang_module['history_payment_cancel'],
	'2' => $lang_module['history_payment_check'],
	'1' => $lang_module['history_payment_send'],
	'0' => $lang_module['history_payment_no'],
	'-1' => $lang_module['history_payment_wait']);

$xtpl = new XTemplate( "order.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file );
$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign( 'NV_BASE_ADMINURL', NV_BASE_ADMINURL );
$xtpl->assign( 'NV_NAME_VARIABLE', NV_NAME_VARIABLE );
$xtpl->assign( 'NV_OP_VARIABLE', NV_OP_VARIABLE );
$xtpl->assign( 'MODULE_NAME', $module_name );
$xtpl->assign( 'OP', $op );

$per_page = 20;
$page = $nv_Request->get_int( 'page', 'get', 1 );
$base_url = NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=" . $op;
$count = 0;
$order_info = array( 'num_items' => 0, 'sum_price' => 0, 'sum_unit' => '' );

// Fetch Limit
$db->sqlreset()->select( 'COUNT(*)' )
	->from( $table_name )
	->where( '1=1 ' . $where );

$num_items = $db->query( $db->sql() )->fetchColumn();
$order_info['num_items'] = $num_items;

$db->select( '*' )->where( '1=1 ' . $where )->order( 'order_id DESC' )->limit( $per_page )->offset( ( $page - 1 ) * $per_page );

$query = $db->query( $db->sql() );
while( $row = $query->fetch() )
{
	$acno = 0;
	$price = nv_currency_conversion( $row['order_total'], $row['unit_total'], $pro_config['money_unit'] );
	$order_info['sum_price'] = $order_info['sum_price'] + $price['price'];
	
	if( $row['transaction_status'] == 4 )
	{
		$row['status_payment'] = $transaction_status[4];
	}
	elseif( $row['transaction_status'] == 3 )
	{
		$row['status_payment'] = $transaction_status[3];
	}
	elseif( $row['transaction_status'] == 2 )
	{
		$row['status_payment'] = $transaction_status[2];
	}
	elseif( $row['transaction_status'] == 1 )
	{
		$row['status_payment'] = $transaction_status[1];
	}
	elseif( $row['transaction_status'] == 0 )
	{
		$row['status_payment'] = $transaction_status[0];
	}
	elseif( $row['transaction_status'] == - 1 )
	{
		$row['status_payment'] = $transaction_status[-1];
	}
	else
	{
		$row['status_payment'] = "ERROR";
	}

	$row['link_user'] = NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=users&" . NV_OP_VARIABLE . "=edit&userid=" . $row['user_id'];
	$row['order_time'] = nv_date( "H:i d/m/y", $row['order_time'] );
	$row['order_total'] = nv_number_format( $row['order_total'], $money_config[$pro_config['money_unit']]['decimals'] );

	$xtpl->assign( 'DATA', $row );

	$xtpl->assign( 'order_id', $row['order_id'] . "_" . md5( $row['order_id'] . $global_config['sitekey'] . session_id() ) );
	$xtpl->assign( 'link_view', NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=or_view&order_id=" . $row['order_id'] );

	if( $row['transaction_status'] < 1 )
	{
		$xtpl->assign( 'link_del', NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=or_del&order_id=" . $row['order_id'] . "&checkss=" . md5( $row['order_id'] . $global_config['sitekey'] . session_id() ) );
		$xtpl->parse( 'main.data.row.delete' );
		$xtpl->assign( 'DIS', '' );
	}
	else
	{
		$xtpl->assign( 'DIS', 'disabled="disabled"' );
	}

	if( $row['order_view'] == '0' ) $xtpl->parse( 'main.data.row.bgview' );

	$xtpl->parse( 'main.data.row' );
	++$count;
}

$xtpl->assign( 'URL_CHECK_PAYMENT', NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=checkpayment" );
$xtpl->assign( 'URL_DEL', NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=or_del" );
$xtpl->assign( 'URL_DEL_BACK', NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=" . $op );
$xtpl->assign( 'PAGES', nv_generate_page( $base_url, $num_items, $per_page, $page ) );
$xtpl->parse( 'main.data' );

foreach( $transaction_status as $key => $lang_status )
{
	$xtpl->assign( 'TRAN_STATUS', array( 'key' => $key, 'title' => $lang_status, 'selected' => ( isset( $search['order_payment'] ) and $key == $search['order_payment'] ) ? 'selected="selected"' : '' ) );
	$xtpl->parse( 'main.transaction_status' );
}

if( ! empty( $search['date_from'] ) )
{
	$search['date_from'] = nv_date( 'd/m/Y', $search['date_from'] );
}

if( ! empty( $search['date_to'] ) )
{
	$search['date_to'] = nv_date( 'd/m/Y', $search['date_to'] );
}

$order_info['sum_unit'] = $pro_config['money_unit'];
$order_info['sum_price'] = nv_number_format( $order_info['sum_price'], $money_config[$pro_config['money_unit']]['decimals'] );
$xtpl->assign( 'ORDER_INFO', $order_info );
$xtpl->assign( 'CHECKSESS', md5( session_id() ) );
$xtpl->assign( 'SEARCH', $search );

$xtpl->parse( 'main' );

$contents = $xtpl->text( 'main' );

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme( $contents );
include NV_ROOTDIR . '/includes/footer.php';