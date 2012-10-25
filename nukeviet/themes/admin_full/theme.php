<?php

/**
 * @Project NUKEVIET 3.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2012 VINADES.,JSC. All rights reserved
 * @Createdate 31/05/2010, 00:36
 */

if( ! defined( 'NV_MAINFILE' ) ) die( 'Stop!!!' );

function nv_get_submenu( $mod )
{
	global $lang_global, $module_name;
	
	$submenu = array();

	if( $mod != $module_name and file_exists( NV_ROOTDIR . "/" . NV_ADMINDIR . "/modules/" . $mod . "/functions.php" ) )
	{
		//ket noi voi file ngon ngu cua module
		if( file_exists( NV_ROOTDIR . "/language/" . NV_LANG_INTERFACE . "/admin_" . $mod . ".php" ) )
		{
			include ( NV_ROOTDIR . "/language/" . NV_LANG_INTERFACE . "/admin_" . $mod . ".php" );
		}
		elseif( file_exists( NV_ROOTDIR . "/language/" . NV_LANG_DATA . "/admin_" . $mod . ".php" ) )
		{
			include ( NV_ROOTDIR . "/language/" . NV_LANG_DATA . "/admin_" . $mod . ".php" );
		}
		elseif( file_exists( NV_ROOTDIR . "/language/en/admin_" . $mod . ".php" ) )
		{
			include ( NV_ROOTDIR . "/language/en/admin_" . $mod . ".php" );
		}
	
		include ( NV_ROOTDIR . "/" . NV_ADMINDIR . "/modules/" . $mod . "/functions.php" );
		unset( $lang_module );
	}

	return $submenu;
}

