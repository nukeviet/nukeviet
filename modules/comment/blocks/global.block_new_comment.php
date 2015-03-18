<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 3/9/2010 23:25
 */

if( ! defined( 'NV_MAINFILE' ) ) die( 'Stop!!!' );

if( ! nv_function_exists( 'nv_comment_new' ) )
{
	function nv_block_comment_new( $module, $data_block, $lang_block )
	{
		global $module_config, $db;

		$array_module_comment = array();
		$result = $db->query( 'SELECT title, module_file, module_data, custom_title, admin_title, admins FROM ' . NV_MODULES_TABLE . ' ORDER BY weight' );
		while( $row = $result->fetch() )
		{
			$module_i = $row['title'];
			if( isset( $module_config[$module_i]['activecomm'] ) )
			{
				$array_module_comment[$module_i] = $row['custom_title'];
			}
		}

		$html = '<tr>';
		$html .= '	<td>' . $lang_block['titlelength'] . '</td>';
		$html .= '	<td><input type="text" name="config_titlelength" class="form-control w200" size="5" value="' . $data_block['titlelength'] . '"/><span class="help-block">' . $lang_block['titlenote'] . '</span></td>';
		$html .= '</tr>';

		$html .= '<tr>';
		$html .= '	<td>' . $lang_block['numrow'] . '</td>';
		$html .= '	<td><input type="text" name="config_numrow" class="form-control w200" size="5" value="' . $data_block['numrow'] . '"/></td>';
		$html .= '</tr>';

		$html .= '<tr>';
		$html .= '<td>' . $lang_block['module'] . '</td>';
		$html .= '<td>';
		foreach( $array_module_comment as $module_i => $title )
		{
			$ck = in_array( $module_i, $data_block['module_view'] ) ? ' checked=checked' : '';
			$html .= '<input ' . $ck . ' type="checkbox" name="config_module[]" class="form-control w200" size="5" id="' . $module_i . '" value="' . $module_i . '"/><label for="' . $module_i . '">' . $title . '</label><br />';
		}
		$html .= '</td>';
		$html .= '</tr>';
		return $html;
	}

	function nv_block_comment_new_submit( $module, $lang_block )
	{
		global $nv_Request;
		$return = array();
		$return['error'] = array();
		$return['config'] = array();
		$return['config']['titlelength'] = $nv_Request->get_int( 'config_titlelength', 'post', 0 );
		$return['config']['module_view'] = $nv_Request->get_array( 'config_module', 'post', array() );
		$return['config']['numrow'] = $nv_Request->get_int( 'config_numrow', 'post', 0 );
		return $return;
	}

	function nv_comment_new( $block_config )
	{
		global $site_mods, $db, $module_info;

		$module = $block_config['module'];
		$mod_data = $site_mods[$module]['module_data'];
		$mod_file = $site_mods[$module]['module_file'];

		if( file_exists( NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $mod_file . '/block_new_comment.tpl' ) )
		{
			$block_theme = $module_info['template'];
		}
		else
		{
			$block_theme = 'default';
		}

		$xtpl = new XTemplate( 'block_new_comment.tpl', NV_ROOTDIR . '/themes/' . $block_theme . '/modules/' . $mod_file );
		if( empty( $block_config['module_view'] ) )
		{
			$sql = "SELECT * FROM " . NV_PREFIXLANG . "_" . $mod_data . "s WHERE status=1 ORDER BY post_time DESC LIMIT " . $block_config['numrow'];
		}
		else
		{
			$sql_where = array();
			foreach( $block_config['module_view'] as $module_i )
			{
				$sql_where[] = 'module LIKE "%' . $module_i . '%"';
			}
			$sql = "SELECT * FROM " . NV_PREFIXLANG . "_" . $mod_data . "s WHERE status=1 AND (" . implode( ' OR ', $sql_where ) . ") ORDER BY post_time DESC LIMIT " . $block_config['numrow'];
		}

		$result = $db->query( $sql );
		$data_comment = array();
		while( $row = $result->fetch() )
		{
			$data_comment[] = $row;
		}
		if( ! empty( $data_comment ) )
		{
			foreach( $data_comment as $comment )
			{
				$comment['content'] = nv_clean60( $comment['content'], $block_config['titlelength'] );
				$comment['post_time'] = nv_date( 'd/m/Y H:i', $comment['post_time'] );
				$xtpl->assign( 'COMMENT', $comment );
				$xtpl->parse( 'main.loop' );
			}
		}

		$xtpl->parse( 'main' );
		return $xtpl->text( 'main' );
	}
}

if( defined( 'NV_SYSTEM' ) )
{
	$module = $block_config['module'];
	$content = nv_comment_new( $block_config );
}
