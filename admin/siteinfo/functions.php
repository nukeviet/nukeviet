<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 12/31/2009 5:50
 */

if( ! defined( 'NV_ADMIN' ) or ! defined( 'NV_MAINFILE' ) or ! defined( 'NV_IS_MODADMIN' ) ) die( 'Stop!!!' );

if( $admin_info['level'] == 1 )
{
	$allow_func[] = 'logs_del';
}

$menu_top = array(
	'title' => $module_name,
	'module_file' => '',
	'custom_title' => $lang_global['mod_siteinfo']
);

define( 'NV_IS_FILE_SITEINFO', true );

/**
 * nv_siteinfo_getlang()
 *
 * @return
 */
function nv_siteinfo_getlang()
{
	global $db_config;
	$sql = 'SELECT DISTINCT lang FROM ' . $db_config['prefix'] . '_logs';
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
	$sql = 'SELECT userid, username FROM ' . NV_USERS_GLOBALTABLE . ' WHERE userid IN ( SELECT DISTINCT userid FROM ' . $db_config['prefix'] . '_logs WHERE userid!=0 ) ORDER BY username ASC';
	$result = nv_db_cache( $sql, 'userid' );
	$array_user = array();

	if( ! empty( $result ) )
	{
		foreach( $result as $row )
		{
			$array_user[] = array( 'userid' => ( int )$row['userid'], 'username' => $row['username'] );
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
	$sql = 'SELECT DISTINCT module_name FROM ' . $db_config['prefix'] . '_logs';
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