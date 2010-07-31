<?php

/**
 * @Project NUKEVIET 3.0
 * @Author VINADES (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES. All rights reserved
 * @Createdate 04/05/2010 10:47:41 AM
 */

if ( ! defined( 'NV_IS_MOD_SEARCH' ) ) die( 'Stop!!!' );

$page_title = $module_info['custom_title'];

$key_words = $module_info['keywords'];

$mod_title = isset( $lang_module['main_title'] ) ? $lang_module['main_title'] : $module_info['custom_title'];

$base_url = NV_BASE_SITEURL . "?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=adv";

$array_modul = LoadModulesSearch();

$key = filter_text_input( 'q', 'get', '', 1, NV_MAX_SEARCH_LENGTH );

$len_key = strlen( $key );

$mod = filter_text_input( 'mod', 'get', 'all', 1 );

$limit = 4;

$pages = filter_text_input( 'page', 'get', '0', 10 );

$per_pages = 20; // nunrecord of pages


$contents = call_user_func( "main_theme", $key, $array_modul, $mod, $base_url );

if ( $mod == "all" )
{
    if ( $len_key >= NV_MIN_SEARCH_LENGTH )
    {
        foreach ( $array_modul as $m_name => $m_info )
        {
            if ( file_exists( NV_ROOTDIR . "/modules/" . $m_info['module_file'] . "/search.php" ) )
            {
                include_once ( NV_ROOTDIR . "/modules/" . $m_info['module_file'] . "/search.php" );
            }
        }
        $contents .= call_user_func( "result_all_theme", $key, $array_modul, $limit, $pages, $per_pages );
    }
}

else
{
    if ( $len_key >= NV_MIN_SEARCH_LENGTH )
    {
        $m_name = $mod;
        $m_info = $array_modul[$m_name];
        if ( file_exists( NV_ROOTDIR . "/modules/" . $m_info['module_file'] . "/search.php" ) )
        {
            include_once ( NV_ROOTDIR . "/modules/" . $m_info['module_file'] . "/search.php" );
        }
        $array_modul_one[$m_name] = $array_modul[$m_name];
        $contents .= call_user_func( "result_all_theme", $key, $array_modul_one, 0, $pages, $per_pages );
    }
}

include ( NV_ROOTDIR . "/includes/header.php" );
echo nv_site_theme( $contents );
include ( NV_ROOTDIR . "/includes/footer.php" );

?> 