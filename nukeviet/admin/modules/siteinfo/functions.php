<?php

/**
 * @Project NUKEVIET 3.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @copyright 2009
 * @createdate 12/31/2009 5:50
 */

if( ! defined( 'NV_ADMIN' ) or ! defined( 'NV_MAINFILE' ) or ! defined( 'NV_IS_MODADMIN' ) ) die( 'Stop!!!' );

global $global_config, $sys_info;

$allow_func = array( 'main' );

/**
 * nv_siteinfo_getlang()
 *
 * @return
 */
function nv_siteinfo_getlang()
{
	global $db_config;
	$sql = "SELECT DISTINCT `lang` FROM `" . $db_config['prefix'] . "_logs`";
	$result = nv_db_cache( $sql, 'lang' );
	$array_lang = array();

	if( ! empty( $result ) )
	{
		foreach( $result as $row )
		{
			$array_lang[] = $row['lang'];
		}
	}

	return $array_lang;
}

/**
 * nv_siteinfo_getuser()
 *
 * @return
 */
function nv_siteinfo_getuser()
{
	global $db_config;
	$sql = "SELECT `userid`, `username` FROM `" . NV_USERS_GLOBALTABLE . "` WHERE `userid` IN ( SELECT DISTINCT `userid` FROM `" . $db_config['prefix'] . "_logs` WHERE `userid`!=0 ) ORDER BY `username` ASC";
	$result = nv_db_cache( $sql, 'userid' );
	$array_user = array();

	if( ! empty( $result ) )
	{
		foreach( $result as $row )
		{
			$array_user[] = array( "userid" => ( int )$row['userid'], "username" => $row['username'] );
		}
	}

	return $array_user;
}

/**
 * nv_siteinfo_getmodules()
 *
 * @return
 */
function nv_siteinfo_getmodules()
{
	global $db_config;
	$sql = "SELECT DISTINCT `module_name` FROM `" . $db_config['prefix'] . "_logs`";
	$result = nv_db_cache( $sql, 'module_name' );
	$array_modules = array();

	if( ! empty( $result ) )
	{
		foreach( $result as $row )
		{
			$array_modules[] = $row['module_name'];
		}
	}

	return $array_modules;
}

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
	$allow_func[] = 'logs';
	$allow_func[] = 'logs_del';
	$submenu['logs'] = $lang_module['logs_title'];
}

if( $module_name == "siteinfo" )
{
	$menu_top = array(
		"title" => $module_name,
		"module_file" => "",
		"custom_title" => $lang_global['mod_siteinfo']
	);

	define( 'NV_IS_FILE_SITEINFO', true );
}

?>