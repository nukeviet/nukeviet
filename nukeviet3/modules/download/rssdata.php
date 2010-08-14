<?php

/**
 * @Project NUKEVIET 3.0
 * @Author VINADES (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES. All rights reserved
 * @Createdate Apr 20, 2010 10:47:41 AM
 */

if ( ! defined( 'NV_IS_MOD_RSS' ) ) die( 'Stop!!!' );

$rssarray = array();

/*
//Cho nay khong hieu muc dich nen tam thoi chua viet.
//Dong chi Thao xem ho nhe

$result2 = $db->sql_query( "SELECT cid, parentid, title FROM " . NV_PREFIXLANG . "_download_categories ORDER BY weight" );

while ( list( $catid, $parentid, $title ) = $db->sql_fetchrow( $result2 ) )
{
    $alias = change_alias( $title );
    $numsubcat = $db->sql_numrows( $db->sql_query( 'SELECT cid FROM ' . NV_PREFIXLANG . '_download_categories WHERE parentid=' . $catid . '' ) );
    $resultsubcat = $db->sql_query( 'SELECT cid FROM ' . NV_PREFIXLANG . '_download_categories WHERE parentid="' . $catid . '"' );
    $subcatid = array();
    while ( list( $cid ) = $db->sql_fetchrow( $resultsubcat ) )
    {
        $subcatid[] = $cid;
    }
    $subcatid = implode( ',', $subcatid );
    $rssarray[$catid] = array( 'catid' => $catid, 'parentid' => $parentid, 'title' => $title, 'alias' => $alias, 'numsubcat' => $numsubcat, 'subcatid' => $subcatid, 'link' => NV_BASE_SITEURL . "?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_file . "&" . NV_OP_VARIABLE . "=rss&catid=" . $catid );
}*/

?>