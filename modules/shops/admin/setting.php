<?php

/**
 * @Project NUKEVIET 3.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES.,JSC. All rights reserved
 * @Createdate 2-9-2010 14:43
 */

if( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );

if( defined( 'NV_EDITOR' ) )
{
	require_once ( NV_ROOTDIR . '/' . NV_EDITORSDIR . '/' . NV_EDITOR . '/nv.php' );
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

$groups_list = nv_groups_list();
$array_who = array( $lang_global['who_view0'], $lang_global['who_view1'], $lang_global['who_view2'] );
if ( ! empty( $groups_list ) )
{
    $array_who[] = $lang_global['who_view3'];
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
	$data['comment'] = $nv_Request->get_int( 'comment', 'post', 0 );
	$data['comment_auto'] = $nv_Request->get_int( 'comment_auto', 'post', 0 );
	$data['who_comment'] = $nv_Request->get_string( 'who_comment', 'post', 0 );
	$data['groups_comment'] = $nv_Request->get_typed_array( 'groups_comment', 'post', 'int' );
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

	if ( ! in_array( $data['who_comment'], array_keys( $array_who ) ) )
	{
		$data['who_comment'] = 0;
	}
	$data['groups_comment'] = ( ! empty( $data['groups_comment'] ) ) ? implode( ',', $data['groups_comment'] ) : '';
	
	if( $error == '' )
	{
		foreach( $data as $config_name => $config_value )
		{
			$db->sql_query( "REPLACE INTO `" . NV_CONFIG_GLOBALTABLE . "` (`lang`, `module`, `config_name`, `config_value`) VALUES('" . NV_LANG_DATA . "', " . $db->dbescape( $module_name ) . ", " . $db->dbescape( $config_name ) . ", " . $db->dbescape( $config_value ) . ")" );
		}
		$mid = intval( $currencies_array[$data['money_unit']]['numeric'] );

		$sql = "UPDATE `" . $db_config['prefix'] . "_" . $module_data . "_money_" . NV_LANG_DATA . "` SET `exchange` = '1' WHERE `id` = " . $mid . " LIMIT 1";
		$db->sql_query( $sql );

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
	$array_setting_payment = array();
	
	$sql = "SELECT * FROM `" . $db_config['prefix'] . "_" . $module_data . "_payment` ORDER BY `weight` ASC";
	$result = $db->sql_query( $sql );
	$all_page = $db->sql_numrows( $result );
	
	while( $row = $db->sql_fetchrow( $result ) )
	{
		$array_setting_payment[$row['payment']] = $row;
	}
}

// Xu ly quyen binh luan
$who_comment = $data['who_comment'];
$data['who_comment'] = array();
foreach ( $array_who as $key => $who )
{
	$data['who_comment'][$key] = array(
		'key' => $key, //
		'title' => $who, //
		'selected' => $key == $who_comment ? " selected=\"selected\"" : "" //
	);
}

$groups_comment = ! empty( $data['groups_comment'] ) ? explode( ",", $data['groups_comment'] ) : array();
$data['groups_comment'] = array();
if ( ! empty( $groups_list ) )
{
	foreach ( $groups_list as $key => $title )
	{
		$data['groups_comment'][$key] = array(
			'key' => $key, //
			'title' => $title, //
			'checked' => in_array( $key, $groups_comment ) ? " checked=\"checked\"" : "" //
		);
	}
}

$xtpl = new XTemplate( "setting.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file );
$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign( 'DATA', $data );
$xtpl->assign( 'MODULE_NAME', $module_name );

$check_view = array( "view_home_all" => "", "view_home_cat" => "", "view_home_group" => "", "view_home_none" => "" );
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

$check = ( $data['comment'] == '1' ) ? "checked=\"checked\"" : "";
$xtpl->assign( 'ck_comment', $check );

$check = ( $data['comment_auto'] == '1' ) ? "checked=\"checked\"" : "";
$xtpl->assign( 'ck_comment_auto', $check );

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

$check = ( $data['active_showhomtext'] == '1' ) ? "checked=\"checked\"" : "";
$xtpl->assign( 'ck_active_showhomtext', $check );

$check = ( $data['active_tooltip'] == '1' ) ? "checked=\"checked\"" : "";
$xtpl->assign( 'ck_active_tooltip', $check );

$check = ! empty( $data['show_product_code'] ) ? "checked=\"checked\"" : "";
$xtpl->assign( 'ck_show_product_code', $check );

// Binh luan
foreach ( $data['who_comment'] as $who )
{
	$xtpl->assign( 'WHO_COMMENT', $who );
	$xtpl->parse( 'main.who_comment' );
}
if ( ! empty( $data['groups_comment'] ) )
{
	foreach ( $data['groups_comment'] as $group )
	{
		$xtpl->assign( 'GROUPS_COMMENT', $group );
		$xtpl->parse( 'main.group.groups_comment' );
	}
	$xtpl->parse( 'main.group' );
}

// Tien te
$result = $db->sql_query( "SELECT `code`, `currency` FROM `" . $db_config['prefix'] . "_" . $module_data . "_money_" . NV_LANG_DATA . "` ORDER BY `code` DESC" );
while( list( $code, $currency ) = $db->sql_fetchrow( $result ) )
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
	$payment = $nv_Request->get_string( 'payment', 'get', 0 );
	
	foreach( $array_setting_payment as $value )
	{
		$value['titleactive'] = ( ! empty( $value['active'] ) ) ? $lang_global['yes'] : $lang_global['no'];
		$value['link_edit'] = NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=payport&amp;payment=" . $value['payment'];
		$value['class'] = ( $a % 2 == 0 ) ? ' class="second"' : '';
		$value['active'] = ( $value['active'] == '1' ) ? "checked=\"checked\"" : "";
		if( ! empty( $value['images_button'] ) and file_exists( NV_UPLOADS_REAL_DIR . "/" . $module_name . "/" . $value['images_button'] ) )
		{
			$value['images_button'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . "/" . $module_name . "/" . $value['images_button'];
		}
		$value['slect_weight'] = drawselect_number( $value['payment'], 1, $all_page + 1, $value['weight'], "nv_chang_pays('" . $value['payment'] . "',this,url_change_weight,url_back);" );
		$xtpl->assign( 'DATA_PM', $value );
		$xtpl->parse( 'main.payment.paymentloop' );
		$a ++;
	}
	$xtpl->assign( 'url_back', NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=" . $op );
	$xtpl->assign( 'url_change', NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=changepay" );
	$xtpl->assign( 'url_active', NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=actpay" );
	$xtpl->parse( 'main.payment' );
}

$xtpl->parse( 'main' );

$contents .= $xtpl->text( 'main' );
include ( NV_ROOTDIR . "/includes/header.php" );
echo nv_admin_theme( $contents );
include ( NV_ROOTDIR . "/includes/footer.php" );

?>