<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 10/03/2010 10:51
 */

if( ! defined( 'NV_IS_MOD_STATISTICS' ) ) die( 'Stop!!!' );

/**
 * nv_theme_statistics_referer()
 *
 * @return
 */
function nv_theme_statistics_referer( $cts, $total )
{
	global $module_info, $module_file, $lang_module, $lang_global, $module_name;

	$xtpl = new XTemplate( 'referer.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_file );

	if( $total )
	{
		$xtpl->assign( 'CTS', $cts );

		foreach( $cts['rows'] as $m )
		{
			if( ! empty( $m['count'] ) )
			{
				$proc = ceil( ( $m['count'] / $cts['max'] ) * 100 );
				$m['count'] = number_format( $m['count'] );
				$xtpl->assign( 'M', $m );
				$xtpl->assign( 'SRC', NV_BASE_SITEURL . 'themes/' . $module_info['template'] . '/images/statistics/bg.gif' );
				$xtpl->assign( 'HEIGHT', $proc * 2 );

				$xtpl->parse( 'main.loop.img' );
			}

			$xtpl->parse( 'main.loop' );
		}

		foreach( $cts['rows'] as $key => $m )
		{
			$xtpl->assign( 'M', $m );

			if( $key == $cts['current_month'] )
			{
				$xtpl->parse( 'main.loop_1.m_c' );
			}
			else
			{
				$xtpl->parse( 'main.loop_1.m_o' );
			}

			$xtpl->parse( 'main.loop_1' );
		}
	}

	$xtpl->parse( 'main' );
	return $xtpl->text( 'main' );
}

/**
 * nv_theme_statistics_allreferers()
 *
 * @return
 */
function nv_theme_statistics_allreferers( $num_items, $cts, $host_list )
{
	global $module_info, $module_file, $lang_module, $lang_global, $module_name;

	$xtpl = new XTemplate( 'allreferers.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_file );

	if( $num_items )
	{
		if( ! empty( $host_list ) )
		{
			$xtpl->assign( 'CTS', $cts );

			$a = 0;
			foreach( $cts['rows'] as $key => $value )
			{
				$class = ( $a % 2 == 0 ) ? " class=\"second\"" : "";

				$xtpl->assign( 'CLASS', $class );
				$xtpl->assign( 'KEY', $key );

				if( $value[0] )
				{
					$proc = ceil( ( $value[0] / $cts['max'] ) * 100 );

					$xtpl->assign( 'SRC', NV_BASE_SITEURL . 'themes/' . $module_info['template'] . '/images/statistics/bg2.gif' );
					$xtpl->assign( 'WIDTH', $proc * 3 );

					$xtpl->parse( 'main.loop.img' );
					$value[0] = number_format( $value[0] );
				}
				$xtpl->assign( 'VALUE', $value );
				++$a;

				$xtpl->parse( 'main.loop' );
			}

			if( ! empty( $cts['generate_page'] ) )
			{
				$xtpl->parse( 'main.gp' );
			}
		}
	}

	$xtpl->parse( 'main' );
	return $xtpl->text( 'main' );
}

/**
 * nv_theme_statistics_allbots()
 *
 * @return
 */
function nv_theme_statistics_allbots( $num_items, $bot_list, $cts )
{
	global $module_info, $module_file, $lang_module, $lang_global, $module_name;

	$xtpl = new XTemplate( 'allbots.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_file );

	if( $num_items )
	{
		if( ! empty( $bot_list ) )
		{
			$xtpl->assign( 'CTS', $cts );

			$a = 0;
			foreach( $cts['rows'] as $key => $value )
			{
				$class = ( $a % 2 == 0 ) ? " class=\"second\"" : "";

				$xtpl->assign( 'CLASS', $class );
				$xtpl->assign( 'KEY', $key );

				if( $value[0] )
				{
					$proc = ceil( ( $value[0] / $cts['max'] ) * 100 );

					$xtpl->assign( 'SRC', NV_BASE_SITEURL . 'themes/' . $module_info['template'] . '/images/statistics/bg2.gif' );
					$xtpl->assign( 'WIDTH', $proc * 3 );

					$xtpl->parse( 'main.loop.img' );
					$value[0] = number_format( $value[0] );
				}
				$xtpl->assign( 'VALUE', $value );
				++$a;

				$xtpl->parse( 'main.loop' );
			}

			if( ! empty( $cts['generate_page'] ) )
			{
				$xtpl->parse( 'main.gp' );
			}
		}
	}

	$xtpl->parse( 'main' );
	return $xtpl->text( 'main' );
}

/**
 * nv_theme_statistics_allos()
 *
 * @return
 */
function nv_theme_statistics_allos( $num_items, $os_list, $cts )
{
	global $module_info, $module_file, $lang_module, $lang_global, $module_name;

	$xtpl = new XTemplate( 'allos.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_file );

	if( $num_items )
	{
		if( ! empty( $os_list ) )
		{
			$xtpl->assign( 'CTS', $cts );

			$a = 0;
			foreach( $cts['rows'] as $key => $value )
			{
				$class = ( $a % 2 == 0 ) ? " class=\"second\"" : "";

				$xtpl->assign( 'CLASS', $class );
				$xtpl->assign( 'KEY', $key );

				if( $value[0] )
				{
					$proc = ceil( ( $value[0] / $cts['max'] ) * 100 );

					$xtpl->assign( 'SRC', NV_BASE_SITEURL . 'themes/' . $module_info['template'] . '/images/statistics/bg2.gif' );
					$xtpl->assign( 'WIDTH', $proc * 3 );

					$xtpl->parse( 'main.loop.img' );
					$value[0] = number_format( $value[0] );
				}
				$xtpl->assign( 'VALUE', $value );
				++$a;

				$xtpl->parse( 'main.loop' );
			}

			if( ! empty( $cts['generate_page'] ) )
			{
				$xtpl->parse( 'main.gp' );
			}
		}
	}

	$xtpl->parse( 'main' );
	return $xtpl->text( 'main' );
}

/**
 * nv_theme_statistics_allbrowsers()
 *
 * @return
 */
function nv_theme_statistics_allbrowsers( $num_items, $browsers_list, $cts )
{
	global $module_info, $module_file, $lang_module, $lang_global, $module_name;

	$xtpl = new XTemplate( 'allbrowsers.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_file );

	if( $num_items )
	{
		if( ! empty( $browsers_list ) )
		{
			$xtpl->assign( 'CTS', $cts );

			$a = 0;
			foreach( $cts['rows'] as $key => $value )
			{
				$class = ( $a % 2 == 0 ) ? " class=\"second\"" : "";

				$xtpl->assign( 'CLASS', $class );
				$xtpl->assign( 'KEY', $key );

				if( $value[0] )
				{
					$proc = ceil( ( $value[0] / $cts['max'] ) * 100 );

					$xtpl->assign( 'SRC', NV_BASE_SITEURL . 'themes/' . $module_info['template'] . '/images/statistics/bg2.gif' );
					$xtpl->assign( 'WIDTH', $proc * 3 );

					$xtpl->parse( 'main.loop.img' );
					$value[0] = number_format( $value[0] );
				}
				$xtpl->assign( 'VALUE', $value );

				++$a;

				$xtpl->parse( 'main.loop' );
			}

			if( ! empty( $cts['generate_page'] ) )
			{
				$xtpl->parse( 'main.gp' );
			}
		}
	}

	$xtpl->parse( 'main' );
	return $xtpl->text( 'main' );
}

/**
 * nv_theme_statistics_allcountries()
 *
 * @return
 */
function nv_theme_statistics_allcountries( $num_items, $countries_list, $cts )
{
	global $module_info, $module_file, $lang_module, $lang_global, $module_name;

	$xtpl = new XTemplate( 'allcountries.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_file );

	if( $num_items )
	{
		if( ! empty( $countries_list ) )
		{
			$xtpl->assign( 'CTS', $cts );

			$a = 0;
			foreach( $cts['rows'] as $key => $value )
			{
				$class = ( $a % 2 == 0 ) ? " class=\"second\"" : "";

				$xtpl->assign( 'CLASS', $class );
				$xtpl->assign( 'KEY', $key );

				if( $value[1] )
				{
					$proc = ceil( ( $value[1] / $cts['max'] ) * 100 );

					$xtpl->assign( 'SRC', NV_BASE_SITEURL . 'themes/' . $module_info['template'] . '/images/statistics/bg2.gif' );
					$xtpl->assign( 'WIDTH', $proc * 3 );

					$xtpl->parse( 'main.loop.img' );
					$value[1] = number_format( $value[1] );
				}
				$xtpl->assign( 'VALUE', $value );
				++$a;

				$xtpl->parse( 'main.loop' );
			}

			if( ! empty( $cts['generate_page'] ) )
			{
				$xtpl->parse( 'main.gp' );
			}
		}
	}

	$xtpl->parse( 'main' );
	return $xtpl->text( 'main' );
}

/**
 * nv_theme_statistics_main()
 *
 * @return
 */
function nv_theme_statistics_main( $ctsy, $ctsm, $ctsdm, $ctsdw, $ctsc, $ctsb, $ctso, $ctsh )
{
	global $module_info, $module_name, $module_file, $lang_module, $lang_global;

	$xtpl = new XTemplate( 'main.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_file );

	$xtpl->assign( 'CTS', $ctsy );

	foreach( $ctsy['rows'] as $key => $m )
	{
		if( ! empty( $m ) )
		{
			$xtpl->assign( 'M', number_format( $m ) );
			$proc = ceil( ( $m / $ctsy['max'] ) * 100 );
			$xtpl->assign( 'SRC', NV_BASE_SITEURL . 'themes/' . $module_info['template'] . '/images/statistics/bg.gif' );
			$xtpl->assign( 'HEIGHT', $proc * 2 );

			$xtpl->parse( 'main.year.loop.img' );
		}

		$xtpl->parse( 'main.year.loop' );
	}

	foreach( $ctsy['rows'] as $key => $m )
	{
		$xtpl->assign( 'KEY', $key );

		if( $key == $ctsy['current_year'] )
		{
			$xtpl->parse( 'main.year.loop_1.yc' );
		}
		else
		{
			$xtpl->parse( 'main.year.loop_1.yc_o' );
		}

		$xtpl->parse( 'main.year.loop_1' );
	}

	$xtpl->parse( 'main.year' );

	//Thong ke theo thang
	$xtpl->assign( 'CTS', $ctsm );

	foreach( $ctsm['rows'] as $m )
	{
		if( ! empty( $m['count'] ) )
		{
			$proc = ceil( ( $m['count'] / $ctsm['max'] ) * 100 );
			$m['count'] = number_format( $m['count'] );
			$xtpl->assign( 'M', $m );
			$xtpl->assign( 'SRC', NV_BASE_SITEURL . 'themes/' . $module_info['template'] . '/images/statistics/bg.gif' );
			$xtpl->assign( 'HEIGHT', $proc * 2 );

			$xtpl->parse( 'main.month.loop.img' );
		}

		$xtpl->parse( 'main.month.loop' );
	}

	foreach( $ctsm['rows'] as $key => $m )
	{
		$xtpl->assign( 'KEY', $key );
		$xtpl->assign( 'M', $m );

		if( $key == $ctsm['current_month'] )
		{
			$xtpl->parse( 'main.month.loop_1.mc' );
		}
		else
		{
			$xtpl->parse( 'main.month.loop_1.mc_o' );
		}

		$xtpl->parse( 'main.month.loop_1' );
	}

	$xtpl->parse( 'main.month' );
	//Thong ke theo thang

	//thong ke theo ngay trong thang
	$xtpl->assign( 'CTS', $ctsdm );

	foreach( $ctsdm['rows'] as $key => $m )
	{
		$xtpl->assign( 'M', number_format( $m ) );

		if( ! empty( $m ) )
		{
			$proc = ceil( ( $m / $ctsdm['max'] ) * 100 );

			$xtpl->assign( 'SRC', NV_BASE_SITEURL . 'themes/' . $module_info['template'] . '/images/statistics/bg.gif' );
			$xtpl->assign( 'HEIGHT', $proc * 2 );

			$xtpl->parse( 'main.day_m.loop.img' );
		}

		$xtpl->parse( 'main.day_m.loop' );
	}

	foreach( $ctsdm['rows'] as $key => $m )
	{
		$xtpl->assign( 'KEY', $key );

		if( $key == $ctsdm['current_day'] )
		{
			$xtpl->parse( 'main.day_m.loop_1.dc' );
		}
		else
		{
			$xtpl->parse( 'main.day_m.loop_1.dc_o' );
		}

		$xtpl->parse( 'main.day_m.loop_1' );
	}
	$xtpl->parse( 'main.day_m' );
	//thong ke theo ngay trong thang

	//Thong ke theo ngay cua tuan
	$xtpl->assign( 'CTS', $ctsdw );

	foreach( $ctsdw['rows'] as $key => $m )
	{
		if( ! empty( $m['count'] ) )
		{
			$proc = ceil( ( $m['count'] / $ctsdw['max'] ) * 100 );

			$xtpl->assign( 'SRC', NV_BASE_SITEURL . 'themes/' . $module_info['template'] . '/images/statistics/bg.gif' );
			$xtpl->assign( 'HEIGHT', $proc * 2 );
			$m['count'] = number_format( $m['count'] );
			$xtpl->assign( 'M', $m );
			$xtpl->parse( 'main.day_k.loop.img' );
		}

		$xtpl->parse( 'main.day_k.loop' );
	}

	foreach( $ctsdw['rows'] as $key => $m )
	{
		$xtpl->assign( 'KEY', $key );
		$xtpl->assign( 'M', $m );

		if( $key == $ctsdw['current_dayofweek'] )
		{
			$xtpl->parse( 'main.day_k.loop_1.dc' );
		}
		else
		{
			$xtpl->parse( 'main.day_k.loop_1.dc_o' );
		}

		$xtpl->parse( 'main.day_k.loop_1' );
	}
	$xtpl->parse( 'main.day_k' );
	//Thong ke theo ngay cua tuan

	//Thong ke theo gio trong ngay
	$xtpl->assign( 'CTS', $ctsh );

	if( ! empty( $ctsh['rows'] ) )
	{
		foreach( $ctsh['rows'] as $key => $m )
		{
			if( ! empty( $m ) )
			{
				$xtpl->assign( 'M', number_format( $m ) );

				$proc = ceil( ( $m / $ctsh['max'] ) * 100 );

				$xtpl->assign( 'SRC', NV_BASE_SITEURL . 'themes/' . $module_info['template'] . '/images/statistics/bg.gif' );
				$xtpl->assign( 'HEIGHT', $proc * 2 );

				$xtpl->parse( 'main.hour.loop.img' );
			}

			$xtpl->parse( 'main.hour.loop' );
		}

		foreach( $ctsh['rows'] as $key => $m )
		{
			$xtpl->assign( 'KEY', $key );

			if( $key == $ctsh['current_hour'] )
			{
				$xtpl->parse( 'main.hour.loop_1.h' );
			}
			else
			{
				$xtpl->parse( 'main.hour.loop_1.h_o' );
			}

			$xtpl->parse( 'main.hour.loop_1' );
		}
	}

	$xtpl->parse( 'main.hour' );
	//Thong ke theo gio trong ngay

	//Thong ke theo quoc gia
	$xtpl->assign( 'CTS', $ctsc );

	$a = 0;
	foreach( $ctsc['rows'] as $key => $value )
	{
		$class = ( $a % 2 == 0 ) ? " class=\"second\"" : "";
		$xtpl->assign( 'CLASS', $class );
		$xtpl->assign( 'KEY', $key );

		if( $value[1] )
		{
			$proc = ceil( ( $value[1] / $ctsc['max'] ) * 100 );

			$xtpl->assign( 'SRC', NV_BASE_SITEURL . 'themes/' . $module_info['template'] . '/images/statistics/bg2.gif' );
			$xtpl->assign( 'WIDTH', $proc * 3 );

			$xtpl->parse( 'main.ct.loop.img' );
			$value[1] = number_format( $value[1] );
		}
		$xtpl->assign( 'VALUE', $value );

		++$a;
		$xtpl->parse( 'main.ct.loop' );
	}

	if( $ctsc['others'][1] )
	{
		$class = ( $a % 2 == 0 ) ? " class=\"second\"" : "";
		$xtpl->assign( 'CLASS', $class );
		$xtpl->assign( 'URL', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $module_info['alias']['allcountries'] );
		$xtpl->parse( 'main.ct.ot' );
	}

	$xtpl->parse( 'main.ct' );
	//Thong ke theo quoc gia

	//Thong ke theo trinh duyet
	$xtpl->assign( 'CTS', $ctsb );

	$a = 0;
	foreach( $ctsb['rows'] as $key => $value )
	{
		$class = ( $a % 2 == 0 ) ? " class=\"second\"" : "";
		$xtpl->assign( 'CLASS', $class );
		$xtpl->assign( 'KEY', $key );

		if( $value[0] )
		{
			$proc = ceil( ( $value[0] / $ctsb['max'] ) * 100 );
			$xtpl->assign( 'SRC', NV_BASE_SITEURL . 'themes/' . $module_info['template'] . '/images/statistics/bg2.gif' );
			$xtpl->assign( 'WIDTH', $proc * 3 );

			$xtpl->parse( 'main.br.loop.img' );
			$value[0] = number_format( $value[0] );
		}
		$xtpl->assign( 'VALUE', $value );

		$xtpl->parse( 'main.br.loop' );
		++$a;
	}

	if( $ctsb['others'][1] )
	{
		$class = ( $a % 2 == 0 ) ? " class=\"second\"" : "";
		$xtpl->assign( 'CLASS', $class );
		$xtpl->assign( 'URL', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $module_info['alias']['allbrowsers'] );
		$xtpl->parse( 'main.br.ot' );
	}

	$xtpl->parse( 'main.br' );
	//Thong ke theo trinh duyet

	//Thong ke theo he dieu hanh
	$xtpl->assign( 'CTS', $ctso );

	$a = 0;
	foreach( $ctso['rows'] as $key => $value )
	{
		$class = ( $a % 2 == 0 ) ? " class=\"second\"" : "";

		$xtpl->assign( 'CLASS', $class );
		$xtpl->assign( 'KEY', $key );

		if( $value[0] )
		{
			$proc = ceil( ( $value[0] / $ctso['max'] ) * 100 );

			$xtpl->assign( 'SRC', NV_BASE_SITEURL . 'themes/' . $module_info['template'] . '/images/statistics/bg2.gif' );
			$xtpl->assign( 'WIDTH', $proc * 3 );

			$xtpl->parse( 'main.os.loop.img' );
			$value[0] = number_format( $value[0] );
		}
		$xtpl->assign( 'VALUE', $value );
		$xtpl->parse( 'main.os.loop' );
		++$a;
	}

	if( $ctso['others'][1] )
	{
		$class = ( $a % 2 == 0 ) ? " class=\"second\"" : "";

		$xtpl->assign( 'CLASS', $class );
		$xtpl->assign( 'URL', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $module_info['alias']['allos'] );
		$xtpl->parse( 'main.os.ot' );
	}

	$xtpl->parse( 'main.os' );
	//Thong ke theo he dieu hanh

	$xtpl->parse( 'main' );

	return $xtpl->text( 'main' );
}