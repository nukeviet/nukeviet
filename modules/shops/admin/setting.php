<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 2-9-2010 14:43
 */

if( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );

if( defined( 'NV_EDITOR' ) )
{
	require_once NV_ROOTDIR . '/' . NV_EDITORSDIR . '/' . NV_EDITOR . '/nv.php';
}

$currencies_array = nv_parse_ini_file( NV_ROOTDIR . '/includes/ini/currencies.ini', true );

$data = $module_config[$module_name];
$active_payment_old = 0;
if( ! empty( $data ) )
{
	$temp = explode( "x", $data['image_size'] );
	$data['homewidth'] = $temp[0];
	$data['homeheight'] = $temp[1];
}

$page_title = $lang_module['setting'];

$savesetting = $nv_Request->get_int( 'savesetting', 'post', 0 );
$error = "";

if( $savesetting == 1 )
{
	$data['homewidth'] = $nv_Request->get_int( 'homewidth', 'post', 0 );
	$data['homeheight'] = $nv_Request->get_int( 'homeheight', 'post', 0 );
	$data['image_size'] = $data['homewidth'] . "x" . $data['homeheight'];
	$data['per_page'] = $nv_Request->get_int( 'per_page', 'post', 0 );
	$data['per_row'] = $nv_Request->get_int( 'per_row', 'post', 0 );
	$data['auto_check_order'] = $nv_Request->get_string( 'auto_check_order', 'post', 0 );
	$data['post_auto_member'] = $nv_Request->get_string( 'post_auto_member', 'post', 0 );
	$data['money_unit'] = $nv_Request->get_string( 'money_unit', 'post', "" );
	$data['home_view'] = $nv_Request->get_string( 'home_view', 'post', '' );
	$data['format_order_id'] = $nv_Request->get_string( 'format_order_id', 'post', '' );
	$data['format_code_id'] = $nv_Request->get_string( 'format_code_id', 'post', '' );
	$data['active_order'] = $nv_Request->get_int( 'active_order', 'post', 0 );
	$data['active_price'] = $nv_Request->get_int( 'active_price', 'post', 0 );
	$data['active_order_number'] = $nv_Request->get_int( 'active_order_number', 'post', 0 );
	$data['active_payment'] = $nv_Request->get_int( 'active_payment', 'post', 0 );
	$data['active_showhomtext'] = $nv_Request->get_int( 'active_showhomtext', 'post', 0 );
	$data['active_tooltip'] = $nv_Request->get_int( 'active_tooltip', 'post', 0 );
	$data['show_product_code'] = $nv_Request->get_int( 'show_product_code', 'post', 0 );
	$data['show_compare'] = $nv_Request->get_int( 'show_compare', 'post', 0 );
	$data['show_displays'] = $nv_Request->get_int( 'show_displays', 'post', 0 );
	$data['active_guest_order'] = $nv_Request->get_int( 'active_guest_order', 'post', 0 );
	$data['active_wishlist'] = $nv_Request->get_int( 'active_wishlist', 'post', 0 );
	$data['tags_alias'] = $nv_Request->get_int( 'tags_alias', 'post', 0 );
	$data['auto_tags'] = $nv_Request->get_int( 'auto_tags', 'post', 0 );
	$data['tags_remind'] = $nv_Request->get_int( 'tags_remind', 'post', 0 );

	if( $error == '' )
	{
		foreach( $data as $config_name => $config_value )
		{
			$db->query( "REPLACE INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES('" . NV_LANG_DATA . "', " . $db->quote( $module_name ) . ", " . $db->quote( $config_name ) . ", " . $db->quote( $config_value ) . ")" );
		}
		$mid = intval( $currencies_array[$data['money_unit']]['numeric'] );

		$sql = "UPDATE " . $db_config['prefix'] . "_" . $module_data . "_money_" . NV_LANG_DATA . " SET exchange = '1' WHERE id = " . $mid;
		$db->query( $sql );

		nv_insert_logs( NV_LANG_DATA, $module_name, $lang_module['setting'], "Setting", $admin_info['userid'] );
		nv_del_moduleCache( 'settings' );
		nv_del_moduleCache( $module_name );

		Header( "Location: " . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . '=setting' );
		die();
	}
}

$array_setting_payment = array();

if( $data['active_payment'] == '1' )
{
	$sql = "SELECT * FROM " . $db_config['prefix'] . "_" . $module_data . "_payment ORDER BY weight ASC";
	$result = $db->query( $sql );
	$num_items = $result->rowCount();

	while( $row = $result->fetch() )
	{
		$array_setting_payment[$row['payment']] = $row;
	}
}

