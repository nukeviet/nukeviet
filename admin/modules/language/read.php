<?php

/**
 * @Project NUKEVIET 3.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2012 VINADES.,JSC. All rights reserved
 * @Createdate 2-9-2010 14:43
 */

if( ! defined( 'NV_IS_FILE_LANG' ) ) die( 'Stop!!!' );

/**
 * nv_admin_read_lang()
 * 
 * @param mixed $dirlang
 * @param mixed $idfile
 * @return error read file
 */
function nv_admin_read_lang( $dirlang, $module, $admin_file = 1 )
{
	global $db, $global_config, $include_lang, $lang_module;
	
	$include_lang = "";
	$modules_exit = nv_scandir( NV_ROOTDIR . "/modules", $global_config['check_module'] );

	if( $module == "global" and preg_match( "/^block\.global\.([a-zA-Z0-9\-\_]+)\.php$/", $admin_file, $m ) )
	{
		$include_lang = NV_ROOTDIR . "/language/" . $dirlang . "/" . $admin_file;
		$admin_file = 'block.global.' . $m[1];
	}
	elseif( preg_match( "/^block\.(global|module)\.([a-zA-Z0-9\-\_]+)\_" . $dirlang . "\.php$/", $admin_file, $m ) )
	{
		$include_lang = NV_ROOTDIR . "/modules/" . $module . "/language/" . $admin_file;
		$admin_file = 'block.' . $m[1] . '.' . $m[2];
	}
	elseif( $module == "global" and $admin_file == 1 )
	{
		$include_lang = NV_ROOTDIR . "/language/" . $dirlang . "/admin_" . $module . ".php";
	}
	elseif( $module == "global" and $admin_file == 0 )
	{
		$include_lang = NV_ROOTDIR . "/language/" . $dirlang . "/" . $module . ".php";
	}
	elseif( $module == "install" and $admin_file == 0 )
	{
		$include_lang = NV_ROOTDIR . "/language/" . $dirlang . "/" . $module . ".php";
	}
	elseif( in_array( $module, $modules_exit ) and $admin_file == 1 )
	{
		$include_lang = NV_ROOTDIR . "/modules/" . $module . "/language/admin_" . $dirlang . ".php";
	}
	elseif( in_array( $module, $modules_exit ) and $admin_file == 0 )
	{
		$include_lang = NV_ROOTDIR . "/modules/" . $module . "/language/" . $dirlang . ".php";
	}
	elseif( file_exists( NV_ROOTDIR . "/language/" . $dirlang . "/admin_" . $module . ".php" ) )
	{
		$admin_file = 1;
		$include_lang = NV_ROOTDIR . "/language/" . $dirlang . "/admin_" . $module . ".php";
	}

	if( $include_lang != "" and file_exists( $include_lang ) )
	{
		$lang_module_temp = $lang_module;
		$lang_module = array();
		$lang_global = array();
		$lang_block = array();
		$lang_translator = array();
		
		include ( $include_lang );
		list( $idfile, $langtype ) = $db->sql_fetchrow( $db->sql_query( "SELECT idfile, langtype FROM `" . NV_LANGUAGE_GLOBALTABLE . "_file` WHERE `module` =" . $db->dbescape( $module ) . " AND `admin_file`=" . $db->dbescape( $admin_file ) ) );
		
		if( intval( $idfile ) == 0 )
		{
			$langtype = isset( $lang_translator['langtype'] ) ? strip_tags( $lang_translator['langtype'] ) : "lang_module";
		
			$lang_translator_save = array();
			$lang_translator_save['author'] = isset( $lang_translator['author'] ) ? strip_tags( $lang_translator['author'] ) : "VINADES.,JSC (contact@vinades.vn)";
			$lang_translator_save['createdate'] = isset( $lang_translator['createdate'] ) ? strip_tags( $lang_translator['createdate'] ) : date( "d/m/Y, H:i" );
			$lang_translator_save['copyright'] = isset( $lang_translator['copyright'] ) ? strip_tags( $lang_translator['copyright'] ) : "Copyright (C) 2010 VINADES.,JSC. All rights reserved";
			$lang_translator_save['info'] = isset( $lang_translator['info'] ) ? strip_tags( $lang_translator['info'] ) : "";
			$lang_translator_save['langtype'] = $langtype;
		
			//$author = base64_encode( serialize( $lang_translator_save ) );
			$author = var_export( $lang_translator_save, true );
		
			$idfile = $db->sql_query_insert_id( "INSERT INTO `" . NV_LANGUAGE_GLOBALTABLE . "_file` (`idfile`, `module`, `admin_file`, `langtype`, `author_" . $dirlang . "`) VALUES (NULL, " . $db->dbescape( $module ) . ", " . $db->dbescape( $admin_file ) . ", " . $db->dbescape( $langtype ) . ", '" . mysql_real_escape_string( $author ) . "')" );
		
			if( ! $idfile )
			{
				nv_info_die( $lang_global['error_404_title'], $lang_global['error_404_title'], "Error insert file: " . $filelang );
			}
		}
		else
		{
			$lang_translator_save = array();
		
			$langtype = isset( $lang_translator['langtype'] ) ? strip_tags( $lang_translator['langtype'] ) : "lang_module";
		
			$lang_translator_save['author'] = isset( $lang_translator['author'] ) ? strip_tags( $lang_translator['author'] ) : "VINADES.,JSC (contact@vinades.vn)";
			$lang_translator_save['createdate'] = isset( $lang_translator['createdate'] ) ? strip_tags( $lang_translator['createdate'] ) : date( "d/m/Y, H:i" );
			$lang_translator_save['copyright'] = isset( $lang_translator['copyright'] ) ? strip_tags( $lang_translator['copyright'] ) : "Copyright (C) 2010 VINADES.,JSC. All rights reserved";
			$lang_translator_save['info'] = isset( $lang_translator['info'] ) ? strip_tags( $lang_translator['info'] ) : "";
			$lang_translator_save['langtype'] = $langtype;
		
			//$author = base64_encode( serialize( $lang_translator_save ) );
			$author = var_export( $lang_translator_save, true );
		
			$sql = "UPDATE `" . NV_LANGUAGE_GLOBALTABLE . "_file` SET `author_" . $dirlang . "` = '" . mysql_real_escape_string( $author ) . "' WHERE `idfile` = '" . $idfile . "'";
			$db->sql_query( $sql );
		}
		
		$temp_lang = array();
		switch( $langtype )
		{
			case 'lang_global':
				$temp_lang = $lang_global;
				break;
			case 'lang_module':
				$temp_lang = $lang_module;
				break;
			case 'lang_block':
				$temp_lang = $lang_block;
				break;
		}

		$result = $db->sql_query( "SHOW COLUMNS FROM `" . NV_LANGUAGE_GLOBALTABLE . "_file`" );
		$add_field = true;
		$array_lang_key = array();
		$array_lang_value = array();
	
		while( $row = $db->sql_fetch_assoc( $result ) )
		{
			if( substr( $row['Field'], 0, 7 ) == "author_" and $row['Field'] != "author_" . $dirlang )
			{
				$array_lang_key[] = str_replace( "author_", "lang_", $row['Field'] );
				$array_lang_value[] = "";
			}
		}
		
		$string_lang_key = implode( "`, `", $array_lang_key );
		$string_lang_value = "";
	
		if( $string_lang_key != "" )
		{
			$string_lang_key = ", `" . $string_lang_key . "`";
			$string_lang_value = implode( "', '", $array_lang_value );
			$string_lang_value = ", '" . $string_lang_value . "'";
		}
	
		$read_type = intval( $global_config['read_type'] );
		
		while( list( $lang_key, $lang_value ) = each( $temp_lang ) )
		{
			$check_type_update = false;
			$lang_key = trim( $lang_key );
			$lang_value = nv_nl2br( $lang_value );
			$lang_value = str_replace( '<br  />', '<br />', $lang_value );
			$lang_value = str_replace( '<br />', '<br />', $lang_value );
		
			if( $read_type == 0 or $read_type == 1 )
			{
				$sql = "INSERT INTO `" . NV_LANGUAGE_GLOBALTABLE . "` (`id`, `idfile`, `lang_key`, `lang_" . $dirlang . "`, `update_" . $dirlang . "` " . $string_lang_key . ") VALUES (NULL, '" . $idfile . "', '" . mysql_real_escape_string( $lang_key ) . "', '" . mysql_real_escape_string( $lang_value ) . "',  UNIX_TIMESTAMP( ) " . $string_lang_value . ")";
			
				if( ! $db->sql_query_insert_id( $sql ) and $read_type == 0 )
				{
					$check_type_update = true;
				}
			}
		
			if( $read_type == 2 or $check_type_update )
			{
				$sql = "UPDATE `" . NV_LANGUAGE_GLOBALTABLE . "` SET `lang_" . $dirlang . "` = '" . mysql_real_escape_string( $lang_value ) . "',  `update_" . $dirlang . "` =  UNIX_TIMESTAMP( ) WHERE `idfile` = '" . $idfile . "' AND `lang_key` = '" . mysql_real_escape_string( $lang_key ) . "' LIMIT 1";
				$db->sql_query( $sql );
			}
		}
	
		$lang_module = $lang_module_temp;
		return "";
	}
	else
	{
		$include_lang = "";
		return $lang_module['nv_error_exit_module'] . " : " . $module;
	}
}

