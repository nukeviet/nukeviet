<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Fri, 10 Jan 2014 04:47:14 GMT
 */

if( ! defined( 'NV_ADMIN' ) ) die( 'Stop!!!' );

// Menu dọc
$shop_module_config = array();
$sql = "SELECT module, config_name, config_value FROM " . NV_CONFIG_GLOBALTABLE . " WHERE lang='" . NV_LANG_DATA . "' and module='" . $module_name . "'";
$list = nv_db_cache( $sql, '', $module_name );
foreach( $list as $row )
{
	$shop_module_config[$row['config_name']] = $row['config_value'];
}

$submenu['order'] = $lang_module['order_title'];

if( $shop_module_config['use_shipping'] == '1' )
{
	$submenu['shipping'] = $lang_module['shipping'];
}

$submenu['order_seller'] = $lang_module['order_seller'];

if( $shop_module_config['review_active'] == '1' )
{
	$submenu['review'] = $lang_module['review'];
}

if( $shop_module_config['active_warehouse'] )
{
	$submenu['warehouse_logs'] = $lang_module['warehouse_logs'];
}
$submenu_price=array();
$submenu_price['updateprice'] = $lang_module['updateprice'];
$submenu['items'] = array( 'title' => $lang_module['content_add_items'], 'submenu' => $submenu_price );

//$submenu['items'] = $lang_module['content_add_items'];
$submenu['content'] = $lang_module['content_add'];
$submenu['discounts'] = $lang_module['discounts'];

if( $shop_module_config['use_coupons'] )
{
	$submenu['coupons'] = $lang_module['coupons'];
}

if( $shop_module_config['point_active'] )
{
	$submenu['point'] = $lang_module['point'];
}

if( $shop_module_config['download_active'] )
{
	$submenu['download'] = $lang_module['download'];
}

$submenu['tags'] = $lang_module['tags'];

$menu_setting = array();
$menu_setting['cat'] = $lang_module['categories'];
$menu_setting['group'] = $lang_module['group'];
$menu_setting['blockcat'] = $lang_module['block'];
$menu_setting['prounit'] = $lang_module['prounit'];
$menu_setting['money'] = $lang_module['money'];
$menu_setting['weight'] = $lang_module['weight_unit'];
if( defined( 'NV_IS_SPADMIN' ) )
{
	if( $shop_module_config['template_active'] )
	{
		$menu_setting['template'] = $lang_module['fields'];
	}
	$menu_setting['tabs'] = $lang_module['tabs'];
}
if( $shop_module_config['active_payment'] )
{
	$menu_setting['payport'] = $lang_module['setup_payment'];
	$menu_setting['docpay'] = $lang_module['document_payment'];
}
$submenu['setting'] = array( 'title' => $lang_module['setting'], 'submenu' => $menu_setting );

// Tài liệu hướng dẫn
$array_url_instruction['carrier_config_items'] = 'http://wiki.nukeviet.vn/nukeviet4:admin:shops:shipping_config';
$array_url_instruction['carrier_config'] = 'http://wiki.nukeviet.vn/nukeviet4:admin:shops:shipping_config';
$array_url_instruction['carrier'] = 'http://wiki.nukeviet.vn/nukeviet4:admin:shops:shipping_config';
$array_url_instruction['shops'] = 'http://wiki.nukeviet.vn/nukeviet4:admin:shops:shipping_config';
$array_url_instruction['shipping_config'] = 'http://wiki.nukeviet.vn/nukeviet4:admin:shops:shipping_config';
$array_url_instruction['shipping'] = 'http://wiki.nukeviet.vn/nukeviet4:admin:shops:shipping';

$array_url_instruction['coupons'] = 'http://wiki.nukeviet.vn/nukeviet4:admin:shops:coupons';
$array_url_instruction['coupons_view'] = 'http://wiki.nukeviet.vn/nukeviet4:admin:shops:coupons';

$array_url_instruction['template'] = 'http://wiki.nukeviet.vn/nukeviet4:admin:shops:template';
$array_url_instruction['template'] = 'http://wiki.nukeviet.vn/nukeviet4:admin:shops:template';

$array_url_instruction['warehouse'] = 'http://wiki.nukeviet.vn/nukeviet4:admin:shops:warehouse';
$array_url_instruction['warehouse_logs'] = 'http://wiki.nukeviet.vn/nukeviet4:admin:shops:warehouse';

$array_url_instruction['order'] = 'http://wiki.nukeviet.vn/nukeviet4:admin:shops:order';
$array_url_instruction['order_view'] = 'http://wiki.nukeviet.vn/nukeviet4:admin:shops:order';

$array_url_instruction['content'] = 'http://wiki.nukeviet.vn/nukeviet4:admin:shops:content';
$array_url_instruction['cat'] = 'http://wiki.nukeviet.vn/nukeviet4:admin:shops:cat';
$array_url_instruction['discount'] = 'http://wiki.nukeviet.vn/nukeviet4:admin:shops:discount';
$array_url_instruction['docpay'] = 'http://wiki.nukeviet.vn/nukeviet4:admin:shops:docpay';
$array_url_instruction['download'] = 'http://wiki.nukeviet.vn/nukeviet4:admin:shops:download';
$array_url_instruction['group'] = 'http://wiki.nukeviet.vn/nukeviet4:admin:shops:group';
$array_url_instruction['items'] = 'http://wiki.nukeviet.vn/nukeviet4:admin:shops:list';
$array_url_instruction['money'] = 'http://wiki.nukeviet.vn/nukeviet4:admin:shops:money';
$array_url_instruction['payport'] = 'http://wiki.nukeviet.vn/nukeviet4:admin:shops:payport';
$array_url_instruction['point'] = 'http://wiki.nukeviet.vn/nukeviet4:admin:shops:point';
$array_url_instruction['unit'] = 'http://wiki.nukeviet.vn/nukeviet4:admin:shops:unit';
$array_url_instruction['review'] = 'http://wiki.nukeviet.vn/nukeviet4:admin:shops:review';
$array_url_instruction['setting'] = 'http://wiki.nukeviet.vn/nukeviet4:admin:shops:setting';
$array_url_instruction['tabs'] = 'http://wiki.nukeviet.vn/nukeviet4:admin:shops:tabs';
$array_url_instruction['tags'] = 'http://wiki.nukeviet.vn/nukeviet4:admin:shops:tags';
$array_url_instruction['weight'] = 'http://wiki.nukeviet.vn/nukeviet4:admin:shops:weight';
$array_url_instruction['block'] = 'http://wiki.nukeviet.vn/nukeviet4:admin:shops:block';
$array_url_instruction['discounts'] = 'http://wiki.nukeviet.vn/nukeviet4:admin:shops:discount';