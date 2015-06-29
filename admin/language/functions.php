<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 2-2-2010 1:58
 */

if( ! defined( 'NV_ADMIN' ) or ! defined( 'NV_MAINFILE' ) or ! defined( 'NV_IS_MODADMIN' ) ) die( 'Stop!!!' );

unset( $page_title, $select_options );
$select_options = array();

$menu_top = array(
	'title' => $module_name,
	'module_file' => '',
	'custom_title' => $lang_global['mod_language']
);

$allow_func = array( 'main' );
if( empty( $global_config['idsite'] ) )
{
	$allow_func[] = 'read';
	$allow_func[] = 'copy';
	$allow_func[] = 'edit';
	$allow_func[] = 'download';
	$allow_func[] = 'interface';
	$allow_func[] = 'check';
	$allow_func[] = 'countries';
	if( defined( 'NV_IS_GODADMIN' ) )
	{
		$allow_func[] = 'setting';
		$allow_func[] = 'write';
		$allow_func[] = 'delete';
	}
}

if( ! isset( $global_config['site_description'] ) )
{
	$global_config['site_description'] = '';
	$global_config['cronjobs_next_time'] = NV_CURRENTTIME;
}

define( 'ALLOWED_HTML_LANG', 'a, b, blockquote, br, em, h1, h2, h3, h4, h5, h6, hr, p, span, strong' );

$allowed_html_tags = array_map( 'trim', explode( ',', ALLOWED_HTML_LANG ) );
$allowed_html_tags = '<' . implode( '><', $allowed_html_tags ) . '>';

define( 'NV_ALLOWED_HTML_LANG', $allowed_html_tags );
define( 'NV_IS_FILE_LANG', true );

$dirlang = $nv_Request->get_title( 'dirlang', 'get', '' );

/**
 * nv_admin_add_field_lang()
 *
 * @param mixed $dirlang
 * @return
 */
function nv_admin_add_field_lang( $dirlang )
{
	global $module_name, $db_config, $db, $language_array;

	if( isset( $language_array[$dirlang] ) and ! empty( $language_array[$dirlang] ) )
	{
		$add_field = true;

		$columns_array = $db->columns_array( NV_LANGUAGE_GLOBALTABLE . '_file' );
		foreach ( $columns_array as $row )
		{
			if( $row['field'] == 'author_' . $dirlang )
			{
				$add_field = false;
				break;
			}
		}

		if( $add_field == true )
		{
			$db->columns_add( NV_LANGUAGE_GLOBALTABLE, 'lang_' . $dirlang, 'string', 4000, true );
			$db->columns_add( NV_LANGUAGE_GLOBALTABLE, 'update_' . $dirlang, 'integer', 2147483647, true, 0);
			$db->columns_add( NV_LANGUAGE_GLOBALTABLE . '_file', 'author_' . $dirlang, 'string', 4000, true );
		}
	}
}

$language_array = nv_parse_ini_file( NV_ROOTDIR . '/includes/ini/langs.ini', true );