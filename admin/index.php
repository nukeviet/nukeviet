<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 12/30/2009 6:18
 */

define( 'NV_ADMIN', true );

// Ket noi den mainfile.php nam o thu muc goc.
$realpath_mainfile = $set_active_op = '';

$temp_dir = str_replace( DIRECTORY_SEPARATOR, '/', dirname( __file__ ) );
$temp_path = '/../';
for( $i = 0; $i < 10; ++$i )
{
	$realpath_mainfile = @realpath( $temp_dir . $temp_path . 'mainfile.php' );
	if( ! empty( $realpath_mainfile ) ) break;
	$temp_path .= '../';
}

unset( $temp_dir, $temp_path );

if( empty( $realpath_mainfile ) ) die();

require $realpath_mainfile;

ob_start();

// Admin dang nhap
if( ! defined( 'NV_IS_ADMIN' ) or ! isset( $admin_info ) or empty( $admin_info ) )
{
	require NV_ROOTDIR . '/includes/core/admin_access.php';
	require NV_ROOTDIR . '/includes/core/admin_login.php';
	exit();
}

if( file_exists( NV_ROOTDIR . '/language/' . NV_LANG_INTERFACE . '/admin_global.php' ) )
{
	require NV_ROOTDIR . '/language/' . NV_LANG_INTERFACE . '/admin_global.php';
}
elseif( file_exists( NV_ROOTDIR . '/language/' . NV_LANG_DATA . '/admin_global.php' ) )
{
	require NV_ROOTDIR . '/language/' . NV_LANG_DATA . '/admin_global.php';
}
elseif( file_exists( NV_ROOTDIR . '/language/en/admin_global.php' ) )
{
	require NV_ROOTDIR . '/language/en/admin_global.php';
}

include_once NV_ROOTDIR . '/includes/core/admin_functions.php';

$admin_mods = array();
$result = $db->query( 'SELECT * FROM ' . $db_config['dbsystem'] . '.' . NV_AUTHORS_GLOBALTABLE . '_module WHERE act_' . $admin_info['level'] . ' = 1 ORDER BY weight ASC' );
while( $row = $result->fetch() )
{
	$row['custom_title'] = isset( $lang_global[$row['lang_key']] ) ? $lang_global[$row['lang_key']] : $row['module'];
	$admin_mods[$row['module']] = $row;
}

