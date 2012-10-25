<?php

/**
 * @Project NUKEVIET 3.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2012 VINADES.,JSC. All rights reserved
 * @Createdate 2-2-2010 12:55
 */

if( ! defined( 'NV_IS_FILE_THEMES' ) ) die( 'Stop!!!' );

$contents = "";

$file_name = $nv_Request->get_string( 'file_name', 'get' );

if( ! empty( $file_name ) and preg_match( $global_config['check_block_module'], $file_name ) )
{
	$module = $nv_Request->get_string( 'module', 'get', '' );
	
	// Xac dinh ton tai cua block
	$path_file_php = $path_file_ini = $path_file_lang = '';
	unset( $matches );
	
	preg_match( $global_config['check_block_module'], $file_name, $matches );

	if( $module == 'global' and file_exists( NV_ROOTDIR . '/includes/blocks/' . $file_name ) and file_exists( NV_ROOTDIR . '/includes/blocks/' . $matches[1] . '.' . $matches[2] . '.ini' ) )
	{
		$path_file_php = NV_ROOTDIR . '/includes/blocks/' . $file_name;
		$path_file_ini = NV_ROOTDIR . '/includes/blocks/' . $matches[1] . '.' . $matches[2] . '.ini';

		if( file_exists( NV_ROOTDIR . "/language/" . NV_LANG_INTERFACE . "/block." . $file_name ) )
		{
			$path_file_lang = NV_ROOTDIR . "/language/" . NV_LANG_INTERFACE . "/block." . $file_name;
		}
		elseif( file_exists( NV_ROOTDIR . "/language/" . NV_LANG_DATA . "/block." . $file_name ) )
		{
			$path_file_lang = NV_ROOTDIR . "/language/" . NV_LANG_DATA . "/block." . $file_name;
		}
		elseif( file_exists( NV_ROOTDIR . "/language/en/block." . $file_name ) )
		{
			$path_file_lang = NV_ROOTDIR . "/language/en/block." . $file_name;
		}
	}
	elseif( isset( $site_mods[$module] ) )
	{
		$module_file = $site_mods[$module]['module_file'];
	
		if( file_exists( NV_ROOTDIR . '/modules/' . $module_file . '/blocks/' . $file_name ) and file_exists( NV_ROOTDIR . '/modules/' . $module_file . '/blocks/' . $matches[1] . '.' . $matches[2] . '.ini' ) )
		{
			$path_file_php = NV_ROOTDIR . '/modules/' . $module_file . '/blocks/' . $file_name;
			$path_file_ini = NV_ROOTDIR . '/modules/' . $module_file . '/blocks/' . $matches[1] . '.' . $matches[2] . '.ini';

			if( file_exists( NV_ROOTDIR . '/modules/' . $module_file . '/language/block.' . $matches[1] . '.' . $matches[2] . '_' . NV_LANG_INTERFACE . '.php' ) )
			{
				$path_file_lang = NV_ROOTDIR . '/modules/' . $module_file . '/language/block.' . $matches[1] . '.' . $matches[2] . '_' . NV_LANG_INTERFACE . '.php';
			}
			elseif( file_exists( NV_ROOTDIR . '/modules/' . $module_file . '/language/block.' . $matches[1] . '.' . $matches[2] . '_' . NV_LANG_DATA . '.php' ) )
			{
				$path_file_lang = NV_ROOTDIR . '/modules/' . $module_file . '/language/block.' . $matches[1] . '.' . $matches[2] . '_' . NV_LANG_DATA . '.php';
			}
			elseif( file_exists( NV_ROOTDIR . '/modules/' . $module_file . '/language/block.' . $matches[1] . '.' . $matches[2] . '_en.php' ) )
			{
				$path_file_lang = NV_ROOTDIR . '/modules/' . $module_file . '/language/block.' . $matches[1] . '.' . $matches[2] . '_en.php';
			}
		}
	}

	if( ! empty( $path_file_php ) and ! empty( $path_file_ini ) )
	{
		// Neu ton tai file config cua block
		$xml = simplexml_load_file( $path_file_ini );
	
		if( $xml !== false )
		{
			$function_name = trim( $xml->datafunction );
		
			if( ! empty( $function_name ) )
			{
				// neu ton tai function de xay dung cau truc cau hinh block
				include_once ( $path_file_php );
			
				if( nv_function_exists( $function_name ) )
				{
					//load cau hinh mac dinh cua block
					$xmlconfig = $xml->xpath( 'config' );
					$config = ( array )$xmlconfig[0];
					$array_config = array();
				
					foreach( $config as $key => $value )
					{
						$array_config[$key] = trim( $value );
					}

					$data_block = $array_config; // Cau hinh cua block
					$bid = $nv_Request->get_int( 'bid', 'get,post', 0 );
				
					if( $bid > 0 )
					{
						$row_config = $db->sql_fetchrow( $db->sql_query( "SELECT `module`, `file_name`, `config` FROM `" . NV_BLOCKS_TABLE . "_groups` WHERE `bid`=" . $bid ) );
					
						if( $row_config['file_name'] == $file_name and $row_config['module'] == $module )
						{
							$data_block = unserialize( $row_config['config'] );
						}
					}

					$lang_block = array(); // Ngon ngu cua block
				
					if( ! empty( $path_file_lang ) )
					{
						require $path_file_lang;
					}
					else
					{
						$xmllanguage = $xml->xpath( 'language' );
						$language = ( array )$xmllanguage[0];
					
						if( isset( $language[NV_LANG_INTERFACE] ) )
						{
							$lang_block = ( array )$language[NV_LANG_INTERFACE];
						}
						elseif( isset( $language['en'] ) )
						{
							$lang_block = ( array )$language['en'];
						}
						else
						{
							$key = array_keys( $array_config );
							$lang_block = array_combine( $key, $key );
						}
					}
				
					// Goi ham xu ly hien thi block
					$contents = call_user_func( $function_name, $module, $data_block, $lang_block );
				}
			}
		}
	}
}

include ( NV_ROOTDIR . "/includes/header.php" );
echo $contents;
include ( NV_ROOTDIR . "/includes/footer.php" );

?>