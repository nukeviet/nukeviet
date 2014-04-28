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

$xtpl = new XTemplate( "order.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file );
$xtpl->assign( 'LANG', $lang_module );

$per_page = 20;
$page = $nv_Request->get_int( 'page', 'get', 1 );
$base_url = NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=" . $op;
$count = 0;

// Fetch Limit
$db->sqlreset()->select( 'COUNT(*)' )->from( $table_name );

$num_items = $db->query( $db->sql() )->fetchColumn();

$db->select( '*' )->order( 'order_id DESC' )->limit( $per_page )->offset( ( $page - 1 ) * $per_page );
$query = $db->query( $db->sql() );
while( $row = $query->fetch() )
{
	$acno = 0;
	if( $row['transaction_status'] == 4 )
	{
		$row['status_payment'] = $lang_module['history_payment_yes'];
	}
	elseif( $row['transaction_status'] == 3 )
	{
		$row['status_payment'] = $lang_module['history_payment_cancel'];
	}
	elseif( $row['transaction_status'] == 2 )
	{
		$row['status_payment'] = $lang_module['history_payment_check'];
	}
	elseif( $row['transaction_status'] == 1 )
	{
		$row['status_payment'] = $lang_module['history_payment_send'];
	}
	elseif( $row['transaction_status'] == 0 )
	{
		$row['status_payment'] = $lang_module['history_payment_no'];
	}
	elseif( $row['transaction_status'] == - 1 )
	{
		$row['status_payment'] = $lang_module['history_payment_wait'];
	}
	else
	{
		$row['status_payment'] = "ERROR";
	}

	$row['link_user'] = NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=users&" . NV_OP_VARIABLE . "=edit&userid=" . $row['user_id'];
	$row['order_time'] = nv_date( "H:i d/m/y", $row['order_time'] );
	$row['order_total'] = FormatNumber( $row['order_total'], 2, '.', ',' );

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

	$bg = ( $count % 2 == 0 ) ? "class=\"second\"" : "";
	$bgview = ( $row['order_view'] == '0' ) ? "class=\"bgview\"" : "";

	$xtpl->assign( 'bg', $bg );
	$xtpl->assign( 'bgview', $bgview );

	$xtpl->parse( 'main.data.row' );
	++$count;
}

$xtpl->assign( 'URL_CHECK_PAYMENT', NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=checkpayment" );
$xtpl->assign( 'URL_DEL', NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=or_del" );
$xtpl->assign( 'URL_DEL_BACK', NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=" . $op );
$xtpl->assign( 'PAGES', nv_generate_page( $base_url, $num_items, $per_page, $page ) );

$xtpl->parse( 'main.data' );
$xtpl->parse( 'main' );

$contents = $xtpl->text( 'main' );

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme( $contents );
include NV_ROOTDIR . '/includes/footer.php';