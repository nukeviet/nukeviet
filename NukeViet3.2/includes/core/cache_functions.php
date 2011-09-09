<?php

/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES.,JSC. All rights reserved
 * @Createdate 1/9/2010, 3:21
 */

if ( ! defined( 'NV_MAINFILE' ) ) die( 'Stop!!!' );

/**
 * nv_delete_cache()
 * 
 * @param mixed $pattern
 * @return
 */
function nv_delete_cache( $pattern )
{
	global $lang_global, $sys_info, $global_config;    	
    $files = nv_scandir( NV_ROOTDIR . "/" . NV_CACHEDIR, $pattern );

    if ( ! empty( $files ) )
    {
		$ftp_check_login = 0;
		if ($sys_info['ftp_support'] and intval($global_config['ftp_check_login']) == 1)
		{
			$ftp_server = nv_unhtmlspecialchars($global_config['ftp_server']);
			$ftp_port = intval($global_config['ftp_port']);
			$ftp_user_name = nv_unhtmlspecialchars($global_config['ftp_user_name']);
			$ftp_user_pass = nv_unhtmlspecialchars($global_config['ftp_user_pass']);
			$ftp_path = nv_unhtmlspecialchars($global_config['ftp_path']);
			// set up basic connection
			$conn_id = ftp_connect($ftp_server, $ftp_port, 10);
			// login with username and password
			$login_result = ftp_login($conn_id, $ftp_user_name, $ftp_user_pass);
			if ((!$conn_id) || (!$login_result))
			{
				$ftp_check_login = 3;
			}
			elseif (ftp_chdir($conn_id, $ftp_path))
			{
				$ftp_check_login = 1;
			}
			else
			{
				$ftp_check_login = 2;
			}
		}    	
		if ($ftp_check_login == 1)
		{
			foreach ($files as $f)
			{
				ftp_delete($conn_id, NV_CACHEDIR . "/" . $f);
			}
			ftp_close($conn_id);
		}
		else
		{
			foreach ($files as $f)
			{
				unlink(NV_ROOTDIR . "/" . NV_CACHEDIR . "/" . $f);
			}
		}
    }
}

/**
 * nv_delete_all_cache()
 * 
 * @return
 */
function nv_delete_all_cache()
{
    $pattern = "/(.*)\.cache/";
    nv_delete_cache( $pattern );
}

/**
 * nv_del_cache_module()
 * 
 * @return void
 */
function nv_del_moduleCache( $module_name )
{
    $pattern = "/^" . NV_LANG_DATA . "\_" . $module_name . "\_(.*)\.cache$/i";
    nv_delete_cache( $pattern );
}

/**
 * nv_get_cache()
 * 
 * @param mixed $filename
 * @return
 */
function nv_get_cache( $filename )
{
    if ( empty( $filename ) or ! preg_match( "/(.*)\.cache/", $filename ) ) return false;
    $filename = basename( $filename );
    if ( ! file_exists( NV_ROOTDIR . "/" . NV_CACHEDIR . "/" . $filename ) ) return false;

    return nv_gz_get_contents( NV_ROOTDIR . "/" . NV_CACHEDIR . "/" . $filename );
}

/**
 * nv_set_cache()
 * 
 * @param mixed $filename
 * @param mixed $content
 * @return
 */
function nv_set_cache( $filename, $content )
{
    if ( empty( $filename ) or ! preg_match( "/(.*)\.cache/", $filename ) ) return false;
    $filename = basename( $filename );

    return nv_gz_put_contents( NV_ROOTDIR . "/" . NV_CACHEDIR . "/" . $filename, $content );
}

/**
 * nv_db_cache()
 * 
 * @param mixed $sql
 * @return
 */
function nv_db_cache( $sql, $key = '', $modname = '' )
{
    global $db, $module_name;

    $list = array();

    if ( empty( $sql ) ) return $list;

    if ( empty( $modname ) ) $modname = $module_name;

    $cache_file = NV_LANG_DATA . "_" . $modname . "_" . md5( $sql ) . "_" . NV_CACHE_PREFIX . ".cache";
    if ( ( $cache = nv_get_cache( $cache_file ) ) != false )
    {
        $list = unserialize( $cache );
    }
    else
    {
        if ( ( $result = $db->sql_query( $sql ) ) !== false )
        {
            $a = 0;
            while ( $row = $db->sql_fetch_assoc( $result ) )
            {
                $key2 = ( ! empty( $key ) and isset( $row[$key] ) ) ? $row[$key] : $a;
                $list[$key2] = $row;
                $a++;
            }

            $db->sql_freeresult( $result );

            $cache = serialize( $list );
            nv_set_cache( $cache_file, $cache );
        }
    }

    return $list;
}

?>