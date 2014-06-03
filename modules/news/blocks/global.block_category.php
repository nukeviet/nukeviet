<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 3/9/2010 23:25
 */

if( ! defined( 'NV_MAINFILE' ) ) die( 'Stop!!!' );

if( ! nv_function_exists( 'nv_news_category' ) )
{

	function nv_block_config_news_category( $module, $data_block, $lang_block )
	{
	    global $site_mods;
		$html .= '<tr>';
		$html .= '<td>' . $lang_block['catid'] . '</td>';
		$html .= '<td><select name="config_catid" class="form-control w200">';
		$html .= '<option value="0"> -- </option>';
		$sql = 'SELECT * FROM ' . NV_PREFIXLANG . '_' . $site_mods[$module]['module_data'] . '_cat ORDER BY sort ASC';
		$list = nv_db_cache( $sql, '', $module );
		foreach( $list as $l )
		{
			$xtitle_i = '';

			if( $l['lev'] > 0 )
			{
				for( $i = 1; $i <= $l['lev']; ++$i )
				{
					$xtitle_i .= '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
				}
			}
			$html_input .= '<input type="hidden" id="config_catid_' . $l['catid'] . '" value="' . NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module . '&amp;' . NV_OP_VARIABLE . '=' . $l['alias'] . '" />';
			$html .= '<option alt="' . $linksite . '" value="' . $l['catid'] . '" ' . ( ( $data_block['catid'] == $l['catid'] ) ? ' selected="selected"' : '' ) . '>' . $xtitle_i . $l['title'] . '</option>';
		}
		$html .= '</select>';
		$html .= $html_input;
		$html .= '<script type="text/javascript">';
		$html .= '	$("select[name=config_catid]").change(function() {';
		$html .= '		$("input[name=title]").val($("select[name=config_catid] option:selected").text());';
		$html .= '		$("input[name=link]").val($("#config_catid_" + $("select[name=config_catid]").val()).val());';
		$html .= '	});';
		$html .= '</script>';
		$html .= '</tr>';
        $html .= '<tr>';
		$html .= '<td>' . $lang_block['title_length'] . '</td>';
        $html .= '<td>';
		$html .= "<select name=\"config_title_length\" class=\"form-control w200\">\n";
		$html .= "<option value=\"\">" . $lang_block['title_length'] . "</option>\n";
		for( $i = 0; $i < 100; ++$i )
		{
			$html .= "<option value=\"" . $i . "\" " . ( ( $data_block['title_length'] == $i ) ? " selected=\"selected\"" : "" ) . ">" . $i . "</option>\n";
		}
		$html .= "</select>\n";
        $html .= '</td>';
        $html .= '</tr>';
		return $html;
	}

	function nv_block_config_news_category_submit( $module, $lang_block )
	{
		global $nv_Request;
		$return = array();
		$return['error'] = array();
		$return['config'] = array();
        $return['config']['catid'] = $nv_Request->get_int( 'config_catid', 'post', 0 );
		$return['config']['title_length'] = $nv_Request->get_int( 'config_title_length', 'post', 0 );
		return $return;
	}

	function nv_news_category( $block_config )
	{
		global $module_array_cat, $module_info, $lang_module;

		$xtpl = new XTemplate( 'block_category.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/news' );

		if( ! empty( $module_array_cat ) )
		{
			$title_length = $block_config['title_length'];
			$xtpl->assign( 'LANG', $lang_module );
			$xtpl->assign( 'BLOCK_ID', $block_config['bid'] );
			$xtpl->assign( 'TEMPLATE', $module_info['template'] );
			$html = '';
			foreach( $module_array_cat as $cat )
			{
				if( $block_config['catid'] == 0 && $cat['parentid'] == 0 || ( $block_config['catid'] > 0 && $cat['parentid'] == $block_config['catid'] ) )
				{
					$html .= "<li>\n";
					$html .= "<a title=\"" . $cat['title'] . "\" href=\"" . $cat['link'] . "\">" . nv_clean60( $cat['title'], $title_length ) . "</a>\n";
					if( ! empty( $cat['subcatid'] ) )
					{
						$html .= "<span class=\"arrow\" id=\"expand\">+</span>";
						$html .= nv_news_sub_category( $cat['subcatid'], $title_length );
					}
					$html .= "</li>\n";
				}
			}
			$xtpl->assign( 'MENUID', $block_config['bid'] );
			$xtpl->assign( 'HTML_CONTENT', $html );
			$xtpl->parse( 'main' );
			return $xtpl->text( 'main' );
		}
	}

	function nv_news_sub_category( $list_sub, $title_length )
	{
		global $module_array_cat;
		if( empty( $list_sub ) )
		{
			return "";
		}
		else
		{
			$list = explode( ',', $list_sub );
			$html = "<ul>\n";
			foreach( $list as $catid )
			{
				$html .= "<li>\n";
				$html .= "<a title=\"" . $module_array_cat[$catid]['title'] . "\" href=\"" . $module_array_cat[$catid]['link'] . "\">" . nv_clean60( $module_array_cat[$catid]['title'], $title_length ) . "</a>\n";
				if( ! empty( $module_array_cat[$catid]['subcatid'] ) ) $html .= nv_news_sub_category( $module_array_cat[$catid]['subcatid'], $title_length );
				$html .= "</li>\n";
			}
			$html .= "</ul>\n";
			return $html;
		}
	}
}

if( defined( 'NV_SYSTEM' ) )
{
	global $site_mods, $module_name, $global_array_cat, $module_array_cat;
	$module = $block_config['module'];
	if( isset( $site_mods[$module] ) )
	{
		if( $module == $module_name )
		{
			$module_array_cat = $global_array_cat;
			unset( $module_array_cat[0] );
		}
		else
		{
			$module_array_cat = array();
			$sql = "SELECT catid, parentid, title, alias, viewcat, subcatid, numlinks, description, inhome, keywords, groups_view FROM " . NV_PREFIXLANG . "_" . $site_mods[$module]['module_data'] . "_cat ORDER BY sort ASC";
			$list = nv_db_cache( $sql, 'catid', $module );
			foreach( $list as $l )
			{
				$module_array_cat[$l['catid']] = $l;
				$module_array_cat[$l['catid']]['link'] = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module . "&amp;" . NV_OP_VARIABLE . "=" . $l['alias'];
			}
		}
		$content = nv_news_category( $block_config );
	}
}