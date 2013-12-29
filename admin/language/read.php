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

	$include_lang = '';
	$modules_exit = nv_scandir( NV_ROOTDIR . '/modules', $global_config['check_module'] );

	if( $module == 'global' and preg_match( '/^block\.global\.([a-zA-Z0-9\-\_]+)\.php$/', $admin_file, $m ) )
	{
		$include_lang = NV_ROOTDIR . '/language/' . $dirlang . '/' . $admin_file;
		$admin_file = 'block.global.' . $m[1];
	}
	elseif( preg_match( '/^block\.(global|module)\.([a-zA-Z0-9\-\_]+)\_' . $dirlang . '\.php$/', $admin_file, $m ) )
	{
		$include_lang = NV_ROOTDIR . '/modules/' . $module . '/language/' . $admin_file;
		$admin_file = 'block.' . $m[1] . '.' . $m[2];
	}
	elseif( $module == 'global' and $admin_file == 1 )
	{
		$include_lang = NV_ROOTDIR . '/language/' . $dirlang . '/admin_' . $module . '.php';
	}
	elseif( $module == 'global' and $admin_file == 0 )
	{
		$include_lang = NV_ROOTDIR . '/language/' . $dirlang . '/' . $module . '.php';
	}
	elseif( $module == 'install' and $admin_file == 0 )
	{
		$include_lang = NV_ROOTDIR . '/language/' . $dirlang . '/' . $module . '.php';
	}
	elseif( in_array( $module, $modules_exit ) and $admin_file == 1 )
	{
		$include_lang = NV_ROOTDIR . '/modules/' . $module . '/language/admin_' . $dirlang . '.php';
	}
	elseif( in_array( $module, $modules_exit ) and $admin_file == 0 )
	{
		$include_lang = NV_ROOTDIR . '/modules/' . $module . '/language/' . $dirlang . '.php';
	}
	elseif( file_exists( NV_ROOTDIR . '/language/' . $dirlang . '/admin_' . $module . '.php' ) )
	{
		$admin_file = 1;
		$include_lang = NV_ROOTDIR . '/language/' . $dirlang . '/admin_' . $module . '.php';
	}

	if( $include_lang != '' and file_exists( $include_lang ) )
	{
		$lang_module_temp = $lang_module;
		$lang_module = array();
		$lang_global = array();
		$lang_block = array();
		$lang_translator = array();

		include $include_lang;

		$sth = $db->prepare( 'SELECT idfile, langtype FROM ' . NV_LANGUAGE_GLOBALTABLE . '_file WHERE module = :module AND admin_file= :admin_file' );
		$sth->bindParam( ':module', $module, PDO::PARAM_STR );
		$sth->bindParam( ':admin_file', $admin_file, PDO::PARAM_STR );
		$sth->execute();
		list( $idfile, $langtype ) = $sth->fetch( 3 );

		if( intval( $idfile ) == 0 )
		{
			$langtype = isset( $lang_translator['langtype'] ) ? strip_tags( $lang_translator['langtype'] ) : 'lang_module';

			$lang_translator_save = array();
			$lang_translator_save['author'] = isset( $lang_translator['author'] ) ? strip_tags( $lang_translator['author'] ) : 'VINADES.,JSC (contact@vinades.vn)';
			$lang_translator_save['createdate'] = isset( $lang_translator['createdate'] ) ? strip_tags( $lang_translator['createdate'] ) : date( 'd/m/Y, H:i' );
			$lang_translator_save['copyright'] = isset( $lang_translator['copyright'] ) ? strip_tags( $lang_translator['copyright'] ) : 'Copyright (C) ' . date( 'Y' ) . ' VINADES.,JSC. All rights reserved';
			$lang_translator_save['info'] = isset( $lang_translator['info'] ) ? strip_tags( $lang_translator['info'] ) : '';
			$lang_translator_save['langtype'] = $langtype;

			$author = var_export( $lang_translator_save, true );
			try
			{
				$sth = $db->prepare( 'INSERT INTO ' . NV_LANGUAGE_GLOBALTABLE . '_file (module, admin_file, langtype, author_' . $dirlang . ') VALUES (:module, :admin_file, :langtype, :author)' );
				$sth->bindParam( ':module', $module, PDO::PARAM_STR );
				$sth->bindParam( ':admin_file', $admin_file, PDO::PARAM_STR );
				$sth->bindParam( ':langtype', $langtype, PDO::PARAM_STR );
				$sth->bindParam( ':author', $author, PDO::PARAM_STR );
				$sth->execute();
				$idfile = $db->lastInsertId();
			}
			catch (PDOException $e)
			{
				nv_info_die( $lang_global['error_404_title'], $lang_global['error_404_title'], $e->getMessage() );
			}
		}
		else
		{
			$lang_translator_save = array();

			$langtype = isset( $lang_translator['langtype'] ) ? strip_tags( $lang_translator['langtype'] ) : 'lang_module';

			$lang_translator_save['author'] = isset( $lang_translator['author'] ) ? strip_tags( $lang_translator['author'] ) : 'VINADES.,JSC (contact@vinades.vn)';
			$lang_translator_save['createdate'] = isset( $lang_translator['createdate'] ) ? strip_tags( $lang_translator['createdate'] ) : date( 'd/m/Y, H:i' );
			$lang_translator_save['copyright'] = isset( $lang_translator['copyright'] ) ? strip_tags( $lang_translator['copyright'] ) : 'Copyright (C) '.date( 'Y' ).' VINADES.,JSC. All rights reserved';
			$lang_translator_save['info'] = isset( $lang_translator['info'] ) ? strip_tags( $lang_translator['info'] ) : '';
			$lang_translator_save['langtype'] = $langtype;

			$author = var_export( $lang_translator_save, true );
			try
				{
				$sth = $db->prepare( 'UPDATE ' . NV_LANGUAGE_GLOBALTABLE . '_file SET lang_' . $dirlang . '= :author WHERE idfile= :idfile' );
				$sth->bindParam( ':idfile', $idfile, PDO::PARAM_INT );
				$sth->bindParam( ':author', $author, PDO::PARAM_STR );
				$sth->execute( );
			}
			catch (PDOException $e)
			{
				nv_info_die( $lang_global['error_404_title'], $lang_global['error_404_title'], $e->getMessage() );
			}
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

		$add_field = true;
		$array_lang_key = array();
		$array_lang_value = array();

		$result = $db->query( 'SHOW COLUMNS FROM ' . NV_LANGUAGE_GLOBALTABLE . '_file' );
		while( $row = $result->fetch() )
		{
			if( substr( $row['field'], 0, 7 ) == 'author_' and $row['field'] != 'author_' . $dirlang )
			{
				$array_lang_key[] = str_replace( 'author_', 'lang_', $row['field'] );
				$array_lang_value[] = '';
			}
		}

		$string_lang_key = implode( ', ', $array_lang_key );
		$string_lang_value = '';

		if( $string_lang_key != '' )
		{
			$string_lang_key = ', ' . $string_lang_key . '';
			$string_lang_value = implode( "', '", $array_lang_value );
			$string_lang_value = ", '" . $string_lang_value . "'";
		}

		$read_type = intval( $global_config['read_type'] );

		while( list( $lang_key, $lang_value ) = each( $temp_lang ) )
		{
			$check_type_update = false;
			$lang_key = trim( $lang_key );
			$lang_value = nv_nl2br( $lang_value );
			$lang_value = preg_replace( "/<br\s*\/>/", '<br />', $lang_value );
			$lang_value = preg_replace( "/<\/\s*br\s*>/", '<br />', $lang_value );

			if( $read_type == 0 or $read_type == 1 )
			{
				try
				{
					$sth = $db->prepare( 'INSERT INTO ' . NV_LANGUAGE_GLOBALTABLE . ' (idfile, lang_key, lang_' . $dirlang . ', update_' . $dirlang . ' ' . $string_lang_key . ') VALUES (:idfile, :lang_key, :lang_value, ' . NV_CURRENTTIME . ', :string_lang_value )' );
					$sth->bindParam( ':idfile', $idfile, PDO::PARAM_INT );
					$sth->bindParam( ':lang_key', $lang_key, PDO::PARAM_STR );
					$sth->bindParam( ':lang_value', $lang_value, PDO::PARAM_STR );
					$sth->bindParam( ':string_lang_value', $string_lang_value, PDO::PARAM_STR );
					$sth->execute();
					$id = $db->lastInsertId();
				}
				catch (PDOException $e)
				{
					if(  $read_type == 0 )
					{
						$check_type_update = true;
					}
				}
			}

			if( $read_type == 2 or $check_type_update )
			{
				$sth = $db->prepare( 'UPDATE ' . NV_LANGUAGE_GLOBALTABLE . ' SET lang_' . $dirlang . ' = :lang_value, update_' . $dirlang . ' = ' . NV_CURRENTTIME . ' WHERE idfile = :idfile AND lang_key = :lang_key');
				$sth->bindParam( ':idfile', $idfile, PDO::PARAM_INT );
				$sth->bindParam( ':lang_key', $lang_key, PDO::PARAM_STR );
				$sth->bindParam( ':lang_value', $lang_value, PDO::PARAM_STR );
				$sth->execute();
			}
		}

		$lang_module = $lang_module_temp;
		return '';
	}
	else
	{
		$include_lang = '';
		return $lang_module['nv_error_exit_module'] . ' : ' . $module;
	}
}

