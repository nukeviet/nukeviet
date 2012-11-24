<?php

/**
 * @Project NUKEVIET 3.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2012 VINADES.,JSC. All rights reserved
 * @Createdate 9/9/2010, 6:51
 */

if( ! defined( 'NV_IS_FILE_WEBTOOLS' ) ) die( 'Stop!!!' );

$page_title = $lang_module['clearsystem'];

/**
 * nv_clear_files()
 * 
 * @param mixed $dir
 * @param mixed $base
 * @return
 */
function nv_clear_files( $dir, $base )
{
	global $client_info;

	$dels = array();
	if( $dh = opendir( $dir ) )
	{
		while( ( $file = readdir( $dh ) ) !== false )
		{
			if( ! preg_match( "/^[\.]{1,2}([a-zA-Z0-9]*)$/", $file ) and $file != "index.html" and is_file( $dir . '/' . $file ) and $file != "sess_" . $client_info['session_id'] )
			{
				if( unlink( $dir . '/' . $file ) )
				{
					$dels[] = $base . '/' . $file;
				}
			}
		}
		closedir( $dh );
	}
	if( ! file_exists( $dir . "/index.html" ) )
	{
		file_put_contents( $dir . "/index.html", "" );
	}
	return $dels;
}

$xtpl = new XTemplate( "clearsystem.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file );
$xtpl->assign( 'LANG', $lang_module );

if( $nv_Request->isset_request( 'submit', 'post' ) and $nv_Request->isset_request( 'deltype', 'post' ) )
{
	$deltype = $nv_Request->get_typed_array( 'deltype', 'post', 'string', array() );

	nv_insert_logs( NV_LANG_DATA, $module_name, $lang_module['clearsystem'], implode( ", ", $deltype ), $admin_info['userid'] );
	clearstatcache();
	if( in_array( 'clearcache', $deltype ) )
	{
		$cacheDir = NV_ROOTDIR . '/' . NV_CACHEDIR;
		$files = nv_clear_files( $cacheDir, NV_CACHEDIR );
		foreach( $files as $file )
		{
			$xtpl->assign( 'DELFILE', $file );
			$xtpl->parse( 'main.delfile.loop' );
		}
		$cssDir = NV_ROOTDIR . '/' . NV_FILES_DIR . '/css';
		$files = nv_clear_files( $cssDir, NV_FILES_DIR . '/css' );
		foreach( $files as $file )
		{
			$xtpl->assign( 'DELFILE', $file );
			$xtpl->parse( 'main.delfile.loop' );
		}
		$timestamp = intval( $global_config['timestamp'] ) + 1;
		$db->sql_query( "REPLACE INTO `" . NV_CONFIG_GLOBALTABLE . "` (`lang`, `module`, `config_name`, `config_value`) VALUES ('sys', 'global', 'timestamp', " . $timestamp . ")" );
		nv_save_file_config_global();
	}

	if( in_array( 'clearsession', $deltype ) )
	{
		$ssDir = NV_ROOTDIR . "/" . NV_SESSION_SAVE_PATH;
		$files = nv_clear_files( $ssDir, NV_SESSION_SAVE_PATH );
		foreach( $files as $file )
		{
			$xtpl->assign( 'DELFILE', $file );
			$xtpl->parse( 'main.delfile.loop' );
		}
	}

	if( in_array( 'clearfiletemp', $deltype ) )
	{
		$dir = NV_ROOTDIR . "/" . NV_TEMP_DIR;
		if( $dh = opendir( $dir ) )
		{
			while( ( $file = readdir( $dh ) ) !== false )
			{
				if( preg_match( "/^(" . nv_preg_quote( NV_TEMPNAM_PREFIX ) . ")[a-zA-Z0-9\_\.]+$/", $file ) )
				{
					if( is_file( $dir . '/' . $file ) )
					{
						if( @unlink( $dir . '/' . $file ) )
						{
							$xtpl->assign( 'DELFILE', NV_TEMP_DIR . '/' . $file );
							$xtpl->parse( 'main.delfile.loop' );
						}
					}
					else
					{
						$rt = nv_deletefile( $dir . '/' . $file, true );
						if( $rt[0] == 1 )
						{
							$xtpl->assign( 'DELFILE', NV_TEMP_DIR . '/' . $file );
							$xtpl->parse( 'main.delfile.loop' );
						}
					}
				}
			}
			closedir( $dh );
		}
	}

	if( in_array( 'clearerrorlogs', $deltype ) )
	{
		$dir = NV_ROOTDIR . '/' . NV_LOGS_DIR . '/error_logs';
		$files = nv_clear_files( $dir, NV_LOGS_DIR . '/error_logs' );
		foreach( $files as $file )
		{
			$xtpl->assign( 'DELFILE', $file );
			$xtpl->parse( 'main.delfile.loop' );
		}

		$dir = NV_ROOTDIR . '/' . NV_LOGS_DIR . '/error_logs/errors256';
		$files = nv_clear_files( $dir, NV_LOGS_DIR . '/error_logs/errors256' );
		foreach( $files as $file )
		{
			$xtpl->assign( 'DELFILE', $file );
			$xtpl->parse( 'main.delfile.loop' );
		}

		$dir = NV_ROOTDIR . '/' . NV_LOGS_DIR . '/error_logs/old';
		$files = nv_clear_files( $dir, NV_LOGS_DIR . '/error_logs/old' );
		foreach( $files as $file )
		{
			$xtpl->assign( 'DELFILE', $file );
			$xtpl->parse( 'main.delfile.loop' );
		}

		$dir = NV_ROOTDIR . '/' . NV_LOGS_DIR . '/error_logs/tmp';
		$files = nv_clear_files( $dir, NV_LOGS_DIR . '/error_logs/tmp' );
		foreach( $files as $file )
		{
			$xtpl->assign( 'DELFILE', $file );
			$xtpl->parse( 'main.delfile.loop' );
		}
	}

	if( in_array( 'clearip_logs', $deltype ) )
	{
		$dir = NV_ROOTDIR . '/' . NV_LOGS_DIR . '/ip_logs';
		$files = nv_clear_files( $dir, NV_LOGS_DIR . '/ip_logs' );
		foreach( $files as $file )
		{
			$xtpl->assign( 'DELFILE', $file );
			$xtpl->parse( 'main.delfile.loop' );
		}
	}
	$xtpl->parse( 'main.delfile' );
}

$xtpl->parse( 'main' );
$contents = $xtpl->text( 'main' );

include ( NV_ROOTDIR . "/includes/header.php" );
echo nv_admin_theme( $contents );
include ( NV_ROOTDIR . "/includes/footer.php" );

?>