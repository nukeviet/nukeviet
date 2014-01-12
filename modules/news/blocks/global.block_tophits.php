<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 3/9/2010 23:25
 */

if( ! defined( 'NV_MAINFILE' ) ) die( 'Stop!!!' );

if( ! nv_function_exists( 'nv_news_block_tophits' ) )
{

	function nv_block_config_tophits_blocks( $module, $data_block, $lang_block )
	{
		$html = '';
		$html .= '<tr>';
		$html .= '	<td>' . $lang_block['number_day'] . '</td>';
		$html .= '	<td><input type="text" name="config_number_day" size="5" value="' . $data_block['number_day'] . '"/></td>';
		$html .= '</tr>';
		$html .= '<tr>';
		$html .= '	<td>' . $lang_block['numrow'] . '</td>';
		$html .= '	<td><input type="text" name="config_numrow" size="5" value="' . $data_block['numrow'] . '"/></td>';
		$html .= '</tr>';
		return $html;
	}

	function nv_block_config_tophits_blocks_submit( $module, $lang_block )
	{
		global $nv_Request;
		$return = array();
		$return['error'] = array();
		$return['config'] = array();
		$return['config']['number_day'] = $nv_Request->get_int( 'config_number_day', 'post', 0 );
		$return['config']['numrow'] = $nv_Request->get_int( 'config_numrow', 'post', 0 );
		return $return;
	}

	function nv_news_block_tophits( $block_config, $mod_data )
	{
		global $module_array_cat, $module_info, $db, $module_config, $global_config;

		$module = $block_config['module'];

		$blockwidth = $module_config[$module]['blockwidth'];
		$show_no_image = $module_config[$module]['show_no_image'];
		$publtime = NV_CURRENTTIME - $block_config['number_day'] * 86400;

		$array_block_news = array();

		$db->sqlreset()
			->select( 'id, catid, publtime, exptime, title, alias, homeimgthumb, homeimgfile' )
			->from( NV_PREFIXLANG . '_' . $mod_data . '_rows' )
			->where( 'status= 1 AND publtime BETWEEN ' . $publtime . ' AND ' . NV_CURRENTTIME )
			->order( 'hitstotal DESC' )
			->limit( $block_config['numrow'] );

		$result = $db->query( $db->sql() );
		while( list( $id, $catid, $publtime, $exptime, $title, $alias, $homeimgthumb, $homeimgfile ) = $result->fetch( 3 ) )
		{
			if( $homeimgthumb == 1 ) // image thumb
			{
				$imgurl = NV_BASE_SITEURL . NV_FILES_DIR . '/' . $module . '/' . $homeimgfile;
			}
			elseif( $homeimgthumb == 2 ) // image file
			{
				$imgurl = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module . '/' . $homeimgfile;
			}
			elseif( $homeimgthumb == 3 ) // image url
			{
				$imgurl = $homeimgfile;
			}
			elseif( $show_no_image ) // no image
			{
				$imgurl = NV_BASE_SITEURL . 'themes/' . $global_config['site_theme'] . '/images/no_image.gif';
			}
			else
			{
				$imgurl = '';
			}
			$link = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module . '&amp;' . NV_OP_VARIABLE . '=' . $module_array_cat[$catid]['alias'] . '/' . $alias . '-' . $id . $global_config['rewrite_exturl'];

			$array_block_news[] = array(
				'id' => $id,
				'title' => $title,
				'link' => $link,
				'imgurl' => $imgurl,
				'width' => $blockwidth
			);
		}

		if( file_exists( NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/news/block_news.tpl' ) )
		{
			$block_theme = $module_info['template'];
		}
		else
		{
			$block_theme = 'default';
		}

		$xtpl = new XTemplate( 'block_news.tpl', NV_ROOTDIR . '/themes/' . $block_theme . '/modules/news' );
		$a = 1;
		foreach( $array_block_news as $array_news )
		{
			$xtpl->assign( 'blocknews', $array_news );
			if( ! empty( $array_news['imgurl'] ) )
			{
				$xtpl->parse( 'main.newloop.imgblock' );
			}
			$xtpl->parse( 'main.newloop' );
			$xtpl->assign( 'BACKGROUND', ( $a % 2 ) ? 'bg ' : '' );
			++$a;
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
			$sql = 'SELECT catid, parentid, title, alias, viewcat, subcatid, numlinks, description, inhome, keywords, who_view, groups_view FROM ' . NV_PREFIXLANG . '_' . $mod_data . '_cat ORDER BY sort ASC';
			$list = nv_db_cache( $sql, 'catid', $module );
			foreach( $list as $l )
			{
				$module_array_cat[$l['catid']] = $l;
				$module_array_cat[$l['catid']]['link'] = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module . '&amp;' . NV_OP_VARIABLE . '=' . $l['alias'];
			}
		}
		$content = nv_news_block_tophits( $block_config, $mod_data );
	}
}

?>