<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 2-10-2010 18:49
 */

if( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );

$page_title = $lang_module['order_title'];
$table_name = $db_config['prefix'] . '_' . $module_data . '_orders';

$id = $nv_Request->get_int( 'id', 'post,get', 0 );
$save = $nv_Request->get_string( 'save', 'post', '' );

if( $save == 1 )
{
	$order_id = $nv_Request->get_int( 'order_id', 'post', 0 );

	$db->query( 'UPDATE ' . $table_name . ' SET status = 1 WHERE order_id=' . $order_id );
	Header( 'Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=order' );
}

$data = $db->query( 'SELECT * FROM ' . $table_name . ' WHERE order_id=' . $id )->fetch();

$xtpl = new XTemplate( 'print.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file );
$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign( 'dateup', date( 'd-m-Y', $data['order_time'] ) );
$xtpl->assign( 'moment', date( 'h:i', $data['order_time'] ) );
$xtpl->assign( 'DATA', $data );
$xtpl->assign( 'order_id', $data['order_id'] );
$listid = explode( '|', $data['listid'] );
$listnum = explode( '|', $data['listnum'] );

$i = 0;
foreach( $listid as $id )
{
	$sql = 'SELECT id, ' . NV_LANG_DATA . '_title, product_price,money_unit FROM ' . $db_config['prefix'] . '_' . $module_data . '_rows WHERE id = ' . $id . ' AND status =1 AND publtime < ' . NV_CURRENTTIME . ' AND (exptime=0 OR exptime>' . NV_CURRENTTIME . ')';
	$result = $db->query( $sql );
	list( $id, $title, $product_price, $money_unit ) = $result->fetch( 3 );

	$xtpl->assign( 'product_name', $title );
	$xtpl->assign( 'product_number', $listnum[$i] );
	$xtpl->assign( 'product_price', nv_number_format( $product_price, 2 ) );
	$xtpl->assign( 'product_unit', $money_unit );
	$xtpl->assign( 'tt', $i + 1 );

	$xtpl->parse( 'main.loop' );
	++$i;
}

$xtpl->assign( 'order_total', nv_number_format( $data['order_total'], 2 ) );
$xtpl->assign( 'unit', $data['unit_total'] );

$payment = ( $data['order_total'] == '1' ) ? $lang_module['order_payment'] : $lang_module['order_no_payment'];

$xtpl->assign( 'payment', $payment );

if( $data['status'] != '1' ) $xtpl->parse( 'main.onsubmit' );

$xtpl->parse( 'main' );

$contents = $xtpl->text( 'main' );

include NV_ROOTDIR . '/includes/header.php';
echo $contents;
include NV_ROOTDIR . '/includes/footer.php';