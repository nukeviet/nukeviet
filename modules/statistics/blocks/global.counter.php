<?php

/**
 * @Project NUKEVIET 3.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2012 VINADES.,JSC. All rights reserved
 * @Createdate 3/9/2010 23:25
 */

if( ! defined( 'NV_MAINFILE' ) ) die( 'Stop!!!' );

if( ! nv_function_exists( 'nv_block_counter' ) )
{
	function nv_block_counter()
	{
		global $global_config, $db, $lang_global;

		if( file_exists( NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/blocks/global.counter.tpl" ) )
		{
			$block_theme = $global_config['module_theme'];
		}
		elseif( file_exists( NV_ROOTDIR . "/themes/" . $global_config['site_theme'] . "/blocks/global.counter.tpl" ) )
		{
			$block_theme = $global_config['site_theme'];
		}
		else
		{
			$block_theme = "default";
		}

		$xtpl = new XTemplate( "global.counter.tpl", NV_ROOTDIR . "/themes/" . $block_theme . "/blocks" );

		$xtpl->assign( 'LANG', $lang_global );
		$xtpl->assign( 'IMG_PATH', NV_BASE_SITEURL . "themes/" . $block_theme . "/" );

		$sql = "SELECT `c_type`, `c_val`, `c_count` FROM `" . NV_COUNTER_TABLE . "`";
		$query = $db->sql_query( $sql );

		while( list( $c_type, $c_val, $c_count ) = $db->sql_fetchrow( $query ) )
		{
			if( $c_type == 'day' and $c_val == date( 'd', NV_CURRENTTIME ) )
			{
				$xtpl->assign( 'COUNT_DAY', $c_count );
			}
			elseif( $c_type == 'month' and $c_val == date( 'M', NV_CURRENTTIME ) )
			{
				$xtpl->assign( 'COUNT_MONTH', $c_count );
			}
			elseif( $c_type == 'total' and $c_val == 'hits' )
			{
				$xtpl->assign( 'COUNT_ALL', $c_count );
			}
		}

		$sql = "SELECT `uid`, `full_name` FROM `" . NV_SESSIONS_GLOBALTABLE . "` WHERE `onl_time` >= " . ( NV_CURRENTTIME - NV_ONLINE_UPD_TIME );
		$query = $db->sql_query( $sql );

		$count_online = $users = $bots = $guests = 0;
		while( $row = $db->sql_fetchrow( $query ) )
		{
			++$count_online;

			if( $row['uid'] != 0 )
			{
				++$users;
			}
			else
			{
				if( preg_match( "/^bot\:/", $row['full_name'] ) )
				{
					++$bots;
				}
				else
				{
					++$guests;
				}
			}
		}

		$xtpl->assign( 'COUNT_ONLINE', $count_online );

		$xtpl->assign( 'NV_BASE_SITEURL', NV_BASE_SITEURL );

		if( $users )
		{
			$xtpl->assign( 'COUNT_USERS', $users );
			$xtpl->parse( 'main.users' );
		}

		if( $bots )
		{
			$xtpl->assign( 'COUNT_BOTS', $bots );
			$xtpl->parse( 'main.bots' );
		}

		if( $guests and $guests != $count_online )
		{
			$xtpl->assign( 'COUNT_GUESTS', $guests );
			$xtpl->parse( 'main.guests' );
		}

		$xtpl->parse( 'main' );
		$content = $xtpl->text( 'main' );

		return $content;
	}
}

if( defined( 'NV_SYSTEM' ) )
{
	global $global_config;
	if( $global_config['online_upd'] )
	{
		$content = nv_block_counter();
	}
}

?>