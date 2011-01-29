<?php

/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES., JSC. All rights reserved
 * @Createdate 3-6-2010 0:30
 */

if ( ! defined( 'NV_IS_MOD_DOWNLOAD' ) ) die( 'Stop!!!' );

$contents = "";
if ( empty( $list_cats ) )
{
    $page_title = $module_info['custom_title'];
    include ( NV_ROOTDIR . "/includes/header.php" );
    echo nv_site_theme( '' );
    include ( NV_ROOTDIR . "/includes/footer.php" );
    exit();
}

$download_config = initial_config_data();

$today = mktime( 0, 0, 0, date( "n" ), date( "j" ), date( "Y" ) );
$yesterday = $today - 86400;

//Xem theo chu de
if ( empty( $catid ) )
{
    Header( "Location: " . NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name );
    exit();
}

$page_title = $mod_title = $module_info['custom_title'];
$key_words = $module_info['keywords'];

$array = array();
$subcats = array();
$page = $nv_Request->get_int( 'page', 'get', 0 );
$per_page = 15;
$base_url = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name;
$c = $list_cats[$catid];
$page_title = $c['title'];
$description = $c['description'];
$subcats = $c['subcats'];

$in = "`catid`=" . $c['id'];
$base_url .= "&amp;" . NV_OP_VARIABLE . "=" . $catalias;

$sql = "SELECT SQL_CALC_FOUND_ROWS `id`, `catid`, `title`, `alias`, `introtext` , `uploadtime`, 
`author_name`, `filesize`, `fileimage`, `view_hits`, `download_hits`, `comment_allow`, `comment_hits` 
FROM `" . NV_PREFIXLANG . "_" . $module_data . "` WHERE " . $in . " AND `status`=1 
ORDER BY `uploadtime` DESC LIMIT " . $page . ", " . $per_page;

$result = $db->sql_query( $sql );
$query = $db->sql_query( "SELECT FOUND_ROWS()" );
list( $all_page ) = $db->sql_fetchrow( $query );

if ( ! $all_page or $page >= $all_page )
{
    if ( $nv_Request->isset_request( 'page', 'get' ) )
    {
        Header( "Location: " . NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name );
        exit();
    }
}

while ( $row = $db->sql_fetchrow( $result ) )
{
    $cattitle = "<a href=\"" . NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $list_cats[$row['catid']]['alias'] . "\">" . $list_cats[$row['catid']]['title'] . "</a>";
    $more_link = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $list_cats[$row['catid']]['alias'] . "/" . $row['alias'];
    
    $uploadtime = ( int )$row['uploadtime'];
    if ( $uploadtime >= $today )
    {
        $uploadtime = $lang_module['today'] . ", " . date( "H:i", $row['uploadtime'] );
    }
    elseif ( $uploadtime >= $yesterday )
    {
        $uploadtime = $lang_module['yesterday'] . ", " . date( "H:i", $row['uploadtime'] );
    }
    else
    {
        $uploadtime = nv_date( "d/m/Y H:i", $row['uploadtime'] );
    }
    
    $img = substr( $row['fileimage'], strlen( NV_BASE_SITEURL ) );
    $imageinfo = nv_ImageInfo( NV_ROOTDIR . '/' . $img, 300, true, NV_UPLOADS_REAL_DIR . '/' . $module_name . '/thumb' );
    
    $array[$row['id']] = array(  //
        'id' => ( int )$row['id'], //
'title' => $row['title'], //
'cattitle' => $cattitle, //
'introtext' => $row['introtext'], //
'uploadtime' => $uploadtime, //
'author_name' => $row['author_name'], //
'filesize' => ! empty( $row['filesize'] ) ? nv_convertfromBytes( $row['filesize'] ) : "", //
'fileimage' => $imageinfo, //
'view_hits' => ( int )$row['view_hits'], //
'download_hits' => ( int )$row['download_hits'], //
'more_link' => $more_link, //
'edit_link' => ( defined( 'NV_IS_MODADMIN' ) ) ? NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;edit=1&amp;id=" . ( int )$row['id'] : "", //
'del_link' => ( defined( 'NV_IS_MODADMIN' ) ) ? NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name : "" 
    ); //
    

    if ( $row['comment_allow'] )
    {
        $array[$row['id']]['comment_hits'] = ( int )$row['comment_hits'];
    }
}

$generate_page = x_generate_page( $base_url, $all_page, $per_page, $page );
$subs = array();
if ( ! empty( $subcats ) )
{
    foreach ( $subcats as $sub )
    {
        $array_item = array();
        $sql = "SELECT `id`, `catid`, `title`, `alias`, `introtext` , `uploadtime`, `author_name`, `filesize`, `fileimage`, `view_hits`, `download_hits`, `comment_allow`, `comment_hits` FROM `" . NV_PREFIXLANG . "_" . $module_data . "` WHERE `catid`=" . $sub . " AND `status`=1 ORDER BY `uploadtime` DESC LIMIT 0, 3";
        $result = $db->sql_query( $sql );
        while ( $row = $db->sql_fetchrow( $result ) )
        {
            $uploadtime = nv_date( "d/m/Y H:i", $row['uploadtime'] );
            $img = substr( $row['fileimage'], strlen( NV_BASE_SITEURL ) );
            $imageinfo = nv_ImageInfo( NV_ROOTDIR . '/' . $img, 300, true, NV_UPLOADS_REAL_DIR . '/' . $module_name . '/thumb' );
            $array_item[] = array(  //
                'id' => ( int )$row['id'], //
'title' => $row['title'], //
'introtext' => $row['introtext'], //
'uploadtime' => $uploadtime, //
'author_name' => ! empty( $row['author_name'] ) ? $row['author_name'] : $lang_module['unknown'], //
'filesize' => ! empty( $row['filesize'] ) ? nv_convertfromBytes( $row['filesize'] ) : "", //
'fileimage' => $imageinfo, //
'view_hits' => ( int )$row['view_hits'], //
'download_hits' => ( int )$row['download_hits'], //
'more_link' => NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $list_cats[$row['catid']]['alias'] . "/" . $row['alias'], 'edit_link' => NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;edit=1&amp;id=" . ( int )$row['id'], //
'del_link' => NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name 
            ); //		
        }
        $subs[] = array(  //
            'catid' => $sub, //
'title' => $list_cats[$sub]['title'], //
'link' => NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $list_cats[$sub]['alias'], // 
'description' => $list_cats[$sub]['description'], //
'posts' => $array_item 
        );
        unset( $array_item );
    }
}

$contents = theme_viewcat_download( $array, $download_config, $subs, $generate_page );

include ( NV_ROOTDIR . "/includes/header.php" );
echo nv_site_theme( $contents );
include ( NV_ROOTDIR . "/includes/footer.php" );

?>