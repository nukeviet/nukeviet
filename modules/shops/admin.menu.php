<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Fri, 10 Jan 2014 04:47:14 GMT
 */

if( ! defined( 'NV_ADMIN' ) ) die( 'Stop!!!' );

$submenu['order'] = $lang_module['order_title'];
$submenu['shipping'] = $lang_module['shipping'];
$submenu['order_seller'] = $lang_module['order_seller'];

$submenu['items'] = $lang_module['content_add_items'];
$submenu['content'] = $lang_module['content_add'];
$submenu['discounts'] = $lang_module['discounts'];
$submenu['coupons'] = $lang_module['coupons'];
if( isset( $module_config[$module_name] ) and $module_config[$module_name]['point_active'] )
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