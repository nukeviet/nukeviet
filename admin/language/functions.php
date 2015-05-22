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
	$allow_func[] = 'countries';
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

$language_array = nv_parse_ini_file( NV_ROOTDIR . '/includes/ini/langs.ini', true );