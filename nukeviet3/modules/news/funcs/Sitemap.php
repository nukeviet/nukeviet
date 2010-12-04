<?php

/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES.,JSC. All rights reserved
 * @Createdate 4/12/2010, 1:27
 */

if ( ! defined( 'NV_IS_MOD_NEWS' ) )
{
    die( 'Stop!!!' );
}

$url = array();
$cacheFile = NV_ROOTDIR . "/" . NV_CACHEDIR . "/" . NV_LANG_DATA . "_" . $module_name . "_Sitemap.cache";
$pa = NV_CURRENTTIME - 7200;

if ( ( $cache = nv_get_cache( $cacheFile ) ) != false and filemtime( $cacheFile ) >= $pa )
{
    $url = unserialize( $cache );
}
else
{
    $sql = "SELECT `id`, `listcatid`, `publtime`, `alias` FROM `" . NV_PREFIXLANG . "_" . $module_data . "_rows` WHERE `publtime` < " . NV_CURRENTTIME . " AND (`exptime`=0 OR `exptime` >" . NV_CURRENTTIME . ") ORDER BY `publtime` DESC LIMIT 1000";
    $result = $db->sql_query( $sql );
    $url = array();

    while ( list( $id, $listcatid, $publtime, $alias ) = $db->sql_fetchrow( $result ) )
    {
        $arr_catid = explode( ',', $listcatid );
        $catid_i = end( $arr_catid );
        $catalias = $global_array_cat[$catid_i]['alias'];
        $url[] = array( //
            'link' => NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $catalias . '/' . $alias . '-' . $id, //
            'publtime' => $publtime //
            );
    }

    $cache = serialize( $url );
    nv_set_cache( $cacheFile, $cache );
}

nv_xmlSitemap_generate( $url );
die();

?>