<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES ., JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Jan 17, 2011 11:34:27 AM
 */

if( ! defined( 'NV_MAINFILE' ) ) die( 'Stop!!!' );

if( ! nv_function_exists( 'nv_menu_theme_default_footer' ) )
{
	function nv_menu_theme_default_footer_config( $module, $data_block, $lang_block )
	{
		global $site_mods;

		$html = "\n";
		foreach( $site_mods as $modname => $modvalues )
		{
			$checked = in_array( $modname, $data_block['module_in_menu'] ) ? ' checked="checked"' : '';
			$html .= '<div style="float: left" class="w150"><label style="text-align: left"><input type="checkbox" ' . $checked . ' value="' . $modname . '" name="module_in_menu[]">' . $modvalues['custom_title'] . '</label></div>';
		}

		return '<tr><td>' . $lang_block['title_length'] . '</td><td>' . $html . '</td></tr>';
	}

	function nv_menu_theme_default_footer_submit( $module, $lang_block )
	{
		global $nv_Request;
		$return = array();
		$return['error'] = array();
		$return['config']['module_in_menu'] = $nv_Request->get_typed_array( 'module_in_menu', 'post', 'string' );
		return $return;
	}

	/**
	 * nv_menu_theme_default_footer()
	 *
	 * @param mixed $block_config
	 * @return
	 */
	function nv_menu_theme_default_footer( $block_config )
	{
		global $global_config, $site_mods, $lang_global, $module_name, $home;

		if( file_exists( NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/blocks/global.menu_footer.tpl' ) )
		{
			$block_theme = $global_config['module_theme'];
		}
		elseif( file_exists( NV_ROOTDIR . '/themes/' . $global_config['site_theme'] . '/blocks/global.menu_footer.tpl' ) )
		{
			$block_theme = $global_config['site_theme'];
		}
		else
		{
			$block_theme = 'default';
		}

		$xtpl = new XTemplate( 'global.menu_footer.tpl', NV_ROOTDIR . '/themes/' . $block_theme . '/blocks' );
		$xtpl->assign( 'LANG', $lang_global );
		$xtpl->assign( 'NV_BASE_SITEURL', NV_BASE_SITEURL );
		$xtpl->assign( 'BLOCK_THEME', $block_theme );
		$xtpl->assign( 'THEME_SITE_HREF', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA );

		$footer_menu = array();
		$footer_menu['index'] = array( 'custom_title' => $lang_global['Home'] );
		$footer_menu = array_merge( $footer_menu, $site_mods );

		$a = 0;
		foreach( $footer_menu as $modname => $modvalues )
		{
			if( in_array( $modname, $block_config['module_in_menu'] ) and ! empty( $modvalues['funcs'] ) )
			{
				if( $home == 1 and $a == 0 )
				{
					$module_current = ' class="current"';
				}
				elseif( $modname == $module_name and $home != 1 )
				{
					$module_current = ' class="current"';
				}
				else
				{
					$module_current = '';
				}

				if( $modname == 'index' )
				{
					$link = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA;
				}
				else
				{
					$link = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $modname;
				}

				$aryay_menu = array(
					'title' => $modvalues['custom_title'],
					'class' => $modname,
					'current' => $module_current,
					'link' => $link
				);

				$xtpl->assign( 'FOOTER_MENU', $aryay_menu );

				if( $a > 0 )
				{
					$xtpl->parse( 'main.footer_menu.defis' );
				}
				$xtpl->parse( 'main.footer_menu' );
				++ $a;
			}
		}
		$xtpl->parse( 'main' );
		return $xtpl->text( 'main' );
	}
}

if( defined( 'NV_SYSTEM' ) )
{
	$content = nv_menu_theme_default_footer( $block_config );
}