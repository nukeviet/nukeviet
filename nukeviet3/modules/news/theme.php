<?php
/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @copyright 2009
 * @createdate 12/31/2009 0:51
 */
if ( ! defined( 'NV_IS_MOD_NEWS' ) ) die( 'Stop!!!' );

function nv_category ( $cat )
{
    global $global_config, $module_name, $lang_module, $module_info, $module_file, $my_head;
    $my_head .= "<script type=\"text/javascript\"	src=\"" . NV_BASE_SITEURL . "themes/" . $module_info['template'] . "/js/jquery.category.news.js\"></script>\n";
    $my_head .= "<script type=\"text/javascript\">\n";
    $my_head .= "jQuery(document).ready(function(){\n";
    $my_head .= "$(\"#navmenu-v li\").hover(function(){\n";
    $my_head .= "	$(this).addClass(\"iehover\");\n";
    $my_head .= "	}, function(){\n";
    $my_head .= "	$(this).removeClass(\"iehover\");\n";
    $my_head .= "});\n";
    $my_head .= "});\n";
    $my_head .= "</script>\n";
    $my_head .= "<link rel=\"stylesheet\" type=\"text/css\"	href=\"" . NV_BASE_SITEURL . "themes/" . $module_info['template'] . "/css/category.news.css\" />\n";
    $xtpl = new XTemplate( "block_category.tpl", NV_ROOTDIR . "/themes/" . $module_info['template'] . "/modules/" . $module_file );
    $xtpl->assign( 'LANG', $lang_module );
    if ( ! empty( $cat ) )
    {
        foreach ( $cat as $item )
        {
            $xtpl->assign( 'CAT', $item );
            if ( ! empty( $item['sub'] ) )
            {
                foreach ( $item['sub'] as $sub )
                {
                    $xtpl->assign( 'SUB', $sub );
                    $xtpl->parse( 'main.item.sub.loop' );
                }
                $xtpl->parse( 'main.item.sub' );
            }
            $xtpl->parse( 'main.item' );
        }
        $xtpl->parse( 'main' );
        return $xtpl->text( 'main' );
    }
}

function nv_headline ( $hot_news, $lastest_news )
{
    global $global_config, $module_name, $module_file, $module_config, $module_info, $lang_module, $my_head;
    $my_head .= "<script type=\"text/javascript\" src=\"" . NV_BASE_SITEURL . "themes/" . $module_info['template'] . "/js/contentslider.js\"></script>\n";
    $my_head .= "<script type=\"text/javascript\" src=\"" . NV_BASE_SITEURL . "js/ui/jquery.ui.core.js\"></script>\n";
    $my_head .= "<script type=\"text/javascript\" src=\"" . NV_BASE_SITEURL . "js/ui/jquery.ui.widget.js\"></script>\n";
    $my_head .= "<script type=\"text/javascript\" src=\"" . NV_BASE_SITEURL . "js/ui/jquery.ui.tabs.js\"></script>\n";
    $my_head .= "<script type=\"text/javascript\">\n";
    $my_head .= "jQuery(document).ready(function(){\n";
    $my_head .= "	$(\"#tabs\").tabs({\n";
    $my_head .= "		ajaxOptions: {\n";
    $my_head .= "			error: function(xhr, status, index, anchor){\n";
    $my_head .= "			$(anchor.hash).html(\"Couldn't load this tab. We'll try to fix this as soon as possible.\");\n";
    $my_head .= "			}\n";
    $my_head .= "		}\n";
    $my_head .= "	});\n";
    $my_head .= "});\n";
    $my_head .= "</script>\n";
    $my_head .= "<link rel=\"stylesheet\" type=\"text/css\" href=\"" . NV_BASE_SITEURL . "themes/" . $module_info['template'] . "/css/contentslider.css\" />\n";
    $my_head .= "<link type=\"text/css\" rel=\"stylesheet\" href=\"" . NV_BASE_SITEURL . "themes/" . $module_info['template'] . "/css/jquery.ui.tabs.css\" media=\"all\" />\n";
    $xtpl = new XTemplate( "block_headline.tpl", NV_ROOTDIR . "/themes/" . $module_info['template'] . "/modules/" . $module_file );
    $xtpl->assign( 'LANG', $lang_module );
    if ( ! empty( $lastest_news ) )
    {
        foreach ( $lastest_news as $lastest )
        {
            $xtpl->assign( 'LASTEST', $lastest );
            $xtpl->parse( 'main.lastest_news.loop' );
            $xtpl->parse( 'main.lastest_news_img.loop' );
        }
        $xtpl->parse( 'main.lastest_news' );
        $xtpl->parse( 'main.lastest_news_img' );
    }
    if ( ! empty( $hot_news ) )
    {
        foreach ( $hot_news as $hotnews )
        {
            $xtpl->assign( 'HOT', $hotnews );
            $xtpl->parse( 'main.hot_news.loop' );
        }
        $xtpl->parse( 'main.hot_news' );
    }
    $xtpl->parse( 'main' );
    return $xtpl->text( 'main' );
}

