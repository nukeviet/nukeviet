<?php

/**
 * @Project NUKEVIET 3.0
 * @Author VINADES (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES. All rights reserved
 * @Createdate Apr 20, 2010 10:47:41 AM
 */

if ( ! defined( 'NV_IS_MOD_RSS' ) ) die( 'Stop!!!' );

$rssarray = array();
//$rssarray[] = array( 'catid' => 0, 'parentid' => 0, 'title' => '', 'link' =>  '');


global $db_config;
$result2 = $db->sql_query( "SELECT catid, parentid, " . NV_LANG_DATA . "_title, " . NV_LANG_DATA . "_alias FROM " . $db_config['prefix'] . "_" . $mod_data . "_catalogs ORDER BY `weight`,`order`" );
while ( list( $catid, $parentid, $title, $alias ) = $db->sql_fetchrow( $result2 ) )
{
    $rssarray[$catid] = array( 'catid' => $catid, 'parentid' => $parentid, 'title' => $title, 'link' => NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $mod_name . "&amp;" . NV_OP_VARIABLE . "=rss/" . $alias );
}

?>