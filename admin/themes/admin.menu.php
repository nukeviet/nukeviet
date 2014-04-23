<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 07/30/2013 10:27
 */

if( ! defined( 'NV_ADMIN' ) ) die( 'Stop!!!' );

$submenu['config'] = $lang_module['config'];
$submenu['setuplayout'] = $lang_module['setup_layout'];
$submenu['blocks'] = $lang_module['blocks'];
$submenu['xcopyblock'] = $lang_module['xcopyblock'];

$allow_func = array( 'main', 'setuplayout', 'activatetheme', 'change_layout', 'config', 'blocks', 'block_content', 'block_config', 'front_outgroup', 'loadblocks', 'blocks_change_pos', 'blocks_change_order', 'blocks_change_order_group', 'blocks_del', 'blocks_del_group', 'blocks_func', 'blocks_reset_order', 'sort_order', 'xcopyblock', 'loadposition', 'xcopyprocess' );

if( defined( 'NV_IS_GODADMIN' ) )
{
	$submenu['autoinstall'] = $lang_module['autoinstall'];
	$allow_func[] = 'deletetheme';
	$allow_func[] = 'autoinstall';
	$allow_func[] = 'install_theme';
	$allow_func[] = 'install_check';
	$allow_func[] = 'package_theme';
	$allow_func[] = 'package_theme_module';
	$allow_func[] = 'getfile';
}