$module_name = strtolower( $nv_Request->get_title( NV_NAME_VARIABLE, 'post,get', 'siteinfo' ) );
if( ! empty( $module_name ) )
{
	$include_functions = $include_file = $include_menu = $lang_file = $mod_theme_file = '';
	$module_data = $module_file = $module_name;

	$op = $nv_Request->get_title( NV_OP_VARIABLE, 'post,get', 'main' );
	if( empty( $op ) or $op == 'functions' )
	{
		$op = 'main';
	}

	$site_mods = nv_site_mods();
	if( empty( $site_mods ) and $module_name != 'language' )
	{
		$sql = "SELECT setup FROM " . $db_config['prefix'] . "_setup_language WHERE lang='" . NV_LANG_DATA . "'";
		$setup = $db->query( $sql )->fetchColumn();
		if( empty( $setup ) )
		{
			Header( 'Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=language' );
			exit();
		}
	}
	$menu_top = array();
	if( isset( $admin_mods['database'] ) and ! ( defined( 'NV_IS_GODADMIN' ) OR ( defined( 'NV_IS_SPADMIN' ) and $global_config['idsite'] > 0 ) ) )
	{
		unset( $admin_mods['database'] );
	}

	if( isset( $site_mods[$module_name] ) )
	{
		$module_info = $site_mods[$module_name];
		$module_file = $module_info['module_file'];
		$module_data = $module_info['module_data'];
		$include_functions = NV_ROOTDIR . '/modules/' . $module_file . '/admin.functions.php';
		$include_menu = NV_ROOTDIR . '/modules/' . $module_file . '/admin.menu.php';
		$include_file = NV_ROOTDIR . '/modules/' . $module_file . '/admin/' . $op . '.php';

		//Ket noi ngon ngu cua module
		if( file_exists( NV_ROOTDIR . '/modules/' . $module_file . '/language/admin_' . NV_LANG_INTERFACE . '.php' ) )
		{
			require NV_ROOTDIR . '/modules/' . $module_file . '/language/admin_' . NV_LANG_INTERFACE . '.php';
		}
		elseif( file_exists( NV_ROOTDIR . '/modules/' . $module_file . '/language/admin_' . NV_LANG_DATA . '.php' ) )
		{
			require NV_ROOTDIR . '/modules/' . $module_file . '/language/admin_' . NV_LANG_DATA . '.php';
		}
		elseif( file_exists( NV_ROOTDIR . '/modules/' . $module_file . '/language/admin_en.php' ) )
		{
			require NV_ROOTDIR . '/modules/' . $module_file . '/language/admin_en.php';
		}
	}
	elseif( isset( $admin_mods[$module_name] ) )
	{
		$module_info = $admin_mods[$module_name];
		if( md5( $module_info['module'] . '#' . $module_info['act_1'] . '#' . $module_info['act_2'] . '#' . $module_info['act_3'] . '#' . $global_config['sitekey'] ) )
		{
			$module_file = $module_name;
			$include_functions = NV_ROOTDIR . '/' . NV_ADMINDIR . '/' . $module_file . '/functions.php';
			$include_menu = NV_ROOTDIR . '/' . NV_ADMINDIR . '/' . $module_file . '/admin.menu.php';
			$include_file = NV_ROOTDIR . '/' . NV_ADMINDIR . '/' . $module_file . '/' . $op . '.php';

			// Ket noi voi file ngon ngu cua module
			if( file_exists( NV_ROOTDIR . '/language/' . NV_LANG_INTERFACE . '/admin_' . $module_file . '.php' ) )
			{
				require NV_ROOTDIR . '/language/' . NV_LANG_INTERFACE . '/admin_' . $module_file . '.php';
			}
			elseif( file_exists( NV_ROOTDIR . '/language/' . NV_LANG_DATA . '/admin_' . $module_file . '.php' ) )
			{
				require NV_ROOTDIR . '/language/' . NV_LANG_DATA . '/admin_' . $module_file . '.php';
			}
			elseif( file_exists( NV_ROOTDIR . '/language/en/admin_' . $module_file . '.php' ) )
			{
				require NV_ROOTDIR . '/language/en/admin_' . $module_file . '.php';
			}
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
				if( file_exists( NV_ROOTDIR . '/language/' . $lang_i . '/global.php' ) )
				{
					$array_lang_admin[$lang_i] = $language_array[$lang_i]['name'];
				}
			}
		}

		//ket noi voi giao dien chung cua admin
		require NV_ROOTDIR . '/themes/' . $global_config['admin_theme'] . '/theme.php';

		// Ket noi giao dien cua module
		$global_config['module_theme'] = '';
		if( is_dir( NV_ROOTDIR . '/themes/' . $global_config['admin_theme'] . '/modules/' . $module_file ) )
		{
			$global_config['module_theme'] = $global_config['admin_theme'];
		}
		elseif( is_dir( NV_ROOTDIR . '/themes/admin_default/modules/' . $module_file ) )
		{
			$global_config['module_theme'] = 'admin_default';
		}

		$allow_func = array();
		//Ket noi menu cua module
		if( file_exists( $include_menu ) )
		{
			require $include_menu;
		}

		require $include_functions;
		if( in_array( $op, $allow_func ) )
		{
			$admin_menu_mods = array();
			if( ! empty( $menu_top ) and ! empty( $submenu ) )
			{
				$admin_menu_mods[$module_name] = $menu_top['custom_title'];
			}
			elseif( isset( $site_mods[$module_name] ) )
			{
				$admin_menu_mods[$module_name] = $site_mods[$module_name]['admin_title'];
			}
			foreach( $site_mods as $key => $value )
			{
				if( $value['admin_file'] ) $admin_menu_mods[$key] = $value['admin_title'];
			}
			require $include_file;
			exit();
		}
		else
		{
			nv_info_die( $lang_global['error_404_title'], $lang_global['error_404_title'], $lang_global['admin_no_allow_func'] );
		}
	}
	elseif( isset( $site_mods[$module_name] ) and $op == 'main' )
	{
		$sth = $db->prepare( 'UPDATE ' . NV_MODULES_TABLE . ' SET admin_file=0 WHERE title= :module_name' );
		$sth->bindParam( ':module_name', $module_name, PDO::PARAM_STR );
		$sth->execute();

		nv_del_moduleCache( 'modules' );
	}
}

nv_info_die( $lang_global['error_404_title'], $lang_global['error_404_title'], $lang_global['error_404_content'] );