<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Mon, 31 Mar 2014 11:25:10 GMT
 */

if ( ! defined( 'NV_MAINFILE' ) ) die( 'Stop!!!' );

function nv4_block_MENU( $block_config )
{
	global $global_config, $db, $site_mods, $module_name;

	$mod_name = 'news'; // or $module_name;
	$list = array();
	if( isset( $site_mods[$mod_name] ) )
	{
		$mod_file = $site_mods[$mod_name]['module_file'];
		$mod_data = $site_mods[$mod_name]['module_data'];
	}
	$xtpl = new XTemplate( 'global.menu.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/blocks' );
	$xtpl->assign( 'NV_BASE_SITEURL', NV_BASE_SITEURL );
	$xtpl->assign( 'TEMPLATE', $global_config['module_theme'] );
	
	$sql = 'SELECT `title` , `dis` , `img` FROM `new-foot` LIMIT 1';
	$q = $db->query($sql);
	$arr = $q->fetch();
	
	
	$xtpl->assign('DATA',$arr);
/*
	foreach( $list as $row )
	{
		$xtpl->assign( 'ROW', $row );
		$xtpl->parse( 'main.loop' );
	}
*/
	$xtpl->parse( 'main' );
	return $xtpl->text( 'main' );
}

if( defined( 'NV_SYSTEM' ) )
{
	$content = nv4_block_MENU( $block_config );
}

?>