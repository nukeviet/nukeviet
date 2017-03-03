<?php

/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES.,JSC. All rights reserved
 * @Createdate Thu, 20 Sep 2012 04:05:46 GMT
 */

if ( ! defined( 'NV_IS_MOD_VIDEOCLIPS' ) ) die( 'Stop!!!' );

$url = array();
$cacheFile = NV_ROOTDIR . "/" . NV_CACHEDIR . "/" . NV_LANG_DATA . "_" . $module_name . "_Sitemap.cache";
$pa = NV_CURRENTTIME - 7200;

if ( ( $cache = nv_get_cache( $cacheFile ) ) != false and filemtime( $cacheFile ) >= $pa )
{
    $url = unserialize( $cache );
}
else
{
    $sql = "SELECT `alias`,`addtime` FROM `" . NV_PREFIXLANG . "_" . $module_data . "_clip` WHERE `status`=1";
    $result = $db->sql_query( $sql );
    while ( list( $alias, $publtime ) = $db->sql_fetchrow( $result ) )
    {
        $url[] = array( //
            'link' => NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $alias, //
            'publtime' => $publtime //
            );
    }
    
    $cache = serialize($url);
    nv_set_cache( $cacheFile, $cache );
}

nv_xmlSitemap_generate( $url );
die();
?>