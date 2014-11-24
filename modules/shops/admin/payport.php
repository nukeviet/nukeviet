<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 2-9-2010 14:43
 */

if( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );

$page_title = $lang_module['setup_payment'];

$array_setting_payment = array();

// Load config template payment port in data
$sql = "SELECT * FROM " . $db_config['prefix'] . "_" . $module_data . "_payment ORDER BY weight ASC";
$result = $db->query( $sql );
$num_items = $result->rowCount();

while( $row = $result->fetch() )
{
	$array_setting_payment[$row['payment']] = $row;
}

// Load config template payment port in file
$check_config_payment = "/^([a-zA-Z0-9\-\_]+)\.config\.ini$/";
$payment_funcs = nv_scandir( NV_ROOTDIR . '/modules/' . $module_file . '/payment', $check_config_payment );

if( ! empty( $payment_funcs ) )
{
	$payment_funcs = preg_replace( $check_config_payment, "\\1", $payment_funcs );
}

$array_setting_payment_key = array_keys( $array_setting_payment );
$array_payment_other = array();

foreach( $payment_funcs as $payment )
{
	$xml = simplexml_load_file( NV_ROOTDIR . '/modules/' . $module_file . '/payment/' . $payment . '.config.ini' );
	if( $xml !== false )
	{
		$xmlconfig = $xml->xpath( 'config' );
		$config = $xmlconfig[0];
		$array_config = array();
		$array_config_title = array();

		foreach( $config as $key => $value )
		{
			$config_lang = $value->attributes();

			if( isset( $config_lang[NV_LANG_INTERFACE] ) )
			{
				$lang = ( string )$config_lang[NV_LANG_INTERFACE];
			}
			else
			{
				$lang = $key;
			}

			$array_config[$key] = trim( $value );
			$array_config_title[$key] = $lang;
		}

		$array_payment_other[$payment] = array(
			'payment' => $payment,
			'paymentname' => trim( $xml->name ),
			'domain' => trim( $xml->domain ),
			'images_button' => trim( $xml->images_button ),
			'config' => $array_config,
			'titlekey' => $array_config_title
		);
		unset( $config, $xmlconfig, $xml );
	}
}

$data_pay = array();
$payment = $nv_Request->get_string( 'payment', 'get', '' );

if( ! empty( $payment ) )
{
	// Get data have not in database
	if( ! in_array( $payment, $array_setting_payment_key ) )
	{
		if( ! empty( $array_payment_other[$payment] ) )
		{
			$weight = $db->query( "SELECT max(weight) FROM " . $db_config['prefix'] . "_" . $module_data . "_payment" )->fetchColumn();
			$weight = intval( $weight ) + 1;

			$sql = "REPLACE INTO " . $db_config['prefix'] . "_" . $module_data . "_payment (payment, paymentname, domain, active, weight, config,images_button) VALUES (" . $db->quote( $payment ) . ", " . $db->quote( $array_payment_other[$payment]['paymentname'] ) . ", " . $db->quote( $array_payment_other[$payment]['domain'] ) . ", '0', '" . $weight . "', '" . nv_base64_encode( serialize( $array_payment_other[$payment]['config'] ) ) . "', " . $db->quote( $array_payment_other[$payment]['images_button'] ) . ")";

			$db->query( $sql );

			$data_pay = $array_payment_other[$payment];
		}
	}

	// Get data have in database
	$stmt = $db->prepare( "SELECT * FROM " . $db_config['prefix'] . "_" . $module_data . "_payment WHERE payment= :payment" );
	$stmt->bindParam( ':payment', $payment, PDO::PARAM_STR );
	$stmt->execute();
	$data_pay = $stmt->fetch();
}