$xtpl = new XTemplate( "setting.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file );
$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign( 'DATA', $data );
$xtpl->assign( 'MODULE_NAME', $module_name );

// Số sản phẩm hiển thị trên một dòng
for( $i = 3; $i <= 4; $i++ )
{
    $xtpl->assign( 'PER_ROW', array( 'value' => $i, 'selected' => $data['per_row'] == $i ? 'selected="selected"' : '' ) );
    $xtpl->parse( 'main.per_row' );
}

$check_view = array(
	"view_home_all" => "",
	"view_home_cat" => "",
	"view_home_group" => "",
	"view_home_none" => ""
);
$check_view[$data['home_view']] = "selected=\"selected\"";

foreach( $check_view as $type_view => $select )
{
	$xtpl->assign( 'type_view', $type_view );
	$xtpl->assign( 'view_selected', $select );
	$xtpl->assign( 'name_view', $lang_module[$type_view] );
	$xtpl->parse( 'main.home_view_loop' );
}

$select = "";
for( $i = 5; $i <= 50; $i = $i + 5 )
{
	$select .= "<option value=\"" . $i . "\"" . ( ( $i == $data['per_page'] ) ? " selected=\"selected\"" : "" ) . ">" . $i . "</option>\n";
}

$xtpl->assign( 'TAGS_ALIAS', $module_config[$module_name]['tags_alias'] ? ' checked="checked"' : '' );
$xtpl->assign( 'AUTO_TAGS', $module_config[$module_name]['auto_tags'] ? ' checked="checked"' : '' );
$xtpl->assign( 'TAGS_REMIND', $module_config[$module_name]['tags_remind'] ? ' checked="checked"' : '' );

$check = ( $data['auto_check_order'] == '1' ) ? "checked=\"checked\"" : "";
$xtpl->assign( 'ck_auto_check_order', $check );

$check = ( $data['post_auto_member'] == '1' ) ? "checked=\"checked\"" : "";
$xtpl->assign( 'ck_post_auto_member', $check );

$check = ( $data['active_order'] == '1' ) ? "checked=\"checked\"" : "";
$xtpl->assign( 'ck_active_order', $check );

$check = ( $data['active_price'] == '1' ) ? "checked=\"checked\"" : "";
$xtpl->assign( 'ck_active_price', $check );

$check = ( $data['active_order_number'] == '1' ) ? "checked=\"checked\"" : "";
$xtpl->assign( 'ck_active_order_number', $check );

$check = ( $data['active_payment'] == '1' ) ? "checked=\"checked\"" : "";
$xtpl->assign( 'ck_active_payment', $check );

$check = ( $data['active_guest_order'] == '1' ) ? "checked=\"checked\"" : "";
$xtpl->assign( 'ck_active_guest_order', $check );

$check = ( $data['active_showhomtext'] == '1' ) ? "checked=\"checked\"" : "";
$xtpl->assign( 'ck_active_showhomtext', $check );

$check = ( $data['active_tooltip'] == '1' ) ? "checked=\"checked\"" : "";
$xtpl->assign( 'ck_active_tooltip', $check );

$check = ! empty( $data['show_product_code'] ) ? "checked=\"checked\"" : "";
$xtpl->assign( 'ck_show_product_code', $check );

$check = ( $data['show_compare'] == '1' ) ? "checked=\"checked\"" : "";
$xtpl->assign( 'ck_compare', $check );

$check = ( $data['show_displays'] == '1' ) ? "checked=\"checked\"" : "";
$xtpl->assign( 'ck_displays', $check );

$check = ( $data['active_wishlist'] == '1' ) ? "checked=\"checked\"" : "";
$xtpl->assign( 'ck_active_wishlist', $check );

// Tien te
$result = $db->query( "SELECT code, currency FROM " . $db_config['prefix'] . "_" . $module_data . "_money_" . NV_LANG_DATA . " ORDER BY code DESC" );
while( list( $code, $currency ) = $result->fetch( 3 ) )
{
	$array_temp = array();
	$array_temp['value'] = $code;
	$array_temp['title'] = $code . " - " . $currency;
	$array_temp['selected'] = ( $code == $data['money_unit'] ) ? " selected=\"selected\"" : "";
	$xtpl->assign( 'DATAMONEY', $array_temp );
	$xtpl->parse( 'main.money_loop' );
}

$xtpl->assign( 'per_page', $select );

if( ! empty( $error ) )
{
	$xtpl->assign( 'error', $error );
	$xtpl->parse( 'main.error' );
}

if( ! empty( $array_setting_payment ) )
{
	$a = 0;
	$all_page = sizeof( $array_setting_payment );
	$payment = $nv_Request->get_string( 'payment', 'get', 0 );

	foreach( $array_setting_payment as $value )
	{
		$value['titleactive'] = ( ! empty( $value['active'] ) ) ? $lang_global['yes'] : $lang_global['no'];
		$value['link_edit'] = NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=payport&amp;payment=" . $value['payment'];
		$value['active'] = ( $value['active'] == '1' ) ? "checked=\"checked\"" : "";
		if( ! empty( $value['images_button'] ) and file_exists( NV_UPLOADS_REAL_DIR . "/" . $module_name . "/" . $value['images_button'] ) )
		{
			$value['images_button'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . "/" . $module_name . "/" . $value['images_button'];
		}
		$value['slect_weight'] = drawselect_number( $value['payment'], 1, $all_page + 1, $value['weight'], "nv_chang_pays('" . $value['payment'] . "',this,url_change_weight,url_back);" );
		$xtpl->assign( 'DATA_PM', $value );
		$xtpl->parse( 'main.payment.paymentloop' );
		++$a;
	}
	$xtpl->assign( 'url_back', NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=" . $op );
	$xtpl->assign( 'url_change', NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=changepay" );
	$xtpl->assign( 'url_active', NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=actpay" );
	$xtpl->parse( 'main.payment' );
}

$xtpl->parse( 'main' );

$contents .= $xtpl->text( 'main' );
include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme( $contents );
include NV_ROOTDIR . '/includes/footer.php';