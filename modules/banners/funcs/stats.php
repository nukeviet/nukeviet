<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 3/25/2010 21:7
 */

if( ! defined( 'NV_IS_MOD_BANNERS' ) ) die( 'Stop!!!' );

$page_title = $lang_module['stats_views'];

global $global_config, $module_name, $module_info, $lang_module;

if( defined( 'NV_IS_BANNER_CLIENT' ) )
{
	$xtpl = new XTemplate( 'stats.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_file );
	$xtpl->assign( 'NV_BASE_URLSITE', NV_BASE_SITEURL );
	$xtpl->assign( 'charturl', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=viewmap' );
	$xtpl->assign( 'LANG', $lang_module );
	$xtpl->assign( 'clientinfo_link', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=clientinfo' );
	$xtpl->assign( 'clientinfo_addads', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=addads' );
	$xtpl->assign( 'clientinfo_stats', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=stats' );
	$xtpl->parse( 'main.management' );

	$sql = "SELECT id,title FROM " . NV_BANNERS_GLOBALTABLE. "_rows WHERE act='1' AND clid=" . $banner_client_info['id'] . " ORDER BY id ASC";
	$result = $db->query( $sql );

	while( $row = $result->fetch() )
	{
		$xtpl->assign( 'ads', $row );
		$xtpl->parse( 'main.ads' );
	}

	for( $i = 1; $i <= 12; ++$i )
	{
		$xtpl->assign( 'month', $i );
		$xtpl->parse( 'main.month' );
	}

	$xtpl->parse( 'main' );
	$contents .= $xtpl->text( 'main' );
}
else
{
	$contents = '';
}

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme( $contents );
include NV_ROOTDIR . '/includes/footer.php';