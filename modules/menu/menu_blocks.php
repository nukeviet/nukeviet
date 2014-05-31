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
 * nv_menu_blocks()
 *
 * Ham xu ly chinh cho block
 *
 * @param mixed $block_config
 * @return
 */
function nv_menu_blocks( $block_config )
{
	global $db, $global_config, $my_head;

	$list_cats = array();
	$sql = 'SELECT id, parentid, title, link, note, subitem, groups_view, module_name, op, target, css, active_type FROM ' . NV_PREFIXLANG . '_menu_rows WHERE status=1 AND mid = ' . $block_config['menuid'] . ' ORDER BY weight ASC';
	$list = nv_db_cache( $sql, '', 'menu' );

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

			$list_cats[$row['id']] = array(
				'id' => $row['id'],
				'parentid' => $row['parentid'],
				'subcats' => $row['subitem'],
				'title' => $row['title'],
				'title_trim' => nv_clean60( $row['title'], $block_config['title_length'] ),
				'target' => $row['target'],
				'note' => empty( $row['note'] ) ? $row['title'] :  $row['note'],
				'link' => nv_url_rewrite( nv_unhtmlspecialchars( $row['link'] ), true ),
				'html_class' => $row['css'],
				'current' => nv_menu_check_current( $row['link'], ( int )$row['active_type'] )
			);
		}
	}

	if( file_exists( NV_ROOTDIR . '/themes/' . $global_config['site_theme'] . '/modules/menu/' . $block_config['block_name'] . '.tpl' ) )
	{
		$block_theme = $global_config['site_theme'];
	}
	else
	{
		$block_theme = 'default';
	}

	$xtpl = new XTemplate( $block_config['block_name'] . '.tpl', NV_ROOTDIR . '/themes/' . $block_theme . '/modules/menu' );
	$xtpl->assign( 'BLOCK_THEME', $block_theme );
	$xtpl->assign( 'NV_BASE_SITEURL', NV_BASE_SITEURL );

	foreach( $list_cats as $cat )
	{
		if( empty( $cat['parentid'] ) )
		{
			$cat['class'] = nv_menu_blocks_active( $cat );

			$xtpl->assign( 'CAT1', $cat );
			if( ! empty( $cat['subcats'] ) )
			{
				$html_content = nv_smenu_blocks( $block_config['block_name'], $list_cats, $cat['subcats'] );
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

/**
 * nv_menu_blocks_active()
 *
 * @param mixed $cat
 * @return
 */
function nv_menu_blocks_active( $cat )
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

/**
 * nv_menu_check_current()
 *
 * @param mixed $url
 * @param integer $type
 * @return
 */
function nv_menu_check_current( $url, $type = 0 )
{
	global $module_name, $home, $client_info, $global_config;

	$url = nv_unhtmlspecialchars( $url );

	if( $client_info['selfurl'] == $url ) return true;
	// Chinh xac tuyet doi

	$_curr_url = NV_BASE_SITEURL . str_replace( $global_config['site_url'] . '/', '', $client_info['selfurl'] );
	$_url = nv_url_rewrite( $url, true );

	if( $home and ( $_url == nv_url_rewrite( NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA ) or $_url == NV_BASE_SITEURL . 'index.php' or $_url == NV_BASE_SITEURL ) )
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
 * nv_smenu_blocks()
 *
 * Hien thi menu con
 *
 * @param mixed $style
 * @param mixed $list_cats
 * @param mixed $list_sub
 * @return
 */
function nv_smenu_blocks( $style, $list_cats, $list_sub )
{
	global $global_config;

	if( file_exists( NV_ROOTDIR . '/themes/' . $global_config['site_theme'] . '/modules/menu/' . $style . '.tpl' ) )
	{
		$block_theme = $global_config['site_theme'];
	}
	else
	{
		$block_theme = 'default';
	}

	$xtpl = new XTemplate( $style . '.tpl', NV_ROOTDIR . '/themes/' . $block_theme . '/modules/menu' );

	if( empty( $list_sub ) )
	{
		return '';
	}
	else
	{
		$list = explode( ',', $list_sub );

		foreach( $list_cats as $cat )
		{
			$catid = $cat['id'];
			if( in_array( $catid, $list ) )
			{
				$list_cats[$catid]['class'] = nv_menu_blocks_active( $list_cats[$catid] );

				$xtpl->assign( 'MENUTREE', $list_cats[$catid] );

				if( ! empty( $list_cats[$catid]['subcats'] ) )
				{
					$tree = nv_smenu_blocks( $style, $list_cats, $list_cats[$catid]['subcats'] );

					$xtpl->assign( 'TREE_CONTENT', $tree );
					$xtpl->parse( 'tree.tree_content' );
				}

				$xtpl->parse( 'tree' );
			}
		}

		return $xtpl->text( 'tree' );
	}
}