$dirlang = $nv_Request->get_title( 'dirlang', 'get', '' );
$page_title = $language_array[$dirlang]['name'] . ': ' . $lang_module['nv_admin_read'];

if( $nv_Request->get_string( 'checksess', 'get' ) == md5( 'readallfile' . session_id() ) )
{
	if( ! empty( $dirlang ) and is_dir( NV_ROOTDIR . '/language/' . $dirlang ) )
	{
		$array_filename = array();

		nv_admin_add_field_lang( $dirlang );
		nv_admin_read_lang( $dirlang, 'global', 0 );
		nv_admin_read_lang( $dirlang, 'install', 0 );

		$array_filename[] = str_replace( NV_ROOTDIR, '', str_replace( '\\', '/', $include_lang ) );
		nv_admin_read_lang( $dirlang, 'global', 1 );

		$array_filename[] = str_replace( NV_ROOTDIR, '', str_replace( '\\', '/', $include_lang ) );
		$dirs = nv_scandir( NV_ROOTDIR . '/' . NV_ADMINDIR, $global_config['check_module'] );

		foreach( $dirs as $module )
		{
			nv_admin_read_lang( $dirlang, $module, 1 );
			$array_filename[] = str_replace( NV_ROOTDIR, '', str_replace( '\\', '/', $include_lang ) );
		}

		$dirs = nv_scandir( NV_ROOTDIR . '/language/' . $dirlang, '/^block\.global\.([a-zA-Z0-9\-\_]+)\.php$/' );
		foreach( $dirs as $file_i )
		{
			nv_admin_read_lang( $dirlang, 'global', $file_i );
		}

		$dirs = nv_scandir( NV_ROOTDIR . '/modules', $global_config['check_module'] );
		foreach( $dirs as $module )
		{
			nv_admin_read_lang( $dirlang, $module, 0 );
			$array_filename[] = str_replace( NV_ROOTDIR, '', str_replace( '\\', '/', $include_lang ) );

			nv_admin_read_lang( $dirlang, $module, 1 );
			$array_filename[] = str_replace( NV_ROOTDIR, '', str_replace( '\\', '/', $include_lang ) );

			$blocks = nv_scandir( NV_ROOTDIR . '/modules/' . $module . '/language/', '/^block\.(global|module)\.([a-zA-Z0-9\-\_]+)\_' . $dirlang . '\.php$/' );
			foreach( $blocks as $file_i )
			{
				nv_admin_read_lang( $dirlang, $module, $file_i );
			}
		}

		$nv_Request->set_Cookie( 'dirlang', $dirlang, NV_LIVE_COOKIE_TIME );

		$xtpl = new XTemplate( 'read.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file );
		$xtpl->assign( 'LANG', $lang_module );
		$xtpl->assign( 'GLANG', $lang_global );
		$xtpl->assign( 'URL', NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=interface' );

		foreach( $array_filename as $name )
		{
			if( ! $name ) continue;

			$xtpl->assign( 'NAME', $name );
			$xtpl->parse( 'main.loop' );
		}

		$xtpl->parse( 'main' );
		$contents = $xtpl->text( 'main' );

		include NV_ROOTDIR . '/includes/header.php';
		echo nv_admin_theme( $contents );
		include NV_ROOTDIR . '/includes/footer.php';
	}
}

Header( 'Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name );

?>