function nv_news_block_bigtop ( $array_bigtop, $array_bigtop_other )
{
    global $global_config, $module_name, $module_file, $module_config, $module_info, $lang_module;
    $xtpl = new XTemplate( "block_bigtop.tpl", NV_ROOTDIR . "/themes/" . $module_info['template'] . "/modules/" . $module_file );
    $xtpl->assign( 'LANG', $lang_module );
    if ( ! empty( $array_bigtop ) )
    {
        if ( $array_bigtop['imagesizex'] > 0 )
        {
            $array_bigtop['imagesizey'] = round( ( 350 / $array_bigtop['imagesizex'] ) * $array_bigtop['imagesizey'] );
            $array_bigtop['imagesizex'] = 350;
        }
        $xtpl->assign( 'blocktop', $array_bigtop );
        if ( $array_bigtop['imagesizex'] > 0 )
        {
            $xtpl->parse( 'main.imgblocktop' );
        }
        $xtpl->parse( 'main.blocktop' );
        foreach ( $array_bigtop_other as $array_bigtop )
        {
            $xtpl->assign( 'blockother', $array_bigtop );
            $xtpl->parse( 'main.other.otherloop' );
        }
    }
    $xtpl->parse( 'main.other' );
    $xtpl->assign( 'UPLOADS_DIR', NV_UPLOADS_DIR );
    $xtpl->assign( 'BASE_SITEURL', NV_BASE_SITEURL );
    $xtpl->parse( 'main' );
    return $xtpl->text( 'main' );
}

function block_news ( $array_block_news )
{
    global $global_config, $module_name, $module_file, $module_config, $module_info;
    $blockwidth = $module_config[$module_name]['blockwidth'];
    
    $xtpl = new XTemplate( "block_news.tpl", NV_ROOTDIR . "/themes/" . $module_info['template'] . "/modules/" . $module_file );
    $a = 1;
    foreach ( $array_block_news as $array_news )
    {
        if ( $array_news['width'] > $blockwidth )
        {
            $array_news['height'] = round( ( $blockwidth / $array_news['width'] ) * $array_news['height'] );
            $array_news['width'] = $blockwidth;
        }
        $xtpl->assign( 'blocknews', $array_news );
        if ( $array_news['width'] > 0 )
        {
            $xtpl->parse( 'main.newloop.imgblock' );
        }
        $xtpl->parse( 'main.newloop' );
        $xtpl->assign('BACKGROUND',($a%2) ? 'bg ' : '');
        $a++;
    }
    $xtpl->parse( 'main' );
    return $xtpl->text( 'main' );
}

function viewcat_page_new ( $array_catpage, $array_cat_other )
{
    global $global_config, $module_name, $module_file, $lang_module, $arr_cat_title, $module_config, $module_info;
    $xtpl = new XTemplate( "viewcat_page.tpl", NV_ROOTDIR . "/themes/" . $module_info['template'] . "/modules/" . $module_file );
    $xtpl->assign( 'LANG', $lang_module );
    $count = count( $arr_cat_title );
    foreach ( $arr_cat_title as $key => $arr_cat_title_i )
    {
        $xtpl->clear_autoreset();
        $xtpl->assign( 'BREAKCOLUMN', $arr_cat_title_i );
        $xtpl->set_autoreset();
        if ( $key + 1 < $count )
        {
            $xtpl->parse( 'main.breakcolumn.arrow' );
        }
        $xtpl->parse( 'main.breakcolumn' );
    }
    $a = 0;
    foreach ( $array_catpage as $array_row_i )
    {
        $array_row_i['publtime'] = nv_date( 'd-m-Y h:i:s A', $array_row_i['publtime'] );
        $xtpl->clear_autoreset();
        $xtpl->assign( 'CONTENT', $array_row_i );
        if ( defined( 'NV_IS_MODADMIN' ) )
        {
            $xtpl->assign( 'ADMINLINK', nv_link_edit_page( $array_row_i['id'] ) . "&nbsp;-&nbsp;" . nv_link_delete_page( $array_row_i['id'] ) );
            $xtpl->parse( 'main.viewcatloop.adminlink' );
        }
        if ( $array_row_i['imghome'] != "" )
        {
            if ( file_exists( NV_UPLOADS_REAL_DIR . '/' . $module_name . '/' . $array_row_i['imghome'] ) )
            {
                $size = @getimagesize( NV_UPLOADS_REAL_DIR . '/' . $module_name . '/' . $array_row_i['imghome'] );
                if ( $size[0] > 0 )
                {
                    $homewidth = $module_config[$module_name]['homewidth'];
                    $size[1] = round( ( $homewidth / $size[0] ) * $size[1] );
                    $size[0] = $homewidth;
                    $xtpl->assign( 'IMGWIDTH1', $size[0] );
                    $xtpl->assign( 'IMGHEIGHT1', $size[1] );
                    $xtpl->assign( 'HOMEIMG1', NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_name . '/' . $array_row_i['imghome'] );
                    $xtpl->assign( 'HOMEIMGALT1', ! empty( $array_row_i['homeimgalt'] ) ? $array_row_i['homeimgalt'] : $array_row_i['title'] );
                    $xtpl->parse( 'main.viewcatloop.image' );
                }
            }
        }
        $xtpl->set_autoreset();
        $xtpl->parse( 'main.viewcatloop' );
        $a ++;
    }
    if ( ! empty( $array_cat_other ) )
    {
        $xtpl->assign( 'ORTHERNEWS', $lang_module['other'] );
        foreach ( $array_cat_other as $array_row_i )
        {
            $array_row_i['publtime'] = nv_date( "d/m/Y", $array_row_i['publtime'] );
            $xtpl->assign( 'RELATED', $array_row_i );
            $xtpl->parse( 'main.related.loop' );
        }
        $xtpl->parse( 'main.related' );
    }
    $xtpl->parse( 'main' );
    return $xtpl->text( 'main' );
}

