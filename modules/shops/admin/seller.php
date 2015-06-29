<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 9-8-2010 14:43
 */

if( !defined( 'NV_IS_FILE_ADMIN' ) )
	die( 'Stop!!!' );

$pro_id = $nv_Request->get_int( 'pro_id', 'get', 0 );
$nv_redirect = $nv_Request->get_title( 'nv_redirect', 'get', '' );

if( empty( $pro_id ) )
{
	Header( 'Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=items' );
	die();
}

$per_page = 50;
$page = $nv_Request->get_int( 'page', 'get', 1 );
$base_url = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;pro_id=' . $pro_id;

// Product info
$pro_info = array();
$sql = 'SELECT id, ' . NV_LANG_DATA . '_title as title, product_unit FROM ' . $db_config['prefix'] . '_' . $module_data . '_rows WHERE id = ' . $pro_id;
$pro_info = $db->query( $sql )->fetch();

// Product unit
$sql = 'SELECT ' . NV_LANG_DATA . '_title FROM ' . $db_config['prefix'] . '_' . $module_data . '_units WHERE id = ' . $pro_info['product_unit'];
list( $pro_info['product_unit'] ) = $db->query( $sql )->fetch( 3 );

$page_title = sprintf( $lang_module['seller_list'], $pro_info['title'] );

$db->sqlreset( )->select( 'COUNT(*)' )->from( $db_config['prefix'] . '_' . $module_data . '_orders_id t1' )->join( ' INNER JOIN ' . $db_config['prefix'] . '_' . $module_data . '_orders t2 ON t1.order_id = t2.order_id' )->where( 't1.proid = ' . $pro_id );
$num_items = $db->query( $db->sql( ) )->fetchColumn( );
if( !$num_items )
{
	Header( 'Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=items' );
	exit( );
}

$xtpl = new XTemplate( 'seller.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file );
$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign( 'GLANG', $lang_global );
$xtpl->assign( 'NV_BASE_ADMINURL', NV_BASE_ADMINURL );
$xtpl->assign( 'NV_NAME_VARIABLE', NV_NAME_VARIABLE );
$xtpl->assign( 'NV_OP_VARIABLE', NV_OP_VARIABLE );
$xtpl->assign( 'MODULE_NAME', $module_name );
$xtpl->assign( 'OP', $op );
$xtpl->assign( 'C_LIST', nv_base64_decode( $nv_redirect ) );

$db->select( 't2.order_name, t2.order_email, t2.order_phone, t2.order_address, t2.unit_total, t2.order_time, t1.num, t1.price' )
	->order( 't1.order_id DESC' )
	->limit( $per_page )
	->offset( ( $page - 1 ) * $per_page );
$sth = $db->prepare( $db->sql() );
$sth->execute();

$i = $page == 1 ? 0 : $page;
$array_total = array( 'price' => 0, 'num' => 0, 'pro_unit' => $pro_config['money_unit'], 'product_unit' => $pro_info['product_unit'] );
while( list( $order_name, $order_email, $order_phone, $order_address, $unit_total, $order_time, $num, $price ) = $sth->fetch(3) )
{
	$i++;
	$price = $price * $num;
	$array_total['price'] += $price;
	$array_total['num'] += $num;
	$xtpl->assign( 'ROW', array(
		'no' => $i,
		'order_name' => $order_name,
		'order_email' => $order_email,
		'order_phone' => $order_phone,
		'order_address' => $order_address,
		'num' => $num,
		'price' => nv_number_format( $price, nv_get_decimals( $unit_total ) ),
		'price_unit' => $unit_total,
		'order_time' => nv_date( 'H:i d/m/Y', $order_time )
	) );
	$xtpl->parse( 'main.loop' );
}

$generate_page = nv_generate_page( $base_url, $num_items, $per_page, $page );
if( !empty( $generate_page ) )
{
	$xtpl->assign( 'GENERATE_PAGE', $generate_page );
	$xtpl->parse( 'main.generate_page' );
}

$array_total['price'] = nv_number_format( $array_total['price'], nv_get_decimals( $array_total['pro_unit'] ) );
$xtpl->assign( 'TOTAL', $array_total );
$xtpl->assign( 'C_REPORT', sprintf( $lang_module['customer_report_display'], $i, $num_items ) );

$xtpl->parse( 'main' );
$contents = $xtpl->text( 'main' );

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme( $contents );
include NV_ROOTDIR . '/includes/footer.php';
