<?php
/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES., JSC. All rights reserved
 * @Createdate 3/9/2010 23:25
 */

if ( ! defined( 'NV_MAINFILE' ) ) die( 'Stop!!!' );

if ( ! function_exists( 'nv_pro_catalogs' ) )
{
	function nv_block_config_product_catalogs_blocks ( $module, $data_block, $lang_block )
    {
        global $db, $language_array, $db_config;
		$sh = $sv = "";
		if ( $data_block['type'] == 'v' ){ $sv = "selected=\"selected\""; $sh = "";}
		if ( $data_block['type'] == 'h' ){ $sh = "selected=\"selected\""; $sv = "";}
        $html = "";
        $html .= "<tr>";
        $html .= "	<td>" . $lang_block['cut_num'] . "</td>";
        $html .= "	<td><input type=\"text\" name=\"config_cut_num\" size=\"5\" value=\"" . $data_block['cut_num'] . "\"/></td>";
        $html .= "</tr>";
		$html .= "<tr>";
        $html .= "	<td>" . $lang_block['type'] . "</td>";
        $html .= "	<td>
						<select name=\"config_type\">
							<option value=\"h\" ".$sh.">Horizontal</option>
							<option value=\"v\" ".$sv.">Vertical</option>
						</select>
					</td>";
        $html .= "</tr>";
        return $html;
    }

    function nv_block_config_product_catalogs_blocks_submit ( $module, $lang_block )
    {
        global $nv_Request;
        $return = array();
        $return['error'] = array();
        $return['config'] = array();
        $return['config']['cut_num'] = $nv_Request->get_int( 'config_cut_num', 'post', 0 );
		$return['config']['type'] = $nv_Request->get_string( 'config_type', 'post', 0 );
        return $return;
    }
    function nv_pro_catalogs ( $block_config )
    {
        global $site_mods, $global_config, $module_config, $module_name, $module_info, $global_array_cat, $db, $db_config, $my_head,$array_cat_shops;
        $module = $block_config['module'];
        $mod_data = $site_mods[$module]['module_data'];
        $mod_file = $site_mods[$module]['module_file'];
		$block_tpl_name = "";
		if ( $block_config['type'] == 'v' ) $block_tpl_name = "block.catalogsv.tpl";
		elseif ( $block_config['type'] == 'h' ) $block_tpl_name = "block.catalogsh.tpl";
        $pro_config = $module_config[$module];
        $array_cat_shops = array();
		
        if ( file_exists( NV_ROOTDIR . "/themes/" . $global_config['site_theme'] . "/modules/" . $mod_file . "/".$block_tpl_name) )
        {
            $block_theme = $global_config['site_theme'];
        }
        else
        {
            $block_theme = "default";
        }
        if ( $module != $module_name )
        {
            $sql_cat = "SELECT catid, parentid, lev," . NV_LANG_DATA . "_title, " . NV_LANG_DATA . "_alias, viewcat, numsubcat, subcatid, numlinks, del_cache_time, " . NV_LANG_DATA . "_description, inhome, " . NV_LANG_DATA . "_keywords, who_view, groups_view FROM `" . $db_config['prefix'] . "_" . $mod_data . "_catalogs` ORDER BY `order` ASC";
            $result = $db->sql_query( $sql_cat );
            while ( list( $catid_i, $parentid_i, $lev_i, $title_i, $alias_i, $viewcat_i, $numsubcat_i, $subcatid_i, $numlinks_i, $del_cache_time_i, $description_i, $inhome_i, $keywords_i, $who_view_i, $groups_view_i ) = $db->sql_fetchrow( $result ) )
            {
                $link_i = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module . "&amp;" . NV_OP_VARIABLE . "=" . $alias_i . "";
                $array_cat_shops[$catid_i] = array( 
                    "catid" => $catid_i, "parentid" => $parentid_i, "title" => $title_i, "alias" => $alias_i, "link" => $link_i, "viewcat" => $viewcat_i, "numsubcat" => $numsubcat_i, "subcatid" => $subcatid_i, "numlinks" => $numlinks_i, "description" => $description_i, "inhome" => $inhome_i, "keywords" => $keywords_i, "who_view" => $who_view_i, "groups_view" => $groups_view_i, 'lev' => $lev_i 
                );
            }
        }
        else
        {
            $array_cat_shops = $global_array_cat;
        }
        $xtpl = new XTemplate( $block_tpl_name, NV_ROOTDIR . "/themes/" . $block_theme. "/modules/" . $mod_file );
        $xtpl->assign( 'TEMPLATE', $block_theme );
        $xtpl->assign( 'ID', $block_config['bid'] );
        $cut_num = $block_config['cut_num'];
        $html = "";
        foreach ( $array_cat_shops as $cat )
        {
            if ( $cat['parentid'] == 0 )
            {
                if ( $cat['inhome'] == '1' )
                {
                    $html .= "<li>\n";
                    $html .= "<a title=\"" . $cat['title'] . "\" href=\"" . $cat['link'] . "\">" . nv_clean60( $cat['title'], $cut_num ) . "</a>\n";
                    if ( !empty( $cat['subcatid'] ) ) $html .= html_viewsub( $cat['subcatid'], $block_config );
                    $html .= "</li>\n";
                }
            }
        }
        $xtpl->assign( 'CONTENT', $html );
        $xtpl->parse( 'main' );
        return $xtpl->text( 'main' );
    }

    function html_viewsub ( $list_sub, $block_config )
    {
        global $array_cat_shops;
        $cut_num = $block_config['cut_num'];
        if ( empty( $list_sub ) ) return "";
        else
        {
            $html = "<ul>\n";
            $list = explode( ",", $list_sub );
            foreach ( $list as $catid )
            {
                if ( $array_cat_shops[$catid]['inhome'] == '1' )
                {
                    $html .= "<li>\n";
                    $html .= "<a title=\"" . $array_cat_shops[$catid]['title'] . "\" href=\"" . $array_cat_shops[$catid]['link'] . "\">" . nv_clean60( $array_cat_shops[$catid]['title'], $cut_num ) . "</a>\n";
                    if ( !empty( $array_cat_shops[$catid]['subcatid'] ) ) $html .= html_viewsub( $array_cat_shops[$catid]['subcatid'],$block_config );
                    $html .= "</li>\n";
                }
            }
            $html .= "</ul>\n";
            return $html;
        }
    }
}
if ( defined( 'NV_SYSTEM' ) )
{
    global $site_mods;
    $module = $block_config['module'];
    $content = nv_pro_catalogs( $block_config );
}
?>