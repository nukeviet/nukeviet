<?php

/**
 * @Project NUKEVIET 3.x
 * @Author VINADES (contact@vinades.vn)
 * @Copyright (C) 2012 VINADES. All rights reserved
 * @Createdate Apr 20, 2010 10:47:41 AM
 */

if( ! defined( 'NV_IS_MOD_SEARCH' ) ) die( 'Stop!!!' );

/**
 * main_theme()
 * 
 * @param mixed $is_search
 * @param mixed $search
 * @param mixed $array_modul
 * @return
 */
function main_theme( $is_search, $search, $array_modul )
{
	global $module_info, $module_file, $global_config, $lang_global, $lang_module, $module_name, $my_head;

	$xtpl = new XTemplate( "form.tpl", NV_ROOTDIR . "/themes/" . $module_info['template'] . "/modules/" . $module_file );
	$xtpl->assign( 'LANG', $lang_module );
	$xtpl->assign( 'NV_MIN_SEARCH_LENGTH', NV_MIN_SEARCH_LENGTH );
	$xtpl->assign( 'NV_MAX_SEARCH_LENGTH', NV_MAX_SEARCH_LENGTH );

	$search['action'] = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name;
	$search['action'] .= "&page=0";
	$search['andChecked'] = $search['logic'] == 1 ? " checked=\"checked\"" : "";
	$search['orChecked'] = $search['logic'] == 1 ? "" : " checked=\"checked\"";

	$xtpl->assign( 'DATA', $search );

	if( ! empty( $array_modul ) )
	{
		foreach( $array_modul as $m_name => $m_info )
		{
			$m_info['value'] = $m_name;
			$m_info['selected'] = ( $m_name == $search['mod'] ) ? " selected=\"selected\"" : "";
			$xtpl->assign( 'MOD', $m_info );
			$xtpl->parse( 'main.select_option' );
		}
	}

	if( isset( $global_config['searchEngineUniqueID'] ) and ! empty( $global_config['searchEngineUniqueID'] ) )
	{
		$xtpl->assign( 'SEARCH_ENGINE_UNIQUE_ID', $global_config['searchEngineUniqueID'] );
		$xtpl->parse( 'main.search_engine_unique_ID' );
	}

	if( $is_search )
	{
		if( $search['is_error'] )
		{
			$xtpl->assign( 'SEARCH_RESULT', "<span class=\"red\">" . $search['errorInfo'] . "</span>" );
		}
		else
		{
			$xtpl->assign( 'SEARCH_RESULT', $search['content'] );
		}
	}

	$xtpl->parse( 'main' );
	return $xtpl->text( 'main' );
}

/**
 * result_theme()
 * 
 * @param mixed $result_array
 * @param mixed $mod
 * @param mixed $mod_custom_title
 * @param mixed $search
 * @param mixed $is_generate_page
 * @param mixed $limit
 * @param mixed $all_page
 * @return
 */
function result_theme( $result_array, $mod, $mod_custom_title, $search, $is_generate_page, $limit, $all_page )
{
	global $module_info, $module_file, $global_config, $lang_global, $lang_module, $db, $module_name;
	$xtpl = new XTemplate( "result.tpl", NV_ROOTDIR . "/themes/" . $module_info['template'] . "/modules/" . $module_file );
	$xtpl->assign( 'LANG', $lang_module );
	$xtpl->assign( 'SEARCH_RESULT_NUM', $all_page );
	$xtpl->assign( 'MODULE_CUSTOM_TITLE', $mod_custom_title );
	$xtpl->assign( 'HIDDEN_KEY', $search['key'] );

	foreach( $result_array as $result )
	{
		$xtpl->assign( 'RESULT', $result );
		$xtpl->parse( 'main.result' );
	}

	$base_url = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . "&q=" . urlencode( $search['key'] );
	if( $mod != "all" ) $base_url .= "&m=" . $mod;
	$base_url .= "&l=" . $search['logic'];

	if( $is_generate_page )
	{
		$generate_page = nv_generate_page( $base_url, $all_page, $limit, $search['page'] );
		if( ! empty( $generate_page ) )
		{
			$xtpl->assign( 'GENERATE_PAGE', $generate_page );
			$xtpl->parse( 'main.generate_page' );
		}
	}
	else
	{
		if( $all_page > $limit )
		{
			$xtpl->assign( 'MORE', $base_url );
			$xtpl->parse( 'main.more' );
		}
	}

	$xtpl->parse( 'main' );
	return $xtpl->text( 'main' );
}

?>