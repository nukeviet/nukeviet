<?php

/**
 * @Project NUKEVIET 3.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2012 VINADES ., JSC. All rights reserved
 * @Createdate Jan 17, 2011  11:34:27 AM
 */

if( ! defined( 'NV_MAINFILE' ) ) die( 'Stop!!!' );

if( ! nv_function_exists( 'nv_menu_theme_modern' ) )
{
	/**
	 * nv_menu_theme_modern()
	 * 
	 * @param mixed $block_config
	 * @return
	 */
	function nv_menu_theme_modern( $block_config )
	{
		global $db, $db_config, $global_config, $site_mods, $module_info, $module_name, $module_file, $module_data, $op, $lang_module, $catid, $lang_global;

		if( file_exists( NV_ROOTDIR . "/themes/" . $global_config['site_theme'] . "/modules/menu/menu_theme_modern.tpl" ) )
		{
			$block_theme = $global_config['site_theme'];
		}
		else
		{
			$block_theme = "default";
		}

		$xtpl = new XTemplate( "menu_theme_modern.tpl", NV_ROOTDIR . "/themes/" . $block_theme . "/modules/menu" );
		$xtpl->assign( 'LANG', $lang_module );
		$xtpl->assign( 'NV_BASE_SITEURL', NV_BASE_SITEURL );
		$xtpl->assign( 'BLOCK_THEME', $block_theme );

		$catid = empty( $catid ) ? 1 : $catid;
		$array_cat_menu = array();
		if( $module_name == 'users' )
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
			$modvalues = $site_mods['users'];

			$array_cat_menu[] = array(
				"catid" => 1,
				"parentid" => 0,
				"title" => $modvalues['custom_title'],
				"alias" => '',
				"link" => "" . NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=users" );

			foreach( $modvalues['funcs'] as $key => $sub_item )
			{
				if( $sub_item['in_submenu'] == 1 and in_array( $key, $in_submenu_users ) )
				{
					$array_cat_menu[] = array(
						"catid" => 1,
						"parentid" => 1,
						"title" => $sub_item['func_custom_name'],
						"alias" => '',
						"link" => "" . NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=users&amp;" . NV_OP_VARIABLE . "=" . $key );
				}
			}
		}
		elseif( $module_file == "news" )
		{
			$sql = "SELECT catid, parentid, title, alias FROM `" . NV_PREFIXLANG . "_" . $module_data . "_cat` ORDER BY `order` ASC";
			$result = $db->sql_query( $sql );
			while( list( $catid_i, $parentid_i, $title_i, $alias_i ) = $db->sql_fetchrow( $result ) )
			{
				$link_i = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $alias_i;
				$array_cat_menu[$catid_i] = array(
					"catid" => $catid_i,
					"parentid" => $parentid_i,
					"title" => $title_i,
					"alias" => $alias_i,
					"link" => $link_i );
			}
		}
		elseif( $module_file == "shops" )
		{
			$sql = "SELECT catid, parentid, " . NV_LANG_DATA . "_title, " . NV_LANG_DATA . "_alias FROM `" . $db_config['prefix'] . "_" . $module_data . "_catalogs` ORDER BY `order` ASC";
			$result = $db->sql_query( $sql );
			while( list( $catid_i, $parentid_i, $title_i, $alias_i ) = $db->sql_fetchrow( $result ) )
			{
				$link_i = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $alias_i;
				$array_cat_menu[$catid_i] = array(
					"catid" => $catid_i,
					"parentid" => $parentid_i,
					"title" => $title_i,
					"alias" => $alias_i,
					"link" => $link_i );
			}
		}
		elseif( $module_file == "message" )
		{
			if( defined( 'NV_IS_USER' ) )
			{
				$array_cat_menu[1] = array(
					'catid' => 1,
					'parentid' => 0,
					'title' => $lang_global['your_account'],
					'alias' => "",
					'link' => NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=config" );
			}
		}
		elseif( $module_file == "weblinks" )
		{
			$sql = "SELECT catid, parentid, title, alias FROM `" . NV_PREFIXLANG . "_" . $module_data . "_cat` ORDER BY `parentid` ASC, `weight` ASC";
			$result = $db->sql_query( $sql );
			while( list( $catid_i, $parentid_i, $title_i, $alias_i ) = $db->sql_fetchrow( $result ) )
			{
				$link_i = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $alias_i;
				$array_cat_menu[$catid_i] = array(
					"catid" => $catid_i,
					"parentid" => $parentid_i,
					"title" => $title_i,
					"alias" => $alias_i,
					"link" => $link_i );
			}
		}
		elseif( $module_file == "download" )
		{
			$sql = "SELECT id, parentid, title, alias FROM `" . NV_PREFIXLANG . "_" . $module_data . "_categories` ORDER BY `weight` ASC";
			$result = $db->sql_query( $sql );
			while( list( $catid_i, $parentid_i, $title_i, $alias_i ) = $db->sql_fetchrow( $result ) )
			{
				$link_i = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $alias_i;
				$array_cat_menu[$catid_i] = array(
					"catid" => $catid_i,
					"parentid" => $parentid_i,
					"title" => $title_i,
					"alias" => $alias_i,
					"link" => $link_i );
			}
		}
		else
		{
			foreach( $module_info['funcs'] as $key => $sub_item )
			{
				if( $sub_item['in_submenu'] == 1 )
				{
					$array_cat_menu[] = array(
						"catid" => ( $op == $key ) ? 1 : 0,
						"parentid" => 1,
						"title" => $sub_item['func_custom_name'],
						"alias" => '',
						"link" => "" . NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $key );
				}
			}
			if( ! empty( $array_cat_menu ) )
			{
				$array_cat_menu[] = array(
					"catid" => 1,
					"parentid" => 0,
					"title" => $module_info['custom_title'],
					"alias" => '',
					"link" => "" . NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name );
			}
		}
		if( $module_name != "news" and empty( $array_cat_menu ) )
		{
			$sql = "SELECT catid, parentid, title, alias FROM `" . NV_PREFIXLANG . "_news_cat` ORDER BY `order` ASC";
			$result = $db->sql_query( $sql );
			while( list( $catid_i, $parentid_i, $title_i, $alias_i ) = $db->sql_fetchrow( $result ) )
			{
				$link_i = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=news&amp;" . NV_OP_VARIABLE . "=" . $alias_i;
				$array_cat_menu[$catid_i] = array(
					"catid" => $catid_i,
					"parentid" => $parentid_i,
					"title" => $title_i,
					"alias" => $alias_i,
					"link" => $link_i );
			}
		}

		// Process cat module
		$i = 1;
		foreach( $array_cat_menu as $catvalue )
		{
			if( ! empty( $catvalue['catid'] ) && empty( $catvalue['parentid'] ) )
			{
				$check_cat = isset( $array_cat_menu[$catid]['parentid'] ) ? $array_cat_menu[$catid]['parentid'] : 0;
				if( ( $catvalue['catid'] == $catid ) || ( $check_cat == $catvalue['catid'] ) || ( empty( $catid ) && $i == 1 ) )
				{
					$catvalue['current'] = 'class="current"';
					$i = 0;
				}
				$xtpl->assign( 'mainloop', $catvalue );
				foreach( $array_cat_menu as $subcatvalue )
				{
					if( $subcatvalue['parentid'] == $catvalue['catid'] )
					{
						$subcatvalue['current'] = ( $subcatvalue['catid'] == $catid ) ? 'class="current"' : '';
						$xtpl->assign( 'loop', $subcatvalue );
						$xtpl->parse( 'main.news_cat.mainloop.sub.loop' );
					}
					else
					{
						$xtpl->parse( 'main.news_cat.mainloop.sub.null' );
					}
				}
				$xtpl->parse( 'main.news_cat.mainloop.sub' );
				$xtpl->parse( 'main.news_cat.mainloop' );
			}
		}
		$xtpl->parse( 'main.news_cat' );
		$xtpl->parse( 'main' );
		return $xtpl->text( 'main' );
	}
}

if( defined( 'NV_SYSTEM' ) )
{
	$content = nv_menu_theme_modern( $block_config );
}

?>