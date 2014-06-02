<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 31/05/2010, 00:36
 */

if( ! defined( 'NV_MAINFILE' ) ) die( 'Stop!!!' );

function nv_get_submenu( $mod )
{
	global $lang_global, $module_name, $global_config, $admin_mods;

	$submenu = array();

	if( $mod != $module_name and file_exists( NV_ROOTDIR . '/' . NV_ADMINDIR . '/' . $mod . '/admin.menu.php' ) )
	{
		//ket noi voi file ngon ngu cua module
		if( file_exists( NV_ROOTDIR . '/language/' . NV_LANG_INTERFACE . '/admin_' . $mod . '.php' ) )
		{
			include NV_ROOTDIR . '/language/' . NV_LANG_INTERFACE . '/admin_' . $mod . '.php';
		}
		elseif( file_exists( NV_ROOTDIR . '/language/' . NV_LANG_DATA . '/admin_' . $mod . '.php' ) )
		{
			include NV_ROOTDIR . '/language/' . NV_LANG_DATA . '/admin_' . $mod . '.php';
		}
		elseif( file_exists( NV_ROOTDIR . '/language/en/admin_' . $mod . '.php' ) )
		{
			include NV_ROOTDIR . '/language/en/admin_' . $mod . '.php';
		}

		include NV_ROOTDIR . '/' . NV_ADMINDIR . '/' . $mod . '/admin.menu.php';
		unset( $lang_module );
	}

	return $submenu;
}

function nv_get_submenu_mod( $module_name )
{
	global $lang_global, $global_config, $db, $site_mods, $admin_info, $db_config, $admin_mods;

	$submenu = array();
	if( isset( $site_mods[$module_name] ) )
	{
		$module_info = $site_mods[$module_name];
		$module_file = $module_info['module_file'];
		$module_data = $module_info['module_data'];
		if( file_exists( NV_ROOTDIR . '/modules/' . $module_file . '/admin.menu.php' ) )
		{
			//ket noi voi file ngon ngu cua module
			if( file_exists( NV_ROOTDIR . '/modules/' . $module_file . '/language/admin_' . NV_LANG_INTERFACE . '.php' ) )
			{
				include NV_ROOTDIR . '/modules/' . $module_file . '/language/admin_' . NV_LANG_INTERFACE . '.php';
			}
			elseif( file_exists( NV_ROOTDIR . '/modules/' . $module_file . '/language/admin_' . NV_LANG_DATA . '.php' ) )
			{
				include NV_ROOTDIR . '/modules/' . $module_file . '/language/admin_' . NV_LANG_DATA . '.php';
			}
			elseif( file_exists( NV_ROOTDIR . '/modules/' . $module_file . '/language/admin_en.php' ) )
			{
				include NV_ROOTDIR . '/modules/' . $module_file . '/language/admin_en.php';
			}

			include NV_ROOTDIR . '/modules/' . $module_file . '/admin.menu.php';
			unset( $lang_module );
		}
	}
	return $submenu;
}

