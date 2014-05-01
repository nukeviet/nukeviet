<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES ., JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Jan 17, 2011 11:34:27 AM
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

		if( file_exists( NV_ROOTDIR . '/themes/' . $global_config['site_theme'] . '/blocks/global.menu.tpl' ) )
		{
			$block_theme = $global_config['site_theme'];
		}
		else
		{
			$block_theme = 'default';
		}

		$xtpl = new XTemplate( 'global.menu.tpl', NV_ROOTDIR . '/themes/' . $block_theme . '/blocks' );
		$xtpl->assign( 'LANG', $lang_module );
		$xtpl->assign( 'NV_BASE_SITEURL', NV_BASE_SITEURL );
		$xtpl->assign( 'BLOCK_THEME', $block_theme );

		$catid = empty( $catid ) ? 1 : $catid;
		$array_cat_menu = array();
		if( $module_file == 'news' )
		{
			$sql = 'SELECT catid, parentid, title, alias FROM ' . NV_PREFIXLANG . '_' . $module_data . '_cat ORDER BY sort ASC';
			$list = nv_db_cache( $sql, 'catid', $module_name );
			foreach( $list as $l )
			{
				$l['link'] = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $l['alias'];
				$array_cat_menu[$l['catid']] = $l;
			}
		}
		elseif( $module_file == 'shops' )
		{
			$sql = 'SELECT catid, parentid, ' . NV_LANG_DATA . '_title as title, ' . NV_LANG_DATA . '_alias AS alias FROM ' . $db_config['prefix'] . '_' . $module_data . '_catalogs ORDER BY sort ASC';
			$list = nv_db_cache( $sql, 'catid', $module_name );
			foreach( $list as $l )
			{
				$l['link'] = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $l['alias'];
				$array_cat_menu[$l['catid']] = $l;
			}
		}
		elseif( $module_file == 'message' )
		{
			if( defined( 'NV_IS_USER' ) )
			{
				$array_cat_menu[1] = array(
					'catid' => 1,
					'parentid' => 0,
					'title' => $lang_global['your_account'],
					'alias' => '',
					'link' => NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=config'
				);
			}
		}
		elseif( $module_file == 'weblinks' )
		{
			$sql = 'SELECT catid, parentid, title, alias FROM ' . NV_PREFIXLANG . '_' . $module_data . '_cat ORDER BY parentid ASC, weight ASC';
			$list = nv_db_cache( $sql, 'catid', $module_name );
			foreach( $list as $l )
			{
				$l['link'] = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $l['alias'];
				$array_cat_menu[$l['catid']] = $l;
			}
		}
		elseif( $module_file == 'download' or $module_file == 'faq' or $module_file == 'saas')
		{
			$sql = 'SELECT id, parentid, title, alias FROM ' . NV_PREFIXLANG . '_' . $module_data . '_categories ORDER BY weight ASC';
			$list = nv_db_cache( $sql, 'id', $module_name );
			foreach( $list as $l )
			{
				$l['link'] = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $l['alias'];
				$l['catid'] = $l['id'];
				$array_cat_menu[$l['id']] = $l;
			}
		}
		else
		{
			foreach( $module_info['funcs'] as $key => $sub_item )
			{
				if( $key == 'main' ) continue;

				$array_cat_menu[] = array(
					'catid' => ( $op == $key ) ? 1 : 0,
					'parentid' => 1,
					'title' => $sub_item['func_custom_name'],
					'alias' => '',
					'link' => '' . NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $key
				);
			}

			if( ! empty( $array_cat_menu ) )
			{
				$array_cat_menu[] = array(
					'catid' => 1,
					'parentid' => 0,
					'title' => $module_info['custom_title'],
					'alias' => '',
					'link' => '' . NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name
				);
			}
		}

		if( $module_name != 'news' and empty( $array_cat_menu ) and isset( $site_mods['news'] ) )
		{
			$sql = 'SELECT catid, parentid, title, alias FROM ' . NV_PREFIXLANG . '_news_cat ORDER BY sort ASC';
			$list = nv_db_cache( $sql, 'catid', 'news' );

			foreach( $list as $l )
			{
				$l['link'] = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=news&amp;' . NV_OP_VARIABLE . '=' . $l['alias'];
				$array_cat_menu[$l['catid']] = $l;
			}
		}

		// Process cat module
		$i = 1;
		foreach( $array_cat_menu as $catvalue )
		{
			if( ! empty( $catvalue['catid'] ) and empty( $catvalue['parentid'] ) )
			{
				$check_cat = isset( $array_cat_menu[$catid]['parentid'] ) ? $array_cat_menu[$catid]['parentid'] : 0;

				if( ( $catvalue['catid'] == $catid ) || ( $check_cat == $catvalue['catid'] ) || ( empty( $catid ) and $i == 1 ) )
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