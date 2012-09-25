<?php
/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES., JSC. All rights reserved
 * @Createdate 3/9/2010 23:25
 */

if ( ! defined( 'NV_MAINFILE' ) ) die( 'Stop!!!' );

if ( ! nv_function_exists( 'nv_global_product_center' ) )
{

    function nv_block_config_product_center_blocks ( $module, $data_block, $lang_block )
    {
        global $db, $language_array, $db_config;
        $html = "";
        $html .= "<tr>";
        $html .= "	<td>" . $lang_block['blockid'] . "</td>";
        $html .= "	<td><select name=\"config_blockid\">\n";
        $sql = "SELECT `bid`,  " . NV_LANG_DATA . "_title," . NV_LANG_DATA . "_alias FROM `" . $db_config['prefix'] . "_" . $module . "_block_cat` ORDER BY `weight` ASC";
        $list = nv_db_cache( $sql, 'catid', $module );
        foreach ( $list as $l )
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

    function nv_block_config_product_center_blocks_submit ( $module, $lang_block )
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

    function nv_global_product_center ( $block_config )
    {
        global $global_config, $module_name, $module_info, $global_array_cat, $db, $db_config;
        $module = $block_config['module'];
        $mod_file = $module;
        $array_cat_shops = $global_array_cat;
        $num_slide = $block_config['numslide'];
        $num_view = $block_config['numrow'];
        $num = $num_slide * $num_view;
        
        $sql = "SELECT bid, " . NV_LANG_DATA . "_title FROM `" . $db_config['prefix'] . "_" . $module . "_block_cat` WHERE bid= " . $block_config['blockid'] . "";
        $result = $db->sql_query( $sql );
        list( $bid, $titlebid ) = $db->sql_fetchrow( $result );
			
        $array_content = array();
        $i = 1;
        $j = 1;
        $page_i = "";
        if ( file_exists( NV_ROOTDIR . "/themes/" . $global_config['site_theme'] . "/modules/" . $mod_file . "/block.product_center.tpl" ) )
        {
            $block_theme = $global_config['site_theme'];
        }
        else
        {
            $block_theme = "default";
        }
        
        if ( $module != $module_name )
        {
            $sql_cat = "SELECT catid, parentid, lev," . NV_LANG_DATA . "_title, " . NV_LANG_DATA . "_alias, viewcat, numsubcat, subcatid, numlinks, del_cache_time, " . NV_LANG_DATA . "_description, inhome, " . NV_LANG_DATA . "_keywords, who_view, groups_view FROM `" . $db_config['prefix'] . "_" . $module . "_catalogs` ORDER BY `order` ASC";
            $result_cat = $db->sql_query( $sql_cat );
            while ( list( $catid_i, $parentid_i, $lev_i, $title_i, $alias_i, $viewcat_i, $numsubcat_i, $subcatid_i, $numlinks_i, $del_cache_time_i, $description_i, $inhome_i, $keywords_i, $who_view_i, $groups_view_i ) = $db->sql_fetchrow( $result_cat ) )
            {
                $link_i = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module . "&amp;" . NV_OP_VARIABLE . "=" . $alias_i . "";
                $array_cat_shops [$catid_i] = array( 
                    "catid" => $catid_i, "parentid" => $parentid_i, "title" => $title_i, "alias" => $alias_i, "link" => $link_i, "viewcat" => $viewcat_i, "numsubcat" => $numsubcat_i, "subcatid" => $subcatid_i, "numlinks" => $numlinks_i, "description" => $description_i, "inhome" => $inhome_i, "keywords" => $keywords_i, "who_view" => $who_view_i, "groups_view" => $groups_view_i, 'lev' => $lev_i 
                );
            }  
        }
        $xtpl = new XTemplate( "block.product_center.tpl", NV_ROOTDIR . "/themes/" . $block_theme . "/modules/" . $module );
        $xtpl->assign( 'THEME_TEM', NV_BASE_SITEURL . "themes/" . $block_theme );
        $sql = "SELECT t1.id, t1.listcatid, t1." . NV_LANG_DATA . "_title, t1." . NV_LANG_DATA . "_alias, t1.homeimgthumb , t1.homeimgalt FROM `" . $db_config['prefix'] . "_" . $module . "_rows` as t1 INNER JOIN `" . $db_config['prefix'] . "_" . $module . "_block` AS t2 ON t1.id = t2.id WHERE t2.bid= " . $bid . " AND t1.status= 1 AND t1.inhome='1' and  t1.publtime < " . NV_CURRENTTIME . " AND (t1.exptime=0 OR t1.exptime >" . NV_CURRENTTIME . ") ORDER BY t1.id DESC LIMIT 0 , " . $num;
        $result = $db->sql_query( $sql );
        while ( list( $id, $listcatid, $title, $alias, $homeimgthumb, $homeimgalt ) = $db->sql_fetchrow( $result ) )
        {
            $link = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module . "&amp;" . NV_OP_VARIABLE . "=" . $array_cat_shops[$listcatid]['alias'] . "/" . $alias . "-" . $id;
            $thumb = explode( "|", $homeimgthumb );
            if ( ! empty( $thumb[0] ) && ! nv_is_url( $thumb[0] ) )
            {
                $thumb[0] = NV_BASE_SITEURL . NV_UPLOADS_DIR . "/" . $module . "/" . $thumb[0];
            }
            else
            {
                $thumb[0] = NV_BASE_SITEURL . "themes/" . $block_theme . "/images/" . $mod_file . "/no-image.jpg";
            }
            $xtpl->assign( 'LINK', $link );
            $xtpl->assign( 'TITLE', $title );
            $xtpl->assign( 'TITLE0', nv_clean60( $title, 30 ) );
            $xtpl->assign( 'SRC_IMG', $thumb[0] );
            $xtpl->parse( 'main.loop.items' );
            if ( $i % $num_view == 0 )
            {
                $page_i .= "<li><a href=\"#\">" . $j . "</a></li>";
                $j ++;
                $xtpl->parse( 'main.loop' );
            }
            $i ++;
        }
        if ( $i > $num_view && ( $i - 1 ) % $num_view != 0 )
        {
            $page_i .= "<li><a href=\"#\">" . $j . "</a></li>";
            $xtpl->parse( 'main.loop' );
        }
        $xtpl->assign( 'page', $page_i );
        $xtpl->parse( 'main' );
        return $xtpl->text( 'main' );
    }
}

if ( defined( 'NV_SYSTEM' ) )
{
    global $site_mods;
    $module = $block_config['module'];
    $content = nv_global_product_center( $block_config );
}

?>