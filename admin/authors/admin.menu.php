<?php

/**
 * @Project NUKEVIET 3.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2013 VINADES.,JSC. All rights reserved
 * @createdate 07/30/2013 10:27
 */

if( ! defined( 'NV_ADMIN' ) ) die( 'Stop!!!' );

$allow_func = array( 'main', 'edit' );

if( empty( $global_config['spadmin_add_admin'] ) AND $global_config['idsite'] > 0 )
{
	// Fix add admin for subsite
	$global_config['spadmin_add_admin'] = 1;
}

if( defined( "NV_IS_GODADMIN" ) or ( defined( "NV_IS_SPADMIN" ) and $global_config['spadmin_add_admin'] == 1 ) )
{
	$allow_func[] = "add";
	$allow_func[] = "suspend";
	$allow_func[] = "del";
}

if( defined( "NV_IS_GODADMIN" ) )
{
	$submenu['module'] = $lang_module['module_admin'];
	$submenu['config'] = $lang_module['config'];
	$allow_func[] = "module";
	$allow_func[] = "config";
}

?>