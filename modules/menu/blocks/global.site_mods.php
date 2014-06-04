<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES ., JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Jan 17, 2011 11:34:27 AM
 */

if( ! defined( 'NV_MAINFILE' ) ) die( 'Stop!!!' );

if( ! nv_function_exists( 'nv_menu_site_mods' ) )
{
	function nv_menu_site_mods_config( $module, $data_block, $lang_block )
	{
		global $site_mods;

		$html = '<tr>';
		$html .= '	<td>&nbsp;</td>';
		$html .= '	<td>' . $lang_block['menu_note_auto'] . '</td>';
		$html .= '</tr>';
		$html .= "<tr>";
		$html .= "<td>";
		$html .= $lang_block['title_length'];
		$html .= "</td>";
		$html .= "<td>";
		$html .= "<input type=\"text\" class=\"form-control w100\" name=\"config_title_length\" value=\"" . $data_block['title_length'] . "\"/>";
		$html .= "</td>";
		$html .= "</tr>";
		$html .= '<tr><td>' . $lang_block['module_display'] . '</td><td>';

		$array_no_show = array( 'comment', 'menu' );
		foreach( $site_mods as $modname => $modvalues )
		{
			if( ! in_array( $modname, $array_no_show ) )
			{
				$checked = in_array( $modname, $data_block['module_in_menu'] ) ? ' checked="checked"' : '';
				$html .= '<div style="float: left" class="w150"><label style="text-align: left"><input type="checkbox" ' . $checked . ' value="' . $modname . '" name="module_in_menu[]">' . $modvalues['custom_title'] . '</label></div>';
			}
		}
		$html .= '</td></tr>';
		return $html;
	}

	function nv_menu_site_mods_submit( $module, $lang_block )
	{
		global $nv_Request;
		$return = array();
		$return['error'] = array();
		$return['config']['title_length'] = $nv_Request->get_int( 'config_title_length', 'post', 24 );
		$return['config']['module_in_menu'] = $nv_Request->get_typed_array( 'module_in_menu', 'post', 'string' );
		return $return;
	}

	/**
	 * nv_menu_site_mods()
	 *
	 * @param mixed $block_config
	 * @return
	 */
	function nv_menu_site_mods( $block_config )
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

		$xtpl = new XTemplate( 'global.bootstrap.tpl', NV_ROOTDIR . '/themes/' . $block_theme . '/modules/menu' );
		$xtpl->assign( 'LANG', $lang_global );
		$xtpl->assign( 'NV_BASE_SITEURL', NV_BASE_SITEURL );
		$xtpl->assign( 'BLOCK_THEME', $block_theme );
		$xtpl->assign( 'THEME_SITE_HREF', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA );

		foreach( $site_mods as $modname => $modvalues )
		{
			if( in_array( $modname, $block_config['module_in_menu'] ) AND ! empty( $modvalues['funcs'] ) )
			{
				$array_menu = array(
					'title' => $modvalues['custom_title'],
					'title_trim' => nv_clean60( $modvalues['custom_title'], $block_config['title_length'] ),
					'class' => $modname,
					'current' => array(),
					'link' => NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $modname
				);

				// Set current menu
				if( $modname == $module_name and empty( $home ) )
				{
					$array_menu['current'][] = "active";
				}

				// Get submenu
				if( ! empty( $modvalues['funcs'] ) )
				{
					$sub_nav_item = array();

					if( $modvalues['module_file'] == 'news' or $modvalues['module_file'] == 'weblinks' )
					{
						$db->sqlreset()
							->select( 'title, alias' )
							->from( NV_PREFIXLANG . '_' . $modvalues['module_data'] . '_cat' )
							->where( 'parentid=0 AND inhome=1' )
							->order( 'weight ASC' )
							->limit( 10 );
						$list = nv_db_cache( $db->sql(), '', $modname );
						foreach( $list as $l )
						{
							$sub_nav_item[] = array( 'title' => $l['title'], 'title_trim' => nv_clean60( $l['title'], $block_config['title_length']), 'link' => NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $modname . '&amp;' . NV_OP_VARIABLE . '=' . $l['alias'] );
						}
					}
					if( $modvalues['module_file'] == 'shops' )
					{
						$db->sqlreset()
							->select( NV_LANG_DATA . '_title as title, ' . NV_LANG_DATA . '_alias as alias' )
							->from( $db_config['prefix'] . '_' . $modvalues['module_data'] . '_catalogs' )
							->where( 'parentid=0 AND inhome=1' )
							->order( 'weight ASC' )
							->limit( 10 );
						$list = nv_db_cache( $db->sql(), '', $modname );
						foreach( $list as $l )
						{
							$sub_nav_item[] = array( 'title' => $l['title'], 'title_trim' => nv_clean60( $l['title'], $block_config['title_length']), 'link' => NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $modname . '&amp;' . NV_OP_VARIABLE . '=' . $l['alias'] );
						}
					}
					elseif( $modvalues['module_file'] == 'message' )
					{
						if( defined( 'NV_IS_USER' ) )
						{
							$sub_nav_item[] = array( 'title' => $lang_global['your_account'], 'title_trim' => nv_clean60( $lang_global['your_account'], $block_config['title_length']), 'link' => NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $modname . '&amp;' . NV_OP_VARIABLE . '=config' );
						}
					}
					elseif( $modvalues['module_file'] == 'download' or $modvalues['module_file'] == 'faq' or $modvalues['module_file'] == 'saas' )
					{
						$db->sqlreset()
							->select( 'title, alias' )
							->from( NV_PREFIXLANG . '_' . $modvalues['module_data'] . '_categories' )
							->where( 'parentid=0 AND status=1' )
							->order( 'weight ASC' )
							->limit( 10 );
						$list = nv_db_cache( $db->sql(), '', $modname );
						foreach( $list as $l )
						{
							$sub_nav_item[] = array( 'title' => $l['title'], 'title_trim' => nv_clean60( $l['title'], $block_config['title_length']), 'link' => NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $modname . '&amp;' . NV_OP_VARIABLE . '=' . $l['alias'] );
						}
					}
					elseif( $modname == 'users' )
					{
						foreach( $modvalues['funcs'] as $key => $sub_item )
						{
							if( $sub_item['in_submenu'] == 1 )
							{
								$sub_nav_item[] = array( 'title' => $sub_item['func_custom_name'], 'title_trim' => nv_clean60( $sub_item['func_custom_name'], $block_config['title_length']), 'link' => NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $modname . '&amp;' . NV_OP_VARIABLE . '=' . $key );
							}
						}
					}
					else
					{
						foreach( $modvalues['funcs'] as $key => $sub_item )
						{
							if( $sub_item['in_submenu'] == 1 )
							{
								$sub_nav_item[] = array(
									'title' => $sub_item['func_custom_name'],
									'link' => NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $modname . '&amp;' . NV_OP_VARIABLE . '=' . $key
								);
							}
						}
					}

					// Prase sub menu
					if( ! empty( $sub_nav_item ) )
					{
						$array_menu['current'][] = "dropdown";

						foreach( $sub_nav_item as $sub_nav )
						{
							$xtpl->assign( 'SUB', $sub_nav );
							$xtpl->parse( 'main.top_menu.sub.item' );
						}

						$xtpl->parse( 'main.top_menu.sub' );

						// Prase dropdown arrow
						$xtpl->parse( 'main.top_menu.has_sub' );
					}
				}

				$array_menu['current'] = empty( $array_menu['current'] ) ? "" : " class=\"" . ( implode( " ", $array_menu['current'] ) ) . "\"";

				$xtpl->assign( 'TOP_MENU', $array_menu );
				$xtpl->parse( 'main.top_menu' );
			}
		}

		// Assign init clock text
		$xtpl->assign( 'THEME_DIGCLOCK_TEXT', nv_date( 'H:i T l, d/m/Y', NV_CURRENTTIME ) );

		// Active home menu
		if( ! empty( $home ) )
		{
			$xtpl->parse( 'main.home_active' );
		}

		$xtpl->parse( 'main' );
		return $xtpl->text( 'main' );
	}
}

if( defined( 'NV_SYSTEM' ) )
{
	$content = nv_menu_site_mods( $block_config );
}