function viewcat_top ( $array_catcontent )
{
    global $global_config, $module_name, $module_file, $global_array_cat, $lang_module, $arr_cat_title, $module_config, $module_info;
    $xtpl = new XTemplate( "viewcat_top.tpl", NV_ROOTDIR . "/themes/" . $module_info['template'] . "/modules/" . $module_file );
    $xtpl->assign( 'LANG', $lang_module );
    //Breakcolumn
    if ( ! empty( $arr_cat_title ) )
    {
        $count = count( $arr_cat_title );
        $a = 1;
        foreach ( $arr_cat_title as $arr_cat_title_i )
        {
            $xtpl->clear_autoreset();
            $xtpl->assign( 'BREAKCOLUMN', $arr_cat_title_i );
            if ( $a < $count )
            {
                $xtpl->parse( 'main.breakcolumn.loop.arrow' );
            }
            $xtpl->set_autoreset();
            $xtpl->parse( 'main.breakcolumn.loop' );
            $a ++;
        }
        $xtpl->parse( 'main.breakcolumn' );
    }
    //Breakcolumn
    

    // Cac bai viet phan dau
    if ( ! empty( $array_catcontent ) )
    {
        foreach ( $array_catcontent as $key => $array_catcontent_i )
        {
            $array_catcontent_i['publtime'] = nv_date( 'd-m-Y h:i:s A', $array_catcontent_i['publtime'] );
            $xtpl->assign( 'CONTENT', $array_catcontent_i );
            if ( $key == 0 )
            {
                if ( $array_catcontent_i['imghome'] != "" )
                {
                    if ( file_exists( NV_UPLOADS_REAL_DIR . '/' . $module_name . '/' . $array_catcontent_i['imghome'] ) )
                    {
                        $size = @getimagesize( NV_UPLOADS_REAL_DIR . '/' . $module_name . '/' . $array_catcontent_i['imghome'] );
                        if ( $size[0] > 0 )
                        {
                            $homewidth = $module_config[$module_name]['homewidth'];
                            $size[1] = round( ( $homewidth / $size[0] ) * $size[1] );
                            $size[0] = $homewidth;
                            $xtpl->assign( 'IMGWIDTH0', $size[0] );
                            $xtpl->assign( 'IMGHEIGHT0', $size[1] );
                            $xtpl->assign( 'HOMEIMG0', NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_name . '/' . $array_catcontent_i['imghome'] );
                            $xtpl->assign( 'HOMEIMGALT0', $array_catcontent_i['homeimgalt'] );
                            $xtpl->parse( 'main.catcontent.image' );
                        }
                    
                    }
                }
                if ( defined( 'NV_IS_MODADMIN' ) )
                {
                    $xtpl->assign( 'ADMINLINK', nv_link_edit_page( $array_catcontent_i['id'] ) . "&nbsp;-&nbsp;" . nv_link_delete_page( $array_catcontent_i['id'] ) );
                    $xtpl->parse( 'main.catcontent.adminlink' );
                }
                $xtpl->parse( 'main.catcontent' );
            }
            else
            {
                $xtpl->parse( 'main.catcontentloop' );
            }
        }
    }
    // Het cac bai viet phan dau
    $xtpl->parse( 'main' );
    return $xtpl->text( 'main' );
}

function viewsubcat_main ( $viewcat, $array_cat )
{
    global $global_config, $module_name, $module_file, $global_array_cat, $lang_module, $arr_cat_title, $module_config, $module_info;
    $xtpl = new XTemplate( $viewcat . ".tpl", NV_ROOTDIR . "/themes/" . $module_info['template'] . "/modules/" . $module_file );
    $xtpl->assign( 'LANG', $lang_module );
    // Hien thi cac chu de con
    foreach ( $array_cat as $key => $array_row_i )
    {
        if ( isset( $array_cat[$key]['content'] ) )
        {
            $xtpl->assign( 'CAT', $array_row_i );
            $catid = intval( $array_row_i['catid'] );
            if ( $array_row_i['subcatid'] != "" )
            {
                $exl = 0;
                $arrsubcat_s = explode( ",", $array_row_i['subcatid'] );
                foreach ( $arrsubcat_s as $subcatid_i )
                {
                    $xtpl->clear_autoreset();
                    if ( $exl < 3 )
                    {
                        $xtpl->assign( 'SUBCAT', $global_array_cat[$subcatid_i] );
                        $xtpl->parse( 'main.listcat.subcatloop' );
                        $xtpl->set_autoreset();
                    }
                    else
                    {
                        $more = array( 
                            'title' => $lang_module['more'], 'link' => $global_array_cat[$catid]['link'] 
                        );
                        $xtpl->assign( 'MORE', $more );
                        $xtpl->parse( 'main.listcat.subcatmore' );
                        $xtpl->set_autoreset();
                        break;
                    }
                    $exl ++;
                }
            }
            $a = 0;
            foreach ( $array_cat[$key]['content'] as $array_row_i )
            {
                $array_row_i['publtime'] = nv_date( 'd-m-Y h:i:s A', $array_row_i['publtime'] );
                $a ++;
                if ( $a == 1 )
                {
                    $xtpl->assign( 'CONTENT', $array_row_i );
                    if ( $array_row_i['imghome'] != "" )
                    {
                        if ( file_exists( NV_UPLOADS_REAL_DIR . '/' . $module_name . '/' . $array_row_i['imghome'] ) )
                        {
                            $size = @getimagesize( NV_UPLOADS_REAL_DIR . '/' . $module_name . '/' . $array_row_i['imghome'] );
                            if ( $size[0] > 0 )
                            {
                                $homewidth = $module_config[$module_name]['homewidth'];
                                $size[1] = round( ( $homewidth / $size[0] ) * $size[1] );
                                $size[0] = $homewidth;
                                $xtpl->assign( 'IMGWIDTH', $size[0] );
                                $xtpl->assign( 'IMGHEIGHT', $size[1] );
                                $xtpl->assign( 'HOMEIMG', NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_name . '/' . $array_row_i['imghome'] );
                                $xtpl->assign( 'HOMEIMGALT', ! empty( $array_row_i['homeimgalt'] ) ? $array_row_i['homeimgalt'] : $array_row_i['title'] );
                                $xtpl->parse( 'main.listcat.image' );
                            }
                        }
                    }
                    if ( defined( 'NV_IS_MODADMIN' ) )
                    {
                        $xtpl->assign( 'ADMINLINK', nv_link_edit_page( $array_row_i['id'] ) . "&nbsp;-&nbsp;" . nv_link_delete_page( $array_row_i['id'] ) );
                        $xtpl->parse( 'main.listcat.adminlink' );
                    }
                }
                else
                {
                    $xtpl->assign( 'OTHER', $array_row_i );
                    $xtpl->parse( 'main.listcat.related.loop' );
                }
                if ( $a > 1 )
                {
                    if ( $viewcat == "viewcat_main_right" )
                    {
                        $xtpl->assign( 'BORDER', 'border_r ' );
                    }
                    elseif ( $viewcat == "viewcat_main_left" )
                    {
                        $xtpl->assign( 'BORDER', 'border_l ' );
                    }
                    else
                    {
                        $xtpl->assign( 'BORDER', 'border_b ' );
                    }
                    $xtpl->assign( 'WCT', 'fixedwidth ' );
                }
                else
                {
                    $xtpl->assign( 'WCT', 'fullwidth noborder ' );
                }
                $xtpl->set_autoreset();
            }
            if ( $a > 1 )
            {
                $xtpl->parse( 'main.listcat.related' );
            }
            $xtpl->parse( 'main.listcat' );
        }
    }
    // het Hien thi cac chu de con
    $xtpl->parse( 'main' );
    return $xtpl->text( 'main' );
}

