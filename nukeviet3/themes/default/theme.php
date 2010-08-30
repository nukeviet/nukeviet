<?php

/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES.,JSC. All rights reserved
 * @Createdate 31/05/2010, 00:36
 */

if ( ! defined( 'NV_SYSTEM' ) or ! defined( 'NV_MAINFILE' ) ) die( 'Stop!!!' );

function nv_site_theme ( $contents )
{
    global $lang_global, $language_array, $global_config, $site_mods, $module_name, $module_info, $op, $db, $mod_title, $my_head, $nv_array_block_contents, $client_info;
    if ( ! file_exists( NV_ROOTDIR . "/themes/" . $global_config['site_theme'] . "/layout/layout." . $module_info['funcs'][$op]['layout'] . ".tpl" ) )
    {
        nv_info_die( $lang_global['error_layout_title'], $lang_global['error_layout_title'], $lang_global['error_layout_content'] );
    }
    
    if ( defined( 'NV_IS_ADMIN' ) )
    {
        $my_head .= "<link rel=\"stylesheet\" href=\"" . NV_BASE_SITEURL . "themes/" . $global_config['site_theme'] . "/css/admin.css\" type=\"text/css\" />";
    }
    if ( defined( 'NV_DISPLAY_ERRORS_LIST' ) and NV_DISPLAY_ERRORS_LIST != 0 )
    {
        $my_head .= "<link rel=\"stylesheet\" href=\"" . NV_BASE_SITEURL . "themes/" . $global_config['site_theme'] . "/css/tab_info.css\" type=\"text/css\" />";
    }
    
    $xtpl = new XTemplate( "layout." . $module_info['funcs'][$op]['layout'] . ".tpl", NV_ROOTDIR . "/themes/" . $global_config['site_theme'] . "/layout/" );
    $xtpl->assign( 'LANG', $lang_global );
    $xtpl->assign( 'TEMPLATE', $global_config['site_theme'] );
    $xtpl->assign( 'NV_BASE_SITEURL', NV_BASE_SITEURL );
    $xtpl->assign( 'THEME_META_TAGS', nv_html_meta_tags() );
    $xtpl->assign( 'THEME_SITE_JS', nv_html_site_js() );
    $xtpl->assign( 'THEME_CSS', nv_html_css() );
    $xtpl->assign( 'THEME_PAGE_TITLE', nv_html_page_title() );
    $xtpl->assign( 'NV_TOP_MENU_HOME', $lang_global['Home'] );
    $xtpl->assign( 'MODULE_CONTENT', $contents . "&nbsp;" );
    
    $xtpl->assign( 'THEME_NOJS', $lang_global['nojs'] );
    $xtpl->assign( 'THEME_LOGO_TITLE', $global_config['site_name'] );
    $xtpl->assign( 'THEME_SITE_HREF', NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA );
    $xtpl->assign( 'THEME_SITE_RSS', nv_html_site_rss() );
    $xtpl->assign( 'THEME_SEARCH_SUBMIT', "nv_search_submit('topmenu_search_query', 'topmenu_search_submit', " . NV_MIN_SEARCH_LENGTH . ", " . NV_MAX_SEARCH_LENGTH . ");" );
    $xtpl->assign( 'THEME_DIGCLOCK_TEXT', nv_date( "H:i T l, d/m/Y", NV_CURRENTTIME ) );
    $xtpl->assign( 'THEME_RSS_INDEX_HREF', NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=rss" );
    
    $xtpl->assign( 'THEME_SEARCH_QUERY_MAX_LENGTH', NV_MAX_SEARCH_LENGTH );
    $xtpl->assign( 'THEME_SEARCH_SUBMIT_ONCLICK', "nv_search_submit('topmenu_search_query', 'topmenu_search_checkss', 'topmenu_search_submit', " . NV_MIN_SEARCH_LENGTH . ", " . NV_MAX_SEARCH_LENGTH . ");" );
    $xtpl->assign( 'CHECKSS', md5( $client_info['session_id'] . $global_config['sitekey'] ) );
    
    $xtpl->assign( 'THEME_SITE_NAME', sprintf( $lang_global['copyright'], $global_config['site_name'] ) );
    //    $xtpl->assign( 'THEME_CONTACT_PHONE', $lang_global['phonenumber'] . ": " . $global_config['site_phone'] );
    $xtpl->assign( 'THEME_CONTACT_EMAIL', $lang_global['email'] . ": " . nv_EncodeEmail( $global_config['site_email'] ) );
    
    if ( $global_config['lang_multi'] and count( $global_config['allow_sitelangs'] ) > 1 )
    {
        $xtpl->assign( 'SELECTLANGSITE', $lang_global['langsite'] );
        foreach ( $global_config['allow_sitelangs'] as $lang_i )
        {
            $langname = $language_array[$lang_i]['name'];
            $xtpl->assign( 'LANGSITENAME', $langname );
            $xtpl->assign( 'LANGSITEURL', NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . $lang_i );
            if ( NV_LANG_DATA != $lang_i ) $xtpl->parse( 'main.language.langitem' );
            else $xtpl->parse( 'main.language.langcuritem' );
        }
        $xtpl->parse( 'main.language' );
    }
    
    foreach ( $site_mods as $modname => $modvalues )
    {
        if ( ! empty( $modvalues['in_menu'] ) )
        {
            $module_current = ( $modname == $module_name ) ? ' class="current"' : '';
            $aryay_menu = array( 
                "title" => $modvalues['custom_title'], "class" => $modname, "current" => $module_current, "link" => NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $modname 
            );
            if ( ! empty( $modvalues['funcs'] ) )
            {
                $sub_nav_item = array();
                
                if ( $modvalues['module_file'] == "news" or $modvalues['module_file'] == "weblinks" )
                {
                    $result2 = $db->sql_query( "SELECT title, alias FROM " . NV_PREFIXLANG . "_" . $modvalues['module_data'] . "_cat where `parentid`='0' AND `inhome`='1' ORDER BY `weight` LIMIT 0,10" );
                    while ( list( $title_i, $alias_i ) = $db->sql_fetchrow( $result2 ) )
                    {
                        $sub_nav_item[] = array( 
                            'title' => $title_i, 'link' => NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $modname . "&amp;" . NV_OP_VARIABLE . "=" . $alias_i 
                        );
                    }
                }
                elseif ( $modvalues['module_file'] == "download" )
                {
                    $result2 = $db->sql_query( "SELECT title, alias FROM " . NV_PREFIXLANG . "_" . $modvalues['module_data'] . "_categories where `parentid`='0' AND `status`='1'ORDER BY `weight` LIMIT 0,10" );
                    while ( list( $title_i, $alias_i ) = $db->sql_fetchrow( $result2 ) )
                    {
                        $sub_nav_item[] = array( 
                            'title' => $title_i, 'link' => NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $modname . "&amp;" . NV_OP_VARIABLE . "=" . $alias_i 
                        );
                    }
                }
                elseif ( $modname == "users" )
                {
                    if ( defined( 'NV_IS_USER' ) )
                    {
                        $in_submenu_users = array( 
                            "changepass", "openid", "logout" 
                        );
                    }
                    else
                    {
                        $in_submenu_users = array( 
                            "login", "register", "lostpass" 
                        );
                    }
                    foreach ( $modvalues['funcs'] as $key => $sub_item )
                    {
                        if ( $sub_item['in_submenu'] == 1 and in_array( $key, $in_submenu_users ) )
                        {
                            $sub_nav_item[] = array( 
                                "title" => $sub_item['func_custom_name'], "link" => NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $modname . "&amp;" . NV_OP_VARIABLE . "=" . $key 
                            );
                        }
                    }
                }
                else
                {
                    foreach ( $modvalues['funcs'] as $key => $sub_item )
                    {
                        if ( $sub_item['in_submenu'] == 1 )
                        {
                            $sub_nav_item[] = array( 
                                "title" => $sub_item['func_custom_name'], "link" => NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $modname . "&amp;" . NV_OP_VARIABLE . "=" . $key 
                            );
                        }
                    }
                }
                if ( ! empty( $sub_nav_item ) )
                {
                    foreach ( $sub_nav_item as $sub_nav )
                    {
                        $xtpl->assign( 'SUB', $sub_nav );
                        $xtpl->parse( 'main.top_menu.sub.item' );
                    }
                    $xtpl->parse( 'main.top_menu.sub' );
                }
            }
            $xtpl->assign( 'TOP_MENU', $aryay_menu );
            $xtpl->parse( 'main.top_menu' );
        }
    }
    
    $theme_stat_img = "";
    if ( $global_config['statistic'] and isset( $site_mods['statistics'] ) )
    {
        $theme_stat_img .= "<a title=\"" . $lang_global['viewstats'] . "\" href=\"" . NV_BASE_SITEURL . "index.php?" . NV_NAME_VARIABLE . "=statistics\"><img alt=\"" . $lang_global['viewstats'] . "\" title=\"" . $lang_global['viewstats'] . "\" src=\"" . NV_BASE_SITEURL . "index.php?second=statimg&amp;p=" . nv_genpass() . "\" width=\"88\" height=\"31\" /></a><br />\n";
    }
    $theme_footer_js = "<script type=\"text/javascript\">\n";
    $theme_footer_js .= "nv_DigitalClock('digclock');\n";
    $theme_footer_js .= "</script>\n";
    if ( NV_LANG_INTERFACE == 'vi' )
    {
        $theme_footer_js .= "<script type=\"text/javascript\" src=\"" . NV_BASE_SITEURL . "js/mudim.js\"></script>";
    }
    $xtpl->assign( 'THEME_STAT_IMG', $theme_stat_img );
    $xtpl->assign( 'THEME_IMG_CRONJOBS', NV_BASE_SITEURL . "index.php?second=cronjobs&amp;p=" . nv_genpass() );
    $xtpl->assign( 'THEME_FOOTER_JS', $theme_footer_js );
    
    if ( defined( 'NV_IS_ADMIN' ) )
    {
        $xtpl->assign( 'THEME_ADMIN_MENU', nv_admin_menu() );
        $end_time = array_sum( explode( " ", microtime() ) );
        $total_time = substr( ( $end_time - NV_START_TIME + $db->time ), 0, 5 );
        $theme_click_show_queries = "";
        if ( defined( 'NV_IS_SPADMIN' ) )
        {
            $show_queries = " <a href=\"#queries\" onclick=\"nv_show_hidden('div_hide',2);\">" . $lang_global['show_queries'] . "</a>";
            $theme_click_show_queries = $lang_global['db_num_queries'] . ": " . count( $db->query_strs ) . " / " . $total_time . "'." . $show_queries . "<br />\n";
        }
        $xtpl->assign( 'CLICK_SHOW_QUERIES', $theme_click_show_queries );
        $xtpl->assign( 'SHOW_QUERIES_FOR_ADMIN', nv_show_queries_for_admin() );
        $xtpl->parse( 'main.for_admin' );
    }
    $xtpl->assign( 'THEME_ERROR_INFO', nv_error_info() );
    $xtpl->parse( 'main' );
    $sitecontent = $xtpl->text( 'main' );
    foreach ( $nv_array_block_contents as $position => $blcontent )
    {
        $sitecontent = str_replace( $position, $blcontent, $sitecontent );
    }
    echo $sitecontent;
}

?>