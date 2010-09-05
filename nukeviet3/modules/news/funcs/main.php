<?php

/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES., JSC. All rights reserved
 * @Createdate 3-6-2010 0:14
 */

if ( ! defined( 'NV_IS_MOD_NEWS' ) ) die( 'Stop!!!' );
$page_title = $module_info['custom_title'];
$key_words = $module_info['keywords'];

$contents = "";
$cache_file = "";
if ( ! defined( 'NV_IS_MODADMIN' ) )
{
    $cache_file = NV_LANG_DATA . "_" . $module_name . "_" . $op . "_" . NV_CACHE_PREFIX . ".cache";
    if ( ( $cache = nv_get_cache( $cache_file ) ) != false )
    {
        $contents = $cache;
    }
}

if ( empty( $contents ) )
{
    $viewcat = $module_config[$module_name]['indexfile'];
    $array_catpage = array();
    $array_cat_other = array();
    $st_links = $st_links;
    $base_url = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=main";
    $arr_cat_title[] = array( 
        'catid' => 0, 'title' => $site_mods[$module_name]['custom_title'], 'link' => $base_url 
    );
    if ( $viewcat == "viewcat_page_new" or $viewcat == "viewcat_page_old" or $set_viewcat == "viewcat_page_new" )
    {
        list( $numf ) = $db->sql_fetchrow( $db->sql_query( "SELECT COUNT(*) FROM `" . NV_PREFIXLANG . "_" . $module_data . "_rows` where `status`= 1 AND `inhome`='1' AND `publtime` < " . NV_CURRENTTIME . " AND (`exptime`=0 OR `exptime`>" . NV_CURRENTTIME . ") " ) );
        $all_page = ( $numf ) ? $numf : 1;
        $order_by = ( $viewcat == "viewcat_page_new" ) ? "ORDER BY `publtime` DESC" : "ORDER BY `publtime` ASC";
        $sql = "SELECT id, listcatid, publtime, title, alias, hometext, homeimgalt, homeimgthumb FROM `" . NV_PREFIXLANG . "_" . $module_data . "_rows` WHERE `status`= 1 AND `inhome`='1' AND `publtime` < " . NV_CURRENTTIME . " AND (`exptime`=0 OR `exptime`>" . NV_CURRENTTIME . ") " . $order_by . " LIMIT  " . $page . "," . $per_page . "";
        $result = $db->sql_query( $sql );
        $end_publtime = 0;
        while ( list( $id, $listcatid, $publtime, $title, $alias, $hometext, $homeimgalt, $homeimgthumb ) = $db->sql_fetchrow( $result ) )
        {
            $catid = end( explode( ",", $listcatid ) );
            $end_publtime = $publtime;
            $array_img = array( 
                "", "" 
            );
            if ( ! empty( $homeimgthumb ) ) $array_img = explode( "|", $homeimgthumb );
            $array_catpage[] = array( 
                "id" => $id, "title" => $title, "publtime" => $publtime, "link" => $global_array_cat[$catid]['link'] . "/" . $alias . "-" . $id, "hometext" => $hometext, "imghome" => $array_img[0], "imgthumb" => $array_img[1], "homeimgalt" => $homeimgalt 
            );
        }
        if ( $viewcat == "viewcat_page_new" )
        {
            $sql = "SELECT `id`, `publtime`, `title`, `alias` FROM `" . NV_PREFIXLANG . "_" . $module_data . "_rows` WHERE `status`= 1 AND `inhome`='1' AND `publtime` < " . $end_publtime . " AND `publtime` < " . NV_CURRENTTIME . " AND (`exptime`=0 OR `exptime`>" . NV_CURRENTTIME . ") " . $order_by . " LIMIT 0," . $st_links . "";
        }
        else
        {
            $sql = "SELECT `id`, `publtime`, `title`, `alias` FROM `" . NV_PREFIXLANG . "_" . $module_data . "_rows` WHERE `status`= 1 AND `inhome`='1' AND `publtime` > " . $end_publtime . " AND `publtime` < " . NV_CURRENTTIME . " AND (`exptime`=0 OR `exptime`>" . NV_CURRENTTIME . ") " . $order_by . " LIMIT 0," . $st_links . "";
        }
        $result = $db->sql_query( $sql );
        while ( list( $id, $publtime, $title, $alias ) = $db->sql_fetchrow( $result ) )
        {
            $array_cat_other[] = array( 
                "id" => $id, "title" => $title, "publtime" => $publtime, "link" => $global_array_cat[$catid]['link'] . "/" . $alias . "-" . $id 
            );
        }
        $viewcat = "viewcat_page_new";
        $contents = call_user_func( $viewcat, $array_catpage, $array_cat_other );
        $contents .= nv_news_page( $base_url, $all_page, $per_page, $page );
    }
    elseif ( $viewcat == "viewcat_main_left" or $viewcat == "viewcat_main_right" or $viewcat == "viewcat_main_bottom" )
    {
        $array_cat = array();
        $key = 0;
        foreach ( $global_array_cat as $key => $array_cat_i )
        {
            if ( $array_cat_i['parentid'] == 0 and $array_cat_i['inhome'] == 1 )
            {
                $array_cat[$key] = $array_cat_i;
                $sql = "SELECT id, publtime, title, alias, hometext, homeimgalt, homeimgthumb FROM `" . NV_PREFIXLANG . "_" . $module_data . "_" . $array_cat_i['catid'] . "` WHERE `status`= 1 AND `inhome`='1' AND `publtime` < " . NV_CURRENTTIME . " AND (`exptime`=0 OR `exptime`>" . NV_CURRENTTIME . ") ORDER BY `publtime` DESC LIMIT 0 , " . $array_cat_i['numlinks'] . "";
                $result = $db->sql_query( $sql );
                while ( list( $id, $publtime, $title, $alias, $hometext, $homeimgalt, $homeimgthumb ) = $db->sql_fetchrow( $result ) )
                {
                    $array_img = array( 
                        "", "" 
                    );
                    if ( ! empty( $homeimgthumb ) ) $array_img = explode( "|", $homeimgthumb );
                    $array_cat[$key]['content'][] = array( 
                        "id" => $id, "publtime" => $publtime, "title" => $title, "link" => $array_cat_i['link'] . "/" . $alias . "-" . $id, "hometext" => $hometext, "imghome" => $array_img[0], "imgthumb" => $array_img[1], "homeimgalt" => $homeimgalt 
                    );
                }
                $key ++;
            }
        }
        $contents = viewsubcat_main( $viewcat, $array_cat );
    }
    elseif ( $viewcat = "viewcat_two_column" )
    {
        // Cac bai viet phan dau
        $array_content = $array_catpage = array();
        // cac bai viet cua cac chu de con
        $key = 0;
        foreach ( $global_array_cat as $key => $array_cat_i )
        {
            if ( $array_cat_i['parentid'] == 0 and $array_cat_i['inhome'] == 1 )
            {
                $catid = $array_cat_i['catid'];
                $array_catpage[$key] = $global_array_cat[$catid];
                $sql = "SELECT `id`, `publtime`, `title`, `alias`, `hometext`, `homeimgalt`, `homeimgthumb` FROM `" . NV_PREFIXLANG . "_" . $module_data . "_" . $catid . "` WHERE `status`= 1 AND `inhome`='1' AND `publtime` < " . NV_CURRENTTIME . " AND (`exptime`=0 OR `exptime`>" . NV_CURRENTTIME . ") ORDER BY `publtime` DESC LIMIT 0 , " . $global_array_cat[$catid]['numlinks'] . "";
                $result = $db->sql_query( $sql );
                while ( list( $id, $publtime, $title, $alias, $hometext, $homeimgalt, $homeimgthumb ) = $db->sql_fetchrow( $result ) )
                {
                    $array_img = array( 
                        "", "" 
                    );
                    if ( ! empty( $homeimgthumb ) ) $array_img = explode( "|", $homeimgthumb );
                    $array_catpage[$key]['content'][] = array( 
                        "id" => $id, "publtime" => $publtime, "title" => $title, "link" => $global_array_cat[$catid]['link'] . "/" . $alias . "-" . $id, "hometext" => $hometext, "imghome" => $array_img[0], "imgthumb" => $array_img[1], "homeimgalt" => $homeimgalt 
                    );
                }
            }
            $key ++;
        }
        unset( $sql, $result );
        //Het cac bai viet cua cac chu de con
        $contents = viewcat_two_column( $array_content, $array_catpage );
    }
    if ( ! defined( 'NV_IS_MODADMIN' ) and $contents != "" and $cache_file != "" )
    {
        nv_set_cache( $cache_file, $contents );
    }
}
include ( NV_ROOTDIR . "/includes/header.php" );
echo nv_site_theme( $contents );
include ( NV_ROOTDIR . "/includes/footer.php" );

?>