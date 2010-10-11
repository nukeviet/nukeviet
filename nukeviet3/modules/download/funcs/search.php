<?php

/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES., JSC. All rights reserved
 * @Createdate 3-6-2010 0:30
 */
if ( ! defined( 'NV_IS_MOD_DOWNLOAD' ) ) die( 'Stop!!!' );

global $global_config, $lang_module, $lang_global, $module_info, $module_name, $nv_Request;
$page_title = $lang_module['search'];
$xtpl = new XTemplate( "viewcat_page.tpl", NV_ROOTDIR . "/themes/" . $module_info['template'] . "/modules/" . $module_name . "/" );
$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign( 'SEARCHTITLE', $lang_module['search_result'] );
$page = $nv_Request->get_int( 'page', 'get', 0 );
$per_page = 15;
$base_url = NV_BASE_SITEURL . "?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . '&op=search';

if ( $nv_Request->isset_request( 'submit', 'post' ) )
{
    $key = filter_text_input( 'q', 'post', '', 1, NV_MAX_SEARCH_LENGTH );
    $cat = $nv_Request->get_int( 'cat', 'post' );
    if ( ! empty( $cat ) )
    {
        $allcat = array();
        $allcat = getsubcatcid( $cat );
        $allcat = ( empty( $allcat ) ) ? ' AND catid = ' . $cat . ' ' : ' AND catid IN (' . implode( ',', $allcat ) . ') ';
    }
    else
    {
        $allcat = '';
    }
    
    $sql1 = "SELECT * FROM `" . NV_PREFIXLANG . "_" . $module_data . "` WHERE title LIKE '%" . $key . "%' " . $allcat . " AND `status`=1";
    $sql2 = "SELECT * FROM `" . NV_PREFIXLANG . "_" . $module_data . "` WHERE title LIKE '%" . $key . "%' " . $allcat . " AND `status`=1 ORDER BY `uploadtime` DESC LIMIT " . $page . ", " . $per_page;
}
else
{
    $sql1 = "SELECT * FROM `" . NV_PREFIXLANG . "_" . $module_data . "` WHERE `status`=1";
    $sql2 = "SELECT * FROM `" . NV_PREFIXLANG . "_" . $module_data . "` WHERE `status`=1 ORDER BY `uploadtime` DESC LIMIT " . $page . ", " . $per_page;
}
$result = $db->sql_query( $sql1 );
list( $all_page ) = $db->sql_fetchrow( $result );
if ( ! empty( $all_page ) )
{
    $result = $db->sql_query( $sql2 );
    $imageinfo = array();
    while ( $row = $db->sql_fetchrow( $result ) )
    {
        list( $catalias ) = $db->sql_fetchrow( $db->sql_query( "SELECT alias FROM `" . NV_PREFIXLANG . "_" . $module_data . "_categories` WHERE id=" . $row['catid'] . "" ) );
        $row['filesize'] = ! empty( $row['filesize'] ) ? nv_convertfromBytes( $row['filesize'] ) : "";
        $row['view_hits'] = ( int )$row['view_hits'];
        $row['download_hits'] = ( int )$row['download_hits'];
        $row['more_link'] = NV_BASE_SITEURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=" . $catalias . '/' . $row['alias'];
        $xtpl->assign( 'ROW', $row );
        $imageinfo['src'] = $row['fileimage'];
        if ( ! empty( $row['fileimage'] ) )
        {
            $xtpl->assign( 'FILEIMAGE', $imageinfo );
            $xtpl->parse( 'main.row.is_image' );
        }
        $xtpl->parse( 'main.row' );
    }
    
    $generate_page = nv_generate_page( $base_url, $all_page, $per_page, $page );
    if ( ! empty( $generate_page ) )
    {
        $xtpl->assign( 'GENERATE_PAGE', $generate_page );
        $xtpl->parse( 'main.generate_page' );
    }
}
else
{
    $xtpl->assign( 'GENERATE_PAGE', $lang_module['search_noresult'] );
    $xtpl->parse( 'main.generate_page' );
}

$xtpl->parse( 'main' );
$contents = $xtpl->text( 'main' );

include ( NV_ROOTDIR . "/includes/header.php" );
echo nv_site_theme( $contents );
include ( NV_ROOTDIR . "/includes/footer.php" );

?>