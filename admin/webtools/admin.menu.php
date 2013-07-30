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
if( empty( $global_config['idsite'] ) )
{
	$submenu['clearsystem'] = $lang_module['clearsystem'];
	$submenu['checkupdate'] = $lang_module['checkupdate'];
	$submenu['rpc'] = $lang_module['rpc_setting'];
	$submenu['config'] = $lang_module['config'];
	if( NV_LANG_INTERFACE == 'vi' )
	{
		$submenu['mudim'] = $lang_module['mudim'];
	}
}

?>