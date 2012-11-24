<?php

/**
 * @Project NUKEVIET 3.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2012 VINADES ., JSC. All rights reserved
 * @Createdate Jan 17, 2011  11:34:27 AM
 */

if( ! defined( 'NV_MAINFILE' ) ) die( 'Stop!!!' );

if( ! nv_function_exists( 'nv_menu_theme_default' ) )
{
	/**
	 * nv_menu_theme_default()
	 * 
	 * @param mixed $block_config
	 * @return
	 */
	function nv_menu_theme_default( $block_config )
	{
		global $db, $db_config, $global_config, $site_mods, $module_info, $module_name, $module_file, $module_data, $lang_global, $catid;
		if( file_exists( NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/menu/menu_theme_default.tpl" ) )
		{
			$block_theme = $global_config['module_theme'];
		}
		elseif( file_exists( NV_ROOTDIR . "/themes/" . $global_config['site_theme'] . "/modules/menu/menu_theme_default.tpl" ) )
		{
			$block_theme = $global_config['site_theme'];
		}
		else
		{
			$block_theme = "default";
		}

		$xtpl = new XTemplate( "menu_theme_default.tpl", NV_ROOTDIR . "/themes/" . $block_theme . "/modules/menu" );
		$xtpl->assign( 'LANG', $lang_global );
		$xtpl->assign( 'NV_BASE_SITEURL', NV_BASE_SITEURL );
		$xtpl->assign( 'BLOCK_THEME', $block_theme );
		$xtpl->assign( 'THEME_SITE_HREF', NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA );
		$xtpl->assign( 'THEME_RSS_INDEX_HREF', NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=rss" );

		foreach( $site_mods as $modname => $modvalues )
		{
			if( ! empty( $modvalues['in_menu'] ) and ! empty( $modvalues['funcs'] ) )
			{
				$module_current = ( $modname == $module_name ) ? ' class="current"' : '';
				$aryay_menu = array(
					"title" => $modvalues['custom_title'],
					"class" => $modname,
					"current" => $module_current,
					"link" => NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $modname );
				if( ! empty( $modvalues['funcs'] ) )
				{
					$sub_nav_item = array();

					if( $modvalues['module_file'] == "news" or $modvalues['module_file'] == "weblinks" )
					{
						$result2 = "SELECT `title`, `alias` FROM `" . NV_PREFIXLANG . "_" . $modvalues['module_data'] . "_cat` WHERE `parentid`='0' AND `inhome`='1' ORDER BY `weight` ASC LIMIT 0,10";
						$list = nv_db_cache( $result2, '', $modname );
						foreach( $list as $l )
						{
							$sub_nav_item[] = array( 'title' => $l['title'], 'link' => NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $modname . "&amp;" . NV_OP_VARIABLE . "=" . $l['alias'] );
						}
					}
					if( $modvalues['module_file'] == "shops" )
					{
						$result2 = "SELECT " . NV_LANG_DATA . "_title as title, " . NV_LANG_DATA . "_alias as alias FROM `" . $db_config['prefix'] . "_" . $modvalues['module_data'] . "_catalogs` WHERE `parentid`='0' AND `inhome`='1' ORDER BY `weight` ASC LIMIT 0,10";
						$list = nv_db_cache( $result2, '', $modname );
						foreach( $list as $l )
						{
							$sub_nav_item[] = array( 'title' => $l['title'], 'link' => NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $modname . "&amp;" . NV_OP_VARIABLE . "=" . $l['alias'] );
						}
					}
					elseif( $modvalues['module_file'] == "message" )
					{
						if( defined( 'NV_IS_USER' ) )
						{
							$sub_nav_item[] = array( 'title' => $lang_global['your_account'], 'link' => NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $modname . "&amp;" . NV_OP_VARIABLE . "=config" );
						}
					}
					elseif( $modvalues['module_file'] == "download" )
					{
						$result2 = "SELECT `title`, `alias` FROM `" . NV_PREFIXLANG . "_" . $modvalues['module_data'] . "_categories` WHERE `parentid`='0' AND `status`='1'ORDER BY `weight` ASC LIMIT 0,10";
						$list = nv_db_cache( $result2, '', $modname );
						foreach( $list as $l )
						{
							$sub_nav_item[] = array( 'title' => $l['title'], 'link' => NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $modname . "&amp;" . NV_OP_VARIABLE . "=" . $l['alias'] );
						}
					}
					elseif( $modname == "users" )
					{
						if( defined( 'NV_IS_USER' ) )
						{
							$in_submenu_users = array();
							$in_submenu_users[] = "changepass";
                            $in_submenu_users[] = "memberlist";
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
								"lostpass",
								"memberlist" );
						}
						foreach( $modvalues['funcs'] as $key => $sub_item )
						{
							if( $sub_item['in_submenu'] == 1 and in_array( $key, $in_submenu_users ) )
							{
								$sub_nav_item[] = array( "title" => $sub_item['func_custom_name'], "link" => NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $modname . "&amp;" . NV_OP_VARIABLE . "=" . $key );
							}
						}
					}
					else
					{
						foreach( $modvalues['funcs'] as $key => $sub_item )
						{
							if( $sub_item['in_submenu'] == 1 )
							{
								$sub_nav_item[] = array( "title" => $sub_item['func_custom_name'], "link" => NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $modname . "&amp;" . NV_OP_VARIABLE . "=" . $key );
							}
						}
					}
					if( ! empty( $sub_nav_item ) )
					{
						foreach( $sub_nav_item as $sub_nav )
						{
							$xtpl->assign( 'SUB', $sub_nav );
							$xtpl->parse( 'main.top_menu.sub.item' );
						}
						$xtpl->parse( 'main.top_menu.sub' );
					}
				}
				$xtpl->assign( 'TOP_MENU', $aryay_menu );
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
	$content = nv_menu_theme_default( $block_config );
}

?>