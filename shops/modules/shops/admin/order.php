<?php

/**
 * @Project NUKEVIET 3.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES.,JSC. All rights reserved
 * @Createdate 2-9-2010 14:43
 */

if( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );

$page_title = $lang_module['order_title'];
$table_name = $db_config['prefix'] . "_" . $module_data . "_orders";

$xtpl = new XTemplate( "order.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file );
$xtpl->assign( 'LANG', $lang_module );

$per_page = 20;
$page = $nv_Request->get_int( 'page', 'get', 0 );
$base_url = NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=" . $op;
$count = 0;

$sql = "SELECT SQL_CALC_FOUND_ROWS *  FROM `" . $table_name . "` ORDER BY `order_id` DESC LIMIT " . $page . "," . $per_page;
$result = $db->sql_query( $sql );
$result_all = $db->sql_query( "SELECT FOUND_ROWS()" );

list( $numf ) = $db->sql_fetchrow( $result_all );
$all_page = ( $numf ) ? $numf : 1;

while( $row = $db->sql_fetchrow( $result ) )
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
	elseif( $row['transaction_status'] == -1 )
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
	$bgview = ( $row['view'] == '0' ) ? "class=\"bgview\"" : "";
	
	$xtpl->assign( 'bg', $bg );
	$xtpl->assign( 'bgview', $bgview );
	
	$xtpl->parse( 'main.data.row' );
	$count ++;
}

$xtpl->assign( 'URL_CHECK_PAYMENT', NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=checkpayment" );
$xtpl->assign( 'URL_DEL', NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=or_del" );
$xtpl->assign( 'URL_DEL_BACK', NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=" . $op );
$xtpl->assign( 'PAGES', nv_generate_page( $base_url, $all_page, $per_page, $page ) );

$xtpl->parse( 'main.data' );
$xtpl->parse( 'main' );

$contents = $xtpl->text( 'main' );

include ( NV_ROOTDIR . "/includes/header.php" );
echo nv_admin_theme( $contents );
include ( NV_ROOTDIR . "/includes/footer.php" );

?>