<?php

/**
 * @Project NUKEVIET 3.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2012 VINADES.,JSC. All rights reserved
 * @Createdate 3/9/2010 23:25
 */

if ( ! defined( 'NV_MAINFILE' ) ) die( 'Stop!!!' );

if ( ! nv_function_exists( 'nv_news_block_tophits' ) )
{
    function nv_block_config_tophits_blocks ( $module, $data_block, $lang_block )
    {
        $html = "";
        $html .= "<tr>";
        $html .= "	<td>" . $lang_block['number_day'] . "</td>";
        $html .= "	<td><input type=\"text\" name=\"config_number_day\" size=\"5\" value=\"" . $data_block['number_day'] . "\"/></td>";
        $html .= "</tr>";
        $html .= "<tr>";
        $html .= "	<td>" . $lang_block['numrow'] . "</td>";
        $html .= "	<td><input type=\"text\" name=\"config_numrow\" size=\"5\" value=\"" . $data_block['numrow'] . "\"/></td>";
        $html .= "</tr>";
        return $html;
    }

    function nv_block_config_tophits_blocks_submit ( $module, $lang_block )
    {
        global $nv_Request;
        $return = array();
        $return['error'] = array();
        $return['config'] = array();
        $return['config']['number_day'] = $nv_Request->get_int( 'config_number_day', 'post', 0 );
        $return['config']['numrow'] = $nv_Request->get_int( 'config_numrow', 'post', 0 );
        return $return;
    }

    function nv_news_block_tophits ( $block_config, $mod_data )
    {
        global $module_array_cat, $module_info, $db, $module_config;
        
        $module = $block_config['module'];
        
        $blockwidth = $module_config[$module]['blockwidth'];
        $publtime = NV_CURRENTTIME - $block_config['number_day'] * 86400;
        
        $array_block_news = array();
        $sql = "SELECT id, catid, publtime, exptime, title, alias, homeimgthumb, homeimgfile FROM `" . NV_PREFIXLANG . "_" . $mod_data . "_rows` WHERE `status`= 1 AND `publtime` BETWEEN  " . $publtime . " AND " . NV_CURRENTTIME . " ORDER BY `hitstotal` DESC LIMIT 0 , " . $block_config['numrow'];
        $result = $db->sql_query( $sql );
        while ( list( $id, $catid, $publtime, $exptime, $title, $alias, $homeimgthumb, $homeimgfile ) = $db->sql_fetchrow( $result ) )
        {
            if ( ! empty( $homeimgthumb ) )
            {
                $array_img = explode( "|", $homeimgthumb );
            }
            else
            {
                $array_img = array( 
                    "", "" 
                );
            }
            if ( $array_img[0] != "" and file_exists( NV_ROOTDIR . '/' . NV_FILES_DIR . '/' . $module . '/' . $array_img[0] ) )
            {
                $imgurl = NV_BASE_SITEURL . NV_FILES_DIR . '/' . $module . '/' . $array_img[0];
            }
            elseif ( nv_is_url( $homeimgfile ) )
            {
                $imgurl = $homeimgfile;
            }
            elseif ( $homeimgfile != "" and file_exists( NV_UPLOADS_REAL_DIR . '/' . $module . '/' . $homeimgfile ) )
            {
                $imgurl = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module . '/' . $homeimgfile;
            }
            else
            {
                $imgurl = "";
            }

            $link = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module . "&amp;" . NV_OP_VARIABLE . "=" . $module_array_cat[$catid]['alias'] . "/" . $alias . "-" . $id;
            
            $array_block_news[] = array( 
                'id' => $id, 'title' => $title, 'link' => $link, 'imgurl' => $imgurl, 'width' => $blockwidth 
            );
        }
        
        if ( file_exists( NV_ROOTDIR . "/themes/" . $module_info['template'] . "/modules/news/block_news.tpl" ) )
        {
            $block_theme = $module_info['template'];
        }
        else
        {
            $block_theme = "default";
        }
        
        $xtpl = new XTemplate( "block_news.tpl", NV_ROOTDIR . "/themes/" . $block_theme . "/modules/news" );
        $a = 1;
        foreach ( $array_block_news as $array_news )
        {
            $xtpl->assign( 'blocknews', $array_news );
            if ( ! empty( $array_news['imgurl'] ) )
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

if ( defined( 'NV_SYSTEM' ) )
{
    global $site_mods, $module_name, $global_array_cat, $module_array_cat;
    $module = $block_config['module'];
    if ( isset( $site_mods[$module] ) )
    {
        $mod_data = $site_mods[$module]['module_data'];
        if ( $module == $module_name )
        {
            $module_array_cat = $global_array_cat;
            unset( $module_array_cat[0] );
        }
        else
        {
            $module_array_cat = array();
            $sql = "SELECT catid, parentid, title, alias, viewcat, subcatid, numlinks, description, inhome, keywords, who_view, groups_view FROM `" . NV_PREFIXLANG . "_" . $mod_data . "_cat` ORDER BY `order` ASC";
            $list = nv_db_cache( $sql, 'catid', $module );
            foreach ( $list as $l )
            {
                $module_array_cat[$l['catid']] = $l;
                $module_array_cat[$l['catid']]['link'] = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module . "&amp;" . NV_OP_VARIABLE . "=" . $l['alias'];
            }
        }
        $content = nv_news_block_tophits( $block_config, $mod_data );
    }
}

?>