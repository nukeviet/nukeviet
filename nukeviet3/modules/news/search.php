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
function result_news_theme ( $m_info, $keyword, $xtpl, $limit, $pages, $per_pages )
{
    global $db;
    $module_name = $m_info['module_name'];
    $module_data = $m_info['module_data'];
    $dbkeyword = $db->dblikeescape($keyword);
    /* used LIKE */
    // SQL : count record -----------------------------------//
    $sql_search1 = "SELECT id,title,alias,listcatid,hometext,bodytext FROM `" . NV_PREFIXLANG . "_" . $module_data . "_rows` 
	WHERE ( title LIKE '%" . $dbkeyword . "%' OR bodytext LIKE '%" . $dbkeyword . "%'  OR hometext LIKE '%" . $dbkeyword . "%' ) 
	AND ( `publtime` < " . NV_CURRENTTIME . " AND (`exptime`=0 OR `exptime`>" . NV_CURRENTTIME . ") )";
    // SQL: show in search all ---------------------------//
    $sql_search2 = "SELECT id,title,alias,listcatid,hometext,bodytext FROM `" . NV_PREFIXLANG . "_" . $module_data . "_rows` 
	WHERE ( title LIKE '%" . $dbkeyword . "%' OR bodytext LIKE '%" . $dbkeyword . "%'  OR hometext LIKE '%" . $dbkeyword . "%' )
	AND ( `publtime` < " . NV_CURRENTTIME . " AND (`exptime`=0 OR `exptime`>" . NV_CURRENTTIME . ") ) LIMIT 0," . $limit;
    // SQL : show have pages -----------------------------------------//
    $sql_search3 = "SELECT id,title,alias,listcatid,hometext,bodytext FROM `" . NV_PREFIXLANG . "_" . $module_data . "_rows` 
	WHERE ( title LIKE '%" . $dbkeyword . "%' OR bodytext LIKE '%" . $dbkeyword . "%'  OR hometext LIKE '%" . $dbkeyword . "%' ) 
	AND ( `publtime` < " . NV_CURRENTTIME . " AND (`exptime`=0 OR `exptime`>" . NV_CURRENTTIME . ") ) ORDER BY ID DESC  LIMIT " . $pages . "," . $per_pages;
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    $URLLink = NV_BASE_SITEURL . "?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . '&' . NV_OP_VARIABLE . "=";
    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    if ( ! empty( $xtpl ) )
    {
        $array_cat_alias = array();
        $array_cat_alias[0] = "other";
        $sql_cat = "SELECT catid, alias FROM `" . NV_PREFIXLANG . "_" . $module_data . "_cat`";
        $re_cat = $db->sql_query( $sql_cat );
        while ( list( $catid, $alias ) = $db->sql_fetchrow( $re_cat ) )
        {
            $array_cat_alias[$catid] = $alias;
        }
        
        $re = $db->sql_query( $sql_search2 ); // get record have limit
        $re_all = $db->sql_query( $sql_search1 ); // get all record
        $numRe = 0;
        if ( $limit != 0 )
        {
            $tmp_re = $re; // set have limit record
        }
        else
        {
            $tmp_re = $db->sql_query( $sql_search3 ); // get all record have pages
        }
        while ( list( $id, $tilterow, $alias, $listcatid, $hometext, $bodytext ) = $db->sql_fetchrow( $tmp_re ) )
        {
            $content = $hometext . $bodytext;
            $catid = end( explode( ",", $listcatid ) ); // if list cat id have over 2
            $url = $URLLink . $array_cat_alias[$catid] . '/' . $alias . "-" . $id;
            $xtpl->assign( 'LINK', $url );
            $xtpl->assign( 'TITLEROW', BoldKeywordInStr( $tilterow, $keyword ) );
            $xtpl->assign( 'CONTENT', BoldKeywordInStr( $content, $keyword ) );
            $xtpl->parse( 'results.loop_result.result' );
        }
        if ( $re_all != NULL )
        {
            return $db->sql_numrows( $re_all );
        }
    }
    return 0;
}
?>