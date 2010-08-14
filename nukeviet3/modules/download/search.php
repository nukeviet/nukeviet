<?php

/**
 * @Project  NUKEVIET V3
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES., JSC. All rights reserved
 * @Createdate  05-05-2010
 */

/**
 * result_download_theme()
 * 
 * @param mixed $m_info
 * @param mixed $keyword
 * @param mixed $xtpl
 * @param mixed $limit
 * @param mixed $pages
 * @param mixed $per_pages
 * @return
 */
function result_download_theme( $m_info, $keyword, $xtpl, $limit, $pages, $per_pages )
{
    global $db;

    $module_name = $m_info['module_name'];
    $module_data = $m_info['module_data'];
    $dbkeyword = $db->dblikeescape( $keyword );

    $sql = "FROM `" . NV_PREFIXLANG . "_" . $module_data . "` WHERE `title` LIKE '%" . $dbkeyword . "%' OR `description` LIKE '%" . $dbkeyword . "%'";

    if ( ! empty( $xtpl ) )
    {
        $result = $db->sql_query( "SELECT COUNT(*) AS count " . $sql );
        list( $count ) = $db->sql_fetchrow( $result );

        if ( $count )
        {
            $URLLink = NV_BASE_SITEURL . "?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . '&amp;file=';

            if ( $limit )
            {
                $tmp_re = $db->sql_query( "SELECT `alias`,`title`,`description` " . $sql . " LIMIT 0," . $limit );
            }
            else
            {
                $tmp_re = $db->sql_query( "SELECT `alias`,`title`,`description` " . $sql );
            }

            while ( list( $alias, $tilterow, $content ) = $db->sql_fetchrow( $tmp_re ) )
            {
                $xtpl->assign( 'LINK', $URLLink . $alias );
                $xtpl->assign( 'TITLEROW', BoldKeywordInStr( $tilterow, $keyword ) );
                $xtpl->assign( 'CONTENT', BoldKeywordInStr( $content, $keyword ) );
                $xtpl->parse( 'results.loop_result.result' );
            }

            return $count;
        }
    }

    return 0;
}

?>