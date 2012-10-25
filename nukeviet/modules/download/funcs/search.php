<?php

/**
 * @Project NUKEVIET 3.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2012 VINADES.,JSC. All rights reserved
 * @Createdate 3-6-2010 0:30
 */

if ( ! defined( 'NV_IS_MOD_DOWNLOAD' ) ) die( 'Stop!!!' );

global $global_config, $lang_module, $lang_global, $module_info, $module_name, $module_file, $nv_Request;

$list_cats = nv_list_cats( true );

$page = $nv_Request->get_int( 'page', 'get', 1 );
$per_page = 15;
$key = filter_text_input( 'q', 'post', '', 1, NV_MAX_SEARCH_LENGTH );

$page_title = $lang_module['search'].' '.$key;

$base_url = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=search";

$sql = "SELECT SQL_CALC_FOUND_ROWS `id`, `catid`, `title`, `alias`, `introtext` , `uploadtime`, 
`author_name`, `filesize`, `fileimage`, `view_hits`, `download_hits`, `comment_allow`, `comment_hits` 
FROM `" . NV_PREFIXLANG . "_" . $module_data . "`";


if ( $nv_Request->isset_request( 'submit', 'post' ) and ! empty( $key ) )
{
    $cat = $nv_Request->get_int( 'cat', 'post', 0 );
    if ( ! empty( $cat ) and isset( $list_cats[$cat] ) )
    {
        $allcat = $list_cats[$cat]['subcats'];
		
		if( ! empty( $allcat ) )
		{
			$allcat[] = $cat;
		}
		
        $allcat = ( empty( $allcat ) ) ? ' AND catid = ' . $cat . ' ' : ' AND catid IN (' . implode( ',', $allcat ) . ') ';
    }
    else
    {
        $allcat = '';
    }
    $dbkey = $db->dblikeescape( $key );
    $sql .= "WHERE (`title` LIKE '%" . $dbkey . "%' OR `description` LIKE '%" . $dbkey . "%' OR `introtext` LIKE '%" . $dbkey . "%') " . $allcat . " AND  `status`='1'";
}
else
{
    $sql .= "WHERE `status`='1'";
}
$sql .= "ORDER BY `uploadtime` DESC LIMIT " .  ($page - 1) * $per_page . ", " . $per_page;

$result = $db->sql_query( $sql );

$result_all = $db->sql_query( "SELECT FOUND_ROWS()" );
list( $all_page ) = $db->sql_fetchrow( $result_all );

if ( ! empty( $all_page ) )
{
    $download_config = nv_mod_down_config();
    
    $array = array();
    $today = mktime( 0, 0, 0, date( "n" ), date( "j" ), date( "Y" ) );
    $yesterday = $today - 86400;
    
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
        
        $img = NV_UPLOADS_DIR . $row['fileimage'];
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
        );
        
        if ( $row['comment_allow'] )
        {
            $array[$row['id']]['comment_hits'] = ( int )$row['comment_hits'];
        }
    
    }
    $generate_page = nv_alias_page($page_title, $base_url, $all_page, $per_page, $page );
    
    $contents = theme_viewcat_download( $array, $download_config, "", $generate_page );
    if ($page > 1)
    {
        $page_title .= ' ' . NV_TITLEBAR_DEFIS . ' ' . $lang_global['page'] . ' ' . $page;
    }
}
else
{
    $contents = $lang_module['search_noresult'];
}

include ( NV_ROOTDIR . "/includes/header.php" );
echo nv_site_theme( $contents );
include ( NV_ROOTDIR . "/includes/footer.php" );

?>