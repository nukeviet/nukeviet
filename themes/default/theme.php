<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 31/05/2010, 00:36
 */

if( ! defined( 'NV_SYSTEM' ) or ! defined( 'NV_MAINFILE' ) ) die( 'Stop!!!' );

function nv_site_theme( $contents, $full = true )
{
	global $home, $array_mod_title, $lang_global, $language_array, $global_config, $site_mods, $module_name, $module_info, $op_file, $mod_title, $my_head, $my_footer, $client_info, $module_config;

	// Determine tpl file, check exists tpl file
	$layout_file = ( $full ) ? 'layout.' . $module_info['layout_funcs'][$op_file] . '.tpl' : 'simple.tpl';

	if( ! file_exists( NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/layout/' . $layout_file ) )
	{
		nv_info_die( $lang_global['error_layout_title'], $lang_global['error_layout_title'], $lang_global['error_layout_content'] );
	}

	$css = nv_html_css();

	// Css for admin
	if( defined( 'NV_IS_ADMIN' ) and $full )
	{
		$css .= "<link rel=\"stylesheet\" type=\"text/css\" href=\"" . NV_BASE_SITEURL . "themes/" . $global_config['module_theme'] . "/css/admin.css\" />\n";
	}

    // Style config
    if ( isset( $module_config['themes'][$global_config['module_theme']] ) )
	{
		if ( ! file_exists( NV_ROOTDIR . '/' . SYSTEM_FILES_DIR . '/css/theme_' . $global_config['module_theme'] . '_' . $global_config['idsite'] . '.css' ) )
    	{
			$config_theme = unserialize( $module_config['themes'][$global_config['module_theme']] );
		    $css_content = nv_css_setproperties( 'body', $config_theme['body'] );
		    $css_content .= nv_css_setproperties( 'a, a:link, a:active, a:visited', $config_theme['a_link'] );
		    $css_content .= nv_css_setproperties( 'a:hover', $config_theme['a_link_hover'] );
		    $css_content .= nv_css_setproperties( '#wraper', $config_theme['content'] );
		    $css_content .= nv_css_setproperties( '#header, #banner', $config_theme['header'] );
		    $css_content .= nv_css_setproperties( '#footer', $config_theme['footer'] );
			$css_content .= nv_css_setproperties( '.panel, .well, .nv-block-banners', $config_theme['block'] );
			$css_content .= nv_css_setproperties( '.panel-default>.panel-heading', $config_theme['block_heading'] );
		    $css_content .= nv_css_setproperties( 'generalcss', $config_theme['generalcss'] ); // Không nên thay đổi "generalcss"

		    file_put_contents( NV_ROOTDIR . '/' . SYSTEM_FILES_DIR . '/css/theme_' . $global_config['module_theme'] . '_' . $global_config['idsite'] . '.css', $css_content );

			unset( $config_theme, $css_content );
    	}
	    $my_footer .= "<link rel=\"stylesheet\" type=\"text/css\" href=\"" . NV_BASE_SITEURL . SYSTEM_FILES_DIR . "/css/theme_" . $global_config['module_theme'] . "_" . $global_config['idsite'] . ".css?t=" . $global_config['timestamp'] . "\" />\n";
	}

	$xtpl = new XTemplate( $layout_file, NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/layout' );
	$xtpl->assign( 'LANG', $lang_global );
	$xtpl->assign( 'TEMPLATE', $global_config['module_theme'] );
	$xtpl->assign( 'NV_BASE_SITEURL', NV_BASE_SITEURL );

	// System variables
	$xtpl->assign( 'THEME_PAGE_TITLE', nv_html_page_title() );
	$xtpl->assign( 'THEME_META_TAGS', nv_html_meta_tags() );
	$xtpl->assign( 'THEME_SITE_RSS', nv_html_site_rss() );
	$xtpl->assign( 'THEME_CSS', $css );
	$xtpl->assign( 'THEME_SITE_JS', nv_html_site_js() );

	// Module contents
	$xtpl->assign( 'MODULE_CONTENT', $contents );

	// Header variables
	$xtpl->assign( 'LOGO_SRC', NV_BASE_SITEURL . $global_config['site_logo'] );
	$xtpl->assign( 'SITE_NAME', $global_config['site_name'] );
	$xtpl->assign( 'THEME_SITE_HREF', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA );

	// Only full theme
	if( $full )
	{
		// Search form variables
		$xtpl->assign( 'THEME_SEARCH_QUERY_MAX_LENGTH', NV_MAX_SEARCH_LENGTH );
		$xtpl->assign( 'THEME_SEARCH_SUBMIT_ONCLICK', "nv_search_submit('topmenu_search_query', 'topmenu_search_submit', " . NV_MIN_SEARCH_LENGTH . ", " . NV_MAX_SEARCH_LENGTH . ");" );

		// Multiple languages
		if( $global_config['lang_multi'] and sizeof( $global_config['allow_sitelangs'] ) > 1 )
		{
			foreach( $global_config['allow_sitelangs'] as $lang_i )
			{
				$xtpl->assign( 'LANGSITENAME', $language_array[$lang_i]['name'] );
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

		// Breadcrumbs
		if( $home != 1 )
		{
			$arr_cat_title_i = array(
				'catid' => 0,
				'title' => $module_info['custom_title'],
				'link' => NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name
			);

			$xtpl->assign( 'BREADCRUMBS', $arr_cat_title_i );
			$xtpl->parse( 'main.breadcrumbs.loop' );

			foreach( $array_mod_title as $arr_cat_title_i )
			{
				$xtpl->assign( 'BREADCRUMBS', $arr_cat_title_i );
				$xtpl->parse( 'main.breadcrumbs.loop' );
			}

			$xtpl->parse( 'main.breadcrumbs' );
		}

		// Statistics image
		$theme_stat_img = '';
		if( $global_config['statistic'] and isset( $site_mods['statistics'] ) )
		{
			$theme_stat_img .= "<a title=\"" . $lang_global['viewstats'] . "\" href=\"" . NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=statistics\"><img alt=\"" . $lang_global['viewstats'] . "\" src=\"" . NV_BASE_SITEURL . "index.php?second=statimg&amp;p=" . nv_genpass() . "\" width=\"88\" height=\"31\" /></a>\n";
		}

		$xtpl->assign( 'THEME_STAT_IMG', $theme_stat_img );

		// Change theme types
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
	}

	$xtpl->parse( 'main' );
	$sitecontent = $xtpl->text( 'main' );

	// Only full theme
	if( $full )
	{
		$sitecontent = nv_blocks_content( $sitecontent );
		$sitecontent = str_replace( '[THEME_ERROR_INFO]', nv_error_info(), $sitecontent );

		if( defined( 'NV_IS_ADMIN' ) )
		{
			$my_footer = nv_admin_menu() . $my_footer;
		}
	}

	if( ! empty( $my_head ) ) $sitecontent = preg_replace( '/(<\/head>)/i', $my_head . '\\1', $sitecontent, 1 );
	if( ! empty( $my_footer ) ) $sitecontent = preg_replace( '/(<\/body>)/i', $my_footer . '\\1', $sitecontent, 1 );

	return $sitecontent;
}