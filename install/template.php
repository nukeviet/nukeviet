<?php

/**
 * @Project NUKEVIET 3.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2012 VINADES.,JSC. All rights reserved
 * @Createdate 2-1-2010 22:42
 */

if( ! defined( 'NV_MAINFILE' ) ) die( 'Stop!!!' );

function nv_site_theme( $step, $titletheme, $contenttheme )
{
	global $lang_module, $languageslist, $language_array, $global_config;

	$xtpl = new XTemplate( "theme.tpl", NV_ROOTDIR . "/install/tpl/" );
	$xtpl->assign( 'BASE_SITEURL', NV_BASE_SITEURL );
	$xtpl->assign( 'LANG_VARIABLE', NV_LANG_VARIABLE );
	$xtpl->assign( 'LANG_DATA', NV_LANG_DATA );
	$xtpl->assign( 'MAIN_TITLE', $titletheme );
	$xtpl->assign( 'MAIN_STEP', $step );
	$xtpl->assign( 'LANG', $lang_module );
	$xtpl->assign( 'VERSION', "v" . $global_config['version'] . " r" . $global_config['revision'] );

	$step_bar = array(
		$lang_module['select_language'],
		$lang_module['check_chmod'],
		$lang_module['license'],
		$lang_module['check_server'],
		$lang_module['config_database'],
		$lang_module['website_info'],
		$lang_module['done']
	);
	
	foreach( $step_bar as $i => $step_bar_i )
	{
		$n = $i + 1;
		$class = "";
		
		if( $step >= $n )
		{
			$class = " class=\"";
			$class .= ( $step > $n ) ? 'passed_step' : '';
			$class .= ( $step == $n ) ? 'current_step' : '';
			$class .= "\"";
		}
		
		$xtpl->assign( 'CLASS_STEP', $class );
		$xtpl->assign( 'STEP_BAR', $step_bar_i );
		$xtpl->assign( 'NUM', $n );
		$xtpl->parse( 'main.step_bar.loop' );
	}

	$xtpl->assign( 'LANGTYPESL', NV_LANG_DATA );
	$langname = $language_array[NV_LANG_DATA]['name'];
	$xtpl->assign( 'LANGNAMESL', $langname );

	foreach( $languageslist as $languageslist_i )
	{
		if( ! empty( $languageslist_i ) and ( NV_LANG_DATA != $languageslist_i ) )
		{
			$xtpl->assign( 'LANGTYPE', $languageslist_i );
			$langname = $language_array[$languageslist_i]['name'];
			$xtpl->assign( 'LANGNAME', $langname );
			$xtpl->parse( 'main.looplang' );
		}
	}
	
	$xtpl->parse( 'main.step_bar' );
	$xtpl->assign( 'MAIN_CONTENT', $contenttheme );
	$xtpl->parse( 'main' );
	$xtpl->out( 'main' );
}

function nv_step_1()
{
	global $lang_module, $languageslist, $language_array;

	$xtpl = new XTemplate( "step1.tpl", NV_ROOTDIR . "/install/tpl/" );
	$xtpl->assign( 'BASE_SITEURL', NV_BASE_SITEURL );
	$xtpl->assign( 'LANG_VARIABLE', NV_LANG_VARIABLE );

	foreach( $languageslist as $languageslist_i )
	{
		if( ! empty( $languageslist_i ) )
		{
			$langname = ( isset( $language_array[$languageslist_i]['name_' . NV_LANG_DATA] ) ) ? $language_array[$languageslist_i]['name_' . NV_LANG_DATA] : $language_array[$languageslist_i]['name'];

			$xtpl->assign( 'LANGTYPE', $languageslist_i );
			$xtpl->assign( 'SELECTED', ( NV_LANG_DATA == $languageslist_i ) ? ' selected="selected"' : '' );
			$xtpl->assign( 'LANGNAME', $langname );
			$xtpl->parse( 'step.languagelist' );
		}
	}
	
	$xtpl->assign( 'CURRENTLANG', NV_LANG_DATA );
	$xtpl->assign( 'LANG', $lang_module );
	$xtpl->parse( 'step' );
	return $xtpl->text( 'step' );
}

function nv_step_2( $array_dir_check, $array_ftp_data, $nextstep )
{
	global $lang_module, $sys_info, $step;
	
	$xtpl = new XTemplate( "step2.tpl", NV_ROOTDIR . "/install/tpl/" );
	$xtpl->assign( 'BASE_SITEURL', NV_BASE_SITEURL );
	$xtpl->assign( 'LANG_VARIABLE', NV_LANG_VARIABLE );
	$xtpl->assign( 'CURRENTLANG', NV_LANG_DATA );
	$xtpl->assign( 'LANG', $lang_module );
	$xtpl->assign( 'ACTIONFORM', NV_BASE_SITEURL . "install/index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&step=" . $step );

	if( $nextstep )
	{
		$xtpl->parse( 'step.nextstep' );
	}
	elseif( $sys_info['ftp_support'] and strpos( $sys_info['os'], 'WIN' ) === false )
	{
		$xtpl->assign( 'FTPDATA', $array_ftp_data );
		$xtpl->parse( 'step.ftpconfig.errorftp' );
		$xtpl->parse( 'step.ftpconfig' );
	}

	$a = 0;
	foreach( $array_dir_check as $dir => $check )
	{
		$class = ( $a % 2 == 0 ) ? "spec text_normal" : "specalt text_normal";
	
		$xtpl->assign( 'DATAFILE', array(
			"dir" => $dir,
			"check" => $check,
			"class" => $class
		) );
	
		$xtpl->parse( 'step.loopdir' );
		++$a;
	}
	
	if( ! ( strpos( $sys_info['os'], 'WIN' ) === false ) )
	{
		if( $nextstep )
		{
			$xtpl->parse( 'step.winhost.infonext' );
		}
		else
		{
			$xtpl->parse( 'step.winhost.inforeload' );
		}
		$xtpl->parse( 'step.winhost' );
	}

	$xtpl->parse( 'step' );
	return $xtpl->text( 'step' );
}

