<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 12/31/2009 2:29
 */

if( ! defined( 'NV_ADMIN' ) or ! defined( 'NV_MAINFILE' ) or ! defined( 'NV_IS_MODADMIN' ) ) die( 'Stop!!!' );

$allow_func = array( 'main', 'clearsystem' );
if( empty( $global_config['idsite'] ) )
{
	$allow_func[] = 'checkupdate';
	$allow_func[] = 'config';
	$allow_func[] = 'mudim';
}

$menu_top = array(
	'title' => $module_name,
	'module_file' => '',
	'custom_title' => $lang_global['mod_webtools']
);

if( defined( 'NV_IS_GODADMIN' ) )
{
	$allow_func[] = 'deleteupdate';
}

define( 'NV_IS_FILE_WEBTOOLS', true );