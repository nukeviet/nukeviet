<?php

/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES., JSC. All rights reserved
 * @Createdate 3-6-2010 0:14
 */
if ( ! defined( 'NV_IS_MOD_NEWS' ) ) die( 'Stop!!!' );

$page = 0;
$topicalias = trim( $array_op[1] );
$page = ( isset( $array_op[2] ) and substr( $array_op[2], 0, 5 ) == "page-" ) ? intval( substr( $array_op[2], 5 ) ) : 0;
list( $topicid, $topictitle ) = $db->sql_fetchrow( $db->sql_query( "SELECT `topicid`, `title` FROM `" . NV_PREFIXLANG . "_" . $module_data . "_topics` WHERE `alias`=" . $db->dbescape( $topicalias ) . "" ) );
if ( $topicid > 0 )
{
    
    $array_mod_title[] = array( 
        'catid' => 0, 'title' => $topictitle, 'link' => NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=topic/" . $topicalias 
    );
    
    $query = $db->sql_query( "SELECT SQL_CALC_FOUND_ROWS `id`, `listcatid`, `topicid`, `admin_id`, `author`, `sourceid`, `addtime`, `edittime`, `publtime`, `title`, `alias`, `hometext`, `homeimgfile`, `homeimgalt`, `homeimgthumb`, `imgposition`, `inhome`, `allowed_rating`, `hitstotal`, `hitscm`, `total_rating`, `click_rating`, `keywords` FROM `" . NV_PREFIXLANG . "_" . $module_name . "_rows` WHERE `status`=1 AND `publtime` < " . NV_CURRENTTIME . " AND (`exptime`=0 OR `exptime`>" . NV_CURRENTTIME . ") AND `topicid` = '" . $topicid . "' ORDER BY `id` DESC LIMIT " . $page . "," . $per_page . "" );
    $result_all = $db->sql_query( "SELECT FOUND_ROWS()" );
    list( $numf ) = $db->sql_fetchrow( $result_all );
    $all_page = ( $numf ) ? $numf : 1;
    
    $topic_array = array();
    $end_id = 0;
    while ( $item = $db->sql_fetchrow( $query ) )
    {
        if ( ! empty( $item['homeimgthumb'] ) )
        {
            $array_img = explode( "|", $item['homeimgthumb'] );
        }
        else
        {
            $array_img = array( 
                "", "" 
            );
        }
        
        if ( $array_img[0] != "" and file_exists( NV_UPLOADS_REAL_DIR . '/' . $module_name . '/' . $array_img[0] ) )
        {
            $item['src'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_name . '/' . $array_img[0];
        }
        elseif ( nv_is_url( $item['homeimgfile'] ) )
        {
            $item['src'] = $item['homeimgfile'];
        }
        elseif ( $item['homeimgfile'] != "" and file_exists( NV_UPLOADS_REAL_DIR . '/' . $module_name . '/' . $item['homeimgfile'] ) )
        {
            $item['src'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_name . '/' . $item['homeimgfile'];
        }
        else
        {
            $item['src'] = "";
        }
        $item['alt'] = ! empty( $item['homeimgalt'] ) ? $item['homeimgalt'] : $item['title'];
        $item['width'] = $module_config[$module_name]['homewidth'];
        
        $end_id = $item['id'];
        $arr_listcatid = explode( ",", $item['listcatid'] );
        $catid = end( $arr_listcatid );
        
        $item['link'] = $global_array_cat[$catid]['link'] . "/" . $item['alias'] . "-" . $item['id'];
        $topic_array[] = $item;
    }
    $db->sql_freeresult( $query );
    unset( $query, $row );
    
    $topic_other_array = array();
    $query = $db->sql_query( "SELECT `id`, `listcatid`, `addtime`, `edittime`, `publtime`, `title`, `alias`, `hitstotal` FROM `" . NV_PREFIXLANG . "_" . $module_name . "_rows` WHERE `status`=1 AND `publtime` < " . NV_CURRENTTIME . " AND (`exptime`=0 OR `exptime`>" . NV_CURRENTTIME . ") AND `topicid` = " . $topicid . " AND `id` < " . $end_id . " ORDER BY `id` DESC LIMIT 0," . $st_links . "" );
    while ( $item = $db->sql_fetchrow( $query ) )
    {
        $arr_listcatid = explode( ",", $item['listcatid'] );
        $catid = end( $arr_listcatid );
        $item['link'] = $global_array_cat[$catid]['link'] . "/" . $item['alias'] . "-" . $item['id'];
        $topic_other_array[] = $item;
    }
    unset( $query, $row, $arr_listcatid );
    $base_url = "" . NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;op=topic/" . $topicalias . "";
    $contents = topic_theme( $topic_array, $topic_other_array );
    $contents .= nv_news_page( $base_url, $all_page, $per_page, $page );
}
include ( NV_ROOTDIR . "/includes/header.php" );
echo nv_site_theme( $contents );
include ( NV_ROOTDIR . "/includes/footer.php" );

?>