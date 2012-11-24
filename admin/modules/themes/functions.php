<?php

/**
 * @Project NUKEVIET 3.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2012 VINADES.,JSC. All rights reserved
 * @Createdate 2-2-2010 1:58
 */

if( ! defined( 'NV_ADMIN' ) or ! defined( 'NV_MAINFILE' ) or ! defined( 'NV_IS_MODADMIN' ) ) die( 'Stop!!!' );

$submenu['setuplayout'] = $lang_module['setup_layout'];
$submenu['blocks'] = $lang_module['blocks'];
$submenu['xcopyblock'] = $lang_module['xcopyblock'];

$allow_func = array( 'main', 'setuplayout', 'activatetheme', 'deletetheme', 'change_layout', 'blocks', 'block_content', 'block_config', 'front_outgroup', 'loadblocks', 'blocks_change_pos', 'blocks_change_order', 'blocks_change_order_group', 'blocks_del', 'blocks_del_group', 'blocks_func', 'blocks_reset_order', 'sort_order', 'xcopyblock', 'loadposition', 'xcopyprocess' );

if( defined( "NV_IS_GODADMIN" ) )
{
	$submenu['autoinstall'] = $lang_module['autoinstall'];
	$allow_func[] = 'deletetheme';
	$allow_func[] = 'autoinstall';
	$allow_func[] = 'install_theme';
	$allow_func[] = 'install_check';
	$allow_func[] = 'package_theme';
	$allow_func[] = 'package_theme_module';
	$allow_func[] = "getfile";
}

if( $module_name == "themes" )
{
	$menu_top = array( "title" => $module_name, "module_file" => "", "custom_title" => $lang_global['mod_themes'] );
	
	define( 'NV_IS_FILE_THEMES', true );
}

?>