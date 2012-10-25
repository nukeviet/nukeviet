<?php

/**
 * @Project NUKEVIET 3.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2012 VINADES.,JSC. All rights reserved
 * @Createdate 3/9/2010 23:25
 */

if ( ! defined( 'NV_IS_MOD_NEWS' ) ) die( 'Stop!!!' );

/**
 * nv_block_headline()
 * 
 * @return
 */
function nv_block_headline ( )
{
    global $module_name, $module_data, $db, $my_head, $my_footer, $module_info, $module_file, $global_array_cat;
    
    $array_bid_content = array();
    
    $cache_file = NV_LANG_DATA . "_" . $module_name . "_block_headline_" . NV_CACHE_PREFIX . ".cache";
    
    if ( ( $cache = nv_get_cache( $cache_file ) ) != false )
    {
        $array_bid_content = unserialize( $cache );
    }
    else
    {
        $id = 0;
        $sql = "SELECT `bid`, `title`, `number` FROM `" . NV_PREFIXLANG . "_" . $module_data . "_block_cat` ORDER BY `weight` ASC LIMIT 0, 2";
        $result = $db->sql_query( $sql );
        while ( list( $bid, $titlebid, $numberbid ) = $db->sql_fetchrow( $result ) )
        {
            ++$id;
            $array_bid_content[$id] = array( "id" => $id, "bid" => $bid, "title" => $titlebid, "number" => $numberbid );
        }
        
        foreach ( $array_bid_content as $i => $array_bid )
        {
            $sql = "SELECT t1.id, t1.catid, t1.title, t1.alias, t1.homeimgfile, t1.homeimgalt FROM `" . NV_PREFIXLANG . "_" . $module_data . "_rows` as t1 INNER JOIN `" . NV_PREFIXLANG . "_" . $module_data . "_block` AS t2 ON t1.id = t2.id WHERE t2.bid= " . $array_bid['bid'] . " AND t1.status= 1 AND t1.inhome='1' ORDER BY t2.weight ASC LIMIT 0 , " . $array_bid['number'];
            $result = $db->sql_query( $sql );
            $array_content = array();
            while ( list( $id, $catid_i, $title, $alias, $homeimgfile, $homeimgalt ) = $db->sql_fetchrow( $result ) )
            {
                $link = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $global_array_cat[$catid_i]['alias'] . "/" . $alias . "-" . $id;
                $array_content[] = array( 'title' => $title, 'link' => $link, 'homeimgfile' => $homeimgfile, 'homeimgalt' => $homeimgalt );
            }
            $array_bid_content[$i]['content'] = $array_content;
        }
        $cache = serialize( $array_bid_content );
        nv_set_cache( $cache_file, $cache );
    }
    
    $xtpl = new XTemplate( "block_headline.tpl", NV_ROOTDIR . "/themes/" . $module_info['template'] . "/modules/" . $module_file );
    
    $xtpl->assign( 'PIX_IMG', NV_BASE_SITEURL . 'images/pix.gif' );
    $xtpl->assign( 'NV_BASE_SITEURL', NV_BASE_SITEURL );
    $xtpl->assign( 'TEMPLATE', $module_info['template'] );
    
    $images = array();
    if ( ! empty( $array_bid_content[1]['content'] ) )
    {
        $hot_news = $array_bid_content[1]['content'];
        $a = 0;
        foreach ( $hot_news as $hot_news_i )
        {
            if ( ! empty( $hot_news_i['homeimgfile'] ) and file_exists( NV_UPLOADS_REAL_DIR . '/' . $module_name . '/' . $hot_news_i['homeimgfile'] ) )
            {
                $images_url = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_name . '/' . $hot_news_i['homeimgfile'];
            }
            elseif ( nv_is_url( $hot_news_i['homeimgfile'] ) )
            {
                $images_url = $hot_news_i['homeimgfile'];
            }
            if ( ! empty( $images_url ) )
            {
                $hot_news_i['image_alt'] = ! empty( $hot_news_i['homeimgalt'] ) ? $hot_news_i['homeimgalt'] : $hot_news_i['title'];
                $hot_news_i['imgID'] = $a;
                $images[] = $images_url;
                
                $xtpl->assign( 'HOTSNEWS', $hot_news_i );
                $xtpl->parse( 'main.hots_news_img.loop' );
                ++$a;
            }
        }
        $xtpl->parse( 'main.hots_news_img' );
    }
    
    foreach ( $array_bid_content as $i => $array_bid )
    {
        $xtpl->assign( 'TAB_TITLE', $array_bid );
        $xtpl->parse( 'main.loop_tabs_title' );
        
        $content_bid = $array_bid['content'];
        if ( ! empty( $content_bid ) )
        {
            foreach ( $content_bid as $lastest )
            {
                $xtpl->assign( 'LASTEST', $lastest );
                $xtpl->parse( 'main.loop_tabs_content.content.loop' );
            }
            $xtpl->parse( 'main.loop_tabs_content.content' );
        }
        
        $xtpl->parse( 'main.loop_tabs_content' );
    }
    
    if ( empty( $my_head ) or ! preg_match( "/jquery\.imgpreload\.min\.js[^>]+>/", $my_head ) ) $my_head .= "<script type=\"text/javascript\" src=\"" . NV_BASE_SITEURL . "js/jquery/jquery.imgpreload.min.js\"></script>\n";
    
    $my_footer .= "<script type=\"text/javascript\" src=\"" . NV_BASE_SITEURL . "themes/" . $module_info['template'] . "/js/contentslider.js\"></script>\n";
    $my_footer .= "<script type=\"text/javascript\" src=\"" . NV_BASE_SITEURL . "js/ui/jquery.ui.core.min.js\"></script>\n";
    $my_footer .= "<script type=\"text/javascript\" src=\"" . NV_BASE_SITEURL . "js/ui/jquery.ui.tabs.min.js\"></script>\n";
    $my_footer .= "<script type=\"text/javascript\">\n//<![CDATA[\n";
    $my_footer .= '$(document).ready(function(){var b=["' . implode( '","', $images ) . '"];$.imgpreload(b,function(){for(var c=b.length,a=0;a<c;a++)$("#slImg"+a).attr("src",b[a]);featuredcontentslider.init({id:"slider1",contentsource:["inline",""],toc:"#increment",nextprev:["&nbsp;","&nbsp;"],revealtype:"click",enablefade:[true,0.2],autorotate:[true,3E3],onChange:function(){}});$("#tabs").tabs({ajaxOptions:{error:function(e,f,g,d){$(d.hash).html("Couldnt load this tab.")}}});$("#topnews").show()})});';
    $my_footer .= "\n//]]>\n</script>\n";
    $my_footer .= "<link rel=\"stylesheet\" type=\"text/css\" href=\"" . NV_BASE_SITEURL . "themes/" . $module_info['template'] . "/css/contentslider.css\" />\n";
    
    $xtpl->parse( 'main' );
    return $xtpl->text( 'main' );
}

$content = nv_block_headline();

?>