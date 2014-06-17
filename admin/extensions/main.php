<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 2-1-2010 22:5
 */

if( ! defined( 'NV_IS_FILE_EXTENSIONS' ) ) die( 'Stop!!!' );

if( ! defined( 'SHADOWBOX' ) )
{
	$my_head = "<script type=\"text/javascript\" src=\"" . NV_BASE_SITEURL . "js/shadowbox/shadowbox.js\"></script>\n";
	$my_head .= "<link rel=\"stylesheet\" type=\"text/css\" href=\"" . NV_BASE_SITEURL . "js/shadowbox/shadowbox.css\" />\n";

	define( 'SHADOWBOX', true );
}

$page_title = $lang_global['mod_extensions'];

$request = array();
$request['page'] = $nv_Request->get_int( 'page', 'get', 0 );
$request['mode'] = $nv_Request->get_title( 'mode', 'get', 'featured' );
$request['q'] = nv_substr( $nv_Request->get_title( 'q', 'get', '' ), 0, 64 );

// Fixed request
$request['per_page'] = 10;
$request['lang'] = NV_LANG_INTERFACE;
$request['basever'] = $global_config['version'];

// Mode filter
if( ! in_array( $request['mode'], array( 'search', 'newest', 'popular', 'featured', 'downloaded', 'favorites' ) ) )
{
	$request['mode'] = 'featured';
}

if( $request['mode'] != 'search' )
{
	$set_active_op = $request['mode'];
}

$xtpl = new XTemplate( $op . '.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file );
$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign( 'REQUEST', $request );
$xtpl->assign( 'NV_BASE_ADMINURL', NV_BASE_ADMINURL );
$xtpl->assign( 'NV_LANG_VARIABLE', NV_LANG_VARIABLE );
$xtpl->assign( 'NV_LANG_DATA', NV_LANG_DATA );
$xtpl->assign( 'NV_NAME_VARIABLE', NV_NAME_VARIABLE );
$xtpl->assign( 'MODULE_NAME', $module_name );

// Develop feature
if( $request['mode'] == 'downloaded' or $request['mode'] == 'favorites' )
{
	$xtpl->parse( 'dev' );
	$contents = $xtpl->text( 'dev' );
	
	include NV_ROOTDIR . '/includes/header.php';
	echo nv_admin_theme( $contents );
	include NV_ROOTDIR . '/includes/footer.php';
	die();
}
// Develop feature end

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
elseif( empty( $array['data'] ) )
{
	$xtpl->parse( 'main.empty' );
}
else
{
	foreach( $array['data'] as $row )
	{
		$row['rating_avg'] = ceil( $row['rating_avg'] );
		$row['type'] = $lang_module['types_' . intval( $row['tid'] )];
		$row['compatible_class'] = empty( $row['compatible'] ) ? 'text-danger' : 'text-success';
		$row['compatible_title'] = empty( $row['compatible'] ) ? $lang_module['incompatible'] : $lang_module['compatible'];
		
		if( empty( $row['image_small'] ) )
		{
			$row['image_small'] = NV_BASE_SITEURL . 'themes/default/images/no_image.gif';
		}
		
		$row['install_link'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=install&amp;id=' . $row['id'];
		$row['detail_link'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=detail&amp;id=' . $row['id'];
		$row['detail_title'] = sprintf( $lang_module['detail_title'], $row['title'] );
		
		$xtpl->assign( 'ROW', $row );
		
		// Parse rating
		for( $i = 1; $i <= 5; $i ++ )
		{
			$xtpl->assign( 'STAR', $row['rating_avg'] == $i ? " active" : "" );
			$xtpl->parse( 'main.data.loop.star' );
		}
		
		// Tuong thich moi cho cai dat
		if( ! empty( $row['compatible'] ) )
		{
			$xtpl->parse( 'main.data.loop.install' );
		}
		
		$xtpl->parse( 'main.data.loop' );
	}
	
	if( ! empty( $array['pagination']['all_page'] ) )
	{
		$base_url = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;mode=' . $request['mode'] . '&amp;q=' . urlencode( $request['q'] );
		$generate_page = nv_generate_page( $base_url, intval( $array['pagination']['all_page'] ), $request['per_page'], $request['page'] );
		
		if( ! empty( $generate_page ) )
		{
			$xtpl->assign( 'GENERATE_PAGE', $generate_page );
			$xtpl->parse( 'main.data.generate_page' );
		}
	}
	
	$xtpl->parse( 'main.data' );
}

$xtpl->parse( 'main' );
$contents = $xtpl->text( 'main' );

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme( $contents );
include NV_ROOTDIR . '/includes/footer.php';