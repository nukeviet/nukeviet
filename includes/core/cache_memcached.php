<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 1/9/2010, 3:21
 */

if( ! defined( 'NV_MAINFILE' ) ) die( 'Stop!!!' );

$memcached = new Memcached();
$memcached->addServer( NV_MEMCACHED_HOST, NV_MEMCACHED_PORT );

/**
 * nv_delete_all_cache()
 * @param mixed $sys
 *
 * @return
 */
function nv_delete_all_cache( $sys = true )
{
	global $memcached;
	$memcached->flush();
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
	global $memcached;
	$AllKeys = $memcached->getAllKeys();
	foreach( $AllKeys as $_key )
	{
		if( preg_match( '/^' . $module_name . '\_/', $_key ) )
		{
			$memcached->delete( $_key );
		}
	}
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
	global $memcached;
	return $memcached->get( $module_name . '_' . md5( $filename ) . '_' . NV_CACHE_PREFIX );
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
	global $memcached;
	$memcached->set( $module_name . '_' . md5( $filename ) . '_' . NV_CACHE_PREFIX, $content );
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
	global $memcached, $db, $module_name;

	$_rows = array();

	if( empty( $sql ) ) return $_rows;

	if( empty( $modname ) ) $modname = $module_name;

	$cache_key = $modname . '_' . $lang . '_' . md5( $sql ) . '_' . NV_CACHE_PREFIX;

	if( ! ( $_rows = $memcached->get( $cache_key ) ) )
	{
		if( ( $result = $db->query( $sql ) ) !== false )
		{
			$a = 0;
			while( $row = $result->fetch() )
			{
				$key2 = ( ! empty( $key ) and isset( $row[$key] ) ) ? $row[$key] : $a;
				$_rows[$key2] = $row;
				++$a;
			}
			$result->closeCursor();
			$memcached->set( $cache_key , $_rows);
		}
	}

	return $_rows;
}