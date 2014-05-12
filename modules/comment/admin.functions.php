<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Mon, 27 Jan 2014 00:08:04 GMT
 */

if ( ! defined( 'NV_ADMIN' ) or ! defined( 'NV_MAINFILE' ) or ! defined( 'NV_IS_MODADMIN' ) ) die( 'Stop!!!' );

$allow_func = array( 'main', 'edit', 'active', 'del' );
if( defined( 'NV_IS_SPADMIN' ) )
{
	$submenu['config'] = $lang_module['config'];
	$allow_func[] = 'config';
}

$site_mod_comm = array();
$result = $db->query( 'SELECT title, module_file, module_data, custom_title, admin_title, admins FROM ' . NV_MODULES_TABLE . ' ORDER BY weight' );
while( $row = $result->fetch() )
{
	$module_i = $row['title'];
	if( isset( $module_config[$module_i]['activecomm'] ) )
	{
		if( defined( 'NV_IS_SPADMIN' ) )
		{
			$allowed = true;
		}
		else
		{
			$adminscomm = explode( ',', $module_config[$module_i]['adminscomm'] );
			$allowed = ( in_array( $admin_info['admin_id'], $adminscomm ) ) ? true : false;
		}
		if( $allowed )
		{
			$site_mod_comm[$module_i] = $row;
		}
	}
}

define( 'NV_IS_FILE_ADMIN', true );