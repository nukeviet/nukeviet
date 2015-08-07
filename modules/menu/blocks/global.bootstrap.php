<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES ., JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Jan 17, 2011 11:34:27 AM
 */

if( ! defined( 'NV_MAINFILE' ) ) die( 'Stop!!!' );

if( defined( 'NV_IS_FILE_THEMES' ) )
{
	// include config theme
	require NV_ROOTDIR . '/modules/menu/menu_config.php';
}

if( ! nv_function_exists( 'nv_menu_bootstrap' ) )
{
	/**
	 * nv_menu_bootstrap_check_current()
	 *
	 * @param mixed $url
	 * @param integer $type
	 * @return
	 *
	 */
	function nv_menu_bootstrap_check_current( $url, $type = 0 )
	{
		global $module_name, $home, $client_info, $global_config;

		// Chinh xac tuyet doi
		if( $client_info['selfurl'] == $url ) return true;

		if( $home and ( $url == nv_url_rewrite( NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA ) or $url == NV_BASE_SITEURL . 'index.php' or $url == NV_BASE_SITEURL ) )
		{
			return true;
		}
		elseif( $url != NV_BASE_SITEURL )
		{
			$_curr_url = NV_BASE_SITEURL . str_replace( $global_config['site_url'] . '/', '', $client_info['selfurl'] );
			if( $type == 2 )
			{
				if( preg_match( '#' . preg_quote( $url, '#' ) . '#', $_curr_url ) ) return true;
			}
			elseif( $type == 1 )
			{
				if( preg_match( '#^' . preg_quote( $url, '#' ) . '#', $_curr_url ) ) return true;
			}
			elseif( $_curr_url == $url )
			{
				return true;
			}
		}
		return false;
	}

	/**
	 * nv_menu_bootstrap()
	 *
	 * @param mixed $block_config
	 * @return
	 *
	 */
	function nv_menu_bootstrap( $block_config )
	{
		global $db, $db_config, $global_config, $site_mods, $module_info, $module_name, $module_file, $module_data, $lang_global, $catid, $home;

		if( file_exists( NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/menu/global.bootstrap.tpl' ) )
		{
			$block_theme = $global_config['module_theme'];
		}
		elseif( file_exists( NV_ROOTDIR . '/themes/' . $global_config['site_theme'] . '/modules/menu/global.bootstrap.tpl' ) )
		{
			$block_theme = $global_config['site_theme'];
		}
		else
		{
			$block_theme = 'default';
		}

		$array_menu = array();
		$sql = 'SELECT id, parentid, title, link, icon, note, subitem, groups_view, module_name, op, target, css, active_type FROM ' . NV_PREFIXLANG . '_menu_rows WHERE status=1 AND mid = ' . $block_config['menuid'] . ' ORDER BY weight ASC';
		$list = nv_db_cache( $sql, '', $block_config['module'] );
		foreach( $list as $row )
		{
			if( nv_user_in_groups( $row['groups_view'] ) )
			{
				switch( $row['target'] )
				{
					case 1:
						$row['target'] = '';
						break;
					case 3:
						$row['target'] = ' onclick="window.open(this.href,\'targetWindow\',\'toolbar=no,location=no,status=no,menubar=no,scrollbars=yes,resizable=yes,\');return false;"';
						break;
					default:
						$row['target'] = ' onclick="this.target=\'_blank\'"';
				}

				$array_menu[$row['parentid']][$row['id']] = array(
					'id' => $row['id'],
					'title' => $row['title'],
					'title_trim' => nv_clean60( $row['title'], $block_config['title_length'] ),
					'target' => $row['target'],
					'note' => empty( $row['note'] ) ? $row['title'] : $row['note'],
					'link' => nv_url_rewrite( nv_unhtmlspecialchars( $row['link'] ), true ),
					'icon' => ( empty( $row['icon'] ) ) ? '' : NV_BASE_SITEURL . NV_UPLOADS_DIR . '/menu/' . $row['icon'],
					'css' => $row['css'],
					'active_type' => $row['active_type']
				);
			}
		}

		$xtpl = new XTemplate( 'global.bootstrap.tpl', NV_ROOTDIR . '/themes/' . $block_theme . '/modules/menu' );
		$xtpl->assign( 'LANG', $lang_global );
		$xtpl->assign( 'NV_BASE_SITEURL', NV_BASE_SITEURL );
		$xtpl->assign( 'BLOCK_THEME', $block_theme );
		$xtpl->assign( 'THEME_SITE_HREF', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA );

		foreach( $array_menu[0] as $id => $item )
		{
			$classcurrent = array();
			$submenu_active = array();
			if( isset( $array_menu[$id] ) )
			{
				$classcurrent[] = 'dropdown';
				$submenu = nv_get_bootstrap_submenu( $id, $array_menu, $submenu_active, $block_theme );
				$xtpl->assign( 'SUB', $submenu );
				$xtpl->parse( 'main.top_menu.sub' );
				$xtpl->parse( 'main.top_menu.has_sub' );
			}
			if( nv_menu_bootstrap_check_current( $item['link'], $item['active_type'] ) )
			{
				$classcurrent[] = 'active';
			}
			elseif( ! empty( $submenu_active ) )
			{
				$classcurrent[] = 'active';
			}
			if( ! empty( $item['class'] ) )
			{
				$classcurrent[] = $item['class'];
			}
			$item['current'] = empty( $classcurrent ) ? '' : ' class="' . ( implode( ' ', $classcurrent ) ) . '"';

			if( nv_menu_bootstrap_check_current( $item['link'], $item['active_type'] ) )
			{
				$classcurrent[] = 'active';
			}

			$xtpl->assign( 'TOP_MENU', $item );
			if( ! empty( $item['icon'] ) )
			{
				$xtpl->parse( 'main.top_menu.icon' );
			}
			$xtpl->parse( 'main.top_menu' );
		}
		$xtpl->parse( 'main' );
		return $xtpl->text( 'main' );
	}

	/**
	 * @param int $id
	 * @param array $array_menu
	 * @param bool $submenu_active
	 * @param string $block_theme
	 * @return string
	 */
	function nv_get_bootstrap_submenu( $id, $array_menu, &$submenu_active, $block_theme )
	{
		$xtpl = new XTemplate( 'global.bootstrap.tpl', NV_ROOTDIR . '/themes/' . $block_theme . '/modules/menu' );

		if( ! empty( $array_menu[$id] ) )
		{
			foreach( $array_menu[$id] as $sid => $smenu )
			{
				if( nv_menu_bootstrap_check_current( $smenu['link'], $smenu['active_type'] ) )
				{
					$submenu_active[] = $id;
				}
				$submenu = '';
				if( isset( $array_menu[$sid] ) )
				{
					$submenu = nv_get_bootstrap_submenu( $sid, $array_menu, $submenu_active, $block_theme );
					$xtpl->assign( 'SUB', $submenu );
					$xtpl->parse( 'submenu.loop.item' );
				}
				$xtpl->assign( 'SUBMENU', $smenu );
				if( ! empty( $submenu ) )
				{
					$xtpl->parse( 'submenu.loop.submenu' );
				}
				if( ! empty( $smenu['icon'] ) )
				{
					$xtpl->parse( 'submenu.loop.icon' );
				}
				$xtpl->parse( 'submenu.loop' );
			}
			$xtpl->parse( 'submenu' );
		}
		return $xtpl->text( 'submenu' );
	}
}

if( defined( 'NV_SYSTEM' ) )
{
	$content = nv_menu_bootstrap( $block_config );
}