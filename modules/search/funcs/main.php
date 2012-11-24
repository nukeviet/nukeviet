<?php

/**
 * @Project NUKEVIET 3.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2012 VINADES.,JSC. All rights reserved
 * @Createdate 24/8/2010, 2:0
 */

if( ! defined( 'NV_IS_MOD_SEARCH' ) ) die( 'Stop!!!' );

$page_title = $module_info['custom_title'];
$key_words = $module_info['keywords'];
$mod_title = isset( $lang_module['main_title'] ) ? $lang_module['main_title'] : $module_info['custom_title'];

$array_modul = LoadModulesSearch();
$is_search = false;
$search = array( //
	'key' => '', //
	'len_key' => 0, //
	'mod' => 'all', //
	'logic' => 1, //OR
	'page' => 0, //
	'is_error' => false, //
	'errorInfo' => '', //
	'content' => '' //
);

if( $nv_Request->isset_request( 'q', 'get' ) )
{
	$is_search = true;

	$search['key'] = filter_text_input( 'q', 'get', '', 0, NV_MAX_SEARCH_LENGTH );
	$search['logic'] = $nv_Request->get_int( 'l', 'get', $search['logic'] );
	$search['mod'] = filter_text_input( 'm', 'get', 'all', $search['mod'] );
	$search['page'] = $nv_Request->get_int( 'page', 'get', 0 );

	if( $search['logic'] != 1 ) $search['logic'] = 0;
	if( ! isset( $array_modul[$search['mod']] ) ) $search['mod'] = "all";

	if( ! empty( $search['key'] ) )
	{
		$search['key'] = nv_unhtmlspecialchars( $search['key'] );
		if( ! $search['logic'] ) $search['key'] = preg_replace( array( "/^([\S]{1})\s/uis", "/\s([\S]{1})\s/uis", "/\s([\S]{1})$/uis" ), " ", $search['key'] );
		$search['key'] = strip_punctuation( $search['key'] );
		$search['key'] = trim( $search['key'] );
		$search['len_key'] = nv_strlen( $search['key'] );
		$search['key'] = nv_htmlspecialchars( $search['key'] );
	}

	if( $search['len_key'] < NV_MIN_SEARCH_LENGTH )
	{
		$search['is_error'] = true;
		$search['errorInfo'] = sprintf( $lang_module['searchQueryError'], NV_MIN_SEARCH_LENGTH );
	}
	else
	{
		if( ! empty( $search['mod'] ) and isset( $array_modul[$search['mod']] ) )
		{
			$mods = array( $search['mod'] => $array_modul[$search['mod']] );
			$limit = 10;
			$is_generate_page = true;
		}
		else
		{
			$mods = $array_modul;
			$limit = 3;
			$is_generate_page = false;
		}

		foreach( $mods as $m_name => $m_values )
		{
			$pages = $search['page'];
			$all_page = 0;
			$key = $search['key'];
			$dbkeyword = $db->dblikeescape( $search['key'] );
			$logic = $search['logic'] ? "AND" : "OR";

			$result_array = array();
			include ( NV_ROOTDIR . "/modules/" . $m_values['module_file'] . "/search.php" );

			if( ! empty( $all_page ) and ! empty( $result_array ) )
			{
				$search['content'] .= result_theme( $result_array, $m_name, $m_values['custom_title'], $search, $is_generate_page, $limit, $all_page );
			}
		}

		if( empty( $search['content'] ) ) $search['content'] = $lang_module['search_none'] . " &quot;" . $search['key'] . "&quot;";
	}
}

$contents = call_user_func( "main_theme", $is_search, $search, $array_modul );

include ( NV_ROOTDIR . "/includes/header.php" );
echo nv_site_theme( $contents );
include ( NV_ROOTDIR . "/includes/footer.php" );

?> 