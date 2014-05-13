<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 3/9/2010 23:25
 */

if( ! defined( 'NV_MAINFILE' ) ) die( 'Stop!!!' );

if( ! nv_function_exists( 'nv_news_block_news' ) )
{

	function nv_block_config_news( $module, $data_block, $lang_block )
	{
		$html = '<tr>';
		$html .= '	<td>' . $lang_block['numrow'] . '</td>';
		$html .= '	<td><input type="text" name="config_numrow" class="form-control w100" size="5" value="' . $data_block['numrow'] . '"/></td>';
		$html .= '</tr>';
		$html .= '<tr>';
		$html .= '<td>' . $lang_block['showtooltip'] . '</td>';
		$html .= '<td>';
		$html .= '<input type="checkbox" value="1" name="config_showtooltip" ' . ( $data_block['showtooltip'] == 1 ? 'checked="checked"' : '' ) . ' /><br /><br />';
		$tooltip_position = array( 'top' => $lang_block['tooltip_position_top'], 'bottom' => $lang_block['tooltip_position_bottom'], 'left' => $lang_block['tooltip_position_left'], 'right' => $lang_block['tooltip_position_right'] );
		$html .= '<span class="text-middle pull-left">' . $lang_block['tooltip_position'] . '&nbsp;</span><select name="config_tooltip_position" class="form-control w100 pull-left">';
		foreach( $tooltip_position as $key => $value )
		{
			$html .= '<option value="' . $key . '" ' . ( $data_block['tooltip_position'] == $key ? 'selected="selected"' : '' ) . '>' . $value . '</option>';
		}
		$html .= '</select>';		
		$html .= '&nbsp;<span class="text-middle pull-left">' . $lang_block['tooltip_length'] . '&nbsp;</span><input type="text" class="form-control w100 pull-left" name="config_tooltip_length" size="5" value="' . $data_block['tooltip_length'] . '"/>';
		$html .= '</td>';
		$html .= '</tr>';
		return $html;
	}

	function nv_block_config_news_submit( $module, $lang_block )
	{
		global $nv_Request;
		$return = array();
		$return['error'] = array();
		$return['config'] = array();
		$return['config']['numrow'] = $nv_Request->get_int( 'config_numrow', 'post', 0 );
		$return['config']['showtooltip'] = $nv_Request->get_int( 'config_showtooltip', 'post', 0 );
		$return['config']['tooltip_position'] = $nv_Request->get_string( 'config_tooltip_position', 'post', 0 );
		$return['config']['tooltip_length'] = $nv_Request->get_string( 'config_tooltip_length', 'post', 0 );
		return $return;
	}

	function nv_news_block_news( $block_config, $mod_data )
	{
		global $module_array_cat, $module_info, $db, $module_config, $global_config;

		$module = $block_config['module'];
		$blockwidth = $module_config[$module]['blockwidth'];
		$show_no_image = $module_config[$module]['show_no_image'];
		$numrow = ( isset( $block_config['numrow'] ) ) ? $block_config['numrow'] : 20;

		$cache_file = NV_LANG_DATA . '__block_news_' . $numrow . '_' . NV_CACHE_PREFIX . '.cache';
		if( ( $cache = nv_get_cache( $module, $cache_file ) ) != false )
		{
			$array_block_news = unserialize( $cache );
		}
		else
		{
			$array_block_news = array();

			$db->sqlreset()
				->select( 'id, catid, publtime, exptime, title, alias, homeimgthumb, homeimgfile, hometext' )
				->from( NV_PREFIXLANG . '_' . $mod_data . '_rows' )
				->where( 'status= 1' )
				->order( 'publtime DESC' )
				->limit( $numrow );
			$result = $db->query( $db->sql() );

			while( list( $id, $catid, $publtime, $exptime, $title, $alias, $homeimgthumb, $homeimgfile, $hometext ) = $result->fetch( 3 ) )
			{
				$link = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module . '&amp;' . NV_OP_VARIABLE . '=' . $module_array_cat[$catid]['alias'] . '/' . $alias . '-' . $id . $global_config['rewrite_exturl'];
				if( $homeimgthumb == 1 ) //image thumb
				{
					$imgurl = NV_BASE_SITEURL . NV_FILES_DIR . '/' . $module . '/' . $homeimgfile;
				}
				elseif( $homeimgthumb == 2 ) //image file
				{
					$imgurl = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module . '/' . $homeimgfile;
				}
				elseif( $homeimgthumb == 3 ) //image url
				{
					$imgurl = $homeimgfile;
				}
				elseif( ! empty( $show_no_image ) ) //no image
				{
					$imgurl = NV_BASE_SITEURL . $show_no_image;
				}
				else
				{
					$imgurl = '';
				}
				$array_block_news[] = array(
					'id' => $id,
					'title' => $title,
					'link' => $link,
					'imgurl' => $imgurl,
					'width' => $blockwidth,
					'hometext' => $hometext
				);
			}
			$cache = serialize( $array_block_news );
			nv_set_cache( $module, $cache_file, $cache );
		}

		if( file_exists( NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/news/block_news.tpl' ) )
		{
			$block_theme = $module_info['template'];
		}
		else
		{
			$block_theme = 'default';
		}
		$xtpl = new XTemplate( 'block_news.tpl', NV_ROOTDIR . '/themes/' . $block_theme . '/modules/news/' );
		
		foreach( $array_block_news as $array_news )
		{
			$array_news['hometext'] = nv_clean60( $array_news['hometext'], $block_config['tooltip_length'] );
			$xtpl->assign( 'blocknews', $array_news );
			if( ! empty( $array_news['imgurl'] ) )
			{
				$xtpl->parse( 'main.newloop.imgblock' );
			}
			
			if( ! $block_config['showtooltip'] )
			{
				$xtpl->assign( 'TITLE', 'title="' . $array_news['title'] . '"' );
			}
			
			$xtpl->parse( 'main.newloop' );
		}
		
		if( $block_config['showtooltip'] )
		{
			$xtpl->assign( 'TOOLTIP_POSITION', $block_config['tooltip_position'] );
			$xtpl->parse( 'main.tooltip' );
		}

		$xtpl->parse( 'main' );
		return $xtpl->text( 'main' );
	}
}

if( defined( 'NV_SYSTEM' ) )
{
	global $site_mods, $module_name, $global_array_cat, $module_array_cat;
	$module = $block_config['module'];
	if( isset( $site_mods[$module] ) )
	{
		$mod_data = $site_mods[$module]['module_data'];
		if( $module == $module_name )
		{
			$module_array_cat = $global_array_cat;
			unset( $module_array_cat[0] );
		}
		else
		{
			$module_array_cat = array();
			$sql = 'SELECT catid, parentid, title, alias, viewcat, subcatid, numlinks, description, inhome, keywords, groups_view FROM ' . NV_PREFIXLANG . '_' . $mod_data . '_cat ORDER BY sort ASC';
			$list = nv_db_cache( $sql, 'catid', $module );
			foreach( $list as $l )
			{
				$module_array_cat[$l['catid']] = $l;
				$module_array_cat[$l['catid']]['link'] = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module . '&amp;' . NV_OP_VARIABLE . '=' . $l['alias'];
			}
		}
		$content = nv_news_block_news( $block_config, $mod_data );
	}
}