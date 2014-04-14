<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 2-9-2010 14:43
 */

if( ! defined( 'NV_IS_FILE_LANG' ) ) die( 'Stop!!!' );

$array_lang_no_check = array();

$array_lang_exit = array();

$columns_array = $db->columns_array( NV_LANGUAGE_GLOBALTABLE . '_file' );
foreach ( $columns_array as $row )
{
	if( substr( $row['field'], 0, 7 ) == 'author_' )
	{
		$array_lang_exit[] .= trim( substr( $row['field'], 7, 2 ) );
	}
}

/**
 * nv_admin_write_lang()
 *
 * @param mixed $dirlang
 * @param mixed $idfile
 * @return error write file
 */
function nv_admin_write_lang( $dirlang, $idfile )
{
	global $db, $language_array, $global_config, $include_lang, $lang_module, $array_lang_exit, $array_lang_no_check;

	list( $module, $admin_file, $langtype, $author_lang ) = $db->query( 'SELECT module, admin_file, langtype, author_' . $dirlang . ' FROM ' . NV_LANGUAGE_GLOBALTABLE . '_file WHERE idfile =' . intval( $idfile ) )->fetch( 3 );

	if( ! empty( $dirlang ) and ! empty( $module ) )
	{
		if( empty( $author_lang ) )
		{
			$array_translator = array();
			$array_translator['author'] = '';
			$array_translator['createdate'] = '';
			$array_translator['copyright'] = '';
			$array_translator['info'] = '';
			$array_translator['langtype'] = $langtype;
		}
		else
		{
			eval( '$array_translator = ' . $author_lang . ';' );
		}

		$include_lang = '';

		$modules_exit = nv_scandir( NV_ROOTDIR . '/modules', $global_config['check_module'] );

		if( $module == 'global' and preg_match( '/^block\.global\.([a-zA-Z0-9\-\_]+)$/', $admin_file ) )
		{
			$include_lang = NV_ROOTDIR . '/language/' . $dirlang . '/' . $admin_file . '.php';
		}
		elseif( in_array( $module, $modules_exit ) and preg_match( '/^block\.(global|module)\.([a-zA-Z0-9\-\_]+)$/', $admin_file ) )
		{
			$include_lang = NV_ROOTDIR . '/modules/' . $module . '/language/' . $admin_file . '_' . $dirlang . '.php';
		}
		elseif( in_array( $module, $modules_exit ) and $admin_file == 1 )
		{
			$include_lang = NV_ROOTDIR . '/modules/' . $module . '/language/admin_' . $dirlang . '.php';
		}
		elseif( in_array( $module, $modules_exit ) and $admin_file == 0 )
		{
			$include_lang = NV_ROOTDIR . '/modules/' . $module . '/language/' . $dirlang . '.php';
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
		else
		{
			$admin_file = 1;
			$include_lang = NV_ROOTDIR . '/language/' . $dirlang . '/admin_' . $module . '.php';
		}

		if( $include_lang == '' )
		{
			return $lang_module['nv_error_write_module'] . ' : ' . $module;
		}
		else
		{
			if( preg_match( '/^(0?\d|[1-2]{1}\d|3[0-1]{1})[\-\/\.]{1}(0?\d|1[0-2]{1})[\-\/\.]{1}(19[\d]{2}|20[\d]{2})[\-\/\.\,\\s]{2}(0?\d|[1]{1}\d|2[0-4]{1})[\-\/\.\:]{1}([0-5]?[0-9])$/', $array_translator['createdate'], $m ) )
			{
				$createdate = mktime( $m[4], $m[5], 0, $m[2], $m[1], $m[3] );
			}
			elseif( preg_match( '/^(0?\d|[1-2]{1}\d|3[0-1]{1})[\-\/\.]{1}(0?\d|1[0-2]{1})[\-\/\.]{1}(19[\d]{2}|20[\d]{2})$/', $array_translator['createdate'], $m ) )
			{
				$createdate = mktime( 0, 0, 0, $m[2], $m[1], $m[3] );
			}
			else
			{
				$createdate = time();
			}

			$content_lang_no_tran = '';
			$content_lang = "<?php\n\n";
			$content_lang .= "/**\n";
			$content_lang .= "* @Project NUKEVIET 4.x\n";
			$content_lang .= "* @Author VINADES.,JSC (contact@vinades.vn)\n";
			$content_lang .= "* @Copyright (C) " . date( "Y" ) . " VINADES.,JSC. All rights reserved\n";
			$content_lang .= "* @Language " . $language_array[$dirlang]['name'] . "\n";
			$content_lang .= "* @License CC BY-SA (http://creativecommons.org/licenses/by-sa/4.0/)\n";
			$content_lang .= "* @Createdate " . gmdate( "M d, Y, h:i:s A", $createdate ) . "\n";
			$content_lang .= "*/\n";

			if( $admin_file )
			{
				$content_lang .= "\nif( ! defined( 'NV_ADMIN' ) or ! defined( 'NV_MAINFILE' ) )";
			}
			else
			{
				$content_lang .= "\nif( ! defined( 'NV_MAINFILE' ) )";
			}

			$content_lang .= " die( 'Stop!!!' );\n\n";

			$array_translator['info'] = ( isset( $array_translator['info'] ) ) ? $array_translator['info'] : "";

			$content_lang .= "\$lang_translator['author'] = '" . $array_translator['author'] . "';\n";
			$content_lang .= "\$lang_translator['createdate'] = '" . $array_translator['createdate'] . "';\n";
			$content_lang .= "\$lang_translator['copyright'] = '" . $array_translator['copyright'] . "';\n";
			$content_lang .= "\$lang_translator['info'] = '" . $array_translator['info'] . "';\n";
			$content_lang .= "\$lang_translator['langtype'] = '" . $array_translator['langtype'] . "';\n";
			$content_lang .= "\n";
			$content_lang_no_check = '';
			$numrows = 0;

			if( in_array( 'vi', $array_lang_exit ) and in_array( 'en', $array_lang_exit ) and $dirlang != 'vi' and $dirlang != 'en' )
			{
				$result = $db->query( 'SELECT lang_key, lang_vi, lang_en, lang_' . $dirlang . ', update_' . $dirlang . ' FROM ' . NV_LANGUAGE_GLOBALTABLE . ' WHERE idfile=' . $idfile . ' ORDER BY id ASC' );
				while( list( $lang_key, $lang_value_vi, $lang_value_en, $lang_value, $update_time ) = $result->fetch( 3 ) )
				{
					if( $lang_value != '' )
					{
						$numrows++;
						$lang_value = nv_unhtmlspecialchars( $lang_value );
						$lang_value = str_replace( "\'", "'", $lang_value );
						$lang_value = str_replace( "'", "\'", $lang_value );
						$lang_value = nv_nl2br( $lang_value );
						$lang_value = str_replace( '<br />', '<br />', $lang_value );

						$content_temp = "\$" . $langtype . "['" . $lang_key . "'] = '$lang_value';\n";
						$content_temp .= "/*\n";

						if( $dirlang != "vi" and ! empty( $lang_value_vi ) )
						{
							$lang_value_vi = nv_unhtmlspecialchars( $lang_value_vi );
							$lang_value_vi = str_replace( "\'", "'", $lang_value_vi );
							$lang_value_vi = str_replace( "'", "\'", $lang_value_vi );
							$lang_value_vi = nv_nl2br( $lang_value_vi );
							$lang_value_vi = str_replace( '<br />', '<br />', $lang_value_vi );
							$content_temp .= "\t vietnam:\t " . $lang_value_vi . "\n";
						}

						if( $dirlang != "en" and ! empty( $lang_value_en ) )
						{
							$lang_value_en = nv_unhtmlspecialchars( $lang_value_en );
							$lang_value_en = str_replace( "\'", "'", $lang_value_en );
							$lang_value_en = str_replace( "'", "\'", $lang_value_en );
							$lang_value_en = nv_nl2br( $lang_value_en );
							$lang_value_en = str_replace( '<br />', '<br />', $lang_value_en );
							$content_temp .= "\t english:\t " . $lang_value_en . "\n";
						}

						$content_temp .= "*/\n\n";

						if( $update_time > 0 )
						{
							$content_lang .= $content_temp;
						}
						else
						{
							$content_lang_no_check .= $content_temp;
						}
					}
				}

				if( ! empty( $content_lang_no_check ) )
				{
					$content_lang .= "\n\n/*---------------------------------------- Language untested ----------------------------------------------*/\n";
					$content_lang .= $content_lang_no_check;
					$array_lang_no_check[] = $include_lang;
				}
			}
			else
			{
				$result = $db->query( 'SELECT lang_key, lang_' . $dirlang . ' FROM ' . NV_LANGUAGE_GLOBALTABLE . ' WHERE idfile=' . $idfile . ' ORDER BY id ASC' );
				while( list( $lang_key, $lang_value ) = $result->fetch( 3 ) )
				{
					if( $lang_value != '' )
					{
						$numrows++;
						$lang_value = nv_unhtmlspecialchars( $lang_value );
						$lang_value = str_replace( "\'", "'", $lang_value );
						$lang_value = str_replace( "'", "\'", $lang_value );
						$lang_value = nv_nl2br( $lang_value );
						$lang_value = str_replace( '<br />', '<br />', $lang_value );
						$content_lang .= "\$" . $langtype . "['" . $lang_key . "'] = '" . $lang_value . "';\n";
					}
				}
			}
			if( $numrows )
			{
				$number_bytes = file_put_contents( $include_lang, $content_lang, LOCK_EX );
				if( empty( $number_bytes ) )
				{
					$errfile = str_replace( NV_ROOTDIR, '', str_replace( '\\', '/', $include_lang ) );
					return $lang_module['nv_error_write_file'] . ' : ' . $errfile;
				}
			}
		}
		return '';
	}
	else
	{
		return $lang_module['nv_error_exit_module'] . ' : ' . $module;
	}
}

$xtpl = new XTemplate( 'write.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file );
$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign( 'GLANG', $lang_global );

$include_lang = '';
$page_title = $language_array[$dirlang]['name'];

if( $nv_Request->isset_request( 'idfile,checksess', 'get' ) and $nv_Request->get_string( 'checksess', 'get' ) == md5( $nv_Request->get_int( 'idfile', 'get' ) . session_id() ) )
{
	$idfile = $nv_Request->get_int( 'idfile', 'get' );
	nv_mkdir( NV_ROOTDIR . '/language/', $dirlang );
	$content = nv_admin_write_lang( $dirlang, $idfile );

	if( empty( $content ) )
	{
		$xtpl->assign( 'INCLUDE_LANG', str_replace( NV_ROOTDIR, '', str_replace( '\\', '/', $include_lang ) ) );
		$xtpl->assign( 'URL', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=interface' );

		$xtpl->parse( 'main.complete' );
	}
	else
	{
		$xtpl->assign( 'CONTENT', $content );

		$xtpl->parse( 'main.error' );
	}

	$xtpl->parse( 'main' );
	$contents = $xtpl->text( 'main' );

	include NV_ROOTDIR . '/includes/header.php';
	echo nv_admin_theme( $contents );
	include NV_ROOTDIR . '/includes/footer.php';
}
elseif( $nv_Request->isset_request( 'checksess', 'get' ) and $nv_Request->get_string( 'checksess', 'get' ) == md5( 'writeallfile' . session_id() ) )
{
	$dirlang = $nv_Request->get_string( 'dirlang', 'get', '' );

	if( $dirlang != '' )
	{
		nv_mkdir( NV_ROOTDIR . '/language/', $dirlang );

		$content = '';
		$array_filename = array();

		$result = $db->query( 'SELECT idfile, author_' . $dirlang . ' FROM ' . NV_LANGUAGE_GLOBALTABLE . '_file ORDER BY idfile ASC' );
		while( list( $idfile, $author_lang ) = $result->fetch( 3 ) )
		{
			$content = nv_admin_write_lang( $dirlang, $idfile );

			if( ! empty( $content ) )
			{
				break;
			}
			else
			{
				$array_filename[] = str_replace( NV_ROOTDIR, '', str_replace( '\\', '/', $include_lang ) );
			}
		}

		if( empty( $content ) )
		{
			$xtpl->assign( 'URL', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=setting' );

			$i = 0;
			foreach( $array_filename as $name )
			{
				$xtpl->assign( 'NAME', $name );
				$xtpl->parse( 'main.write_allfile_complete.loop' );
			}

			$xtpl->parse( 'main.write_allfile_complete' );
		}
		else
		{
			$xtpl->parse( 'main.error_write_allfile' );
		}
	}
	else
	{
		$xtpl->parse( 'main.error_write_allfile' );
	}

	$xtpl->parse( 'main' );
	$contents = $xtpl->text( 'main' );

	include NV_ROOTDIR . '/includes/header.php';
	echo nv_admin_theme( $contents );
	include NV_ROOTDIR . '/includes/footer.php';
}
else
{
	Header( 'Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=main&dirlang=' . $dirlang );
	die();
}