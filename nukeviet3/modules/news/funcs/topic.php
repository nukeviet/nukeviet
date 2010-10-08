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
    list( $numf ) = $db->sql_fetchrow( $db->sql_query( "SELECT COUNT(*) FROM `" . NV_PREFIXLANG . "_" . $module_name . "_rows` WHERE `publtime` <  UNIX_TIMESTAMP( ) AND `topicid` = '" . $topicid . "'" ) );
    $all_page = ( $numf ) ? $numf : 1;
    
    $query = $db->sql_query( "SELECT `id`, `listcatid`, `topicid`, `admin_id`, `author`, `sourceid`, `addtime`, `edittime`, `publtime`, `title`, `alias`, `hometext`, `homeimgfile`, `homeimgalt`, `homeimgthumb`, `imgposition`, `inhome`, `allowed_rating`, `ratingdetail`, `hitstotal`, `hitscm` , `keywords` FROM `" . NV_PREFIXLANG . "_" . $module_name . "_rows` WHERE `status`=1 AND `publtime` < " . NV_CURRENTTIME . " AND (`exptime`=0 OR `exptime`>" . NV_CURRENTTIME . ") AND `topicid` = '" . $topicid . "' ORDER BY `id` DESC LIMIT " . $page . "," . $per_page . "" );
    $topic_array = array();
    $end_id = 0;
    while ( $item = $db->sql_fetchrow( $query ) )
    {
        $end_id = $item['id'];
        $catid = end( explode( ",", $item['listcatid'] ) );
        $alt = $src = "";
        $array_img = ! empty( $item['homeimgthumb'] ) ? explode( "|", $item['homeimgthumb'] ) : "|";
        $size = @getimagesize( NV_UPLOADS_REAL_DIR . '/' . $module_name . '/' . $array_img[0] );
        if ( $size > 0 )
        {
            $homewidth = $module_config[$module_name]['homewidth'];
            $size[1] = round( ( $homewidth / $size[0] ) * $size[1] );
            $size[0] = $homewidth;
            $item['alt'] = ! empty( $item['homeimgalt'] ) ? $item['homeimgalt'] : $item['title'];
            $src = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_name . '/' . $array_img[0];
        }
        
        $item['alt'] = $alt;
        $item['src'] = $src;
        $item['width'] = $size[0];
        $item['height'] = $size[1];
        $item['link'] = $global_array_cat[$catid]['link'] . "/" . $item['alias'] . "-" . $item['id'];
        $topic_array[] = $item;
    }
    $db->sql_freeresult( $query );
    unset( $query, $row );
    
    $topic_other_array = array();
    $query = $db->sql_query( "SELECT `id`, `listcatid`, `addtime`, `edittime`, `publtime`, `title`, `alias`, `hitstotal` FROM `" . NV_PREFIXLANG . "_" . $module_name . "_rows` WHERE `status`=1 AND `publtime` < " . NV_CURRENTTIME . " AND (`exptime`=0 OR `exptime`>" . NV_CURRENTTIME . ") AND `topicid` = " . $topicid . " AND `id` < " . $end_id . " ORDER BY `id` DESC LIMIT 0," . $st_links . "" );
    while ( $item = $db->sql_fetchrow( $query ) )
    {
        $catid = end( explode( ",", $item['listcatid'] ) );
        $item['link'] = $global_array_cat[$catid]['link'] . "/" . $item['alias'] . "-" . $item['id'];
        $topic_other_array[] = $item;
    }
    unset( $query, $row );
    $base_url = "" . NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;op=topic/" . $topicalias . "";
    $contents = topic_theme( $topic_array, $topic_other_array );
    $contents .= nv_news_page( $base_url, $all_page, $per_page, $page );
}
include ( NV_ROOTDIR . "/includes/header.php" );
echo nv_site_theme( $contents );
include ( NV_ROOTDIR . "/includes/footer.php" );

?>