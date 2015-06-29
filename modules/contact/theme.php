<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Apr 20, 2010 10:47:41 AM
 */

if( ! defined( 'NV_IS_MOD_CONTACT' ) ) die( 'Stop!!!' );

/**
 * main_theme()
 *
 * @param mixed $array_content
 * @param mixed $array_department
 * @param mixed $base_url
 * @param mixed $checkss
 * @return
 */
function contact_main_theme( $array_content, $array_department, $base_url, $checkss )
{
	global $module_file, $lang_global, $lang_module, $module_info;

	$xtpl = new XTemplate( 'form.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_file );
	$xtpl->assign( 'CONTENT', $array_content );
	$xtpl->assign( 'LANG', $lang_module );
	$xtpl->assign( 'ACTION_FILE', $base_url );
	$xtpl->assign( 'CHECKSS', $checkss );
	$xtpl->assign( 'GFX_WIDTH', NV_GFX_WIDTH );
	$xtpl->assign( 'GFX_HEIGHT', NV_GFX_HEIGHT );
	$xtpl->assign( 'NV_BASE_SITEURL', NV_BASE_SITEURL );
	$xtpl->assign( 'CAPTCHA_REFRESH', $lang_global['captcharefresh'] );
	$xtpl->assign( 'CAPTCHA_REFR_SRC', NV_BASE_SITEURL . 'images/refresh.png' );
	$xtpl->assign( 'NV_GFX_NUM', NV_GFX_NUM );

	if( ! empty( $array_content['error'] ) )
	{
		$xtpl->parse( 'main.error' );
	}

	if( defined( 'NV_IS_USER' ) )
	{
		$xtpl->parse( 'main.form.iuser' );
	}
	else
	{
		$xtpl->parse( 'main.form.iguest' );
	}

	if( ! empty( $array_department ) )
	{
		foreach( $array_department as $value => $row )
		{
			if( ! empty( $row['full_name'] ) )
			{
				$xtpl->assign( 'SELECT_NAME', $row['full_name'] );
				$xtpl->assign( 'SELECT_VALUE', $value );
				$xtpl->assign( 'SELECTED', ( $array_content['fpart'] == $value ) ? ' selected="selected"' : '' );
				$xtpl->parse( 'main.form.select_option_loop' );
			}
		}

		$xtpl->parse( 'main.form' );
	}

	$xtpl->parse( 'main' );
	return $xtpl->text( 'main' );
}

/**
 * sendcontact()
 *
 * @param mixed $url
 * @return
 */
function sendcontact( $url )
{
	global $module_file, $module_info, $lang_module;

	$xtpl = new XTemplate( 'sendcontact.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_file );

	$lang_module['urlrefresh'] = nv_url_rewrite( $url, true );

	$xtpl->assign( 'LANG', $lang_module );

	$xtpl->parse( 'main' );

	return $xtpl->text( 'main' );
}