<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 3/9/2010 23:25
 */

if( ! defined( 'NV_MAINFILE' ) ) die( 'Stop!!!' );

if( defined( 'NV_SYSTEM' ) )
{
	global $site_mods, $module_name, $module_info, $lang_module, $nv_Request;
	$module = $block_config['module'];
	if( isset( $site_mods[$module] ) )
	{
		if( $module == $module_name )
		{
			$lang_block_module = $lang_module;
		}
		else
		{
			$temp_lang_module = $lang_module;
			$lang_module = array();
			include NV_ROOTDIR . '/modules/' . $site_mods[$module]['module_file'] . '/language/' . NV_LANG_INTERFACE . '.php' ;
			$lang_block_module = $lang_module;
			$lang_module = $temp_lang_module;
		}

		$sql = "SELECT id, title, alias, parentid FROM " . NV_PREFIXLANG . "_" . $site_mods[$module]['module_data'] . "_categories WHERE parentid=0 ORDER BY weight";
		$list = nv_db_cache( $sql, '', $module );

		$key = nv_substr( $nv_Request->get_title( 'q', 'post', '', 1 ), 0, NV_MAX_SEARCH_LENGTH );
		$cat = $nv_Request->get_int( 'cat', 'post' );

		$path = NV_ROOTDIR . "/themes/" . $module_info['template'] . "/modules/" . $site_mods[$module]['module_file'];
		if( ! file_exists( NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $site_mods[$module]['module_file'] . '/block_search.tpl' ) )
		{
			$path = NV_ROOTDIR . "/themes/default/modules/" . $site_mods[$module]['module_file'];
		}

		$xtpl = new XTemplate( "block_search.tpl", $path );
		$xtpl->assign( 'LANG', $lang_block_module );
		$xtpl->assign( 'keyvalue', $key );
		$xtpl->assign( 'FORMACTION', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module . '&' . NV_OP_VARIABLE . '=search' );
		foreach( $list as $row )
		{
			$row['select'] = ( $row['id'] == $cat ) ? 'selected=selected' : '';
			$xtpl->assign( 'loop', $row );
			$xtpl->parse( 'main.loop' );
		}
		$xtpl->parse( 'main' );
		$content = $xtpl->text( 'main' );
	}
}