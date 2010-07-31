<?php

/**
 * @Project  NUKEVIET V3
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES., JSC. All rights reserved
 * @Createdate  05-05-2010
 */
/*****..............................
  function result_news_theme : 
 * $module_name : module name when include this file
 * $keyword : keyword want searching
 * $xtpl : xtemplate for search result in module search
 * -- conenct to module search ---- +
 */
function result_download_theme ( $m_info, $keyword, $xtpl, $limit, $pages, $per_pages )
{
    global $db;
    $module_name = $m_info['module_name'];
    $module_data = $m_info['module_data'];
    $dbkeyword = $db->dblikeescape($keyword);
    $sql_search1 = "SELECT id,title,description FROM `" . NV_PREFIXLANG . "_" . $module_data . "` 
	WHERE title LIKE '%" . $dbkeyword . "%' OR description LIKE '%" . $dbkeyword . "%'";
    
    $sql_search2 = "SELECT id,title,description FROM `" . NV_PREFIXLANG . "_" . $module_data . "` 
	WHERE title LIKE '%" . $dbkeyword . "%' OR description LIKE '%" . $dbkeyword . "%' LIMIT 0," . $limit;
    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    $URLLink = NV_BASE_SITEURL . "?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . '&op=view&id=';
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    if ( ! empty( $xtpl ) )
    {
        $re_all = $db->sql_query( $sql_search1 );
        $re = $db->sql_query( $sql_search2 );
        if ( $limit != 0 ) $tmp_re = $re;
        else $tmp_re = $re_all;
        
        while ( list( $id, $tilterow, $content ) = $db->sql_fetchrow( $tmp_re ) )
        {
            $xtpl->assign( 'LINK', $URLLink . $id );
            $xtpl->assign( 'TITLEROW', BoldKeywordInStr( $tilterow, $keyword ) );
            $xtpl->assign( 'CONTENT', BoldKeywordInStr( $content, $keyword ) );
            $xtpl->parse( 'results.loop_result.result' );
        }
        if ( $re_all != NULL ) return $db->sql_numrows( $re_all );
    }
    return 0;
}
?>