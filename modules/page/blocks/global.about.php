<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 3/25/2010 18:6
 */

if( ! defined( 'NV_SYSTEM' ) ) die( 'Stop!!!' );

if( ! nv_function_exists( 'nv_message_page' ) )
{

	/**
	 * nv_message_page()
	 *
	 * @return
	 */
	function nv_message_page( $block_config )
	{
		global $global_config, $site_mods, $db, $module_name;
		$module = $block_config['module'];

		if( ! isset( $site_mods[$module] ) ) return '';

		if( $module_name == $module ) return '';

		$is_show = false;

		$pattern = '/^' . NV_LANG_DATA . '\_([a-zA-z0-9\_\-]+)\_([0-9]+)\_' . NV_CACHE_PREFIX . '\.cache$/i';

		$cache_files = nv_scandir( NV_ROOTDIR . '/' . NV_CACHEDIR . '/' . $module, $pattern );

		if( ( $count = sizeof( $cache_files ) ) >= 1 )
		{
			$num = rand( 1, $count );
			--$num;
			$cache_file = $cache_files[$num];

			if( ( $cache = nv_get_cache( $module, $cache_file ) ) != false )
			{
				$cache = unserialize( $cache );
				$link = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module . '&amp;' . NV_OP_VARIABLE . '=' . $cache['alias'] . $global_config['rewrite_exturl'];
				$title = $cache['page_title'];
				$bodytext = strip_tags( $cache['bodytext'] );

				$is_show = true;
			}
		}

		if( ! $is_show )
		{
			$sql = 'SELECT id,title,alias,bodytext,keywords,add_time,edit_time FROM ' . NV_PREFIXLANG . '_' . $site_mods[$module]['module_data'] . ' WHERE status=1 ORDER BY rand() DESC';

			if( ( $query = $db->query( $sql ) ) !== false )
			{
				if( ( $row = $query->fetch() ) !== false )
				{
					$link = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module . '&amp;' . NV_OP_VARIABLE . '=' . $row['alias'] . $global_config['rewrite_exturl'];
					$title = $row['title'];
					$bodytext = strip_tags( $row['bodytext'] );
					$bodytext = nv_clean60( $bodytext, 300 );

					$is_show = true;
				}
			}
		}

		if( $is_show )
		{
			if( file_exists( NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/page/block.about.tpl' ) )
			{
				$block_theme = $global_config['module_theme'];
			}
			elseif( file_exists( NV_ROOTDIR . '/themes/' . $global_config['site_theme'] . '/modules/page/block.about.tpl' ) )
			{
				$block_theme = $global_config['site_theme'];
			}
			else
			{
				$block_theme = 'default';
			}

			$xtpl = new XTemplate( 'block.about.tpl', NV_ROOTDIR . '/themes/' . $block_theme . '/modules/page' );
			$xtpl->assign( 'LINK', $link );
			$xtpl->assign( 'TITLE', $title );
			$xtpl->assign( 'BODYTEXT', $bodytext );

			$xtpl->parse( 'main' );
			return $xtpl->text( 'main' );
		}

		return '';
	}
}

$content = nv_message_page( $block_config );