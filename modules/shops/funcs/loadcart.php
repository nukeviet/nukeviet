<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES., JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 3-6-2010 0:14
 */

if( ! defined( 'NV_IS_MOD_SHOPS' ) ) die( 'Stop!!!' );

$num = isset( $_SESSION[$module_data . '_cart'] ) ? count( $_SESSION[$module_data . '_cart'] ) : 0;
$total = 0;
if( ! empty( $_SESSION[$module_data . '_cart'] ) )
{
	foreach( $_SESSION[$module_data . '_cart'] as $pro_id => $info )
	{
		$total = $total + $info['price'] * $info['num'];
	}
}

if( $pro_config['active_price'] == '0' ) $total = 0;
$total = FormatNumber( $total, 2, '.', ',' );

$lang_tmp['cart_title'] = $lang_module['cart_title'];
$lang_tmp['cart_product_title'] = $lang_module['cart_product_title'];
$lang_tmp['cart_product_total'] = $lang_module['cart_product_total'];
$lang_tmp['cart_check_out'] = $lang_module['cart_check_out'];
$lang_tmp['history_title'] = $lang_module['history_title'];
$lang_tmp['active_order_dis'] = $lang_module['active_order_dis'];

$xtpl = new XTemplate( "block.cart.tpl", NV_ROOTDIR . "/themes/" . $module_info['template'] . "/modules/" . $module_file );
$xtpl->assign( 'LANG', $lang_tmp );
$xtpl->assign( 'total', $total );
$xtpl->assign( 'TEMPLATE', $module_info['template'] );
$xtpl->assign( 'NV_BASE_SITEURL', NV_BASE_SITEURL );
$xtpl->assign( 'LINK_VIEW', NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=cart" );

if( defined( 'NV_IS_USER' ) )
{
	$xtpl->assign( 'LINK_HIS', NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=history" );
	$xtpl->parse( 'main.enable.history' );
}

$xtpl->assign( 'money_unit', $pro_config['money_unit'] );
$xtpl->assign( 'num', $num );

if( $pro_config['active_price'] == '1' ) $xtpl->parse( 'main.enable.price' );

if( $pro_config['active_order'] == '1' )
{
	$xtpl->parse( 'main.enable' );
}
else
{
	$xtpl->parse( 'main.disable' );
}

$xtpl->parse( 'main' );
$content = $xtpl->text( 'main' );
$content = nv_url_rewrite( $content );

$type = $nv_Request->get_int( 't', 'get', 0 );
switch ( $type )
{
	case 0:
		echo $content;
		break;
	case 1:
		echo $num;
		break;
	case 2:
		echo $total;
		break;
	default:
		echo $content;
		break;
}