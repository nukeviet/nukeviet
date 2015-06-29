<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 2-9-2010 14:43
 */

if( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );

$page_title = $lang_module['document_payment'];

if( defined( 'NV_EDITOR' ) )
{
	require_once NV_ROOTDIR . '/' . NV_EDITORSDIR . '/' . NV_EDITOR . '/nv.php';
}

$content_docpay_file = NV_ROOTDIR . '/' . NV_DATADIR . '/' . NV_LANG_DATA . '_' . $module_data . '_docpay_content.txt';
$content_order_file = NV_ROOTDIR . '/' . NV_DATADIR . '/' . NV_LANG_DATA . '_' . $module_data . '_order_content.txt';
$content_order_payment_file = NV_ROOTDIR . '/' . NV_DATADIR . '/' . NV_LANG_DATA . '_' . $module_data . '_order_payment_content.txt';
$content_docpay = '';
$content_order = '';
$content_order_payment = '';

if( file_exists( $content_docpay_file ) )
{
	$content_docpay = file_get_contents( $content_docpay_file );
}

if( file_exists( $content_order_file ) )
{
	$content_order = file_get_contents( $content_order_file );
	if( empty( $content_order ) )
	{
		require_once NV_ROOTDIR . '/modules/' . $module_file . '/language/' . NV_LANG_DATA . '.php';
		$content_order = $lang_module['order_payment_email'];
	}
}
else
{
	require_once NV_ROOTDIR . '/modules/' . $module_file . '/language/' . NV_LANG_DATA . '.php';
	$content_order = $lang_module['order_payment_email'];
}

if( file_exists( $content_order_payment_file ) )
{
	$content_order_payment = file_get_contents( $content_order_payment_file );
	if( empty( $content_order_payment ) )
	{
		$content_order_payment = $lang_module['order_email_payment'];
	}
}
else
{
	$content_order_payment = $lang_module['order_email_payment'];
}

if( $nv_Request->get_int( 'saveintro', 'post', 0 ) == 1 )
{
	$content_docpay = $nv_Request->get_string( 'content_docpay', 'post', '' );
	$content_order = $nv_Request->get_string( 'content_order', 'post', '' );
	$content_order_payment = $nv_Request->get_string( 'content_order_payment', 'post', '' );

	if( defined( 'NV_EDITOR' ) )
	{
		$content_docpay = nv_nl2br( $content_docpay, '' );
		$content_order = nv_nl2br( $content_order, '' );
		$content_order_payment = nv_nl2br( $content_order_payment, '' );
	}
	else
	{
		$content_docpay = nv_nl2br( nv_htmlspecialchars( strip_tags( $content_docpay ) ), '<br />' );
		$content_order = nv_nl2br( nv_htmlspecialchars( strip_tags( $content_order ) ), '<br />' );
		$content_order_payment = nv_nl2br( nv_htmlspecialchars( strip_tags( $content_order_payment ) ), '<br />' );
	}
	file_put_contents( $content_docpay_file, $content_docpay );
	file_put_contents( $content_order_file, $content_order );
	file_put_contents( $content_order_payment_file, $content_order_payment );
}

$content_docpay = htmlspecialchars( nv_editor_br2nl( $content_docpay ) );
if( defined( 'NV_EDITOR' ) and function_exists( 'nv_aleditor' ) )
{
	$content_docpay_edits = nv_aleditor( 'content_docpay', '100%', '300px', $content_docpay );
}
else
{
	$content_docpay_edits = "<textarea style=\"width: 100%\" name=\"content_docpay\" id=\"content_docpay\" cols=\"20\" rows=\"15\">" . $content_docpay . "</textarea>";
}

$content_order = htmlspecialchars( nv_editor_br2nl( $content_order ) );
if( defined( 'NV_EDITOR' ) and function_exists( 'nv_aleditor' ) )
{
	$content_order_edits = nv_aleditor( 'content_order', '100%', '300px', $content_order );
}
else
{
	$content_order_edits = "<textarea style=\"width: 100%\" name=\"content_order\" id=\"content_order\" cols=\"20\" rows=\"15\">" . $content_order . "</textarea>";
}

$content_order_payment = htmlspecialchars( nv_editor_br2nl( $content_order_payment ) );
if( defined( 'NV_EDITOR' ) and function_exists( 'nv_aleditor' ) )
{
	$content_order_payment_edits = nv_aleditor( 'content_order_payment', '100%', '300px', $content_order_payment );
}
else
{
	$content_order_payment_edits = "<textarea style=\"width: 100%\" name=\"content_order_payment\" id=\"content_order_payment\" cols=\"20\" rows=\"15\">" . $content_order_payment . "</textarea>";
}

$replace_order = array(
	'order_code' => $lang_module['order_code'],
	'order_name' => $lang_module['order_name'],
	'order_email' => $lang_module['order_email'],
	'order_phone' => $lang_module['order_phone'],
	'order_address' => $lang_module['order_address'],
	'order_note' => $lang_module['order_note'],
	'order_total' => $lang_module['order_total'],
	'unit_total' => $lang_module['unit_total'],
	'dateup' => $lang_module['dateup'],
	'moment' => $lang_module['moment'],
	'review_url' => $lang_module['review_url'],
	'table_product' => $lang_module['table_product'],
	'site_url' => $lang_module['site_url'],
	'site_name' => $lang_module['site_name'],
);

$xtpl = new XTemplate( "docpay.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file );
$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign( 'content_docpay', $content_docpay_edits );
$xtpl->assign( 'content_order', $content_order_edits );
$xtpl->assign( 'content_order_payment', $content_order_payment_edits );

foreach( $replace_order as $key => $value )
{
	$xtpl->assign( 'ORDER', array( 'key' => $key, 'value' => $value ) );
	$xtpl->parse( 'main.order_loop' );
	$xtpl->parse( 'main.order_payment_loop' );
}

$xtpl->parse( 'main' );
$contents = $xtpl->text( 'main' );

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme( $contents );
include NV_ROOTDIR . '/includes/footer.php';