$dirlang = filter_text_input( 'dirlang', 'get', '' );
$page_title = $language_array[$dirlang]['name'] . ": " . $lang_module['nv_admin_read'];

if( $nv_Request->get_string( 'checksess', 'get' ) == md5( "readallfile" . session_id() ) )
{
	if( ! empty( $dirlang ) and is_dir( NV_ROOTDIR . "/language/" . $dirlang ) )
	{
		$array_filename = array();
		
		nv_admin_add_field_lang( $dirlang );
		nv_admin_read_lang( $dirlang, "global", 0 );
		nv_admin_read_lang( $dirlang, "install", 0 );
		
		$array_filename[] = str_replace( NV_ROOTDIR, "", str_replace( '\\', '/', $include_lang ) );
		nv_admin_read_lang( $dirlang, "global", 1 );
		
		$array_filename[] = str_replace( NV_ROOTDIR, "", str_replace( '\\', '/', $include_lang ) );
		$dirs = nv_scandir( NV_ROOTDIR . "/" . NV_ADMINDIR . "/modules", $global_config['check_module'] );
		
		foreach( $dirs as $module )
		{
			nv_admin_read_lang( $dirlang, $module, 1 );
			$array_filename[] = str_replace( NV_ROOTDIR, "", str_replace( '\\', '/', $include_lang ) );
		}

		$dirs = nv_scandir( NV_ROOTDIR . "/language/" . $dirlang, "/^block\.global\.([a-zA-Z0-9\-\_]+)\.php$/" );
		foreach( $dirs as $file_i )
		{
			nv_admin_read_lang( $dirlang, 'global', $file_i );
		}

		$dirs = nv_scandir( NV_ROOTDIR . "/modules", $global_config['check_module'] );
		foreach( $dirs as $module )
		{
			nv_admin_read_lang( $dirlang, $module, 0 );
			$array_filename[] = str_replace( NV_ROOTDIR, "", str_replace( '\\', '/', $include_lang ) );
			
			nv_admin_read_lang( $dirlang, $module, 1 );
			$array_filename[] = str_replace( NV_ROOTDIR, "", str_replace( '\\', '/', $include_lang ) );

			$blocks = nv_scandir( NV_ROOTDIR . "/modules/" . $module . "/language/", "/^block\.(global|module)\.([a-zA-Z0-9\-\_]+)\_" . $dirlang . "\.php$/" );
			foreach( $blocks as $file_i )
			{
				nv_admin_read_lang( $dirlang, $module, $file_i );
			}
		}
		
		$nv_Request->set_Cookie( 'dirlang', $dirlang, NV_LIVE_COOKIE_TIME );
		
		$xtpl = new XTemplate( "read.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file );
		$xtpl->assign( 'LANG', $lang_module );
		$xtpl->assign( 'GLANG', $lang_global );
		$xtpl->assign( 'URL', NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=interface" );
		
		foreach( $array_filename as $name )
		{
			if( ! $name ) continue;
			
			$xtpl->assign( 'NAME', $name );
			$xtpl->assign( 'CLASS', ++ $i % 2 ? ' class="second"' : '' );
			$xtpl->parse( 'main.loop' );
		}
		
		$xtpl->parse( 'main' );
		$contents = $xtpl->text( 'main' );
		
		include ( NV_ROOTDIR . "/includes/header.php" );
		echo nv_admin_theme( $contents );
		include ( NV_ROOTDIR . "/includes/footer.php" );
	}
}

Header( "Location: " . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name );

?>