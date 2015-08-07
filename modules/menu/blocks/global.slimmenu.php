<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Sun, 04 May 2014 12:41:32 GMT
 */

if( ! defined( 'NV_MAINFILE' ) ) die( 'Stop!!!' );

if( defined( 'NV_IS_FILE_THEMES' ) )
{
	//include config theme
	require NV_ROOTDIR . '/modules/menu/menu_config.php';
}

if( defined( 'NV_SYSTEM' ) )
{
	require_once NV_ROOTDIR . '/modules/menu/menu_blocks.php';
	$content = nv_menu_blocks( $block_config );
}