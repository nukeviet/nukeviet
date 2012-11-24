<?php

/**
 * @Project NUKEVIET 3.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2012 VINADES ., JSC. All rights reserved
 * @Createdate Jan 17, 2011  11:34:27 AM
 */

if( ! defined( 'NV_MAINFILE' ) ) die( 'Stop!!!' );

if( ! nv_function_exists( 'nv_smooth_navigational_menu' ) )
{
	/**
	 * nv_html_sub_menu_mod_users()
	 * 
	 * @param mixed $modvalues
	 * @return
	 */
	function nv_html_sub_menu_mod_users( $modvalues )
	{
		if( defined( 'NV_IS_USER' ) )
		{
			$in_submenu_users = array();
			$in_submenu_users[] = "changepass";
			if( defined( 'NV_OPENID_ALLOWED' ) )
			{
				$in_submenu_users[] = "openid";
			}
			if( ! defined( 'NV_IS_ADMIN' ) )
			{
				$in_submenu_users[] = "logout";
			}
		}
		else
		{
			$in_submenu_users = array(
				"login",
				"register",
				"lostpass" );
		}
		$html = "<ul>\n";
		if( empty( $modvalues['funcs'] ) ) continue;
		foreach( $modvalues['funcs'] as $key => $sub_item )
		{
			if( $sub_item['in_submenu'] == 1 and in_array( $key, $in_submenu_users ) )
			{
				$html .= "<li><a title=\"" . $sub_item['func_custom_name'] . "\" href=\"" . NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=users&amp;" . NV_OP_VARIABLE . "=" . $key . "\">" . $sub_item['func_custom_name'] . "</a></li>\n";
			}
		}
		$html .= "</ul>\n";
		return $html;
	}

	/**
	 * nv_submenu_html_item()
	 * 
	 * @param mixed $module_array_cat
	 * @param integer $parentid
	 * @return
	 */
	function nv_submenu_html_item( $module_array_cat, $parentid = 0 )
	{
		$array_item = array();
		foreach( $module_array_cat as $cat )
		{
			if( $cat['parentid'] == $parentid )
			{
				$array_item[] = array(
					'catid' => $cat['catid'],
					'title' => $cat['title'],
					'link' => $cat['link'] );
			}
		}

		if( ! empty( $array_item ) )
		{
			$html = "<ul>\n";
			foreach( $array_item as $cat )
			{
				$html .= "<li>\n";
				$html .= "<a title=\"" . $cat['title'] . "\" href=\"" . $cat['link'] . "\">" . $cat['title'] . "</a>\n";
				$html .= nv_submenu_html_item( $module_array_cat, $cat['catid'] );
				$html .= "</li>\n";
			}
			$html .= "</ul>\n";
			return $html;
		}
		return "";
	}

	/**
	 * nv_smooth_navigational_menu()
	 * 
	 * @param mixed $block_config
	 * @return
	 */
	function nv_smooth_navigational_menu( $block_config )
	{
		global $db, $db_config, $global_config, $site_mods, $module_info, $module_name, $module_file, $module_data, $lang_global, $catid;

		if( file_exists( NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/menu/smooth_navigational_menu.tpl" ) )
		{
			$block_theme = $global_config['module_theme'];
		}
		elseif( file_exists( NV_ROOTDIR . "/themes/" . $global_config['site_theme'] . "/modules/menu/smooth_navigational_menu.tpl" ) )
		{
			$block_theme = $global_config['site_theme'];
		}
		else
		{
			$block_theme = "default";
		}

		$xtpl = new XTemplate( "smooth_navigational_menu.tpl", NV_ROOTDIR . "/themes/" . $block_theme . "/modules/menu" );
		$xtpl->assign( 'LANG', $lang_global );
		$xtpl->assign( 'NV_BASE_SITEURL', NV_BASE_SITEURL );
		$xtpl->assign( 'BLOCK_THEME', $block_theme );
		$xtpl->assign( 'THEME_SITE_HREF', NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA );
		$xtpl->assign( 'THEME_RSS_INDEX_HREF', NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=rss" );

		foreach( $site_mods as $modname => $modvalues )
		{
			if( ! empty( $modvalues['in_menu'] ) )
			{
				$module_current = ( $modname == $module_name ) ? ' class="current"' : '';
				$array_menu = array(
					"title" => $modvalues['custom_title'],
					"class" => $modname,
					"current" => $module_current,
					"link" => NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $modname,
					"submenu" => "" );
				$mod_file = empty( $modvalues['module_file'] ) ? "" : $modvalues['module_file'];
				$array_m_html_item = array(
					'news',
					'shops',
					'weblinks',
					'download' );

				if( in_array( $mod_file, $array_m_html_item ) )
				{
					if( $mod_file == "shops" )
					{
						$sql = "SELECT `catid`, `parentid`, `" . NV_LANG_DATA . "_title` as title, `" . NV_LANG_DATA . "_alias` as alias FROM `" . NV_PREFIXLANG . "_" . $modvalues['module_data'] . "_cat` ORDER BY `order` ASC";
					}
					elseif( $mod_file == "download" )
					{
						$sql = "SELECT `id` as catid, `parentid`, `title`, `alias` FROM `" . NV_PREFIXLANG . "_" . $modvalues['module_data'] . "_categories` ORDER BY `parentid` ASC, `weight` ASC";
					}
					elseif( $mod_file == "weblinks" )
					{
						$sql = "SELECT `catid`, `parentid`, `title`, `alias` FROM `" . NV_PREFIXLANG . "_" . $modvalues['module_data'] . "_cat` ORDER BY `parentid` ASC, `weight` ASC";
					}
					else
					{
						$sql = "SELECT `catid`, `parentid`, `title`, `alias` FROM `" . NV_PREFIXLANG . "_" . $modvalues['module_data'] . "_cat` ORDER BY `order` ASC";
					}
					$list = nv_db_cache( $sql, 'catid', $modname );
					$module_array_cat = array();
					foreach( $list as $l )
					{
						$module_array_cat[$l['catid']] = $l;
						$module_array_cat[$l['catid']]['link'] = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $modname . "&amp;" . NV_OP_VARIABLE . "=" . $l['alias'];
					}
					$array_menu['submenu'] = nv_submenu_html_item( $module_array_cat );
				}
				elseif( $mod_file == "users" )
				{
					$array_menu['submenu'] = nv_html_sub_menu_mod_users( $modvalues );
				}
				elseif( $mod_file == "message" )
				{
					if( defined( 'NV_IS_USER' ) )
					{
						$sub_mess = array();
						$sub_mess[0] = array(
							'catid' => 1,
							'parentid' => 0,
							'title' => $lang_global['your_account'],
							'alias' => "",
							'link' => NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $modname . "&amp;" . NV_OP_VARIABLE . "=config" );
						$array_menu['submenu'] = nv_submenu_html_item( $sub_mess );
					}
				}
				else
				{
					$sub_nav_item = array();
					if( empty( $modvalues['funcs'] ) ) continue;
					foreach( $modvalues['funcs'] as $key => $sub_item )
					{
						if( $sub_item['in_submenu'] == 1 )
						{
							$sub_nav_item[] = array( "title" => $sub_item['func_custom_name'], "link" => NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $modname . "&amp;" . NV_OP_VARIABLE . "=" . $key );
						}
					}
					if( ! empty( $sub_nav_item ) )
					{
						$array_menu['submenu'] = "<ul>\n";
						foreach( $sub_nav_item as $item )
						{
							$array_menu['submenu'] .= "<li><a title=\"" . $item['title'] . "\" href=\"" . $item['link'] . "\">" . $item['title'] . "</a></li>\n";
						}
						$array_menu['submenu'] .= "</ul>\n";
					}
				}
				$xtpl->assign( 'TOP_MENU', $array_menu );
				$xtpl->parse( 'main.top_menu' );
			}
		}

		$xtpl->parse( 'main.news_cat' );
		$xtpl->parse( 'main' );
		return $xtpl->text( 'main' );
	}
}

if( defined( 'NV_SYSTEM' ) )
{
	$content = nv_smooth_navigational_menu( $block_config );
}

?>