<?php

/**
 * @Project NUKEVIET 3.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2012 VINADES.,JSC. All rights reserved
 * @Createdate 3/9/2010 23:25
 */

if( ! defined( 'NV_MAINFILE' ) ) die( 'Stop!!!' );

if( ! nv_function_exists( 'nv_bdown_news' ) )
{
	function nv_block_config_bdown_news( $module, $data_block, $lang_block )
	{
		global $db, $site_mods;
		$html = "";
		$html .= "<tr>";
		$html .= "	<td>" . $lang_block['title_length'] . "</td>";
		$html .= "	<td><input type=\"text\" name=\"config_title_length\" size=\"5\" value=\"" . $data_block['title_length'] . "\"/></td>";
		$html .= "</tr>";
		$html .= "<tr>";
		$html .= "	<td>" . $lang_block['numrow'] . "</td>";
		$html .= "	<td><input type=\"text\" name=\"config_numrow\" size=\"5\" value=\"" . $data_block['numrow'] . "\"/></td>";
		$html .= "</tr>";
		$html .= "<tr>";
		$html .= "	<td>" . $lang_block['class_name'] . "</td>";
		$html .= "	<td><input type=\"text\" name=\"config_class_name\" size=\"5\" value=\"" . $data_block['class_name'] . "\"/></td>";
		$html .= "</tr>";
		$html .= "<tr>";
		$html .= "	<td>" . $lang_block['img_bullet'] . "</td>";
		$html .= "	<td><input type=\"text\" name=\"config_img_bullet\" size=\"5\" value=\"" . $data_block['img_bullet'] . "\"/></td>";
		$html .= "</tr>";
		return $html;
	}

	function nv_block_config_bdown_news_submit( $module, $lang_block )
	{
		global $nv_Request;
		$return = array();
		$return['error'] = array();
		$return['config'] = array();
		$return['config']['title_length'] = $nv_Request->get_int( 'config_title_length', 'post', 24 );
		$return['config']['numrow'] = $nv_Request->get_int( 'config_numrow', 'post', 5 );
		$return['config']['class_name'] = filter_text_input( 'config_class_name', 'post', 'list_item' );
		$return['config']['img_bullet'] = filter_text_input( 'config_img_bullet', 'post', '' );
		return $return;
	}

	function nv_bdown_news( $block_config )
	{
		global $db, $module_info, $site_mods;

		$module = $block_config['module'];
		$file = $site_mods[$module]['module_file'];

		// Lay thong tin phan quyen
		$sql = "SELECT `id`, `alias`, `who_view`, `groups_view` FROM `" . NV_PREFIXLANG . "_" . $site_mods[$module]['module_data'] . "_categories` WHERE `status`=1";
		$_tmp = nv_db_cache( $sql, 'id', $module );
		$list_cat = array();
		if( $_tmp )
		{
			foreach( $_tmp as $row )
			{
				if( nv_set_allow( $row['who_view'], $row['groups_view'] ) ) $list_cat[$row['id']] = $row['alias'];
			}
		}
		unset( $_tmp, $sql );

		if( $list_cat )
		{
			$sql = "SELECT `id`, `catid`, `title`, `alias`, `updatetime` FROM `" . NV_PREFIXLANG . "_" . $site_mods[$module]['module_data'] . "` WHERE `status` AND `catid` IN(" . implode( ",", array_keys( $list_cat ) ) . ") ORDER BY `updatetime` DESC LIMIT 0 , " . $block_config['numrow'];
			$list = nv_db_cache( $sql, 'id', $module );

			if( ! empty( $list ) )
			{
				if( file_exists( NV_ROOTDIR . "/themes/" . $module_info['template'] . "/modules/" . $file . "/block_new_files.tpl" ) )
				{
					$block_theme = $module_info['template'];
				}
				else
				{
					$block_theme = "default";
				}
				$xtpl = new XTemplate( "block_new_files.tpl", NV_ROOTDIR . "/themes/" . $block_theme . "/modules/" . $file );
				$xtpl->assign( 'CONFIG', $block_config );

				foreach( $list as $row )
				{
					$row['link'] = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module . "&amp;" . NV_OP_VARIABLE . "=" . $list_cat[$row['catid']] . "/" . $row['alias'];

					$row['updatetime'] = nv_date( "d/m/Y", $row['updatetime'] );
					$row['stitle'] = nv_clean60( $row['title'], $block_config['title_length'] );

					$xtpl->assign( 'ROW', $row );

					if( $block_config['img_bullet'] ) $xtpl->parse( 'main.loop.bullet' );

					$xtpl->parse( 'main.loop' );
				}

				$xtpl->parse( 'main' );
				return $xtpl->text( 'main' );
			}
		}
	}
}

if( defined( 'NV_SYSTEM' ) )
{
	$content = nv_bdown_news( $block_config );
}

?>