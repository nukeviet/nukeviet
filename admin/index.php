<?php

/**
 * @Project NUKEVIET 3.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @copyright 2009
 * @createdate 12/30/2009 6:18
 */

define( 'NV_ADMIN', true );

// Ket noi den mainfile.php nam o thu muc goc.
$realpath_mainfile = $set_active_op = "";

$temp_dir = str_replace( DIRECTORY_SEPARATOR, '/', dirname( __file__ ) );
$temp_path = "/../";
for( $i = 0; $i < 10; ++$i )
{
	$realpath_mainfile = @realpath( $temp_dir . $temp_path . 'mainfile.php' );
	if( ! empty( $realpath_mainfile ) ) break;
	$temp_path .= "../";
}

unset( $temp_dir, $temp_path );

if( empty( $realpath_mainfile ) ) die();

require ( $realpath_mainfile );

ob_start();

// Admin dang nhap
if( ! defined( 'NV_IS_ADMIN' ) or ! isset( $admin_info ) or empty( $admin_info ) )
{
	require ( NV_ROOTDIR . "/includes/core/admin_access.php" );
	require ( NV_ROOTDIR . "/includes/core/admin_login.php" );
	exit();
}

if( file_exists( NV_ROOTDIR . "/language/" . NV_LANG_INTERFACE . "/admin_global.php" ) )
{
	require ( NV_ROOTDIR . "/language/" . NV_LANG_INTERFACE . "/admin_global.php" );
}
elseif( file_exists( NV_ROOTDIR . "/language/" . NV_LANG_DATA . "/admin_global.php" ) )
{
	require ( NV_ROOTDIR . "/language/" . NV_LANG_DATA . "/admin_global.php" );
}
elseif( file_exists( NV_ROOTDIR . "/language/en/admin_global.php" ) )
{
	require ( NV_ROOTDIR . "/language/en/admin_global.php" );
}

include_once ( NV_ROOTDIR . "/includes/core/admin_functions.php" );

$admin_mods = array();
$admin_mods['siteinfo'] = array( 'custom_title' => $lang_global['mod_siteinfo'] );
$admin_mods['authors'] = array( 'custom_title' => $lang_global['mod_authors'] );

if( defined( 'NV_IS_SPADMIN' ) )
{
	$admin_mods['settings'] = array( 'custom_title' => $lang_global['mod_settings'] );
	if( defined( 'NV_IS_GODADMIN' ) )
	{
		$admin_mods['database'] = array( 'custom_title' => $lang_global['mod_database'] );
		$admin_mods['webtools'] = array( 'custom_title' => $lang_global['mod_webtools'] );
	}
	$admin_mods['language'] = array( 'custom_title' => $lang_global['mod_language'] );
	$admin_mods['modules'] = array( 'custom_title' => $lang_global['mod_modules'] );
	$admin_mods['themes'] = array( 'custom_title' => $lang_global['mod_themes'] );
}

$admin_mods['upload'] = array( 'custom_title' => $lang_global['mod_upload'] );

$module_name = strtolower( filter_text_input( NV_NAME_VARIABLE, 'post,get', 'siteinfo' ) );

