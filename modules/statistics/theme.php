<?php

/**
 * @Project NUKEVIET 3.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @copyright 2009
 * @createdate 10/03/2010 10:51
 */

if( ! defined( 'NV_IS_MOD_STATISTICS' ) ) die( 'Stop!!!' );

/**
 * referer()
 * 
 * @return
 */
function referer()
{
	global $module_info, $global_config, $module_file, $db, $lang_module, $lang_global, $nv_Request, $module_name, $all_page, $countries_list, $cts, $host_list, $total;

	$xtpl = new XTemplate( "referer.tpl", NV_ROOTDIR . "/themes/" . $module_info['template'] . '/modules/' . $module_file );

	if( $total )
	{
		$xtpl->assign( 'CTS', $cts );

		foreach( $cts['rows'] as $m )
		{
			if( ! empty( $m['count'] ) )
			{
				$proc = ceil( ( $m['count'] / $cts['max'] ) * 100 );

				$xtpl->assign( 'M', $m );
				$xtpl->assign( 'SRC', NV_BASE_SITEURL . "themes/" . $module_info['template'] . "/images/statistics/bg.gif" );
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
 * allreferers()
 * 
 * @return
 */
function allreferers()
{
	global $module_info, $global_config, $module_file, $db, $lang_module, $lang_global, $nv_Request, $module_name, $all_page, $countries_list, $cts, $host_list;

	$xtpl = new XTemplate( "allreferers.tpl", NV_ROOTDIR . "/themes/" . $module_info['template'] . '/modules/' . $module_file );

	if( $all_page )
	{
		if( ! empty( $host_list ) )
		{
			$xtpl->assign( 'CTS', $cts );

			$a = 0;
			foreach( $cts['rows'] as $key => $value )
			{
				$class = ( $a % 2 == 0 ) ? "  class=\"second\"" : "";

				$xtpl->assign( 'CLASS', $class );
				$xtpl->assign( 'VALUE', $value );
				$xtpl->assign( 'KEY', $key );

				if( $value[0] )
				{
					$proc = ceil( ( $value[0] / $cts['max'] ) * 100 );

					$xtpl->assign( 'SRC', NV_BASE_SITEURL . "themes/" . $module_info['template'] . "/images/statistics/bg2.gif" );
					$xtpl->assign( 'WIDTH', $proc * 3 );

					$xtpl->parse( 'main.loop.img' );
				}

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
 * allbots()
 * 
 * @return
 */
function allbots()
{
	global $module_info, $global_config, $module_file, $db, $lang_module, $lang_global, $nv_Request, $module_name, $all_page, $bot_list, $cts;

	$xtpl = new XTemplate( "allbots.tpl", NV_ROOTDIR . "/themes/" . $module_info['template'] . '/modules/' . $module_file );

	if( $all_page )
	{
		if( ! empty( $bot_list ) )
		{
			$xtpl->assign( 'CTS', $cts );

			$a = 0;
			foreach( $cts['rows'] as $key => $value )
			{
				$class = ( $a % 2 == 0 ) ? "  class=\"second\"" : "";

				$xtpl->assign( 'CLASS', $class );
				$xtpl->assign( 'KEY', $key );
				$xtpl->assign( 'VALUE', $value );

				if( $value[0] )
				{
					$proc = ceil( ( $value[0] / $cts['max'] ) * 100 );

					$xtpl->assign( 'SRC', NV_BASE_SITEURL . "themes/" . $module_info['template'] . "/images/statistics/bg2.gif" );
					$xtpl->assign( 'WIDTH', $proc * 3 );

					$xtpl->parse( 'main.loop.img' );
				}
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
 * allos()
 * 
 * @return
 */
function allos()
{
	global $module_info, $global_config, $module_file, $db, $lang_module, $lang_global, $nv_Request, $module_name, $all_page, $os_list, $cts;

	$xtpl = new XTemplate( "allos.tpl", NV_ROOTDIR . "/themes/" . $module_info['template'] . '/modules/' . $module_file );

	if( $all_page )
	{
		if( ! empty( $os_list ) )
		{
			$xtpl->assign( 'CTS', $cts );

			$a = 0;
			foreach( $cts['rows'] as $key => $value )
			{
				$class = ( $a % 2 == 0 ) ? "  class=\"second\"" : "";

				$xtpl->assign( 'CLASS', $class );
				$xtpl->assign( 'KEY', $key );
				$xtpl->assign( 'VALUE', $value );

				if( $value[0] )
				{
					$proc = ceil( ( $value[0] / $cts['max'] ) * 100 );

					$xtpl->assign( 'SRC', NV_BASE_SITEURL . "themes/" . $module_info['template'] . "/images/statistics/bg2.gif" );
					$xtpl->assign( 'WIDTH', $proc * 3 );

					$xtpl->parse( 'main.loop.img' );
				}
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
 * allbrowsers()
 * 
 * @return
 */
function allbrowsers()
{
	global $module_info, $global_config, $module_file, $db, $lang_module, $lang_global, $nv_Request, $module_name, $all_page, $browsers_list, $cts;

	$xtpl = new XTemplate( "allbrowsers.tpl", NV_ROOTDIR . "/themes/" . $module_info['template'] . '/modules/' . $module_file );

	if( $all_page )
	{
		if( ! empty( $browsers_list ) )
		{
			$xtpl->assign( 'CTS', $cts );

			$a = 0;
			foreach( $cts['rows'] as $key => $value )
			{
				$class = ( $a % 2 == 0 ) ? "  class=\"second\"" : "";

				$xtpl->assign( 'CLASS', $class );
				$xtpl->assign( 'KEY', $key );
				$xtpl->assign( 'VALUE', $value );

				if( $value[0] )
				{
					$proc = ceil( ( $value[0] / $cts['max'] ) * 100 );

					$xtpl->assign( 'SRC', NV_BASE_SITEURL . "themes/" . $module_info['template'] . "/images/statistics/bg2.gif" );
					$xtpl->assign( 'WIDTH', $proc * 3 );

					$xtpl->parse( 'main.loop.img' );
				}
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
 * allcountries()
 * 
 * @return
 */
function allcountries()
{
	global $module_info, $global_config, $module_file, $db, $lang_module, $lang_global, $nv_Request, $module_name, $all_page, $countries_list, $cts;

	$xtpl = new XTemplate( "allcountries.tpl", NV_ROOTDIR . "/themes/" . $module_info['template'] . '/modules/' . $module_file );

	if( $all_page )
	{
		if( ! empty( $countries_list ) )
		{
			$xtpl->assign( 'CTS', $cts );

			$a = 0;
			foreach( $cts['rows'] as $key => $value )
			{
				$class = ( $a % 2 == 0 ) ? "  class=\"second\"" : "";

				$xtpl->assign( 'CLASS', $class );
				$xtpl->assign( 'VALUE', $value );
				$xtpl->assign( 'KEY', $key );

				if( $value[0] )
				{
					$proc = ceil( ( $value[1] / $cts['max'] ) * 100 );

					$xtpl->assign( 'SRC', NV_BASE_SITEURL . "themes/" . $module_info['template'] . "/images/statistics/bg2.gif" );
					$xtpl->assign( 'WIDTH', $proc * 3 );

					$xtpl->parse( 'main.loop.img' );
				}

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
 * main()
 * 
 * @return
 */
function main()
{
	global $module_info, $global_config, $module_file, $db, $lang_module, $lang_global, $ctsy, $ctsm, $ctsdm, $ctsdw, $ctsc, $ctsb, $ctso, $ctsh, $contents;

	$xtpl = new XTemplate( "main.tpl", NV_ROOTDIR . "/themes/" . $module_info['template'] . '/modules/' . $module_file );

	$xtpl->assign( 'CTS', $ctsy );

	foreach( $ctsy['rows'] as $key => $m )
	{
		if( ! empty( $m ) )
		{
			$xtpl->assign( 'M', $m );
			$proc = ceil( ( $m / $ctsy['max'] ) * 100 );
			$xtpl->assign( 'SRC', NV_BASE_SITEURL . "themes/" . $module_info['template'] . "/images/statistics/bg.gif" );
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
			$contents .= $m['count'] . "<br />";
			$proc = ceil( ( $m['count'] / $ctsm['max'] ) * 100 );
			$xtpl->assign( 'M', $m );
			$xtpl->assign( 'SRC', NV_BASE_SITEURL . "themes/" . $module_info['template'] . "/images/statistics/bg.gif" );
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
		$xtpl->assign( 'M', $m );

		if( ! empty( $m ) )
		{
			$proc = ceil( ( $m / $ctsdm['max'] ) * 100 );

			$xtpl->assign( 'SRC', NV_BASE_SITEURL . "themes/" . $module_info['template'] . "/images/statistics/bg.gif" );
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
		$xtpl->assign( 'M', $m );

		if( ! empty( $m['count'] ) )
		{
			$proc = ceil( ( $m['count'] / $ctsdw['max'] ) * 100 );

			$xtpl->assign( 'SRC', NV_BASE_SITEURL . "themes/" . $module_info['template'] . "/images/statistics/bg.gif" );
			$xtpl->assign( 'HEIGHT', $proc * 2 );

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
				$xtpl->assign( 'M', $m );

				$proc = ceil( ( $m / $ctsh['max'] ) * 100 );

				$xtpl->assign( 'SRC', NV_BASE_SITEURL . "themes/" . $module_info['template'] . "/images/statistics/bg.gif" );
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
		$class = ( $a % 2 == 0 ) ? "  class=\"second\"" : "";
		$xtpl->assign( 'CLASS', $class );
		$xtpl->assign( 'VALUE', $value );
		$xtpl->assign( 'KEY', $key );

		if( $value[1] )
		{
			$proc = ceil( ( $value[1] / $ctsc['max'] ) * 100 );

			$xtpl->assign( 'SRC', NV_BASE_SITEURL . "themes/" . $module_info['template'] . "/images/statistics/bg2.gif" );
			$xtpl->assign( 'WIDTH', $proc * 3 );

			$xtpl->parse( 'main.ct.loop.img' );
		}

		++$a;
		$xtpl->parse( 'main.ct.loop' );
	}

	if( $ctsc['others'][1] )
	{
		$class = ( $a % 2 == 0 ) ? "  class=\"second\"" : "";
		$xtpl->assign( 'CLASS', $class );
		$xtpl->assign( 'URL', NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=statistics&amp;" . NV_OP_VARIABLE . "=allcountries" );

		$xtpl->parse( 'main.ct.ot' );
	}

	$xtpl->parse( 'main.ct' );
	//Thong ke theo quoc gia

	//Thong ke theo trinh duyet
	$xtpl->assign( 'CTS', $ctsb );

	$a = 0;
	foreach( $ctsb['rows'] as $key => $value )
	{
		$class = ( $a % 2 == 0 ) ? "  class=\"second\"" : "";
		$xtpl->assign( 'CLASS', $class );
		$xtpl->assign( 'KEY', $key );
		$xtpl->assign( 'VALUE', $value );

		if( $value[0] )
		{
			$proc = ceil( ( $value[0] / $ctsb['max'] ) * 100 );
			$xtpl->assign( 'SRC', NV_BASE_SITEURL . "themes/" . $module_info['template'] . "/images/statistics/bg2.gif" );
			$xtpl->assign( 'WIDTH', $proc * 3 );

			$xtpl->parse( 'main.br.loop.img' );
		}

		$xtpl->parse( 'main.br.loop' );
		++$a;
	}

	if( $ctsb['others'][1] )
	{
		$class = ( $a % 2 == 0 ) ? "  class=\"second\"" : "";
		$xtpl->assign( 'CLASS', $class );

		$xtpl->assign( 'URL', NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=statistics&amp;" . NV_OP_VARIABLE . "=allbrowsers" );

		$xtpl->parse( 'main.br.ot' );
	}

	$xtpl->parse( 'main.br' );
	//Thong ke theo trinh duyet

	//Thong ke theo he dieu hanh
	$xtpl->assign( 'CTS', $ctso );

	$a = 0;
	foreach( $ctso['rows'] as $key => $value )
	{
		$class = ( $a % 2 == 0 ) ? "  class=\"second\"" : "";

		$xtpl->assign( 'CLASS', $class );
		$xtpl->assign( 'KEY', $key );
		$xtpl->assign( 'VALUE', $value );

		if( $value[0] )
		{
			$proc = ceil( ( $value[0] / $ctso['max'] ) * 100 );

			$xtpl->assign( 'SRC', NV_BASE_SITEURL . "themes/" . $module_info['template'] . "/images/statistics/bg2.gif" );
			$xtpl->assign( 'WIDTH', $proc * 3 );

			$xtpl->parse( 'main.os.loop.img' );
		}
		$xtpl->parse( 'main.os.loop' );
		++$a;
	}

	if( $ctso['others'][1] )
	{
		$class = ( $a % 2 == 0 ) ? "  class=\"second\"" : "";

		$xtpl->assign( 'CLASS', $class );
		$xtpl->assign( 'URL', NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=statistics&amp;" . NV_OP_VARIABLE . "=allos" );

		$xtpl->parse( 'main.os.ot' );
	}

	$xtpl->parse( 'main.os' );
	//Thong ke theo he dieu hanh

	$xtpl->parse( 'main' );

	return $xtpl->text( 'main' );
}

?>