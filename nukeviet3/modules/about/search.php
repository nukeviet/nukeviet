<?php
/**
 * @Project  NUKEVIET V3
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES., JSC. All rights reserved
 * @Createdate  03-05-2010
 */

if ( ! defined( 'NV_IS_MOD_SEARCH' ) ) die( 'Stop!!!' );

/*****..............................
  function result_news_theme : 
 * $module_name : module name when include this file
 * $keyword : keyword want searching
 * $xtpl : xtemplate for search result in module search
 * -- conenct to module search ---- +
 */
function result_about_theme ( $m_info, $keyword, $xtpl, $limit, $pages, $per_pages )
{
    global $db;
    /* used LIKE */
    $module_name = $m_info['module_name'];
    $module_data = $m_info['module_data'];
    $dbkeyword = $db->dblikeescape($keyword);
    // SQL : count record -----------------------------------//
    $sql_search1 = "SELECT id,title,alias,bodytext FROM `" . NV_PREFIXLANG . "_" . $module_data . "` 
	WHERE ( title LIKE '%" . $keyword . "%' OR bodytext LIKE '%" . $dbkeyword . "%') ";
    // SQL: show in search all ---------------------------//
    $sql_search2 = "SELECT id,title,alias,bodytext FROM `" . NV_PREFIXLANG . "_" . $module_data . "` 
	WHERE ( title LIKE '%" . $keyword . "%' OR bodytext LIKE '%" . $dbkeyword . "%' )
	LIMIT 0," . $limit;
    // SQL : show have pages -----------------------------------------//
    $sql_search3 = "SELECT id,title,alias,bodytext FROM `" . NV_PREFIXLANG . "_" . $module_data . "` 
	WHERE ( title LIKE '%" . $keyword . "%' OR bodytext LIKE '%" . $dbkeyword . "%' ) 
	ORDER BY ID DESC  LIMIT " . $pages . "," . $per_pages;
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    $URLLink = NV_BASE_SITEURL . "?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . '&op=';
    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    if ( ! empty( $xtpl ) )
    {
        $re = $db->sql_query( $sql_search2 ); // get record have limit
        $re_all = $db->sql_query( $sql_search1 ); // get all record
        $numRe = 0;
        if ( $limit != 0 ) $tmp_re = $re; // set have limit record
        else
        {
            $tmp_re = $db->sql_query( $sql_search3 ); // get all record have pages
        }
        while ( list( $id, $tilterow, $alias, $content ) = $db->sql_fetchrow( $tmp_re ) )
        {
            $url = $URLLink . $alias . "-" . $id;
            $xtpl->assign( 'LINK', $url );
            $xtpl->assign( 'TITLEROW', BoldKeywordInStr( $tilterow, $keyword ) );
            $xtpl->assign( 'CONTENT', BoldKeywordInStr( $content, $keyword ) );
            $xtpl->parse( 'results.loop_result.result' );
        }
        if ( $re_all != NULL ) return $db->sql_numrows( $re_all );
    }
    return 0;
}
?>