<?php

/**
 * @Project NUKEVIET 3.0
 * @Author VINADES (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES. All rights reserved
 * @Createdate Apr 20, 2010 10:47:41 AM
 */

if ( ! defined( 'NV_IS_MOD_RSS' ) ) die( 'Stop!!!' );
$rssarray = array();
$result2 = $db->sql_query( "SELECT catid, parentid, title, alias FROM `" . NV_PREFIXLANG . "_" . $module_data . "_cat` ORDER BY parentid ASC, weight ASC" );
while ( list( $catid, $parentid, $title, $alias ) = $db->sql_fetchrow( $result2 ) )
{
    $resultsubcat = $db->sql_query( "SELECT `catid` FROM `" . NV_PREFIXLANG . "_" . $module_data . "_cat` WHERE `parentid`=" . $catid );
    $numsubcat = $db->sql_numrows( $resultsubcat );
    $subcatid = array();
    while ( list( $cid ) = $db->sql_fetchrow( $resultsubcat ) )
    {
        $subcatid[] = $cid;
    }
    $rssarray[$catid] = array( 
        'catid' => $catid, 'parentid' => $parentid, 'title' => $title, 'alias' => $alias, 'numsubcat' => $numsubcat, 'subcatid' => implode( ',', $subcatid ), 'link' => NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_title . "&amp;" . NV_OP_VARIABLE . "=rss/" . $alias 
    );
}

?>