function nv_step_3( $license )
{
	global $lang_module;

	$xtpl = new XTemplate( "step3.tpl", NV_ROOTDIR . "/install/tpl/" );
	$xtpl->assign( 'BASE_SITEURL', NV_BASE_SITEURL );
	$xtpl->assign( 'LANG_VARIABLE', NV_LANG_VARIABLE );
	$xtpl->assign( 'CONTENT_LICENSE', $license );
	$xtpl->assign( 'CURRENTLANG', NV_LANG_DATA );
	$xtpl->assign( 'LANG', $lang_module );
	$xtpl->parse( 'step' );

	return $xtpl->text( 'step' );
}

function nv_step_4( $array_resquest, $array_support, $nextstep )
{
	global $lang_module;

	$xtpl = new XTemplate( "step4.tpl", NV_ROOTDIR . "/install/tpl/" );
	$xtpl->assign( 'BASE_SITEURL', NV_BASE_SITEURL );
	$xtpl->assign( 'LANG_VARIABLE', NV_LANG_VARIABLE );
	$xtpl->assign( 'CURRENTLANG', NV_LANG_DATA );
	$xtpl->assign( 'LANG', $lang_module );
	$xtpl->assign( 'DATA_REQUEST', $array_resquest );
	$xtpl->assign( 'DATA_SUPPORT', $array_support );

	if( $nextstep )
	{
		$xtpl->parse( 'step.nextstep' );
	}

	$xtpl->parse( 'step' );
	return $xtpl->text( 'step' );
}

function nv_step_5( $db_config, $nextstep )
{
	global $lang_module, $step;

	$xtpl = new XTemplate( "step5.tpl", NV_ROOTDIR . "/install/tpl/" );
	$xtpl->assign( 'BASE_SITEURL', NV_BASE_SITEURL );
	$xtpl->assign( 'LANG_VARIABLE', NV_LANG_VARIABLE );
	$xtpl->assign( 'CURRENTLANG', NV_LANG_DATA );
	$xtpl->assign( 'LANG', $lang_module );
	$xtpl->assign( 'DATADASE', $db_config );
	$xtpl->assign( 'ACTIONFORM', NV_BASE_SITEURL . "install/index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&step=" . $step );

	if( $db_config['num_table'] > 0 )
	{
		$xtpl->parse( 'step.db_detete' );
	}

	if( ! empty( $db_config['error'] ) )
	{
		$xtpl->parse( 'step.errordata' );
	}

	if( $nextstep )
	{
		$xtpl->parse( 'step.nextstep' );
	}

	$xtpl->parse( 'step' );
	return $xtpl->text( 'step' );
}

function nv_step_6( $array_data, $nextstep )
{
	global $lang_module, $step;

	$xtpl = new XTemplate( "step6.tpl", NV_ROOTDIR . "/install/tpl/" );
	$xtpl->assign( 'BASE_SITEURL', NV_BASE_SITEURL );
	$xtpl->assign( 'LANG_VARIABLE', NV_LANG_VARIABLE );
	$xtpl->assign( 'CURRENTLANG', NV_LANG_DATA );
	$xtpl->assign( 'LANG', $lang_module );
	$xtpl->assign( 'DATA', $array_data );
	$xtpl->assign( 'ACTIONFORM', NV_BASE_SITEURL . "install/index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&step=" . $step );

	if( ! empty( $array_data['error'] ) )
	{
		$xtpl->parse( 'step.errordata' );
	}

	if( $nextstep )
	{
		$xtpl->parse( 'step.nextstep' );
	}

	if( NV_LANG_DATA == 'vi' )
	{
		$xtpl->parse( 'step.viet_keyboard' );
	}

	$xtpl->parse( 'step' );
	return $xtpl->text( 'step' );
}

function nv_step_7( $finish )
{
	global $lang_module;

	$xtpl = new XTemplate( "step7.tpl", NV_ROOTDIR . "/install/tpl/" );
	$xtpl->assign( 'BASE_SITEURL', NV_BASE_SITEURL );
	$xtpl->assign( 'ADMINDIR', NV_ADMINDIR );
	$xtpl->assign( 'LANG_VARIABLE', NV_LANG_VARIABLE );
	$xtpl->assign( 'CURRENTLANG', NV_LANG_DATA );
	$xtpl->assign( 'LANG', $lang_module );

	if( $finish == 1 )
	{
		$xtpl->parse( 'step.finish1' );
	}
	else
	{
		$xtpl->parse( 'step.finish2' );
	}

	$xtpl->parse( 'step' );
	return $xtpl->text( 'step' );
}

?>