function viewcat_two_column ( $array_content, $array_catpage )
{
    global $global_config, $module_name, $module_file, $arr_cat_title, $module_config, $module_info, $home;
    $xtpl = new XTemplate( "viewcat_two_column.tpl", NV_ROOTDIR . "/themes/" . $module_info['template'] . "/modules/" . $module_file );
    //Breakcolumn
    if ( ! empty( $arr_cat_title ) )
    {
        foreach ( $arr_cat_title as $arr_cat_title_i )
        {
            $xtpl->assign( 'SUBCAT', $arr_cat_title_i );
            if ( $home != 1 )
            {
                $xtpl->parse( 'main.breakcolumn.loop' );
            }
        }
        $xtpl->parse( 'main.breakcolumn' );
    }
    //End Breakcolumn
    //Bai viet o phan dau
    if ( ! empty( $array_content ) )
    {
        foreach ( $array_content as $key => $array_content_i )
        {
            $xtpl->assign( 'NEWSTOP', $array_content_i );
            if ( $key == 0 )
            {
                if ( $array_content_i['imgthumb'] != "" )
                {
                    if ( file_exists( NV_UPLOADS_REAL_DIR . '/' . $module_name . '/' . $array_content_i['imghome'] ) )
                    {
                        $size = @getimagesize( NV_UPLOADS_REAL_DIR . '/' . $module_name . '/' . $array_content_i['imghome'] );
                        if ( $size[0] > 0 )
                        {
                            $homewidth = $module_config[$module_name]['homewidth'];
                            $size[1] = round( ( $homewidth / $size[0] ) * $size[1] );
                            $size[0] = $homewidth;
                            
                            $xtpl->assign( 'IMGWIDTH0', $size[0] );
                            $xtpl->assign( 'IMGHEIGHT0', $size[1] );
                            $xtpl->assign( 'HOMEIMG0', NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_name . '/' . $array_content_i['imgthumb'] );
                            $xtpl->assign( 'HOMEIMGALT0', $array_content_i['homeimgalt'] );
                            $xtpl->parse( 'main.catcontent.content.image' );
                        }
                    }
                }
                if ( defined( 'NV_IS_MODADMIN' ) )
                {
                    $xtpl->assign( 'ADMINLINK', nv_link_edit_page( $array_content_i['id'] ) . "&nbsp;-&nbsp;" . nv_link_delete_page( $array_content_i['id'] ) );
                    $xtpl->parse( 'main.catcontent.content.adminlink' );
                }
                $xtpl->parse( 'main.catcontent.content' );
            }
            else
            {
                $xtpl->parse( 'main.catcontent.other' );
            }
        }
        $xtpl->parse( 'main.catcontent' );
    }
    //Het Bai viet o phan dau
    

    //Theo chu de
    $a = 0;
    foreach ( $array_catpage as $key => $array_catpage_i )
    {
        $number_content = isset( $array_catpage[$key]['content'] ) ? count( $array_catpage[$key]['content'] ) : 0;
        if ( $number_content > 0 )
        {
            $xtpl->assign( 'CAT', $array_catpage_i );
            $xtpl->assign( 'ID', $a );
            $xtpl->assign( 'LAST', $a % 2 ? ' last' : '' );
            $xtpl->assign( 'BORDER', $number_content > 1 ? ' border_b' : '' );
            $k = 0;
            $array_content_i = $array_catpage_i['content'][0];
            $array_content_i['hometext'] = nv_clean60( $array_content_i['hometext'], 300 );
            $xtpl->assign( 'CONTENT', $array_content_i );
            if ( $array_content_i['imghome'] != "" )
            {
                if ( file_exists( NV_UPLOADS_REAL_DIR . '/' . $module_name . '/' . $array_content_i['imghome'] ) )
                {
                    $size = @getimagesize( NV_UPLOADS_REAL_DIR . '/' . $module_name . '/' . $array_content_i['imghome'] );
                    if ( $size[0] > 0 )
                    {
                        $homewidth = $module_config[$module_name]['homewidth'];
                        $size[1] = round( ( $homewidth / $size[0] ) * $size[1] );
                        $size[0] = $homewidth;
                        $xtpl->assign( 'IMGWIDTH01', $size[0] );
                        $xtpl->assign( 'IMGHEIGHT01', $size[1] );
                        $xtpl->assign( 'HOMEIMG01', NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_name . '/' . $array_content_i['imghome'] );
                        $xtpl->assign( 'HOMEIMGALT01', ! empty( $array_content_i['homeimgalt'] ) ? $array_content_i['homeimgalt'] : $array_content_i['title'] );
                        $xtpl->parse( 'main.loopcat.content.image' );
                    }
                }
            }
            if ( defined( 'NV_IS_MODADMIN' ) )
            {
                $xtpl->assign( 'ADMINLINK', nv_link_edit_page( $array_content_i['id'] ) . "&nbsp;-&nbsp;" . nv_link_delete_page( $array_content_i['id'] ) );
                $xtpl->parse( 'main.loopcat.content.adminlink' );
            }
            $xtpl->parse( 'main.loopcat.content' );
            if ( $number_content > 1 )
            {
                for ( $index = 1; $index < $number_content; $index ++ )
                {
                    $xtpl->assign( 'CONTENT', $array_catpage_i['content'][$index] );
                    $xtpl->parse( 'main.loopcat.other' );
                }
            }
            $xtpl->parse( 'main.loopcat' );
            $a ++;
        }
    }
    //Theo chu de
    $xtpl->parse( 'main' );
    return $xtpl->text( 'main' );
}

