<?php

/** * @Project NUKEVIET 3.0 * @Author VINADES (contact@vinades.vn) * @Copyright (C) 2010 VINADES. All rights reserved * @Createdate Apr 20, 2010 10:47:41 AM */
if ( ! defined( 'NV_IS_MOD_SEARCH' ) )
{
    die( 'Stop!!!' );
}

function main_theme ( $key, $array_modul, $mod, $base_url )
{
    global $module_info, $module_file, $global_config, $lang_global, $lang_module, $module_name;
    $xtpl = new XTemplate( "form.tpl", NV_ROOTDIR . "/themes/" . $module_info['template'] . "/modules/" . $module_file );
    $base_url_site = NV_BASE_SITEURL . "?";
    $xtpl->assign( 'LANG', $lang_module );
    $xtpl->assign( 'NV_LANG_VARIABLE', NV_LANG_VARIABLE );
    $xtpl->assign( 'NV_LANG_DATA', NV_LANG_DATA );
    $xtpl->assign( 'NV_NAME_VARIABLE', NV_NAME_VARIABLE );
    $xtpl->assign( 'MODULE_NAME', $module_name );
    $xtpl->assign( 'BASE_URL_SITE', $base_url_site );
    $xtpl->assign( 'URL_SEARCH_ADV', $base_url );
    $xtpl->assign( 'KEY', $key );
    if ( ! empty( $array_modul ) )
    {
        foreach ( $array_modul as $m_name => $m_info )
        {
            $xtpl->assign( 'SELECT_NAME', $m_info['custom_title'] );
            $xtpl->assign( 'SELECT_VALUE', $m_name );
            if ( $m_name == $mod ) $xtpl->parse( 'main.select_option' );
            else $xtpl->parse( 'main.option_loop' );
        }
    }
    $xtpl->parse( 'main' );
    return $xtpl->text( 'main' );
}

function result_all_theme ( $key, $array_modul, $limit, $pages, $per_pages )
{
    global $module_info, $module_file, $global_config, $lang_global, $lang_module, $db, $module_name;
    $numall = 0;
    $xtpl = new XTemplate( "form.tpl", NV_ROOTDIR . "/themes/" . $module_info['template'] . "/modules/" . $module_file );
    $xtpl->assign( 'LANG', $lang_module );
    $xtpl->assign( 'KEY', $key );
    $xtpl->assign( 'MY_DOMAIN', NV_MY_DOMAIN );
    if ( ! empty( $array_modul ) )
    {
        foreach ( $array_modul as $m_name => $m_info )
        {
            $url = "javascript:ViewAll('" . $m_name . "')";
            $num = 0;
            
            $xtpl->assign( 'TITLE_MOD', $m_info['custom_title'] );
            if ( function_exists( "result_" . $m_info['module_file'] . "_theme" ) )
            {
                $num = call_user_func( "result_" . $m_info['module_file'] . "_theme", $m_info, $key, $xtpl, $limit, $pages, $per_pages );
            }
            if ( $num == 0 )
            {
                $xtpl->assign( 'KEY', $key );
                $xtpl->assign( 'INMOD', $m_info['custom_title'] );
                $xtpl->parse( 'results.loop_result.noneresult' );
            }
            $numall = $numall + $num;
            if ( $num > $limit && $limit > 0 )
            {
                $xtpl->assign( 'URL_VIEW_ALL', $url );
                $xtpl->parse( 'results.loop_result.limit_result' );
            }
            if ( $num > $per_pages && $limit == 0 ) // show pages
            {
                $url_link = $_SERVER['REQUEST_URI'];
                $in = strpos( $url_link, '&page' );
                if ( $in != 0 ) $url_link = substr( $url_link, 0, $in );
                $generate_page = nv_generate_page( $url_link, $num, $per_pages, $pages );
                $xtpl->assign( 'VIEW_PAGES', $generate_page );
                $xtpl->parse( 'results.loop_result.pages_result' );
            }
            $xtpl->parse( 'results.loop_result' );
        }
        $xtpl->assign( 'NUMRECORD', $numall );
        $xtpl->parse( 'results' );
    }
    return $xtpl->text( 'results' );
}

?>