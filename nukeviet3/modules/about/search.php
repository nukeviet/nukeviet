<?php

/**
 * @Project  NUKEVIET V3
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES., JSC. All rights reserved
 * @Createdate  03-05-2010
 */

if ( ! defined( 'NV_IS_MOD_SEARCH' ) ) die( 'Stop!!!' );

$sql = "FROM `" . NV_PREFIXLANG . "_" . $m_values['module_data'] . "` WHERE `title` LIKE '%" . $dbkeyword . "%' OR `bodytext` LIKE '%" . $dbkeyword . "%'";
$result = $db->sql_query( "SELECT COUNT(*) AS count " . $sql );
list( $all_page ) = $db->sql_fetchrow( $result );

if ( $all_page )
{
    $link = NV_BASE_SITEURL . "?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $m_values['module_name'] . '&amp;' . NV_OP_VARIABLE . '=';
    $tmp_re = $db->sql_query( "SELECT `id`,`title`,`alias`,`bodytext` " . $sql . " LIMIT " . $pages . "," . $limit );

    while ( list( $id, $tilterow, $alias, $content ) = $db->sql_fetchrow( $tmp_re ) )
    {
        $url = $link . $alias . "-" . $id;

        $result_array[] = array( //
            'link' => $link . $alias . "-" . $id, //
            'title' => BoldKeywordInStr( $tilterow, $key ), //
            'content' => BoldKeywordInStr( $content, $key ) //
            );
    }
}

?>