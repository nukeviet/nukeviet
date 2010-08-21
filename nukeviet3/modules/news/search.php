<?php

/**
 * @Project  NUKEVIET V3
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES., JSC. All rights reserved
 * @Createdate  03-05-2010
 */

if ( ! defined( 'NV_IS_MOD_SEARCH' ) ) die( 'Stop!!!' );

/**
 * result_news_theme()
 * 
 * @param mixed $m_info
 * @param mixed $keyword
 * @param mixed $xtpl
 * @param mixed $limit
 * @param mixed $pages
 * @param mixed $per_pages
 * @return
 */
function result_news_theme( $m_info, $keyword, $xtpl, $limit, $pages, $per_pages )
{
    global $db;

    $module_name = $m_info['module_name'];
    $module_data = $m_info['module_data'];
    $dbkeyword = $db->dblikeescape( $keyword );

    $sql = "FROM `" . NV_PREFIXLANG . "_" . $module_data . "_rows` WHERE `title` LIKE '%" . $dbkeyword . "%' OR `bodytext` LIKE '%" . $dbkeyword . "%' OR `hometext` LIKE '%" . $dbkeyword . "%' 
    AND ( `publtime` < " . NV_CURRENTTIME . " AND (`exptime`=0 OR `exptime`>" . NV_CURRENTTIME . ") )";

    if ( ! empty( $xtpl ) )
    {
        $result = $db->sql_query( "SELECT COUNT(*) AS count " . $sql );
        list( $count ) = $db->sql_fetchrow( $result );

        if ( $count )
        {
            $array_cat_alias = array();
            $array_cat_alias[0] = "other";

            $sql_cat = "SELECT `catid`, `alias` FROM `" . NV_PREFIXLANG . "_" . $module_data . "_cat`";
            $re_cat = $db->sql_query( $sql_cat );
            while ( list( $catid, $alias ) = $db->sql_fetchrow( $re_cat ) )
            {
                $array_cat_alias[$catid] = $alias;
            }

            $URLLink = NV_BASE_SITEURL . "?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . '&amp;' . NV_OP_VARIABLE . '=';

            if ( $limit )
            {
                $tmp_re = $db->sql_query( "SELECT `id`,`title`,`alias`,`listcatid`,`hometext`,`bodytext` " . $sql . " LIMIT 0," . $limit );
            }
            else
            {
                $tmp_re = $db->sql_query( "SELECT `id`,`title`,`alias`,`listcatid`,`hometext`,`bodytext` " . $sql );
            }

            while ( list( $id, $tilterow, $alias, $listcatid, $hometext, $bodytext ) = $db->sql_fetchrow( $tmp_re ) )
            {
                $content = $hometext . $bodytext;
                $catid = end( explode( ",", $listcatid ) );

                $url = $URLLink . $array_cat_alias[$catid] . '/' . $alias . "-" . $id;

                $xtpl->assign( 'LINK', $url );
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