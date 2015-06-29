<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 2-9-2010 14:43
 */

if( ! defined( 'NV_IS_FILE_LANG' ) ) die( 'Stop!!!' );

$dirlang = $nv_Request->get_title( 'dirlang', 'get', '' );
$page_title = $language_array[$dirlang]['name'] . ': ' . $lang_module['nv_admin_read'];

if( $nv_Request->get_string( 'checksess', 'get' ) == md5( 'downloadallfile' . session_id() ) )
{
	if( ! empty( $dirlang ) )
	{
		$allowfolder = array();
		$dirs = nv_scandir( NV_ROOTDIR . '/modules', $global_config['check_module'] );
		$err = 0;

		foreach( $dirs as $module )
		{
			// Zip lang block
			$files_lang_block = nv_scandir( NV_ROOTDIR . '/modules/' . $module . '/language', '/^block.(module|global)\.([a-zA-Z0-9\-\_]+)\_' . $dirlang . '.php$/' );

			foreach( $files_lang_block as $file_lang_block )
			{
				if( file_exists( NV_ROOTDIR . '/modules/' . $module . '/language/' . $file_lang_block ) )
				{
					$allowfolder[] = NV_ROOTDIR . '/modules/' . $module . '/language/' . $file_lang_block;
				}
			}

			// Lang module admin
			if( file_exists( NV_ROOTDIR . '/modules/' . $module . '/language/admin_' . $dirlang . '.php' ) )
			{
				$allowfolder[] = NV_ROOTDIR . '/modules/' . $module . '/language/admin_' . $dirlang . '.php';
			}

			// Lang module site
			if( file_exists( NV_ROOTDIR . '/modules/' . $module . '/language/' . $dirlang . '.php' ) )
			{
				$allowfolder[] = NV_ROOTDIR . '/modules/' . $module . '/language/' . $dirlang . '.php';
			}
		}

		if( is_dir( NV_ROOTDIR . '/language/' . $dirlang ) )
		{
			$allowfolder[] = NV_ROOTDIR . '/language/' . $dirlang;
		}

		//package js language
		if( file_exists( NV_ROOTDIR . '/js/language/' . $dirlang . '.js' ) )
		{
			$allowfolder[] = NV_ROOTDIR . '/js/language/' . $dirlang . '.js';
		}
		elseif( file_exists( NV_ROOTDIR . '/js/language/en.js' ) )
		{
			$allowfolder[] = NV_ROOTDIR . '/js/language/en.js';
		}

		$pattern_lang_js = '/[a-zA-Z0-9\-\_\.]+\-' . $dirlang . '\.js$/';
		$array_lang_js = nv_scandir( NV_ROOTDIR . '/js/language', $pattern_lang_js );

		if( ! empty( $array_lang_js ) )
		{
			foreach( $array_lang_js as $fjs )
			{
				$allowfolder[] = NV_ROOTDIR . '/js/language/' . $fjs;
			}
		}

		//package samples data
		if( file_exists( NV_ROOTDIR . '/install/data_' . $dirlang . '.php' ) )
		{
			$allowfolder[] = NV_ROOTDIR . '/install/data_' . $dirlang . '.php';
		}
		elseif( file_exists( NV_ROOTDIR . '/js/install/data_en.php' ) )
		{
			$allowfolder[] = NV_ROOTDIR . '/js/install/data_en.php';
		}

		if( file_exists( NV_ROOTDIR . '/includes/phpmailer/language/phpmailer.lang-' . $dirlang . '.php' ) )
		{
			$allowfolder[] = NV_ROOTDIR . '/includes/phpmailer/language/phpmailer.lang-' . $dirlang . '.php';
		}

		$file_src = NV_ROOTDIR . '/' . NV_TEMP_DIR . '/' . NV_TEMPNAM_PREFIX . $dirlang . '.zip';

		if( file_exists( $file_src ) )
		{
			unlink( $file_src );
		}

		//Zip file
		require_once NV_ROOTDIR . '/includes/class/pclzip.class.php';
		$zip = new PclZip( $file_src );
		$zip->create( $allowfolder, PCLZIP_OPT_REMOVE_PATH, NV_ROOTDIR );

		//Download file
		require_once NV_ROOTDIR . '/includes/class/download.class.php';
		$file_basename = 'Language_' . $dirlang . '.zip';
		$download = new download( $file_src, NV_ROOTDIR . '/' . NV_TEMP_DIR, $file_basename );
		$download->download_file();
		exit();
	}
}
else
{
	trigger_error( 'error checksess', 256 );
}