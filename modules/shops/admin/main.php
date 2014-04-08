<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 9-8-2010 14:43
 */

if( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );

$page_title = $lang_module['content_main'];

$link = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE;
$array_info = array();

// Tong so luong san pham
$number = $db->query( 'SELECT COUNT(*) FROM ' . $db_config['prefix'] . '_' . $module_data . '_rows WHERE publtime < ' . NV_CURRENTTIME . ' AND (exptime=0 OR exptime>' . NV_CURRENTTIME . ')' )->fetchColumn();
$array_info[] = array(
	'title' => $lang_module['product_number_all'],
	'value' => $number,
	'link' => $link . '=items',
	'unit' => $lang_module['product_unit']
);

// Tong so luong san pham chua duyet
$number = $db->query( 'SELECT COUNT(*) FROM ' . $db_config['prefix'] . '_' . $module_data . '_rows WHERE status = 0 AND publtime < ' . NV_CURRENTTIME . ' AND (exptime=0 OR exptime>' . NV_CURRENTTIME . ')' )->fetchColumn();

$array_info[] = array(
	'title' => $lang_module['product_number_all_noctive'],
	'value' => $number,
	'link' => $link . '=items',
	'unit' => $lang_module['product_unit']
);
// Tong so luong binh luan
$number = $db->query( 'SELECT COUNT(*) FROM ' . NV_PREFIXLANG . '_comments WHERE module=' . $db->quote( $module_name ) )->fetchColumn();
$array_info[] = array(
	'title' => $lang_module['product_number_commet'],
	'value' => $number,
	'link' => NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=comment&amp;module=' . $module_name,
	'unit' => $lang_module['product_comment']
);

// Tong so luong so luong san pham trong kho
$number = $db->query( 'SELECT SUM(product_number) FROM ' . $db_config['prefix'] . '_' . $module_data . '_rows' )->fetchColumn();
$array_info[] = array(
	'title' => $lang_module['product_number_all_store'],
	'value' => $number,
	'link' => $link . '=items',
	'unit' => $lang_module['product_unit']
);

// Tong so luong don dat hang
$number = $db->query( 'SELECT COUNT(*) FROM ' . $db_config['prefix'] . '_' . $module_data . '_orders' )->fetchColumn();
$array_info[] = array(
	'title' => $lang_module['product_number_order'],
	'value' => $number,
	'link' => $link . '=order',
	'unit' => $lang_module['product_order']
);

// Tong so luong don dat hang moi
$number = $db->query( 'SELECT COUNT(*) FROM ' . $db_config['prefix'] . '_' . $module_data . '_orders WHERE order_view = 0' )->fetchColumn();
$array_info[] = array(
	'title' => $lang_module['product_number_order_new'],
	'value' => $number,
	'link' => $link . '=order',
	'unit' => $lang_module['product_order']
);

// Tong so luong don dat hang nhung chua duyet
$number = $db->query( 'SELECT COUNT(*) FROM ' . $db_config['prefix'] . '_' . $module_data . '_orders WHERE transaction_status = -1' )->fetchColumn();
$array_info[] = array(
	'title' => $lang_module['product_number_order_no_active'],
	'value' => $number,
	'link' => $link . '=order',
	'unit' => $lang_module['product_order']
);

// Tong so luong don dat hang chua thanh toan
$number = $db->query( 'SELECT COUNT(*) FROM ' . $db_config['prefix'] . '_' . $module_data . '_orders WHERE transaction_status = 0' )->fetchColumn();
$array_info[] = array(
	'title' => $lang_module['product_number_order_no_payment'],
	'value' => $number,
	'link' => $link . '=order',
	'unit' => $lang_module['product_order']
);

// Tong so luong don dat hang da thanh toan
$number = $db->query( 'SELECT COUNT(*) FROM ' . $db_config['prefix'] . '_' . $module_data . '_orders WHERE transaction_status = 4' )->fetchColumn();
$array_info[] = array(
	'title' => $lang_module['product_number_order_payment'],
	'value' => $number,
	'link' => $link . '=order',
	'unit' => $lang_module['product_order']
);

// Tong so luong don dat hang da gui thanh toan
$number = $db->query( 'SELECT COUNT(*) FROM ' . $db_config['prefix'] . '_' . $module_data . '_orders WHERE transaction_status = 1' )->fetchColumn();
$array_info[] = array(
	'title' => $lang_module['product_number_order_send_payment'],
	'value' => $number,
	'link' => $link . '=order',
	'unit' => $lang_module['product_order']
);

// Tong so luong don dat hang da gui thanh toan
$number = $db->query( 'SELECT COUNT(*) FROM ' . $db_config['prefix'] . '_' . $module_data . '_orders WHERE transaction_status = 5' )->fetchColumn();
$array_info[] = array(
	'title' => $lang_module['product_number_order_dis_payment'],
	'value' => $number,
	'link' => $link . '=order',
	'unit' => $lang_module['product_order']
);

$xtpl = new XTemplate( 'main.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file );

$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign( 'NV_BASE_SITEURL', NV_BASE_SITEURL );
$xtpl->assign( 'module', $module_info['custom_title'] . ' (' . $module_name . ')' );
$xtpl->assign( 'module_version', '1.0.0 : 20-01-2011' );

$i = 0;
foreach( $array_info as $info )
{
	$xtpl->assign( 'KEY', $info );
	$bg = ( $i % 2 == 0 ) ? 'class="second"' : '';
	$xtpl->assign( 'bg', $bg );
	$xtpl->parse( 'main.loop' );
	++$i;
}

$xtpl->parse( 'main' );
$contents = $xtpl->text( 'main' );

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme( $contents );
include NV_ROOTDIR . '/includes/footer.php';