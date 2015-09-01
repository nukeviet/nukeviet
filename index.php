<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 31/05/2010, 00:36
 */

define( 'NV_SYSTEM', true );

// Xac dinh thu muc goc cua site
define( 'NV_ROOTDIR', pathinfo( str_replace( DIRECTORY_SEPARATOR, '/', __file__ ), PATHINFO_DIRNAME ) );

require NV_ROOTDIR .'/includes/mainfile.php';

require NV_ROOTDIR . '/includes/core/user_functions.php';

// Google Sitemap
if( $nv_Request->isset_request( NV_NAME_VARIABLE, 'get' ) and $nv_Request->get_string( NV_NAME_VARIABLE, 'get' ) == 'SitemapIndex' )
{
	nv_xmlSitemapIndex_generate();
	die();
}

// Check user
if( defined( 'NV_IS_USER' ) ) trigger_error( 'Hacking attempt', 256 );
require NV_ROOTDIR . '/includes/core/is_user.php';

// Cap nhat trang thai online
if( $global_config['online_upd'] and ! defined( 'NV_IS_AJAX' ) and ! defined( 'NV_IS_MY_USER_AGENT' ) )
{
	require NV_ROOTDIR . '/includes/core/online.php';
}

// Thong ke
if( $global_config['statistic'] and ! defined( 'NV_IS_AJAX' ) and ! defined( 'NV_IS_MY_USER_AGENT' ) )
{
	if( ! $nv_Request->isset_request( 'statistic_' . NV_LANG_DATA, 'cookie' ) )
	{
		require NV_ROOTDIR . '/includes/core/stat.php';
	}
}

// Referer + Gqueries
if( $client_info['is_myreferer'] === 0 and ! defined( 'NV_IS_MY_USER_AGENT' ) )
{
	require NV_ROOTDIR . '/includes/core/referer.php';
}

if( $nv_Request->isset_request( NV_NAME_VARIABLE, 'get' ) || $nv_Request->isset_request( NV_NAME_VARIABLE, 'post' ) )
{
	$home = 0;
	$module_name = $nv_Request->get_string( NV_NAME_VARIABLE, 'post,get' );

	if( empty( $module_name ) )
	{
		$module_name = $global_config['rewrite_op_mod'];
	}
}
else
{
	$home = 1;
	$module_name = $global_config['site_home_module'];
	$meta_property['og:title'] = $global_config['site_name'];
}

