<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 2-1-2010 22:5
 */

if( ! defined( 'NV_IS_FILE_EXTENSIONS' ) ) die( 'Stop!!!' );

$page_title = $lang_global['mod_extensions'];

$request = array();

// Fixed request
$request['lang'] = NV_LANG_DATA;
$request['basever'] = $global_config['version'];
$request['mode'] = 'detail';

$xtpl = new XTemplate( $op . '.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file );
$xtpl->assign( 'LANG', $lang_module );

$request['id'] = $nv_Request->get_int( 'id', 'get', 0 );

require( NV_ROOTDIR . '/' . NV_ADMINDIR . '/extensions/extensions.class.php' );
$NV_Extensions = new NV_Extensions( $global_config, NV_TEMP_DIR );

// Debug
$args = array(
	'headers' => array(
		'Referer' => NUKEVIET_STORE_APIURL,
	),
	'body' => $request
);

$array = $NV_Extensions->post( NUKEVIET_STORE_APIURL, $args );
$array = ! empty( $array['body'] ) ? @unserialize( $array['body'] ) : array();

$error = '';
if( ! empty( $NV_Extensions::$error ) )
{
	$error = nv_extensions_get_lang( $NV_Extensions::$error );
}
elseif( ! isset( $array['error'] ) or ! isset( $array['data'] ) or ! isset( $array['pagination'] ) or ! is_array( $array['error'] ) or ! is_array( $array['data'] ) or ! is_array( $array['pagination'] ) or ( ! empty( $array['error'] ) and ( ! isset( $array['error']['level'] ) or empty( $array['error']['message'] ) ) ) )
{
	$error = $lang_module['error_valid_response'];
}
elseif( ! empty( $array['error']['message'] ) )
{
	$error = $array['error']['message'];
}

// Show error
if( ! empty( $error ) )
{
	$xtpl->assign( 'ERROR', $error );
	$xtpl->parse( 'main.error' );
}
else
{
	$array = $array['data'];
	$array_files = $array['files'];
	$array_images = $array['image_demo'];
	unset( $array['files'], $array['image_demo'] );
	
	// Change some variable to display value
	$array['updatetime'] = nv_date( "H:i d/m/Y", $array['updatetime'] );
	$array['view_hits'] = number_format( $array['view_hits'], 0, '.', '.' );
	$array['download_hits'] = number_format( $array['download_hits'], 0, '.', '.' );
	$array['rating_text'] = sprintf( $lang_module['rating_text_detail'], number_format( $array['rating_totals'], 0, '.', '.' ), number_format( $array['rating_hits'], 0, '.', '.' ) );
	$array['compatible_class'] = empty( $array['compatible'] ) ? 'text-danger' : 'text-success';
	$array['compatible_title'] = empty( $array['compatible'] ) ? $lang_module['incompatible'] : $lang_module['compatible'];
	$array['install_link'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=install&amp;id=' . $array['id'];
	
	$xtpl->assign( 'DATA', $array );
	
	if( empty( $array['documentation'] ) )
	{
		$xtpl->parse( 'main.data.empty_documentation' );
	}
	
	if( ! empty( $array_images ) )
	{
		foreach( $array_images as $image )
		{
			$xtpl->assign( 'IMAGE', $image );
			$xtpl->parse( 'main.data.demo_images.loop' );
		}
		
		$xtpl->parse( 'main.data.demo_images' );
	}
	else
	{
		$xtpl->parse( 'main.data.empty_images' );	
	}
	
	if( ! empty( $array['compatible'] ) )
	{
		$xtpl->parse( 'main.data.install' );
	}
	
	foreach( $array_files as $file )
	{
		$file['compatible_class'] = empty( $file['compatible'] ) ? 'text-danger' : 'text-success';
		$file['compatible_title'] = empty( $file['compatible'] ) ? $lang_module['incompatible'] : $lang_module['compatible'];
		$file['install_link'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=install&amp;id=' . $array['id'] . '&amp;fid=' . $file['id'];

		$xtpl->assign( 'FILE', $file );
		
		if( $file['type'] == 1 and ! empty( $file['compatible'] ) )
		{
			$xtpl->parse( 'main.data.file.install' );
		}
		else
		{
			$xtpl->parse( 'main.data.file.download' );
		}
		
		$xtpl->parse( 'main.data.file' );
	}
	
	$xtpl->parse( 'main.data' );
}

$xtpl->parse( 'main' );
$contents = $xtpl->text( 'main' );

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme( $contents, 0 );
include NV_ROOTDIR . '/includes/footer.php';