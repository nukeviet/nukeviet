<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 1/9/2010, 3:21
 */

if( ! defined( 'NV_MAINFILE' ) ) die( 'Stop!!!' );

/**
 * nv_delete_cache()
 *
 * @param mixed $pattern
 * @return
 */
function nv_delete_cache( $modname, $pattern )
{
	if( $dh = opendir( NV_ROOTDIR . '/' . NV_CACHEDIR . '/' . $modname ) )
	{
		while( ( $file = readdir( $dh ) ) !== false )
		{
			if( preg_match( $pattern, $file ) )
			{
				unlink( NV_ROOTDIR . '/' . NV_CACHEDIR . '/' . $modname . '/' . $file );
			}
		}
		closedir( $dh );
	}
}

/**
 * nv_delete_all_cache()
 * @param mixed $sys
 *
 * @return
 */
function nv_delete_all_cache( $sys = true)
{
	if( $dh = opendir( NV_ROOTDIR . '/' . NV_CACHEDIR ) )
	{
		if( $sys )
		{
			$pattern = '/(.*)\.cache$/';
		}
		else
		{
			$pattern = '/^' . NV_LANG_DATA . '\_(.*)\.cache$/';
		}
		while( ( $modname = readdir( $dh ) ) !== false )
		{
			if( preg_match( '/^([a-z0-9\_]+)$/', $modname ) )
			{
				nv_delete_cache( $modname, $pattern );
			}
		}
		closedir( $dh );
	}
}

/**
 * nv_del_cache_module()
 *
 * @param mixed $module_name
 * @param mixed $lang
 *
 * @return void
 */
function nv_del_moduleCache( $module_name, $lang = NV_LANG_DATA )
{
	if( empty( $lang ) )
	{
		$pattern = '/^' . $lang . '\_(.*)\.cache$/';
	}
	else
	{
		$pattern = '/(.*)\.cache$/';
	}
	nv_delete_cache( $module_name, $pattern );
}

/**
 * nv_get_cache()
 *
 * @param mixed $module_name
 * @param mixed $filename
 * @return
 */
function nv_get_cache( $module_name, $filename )
{
	if( empty( $filename ) or ! preg_match( '/([a-z0-9\_]+)\.cache/', $filename ) ) return false;

	if( ! file_exists( NV_ROOTDIR . '/' . NV_CACHEDIR . '/' . $module_name . '/' . $filename ) ) return false;

	return nv_gz_get_contents( NV_ROOTDIR . '/' . NV_CACHEDIR . '/' . $module_name . '/' . $filename );
}

/**
 * nv_set_cache()
 *
 * @param mixed $module_name
 * @param mixed $filename
 * @param mixed $content
 * @return
 */
function nv_set_cache( $module_name, $filename, $content )
{
	if( empty( $filename ) or ! preg_match( '/([a-z0-9\_]+)\.cache/', $filename ) ) return false;

	nv_mkdir( NV_ROOTDIR . '/' . NV_CACHEDIR, $module_name );

	return nv_gz_put_contents( NV_ROOTDIR . '/' . NV_CACHEDIR . '/' . $module_name . '/' . $filename, $content );
}

/**
 * nv_db_cache()
 *
 * @param mixed $sql
 * @param mixed $key
 * @param mixed $modname
 * @param mixed $lang
 * @return
 */
function nv_db_cache( $sql, $key = '', $modname = '', $lang = NV_LANG_DATA )
{
	global $db, $module_name, $global_config;

	$list = array();

	if( empty( $sql ) ) return $list;

	if( empty( $modname ) ) $modname = $module_name;

	$cache_file = $lang . '_' . md5( $sql ) . '_' . NV_CACHE_PREFIX . '.cache';

	if( ( $cache = nv_get_cache( $modname, $cache_file ) ) != false )
	{
		$list = unserialize( $cache );
	}
	else
	{
		if( ( $result = $db->query( $sql ) ) !== false )
		{
			$a = 0;
			while( $row = $result->fetch() )
			{
				$key2 = ( ! empty( $key ) and isset( $row[$key] ) ) ? $row[$key] : $a;
				$list[$key2] = $row;
				++$a;
			}
			$result->closeCursor();

			$cache = serialize( $list );
			nv_set_cache( $modname, $cache_file, $cache );
		}
	}

	return $list;
}