function nv_admin_theme( $contents, $head_site = 1 )
{
	global $global_config, $lang_global, $admin_mods, $site_mods, $admin_menu_mods, $module_name, $module_file, $module_info, $admin_info, $db, $page_title, $submenu, $select_options, $op, $set_active_op, $array_lang_admin, $my_head;

	$dir_template = "";

	if( $head_site == 1 )
	{
		$file_name_tpl = "main.tpl";
	
		if( file_exists( NV_ROOTDIR . "/themes/" . $global_config['admin_theme'] . "/system/" . $file_name_tpl ) )
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
	
		if( file_exists( NV_ROOTDIR . "/themes/" . $global_config['admin_theme'] . "/system/" . $file_name_tpl ) )
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
	$xtpl->assign( 'NV_SITE_COPYRIGHT', "" . $global_config['site_name'] . " [" . $global_config['site_email'] . "] " );
	$xtpl->assign( 'NV_SITE_NAME', $global_config['site_name'] );
	$xtpl->assign( 'NV_SITE_TITLE', "" . $global_config['site_name'] . " " . NV_TITLEBAR_DEFIS . " " . $lang_global['admin_page'] . " " . NV_TITLEBAR_DEFIS . " " . $module_info['custom_title'] . "" );
	$xtpl->assign( 'NV_ADMIN_CHECK_PASS_TIME', NV_ADMIN_CHECK_PASS_TIME );
	$xtpl->assign( 'NV_BASE_SITEURL', NV_BASE_SITEURL );
	$xtpl->assign( 'NV_ADMINDIR', NV_ADMINDIR );
	$xtpl->assign( 'NV_BASE_ADMINURL', NV_BASE_ADMINURL );
	$xtpl->assign( 'MODULE_NAME', $module_name );
	$xtpl->assign( 'MODULE_FILE', $module_file );
	$xtpl->assign( 'NV_ADMIN_THEME', $global_config['admin_theme'] );

	if( file_exists( NV_ROOTDIR . "/themes/" . $global_config['admin_theme'] . "/css/" . $module_file . ".css" ) )
	{
		$xtpl->assign( 'NV_CSS_MODULE_THEME', NV_BASE_SITEURL . "themes/" . $global_config['admin_theme'] . "/css/" . $module_file . ".css" );
		$xtpl->parse( 'main.css_module' );
	}
	elseif( file_exists( NV_ROOTDIR . "/themes/admin_default/css/" . $module_file . ".css" ) )
	{
		$xtpl->assign( 'NV_CSS_MODULE_THEME', NV_BASE_SITEURL . "themes/admin_default/css/" . $module_file . ".css" );
		$xtpl->parse( 'main.css_module' );
	}

	$xtpl->assign( 'NV_LANG_VARIABLE', NV_LANG_VARIABLE );
	$xtpl->assign( 'NV_LANG_INTERFACE', NV_LANG_INTERFACE );
	$xtpl->assign( 'NV_NAME_VARIABLE', NV_NAME_VARIABLE );
	$xtpl->assign( 'NV_OP_VARIABLE', NV_OP_VARIABLE );
	$xtpl->assign( 'NV_LANG_VARIABLE', NV_LANG_VARIABLE );
	$xtpl->assign( 'NV_SITE_TIMEZONE_OFFSET', round( NV_SITE_TIMEZONE_OFFSET / 3600 ) );
	$xtpl->assign( 'NV_CURRENTTIME', nv_date( "T", NV_CURRENTTIME ) );
	$xtpl->assign( 'NV_COOKIE_PREFIX', $global_config['cookie_prefix'] );

	if( file_exists( NV_ROOTDIR . "/js/admin_" . $module_file . ".js" ) )
	{
		$xtpl->assign( 'NV_JS_MODULE', NV_BASE_SITEURL . "js/admin_" . $module_file . ".js" );
		$xtpl->parse( 'main.module_js' );
	}
	elseif( file_exists( NV_ROOTDIR . "/modules/" . $module_file . "/js/admin.js" ) )
	{
		$xtpl->assign( 'NV_JS_MODULE', NV_BASE_SITEURL . "modules/" . $module_file . "/js/admin.js" );
		$xtpl->parse( 'main.module_js' );
	}

	if( defined( 'NV_EDITOR' ) and nv_function_exists( 'nv_add_editor_js' ) )
	{
		$xtpl->assign( 'NV_ADD_EDITOR_JS', nv_add_editor_js() );
		$xtpl->parse( 'main.nv_add_editor_js' );
	}

	if( ! empty( $my_head ) )
	{
		$xtpl->assign( 'NV_ADD_MY_HEAD', $my_head );
		$xtpl->parse( 'main.nv_add_my_head' );
	}

	if( $head_site == 1 )
	{
		$xtpl->assign( 'NV_GO_CLIENTSECTOR', $lang_global['go_clientsector'] );
		$lang_site = ( ! empty( $site_mods ) ) ? NV_LANG_DATA : $global_config['site_lang'];
		$xtpl->assign( 'NV_GO_CLIENTSECTOR_URL', NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . $lang_site );
		$xtpl->assign( 'NV_LOGOUT', $lang_global['logout'] );

		if( ! empty( $array_lang_admin ) )
		{
			$xtpl->assign( 'NV_LANGDATA', $lang_global['langdata'] );
		
			foreach( $array_lang_admin as $lang_i => $lang_name )
			{
				$xtpl->assign( 'SELECTED', ( $lang_i == NV_LANG_DATA ) ? " selected=\"selected\"" : "" );
				$xtpl->assign( 'LANGVALUE', $lang_name );
				$xtpl->assign( 'LANGOP', NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . $lang_i );
				$xtpl->parse( 'main.langdata.option' );
			}
		
			$xtpl->parse( 'main.langdata' );
		}

		//Top_menu
		foreach( $admin_mods as $m => $v )
		{
			if( ! empty( $v['custom_title'] ) )
			{
				$xtpl->assign( 'TOP_MENU_CURRENT', ( ( $module_name == $m ) ? " class=\"current\"" : "" ) );
				$xtpl->assign( 'TOP_MENU_HREF', $m );
				$xtpl->assign( 'TOP_MENU_NAME', $v['custom_title'] );
				$array_submenu = nv_get_submenu( $m );
			
				if( ! empty( $array_submenu ) )
				{
					foreach( $array_submenu as $mop => $submenu_i )
					{
						$xtpl->assign( 'SUBMENULINK', NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $m . "&amp;" . NV_OP_VARIABLE . "=" . $mop );
						$xtpl->assign( 'SUBMENUTITLE', $submenu_i );
						$xtpl->parse( 'main.top_menu.top_menu_loop.submenu.submenu_loop' );
					}
				
					$xtpl->parse( 'main.top_menu.top_menu_loop.submenu' );
				}
			
				$xtpl->parse( 'main.top_menu.top_menu_loop' );
			}
		}
	
		$xtpl->parse( 'main.top_menu' );
		$xtpl->assign( 'NV_DIGCLOCK', nv_date( "H:i T l, d/m/Y", NV_CURRENTTIME ) );
	
		if( $admin_info['current_login'] >= NV_CURRENTTIME - 60 )
		{
			if( ! empty( $admin_info['last_login'] ) )
			{
				$temp = sprintf( $lang_global['hello_admin1'], "<strong>" . $admin_info['username'] . "</strong>", date( "H:i d/m/Y", $admin_info['last_login'] ), $admin_info['last_ip'] );
				$xtpl->assign( 'HELLO_ADMIN1', $temp );
				$xtpl->parse( 'main.hello_admin' );
			}
			else
			{
				$temp = sprintf( $lang_global['hello_admin3'], "<strong>" . $admin_info['username'] . "</strong>" );
				$xtpl->assign( 'HELLO_ADMIN3', $temp );
				$xtpl->parse( 'main.hello_admin3' );
			}
		}
		else
		{
			$temp = sprintf( $lang_global['hello_admin2'], "<strong>" . $admin_info['username'] . "</strong>", nv_convertfromSec( NV_CURRENTTIME - $admin_info['current_login'] ), $admin_info['current_ip'] );
			$xtpl->assign( 'HELLO_ADMIN2', $temp );
			$xtpl->parse( 'main.hello_admin2' );
		}
	
		if( ! empty( $admin_menu_mods ) )
		{
			//Vertical menu
			foreach( $admin_menu_mods as $m => $v )
			{
				$xtpl->assign( 'VERTICAL_MENU_CURRENT', ( ( $module_name == $m ) ? "class=\"current\"" : "" ) );
				$xtpl->assign( 'VERTICAL_MENU_HREF', $m );
				$xtpl->assign( 'VERTICAL_MENU_NAME', $v );
			
				if( $m == $module_name and ! empty( $submenu ) )
				{
					foreach( $submenu as $n => $l )
					{
						$xtpl->assign( 'VERTICAL_MENU_SUB_CURRENT', ( ( ( ! empty( $op ) and $op == $n ) or ( ! empty( $set_active_op ) and $set_active_op == $n ) ) ? " class=\"sub_current\"" : " class=\"sub_normal\"" ) );
						$xtpl->assign( 'VERTICAL_MENU_SUB_HREF', $m );
						$xtpl->assign( 'VERTICAL_MENU_SUB_HREF1', $n );
						$xtpl->assign( 'VERTICAL_MENU_SUB_NAME', $l );
						$xtpl->parse( 'main.vertical_menu.vertical_menu_loop.vertical_menu_sub_loop' );
					}
				}
			
				$xtpl->parse( 'main.vertical_menu.vertical_menu_loop' );
			}
		
			$xtpl->parse( 'main.vertical_menu' );
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

	if( ! empty( $page_title ) )
	{
		$xtpl->assign( 'PAGE_TITLE', $page_title );
		$xtpl->parse( 'main.empty_page_title' );
	}

	$xtpl->assign( 'THEME_ERROR_INFO', nv_error_info() );
	$xtpl->assign( 'MODULE_CONTENT', $contents );

	$end_time = array_sum( explode( " ", microtime() ) );

	$xtpl->assign( 'NV_TOTAL_TIME', substr( ( $end_time - NV_START_TIME + $db->time ), 0, 5 ) );

	if( defined( "NV_IS_SPADMIN" ) )
	{
		$xtpl->assign( 'NV_SHOW_QUERIES', $lang_global['show_queries'] );
	}

	$xtpl->assign( 'NV_DB_NUM_QUERIES', $lang_global['db_num_queries'] );
	$xtpl->assign( 'COUNT_QUERY_STRS', sizeof( $db->query_strs ) );
	$xtpl->assign( 'NV_COPYRIGHT', sprintf( $lang_global['copyright'], $global_config['site_name'] ) );

	if( defined( "NV_IS_SPADMIN" ) )
	{
		foreach( $db->query_strs as $key => $field )
		{
			$xtpl->assign( 'NV_SHOW_QUERIES_CLASS', ( $key % 2 ) ? " class=\"second\"" : "" );
			$xtpl->assign( 'NV_FIELD1', ( $field[1] ? "<img alt=\"" . $lang_global['ok'] . "\" title=\"" . $lang_global['ok'] . "\" src=\"" . NV_BASE_SITEURL . "themes/" . $global_config['admin_theme'] . "/images/icons/good.png\" />" : "<img alt=\"" . $lang_global['fail'] . "\" title=\"" . $lang_global['fail'] . "\" src=\"" . NV_BASE_SITEURL . "themes/" . $global_config['admin_theme'] . "/images/icons/bad.png\" />" ) );
			$xtpl->assign( 'NV_FIELD', $field[0] );
			$xtpl->parse( 'main.nv_show_queries.nv_show_queries_loop' );
		}

		$xtpl->parse( 'main.nv_show_queries' );
	}

	if( NV_LANG_INTERFACE == 'vi' and NV_LANG_DATA == 'vi' )
	{
		$xtpl->parse( 'main.nv_if_mudim' );
	}

	$xtpl->assign( 'NV_GENPASS', nv_genpass() );
	$xtpl->parse( 'main' );

	return $xtpl->text( 'main' );
}

?>