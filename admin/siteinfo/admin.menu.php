<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 07/30/2013 10:27
 */

if( ! defined( 'NV_ADMIN' ) ) die( 'Stop!!!' );

$allow_func = array( 'main' );
if( defined( 'NV_IS_GODADMIN' ) )
{
	$submenu['system_info'] = $lang_module['site_configs_info'];

	if( nv_function_exists( 'phpinfo' ) )
	{
		$submenu['php_info_configuration'] = $lang_module['configuration_php'];
		$submenu['php_info_modules'] = $lang_module['extensions'];
		$submenu['php_info_environment'] = $lang_module['environment_php'];
		$submenu['php_info_variables'] = $lang_module['variables_php'];

		$allow_func[] = 'php_info_configuration';
		$allow_func[] = 'php_info_modules';
		$allow_func[] = 'php_info_environment';
		$allow_func[] = 'php_info_variables';
	}

	$allow_func[] = 'system_info';
	$allow_func[] = 'checkchmod';
}

if( defined( 'NV_IS_SPADMIN' ) )
{
	$allow_func[] = 'logs';
	$submenu['logs'] = $lang_module['logs_title'];
}