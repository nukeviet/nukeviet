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
$request['id'] = $nv_Request->get_int( 'id', 'get', 0 );
$request['fid'] = $nv_Request->get_int( 'fid', 'get', 0 );

// Fixed request
$request['lang'] = NV_LANG_INTERFACE;
$request['basever'] = $global_config['version'];

$xtpl = new XTemplate( $op . '.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file );
$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign( 'REQUEST', $request );
$xtpl->assign( 'NV_BASE_ADMINURL', NV_BASE_ADMINURL );
$xtpl->assign( 'NV_LANG_VARIABLE', NV_LANG_VARIABLE );
$xtpl->assign( 'NV_LANG_DATA', NV_LANG_DATA );
$xtpl->assign( 'NV_NAME_VARIABLE', NV_NAME_VARIABLE );
$xtpl->assign( 'MODULE_NAME', $module_name );

$error = '';
$message = '';

require( NV_ROOTDIR . '/' . NV_ADMINDIR . '/extensions/extensions.class.php' );
$NV_Extensions = new NV_Extensions( $global_config, NV_TEMP_DIR );

// Find file
if( empty( $request['fid'] ) )
{
	$request['mode'] = 'getfile';
}
// Download file
else
{
	$request['mode'] = 'install';
	$request['getfile'] = $nv_Request->get_int( 'getfile', 'get', 0 );
}

if( empty( $error ) and empty( $message ) )
{
	$args = array(
		'headers' => array(
			'Referer' => NUKEVIET_STORE_APIURL,
		),
		'body' => $request
	);	
	
	$array = $NV_Extensions->post( NUKEVIET_STORE_APIURL, $args );
	$array = ! empty( $array['body'] ) ? @unserialize( $array['body'] ) : array();
	
	// Next step
	if( ! empty( $array['data']['compatible']['id'] ) and $request['mode'] == 'getfile' )
	{
		header( 'location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=install&id=' . $array['data']['id'] . '&fid=' . $array['data']['compatible']['id'] . '&getfile=1' );
		die();
	}
	
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
}

// Show error
if( ! empty( $error ) )
{
	$xtpl->assign( 'ERROR', $error );
	$xtpl->parse( 'main.error' );
}
else
{
	if( $request['mode'] == 'getfile' )
	{		
		$xtpl->parse( 'main.getfile_error' );
	}
	else
	{
		$array = $array['data'];
		unset( $array['data'] );
		
		$xtpl->assign( 'DATA', $array );
		
		$page_title = sprintf( $lang_module['install_title'], $array['title'] );
		
		// Show getfile info
		if( $request['getfile'] )
		{
			$xtpl->parse( 'main.install.getfile' );
		}
				
		if( empty( $array['compatible']['id'] ) )
		{
			$xtpl->parse( 'main.install.incompatible' );
		}
		else
		{
			$xtpl->parse( 'main.install.compatible' );
			
			// Check auto install
			if( $array['compatible']['type'] != 1 or ! in_array( $array['tid'], array( 1, 2, 3, 4 ) ) )
			{
				$xtpl->assign( 'MANUAL_MESSAGE', $array['documentation'] ? $lang_module['install_manual_install'] : $lang_module['install_manual_install_danger'] );
				$xtpl->parse( 'main.install.manual' );
			}
			else
			{
				$xtpl->parse( 'main.install.auto' );
				
				$xtpl->assign( 'CANCEL_LINK', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name );
				
				// Check installed
				$installed = nv_extensions_is_installed( $array['tid'], $array['name'], $array['compatible']['ver'] );
				
				if( $installed == 1 )
				{
					$xtpl->parse( 'main.install.installed' );
				}
				else
				{
					if( $installed == 2 )
					{
						$xtpl->parse( 'main.install.not_install.unsure' );
					}
					else
					{
						$xtpl->parse( 'main.install.not_install.startdownload' );
					}
					
					$xtpl->parse( 'main.install.not_install' );
				}
			}
		}
		
		$xtpl->parse( 'main.install' );
	}
}

$xtpl->parse( 'main' );
$contents = $xtpl->text( 'main' );

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme( $contents );
include NV_ROOTDIR . '/includes/footer.php';