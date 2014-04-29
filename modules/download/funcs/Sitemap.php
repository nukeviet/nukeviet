<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 4/12/2010, 1:27
 */

if( ! defined( 'NV_IS_MOD_DOWNLOAD' ) ) die( 'Stop!!!' );

$url = array();
$cacheFile = NV_LANG_DATA . '_Sitemap_' . NV_CACHE_PREFIX . '.cache';
$pa = NV_CURRENTTIME - 7200;

if( ( $cache = nv_get_cache( $module_name, $cacheFile ) ) != false and filemtime( NV_ROOTDIR . '/' . NV_CACHEDIR . '/' . $module_name . '/' . $cacheFile ) >= $pa )
{
	$url = unserialize( $cache );
}
else
{
	$list_cats = nv_list_cats();
	$in = array_keys( $list_cats );
	$in = implode( ',', $in );

	$db->sqlreset()
		->select( 'catid, alias, uploadtime' )
		->from( NV_PREFIXLANG . '_' . $module_data )
		->where( 'catid IN (' . $in . ') AND status=1' )
		->order( 'uploadtime DESC' )
		->limit( 1000 );
	$result = $db->query( $db->sql() );
	while( list( $cid, $alias, $publtime ) = $result->fetch( 3 ) )
	{
		$url[] = array(
			'link' => NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $list_cats[$cid]['alias'] . '/' . $alias . $global_config['rewrite_exturl'], //
			'publtime' => $publtime
		);
	}

	$cache = serialize( $url );
	nv_set_cache( $module_name, $cacheFile, $cache );
}

nv_xmlSitemap_generate( $url );
die();