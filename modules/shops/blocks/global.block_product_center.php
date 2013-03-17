<?php

/**
 * @Project NUKEVIET 3.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES., JSC. All rights reserved
 * @Createdate 3/9/2010 23:25
 */

if( ! defined( 'NV_MAINFILE' ) ) die( 'Stop!!!' );

if( ! nv_function_exists( 'nv_global_product_center' ) )
{
	/**
	 * nv_block_config_product_center_blocks()
	 * 
	 * @param mixed $module
	 * @param mixed $data_block
	 * @param mixed $lang_block
	 * @return
	 */
	function nv_block_config_product_center_blocks( $module, $data_block, $lang_block )
	{
		global $db_config, $site_mods;
		
		$html = "";
		
		$html .= "<tr>";
		$html .= "	<td>" . $lang_block['blockid'] . "</td>";
		$html .= "	<td><select name=\"config_blockid\">\n";
		
		$sql = "SELECT `bid`,  " . NV_LANG_DATA . "_title," . NV_LANG_DATA . "_alias FROM `" . $db_config['prefix'] . "_" . $site_mods[$module]['module_data'] . "_block_cat` ORDER BY `weight` ASC";
		$list = nv_db_cache( $sql, 'catid', $module );
		
		foreach( $list as $l )
		{
			$sel = ( $data_block['blockid'] == $l['bid'] ) ? ' selected' : '';
			$html .= "<option value=\"" . $l['bid'] . "\" " . $sel . ">" . $l[NV_LANG_DATA . '_title'] . "</option>\n";
		}
		
		$html .= "	</select></td>\n";
		$html .= "</tr>";
		
		$html .= "<tr>";
		$html .= "	<td>" . $lang_block['numslide'] . "</td>";
		$html .= "	<td><input type=\"text\" name=\"config_numslide\" size=\"5\" value=\"" . $data_block['numslide'] . "\"/></td>";
		$html .= "</tr>";
		
		$html .= "<tr>";
		$html .= "	<td>" . $lang_block['numrow'] . "</td>";
		$html .= "	<td><input type=\"text\" name=\"config_numrow\" size=\"5\" value=\"" . $data_block['numrow'] . "\"/></td>";
		$html .= "</tr>";
		
		return $html;
	}

	/**
	 * nv_block_config_product_center_blocks_submit()
	 * 
	 * @param mixed $module
	 * @param mixed $lang_block
	 * @return
	 */
	function nv_block_config_product_center_blocks_submit( $module, $lang_block )
	{
		global $nv_Request;
		$return = array();
		$return['error'] = array();
		$return['config'] = array();
		$return['config']['blockid'] = $nv_Request->get_int( 'config_blockid', 'post', 0 );
		$return['config']['numrow'] = $nv_Request->get_int( 'config_numrow', 'post', 0 );
		$return['config']['numslide'] = $nv_Request->get_int( 'config_numslide', 'post', 0 );
		return $return;
	}

	/**
	 * nv_global_product_center()
	 * 
	 * @param mixed $block_config
	 * @return
	 */
	function nv_global_product_center( $block_config )
	{
		global $site_mods, $global_config, $module_name, $global_array_cat, $db_config, $my_head;

		$module = $block_config['module'];
		$mod_data = $site_mods[$module]['module_data'];
		$mod_file = $site_mods[$module]['module_file'];
		$array_cat_shops = $global_array_cat;
		$num_slide = $block_config['numslide'];
		$num_view = $block_config['numrow'];
		$num = $num_slide * $num_view;

		$i = 1;
		$j = 1;
		$page_i = "";
		if( file_exists( NV_ROOTDIR . "/themes/" . $global_config['site_theme'] . "/modules/" . $mod_file . "/block.product_center.tpl" ) )
		{
			$block_theme = $global_config['site_theme'];
		}
		else
		{
			$block_theme = "default";
		}

		// Xac dinh CSS
		if( file_exists( NV_ROOTDIR . "/themes/" . $global_config['site_theme'] . "/css/" . $mod_file . ".css" ) )
		{
			$block_css = $global_config['site_theme'];
		}
		else
		{
			$block_css = "default";
		}

		if( $module != $module_name )
		{
			$my_head .= '<link rel="stylesheet" href="' . NV_BASE_SITEURL . 'themes/' . $block_css . '/css/' . $mod_file . '.css' . '" type="text/css" />';
			$sql = "SELECT `catid`, `parentid`, `lev`, `" . NV_LANG_DATA . "_title` AS `title`, `" . NV_LANG_DATA . "_alias` AS `alias`, `viewcat`, `numsubcat`, `subcatid`, `numlinks`, `" . NV_LANG_DATA . "_description` AS `description`, `inhome`, `" . NV_LANG_DATA . "_keywords` AS `keywords`, `who_view`, `groups_view` FROM `" . $db_config['prefix'] . "_" . $mod_data . "_catalogs` ORDER BY `order` ASC";

			$list = nv_db_cache( $sql, "catid", $module );
			foreach( $list as $row )
			{
				$array_cat_shops[$row['catid']] = array(
					"catid" => $row['catid'],
					"parentid" => $row['parentid'],
					"title" => $row['title'],
					"alias" => $row['alias'],
					"link" => NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module . "&amp;" . NV_OP_VARIABLE . "=" . $row['alias'],
					"viewcat" => $row['viewcat'],
					"numsubcat" => $row['numsubcat'],
					"subcatid" => $row['subcatid'],
					"numlinks" => $row['numlinks'],
					"description" => $row['description'],
					"inhome" => $row['inhome'],
					"keywords" => $row['keywords'],
					"who_view" => $row['who_view'],
					"groups_view" => $row['groups_view'],
					'lev' => $row['lev']
				);
			}
			unset( $list, $row );
		}

		$xtpl = new XTemplate( "block.product_center.tpl", NV_ROOTDIR . "/themes/" . $block_theme . "/modules/" . $module );
		$xtpl->assign( 'THEME_TEM', NV_BASE_SITEURL . "themes/" . $block_theme );

		$sql = "SELECT t1.id, t1.listcatid, t1." . NV_LANG_DATA . "_title AS `title`, t1." . NV_LANG_DATA . "_alias AS `alias`, t1.homeimgthumb , t1.homeimgalt FROM `" . $db_config['prefix'] . "_" . $module . "_rows` as t1 INNER JOIN `" . $db_config['prefix'] . "_" . $module . "_block` AS t2 ON t1.id = t2.id WHERE t2.bid= " . $block_config['blockid'] . " AND t1.status=1 ORDER BY t1.id DESC LIMIT 0," . $num;
		$list = nv_db_cache( $sql, '', $module );

		foreach( $list as $row )
		{
			$link = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module . "&amp;" . NV_OP_VARIABLE . "=" . $array_cat_shops[$row['listcatid']]['alias'] . "/" . $row['alias'] . "-" . $row['id'];

			$thumb = explode( "|", $row['homeimgthumb'] );
			if( ! empty( $thumb[0] ) and ! nv_is_url( $thumb[0] ) )
			{
				$thumb[0] = NV_BASE_SITEURL . NV_UPLOADS_DIR . "/" . $module . "/" . $thumb[0];
			}
			else
			{
				$thumb[0] = NV_BASE_SITEURL . "themes/" . $block_theme . "/images/" . $mod_file . "/no-image.jpg";
			}

			$xtpl->assign( 'LINK', $link );
			$xtpl->assign( 'TITLE', $row['title'] );
			$xtpl->assign( 'TITLE0', nv_clean60( $row['title'], 30 ) );
			$xtpl->assign( 'SRC_IMG', $thumb[0] );
			$xtpl->parse( 'main.loop.items' );

			if( $i % $num_view == 0 )
			{
				$page_i .= "<li><a href=\"#\">" . $j . "</a></li>";
				$j++;
				$xtpl->parse( 'main.loop' );
			}
			$i++;
		}
		
		if( $i > $num_view and ( $i - 1 ) % $num_view != 0 )
		{
			$page_i .= "<li><a href=\"#\">" . $j . "</a></li>";
			$xtpl->parse( 'main.loop' );
		}
		
		$xtpl->assign( 'page', $page_i );
		
		$xtpl->parse( 'main' );
		return $xtpl->text( 'main' );
	}
}

if( defined( 'NV_SYSTEM' ) )
{
	$content = nv_global_product_center( $block_config );
}

?>