<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 31/05/2010, 00:36
 */

if( ! defined( 'NV_SYSTEM' ) or ! defined( 'NV_MAINFILE' ) ) die( 'Stop!!!' );

function nv_site_theme( $contents )
{
	global $home, $array_mod_title, $lang_global, $language_array, $global_config, $module_name, $module_info, $op_file, $mod_title, $my_head, $my_footer, $client_info;

	if( ! file_exists( NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/layout/layout.' . $module_info['layout_funcs'][$op_file] . '.tpl' ) )
	{
		nv_info_die( $lang_global['error_layout_title'], $lang_global['error_layout_title'], $lang_global['error_layout_content'] );
	}

	if( defined( 'NV_IS_ADMIN' ) )
	{
		$my_head .= "<link rel=\"stylesheet\" href=\"" . NV_BASE_SITEURL . "themes/" . $global_config['module_theme'] . "/css/admin.css\" type=\"text/css\" />";
	}

	$xtpl = new XTemplate( 'layout.' . $module_info['layout_funcs'][$op_file] . '.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/layout/' );
	$xtpl->assign( 'LANG', $lang_global );
	$xtpl->assign( 'TEMPLATE', $global_config['module_theme'] );
	$xtpl->assign( 'NV_BASE_SITEURL', NV_BASE_SITEURL );
	$xtpl->assign( 'THEME_META_TAGS', nv_html_meta_tags() );
	$xtpl->assign( 'THEME_SITE_JS', nv_html_site_js() );
	$xtpl->assign( 'THEME_CSS', nv_html_css() );
	$xtpl->assign( 'THEME_PAGE_TITLE', nv_html_page_title() );
	$xtpl->assign( 'MODULE_CONTENT', $contents . '&nbsp;' );
	$xtpl->assign( 'THEME_SITE_HREF', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA );
	$xtpl->assign( 'NV_NAME_VARIABLE', NV_NAME_VARIABLE );
	$xtpl->assign( 'NV_LANG_VARIABLE', NV_LANG_VARIABLE );
	$xtpl->assign( 'NV_LANG_DATA', NV_LANG_DATA );

	if( $global_config['lang_multi'] and sizeof( $global_config['allow_sitelangs'] ) > 1 )
	{
		$xtpl->assign( 'SELECTLANGSITE', $lang_global['langsite'] );

		foreach( $global_config['allow_sitelangs'] as $lang_i )
		{
			$langname = $language_array[$lang_i]['name'];
			$xtpl->assign( 'LANGSITENAME', $langname );
			$xtpl->assign( 'LANGSITEURL', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . $lang_i );

			if( NV_LANG_DATA != $lang_i )
			{
				$xtpl->parse( 'main.language.langitem' );
			}
			else
			{
				$xtpl->parse( 'main.language.langcuritem' );
			}
		}

		$xtpl->parse( 'main.language' );
	}

	//Breakcolumn
	if( $home != 1 )
	{
		$arr_cat_title_i = array(
			'catid' => 0,
			'title' => $module_info['custom_title'],
			'link' => NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name
		);

		$xtpl->assign( 'BREAKCOLUMN', $arr_cat_title_i );
		$xtpl->parse( 'main.mod_title.breakcolumn' );

		foreach( $array_mod_title as $arr_cat_title_i )
		{
			$xtpl->assign( 'BREAKCOLUMN', $arr_cat_title_i );
			$xtpl->parse( 'main.mod_title.breakcolumn' );
		}

		$xtpl->parse( 'main.mod_title' );
	}

	// Chuyen doi giao dien
	if( ! empty( $global_config['switch_mobi_des'] ) and ! empty( $module_info['mobile'] ) )
	{
		$num_theme_type = sizeof( $global_config['array_theme_type'] ) - 1;

		foreach( $global_config['array_theme_type'] as $i => $theme_type )
		{
			$xtpl->assign( 'STHEME_TYPE', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;nv' . NV_LANG_DATA . 'themever=' . $theme_type . '&amp;nv_redirect=' . nv_base64_encode( $client_info['selfurl'] ) );
			$xtpl->assign( 'STHEME_TITLE', $lang_global['theme_type_' . $i] );
			$xtpl->assign( 'STHEME_INFO', sprintf( $lang_global['theme_type_chose'], $lang_global['theme_type_' . $i] ) );

			if( $theme_type == $global_config['current_theme_type'] )
			{
				$xtpl->parse( 'main.theme_type.loop.current' );
			}
			else
			{
				$xtpl->parse( 'main.theme_type.loop.other' );
			}

			if( $i < $num_theme_type ) $xtpl->parse( 'main.theme_type.loop.space' );
			$xtpl->parse( 'main.theme_type.loop' );
		}

		$xtpl->parse( 'main.theme_type' );
	}
	unset( $theme_type, $i, $num_theme_type );

	$xtpl->parse( 'main' );

	$sitecontent = $xtpl->text( 'main' );
	$sitecontent = nv_blocks_content( $sitecontent );

	if( defined( 'NV_IS_ADMIN' ) )
	{
		$my_footer = nv_admin_menu() . $my_footer;
	}

	if( ! empty( $my_head ) ) $sitecontent = preg_replace( '/(<\/head>)/i', $my_head . "\\1", $sitecontent, 1 );
	if( ! empty( $my_footer ) ) $sitecontent = preg_replace( '/(<\/body>)/i', $my_footer . "\\1", $sitecontent, 1 );

	return $sitecontent;
}