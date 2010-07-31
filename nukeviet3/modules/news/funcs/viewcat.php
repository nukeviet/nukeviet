<?php

/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES., JSC. All rights reserved
 * @Createdate 3-6-2010 0:14
 */

if ( ! defined( 'NV_IS_MOD_NEWS' ) ) die( 'Stop!!!' );
$cache_file = "";
$contents = "";
if ( ! defined( 'NV_IS_MODADMIN' ) and $page < 100 )
{
    if ( empty( $set_viewcat ) )
    {
        $cache_file = NV_ROOTDIR . "/" . NV_CACHEDIR . "/" . NV_LANG_DATA . "_" . $module_name . "_" . $op . "_" . $catid . "_" . $page . "_" . md5( $global_config['sitekey'] . NV_BASE_SITEURL ) . ".cache";
    }
    else
    {
        $cache_file = NV_ROOTDIR . "/" . NV_CACHEDIR . "/" . NV_LANG_DATA . "_" . $module_name . "_" . $op . "_" . $catid . "_page_" . $page . "_" . md5( $global_config['sitekey'] . NV_BASE_SITEURL ) . ".cache";
    }
    if ( file_exists( $cache_file ) )
    {
        $contents = file_get_contents( $cache_file );
    }
}

if ( empty( $contents ) )
{
    $array_catpage = array();
    $array_cat_other = array();
    $viewcat = $global_array_cat[$catid]['viewcat'];
    $base_url = $global_array_cat[$catid]['link'];
    list( $numf ) = $db->sql_fetchrow( $db->sql_query( "SELECT COUNT(*) FROM `" . NV_PREFIXLANG . "_" . $module_data . "_" . $catid . "` WHERE `status`=1 AND `publtime` < " . NV_CURRENTTIME . " AND (`exptime`=0 OR `exptime`>" . NV_CURRENTTIME . ") " ) );
    $all_page = ( $numf ) ? $numf : 1;
    if ( $viewcat == "viewcat_page_new" or $viewcat == "viewcat_page_old" or $set_viewcat == "viewcat_page_new" )
    {
        $st_links = 2 * $st_links;
        $order_by = ( $viewcat == "viewcat_page_new" ) ? "ORDER BY `publtime` DESC" : "ORDER BY `publtime` ASC";
        $sql = "SELECT `id`, `publtime`, `title`, `alias`, `hometext`, `homeimgalt`, `homeimgthumb` FROM `" . NV_PREFIXLANG . "_" . $module_data . "_" . $catid . "` WHERE `status`=1 AND `publtime` < " . NV_CURRENTTIME . " AND (`exptime`=0 OR `exptime`>" . NV_CURRENTTIME . ") " . $order_by . " LIMIT " . $page . "," . $per_page . "";
        $result = $db->sql_query( $sql );
        $end_publtime = 0;
        while ( list( $id, $publtime, $title, $alias, $hometext, $homeimgalt, $homeimgthumb ) = $db->sql_fetchrow( $result ) )
        {
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
            $sql = "SELECT `id`, `publtime`, `title`, `alias` FROM `" . NV_PREFIXLANG . "_" . $module_data . "_" . $catid . "` WHERE `status`=1 AND `publtime` < " . $end_publtime . " AND `publtime` < " . NV_CURRENTTIME . " AND (`exptime`=0 OR `exptime`>" . NV_CURRENTTIME . ") " . $order_by . " LIMIT 0," . $st_links . "";
        }
        else
        {
            $sql = "SELECT `id`, `publtime`, `title`, `alias` FROM `" . NV_PREFIXLANG . "_" . $module_data . "_" . $catid . "` WHERE `status`=1 AND `publtime` > " . $end_publtime . " AND `publtime` < " . NV_CURRENTTIME . " AND (`exptime`=0 OR `exptime`>" . NV_CURRENTTIME . ") " . $order_by . " LIMIT 0," . $st_links . "";
        }
        $result = $db->sql_query( $sql );
        while ( list( $id, $publtime, $title, $alias ) = $db->sql_fetchrow( $result ) )
        {
            $array_cat_other[] = array( 
                "id" => $id, "title" => $title, "publtime" => $publtime, "link" => $global_array_cat[$catid]['link'] . "/" . $alias . "-" . $id 
            );
        }
        
        $contents = viewcat_page_new($array_catpage, $array_cat_other );
        $contents .= nv_news_page( $base_url, $all_page, $per_page, $page );
    }
    elseif ( $viewcat == "viewcat_main_left" or $viewcat == "viewcat_main_right" or $viewcat == "viewcat_main_bottom" )
    {
        $array_catcontent = array();
        $array_subcatpage = array();
        
        $sql = "SELECT `id`, `publtime`, `title`, `alias`, `hometext`, `homeimgalt`, `homeimgthumb` FROM `" . NV_PREFIXLANG . "_" . $module_data . "_" . $catid . "` WHERE `status`=1 AND `publtime` < " . NV_CURRENTTIME . " AND (`exptime`=0 OR `exptime`>" . NV_CURRENTTIME . ") ORDER BY `id` DESC LIMIT " . $page . "," . $per_page . "";
        $result = $db->sql_query( $sql );
        while ( list( $id, $publtime, $title, $alias, $hometext, $homeimgalt, $homeimgthumb ) = $db->sql_fetchrow( $result ) )
        {
            $array_img = array( 
                "", "" 
            );
            if ( ! empty( $homeimgthumb ) ) $array_img = explode( "|", $homeimgthumb );
            $link = NV_BASE_SITEURL . "?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $global_array_cat[$catid]['alias'];
            $array_catcontent[] = array( 
                "id" => $id, "publtime" => $publtime, "title" => $title, "link" => $link . "/" . $alias . "-" . $id, "hometext" => $hometext, "imghome" => $array_img[0], "imgthumb" => $array_img[1], "homeimgalt" => $homeimgalt 
            );
        }
        unset( $sql, $result );
        
        $array_cat_other = array();
        if ( $global_array_cat[$catid]['subcatid'] != "" )
        {
            $key = 0;
            $array_catid = explode( ",", $global_array_cat[$catid]['subcatid'] );
            foreach ( $array_catid as $catid_i )
            {
                $array_cat_other[$key] = $global_array_cat[$catid_i];
                $sql = "SELECT `id`, `publtime`, `title`, `alias`, `hometext`, `homeimgalt`, `homeimgthumb` FROM `" . NV_PREFIXLANG . "_" . $module_data . "_" . $catid_i . "` WHERE `status`=1 AND `publtime` < " . NV_CURRENTTIME . " AND (`exptime`=0 OR `exptime`>" . NV_CURRENTTIME . ") ORDER BY `publtime` DESC LIMIT 0 , " . $global_array_cat[$catid_i]['numlinks'] . "";
                $result = $db->sql_query( $sql );
                while ( list( $id, $publtime, $title, $alias, $hometext, $homeimgalt, $homeimgthumb ) = $db->sql_fetchrow( $result ) )
                {
                    $array_img = array( 
                        "", "" 
                    );
                    if ( ! empty( $homeimgthumb ) ) $array_img = explode( "|", $homeimgthumb );
                    $array_cat_other[$key]['content'][] = array( 
                        "id" => $id, "publtime" => $publtime, "title" => $title, "link" => $global_array_cat[$catid_i]['link'] . "/" . $alias . "-" . $id, "hometext" => $hometext, "imghome" => $array_img[0], "imgthumb" => $array_img[1], "homeimgalt" => $homeimgalt 
                    );
                }
                unset( $sql, $result );
                $key ++;
            }
            unset( $array_catid );
        }
        $contents = viewcat_top( $array_catcontent );
        $contents .= nv_news_page( $base_url, $all_page, $per_page, $page );
        $contents .= call_user_func( "viewsubcat_main", $viewcat, $array_cat_other );
    }
    elseif ( $viewcat == "viewcat_two_column" )
    {
        // Cac bai viet phan dau
        $array_catcontent = array();
        $sql = "SELECT `id`, `publtime`, `title`, `alias`, `hometext`, `homeimgalt`, `homeimgthumb` FROM `" . NV_PREFIXLANG . "_" . $module_data . "_" . $catid . "` WHERE `status`=1 AND `publtime` < " . NV_CURRENTTIME . " AND (`exptime`=0 OR `exptime`>" . NV_CURRENTTIME . ") ORDER BY `publtime` DESC LIMIT " . $page . "," . $per_page . "";
        $result = $db->sql_query( $sql );
        while ( list( $id, $publtime, $title, $alias, $hometext, $homeimgalt, $homeimgthumb ) = $db->sql_fetchrow( $result ) )
        {
            $array_img = array( 
                "", "" 
            );
            if ( ! empty( $homeimgthumb ) ) $array_img = explode( "|", $homeimgthumb );
            $link = NV_BASE_SITEURL . "?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $global_array_cat[$catid]['alias'];
            $array_catcontent[] = array( 
                "id" => $id, "publtime" => $publtime, "title" => $title, "link" => $link . "/" . $alias . "-" . $id, "hometext" => $hometext, "imghome" => $array_img[0], "imgthumb" => $array_img[1], "homeimgalt" => $homeimgalt 
            );
        }
        unset( $sql, $result );
        // Het cac bai viet phan dau
        

        // cac bai viet cua cac chu de con
        $key = 0;
        $array_catid = explode( ",", $global_array_cat[$catid]['subcatid'] );
        foreach ( $array_catid as $catid_i )
        {
            $array_cat_other[$key] = $global_array_cat[$catid_i];
            $sql = "SELECT `id`, `publtime`, `title`, `alias`, `hometext`, `homeimgalt`, `homeimgthumb` FROM `" . NV_PREFIXLANG . "_" . $module_data . "_" . $catid_i . "` WHERE `status`=1 AND `publtime` < " . NV_CURRENTTIME . " AND (`exptime`=0 OR `exptime`>" . NV_CURRENTTIME . ") ORDER BY `publtime` DESC LIMIT 0 , " . $global_array_cat[$catid_i]['numlinks'] . "";
            $result = $db->sql_query( $sql );
            while ( list( $id, $publtime, $title, $alias, $hometext, $homeimgalt, $homeimgthumb ) = $db->sql_fetchrow( $result ) )
            {
                $array_img = array( 
                    "", "" 
                );
                if ( ! empty( $homeimgthumb ) ) $array_img = explode( "|", $homeimgthumb );
                $array_cat_other[$key]['content'][] = array( 
                    "id" => $id, "publtime" => $publtime, "title" => $title, "link" => $global_array_cat[$catid_i]['link'] . "/" . $alias . "-" . $id, "hometext" => $hometext, "imghome" => $array_img[0], "imgthumb" => $array_img[1], "homeimgalt" => $homeimgalt 
                );
            }
            $key ++;
        }
        unset( $sql, $result );
        //Het cac bai viet cua cac chu de con
        $contents = call_user_func( $viewcat, $array_catcontent, $array_cat_other );
    }
    if ( $cache_file != "" and $contents != "" )
    {
        file_put_contents( $cache_file, $contents, LOCK_EX );
    }
}
$page_title = $global_array_cat[$catid]['title'];
$key_words = $global_array_cat[$catid]['keywords'];
$description = $global_array_cat[$catid]['description'];

include ( NV_ROOTDIR . "/includes/header.php" );
echo nv_site_theme( $contents );
include ( NV_ROOTDIR . "/includes/footer.php" );

?>