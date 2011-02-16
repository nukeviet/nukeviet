<?php
/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES., JSC. All rights reserved
 * @Createdate 3/9/2010 23:25
 */

if ( ! defined( 'NV_MAINFILE' ) ) die( 'Stop!!!' );

if ( ! nv_function_exists( 'nv_news_blocks' ) )
{

    function nv_block_config_news_blocks ( $module, $data_block, $lang_block )
    {
        global $db, $language_array;
        $html = "";
        $html .= "<tr>";
        $html .= "	<td>" . $lang_block['blockid'] . "</td>";
        $html .= "	<td><select name=\"config_blockid\">\n";
        $sql = "SELECT * FROM `" . NV_PREFIXLANG . "_" . $module . "_block_cat` ORDER BY `weight` ASC";
        $list = nv_db_cache( $sql, 'catid', $module );
        foreach ( $list as $l )
        {
            $sel = ( $data_block['blockid'] == $l['bid'] ) ? ' selected' : '';
            $html .= "<option value=\"" . $l['bid'] . "\" " . $sel . ">" . $l['title'] . "</option>\n";
        }
        $html .= "	</select></td>\n";
        $html .= "</tr>";
        $html .= "<tr>";
        $html .= "	<td>" . $lang_block['numrow'] . "</td>";
        $html .= "	<td><input type=\"text\" name=\"config_numrow\" size=\"5\" value=\"" . $data_block['numrow'] . "\"/></td>";
        $html .= "<td>";
        return $html;
    }

    function nv_block_config_news_blocks_submit ( $module, $lang_block )
    {
        global $nv_Request;
        $return = array();
        $return['error'] = array();
        $return['config'] = array();
        $return['config']['blockid'] = $nv_Request->get_int( 'config_blockid', 'post', 0 );
        $return['config']['numrow'] = $nv_Request->get_int( 'config_numrow', 'post', 0 );
        return $return;
    }

    function nv_news_blocks ( $block_config )
    {
        global $module_array_cat, $module_info, $lang_module;
        $module = $block_config['module'];
        $sql = "SELECT t1.id, t1.listcatid, t1.title, t1.alias, t1.homeimgthumb, t1.homeimgalt FROM `" . NV_PREFIXLANG . "_" . $module . "_rows` as t1 INNER JOIN `" . NV_PREFIXLANG . "_" . $module . "_block` AS t2 ON t1.id = t2.id WHERE t2.bid= " . $block_config['blockid'] . " AND t1.status= 1 AND t1.inhome='1' and  t1.publtime < " . NV_CURRENTTIME . " AND (t1.exptime=0 OR t1.exptime >" . NV_CURRENTTIME . ") ORDER BY t2.weight ASC LIMIT 0 , " . $block_config['numrow'];
        $list = nv_db_cache( $sql, 'catid', $module );
        $html = "";
        $i = 1;
        if ( ! empty( $list ) )
        {
            if ( file_exists( NV_ROOTDIR . "/themes/" . $module_info['template'] . "/modules/news/block_blocknews.tpl" ) )
            {
                $block_theme = $module_info['template'];
            }
            else
            {
                $block_theme = "default";
            }
            $xtpl = new XTemplate( "block_blocknews.tpl", NV_ROOTDIR . "/themes/" . $block_theme . "/modules/news" );
            foreach ( $list as $l )
            {
                $arr_catid = explode( ',', $l['listcatid'] );
                $link = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module . "&amp;" . NV_OP_VARIABLE . "=" . $module_array_cat[$arr_catid[0]]['alias'] . "/" . $l['alias'] . "-" . $l['id'];
                $l['link'] = $link;
                $l['thumb'] = "";
                if ( ! empty( $l['homeimgthumb'] ) )
                {
                    $array_img = array();
                    $array_img = explode( "|", $l['homeimgthumb'] );
                    if ( $array_img[0] != "" and file_exists( NV_ROOTDIR . '/' . NV_FILES_DIR . '/' . $module . '/' . $array_img[0] ) )
                    {
                        $imgurl = NV_BASE_SITEURL . NV_FILES_DIR . '/' . $module . '/' . $array_img[0];
                        $l['thumb'] = $imgurl;
                    }
                }
                $xtpl->assign( 'ROW', $l );
                if ( ! empty( $l['thumb'] ) ) $xtpl->parse( 'main.loop.img' );
                $bg = ( $i % 2 == 0 ) ? "bg" : "";
                $xtpl->assign( 'bg', $bg );
                $i ++;
                $xtpl->parse( 'main.loop' );
            }
            $xtpl->assign( 'HTML_CONTENT', $html );
            $xtpl->parse( 'main' );
            return $xtpl->text( 'main' );
        }
    }
}

if ( defined( 'NV_SYSTEM' ) )
{
    global $site_mods, $module_name, $global_array_cat, $module_array_cat;
    $module = $block_config['module'];
    if ( isset( $site_mods[$module] ) )
    {
        if ( $module == $module_name )
        {
            $module_array_cat = $global_array_cat;
            unset( $module_array_cat[0] );
        }
        else
        {
            $module_array_cat = array();
            $module_data = $site_mods[$module]['module_data'];
            $sql = "SELECT catid, parentid, title, alias, viewcat, subcatid, numlinks, del_cache_time, description, inhome, keywords, who_view, groups_view FROM `" . NV_PREFIXLANG . "_" . $module_data . "_cat` ORDER BY `order` ASC";
            $list = nv_db_cache( $sql, 'catid', $module );
            foreach ( $list as $l )
            {
                $module_array_cat[$l['catid']] = $l;
                $module_array_cat[$l['catid']]['link'] = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module . "&amp;" . NV_OP_VARIABLE . "=" . $l['alias'];
            }
        }
        $content = nv_news_blocks( $block_config );
    }
}

?>