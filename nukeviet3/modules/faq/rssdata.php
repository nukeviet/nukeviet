<?php

/**
 * @Project NUKEVIET 3.0
 * @Author VINADES (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES. All rights reserved
 * @Createdate Apr 20, 2010 10:47:41 AM
 */

if ( ! defined( 'NV_IS_MOD_RSS' ) ) die( 'Stop!!!' );

$rssarray = array();

$result2 = $db->sql_query( "SELECT `id`, `parentid`, `title`, `alias` FROM `" . NV_PREFIXLANG . "_" . $module_data . "_categories` ORDER BY `weight` ASC" );
while ( list( $catid, $parentid, $title, $alias ) = $db->sql_fetchrow( $result2 ) )
{
    $resultsubcat = $db->sql_query( 'SELECT `id` FROM `' . NV_PREFIXLANG . '_' . $module_data . '"_categories` WHERE `parentid`="' . $catid . '"' );
    $subcatid = array();
    while ( list( $cid ) = $db->sql_fetchrow( $resultsubcat ) )
    {
        $subcatid[] = $cid;
    }
    $numsubcat = count( $numsubcat );
    $subcatid = implode( ',', $subcatid );

    $rssarray[$catid] = array( 'catid' => $catid, 'parentid' => $parentid, 'title' => $title, 'alias' => $alias, 'numsubcat' => $numsubcat, 'subcatid' => $subcatid, 'link' => NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_file . "&amp;" . NV_OP_VARIABLE . "=rss/" . $alias );
}

?>