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
        $cache_file = NV_LANG_DATA . "_" . $module_name . "_" . $op . "_" . $catid . "_" . $page . "_" . NV_CACHE_PREFIX . ".cache";
    }
    else
    {
        $cache_file = NV_LANG_DATA . "_" . $module_name . "_" . $op . "_" . $catid . "_page_" . $page . "_" . NV_CACHE_PREFIX . ".cache";
    }
    if ( ( $cache = nv_get_cache( $cache_file ) ) != false )
    {
        $contents = $cache;
    }
}

if ( empty( $contents ) )
{
    $array_catpage = array();
    $array_cat_other = array();
    $viewcat = $global_array_cat [$catid] ['viewcat'];
    $base_url = $global_array_cat [$catid] ['link'];
    if ( $viewcat == "viewcat_page_new" or $viewcat == "viewcat_page_old" or $set_viewcat == "viewcat_page_new" )
    {
        $st_links = 2 * $st_links;
        $order_by = ( $viewcat == "viewcat_page_new" ) ? "ORDER BY `publtime` DESC" : "ORDER BY `publtime` ASC";
        $sql = "SELECT SQL_CALC_FOUND_ROWS `id`, `listcatid`, `topicid`, `admin_id`, `author`, `sourceid`, `addtime`, `edittime`, `publtime`, `title`, `alias`, `hometext`, `homeimgfile`, `homeimgalt`, `homeimgthumb`, `imgposition`, `inhome`, `allowed_rating`, `hitstotal`, `hitscm`, `total_rating`, `click_rating`, `keywords` FROM `" . NV_PREFIXLANG . "_" . $module_data . "_" . $catid . "` WHERE `status`=1 AND `publtime` < " . NV_CURRENTTIME . " AND (`exptime`=0 OR `exptime`>" . NV_CURRENTTIME . ") " . $order_by . " LIMIT " . $page . "," . $per_page . "";
        $result = $db->sql_query( $sql );
        
        $result_all = $db->sql_query( "SELECT FOUND_ROWS()" );
        list( $numf ) = $db->sql_fetchrow( $result_all );
        $all_page = ( $numf ) ? $numf : 1;
        
        $end_publtime = 0;
        while ( $item = $db->sql_fetchrow( $result ) )
        {
            if ( ! empty( $item ['homeimgthumb'] ) )
            {
                $array_img = explode( "|", $item ['homeimgthumb'] );
            }
            else
            {
                $array_img = array( 
                    "", "" 
                );
            }
            
            if ( $array_img [0] != "" and file_exists( NV_UPLOADS_REAL_DIR . '/' . $module_name . '/' . $array_img [0] ) )
            {
                $item ['imghome'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_name . '/' . $array_img [0];
            }
            elseif ( nv_is_url( $item ['homeimgfile'] ) )
            {
                $item ['imghome'] = $item ['homeimgfile'];
            }
            elseif ( $item ['homeimgfile'] != "" and file_exists( NV_UPLOADS_REAL_DIR . '/' . $module_name . '/' . $item ['homeimgfile'] ) )
            {
                $item ['imghome'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_name . '/' . $item ['homeimgfile'];
            }
            else
            {
                $item ['imghome'] = "";
            }
            
            $item ['link'] = $global_array_cat [$catid] ['link'] . "/" . $item ['alias'] . "-" . $item ['id'];
            $array_catpage [] = $item;
            $end_publtime = $item ['publtime'];
        }
        
        if ( $viewcat == "viewcat_page_new" )
        {
            $sql = "SELECT `id`, `listcatid`, `addtime`, `edittime`, `publtime`, `title`, `alias`, `hitstotal` FROM `" . NV_PREFIXLANG . "_" . $module_data . "_" . $catid . "` WHERE `status`=1 AND `publtime` < " . $end_publtime . " AND `publtime` < " . NV_CURRENTTIME . " AND (`exptime`=0 OR `exptime`>" . NV_CURRENTTIME . ") " . $order_by . " LIMIT 0," . $st_links . "";
        }
        else
        {
            $sql = "SELECT `id`, `listcatid`, `addtime`, `edittime`, `publtime`, `title`, `alias`, `hitstotal` FROM `" . NV_PREFIXLANG . "_" . $module_data . "_" . $catid . "` WHERE `status`=1 AND `publtime` > " . $end_publtime . " AND `publtime` < " . NV_CURRENTTIME . " AND (`exptime`=0 OR `exptime`>" . NV_CURRENTTIME . ") " . $order_by . " LIMIT 0," . $st_links . "";
        }
        $result = $db->sql_query( $sql );
        while ( $item = $db->sql_fetchrow( $result ) )
        {
            $item ['link'] = $global_array_cat [$catid] ['link'] . "/" . $item ['alias'] . "-" . $item ['id'];
            $array_cat_other [] = $item;
        }
        
        $contents = viewcat_page_new( $array_catpage, $array_cat_other );
        $contents .= nv_news_page( $base_url, $all_page, $per_page, $page );
    }
    elseif ( $viewcat == "viewcat_main_left" or $viewcat == "viewcat_main_right" or $viewcat == "viewcat_main_bottom" )
    {
        $array_catcontent = array();
        $array_subcatpage = array();
        $sql = "SELECT SQL_CALC_FOUND_ROWS `id`, `listcatid`, `topicid`, `admin_id`, `author`, `sourceid`, `addtime`, `edittime`, `publtime`, `title`, `alias`, `hometext`, `homeimgfile`, `homeimgalt`, `homeimgthumb`, `imgposition`, `inhome`, `allowed_rating`, `hitstotal`, `hitscm`, `total_rating`, `click_rating`, `keywords` FROM `" . NV_PREFIXLANG . "_" . $module_data . "_" . $catid . "` WHERE `status`=1 AND `publtime` < " . NV_CURRENTTIME . " AND (`exptime`=0 OR `exptime`>" . NV_CURRENTTIME . ") ORDER BY `id` DESC LIMIT " . $page . "," . $per_page . "";
        $result = $db->sql_query( $sql );
        
        $result_all = $db->sql_query( "SELECT FOUND_ROWS()" );
        list( $numf ) = $db->sql_fetchrow( $result_all );
        $all_page = ( $numf ) ? $numf : 1;
        
        while ( $item = $db->sql_fetchrow( $result ) )
        {
            if ( ! empty( $item ['homeimgthumb'] ) )
            {
                $array_img = explode( "|", $item ['homeimgthumb'] );
            }
            else
            {
                $array_img = array( 
                    "", "" 
                );
            }
            
            if ( $array_img [0] != "" and file_exists( NV_UPLOADS_REAL_DIR . '/' . $module_name . '/' . $array_img [0] ) )
            {
                $item ['imghome'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_name . '/' . $array_img [0];
            }
            elseif ( nv_is_url( $item ['homeimgfile'] ) )
            {
                $item ['imghome'] = $item ['homeimgfile'];
            }
            elseif ( $item ['homeimgfile'] != "" and file_exists( NV_UPLOADS_REAL_DIR . '/' . $module_name . '/' . $item ['homeimgfile'] ) )
            {
                $item ['imghome'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_name . '/' . $item ['homeimgfile'];
            }
            else
            {
                $item ['imghome'] = "";
            }
            
            $item ['link'] = $global_array_cat [$catid] ['link'] . "/" . $item ['alias'] . "-" . $item ['id'];
            $array_catcontent [] = $item;
        }
        unset( $sql, $result );
        
        $array_cat_other = array();
        if ( $global_array_cat [$catid] ['subcatid'] != "" )
        {
            $key = 0;
            $array_catid = explode( ",", $global_array_cat [$catid] ['subcatid'] );
            foreach ( $array_catid as $catid_i )
            {
                $array_cat_other [$key] = $global_array_cat [$catid_i];
                $sql = "SELECT `id`, `listcatid`, `topicid`, `admin_id`, `author`, `sourceid`, `addtime`, `edittime`, `publtime`, `title`, `alias`, `hometext`, `homeimgfile`, `homeimgalt`, `homeimgthumb`, `imgposition`, `inhome`, `allowed_rating`, `hitstotal`, `hitscm`, `total_rating`, `click_rating`, `keywords` FROM `" . NV_PREFIXLANG . "_" . $module_data . "_" . $catid_i . "` WHERE `status`=1 AND `publtime` < " . NV_CURRENTTIME . " AND (`exptime`=0 OR `exptime`>" . NV_CURRENTTIME . ") ORDER BY `publtime` DESC LIMIT 0 , " . $global_array_cat [$catid_i] ['numlinks'] . "";
                $result = $db->sql_query( $sql );
                while ( $item = $db->sql_fetchrow( $result ) )
                {
                    if ( ! empty( $item ['homeimgthumb'] ) )
                    {
                        $array_img = explode( "|", $item ['homeimgthumb'] );
                    }
                    else
                    {
                        $array_img = array( 
                            "", "" 
                        );
                    }
                    
                    if ( $array_img [0] != "" and file_exists( NV_UPLOADS_REAL_DIR . '/' . $module_name . '/' . $array_img [0] ) )
                    {
                        $item ['imghome'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_name . '/' . $array_img [0];
                    }
                    elseif ( nv_is_url( $item ['homeimgfile'] ) )
                    {
                        $item ['imghome'] = $item ['homeimgfile'];
                    }
                    elseif ( $item ['homeimgfile'] != "" and file_exists( NV_UPLOADS_REAL_DIR . '/' . $module_name . '/' . $item ['homeimgfile'] ) )
                    {
                        $item ['imghome'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_name . '/' . $item ['homeimgfile'];
                    }
                    else
                    {
                        $item ['imghome'] = "";
                    }
                    
                    $item ['link'] = $global_array_cat [$catid_i] ['link'] . "/" . $item ['alias'] . "-" . $item ['id'];
                    $array_cat_other [$key] ['content'] [] = $item;
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
        $sql = "SELECT `id`, `listcatid`, `topicid`, `admin_id`, `author`, `sourceid`, `addtime`, `edittime`, `publtime`, `title`, `alias`, `hometext`, `homeimgfile`, `homeimgalt`, `homeimgthumb`, `imgposition`, `inhome`, `allowed_rating`, `hitstotal`, `hitscm`, `total_rating`, `click_rating`, `keywords` FROM `" . NV_PREFIXLANG . "_" . $module_data . "_" . $catid . "` WHERE `status`=1 AND `publtime` < " . NV_CURRENTTIME . " AND (`exptime`=0 OR `exptime`>" . NV_CURRENTTIME . ") ORDER BY `publtime` DESC LIMIT " . $page . "," . $per_page . "";
        $result = $db->sql_query( $sql );
        while ( $item = $db->sql_fetchrow( $result ) )
        {
            if ( ! empty( $item ['homeimgthumb'] ) )
            {
                $array_img = explode( "|", $item ['homeimgthumb'] );
            }
            else
            {
                $array_img = array( 
                    "", "" 
                );
            }
            
            if ( $array_img [0] != "" and file_exists( NV_UPLOADS_REAL_DIR . '/' . $module_name . '/' . $array_img [0] ) )
            {
                $item ['imghome'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_name . '/' . $array_img [0];
            }
            elseif ( nv_is_url( $item ['homeimgfile'] ) )
            {
                $item ['imghome'] = $item ['homeimgfile'];
            }
            elseif ( $item ['homeimgfile'] != "" and file_exists( NV_UPLOADS_REAL_DIR . '/' . $module_name . '/' . $item ['homeimgfile'] ) )
            {
                $item ['imghome'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_name . '/' . $item ['homeimgfile'];
            }
            else
            {
                $item ['imghome'] = "";
            }
            $item ['link'] = $global_array_cat [$catid] ['link'] . "/" . $item ['alias'] . "-" . $item ['id'];
            $array_catcontent [] = $item;
        }
        unset( $sql, $result );
        // Het cac bai viet phan dau
        

        // cac bai viet cua cac chu de con
        $key = 0;
        $array_catid = explode( ",", $global_array_cat [$catid] ['subcatid'] );
        foreach ( $array_catid as $catid_i )
        {
            $array_cat_other [$key] = $global_array_cat [$catid_i];
            $sql = "SELECT `id`, `listcatid`, `topicid`, `admin_id`, `author`, `sourceid`, `addtime`, `edittime`, `publtime`, `title`, `alias`, `hometext`, `homeimgfile`, `homeimgalt`, `homeimgthumb`, `imgposition`, `inhome`, `allowed_rating`, `hitstotal`, `hitscm`, `total_rating`, `click_rating`, `keywords` FROM `" . NV_PREFIXLANG . "_" . $module_data . "_" . $catid_i . "` WHERE `status`=1 AND `publtime` < " . NV_CURRENTTIME . " AND (`exptime`=0 OR `exptime`>" . NV_CURRENTTIME . ") ORDER BY `publtime` DESC LIMIT 0 , " . $global_array_cat [$catid_i] ['numlinks'] . "";
            $result = $db->sql_query( $sql );
            while ( $item = $db->sql_fetchrow( $result ) )
            {
                if ( ! empty( $item ['homeimgthumb'] ) )
                {
                    $array_img = explode( "|", $item ['homeimgthumb'] );
                }
                else
                {
                    $array_img = array( 
                        "", "" 
                    );
                }
                
                if ( $array_img [0] != "" and file_exists( NV_UPLOADS_REAL_DIR . '/' . $module_name . '/' . $array_img [0] ) )
                {
                    $item ['imghome'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_name . '/' . $array_img [0];
                }
                elseif ( nv_is_url( $item ['homeimgfile'] ) )
                {
                    $item ['imghome'] = $item ['homeimgfile'];
                }
                elseif ( $item ['homeimgfile'] != "" and file_exists( NV_UPLOADS_REAL_DIR . '/' . $module_name . '/' . $item ['homeimgfile'] ) )
                {
                    $item ['imghome'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_name . '/' . $item ['homeimgfile'];
                }
                else
                {
                    $item ['imghome'] = "";
                }
                $item ['link'] = $global_array_cat [$catid_i] ['link'] . "/" . $item ['alias'] . "-" . $item ['id'];
                $array_cat_other [$key] ['content'] [] = $item;
            }
            $key ++;
        }
        unset( $sql, $result );
        //Het cac bai viet cua cac chu de con
        $contents = call_user_func( $viewcat, $array_catcontent, $array_cat_other );
    }
    if ( ! defined( 'NV_IS_MODADMIN' ) and $contents != "" and $cache_file != "" )
    {
        nv_set_cache( $cache_file, $contents );
    }
}
$page_title = $global_array_cat [$catid] ['title'];
$key_words = $global_array_cat [$catid] ['keywords'];
$description = $global_array_cat [$catid] ['description'];

include ( NV_ROOTDIR . "/includes/header.php" );
echo nv_site_theme( $contents );
include ( NV_ROOTDIR . "/includes/footer.php" );

?>