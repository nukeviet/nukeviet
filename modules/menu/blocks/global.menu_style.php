<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 21-04-2011 11:17
 */

if( ! defined( 'NV_MAINFILE' ) ) die( 'Stop!!!' );

/**
 * type1 = Nav_bar
 * type2 = Vertical
 * type3 = Treeview
*/

if( ! nv_function_exists( 'nv_menu_site' ) )
{

	/**
	 * nv_block_config_menu()
	 *
	 * @param mixed $module
	 * @param mixed $data_block
	 * @param mixed $lang_block
	 * @return
	 */
	function nv_block_config_menu( $module, $data_block, $lang_block )
	{
		$html = '';
		$html .= "<tr>";
		$html .= "	<td>" . $lang_block['menu'] . "</td>";
		$html .= "	<td><select name=\"menuid\">\n";

		$sql = "SELECT * FROM " . NV_PREFIXLANG . "_" . $module . " ORDER BY id DESC";
		$list = nv_db_cache( $sql, 'id', $module );
		foreach( $list as $l )
		{
			$sel = ( $data_block['menuid'] == $l['id'] ) ? ' selected' : '';
			$html .= "<option value=\"" . $l['id'] . "\" " . $sel . ">" . $l['title'] . "</option>\n";
		}

		$html .= "	</select></td>\n";
		$html .= "</tr>";
		$html .= "<tr>";
		$html .= "	<td>" . $lang_block['type'] . "</td>";
		$html .= "	<td><select name=\"type\">\n";
		$sel = ( $data_block['type'] == 1 ) ? ' selected' : '';
		$html .= "<option value=\"1\" " . $sel . ">" . $lang_block['m_type1'] . "</option>\n";
		$sel = ( $data_block['type'] == 2 ) ? ' selected' : '';
		$html .= "<option value=\"2\" " . $sel . ">" . $lang_block['m_type2'] . "</option>\n";
		$sel = ( $data_block['type'] == 3 ) ? ' selected' : '';
		$html .= "<option value=\"3\" " . $sel . ">" . $lang_block['m_type3'] . "</option>\n";
		$html .= "	</select></td>\n";
		$html .= "</tr>";
		$html .= "<tr>";
		$html .= "<td>";
		$html .= $lang_block['is_viewdes'];
		$html .= "</td>";
		$html .= "<td>";
		$html .= "<input type=\"checkbox\" name=\"config_is_viewdes\" value=\"1\"" . ( ! empty( $data_block['is_viewdes'] ) ? ' checked="checked"' : '' ) . "/> " . $lang_block['is_viewdes_1'];
		$html .= "</td>";
		$html .= "</tr>";
		$html .= "<tr>";
		$html .= "<td>";
		$html .= $lang_block['title_length'];
		$html .= "</td>";
		$html .= "<td>";
		$html .= "<input type=\"text\" name=\"config_title_length\" value=\"" . $data_block['title_length'] . "\"/>";
		$html .= "</td>";
		$html .= "</tr>";

		return $html;
	}

	/**
	 * nv_block_config_menu_submit()
	 *
	 * @param mixed $module
	 * @param mixed $lang_block
	 * @return
	 */
	function nv_block_config_menu_submit( $module, $lang_block )
	{
		global $nv_Request;
		$return = array();
		$return['error'] = array();
		$return['config'] = array();
		$return['config']['type'] = $nv_Request->get_int( 'type', 'post', 0 );
		$return['config']['menuid'] = $nv_Request->get_int( 'menuid', 'post', 0 );
		$return['config']['is_viewdes'] = $nv_Request->get_int( 'config_is_viewdes', 'post', 0 );
		$return['config']['title_length'] = $nv_Request->get_int( 'config_title_length', 'post', 24 );
		return $return;
	}

	/**
	 * nv_bmenu_check_currit()
	 *
	 * @param mixed $url
	 * @param integer $type
	 * @return
	 */
	function nv_bmenu_check_currit( $url, $type = 0 )
	{
		global $module_name, $home, $client_info, $global_config;

		$url = nv_unhtmlspecialchars( $url );

		if( $client_info['selfurl'] == $url ) return true;
		// Chinh xac tuyet doi

		$_curr_url = NV_BASE_SITEURL . str_replace( $global_config['site_url'] . '/', '', $client_info['selfurl'] );
		$_url = nv_url_rewrite( $url, true );

		if( $home and ( $_url == nv_url_rewrite( NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA ) or $_url == NV_BASE_SITEURL . "index.php" or $_url == NV_BASE_SITEURL ) )
		{
			return true;
		}
		elseif( $type == 2 )
		{
			if( preg_match( '#' . preg_quote( $_url, '#' ) . '#', $_curr_url ) ) return true;
			return false;
		}
		elseif( $type == 1 )
		{
			if( preg_match( '#^' . preg_quote( $_url, '#' ) . '#', $_curr_url ) ) return true;
			return false;
		}
		elseif( $_curr_url == $_url )
		{
			return true;
		}

		return false;
	}

	/**
	 * nv_bmenu_active_menu()
	 *
	 * @param mixed $cat
	 * @return
	 */
	function nv_bmenu_active_menu( $cat )
	{
		if( $cat['current'] === true and ! $cat['html_class'] )
		{
			$class = ' class="current"';
		}
		elseif( $cat['current'] === false and $cat['html_class'] )
		{
			$class = ' class="' . $cat['html_class'] . '"';
		}
		elseif( $cat['current'] === true and $cat['html_class'] )
		{
			$class = ' class="' . $cat['html_class'] . ' current"';
		}
		else
		{
			$class = '';
		}

		return $class;
	}

	// Ham xu ly chinh cho block
	/**
	 * nv_menu_site()
	 *
	 * @param mixed $block_config
	 * @return
	 */
	function nv_menu_site( $block_config )
	{
		global $db;

		$list_cats = array();
		$sql = "SELECT id, parentid, title, link, note, subitem, who_view, groups_view, module_name, op, target, css, active_type FROM " . NV_PREFIXLANG . "_menu_rows WHERE status=1 AND mid = " . $block_config['menuid'] . " ORDER BY weight ASC";
		$list = nv_db_cache( $sql, '', 'menu' );

		foreach( $list as $row )
		{
			if( nv_set_allow( $row['who_view'], $row['groups_view'] ) )
			{
				switch( $row['target'] )
				{
					case 1:
						$row['target'] = '';
						break;
					default:
						$row['target'] = ' onclick="this.target=\'_blank\'"';
				}

				$list_cats[$row['id']] = array(
					'id' => $row['id'],
					'parentid' => $row['parentid'],
					'subcats' => $row['subitem'],
					'title' => nv_clean60( $row['title'], $block_config['title_length'] ),
					'target' => $row['target'],
					'note' => ( $block_config['is_viewdes'] and $row['note'] ) ? $row['note'] : $row['title'],
					'link' => nv_url_rewrite( nv_unhtmlspecialchars( $row['link'] ), true ),
					'html_class' => $row['css'],
					'current' => nv_bmenu_check_currit( $row['link'], ( int )$row['active_type'] )
				);
			}
		}

		if( $block_config['type'] == 1 )
		{
			$style = 'nav_bar';
			return nv_style_type( $style, $list_cats, $block_config );
		}
		elseif( $block_config['type'] == 2 )
		{
			$style = 'vertical';
			return nv_style_type( $style, $list_cats, $block_config );
		}
		else
		{
			$style = 'treeview';
			return nv_style_type( $style, $list_cats, $block_config );
		}
	}

	/**
	 * nv_style_type()
	 *
	 * @param mixed $style
	 * @param mixed $list_cats
	 * @param mixed $block_config
	 * @return
	 */
	function nv_style_type( $style, $list_cats, $block_config )
	{
		global $module_info, $my_head;
		
		$my_head .= "<link rel=\"stylesheet\" type=\"text/css\"	href=\"" . NV_BASE_SITEURL . "/themes/" . $module_info['template'] . "/css/menu.css\" />";

		if( file_exists( NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/menu/' . $style . '.tpl' ) )
		{
			$block_theme = $module_info['template'];
		}
		else
		{
			$block_theme = "default";
		}

		$xtpl = new XTemplate( $style . '.tpl', NV_ROOTDIR . '/themes/' . $block_theme . '/modules/menu' );
		$xtpl->assign( 'BLOCK_THEME', $block_theme );
		$xtpl->assign( 'NV_BASE_SITEURL', NV_BASE_SITEURL );

		foreach( $list_cats as $cat )
		{
			if( empty( $cat['parentid'] ) )
			{
				$cat['class'] = nv_bmenu_active_menu( $cat );

				$xtpl->assign( 'CAT1', $cat );
				if( ! empty( $cat['subcats'] ) )
				{
					$html_content = nv_sub_menu( $style, $list_cats, $cat['subcats'] );
					$xtpl->assign( 'HTML_CONTENT', $html_content );
					$xtpl->parse( 'main.loopcat1.cat2' );
					$xtpl->parse( 'main.loopcat1.expand' );
				}
				$xtpl->parse( 'main.loopcat1' );
			}
		}
		$xtpl->assign( 'MENUID', $block_config['bid'] );
		$xtpl->parse( 'main' );

		return $xtpl->text( 'main' );
	}

	// Hien thi menu con
	/**
	 * nv_sub_menu()
	 *
	 * @param mixed $style
	 * @param mixed $list_cats
	 * @param mixed $list_sub
	 * @return
	 */
	function nv_sub_menu( $style, $list_cats, $list_sub )
	{
		global $module_info;

		if( file_exists( NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/menu/' . $style . '.tpl' ) )
		{
			$block_theme = $module_info['template'];
		}
		else
		{
			$block_theme = "default";
		}

		$xtpl = new XTemplate( $style . '.tpl', NV_ROOTDIR . '/themes/' . $block_theme . '/modules/menu' );

		if( empty( $list_sub ) )
		{
			return "";
		}
		else
		{
			$list = explode( ',', $list_sub );

			foreach( $list_cats as $cat )
			{
				$catid = $cat['id'];
				if( in_array( $catid, $list ) )
				{
					$list_cats[$catid]['class'] = nv_bmenu_active_menu( $list_cats[$catid] );

					$xtpl->assign( 'MENUTREE', $list_cats[$catid] );

					if( ! empty( $list_cats[$catid]['subcats'] ) )
					{
						$tree = nv_sub_menu( $style, $list_cats, $list_cats[$catid]['subcats'] );

						$xtpl->assign( 'TREE_CONTENT', $tree );
						$xtpl->parse( 'tree.tree_content' );
					}

					$xtpl->parse( 'tree' );
				}
			}

			return $xtpl->text( 'tree' );
		}
	}
}

if( defined( 'NV_SYSTEM' ) )
{
	$content = nv_menu_site( $block_config );
}