if( ! empty( $module_name ) )
{
	$include_functions = $include_file = $lang_file = $mod_theme_file = "";
	$module_data = $module_file = $module_name;

	$op = filter_text_input( NV_OP_VARIABLE, 'post,get', 'main' );
	if( empty( $op ) or $op == "functions" )
	{
		$op = "main";
	}

	$site_mods = nv_site_mods();
	if( empty( $site_mods ) and $module_name != "language" )
	{

		$sql = "SELECT `setup` FROM `" . $db_config['prefix'] . "_setup_language` WHERE `lang`='" . NV_LANG_DATA . "' LIMIT 1";
		$result = $db->sql_query( $sql );
		list( $setup ) = $db->sql_fetchrow( $result );
		if( empty( $setup ) )
		{
			Header( "Location: " . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=language" );
			exit();
		}
	}

	$menu_top = array();
	if( isset( $admin_mods[$module_name] ) )
	{
		$module_info = $admin_mods[$module_name];
		$module_file = $module_name;
		$include_functions = NV_ROOTDIR . "/" . NV_ADMINDIR . "/modules/" . $module_file . "/functions.php";
		$include_file = NV_ROOTDIR . "/" . NV_ADMINDIR . "/modules/" . $module_file . "/" . $op . ".php";

		// Ket noi voi file ngon ngu cua module
		if( file_exists( NV_ROOTDIR . "/language/" . NV_LANG_INTERFACE . "/admin_" . $module_file . ".php" ) )
		{
			require ( NV_ROOTDIR . "/language/" . NV_LANG_INTERFACE . "/admin_" . $module_file . ".php" );
		}
		elseif( file_exists( NV_ROOTDIR . "/language/" . NV_LANG_DATA . "/admin_" . $module_file . ".php" ) )
		{
			require ( NV_ROOTDIR . "/language/" . NV_LANG_DATA . "/admin_" . $module_file . ".php" );
		}
		elseif( file_exists( NV_ROOTDIR . "/language/en/admin_" . $module_file . ".php" ) )
		{
			require ( NV_ROOTDIR . "/language/en/admin_" . $module_file . ".php" );
		}
	}
	elseif( isset( $site_mods[$module_name] ) )
	{
		$module_info = $site_mods[$module_name];
		$module_file = $module_info['module_file'];
		$module_data = $module_info['module_data'];
		$include_functions = NV_ROOTDIR . "/modules/" . $module_file . "/admin.functions.php";
		$include_file = NV_ROOTDIR . "/modules/" . $module_file . "/admin/" . $op . ".php";

		//Ket noi ngon ngu cua module
		if( file_exists( NV_ROOTDIR . "/modules/" . $module_file . "/language/admin_" . NV_LANG_INTERFACE . ".php" ) )
		{
			require ( NV_ROOTDIR . "/modules/" . $module_file . "/language/admin_" . NV_LANG_INTERFACE . ".php" );
		}
		elseif( file_exists( NV_ROOTDIR . "/modules/" . $module_file . "/language/admin_" . NV_LANG_DATA . ".php" ) )
		{
			require ( NV_ROOTDIR . "/modules/" . $module_file . "/language/admin_" . NV_LANG_DATA . ".php" );
		}
		elseif( file_exists( NV_ROOTDIR . "/modules/" . $module_file . "/language/admin_en.php" ) )
		{
			require ( NV_ROOTDIR . "/modules/" . $module_file . "/language/admin_en.php" );
		}
	}

	if( file_exists( $include_functions ) and file_exists( $include_file ) )
	{
		define( 'NV_IS_MODADMIN', true );

		$array_lang_admin = array();

		if( $global_config['lang_multi'] )
		{
			foreach( $global_config['allow_adminlangs'] as $lang_i )
			{
				if( file_exists( NV_ROOTDIR . "/language/" . $lang_i . "/global.php" ) )
				{
					$array_lang_admin[$lang_i] = $language_array[$lang_i]['name'];
				}
			}
		}

		//ket noi voi giao dien chung cua admin
		require ( NV_ROOTDIR . "/themes/" . $global_config['admin_theme'] . "/theme.php" );

		// Ket noi giao dien cua module
		$global_config['module_theme'] = "";
		if( is_dir( NV_ROOTDIR . "/themes/" . $global_config['admin_theme'] . "/modules/" . $module_file . "/" ) )
		{
			$global_config['module_theme'] = $global_config['admin_theme'];
		}
		elseif( is_dir( NV_ROOTDIR . "/themes/admin_default/modules/" . $module_file . "/" ) )
		{
			$global_config['module_theme'] = "admin_default";
		}

		$allow_func = array();
		require ( $include_functions );
		if( in_array( $op, $allow_func ) )
		{
			$admin_menu_mods = array();
			if( ! empty( $menu_top ) and ! empty( $submenu ) )
			{
				$admin_menu_mods[$module_name] = $menu_top['custom_title'];
			}
			elseif( isset( $site_mods[$module_name] ) )
			{
				$admin_menu_mods[$module_name] = $site_mods[$module_name]['custom_title'];
			}
			foreach( $site_mods as $key => $value )
			{
				if( $value['admin_file'] ) $admin_menu_mods[$key] = $value['custom_title'];
			}
			require ( $include_file );
			exit();
		}
		else
		{
			nv_info_die( $lang_global['error_404_title'], $lang_global['error_404_title'], $lang_global['admin_no_allow_func'] );
		}
	}
	elseif( isset( $site_mods[$module_name] ) and $op == "main" )
	{
		$sql = "UPDATE `" . NV_MODULES_TABLE . "` SET `admin_file`='0' WHERE `title`=" . $db->dbescape( $module_name );
		$db->sql_query( $sql );
		nv_del_moduleCache( 'modules' );
	}
}

nv_info_die( $lang_global['error_404_title'], $lang_global['error_404_title'], $lang_global['error_404_content'] );

?>