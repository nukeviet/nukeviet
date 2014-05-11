<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 3/25/2010 18:6
 */

if( ! defined( 'NV_SYSTEM' ) ) die( 'Stop!!!' );

if( ! nv_function_exists( 'nv_block_voting' ) )
{

	/**
	 * nv_block_voting()
	 *
	 * @return
	 */
	function nv_block_voting()
	{
		global $db, $my_head, $site_mods, $global_config, $client_info;

		$content = '';

		if( ! isset( $site_mods['voting'] ) ) return '';

		$sql = 'SELECT vid, question, link, acceptcm, groups_view, publ_time, exp_time FROM ' . NV_PREFIXLANG . '_' . $site_mods['voting']['module_data'] . ' WHERE act=1';

		$list = nv_db_cache( $sql, 'vid', 'voting' );

		if( empty( $list ) ) return '';

		$allowed = array();
		$is_update = array();

		$a = 0;
		foreach( $list as $row )
		{
			if( $row['exp_time'] > 0 and $row['exp_time'] < NV_CURRENTTIME )
			{
				$is_update[] = $row['vid'];
			}
			elseif( $row['publ_time'] <= NV_CURRENTTIME and nv_user_in_groups( $row['groups_view'] ) )
			{
				$allowed[$a] = $row;
				++$a;
			}
		}

		if( ! empty( $is_update ) )
		{
			$is_update = implode( ',', $is_update );

			$sql = 'UPDATE ' . NV_PREFIXLANG . '_' . $site_mods['voting']['module_data'] . ' SET act=0 WHERE vid IN (' . $is_update . ')';
			$db->query( $sql );

			nv_del_moduleCache( 'voting' );
		}

		if( $allowed )
		{
			--$a;
			$rand = rand( 0, $a );
			$current_voting = $allowed[$rand];

			$sql = 'SELECT id, vid, title, url FROM ' . NV_PREFIXLANG . '_' . $site_mods['voting']['module_data'] . '_rows WHERE vid = ' . $current_voting['vid'] . ' ORDER BY id ASC';

			$list = nv_db_cache( $sql, '', 'voting' );

			if( empty( $list ) ) return '';

			include NV_ROOTDIR . '/modules/' . $site_mods['voting']['module_file'] . '/language/' . NV_LANG_INTERFACE . '.php' ;

			if( file_exists( NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $site_mods['voting']['module_file'] . '/global.voting.tpl' ) )
			{
				$block_theme = $global_config['module_theme'];
			}
			elseif( file_exists( NV_ROOTDIR . '/themes/' . $global_config['site_theme'] . '/modules/' . $site_mods['voting']['module_file'] . '/global.voting.tpl' ) )
			{
				$block_theme = $global_config['site_theme'];
			}
			else
			{
				$block_theme = 'default';
			}

			if( ! defined( 'SHADOWBOX' ) )
			{
				$my_head .= "<link rel=\"Stylesheet\" href=\"" . NV_BASE_SITEURL . "js/shadowbox/shadowbox.css\" />\n";
				$my_head .= "<script type=\"text/javascript\" src=\"" . NV_BASE_SITEURL . "js/shadowbox/shadowbox.js\"></script>\n";
				$my_head .= "<script type=\"text/javascript\">Shadowbox.init();</script>";
				define( 'SHADOWBOX', true );
			}
			$my_head .= "<script type=\"text/javascript\" src=\"" . NV_BASE_SITEURL . "modules/" . $site_mods['voting']['module_file'] . "/js/user.js\"></script>\n";

			$action = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=voting';

			$voting_array = array(
				'checkss' => md5( $current_voting['vid'] . $client_info['session_id'] . $global_config['sitekey'] ),
				'accept' => ( int )$current_voting['acceptcm'],
				'errsm' => ( int )$current_voting['acceptcm'] > 1 ? sprintf( $lang_module['voting_warning_all'], ( int )$current_voting['acceptcm'] ) : $lang_module['voting_warning_accept1'],
				'vid' => $current_voting['vid'],
				'question' => ( empty( $current_voting['link'] ) ) ? $current_voting['question'] : '<a target="_blank" href="' . $current_voting['link'] . '">' . $current_voting['question'] . '</a>',
				'action' => $action,
				'langresult' => $lang_module['voting_result'],
				'langsubmit' => $lang_module['voting_hits']
			);

			$xtpl = new XTemplate( 'global.voting.tpl', NV_ROOTDIR . '/themes/' . $block_theme . '/modules/' . $site_mods['voting']['module_file'] );
			$xtpl->assign( 'VOTING', $voting_array );
			foreach( $list as $row )
			{
				if( ! empty( $row['url'] ) )
				{
					$row['title'] = '<a target="_blank" href="' . $row['url'] . '">' . $row['title'] . '</a>';
				}
				$xtpl->assign( 'RESULT', $row );
				if( ( int )$current_voting['acceptcm'] > 1 )
				{
					$xtpl->parse( 'main.resultn' );
				}
				else
				{
					$xtpl->parse( 'main.result1' );
				}
			}
			$xtpl->parse( 'main' );
			$content = $xtpl->text( 'main' );
		}

		return $content;
	}
}
if( defined( 'NV_SYSTEM' ) )
{
	$content = nv_block_voting();
}