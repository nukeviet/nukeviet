<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Fri, 10 Jan 2014 04:47:14 GMT
 */

if( ! defined( 'NV_ADMIN' ) ) die( 'Stop!!!' );

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

$submenu['items'] = $lang_module['content_add_items'];
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
$submenu['cat'] = $lang_module['categories'];
$submenu['group'] = $lang_module['group'];
$submenu['blockcat'] = $lang_module['block'];
$submenu['prounit'] = $lang_module['prounit'];
$submenu['money'] = $lang_module['money'];
$submenu['weight'] = $lang_module['weight_unit'];
$submenu['tags'] = $lang_module['tags'];
if( defined( 'NV_IS_SPADMIN' ) )
{
	$submenu['template'] = $lang_module['fields'];
}
$submenu['payport'] = $lang_module['setup_payment'];
$submenu['docpay'] = $lang_module['document_payment'];
$submenu['setting'] = $lang_module['setting'];
