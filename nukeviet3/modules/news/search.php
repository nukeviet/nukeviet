<?php

/**
 * @Project  NUKEVIET V3
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES., JSC. All rights reserved
 * @Createdate  03-05-2010
 */

if ( ! defined( 'NV_IS_MOD_SEARCH' ) ) die( 'Stop!!!' );

$sql = "SELECT SQL_CALC_FOUND_ROWS `id`,`title`,`alias`,`listcatid`,`hometext`,`bodytext` 
FROM `" . NV_PREFIXLANG . "_" . $m_values['module_data'] . "_rows` 
WHERE (" . nv_like_logic( 'title', $dbkeyword, $logic ) . " 
OR " . nv_like_logic( 'bodytext', $dbkeyword, $logic ) . " 
OR " . nv_like_logic( 'hometext', $dbkeyword, $logic ) . ") 
AND ( `publtime` < " . NV_CURRENTTIME . " AND (`exptime`=0 OR `exptime`>" . NV_CURRENTTIME . ") ) 
LIMIT " . $pages . "," . $limit;

$tmp_re = $db->sql_query( $sql );

$result = $db->sql_query( "SELECT FOUND_ROWS()" );
list( $all_page ) = $db->sql_fetchrow( $result );

if ( $all_page )
{
    $array_cat_alias = array();
    $array_cat_alias[0] = "other";

    $sql_cat = "SELECT `catid`, `alias` FROM `" . NV_PREFIXLANG . "_" . $m_values['module_data'] . "_cat`";
    $re_cat = $db->sql_query( $sql_cat );
    while ( list( $catid, $alias ) = $db->sql_fetchrow( $re_cat ) )
    {
        $array_cat_alias[$catid] = $alias;
    }

    $link = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $m_values['module_name'] . '&amp;' . NV_OP_VARIABLE . '=';

    while ( list( $id, $tilterow, $alias, $listcatid, $hometext, $bodytext ) = $db->sql_fetchrow( $tmp_re ) )
    {
        $content = $hometext . $bodytext;
        $catid = explode( ",", $listcatid );
        $catid = end( $catid );

        $url = $link . $array_cat_alias[$catid] . '/' . $alias . "-" . $id;

        $result_array[] = array( //
            'link' => $url, //
            'title' => BoldKeywordInStr( $tilterow, $key, $logic ), //
            'content' => BoldKeywordInStr( $content, $key, $logic ) //
            );
    }
}

?>