function detail_theme ( $news_contents, $related_new_array, $related_array, $topic_array, $commentenable )
{
    global $global_config, $module_info, $lang_module, $module_name, $module_file, $module_config, $global_array_cat, $arr_cat_title, $my_head, $lang_global, $user_info, $admin_info, $catid;
    
    $my_head .= "<script type=\"text/javascript\" src=\"" . NV_BASE_SITEURL . "js/star-rating/jquery.rating.pack.js\"></script>\n";
    $my_head .= "<script src='" . NV_BASE_SITEURL . "js/star-rating/jquery.MetaData.js' type=\"text/javascript\" language=\"javascript\"></script>\n";
    $my_head .= "<link href='" . NV_BASE_SITEURL . "js/star-rating/jquery.rating.css' type=\"text/css\" rel=\"stylesheet\"/>\n";
    
    $xtpl = new XTemplate( "detail.tpl", NV_ROOTDIR . "/themes/" . $module_info['template'] . "/modules/" . $module_file );
    $xtpl->assign( 'LANG', $lang_module );
    $news_contents['addtime'] = nv_date( "d-m-Y h:i:s", $news_contents['addtime'] );
    $xtpl->assign( 'NEWSID', $news_contents['id'] );
    $xtpl->assign( 'NEWSCHECKSS', $news_contents['newscheckss'] );
    $xtpl->assign( 'DETAIL', $news_contents );
    $a = 1;
    $count = count( $arr_cat_title );
    foreach ( $arr_cat_title as $key => $arr_cat_title_i )
    {
        $xtpl->clear_autoreset();
        $xtpl->assign( 'SUB_CAT', $arr_cat_title_i );
        if ( $a < $count )
        {
            $xtpl->parse( 'main.breakcolumn.arrow' );
        }
        $xtpl->set_autoreset();
        $xtpl->parse( 'main.breakcolumn' );
        $a ++;
    }
    if ( $news_contents['allowed_send'] == 1 )
    {
        $xtpl->assign( 'URL_SENDMAIL', NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=sendmail&amp;catid=" . $catid . "&amp;id=" . $news_contents['id'] . "" );
        $xtpl->parse( 'main.allowed_send' );
    }
    if ( $news_contents['allowed_print'] == 1 )
    {
        $xtpl->assign( 'URL_PRINT', NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=print&amp;catid=" . $catid . "&amp;id=" . $news_contents['id'] . "" );
        $xtpl->parse( 'main.allowed_print' );
    }
    if ( $news_contents['allowed_save'] == 1 )
    {
        $xtpl->assign( 'URL_SAVEFILE', NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=savefile&amp;catid=" . $catid . "&amp;id=" . $news_contents['id'] . "" );
        $xtpl->parse( 'main.allowed_save' );
    }
    if ( $news_contents['allowed_rating'] == 1 )
    {
        $xtpl->assign( 'LANGSTAR', $news_contents['langstar'] );
        $xtpl->assign( 'STRINGRATING', $news_contents['stringrating'] );
        $xtpl->assign( 'NUMBERRATING', $news_contents['numberrating'] );
        if ( $news_contents['disablerating'] == 1 )
        {
            $xtpl->parse( 'main.allowed_rating.disablerating' );
        }
        $xtpl->parse( 'main.allowed_rating' );
    }
    
    if ( $news_contents['showhometext'] )
    {
        if ( ! empty( $news_contents['image']['width'] ) )
        {
            if ( $news_contents['image']['position'] == 1 )
            {
                $xtpl->parse( 'main.showhometext.imgthumb' );
            }
            elseif ( $news_contents['image']['position'] == 2 )
            {
                $xtpl->parse( 'main.showhometext.imgfull' );
            }
        }
        $xtpl->parse( 'main.showhometext' );
    }
    
    if ( ! empty( $news_contents['author'] ) or ! empty( $news_contents['source'] ) )
    {
        if ( ! empty( $news_contents['author'] ) )
        {
            $xtpl->parse( 'main.author.name' );
        }
        if ( ! empty( $news_contents['source'] ) )
        {
            $xtpl->parse( 'main.author.source' );
        }
        $xtpl->parse( 'main.author' );
    }
    if ( $news_contents['copyright'] == 1 )
    {
        if ( ! empty( $module_config[$module_name]['copyright'] ) )
        {
            $xtpl->assign( 'COPYRIGHT', $module_config[$module_name]['copyright'] );
            $xtpl->parse( 'main.copyright' );
        }
    }
    
    if ( ! empty( $news_contents['keywords'] ) )
    {
        $news_contents['keywords'] = explode( ',', $news_contents['keywords'] );
        $count = count( $news_contents['keywords'] );
        foreach ( $news_contents['keywords'] as $i => $value )
        {
            $value = trim( $value );
            $value = trim( $value );
            $keyword = NV_BASE_SITEURL . "?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=search&amp;q=" . urlencode( $value ) . "&amp;mod=all";
            $xtpl->assign( 'KEYWORD', $value );
            $xtpl->assign( 'LINK_KEYWORDS', $keyword );
            $xtpl->assign( 'SLASH', ( ( $count - 1 ) == $i ) ? '' : ', ' );
            $xtpl->parse( 'main.keywords.loop' );
        }
        $xtpl->parse( 'main.keywords' );
    }
    
    if ( defined( 'NV_IS_MODADMIN' ) )
    {
        $xtpl->assign( 'ADMINLINK', nv_link_edit_page( $news_contents['id'] ) . "&nbsp;-&nbsp;" . nv_link_delete_page( $news_contents['id'] ) );
        $xtpl->parse( 'main.adminlink' );
    }
    
    if ( $commentenable )
    {
        $xtpl->assign( 'COMMENTCONTENT', $news_contents['comment'] );
        if ( defined( 'NV_IS_ADMIN' ) )
        {
            $xtpl->assign( 'NAME', $admin_info['full_name'] );
            $xtpl->assign( 'EMAIL', $admin_info['email'] );
        }
        elseif ( defined( 'NV_IS_USER' ) )
        {
            $xtpl->assign( 'NAME', $user_info['full_name'] );
            $xtpl->assign( 'EMAIL', $user_info['email'] );
        }
        else
        {
            $xtpl->assign( 'NAME', "" );
            $xtpl->assign( 'EMAIL', "" );
        }
        $xtpl->assign( 'N_CAPTCHA', $lang_global['securitycode'] );
        $xtpl->assign( 'CAPTCHA_REFRESH', $lang_global['captcharefresh'] );
        $xtpl->assign( 'GFX_NUM', NV_GFX_NUM );
        $xtpl->assign( 'GFX_WIDTH', NV_GFX_WIDTH );
        $xtpl->assign( 'GFX_WIDTH', NV_GFX_WIDTH );
        $xtpl->assign( 'GFX_HEIGHT', NV_GFX_HEIGHT );
        $xtpl->assign( 'CAPTCHA_REFR_SRC', NV_BASE_SITEURL . "images/refresh.png" );
        $xtpl->assign( 'IMGSHOWCOMMENT', NV_BASE_SITEURL . "themes/" . $module_info['template'] . "/images/" . $module_file . "/comment.png" );
        $xtpl->assign( 'IMGADDCOMMENT', NV_BASE_SITEURL . "themes/" . $module_info['template'] . "/images/" . $module_file . "/comment_add.png" );
        $xtpl->assign( 'SRC_CAPTCHA', NV_BASE_SITEURL . "?scaptcha=captcha" );
        $xtpl->parse( 'main.comment' );
    }
    if ( ! empty( $related_new_array ) )
    {
        foreach ( $related_new_array as $key => $related_new_array_i )
        {
            $xtpl->assign( 'RELATED_NEW', $related_new_array_i );
            $xtpl->parse( 'main.related_new.loop' );
        }
        unset( $key );
        $xtpl->parse( 'main.related_new' );
    }
    
    if ( ! empty( $related_array ) )
    {
        foreach ( $related_array as $related_array_i )
        {
            $xtpl->assign( 'RELATED', $related_array_i );
            $xtpl->parse( 'main.related.loop' );
        }
        $xtpl->parse( 'main.related' );
    }
    if ( ! empty( $topic_array ) )
    {
        foreach ( $topic_array as $key => $topic_array_i )
        {
            $xtpl->assign( 'TOPIC', $topic_array_i );
            $xtpl->parse( 'main.topic.loop' );
        }
        $xtpl->parse( 'main.topic' );
    }
    $xtpl->parse( 'main' );
    return $xtpl->text( 'main' );
}

function no_permission ( $func_who_view )
{
    global $module_info, $module_file, $global_config, $lang_global, $lang_module, $db, $module_name;
    $xtpl = new XTemplate( "detail.tpl", NV_ROOTDIR . "/themes/" . $module_info['template'] . "/modules/" . $module_file );
    if ( $func_who_view == 1 )
    {
        $no_permission = $lang_module['member_view'];
    }
    elseif ( $func_who_view == 2 )
    {
        $no_permission = $lang_module['admin_view'];
    }
    elseif ( $func_who_view == 3 )
    {
        $no_permission = $lang_module['group_view'];
    }
    $xtpl->assign( 'NO_PERMISSION', $no_permission );
    $xtpl->parse( 'no_permission' );
    return $xtpl->text( 'no_permission' );
}

function topic_theme ( $topic_array, $topic_other_array )
{
    global $global_config, $module_info, $module_name, $module_file, $topictitle, $topicalias, $module_config;
    $xtpl = new XTemplate( "topic.tpl", NV_ROOTDIR . "/themes/" . $module_info['template'] . "/modules/" . $module_file );
    $xtpl->assign( 'TOPIC_TOP_TITLE', $topictitle );
    $xtpl->assign( 'TOPIC_TOP_LINK', "" . NV_BASE_SITEURL . "?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=topic-" . $topicalias . "" );
    if ( ! empty( $topic_array ) )
    {
        foreach ( $topic_array as $topic_array_i )
        {
            $xtpl->assign( 'TOPIC', $topic_array_i );
            $xtpl->assign( 'TIME', date( "H:i", $topic_array_i['publtime'] ) );
            $xtpl->assign( 'DATE', date( "d/m/Y", $topic_array_i['publtime'] ) );
            if ( ! empty( $topic_array_i['src'] ) )
            {
                $xtpl->parse( 'main.topic.homethumb' );
            }
            if ( defined( 'NV_IS_MODADMIN' ) )
            {
                $xtpl->assign( 'ADMINLINK', nv_link_edit_page( $topic_array_i['id'] ) . "&nbsp;-&nbsp;" . nv_link_delete_page( $topic_array_i['id'] ) );
                $xtpl->parse( 'main.topic.adminlink' );
            }
            
            $xtpl->parse( 'main.topic' );
        }
    }
    if ( ! empty( $topic_other_array ) )
    {
        foreach ( $topic_other_array as $topic_other_array_i )
        {
            $xtpl->assign( 'TOPIC_OTHER', $topic_other_array_i );
            $xtpl->parse( 'main.other.loop' );
        }
        $xtpl->parse( 'main.other' );
    }
    $xtpl->parse( 'main' );
    return $xtpl->text( 'main' );
}

function comment_theme ( $comment_array )
{
    $comment = "";
    global $global_config, $module_info, $module_name, $module_file, $topictitle, $topicalias, $module_config;
    $xtpl = new XTemplate( "comment.tpl", NV_ROOTDIR . "/themes/" . $module_info['template'] . "/modules/" . $module_file );
    $k = 0;
    foreach ( $comment_array['comment'] as $comment_array_i )
    {
        $xtpl->assign( 'TIME', date( "d/m/Y H:i", $comment_array_i['post_time'] ) );
        $xtpl->assign( 'NAME', $comment_array_i['post_name'] );
        if ( $module_config[$module_name]['emailcomm'] and ! empty( $comment_array_i['post_email'] ) )
        {
            $xtpl->assign( 'EMAIL', $comment_array_i['post_email'] );
            $xtpl->parse( 'main.detail.emailcomm' );
        }
        $xtpl->assign( 'CONTENT', $comment_array_i['content'] );
        $xtpl->assign( 'BG', ( $k % 2 ) ? " bg" : "" );
        $xtpl->parse( 'main.detail' );
        $k ++;
    }
    if ( ! empty( $comment_array['page'] ) )
    {
        $xtpl->assign( 'PAGE', $comment_array['page'] );
    }
    $xtpl->parse( 'main' );
    return $xtpl->text( 'main' );
}

function sendmail_themme ( $sendmail )
{
    global $module_name, $module_info, $module_file, $global_config, $lang_module, $lang_global;
    $script = nv_html_site_js();
    $script .= "<script type=\"text/javascript\" src=\"" . NV_BASE_SITEURL . "js/jquery/jquery.validate.js\"></script>\n";
    $script .= "<script type=\"text/javascript\">\n";
    $script .= "          $(document).ready(function(){\n";
    $script .= "            $(\"#sendmailForm\").validate();\n";
    $script .= "          });\n";
    $script .= "</script>\n";
    $script .= "<script type=\"text/javascript\" src=\"" . NV_BASE_SITEURL . "js/jquery/jquery.cookie.js\"></script>\n";
    $script .= "<script type=\"text/javascript\">\n";
    $script .= "$(document).ready(function(){remember( '[name=name],[name=youremail],[name=email],[name=content]' );});\n";
    $script .= "function remember( selector ){\n";
    $script .= "	$(selector).each(function(){\n";
    $script .= "		//if this item has been cookied, restore it\n";
    $script .= "		var name = $(this).attr('name');\n";
    $script .= "		if( $.cookie( name ) ){\n";
    $script .= "			$(this).val( $.cookie(name) );\n";
    $script .= "		}\n";
    $script .= "		//assign a change function to the item to cookie it\n";
    $script .= "		$(this).change(function(){\n";
    $script .= "			$.cookie(name, $(this).val(), { path: '/', expires: 365 });\n";
    $script .= "		});\n";
    $script .= "	});\n";
    $script .= "}\n";
    $script .= "</script>\n";
    
    $sendmail['script'] = $script;
    $xtpl = new XTemplate( "sendmail.tpl", NV_ROOTDIR . "/themes/" . $module_info['template'] . "/modules/" . $module_file );
    $xtpl->assign( 'SENDMAIL', $sendmail );
    $xtpl->assign( 'LANG', $lang_module );
    $xtpl->assign( 'NV_BASE_SITEURL', NV_BASE_SITEURL );
    $xtpl->assign( 'GFX_NUM', NV_GFX_NUM );
    if ( $global_config['gfx_chk'] == 1 )
    {
        $xtpl->assign( 'CAPTCHA_REFRESH', $lang_global['captcharefresh'] );
        $xtpl->assign( 'CAPTCHA_REFR_SRC', NV_BASE_SITEURL . "images/refresh.png" );
        $xtpl->assign( 'N_CAPTCHA', $lang_global['securitycode'] );
        $xtpl->assign( 'GFX_WIDTH', NV_GFX_WIDTH );
        $xtpl->assign( 'GFX_HEIGHT', NV_GFX_HEIGHT );
        $xtpl->parse( 'main.content.captcha' );
    }
    $xtpl->parse( 'main.content' );
    if ( ! empty( $sendmail['result'] ) )
    {
        $xtpl->assign( 'RESULT', $sendmail['result'] );
        $xtpl->parse( 'main.result' );
        if ( $sendmail['result']['check'] == true )
        {
            $xtpl->parse( 'main.close' );
        }
    }
    $xtpl->parse( 'main' );
    return $xtpl->text( 'main' );
}

function news_print ( $result )
{
    global $module_name, $module_info, $module_file, $global_config, $lang_module;
    $xtpl = new XTemplate( "print.tpl", NV_ROOTDIR . "/themes/" . $module_info['template'] . "/modules/" . $module_file );
    $xtpl->assign( 'CONTENT', $result );
    $xtpl->assign( 'LANG', $lang_module );
    if ( ! empty( $result['image']['width'] ) )
    {
        if ( $result['image']['position'] == 1 )
        {
            if ( ! empty( $result['image']['note'] ) )
            {
                $xtpl->parse( 'main.image.note' );
            }
            $xtpl->parse( 'main.image' );
        }
        elseif ( $result['image']['position'] == 2 )
        {
            if ( $result['image']['note'] > 0 )
            {
                $xtpl->parse( 'main.imagefull.note' );
            }
            $xtpl->parse( 'main.imagefull' );
        }
    }
    if ( $result['copyright'] == 1 )
    {
        $xtpl->parse( 'main.copyright' );
    }
    if ( ! empty( $result['author'] ) or ! empty( $result['source'] ) )
    {
        if ( ! empty( $result['author'] ) )
        {
            $xtpl->parse( 'main.author.name' );
        }
        if ( ! empty( $result['source'] ) )
        {
            $xtpl->parse( 'main.author.source' );
        }
        $xtpl->parse( 'main.author' );
    }
    $xtpl->parse( 'main' );
    return $xtpl->text( 'main' );
}

//// search.php
function search_theme ( $key, $check_num, $date_array )
{
    global $module_name, $module_info, $module_file, $global_config, $lang_module, $module_name;
    $xtpl = new XTemplate( "search.tpl", NV_ROOTDIR . "/themes/" . $module_info['template'] . "/modules/" . $module_file );
    $base_url_site = NV_BASE_SITEURL . "?";
    $xtpl->assign( 'LANG', $lang_module );
    $xtpl->assign( 'NV_LANG_VARIABLE', NV_LANG_VARIABLE );
    $xtpl->assign( 'NV_LANG_DATA', NV_LANG_DATA );
    $xtpl->assign( 'NV_NAME_VARIABLE', NV_NAME_VARIABLE );
    $xtpl->assign( 'MODULE_NAME', $module_name );
    $xtpl->assign( 'BASE_URL_SITE', $base_url_site );
    $xtpl->assign( 'TO_DATE', $date_array['to_date'] );
    $xtpl->assign( 'FROM_DATE', $date_array['from_date'] );
    $xtpl->assign( 'KEY', $key );
    $xtpl->assign( 'NV_OP_VARIABLE', NV_OP_VARIABLE );
    $xtpl->assign( 'OP_NAME', 'search' );
    for ( $i = 0; $i <= 3; $i ++ )
    {
        if ( $check_num == $i ) $xtpl->assign( 'CHECK' . $i, "selected=\"selected\"" );
        else $xtpl->assign( 'CHECK' . $i, "" );
    }
    $xtpl->parse( 'main' );
    return $xtpl->text( 'main' );
}

function search_result_theme ( $key, $numRecord, $per_pages, $pages, $array_content, $url_link )
{
    global $module_file, $module_info, $global_config, $lang_global, $lang_module, $db, $module_name;
    $xtpl = new XTemplate( "search.tpl", NV_ROOTDIR . "/themes/" . $module_info['template'] . "/modules/" . $module_file );
    
    $xtpl->assign( 'LANG', $lang_module );
    $xtpl->assign( 'KEY', $key );
    
    $xtpl->assign( 'TITLE_MOD', $lang_module['search_modul_title'] );
    
    if ( ! empty( $array_content ) )
    {
        foreach ( $array_content as $value )
        {
            $alias_cat = GetCatNews( $value['listcatid'], $module_name );
            $url = $url_link . $alias_cat . '/' . $value['alias'] . "-" . $value['id'];
            $xtpl->assign( 'LINK', $url );
            $xtpl->assign( 'TITLEROW', BoldKeywordInStr( $value['title'], $key ) );
            $xtpl->assign( 'CONTENT', BoldKeywordInStr( $value['hometext'], $key ) . "..." );
            $xtpl->assign( 'AUTHOR', date( 'd/m/Y', $value['publtime'] ) . " - " . BoldKeywordInStr( $value['author'], $key ) );
            $xtpl->assign( 'SOURCE', BoldKeywordInStr( GetSourceNews( $value['sourceid'] ), $key ) );
            $img = "uploads/" . $module_name . "/" . $value['homeimgfile'];
            if ( file_exists( NV_ROOTDIR . "/" . $img ) )
            {
                if ( is_file( NV_ROOTDIR . "/" . $img ) )
                {
                    $xtpl->assign( 'IMG_SRC', NV_BASE_SITEURL . $img );
                    $xtpl->parse( 'results.result.result_img' );
                }
            }
            $xtpl->parse( 'results.result' );
        }
    }
    if ( $numRecord == 0 )
    {
        $xtpl->assign( 'KEY', $key );
        $xtpl->assign( 'INMOD', $lang_module['search_modul_title'] );
        $xtpl->parse( 'results.noneresult' );
    }
    if ( $numRecord > $per_pages ) // show pages
    {
        $url_link = $_SERVER['REQUEST_URI'];
        $in = strpos( $url_link, '&page' );
        if ( $in != 0 ) $url_link = substr( $url_link, 0, $in );
        $generate_page = nv_generate_page( $url_link, $numRecord, $per_pages, $pages );
        $xtpl->assign( 'VIEW_PAGES', $generate_page );
        $xtpl->parse( 'results.pages_result' );
    }
    $xtpl->assign( 'NUMRECORD', $numRecord );
    $xtpl->parse( 'results' );
    return $xtpl->text( 'results' );
}

?>