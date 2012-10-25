<?php

/**
 * @Project  NUKEVIET V3
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2012 VINADES.,JSC. All rights reserved
 * @Createdate  05-05-2010
 */

if ( ! defined( 'NV_IS_MOD_SEARCH' ) ) die( 'Stop!!!' );

/**
 * nv_set_allow()
 * 
 * @param mixed $who
 * @param mixed $groups
 * @return
 */
function nv_faq_set_allow( $who, $groups )
{
    global $user_info;

    if ( ! $who or ( $who == 1 and defined( 'NV_IS_USER' ) ) or ( $who == 2 and defined( 'NV_IS_ADMIN' ) ) )
    {
        return true;
    } elseif ( $who == 3 and ! empty( $groups ) and defined( 'NV_IS_USER' ) and nv_is_in_groups( $user_info['in_groups'], $groups ) )
    {
        return true;
    }

    return false;
}

/**
 * nv_faq_list_cats()
 * 
 * @param mixed $module_data
 * @return
 */
function nv_faq_list_cats( $module_data )
{
    global $db;

    $sql = "SELECT `id`, `title`, `alias`, `who_view`, `groups_view` FROM `" . NV_PREFIXLANG . "_" . $module_data . "_categories` WHERE `status`=1";
    $result = $db->sql_query( $sql );

    $list = array();
    while ( $row = $db->sql_fetchrow( $result ) )
    {
        if ( nv_faq_set_allow( $row['who_view'], $row['groups_view'] ) )
        {
            $list[$row['id']] = array( //
                'id' => ( int )$row['id'], //
                'title' => $row['title'], //
                'alias' => $row['alias'] //
                );
        }
    }

    return $list;
}

$list_cats = nv_faq_list_cats( $m_values['module_data'] );
$in = implode( ",", array_keys( $list_cats ) );

$sql = "SELECT SQL_CALC_FOUND_ROWS `id`,`question`, `answer`, `catid` 
FROM `" . NV_PREFIXLANG . "_" . $m_values['module_data'] . "` 
WHERE `catid` IN (" . $in . ") 
AND 
(" . nv_like_logic( 'question', $dbkeyword, $logic ) . " 
OR " . nv_like_logic( 'answer', $dbkeyword, $logic ) . ") 
LIMIT " . $pages . "," . $limit;

$tmp_re = $db->sql_query( $sql );

$result = $db->sql_query( "SELECT FOUND_ROWS()" );
list( $all_page ) = $db->sql_fetchrow( $result );

if ( $all_page )
{
    $link = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $m_values['module_name'] . '&amp;' . NV_OP_VARIABLE . '=';

    while ( list( $id, $question, $answer, $catid ) = $db->sql_fetchrow( $tmp_re ) )
    {
        $result_array[] = array( //
            'link' => $link . $list_cats[$catid]['alias'] . '#faq' . $id, //
            'title' => BoldKeywordInStr( $question, $key, $logic ), //
            'content' => BoldKeywordInStr( $answer, $key, $logic ) //
            );
    }
}

?>