if( preg_match( $global_config['check_module'], $module_name ) )
{
	$site_mods = nv_site_mods( $module_name );
	// IMG thong ke truy cap + online
	if( $global_config['statistic'] and isset( $sys_mods['statistics'] ) and $nv_Request->get_string( 'second', 'get' ) == 'statimg' )
	{
		include_once NV_ROOTDIR . '/includes/core/statimg.php';
	}
	$op = $nv_Request->get_string( NV_OP_VARIABLE, 'post,get', 'main' );
	if( empty( $op ) ) $op = 'main';

	if( $global_config['rewrite_op_mod'] != '' and ! isset( $sys_mods[$module_name] ) )
	{
		$op = ( $op == 'main' ) ? $module_name : $module_name . '/' . $op;
		$module_name = $global_config['rewrite_op_mod'];
	}

	// Kiểm tra module có trong hệ thống hay không
	if( isset( $site_mods[$module_name] ) )
	{
		// SSL
		if( $global_config['ssl_https'] === 3 and ! empty( $global_config['ssl_https_modules'] ) and in_array( $module_name, $global_config['ssl_https_modules'] ) and ( ! isset( $_SERVER['HTTPS'] ) or $_SERVER['HTTPS'] == 'off' ) )
		{
			header( "HTTP/1.1 301 Moved Permanently" );
			header( "Location: https://" . $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"] );
			exit();
		}

		// Global variable for module
		$module_info = $site_mods[$module_name];
		$module_file = $module_info['module_file'];
		$module_data = $module_info['module_data'];
		$module_upload = $module_info['module_upload'];
		$include_file = NV_ROOTDIR . '/modules/' . $module_file . '/funcs/main.php';

		if( file_exists( $include_file ) )
		{
			// Tuy chon kieu giao dien
			if( $nv_Request->isset_request( 'nv' . NV_LANG_DATA . 'themever', 'get' ) )
			{
				if( empty( $global_config['switch_mobi_des'] ) )
				{
					$array_theme_type  = array_diff( $global_config['array_theme_type'], array( 'm' ) );
				}
				else
				{
					$array_theme_type  =  $global_config['array_theme_type'];
				}
                
                $theme_type = $nv_Request->get_title( 'nv' . NV_LANG_DATA . 'themever', 'get', '', 1 );
				if( in_array( $theme_type, $array_theme_type ) ) $nv_Request->set_Cookie( 'nv' . NV_LANG_DATA . 'themever', $theme_type, NV_LIVE_COOKIE_TIME );

                $nv_redirect = nv_get_redirect( 'get', true );
				if( empty ( $nv_redirect ) ) $nv_redirect = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA;
				Header( 'Location: ' . nv_url_rewrite( $nv_redirect ) );
				die();
			}

			// Xac dinh cac $op, $array_op
			$array_op = array();

			if( ! preg_match( '/^[a-z0-9\-\_\/\+]+$/i', $op ) )
			{
				Header( 'Location: ' . nv_url_rewrite( NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name, true ) );
				die();
			}

			if( $op != 'main' and ! isset( $module_info['funcs'][$op] ) )
			{
				$array_op = explode( '/', $op );
				$op = ( isset( $module_info['funcs'][$array_op[0]] ) ) ? $array_op[0] : 'main';
			}
			$op_file = $op;

			// Xac dinh quyen dieu hanh module
			if( $module_info['is_modadmin'] )
			{
				define( 'NV_IS_MODADMIN', true );
			}

			if( defined( 'NV_IS_SPADMIN' ) )
			{
				$drag_block = $nv_Request->get_int( 'drag_block', 'session', 0 );
				if( $nv_Request->isset_request( 'drag_block', 'get' ) )
				{
					$drag_block = $nv_Request->get_int( 'drag_block', 'get', 0 );
					$nv_Request->set_Session( 'drag_block', $drag_block );

					$nv_redirect = nv_get_redirect( 'get', true );

                    if( empty( $nv_redirect ) ) $nv_redirect = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name;
					Header( 'Location: ' . nv_url_rewrite( $nv_redirect, true ) );
					die();
				}
				if( $drag_block )
				{
					define( 'NV_IS_DRAG_BLOCK', true );
					$adm_int_lang = $nv_Request->get_string( 'int_lang', 'cookie' );
					if( $adm_int_lang != NV_LANG_DATA )
					{
						$nv_Request->set_Cookie( 'int_lang', NV_LANG_DATA, NV_LIVE_COOKIE_TIME );
					}
				}
			}

			// Ket noi ngon ngu cua module
			if( file_exists( NV_ROOTDIR . '/modules/' . $module_file . '/language/' . NV_LANG_INTERFACE . '.php' ) )
			{
				require NV_ROOTDIR . '/modules/' . $module_file . '/language/' . NV_LANG_INTERFACE . '.php';
			}
			elseif( file_exists( NV_ROOTDIR . '/modules/' . $module_file . '/language/en.php' ) )
			{
				require NV_ROOTDIR . '/modules/' . $module_file . '/language/en.php';
			}

			// Xac dinh kieu giao dien mac dinh
			$global_config['current_theme_type'] = $nv_Request->get_string( 'nv' . NV_LANG_DATA . 'themever', 'cookie', '' );

			// Xac dinh giao dien chung
			$is_mobile = false;
			$theme_type = '';
			$_theme_mobile = ( ! empty( $module_info['mobile'] ) ) ? $module_info['mobile'] : $global_config['mobile_theme'];
			if( ( ( $client_info['is_mobile'] and ( empty( $global_config['current_theme_type'] ) or empty( $global_config['switch_mobi_des'] ) ) ) or ( $global_config['current_theme_type'] == 'm' and ! empty( $global_config['switch_mobi_des'] ) ) ) and ! empty( $_theme_mobile ) and file_exists( NV_ROOTDIR . '/themes/' . $_theme_mobile . '/theme.php' ) )
			{
				$global_config['module_theme'] = $_theme_mobile;
				$is_mobile = true;
				$theme_type = 'm';
			}
			else
			{
				if( empty( $global_config['current_theme_type'] ) and ( $client_info['is_mobile'] or empty( $_theme_mobile ) ) )
				{
					$global_config['current_theme_type'] = 'r';
				}

				$_theme = ( ! empty( $module_info['theme'] ) ) ? $module_info['theme'] : $global_config['site_theme'];
				if( ! empty( $_theme ) and file_exists( NV_ROOTDIR . '/themes/' . $_theme . '/theme.php' ) )
				{
					$global_config['module_theme'] = $_theme;
					$theme_type = $global_config['current_theme_type'];
				}
				elseif( file_exists( NV_ROOTDIR . '/themes/default/theme.php' ) )
				{
					$global_config['module_theme'] = 'default';
					$theme_type = $global_config['current_theme_type'];
				}
				else
				{
					trigger_error( 'Error! Does not exist themes default', 256 );
				}
			}

			// Xac lap lai giao kieu giao dien hien tai
			if( $theme_type != $global_config['current_theme_type'] )
			{
				$global_config['current_theme_type'] = $theme_type;
				$nv_Request->set_Cookie( 'nv' . NV_LANG_DATA . 'themever', $theme_type, NV_LIVE_COOKIE_TIME );
			}
			unset( $theme_type );

			// Xac dinh layout funcs cua module
			$cache_file = NV_LANG_DATA . '_' . md5( $module_name . '_' . $global_config['module_theme'] ) . '_' . NV_CACHE_PREFIX . '.cache';
			if( ( $cache = nv_get_cache( 'modules', $cache_file ) ) != false )
			{
				$module_info['layout_funcs'] = unserialize( $cache );
			}
			else
			{
				$module_info['layout_funcs'] = array();
				$sth = $db->prepare( 'SELECT f.func_name, t.layout FROM ' . NV_MODFUNCS_TABLE . ' f
					INNER JOIN ' . NV_PREFIXLANG . '_modthemes t ON f.func_id=t.func_id
					WHERE f.in_module = :module AND t.theme= :theme' );
				$sth->bindParam( ':module', $module_name, PDO::PARAM_STR );
				$sth->bindParam( ':theme', $global_config['module_theme'], PDO::PARAM_STR );
				$sth->execute();
				while( $row = $sth->fetch() )
				{
					$module_info['layout_funcs'][$row['func_name']] = $row['layout'];
				}
				$sth->closeCursor();

				$cache = serialize( $module_info['layout_funcs'] );
				nv_set_cache( 'modules', $cache_file, $cache );
			}

			// Doc file cau hinh giao dien
			$themeConfig = nv_object2array( simplexml_load_file( NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/config.ini' ) );
			require NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/theme.php';

			// Ket noi ngon ngu theo theme
			if( file_exists( NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/language/' . NV_LANG_INTERFACE . '.php' ) )
			{
				require NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/language/' . NV_LANG_INTERFACE . '.php';
			}
			elseif( file_exists( NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/language/en.php' ) )
			{
				require NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/language/en.php';
			}

			// Xac dinh template module
			$module_info['template'] = $global_config['module_theme'];
			if( ! file_exists( NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file ) )
			{
				if( file_exists( NV_ROOTDIR . '/themes/default/modules/' . $module_file ) )
				{
					$module_info['template'] = 'default';
				}
			}

			// Ket noi voi file functions.php, file chua cac function dung chung
			// cho ca module
			if( file_exists( NV_ROOTDIR . '/modules/' . $module_file . '/functions.php' ) )
			{
				require NV_ROOTDIR . '/modules/' . $module_file . '/functions.php';
			}

			// Xac dinh op file
			$op_file = $module_info['funcs'][$op]['func_name'];

			if( file_exists( NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file . '/theme.php' ) )
			{
				require NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file . '/theme.php';
			}
			elseif( file_exists( NV_ROOTDIR . '/modules/' . $module_file . '/theme.php' ) )
			{
				require NV_ROOTDIR . '/modules/' . $module_file . '/theme.php';
			}

			if( ! defined( 'NV_IS_AJAX' ) )
			{
				nv_create_submenu();
			}

			// Ket noi voi cac op cua module de thuc hien
			if( $is_mobile and file_exists( NV_ROOTDIR . '/modules/' . $module_file . '/mobile/' . $op_file . '.php' ) )
			{
				require NV_ROOTDIR . '/modules/' . $module_file . '/mobile/' . $op_file . '.php';
			}
			else
			{
				require NV_ROOTDIR . '/modules/' . $module_file . '/funcs/' . $op_file . '.php';
			}
			exit();
		}
		elseif( isset( $module_info['funcs']['main'] ) )
		{
			$sth = $db->prepare( 'UPDATE ' . NV_MODULES_TABLE . ' SET act=2 WHERE title= :title' );
			$sth->bindParam( ':title', $module_name, PDO::PARAM_STR );
			$sth->execute();

			nv_insert_notification( 'modules', 'auto_deactive_module', array( 'custom_title' => $site_mods[$module_name]['custom_title'] ) );
			nv_del_moduleCache( 'modules' );
		}
	}
	elseif( isset( $sys_mods[$module_name] ) )
	{
		$groups_view = ( string )$sys_mods[$module_name]['groups_view'];
		if( ! defined( 'NV_IS_USER' ) and $groups_view == '4' )
		{
			Header( 'Location: ' . nv_url_rewrite( NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=users&' . NV_OP_VARIABLE . '=login&nv_redirect=' . nv_redirect_encrypt( $client_info['selfurl'] ), true ) );
            die();
		}
		elseif( ! defined( 'NV_IS_ADMIN' ) and ( $groups_view == '2' or $groups_view == '1' ) )
		{
			// Exit
			nv_info_die( $lang_global['error_404_title'], $lang_global['site_info'], $lang_global['module_for_admin'] );
		}
		elseif( defined( 'NV_IS_USER' ) and ! nv_user_in_groups( $groups_view ) )
		{
			nv_info_die( $lang_global['error_404_title'], $lang_global['error_404_title'], $lang_global['error_404_content'] );
		}
	}
}

nv_info_die( $lang_global['error_404_title'], $lang_global['error_404_title'], $lang_global['error_404_content'] );