if( $nv_Request->isset_request( 'saveconfigpaymentedit', 'post' ) )
{
	$payment = $nv_Request->get_title( 'payment', 'post', '', 0 );
	$paymentname = $nv_Request->get_title( 'paymentname', 'post', '', 0 );
	$domain = $nv_Request->get_title( 'domain', 'post', '', 0 );
	$images_button = $nv_Request->get_title( 'images_button', 'post', '', 0 );
	$active = $nv_Request->get_int( 'active', 'post', 0 );
	$array_config = $nv_Request->get_array( 'config', 'post', array() );

	if( ! nv_is_url( $images_button ) and file_exists( NV_DOCUMENT_ROOT . $images_button ) )
	{
		$lu = strlen( NV_BASE_SITEURL . NV_UPLOADS_DIR . "/" . $module_name . "/" );
		$images_button = substr( $images_button, $lu );
	}
	elseif( ! nv_is_url( $images_button ) )
	{
		$images_button = "";
	}

	$stmt = $db->prepare( "UPDATE " . $db_config['prefix'] . "_" . $module_data . "_payment SET paymentname = :paymentname, domain = :domain, active=" . $active . ", config = '" . nv_base64_encode( serialize( $array_config ) ) . "',images_button= :images_button WHERE payment = :payment" );
	$stmt->bindParam( ':paymentname', $paymentname, PDO::PARAM_STR );
	$stmt->bindParam( ':domain', $domain, PDO::PARAM_STR );
	$stmt->bindParam( ':images_button', $images_button, PDO::PARAM_STR );
	$stmt->bindParam( ':payment', $payment, PDO::PARAM_STR );
	$stmt->execute();

	nv_insert_logs( NV_LANG_DATA, $module_name, 'log_edit_product', "edit " . $paymentname, $admin_info['userid'] );
	nv_del_moduleCache( $module_name );

	Header( "Location: " . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=" . $op );
}

$xtpl = new XTemplate( "payport.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file );
$xtpl->assign( 'LANG', $lang_module );

$a = 0;
if( ! empty( $array_setting_payment ) and empty( $data_pay ) )
{
	$all_page = sizeof( $array_setting_payment );
	foreach( $array_setting_payment as $value )
	{
		$value['link_edit'] = NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=" . $op . "&amp;payment=" . $value['payment'];
		$value['active'] = ( $value['active'] == "1" ) ? "checked=\"checked\"" : "";
		$value['slect_weight'] = drawselect_number( $value['payment'], 1, $all_page + 1, $value['weight'], "nv_chang_pays('" . $value['payment'] . "',this,url_change_weight,url_back);" );

		$xtpl->assign( 'DATA_PM', $value );

		$xtpl->parse( 'main.listpay.paymentloop' );
		++$a;
	}

	$xtpl->assign( 'url_back', NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=" . $op );
	$xtpl->assign( 'url_change', NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=changepay" );
	$xtpl->assign( 'url_active', NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=actpay" );
	$xtpl->parse( 'main.listpay' );
}

if( ! empty( $array_payment_other ) && empty( $data_pay ) )
{
	$a = 1;
	foreach( $array_payment_other as $pay => $value )
	{
		if( ! in_array( $pay, $array_setting_payment_key ) )
		{
			$value['link_edit'] = NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=" . $op . "&amp;payment=" . $value['payment'];
			$value['STT'] = $a;

			$xtpl->assign( 'ODATA_PM', $value );

			$xtpl->parse( 'main.olistpay.opaymentloop' );
			++$a;
		}
	}

	if( $a > 1 ) $xtpl->parse( 'main.olistpay' );
}

if( ! empty( $data_pay ) )
{
	$xtpl->assign( 'EDITPAYMENT', sprintf( $lang_module['editpayment'], $data_pay['payment'] ) );
	$array_config = unserialize( nv_base64_decode( $data_pay['config'] ) );

	$arkey_title = array();
	if( ! empty( $array_payment_other[$data_pay['payment']]['titlekey'] ) )
	{
		$arkey_title = $array_payment_other[$data_pay['payment']]['titlekey'];
	}
	foreach( $array_config as $key => $value )
	{
		if( isset( $arkey_title[$key] ) )
		{
			$lang = ( string )$arkey_title[$key];
		}
		else
		{
			$lang = $key;
		}

		$value = $array_config[$key];

		$xtpl->assign( 'CONFIG_LANG', $lang );
		$xtpl->assign( 'CONFIG_NAME', $key );
		$xtpl->assign( 'CONFIG_VALUE', $value );

		$xtpl->parse( 'main.paymentedit.config' );
	}

	$data_pay['active'] = ( $data_pay['active'] == "1" ) ? "checked=\"checked\"" : "";

	$xtpl->assign( 'DATA', $data_pay );

	$xtpl->parse( 'main.paymentedit' );
}

if( NV_LANG_DATA == 'vi' )
{
	$xtpl->parse( 'main.guide' );
}

$xtpl->parse( 'main' );
$contents = $xtpl->text( 'main' );

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme( $contents );
include NV_ROOTDIR . '/includes/footer.php';