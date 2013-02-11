<?php

/**
 * @Project NUKEVIET 3.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2012 VINADES.,JSC. All rights reserved
 * @createdate 12/31/2009 2:29
 */

if( ! defined( 'NV_ADMIN' ) or ! defined( 'NV_MAINFILE' ) or ! defined( 'NV_IS_MODADMIN' ) ) die( 'Stop!!!' );

$submenu['clearsystem'] = $lang_module['clearsystem'];
$submenu['siteDiagnostic'] = $lang_module['siteDiagnostic'];
$submenu['keywordRank'] = $lang_module['keywordRank'];
$submenu['sitemapPing'] = $lang_module['sitemapPing'];
$submenu['checkupdate'] = $lang_module['checkupdate'];
$submenu['config'] = $lang_module['config'];
if( NV_LANG_INTERFACE == 'vi' )
{
	$submenu['mudim'] = $lang_module['mudim'];
}

if( $module_name == "webtools" )
{
	$allow_func = array( 'main', 'clearsystem', 'sitemapPing', 'checkupdate', 'siteDiagnostic', 'keywordRank', 'config', 'mudim' );
	$menu_top = array(
		"title" => $module_name,
		"module_file" => "",
		"custom_title" => $lang_global['mod_webtools']
	);

	if( defined( 'NV_IS_GODADMIN' ) )
	{
		$allow_func[] = "deleteupdate";
	}

	define( 'NV_IS_FILE_WEBTOOLS', true );
}

?>