<?php

/**
 * @Project NUKEVIET 3.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2012 VINADES.,JSC. All rights reserved
 * @Createdate 4/12/2010, 1:27
 */

if ( ! defined( 'NV_IS_MOD_FAQ' ) )
{
    die( 'Stop!!!' );
}

$url = array();
$cacheFile = NV_ROOTDIR . "/" . NV_CACHEDIR . "/" . NV_LANG_DATA . "_" . $module_name . "_Sitemap.cache";
$pa = NV_CURRENTTIME - 7200;

if ( ( $cache = nv_get_cache( $cacheFile ) ) != false AND filemtime($cacheFile) >= $pa )
{
    $url = unserialize( $cache );
}
else
{
    $list_cats = nv_list_cats();
    $in = array_keys( $list_cats );
    $in = implode( ",", $in );

    $sql = "SELECT `id`, `catid`, `addtime` 
        FROM `" . NV_PREFIXLANG . "_" . $module_data . "` WHERE `catid` IN (" . $in . ") 
        AND `status`=1 ORDER BY `weight` ASC LIMIT 1000";
    $result = $db->sql_query( $sql );

    while ( list( $id, $cid, $publtime ) = $db->sql_fetchrow( $result ) )
    {
        $url[] = array( //
            'link' => NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $list_cats[$cid]['alias'] . "#faq" . $id, //
            'publtime' => $publtime //
            );
    }
    
    $cache = serialize($url);
    nv_set_cache( $cacheFile, $cache );
}

nv_xmlSitemap_generate( $url );
die();

?>