<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 31/05/2010, 00:36
 */

if( ! defined( 'NV_SYSTEM' ) ) die( 'Stop!!!' );

/**
 * nv_banner_theme_main()
 *
 * @param mixed $contents
 * @return
 */
function nv_banner_theme_main( $contents )
{
	global $global_config, $module_name, $module_info, $module_file, $lang_module;

	$xtpl = new XTemplate( 'home.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_file );
	$xtpl->assign( 'LANG', $lang_module );

	if( ! empty( $contents['rows'] ) )
	{
		$xtpl->assign( 'MAIN_PAGE_INFO', $contents['info'] );
		$xtpl->parse( 'main.if_banner_plan.info' );

		foreach( $contents['rows'] as $row )
		{
			$xtpl->clear_autoreset();
			$xtpl->assign( 'PLAN_TITLE', $row['title'][0] );
			$xtpl->assign( 'PLAN_LANG_TITLE', $row['blang'][0] );
			$xtpl->assign( 'PLAN_LANG_NAME', $row['blang'][1] );
			$xtpl->assign( 'PLAN_SIZE_TITLE', $row['size'][0] );
			$xtpl->assign( 'PLAN_SIZE_NAME', $row['size'][1] );
			$xtpl->assign( 'PLAN_FORM_TITLE', $row['form'][0] );
			$xtpl->assign( 'PLAN_FORM_NAME', $row['form'][1] );
			$xtpl->assign( 'PLAN_DESCRIPTION_TITLE', $row['description'][0] );
			$xtpl->assign( 'PLAN_DESCRIPTION_NAME', $row['description'][1] );
			$xtpl->assign( 'PLAN_DETAIL', $contents['detail'] );
			$xtpl->set_autoreset();
			$xtpl->parse( 'main.if_banner_plan.banner_plan' );
		}

		$xtpl->parse( 'main.if_banner_plan' );
	}

	$xtpl->assign( 'CONTAINERID', $contents['containerid'] );
	$xtpl->assign( 'AJ', $contents['aj'] );

	if( defined( 'NV_IS_BANNER_CLIENT' ) )
	{
		$xtpl->assign( 'clientinfo_link', $contents['clientinfo_link'] );
		$xtpl->assign( 'clientinfo_addads', $contents['clientinfo_addads'] );
		$xtpl->assign( 'clientinfo_stats', $contents['clientinfo_stats'] );
		$xtpl->parse( 'main.management' );
	}

	$xtpl->parse( 'main' );
	return $xtpl->text( 'main' );
}

/**
 * logininfo_theme()
 *
 * @param mixed $contents
 * @return
 */
function logininfo_theme( $contents )
{
	global $global_config, $module_name, $module_file, $module_info;

	$xtpl = new XTemplate( 'logininfo.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_file );
	$xtpl->assign( 'CLIENT_LOGIN_INFO', $contents['client_info'] );
	$xtpl->assign( 'LOGIN_LANG', $contents['login'] );
	$xtpl->assign( 'LOGIN_INPUT_NAME', $contents['login_input_name'] );
	$xtpl->assign( 'LOGIN_INPUT_MAXLENGTH', $contents['login_input_maxlength'] );
	$xtpl->assign( 'PASSWORD_LANG', $contents['password'] );
	$xtpl->assign( 'PASS_INPUT_NAME', $contents['pass_input_name'] );
	$xtpl->assign( 'PASS_INPUT_MAXLENGTH', $contents['pass_input_maxlength'] );

	if( $contents['gfx_chk'] )
	{
		$xtpl->assign( 'CAPTCHA_LANG', $contents['captcha'] );
		$xtpl->assign( 'CAPTCHA_NAME', $contents['captcha_name'] );
		$xtpl->assign( 'CAPTCHA_IMG', $contents['captcha_img'] );
		$xtpl->assign( 'CAPTCHA_MAXLENGTH', $contents['captcha_maxlength'] );
		$xtpl->assign( 'CAPTCHA_REFRESH', $contents['captcha_refresh'] );
		$xtpl->assign( 'CAPTCHA_REFR_SRC', $contents['captcha_refr_src'] );
		$xtpl->parse( 'logininfo.captcha' );
	}

	$xtpl->assign( 'SUBMIT_LANG', $contents['submit'] );
	$xtpl->assign( 'SM_BUTTON_NAME', $contents['sm_button_name'] );
	$xtpl->assign( 'SM_BUTTON_ONCLICK', $contents['sm_button_onclick'] );
	$xtpl->parse( 'logininfo' );

	return $xtpl->text( 'logininfo' );
}

/**
 * clientinfo_theme()
 *
 * @param mixed $contents
 * @return
 */
function clientinfo_theme( $contents )
{
	global $global_config, $module_name, $module_file, $module_info;

	$xtpl = new XTemplate( 'clientinfo.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_file );
	$xtpl->assign( 'CONTAINERID', $contents['containerid'] );
	$xtpl->assign( 'AJ', $contents['aj'] );
	$xtpl->parse( 'clientinfo' );

	return $xtpl->text( 'clientinfo' );
}

/**
 * clinfo_theme()
 *
 * @param mixed $contents
 * @param mixed $manament
 * @return
 */
function clinfo_theme( $contents, $manament )
{
	global $global_config, $module_name, $module_file, $module_info, $lang_module;

	$xtpl = new XTemplate( 'clinfo.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_file );

	if( defined( 'NV_IS_BANNER_CLIENT' ) )
	{
		$xtpl->assign( 'LANG', $lang_module );
		$xtpl->assign( 'clientinfo_link', $manament['clientinfo_link'] );
		$xtpl->assign( 'clientinfo_addads', $manament['clientinfo_addads'] );
		$xtpl->assign( 'clientinfo_stats', $manament['clientinfo_stats'] );
		$xtpl->parse( 'clinfo.management' );
	}

	$a = 0;
	foreach( $contents['rows'] as $values )
	{
		$class = ( $a % 2 == 0 ) ? "act" : "deact";

		$xtpl->clear_autoreset();
		$xtpl->assign( 'CLASS', $class );
		$xtpl->assign( 'INFO_NAME', $values[0] );
		$xtpl->assign( 'INFO_VALUE', $values[1] );
		$xtpl->set_autoreset();
		$xtpl->parse( 'clinfo.name_value' );

		++$a;
	}

	$xtpl->assign( 'EDIT_ONCLICK', $contents['edit_onclick'] );
	$xtpl->assign( 'EDIT_NAME', $contents['edit_name'] );
	$xtpl->parse( 'clinfo' );

	return $xtpl->text( 'clinfo' );
}

/**
 * cledit_theme()
 *
 * @param mixed $contents
 * @return
 */
function cledit_theme( $contents )
{
	global $global_config, $module_name, $module_file, $module_info;
	$xtpl = new XTemplate( 'cledit.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_file );

	foreach( $contents['rows'] as $row )
	{
		$xtpl->clear_autoreset();
		$xtpl->assign( 'LT_NAME', $row[0] );
		$xtpl->assign( 'LT_ID', $row[1] );
		$xtpl->assign( 'LT_VALUE', $row[2] );
		$xtpl->assign( 'LT_MAXLENGTH', $row[3] );
		$xtpl->set_autoreset();
		$xtpl->parse( 'cledit.lt' );
	}

	foreach( $contents['npass'] as $row )
	{
		$xtpl->clear_autoreset();
		$xtpl->assign( 'NPASS_NAME', $row[0] );
		$xtpl->assign( 'NPASS_ID', $row[1] );
		$xtpl->assign( 'NPASS_MAXLENGTH', $row[2] );
		$xtpl->set_autoreset();
		$xtpl->parse( 'cledit.npass' );
	}

	$xtpl->assign( 'EDIT_NAME', $contents['edit_name'] );
	$xtpl->assign( 'EDIT_ONCLICK', $contents['edit_onclick'] );
	$xtpl->assign( 'EDIT_ID', $contents['edit_id'] );
	$xtpl->assign( 'CANCEL_NAME', $contents['cancel_name'] );
	$xtpl->assign( 'CANCEL_ONCLICK', $contents['cancel_onclick'] );

	$xtpl->parse( 'cledit' );
	return $xtpl->text( 'cledit' );
}