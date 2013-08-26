<?php

/**
 * @Project NUKEVIET 3.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2013 VINADES.,JSC. All rights reserved
 * @createdate 07/30/2013 10:27
 */

if( ! defined( 'NV_ADMIN' ) ) die( 'Stop!!!' );

$submenu['siteDiagnostic'] = $lang_module['siteDiagnostic'];
$submenu['keywordRank'] = $lang_module['keywordRank'];
$submenu['sitemapPing'] = $lang_module['sitemapPing'];
$submenu['googleplus'] = $lang_module['googleplus'];
$submenu['pagetitle'] = $lang_module['pagetitle'];
$submenu['metatags'] = $lang_module['metaTagsConfig'];
$submenu['statistics'] = $lang_module['global_statistics'];
if( empty( $global_config['idsite'] ) )
{
	$submenu['rpc'] = $lang_module['rpc_setting'];
}
if( defined( 'NV_IS_GODADMIN' ) )
{
	$submenu['robots'] = $lang_module['robots'];
}

?>