function nv_admin_theme( $contents, $head_site = 1 )
{
	global $global_config, $lang_global, $admin_mods, $site_mods, $admin_menu_mods, $module_name, $module_file, $module_info, $admin_info, $db, $page_title, $submenu, $select_options, $op, $set_active_op, $array_lang_admin, $my_head, $my_footer, $array_mod_title;

	$dir_template = '';

	if( $head_site == 1 )
	{
		$file_name_tpl = "main.tpl";

		if( file_exists( NV_ROOTDIR . '/themes/' . $global_config['admin_theme'] . '/system/' . $file_name_tpl ) )
		{
			$dir_template = NV_ROOTDIR . "/themes/" . $global_config['admin_theme'] . "/system";
		}
		else
		{
			$dir_template = NV_ROOTDIR . "/themes/admin_default/system";
			$global_config['admin_theme'] = "admin_default";
		}
	}
	else
	{
		$file_name_tpl = "content.tpl";

		if( file_exists( NV_ROOTDIR . '/themes/' . $global_config['admin_theme'] . '/system/' . $file_name_tpl ) )
		{
			$dir_template = NV_ROOTDIR . "/themes/" . $global_config['admin_theme'] . "/system";
		}
		else
		{
			$dir_template = NV_ROOTDIR . "/themes/admin_default/system";
			$global_config['admin_theme'] = "admin_default";
		}
	}

	$global_config['site_name'] = empty( $global_config['site_name'] ) ? NV_SERVER_NAME : $global_config['site_name'];

	$xtpl = new XTemplate( $file_name_tpl, $dir_template );
	$xtpl->assign( 'NV_SITE_COPYRIGHT', $global_config['site_name'] . ' [' . $global_config['site_email'] . '] ' );
	$xtpl->assign( 'NV_SITE_NAME', $global_config['site_name'] );
	$xtpl->assign( 'NV_SITE_TITLE', $global_config['site_name'] . ' ' . NV_TITLEBAR_DEFIS . ' ' . $lang_global['admin_page'] . ' ' . NV_TITLEBAR_DEFIS . ' ' . $module_info['custom_title'] );
	$xtpl->assign( 'SITE_DESCRIPTION', $global_config['site_description'] );
	$xtpl->assign( 'NV_CHECK_PASS_MSTIME', (intval( $global_config['admin_check_pass_time'] ) - 62) * 1000 );
	$xtpl->assign( 'NV_BASE_SITEURL', NV_BASE_SITEURL );
	$xtpl->assign( 'NV_ADMINDIR', NV_ADMINDIR );
	$xtpl->assign( 'NV_BASE_ADMINURL', NV_BASE_ADMINURL );
	$xtpl->assign( 'MODULE_NAME', $module_name );
	$xtpl->assign( 'MODULE_FILE', $module_file );
	$xtpl->assign( 'NV_ADMIN_THEME', $global_config['admin_theme'] );

	if( file_exists( NV_ROOTDIR . '/themes/' . $global_config['admin_theme'] . '/css/' . $module_file . '.css' ) )
	{
		$xtpl->assign( 'NV_CSS_MODULE_THEME', NV_BASE_SITEURL . 'themes/' . $global_config['admin_theme'] . '/css/' . $module_file . '.css' );
		$xtpl->parse( 'main.css_module' );
	}
	elseif( file_exists( NV_ROOTDIR . '/themes/admin_default/css/' . $module_file . '.css' ) )
	{
		$xtpl->assign( 'NV_CSS_MODULE_THEME', NV_BASE_SITEURL . 'themes/admin_default/css/' . $module_file . '.css' );
		$xtpl->parse( 'main.css_module' );
	}

	$xtpl->assign( 'NV_LANG_VARIABLE', NV_LANG_VARIABLE );
	$xtpl->assign( 'NV_LANG_INTERFACE', NV_LANG_INTERFACE );
	$xtpl->assign( 'NV_NAME_VARIABLE', NV_NAME_VARIABLE );
	$xtpl->assign( 'NV_OP_VARIABLE', NV_OP_VARIABLE );
	$xtpl->assign( 'NV_SITE_TIMEZONE_OFFSET', round( NV_SITE_TIMEZONE_OFFSET / 3600 ) );
	$xtpl->assign( 'NV_CURRENTTIME', nv_date( 'T', NV_CURRENTTIME ) );
	$xtpl->assign( 'NV_COOKIE_PREFIX', $global_config['cookie_prefix'] );

	if( file_exists( NV_ROOTDIR . '/js/admin_' . $module_file . '.js' ) )
	{
		$xtpl->assign( 'NV_JS_MODULE', NV_BASE_SITEURL . 'js/admin_' . $module_file . '.js' );
		$xtpl->parse( 'main.module_js' );
	}
	elseif( file_exists( NV_ROOTDIR . '/modules/' . $module_file . '/js/admin.js' ) )
	{
		$xtpl->assign( 'NV_JS_MODULE', NV_BASE_SITEURL . 'modules/' . $module_file . '/js/admin.js' );
		$xtpl->parse( 'main.module_js' );
	}

	if( defined( 'NV_EDITOR' ) and nv_function_exists( 'nv_add_editor_js' ) )
	{
		$xtpl->assign( 'NV_ADD_EDITOR_JS', nv_add_editor_js() );
		$xtpl->parse( 'main.nv_add_editor_js' );
	}

	if( $head_site == 1 )
	{
		$xtpl->assign( 'NV_GO_CLIENTSECTOR', $lang_global['go_clientsector'] );
		$lang_site = ( ! empty( $site_mods )) ? NV_LANG_DATA : $global_config['site_lang'];
		$xtpl->assign( 'NV_GO_CLIENTSECTOR_URL', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . $lang_site );
		$xtpl->assign( 'NV_LOGOUT', $lang_global['admin_logout_title'] );

		if( ! empty( $array_lang_admin ) )
		{
			$xtpl->assign( 'NV_LANGDATA', $lang_global['langdata'] );
			$xtpl->assign( 'NV_LANGDATA_CURRENT', $array_lang_admin[NV_LANG_DATA] );

			foreach( $array_lang_admin as $lang_i => $lang_name )
			{
				$xtpl->assign( 'DISABLED', ($lang_i == NV_LANG_DATA) ? " class=\"disabled\"" : "" );
				$xtpl->assign( 'LANGVALUE', $lang_name );
				$xtpl->assign( 'LANGOP', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . $lang_i );
				$xtpl->parse( 'main.langdata.option' );
			}

			$xtpl->parse( 'main.langdata' );
		}

		// Top_menu
		$top_menu = $admin_mods;
		if( sizeof( $top_menu ) > 8 )
		{
			unset( $top_menu['authors'] );
			unset( $top_menu['language'] );
		}
		foreach( $top_menu as $m => $v )
		{
			if( ! empty( $v['custom_title'] ) and $module_name != $m )
			{
				$array_submenu = nv_get_submenu( $m );

				$xtpl->assign( 'TOP_MENU_CLASS', $array_submenu ? ' class="dropdown"' : '' );
				$xtpl->assign( 'TOP_MENU_HREF', $m );
				$xtpl->assign( 'TOP_MENU_NAME', $v['custom_title'] );

				if( ! empty( $array_submenu ) )
				{
					$xtpl->parse( 'main.top_menu_loop.has_sub' );

					foreach( $array_submenu as $mop => $submenu_i )
					{
						$xtpl->assign( 'SUBMENULINK', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $m . '&amp;' . NV_OP_VARIABLE . '=' . $mop );
						$xtpl->assign( 'SUBMENUTITLE', $submenu_i );
						$xtpl->parse( 'main.top_menu_loop.submenu.submenu_loop' );
					}

					$xtpl->parse( 'main.top_menu_loop.submenu' );
				}

				$xtpl->parse( 'main.top_menu_loop' );
			}
		}

		$xtpl->parse( 'main.top_menu' );
		$xtpl->assign( 'NV_DIGCLOCK', nv_date( 'H:i T l, d/m/Y', NV_CURRENTTIME ) );

		if( $admin_info['current_login'] >= NV_CURRENTTIME - 60 )
		{
			if( ! empty( $admin_info['last_login'] ) )
			{
				$temp = sprintf( $lang_global['hello_admin1'], $admin_info['username'], date( "H:i d/m/Y", $admin_info['last_login'] ), $admin_info['last_ip'] );
				$xtpl->assign( 'HELLO_ADMIN1', $temp );
				$xtpl->parse( 'main.hello_admin' );
			}
			else
			{
				$temp = sprintf( $lang_global['hello_admin3'], $admin_info['username'] );
				$xtpl->assign( 'HELLO_ADMIN3', $temp );
				$xtpl->parse( 'main.hello_admin3' );
			}
		}
		else
		{
			$temp = sprintf( $lang_global['hello_admin2'], $admin_info['username'], nv_convertfromSec( NV_CURRENTTIME - $admin_info['current_login'] ), $admin_info['current_ip'] );
			$xtpl->assign( 'HELLO_ADMIN2', $temp );
			$xtpl->parse( 'main.hello_admin2' );
		}

		// Admin photo
		$xtpl->assign( 'ADMIN_USERNAME', $admin_info['username'] );
		if( ! empty( $admin_info['photo'] ) and file_exists( NV_ROOTDIR . '/' . $admin_info['photo'] ) )
		{
			$xtpl->assign( 'ADMIN_PHOTO', NV_BASE_SITEURL . $admin_info['photo'] );
		}
		else
		{
			$xtpl->assign( 'ADMIN_PHOTO', NV_BASE_SITEURL . 'themes/default/images/users/no_avatar.jpg' );
		}

		// Vertical menu
		foreach( $admin_menu_mods as $m => $v )
		{
			$xtpl->assign( 'MENU_CLASS', (($module_name == $m) ? ' class="active"' : '') );
			$xtpl->assign( 'MENU_HREF', $m );
			$xtpl->assign( 'MENU_NAME', $v );

			if( $m != $module_name )
			{
				$submenu = nv_get_submenu_mod( $m );

				$xtpl->assign( 'MENU_CLASS', $submenu ? ' class="dropdown"' : '' );

				if( ! empty( $submenu ) )
				{
					foreach( $submenu as $n => $l )
					{
						$xtpl->assign( 'MENU_SUB_HREF', $m );
						$xtpl->assign( 'MENU_SUB_OP', $n );
						$xtpl->assign( 'MENU_SUB_NAME', $l );
						$xtpl->parse( 'main.menu_loop.submenu.submenu_loop' );
					}
					$xtpl->parse( 'main.menu_loop.submenu' );
				}
			}
			elseif( ! empty( $submenu ) )
			{
				foreach( $submenu as $n => $l )
				{
					$xtpl->assign( 'MENU_SUB_CURRENT', ((( ! empty( $op ) and $op == $n) or ( ! empty( $set_active_op ) and $set_active_op == $n)) ? "subactive" : "subcurrent") );
					$xtpl->assign( 'MENU_SUB_HREF', $m );
					$xtpl->assign( 'MENU_SUB_OP', $n );
					$xtpl->assign( 'MENU_SUB_NAME', $l );
					$xtpl->parse( 'main.menu_loop.current' );
				}
			}
			$xtpl->parse( 'main.menu_loop' );
		}
	}

	if( ! empty( $select_options ) )
	{
		$xtpl->assign( 'PLEASE_SELECT', $lang_global['please_select'] );

		foreach( $select_options as $value => $link )
		{
			$xtpl->assign( 'SELECT_NAME', $link );
			$xtpl->assign( 'SELECT_VALUE', $value );
			$xtpl->parse( 'main.select_option.select_option_loop' );
		}

		$xtpl->parse( 'main.select_option' );
	}
	elseif( isset( $site_mods[$module_name]['main_file'] ) and $site_mods[$module_name]['main_file'] )
	{
		$xtpl->assign( 'NV_GO_CLIENTMOD', $lang_global['go_clientmod'] );
		$xtpl->parse( 'main.site_mods' );
	}

	/**
	 * Breadcrumbs
	 * Note: If active is true, the link will be dismiss
	 * If empty $array_mod_title and $page_title, breadcrumbs do not display
	 * By default, breadcrumbs is $page_title
	 */
	if( empty( $array_mod_title ) and ! empty( $page_title ) )
	{
		$array_mod_title = array(
			0 => array(
				'title' => $page_title,
				'link' => '',
				'active' => true,
			),
		);
	}

	if( ! empty( $array_mod_title ) )
	{
		foreach( $array_mod_title as $breadcrumbs )
		{
			$xtpl->assign( 'BREADCRUMBS', $breadcrumbs );

			if( ! empty( $breadcrumbs['active'] ) )
			{
				$xtpl->parse( 'main.breadcrumbs.loop.active' );
			}

			if( ! empty( $breadcrumbs['link'] ) and empty( $breadcrumbs['active'] ) )
			{
				$xtpl->parse( 'main.breadcrumbs.loop.linked' );
			}
			else
			{
				$xtpl->parse( 'main.breadcrumbs.loop.text' );
			}

			$xtpl->parse( 'main.breadcrumbs.loop' );
		}

		$xtpl->parse( 'main.breadcrumbs' );
	}

	$xtpl->assign( 'THEME_ERROR_INFO', nv_error_info() );
	$xtpl->assign( 'MODULE_CONTENT', $contents );

	$xtpl->assign( 'NV_COPYRIGHT', sprintf( $lang_global['copyright'], $global_config['site_name'] ) );

	$xtpl->assign( 'LANG_TIMEOUTSESS_NOUSER', $lang_global['timeoutsess_nouser'] );
	$xtpl->assign( 'LANG_TIMEOUTSESS_CLICK', $lang_global['timeoutsess_click'] );
	$xtpl->assign( 'LANG_TIMEOUTSESS_SEC', $lang_global['sec'] );
	$xtpl->assign( 'LANG_TIMEOUTSESS_TIMEOUT', $lang_global['timeoutsess_timeout'] );
	$xtpl->assign( 'MSGBEFOREUNLOAD', $lang_global['msgbeforeunload'] );

	if( defined( 'CKEDITOR' ) )
	{
		$xtpl->parse( 'main.ckeditor' );
	}

	if( defined( "NV_IS_SPADMIN" ) and $admin_info['level'] == 1 )
	{
		$xtpl->parse( 'main.memory_time_usage' );
	}
	$xtpl->parse( 'main' );
	$sitecontent = $xtpl->text( 'main' );

	if( ! empty( $my_head ) )
		$sitecontent = preg_replace( '/(<\/head>)/i', $my_head . "\\1", $sitecontent, 1 );
	if( ! empty( $my_footer ) )
		$sitecontent = preg_replace( '/(<\/body>)/i', $my_footer . "\\1", $sitecontent, 1 );

	return $sitecontent;
}