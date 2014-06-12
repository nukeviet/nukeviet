<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 12/31/2009 5:50
 */

if( ! defined( 'NV_ADMIN' ) or ! defined( 'NV_MAINFILE' ) or ! defined( 'NV_IS_MODADMIN' ) ) die( 'Stop!!!' );

define( 'NV_IS_FILE_EXTENSIONS', true );
define( 'NUKEVIET_STORE_APIURL', 'http://api.nukeviet.vn/store/' );

$menu_top = array(
	'title' => $module_name,
	'module_file' => '',
	'custom_title' => $lang_global['mod_extensions']
);

$allow_func = array( 'main', 'newest', 'popular', 'featured', 'downloaded', 'favorites', 'detail', 'install', 'download' );

$submenu['newest'] = $lang_module['newest'];
$submenu['popular'] = $lang_module['popular'];
$submenu['featured'] = $lang_module['featured'];
$submenu['downloaded'] = $lang_module['downloaded'];
$submenu['favorites'] = $lang_module['favorites'];


/**
 * nv_extensions_get_lang()
 * 
 * @param mixed $input
 * @return
 */
function nv_extensions_get_lang( $input )
{
	global $lang_module;
	
	if( ! isset( $input['code'] ) or ! isset( $input['message'] ) )
	{
		return '';
	}
	
	if( ! empty( $lang_module['error_code_' . $input['code']] ) )
	{
		return $lang_module['error_code_' . $input['code']];
	}
	
	if( ! empty( $input['message'] ) )
	{
		return $input['message'];
	}
	
	return 'Error' . ( $input['code'] ? ': ' . $input['code'] . '.' : '.' );
}

/**
 * nv_extensions_is_installed()
 * 
 * @param mixed $type
 * @param mixed $name
 * @param mixed $version
 * @return
 * 0: Not exists
 * 1: Exists
 * 2: Unsure
 */
function nv_extensions_is_installed( $type, $name, $version )
{
	global $db;
	
	// Module
	if( $type == 1 )
	{
		if( ! is_dir( NV_ROOTDIR . '/modules/' . $name ) )
		{
			return 0;
		}
		
		return 1;
		
		//$stmt = $db->prepare( 'SELECT mod_version FROM ' . NV_PREFIXLANG . '_setup_modules WHERE module_file= :modfile AND module_file=title' );
		//$stmt->bindParam( ':modfile', $name, PDO::PARAM_STR );
		//$stmt->execute();
		//$row = $stmt->fetch();	
	}
	// Theme
	elseif( $type == 2 )
	{
		if( ! is_dir( NV_ROOTDIR . '/themes/' . $name ) )
		{
			return 0;
		}
		return 1;
	}
	// Block
	elseif( $type == 3 )
	{
		return 2;
	}
	// Crons
	elseif( $type == 4 )
	{
		if( ! is_file( NV_ROOTDIR . '/includes/cronjobs/' . $name ) )
		{
			return 0;
		}
		
		return 1;
	}
	
	return 2;
}