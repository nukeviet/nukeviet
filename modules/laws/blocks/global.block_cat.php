<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Jul 06, 2011, 06:31:13 AM
 */

if( ! defined( 'NV_MAINFILE' ) ) die( 'Stop!!!' );

if ( ! function_exists( 'nv_laws_block_cat' ) )
{
	function nv_block_config_laws_cat( $module, $data_block, $lang_block )
	{
		$html = '';
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

	function nv_block_config_laws_cat_submit( $module, $lang_block )
	{
		global $nv_Request;
		$return = array();
		$return['error'] = array();
		$return['config'] = array();
		$return['config']['title_length'] = $nv_Request->get_int( 'config_title_length', 'post', 0 );
		return $return;
	}

    function nv_laws_block_cat( $block_config )
    {
        global $lang_module, $module_info, $global_config, $site_mods, $nv_laws_listcat, $module_name;

		$module = $block_config['module'];
		$mod_data = $site_mods[$module]['module_data'];
		$mod_file = $site_mods[$module]['module_file'];

		if( file_exists( NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $mod_file . '/block_cat.tpl' ) )
		{
			$block_theme = $global_config['module_theme'];
		}
		else
		{
			$block_theme = 'default';
		}

        $xtpl = new XTemplate( "block_cat.tpl", NV_ROOTDIR . "/themes/" . $block_theme . "/modules/" . $mod_file );

        $html = "";

		$i = 1;
        foreach ( $nv_laws_listcat as $cat )
        {
        	if( $cat['id'] == 0 ) continue;
            if ( $cat['parentid'] == 0 )
            {
				$link = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module . "&amp;" . NV_OP_VARIABLE . "=" . $cat['alias'];
				$html .= "<li>";
				$html .= "<em class=\"fa fa-comment-o fa-lg\">&nbsp;</em><a title=\"" . $cat['title'] . "\" href=\"" . $link . "\">" . nv_clean60( $cat['title'], $block_config['title_length'] ) . "</a>\n";
				if ( ! empty( $cat['subcats'] ) ) $html .= nv_content_subcat( $cat['subcats'], $block_config['title_length'] );
				$html .= "</li>";

				if( $i >= 10 ) break;
				$i ++;
            }
        }
        $xtpl->assign( 'CONTENT', $html );
        $xtpl->parse( 'main' );
        return $xtpl->text( 'main' );
    }

    function nv_content_subcat ( $list_sub, $title_length )
    {
        global $nv_laws_listcat, $module_name;

        if ( empty( $list_sub ) ) return "";
        else
        {
            $html = "<ul>\n";
            foreach ( $list_sub as $catid )
            {
				$link = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $nv_laws_listcat[$catid]['alias'];
				$html .= "<li>\n";
				$html .= "<a title=\"" . $nv_laws_listcat[$catid]['title'] . "\" href=\"" . $link . "\">" . nv_clean60( $nv_laws_listcat[$catid]['title'], $title_length ) . "</a>\n";
				if ( ! empty( $nv_laws_listcat[$catid]['subcats'] ) ) $html .= nv_content_subcat( $nv_laws_listcat[$catid]['subcats'], $title_length );
				$html .= "</li>\n";
            }
            $html .= "</ul>\n";
            return $html;
        }
    }
}

if( defined( 'NV_SYSTEM' ) )
{
	global $site_mods, $module_name, $nv_laws_listcat, $module_array_cat, $nv_Cache;
	$module = $block_config['module'];

	if( isset( $site_mods[$module] ) )
	{
		if( $module != $module_name )
		{
			$module_array_cat = array();
    		$sql = "SELECT id, parentid, alias, title, introduction, keywords FROM " . NV_PREFIXLANG . "_" . $site_mods[$module]['module_data'] . "_cat ORDER BY parentid, weight ASC";
			$list = $nv_Cache->db( $sql, 'id', $module );
			foreach( $list as $l )
			{
				$module_array_cat[$l['id']] = $l;
				$module_array_cat[$l['id']]['link'] = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module . '&amp;' . NV_OP_VARIABLE . '=' . $l['alias'];
			}
			$nv_laws_listcat = $module_array_cat;
		}
		$content = nv_laws_block_cat( $block_config );
	}
}