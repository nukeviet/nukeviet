<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 12/31/2009 0:51
 */

if( !defined( 'NV_IS_MOD_VOTING' ) )
{
	die( 'Stop!!!' );
}
/**
 * nv_view_voting_main()
 *
 * @param mixed $voting
 * @return
 */
function nv_view_voting_main( $allowed, $id, $listhithot, $listhot )
{
	global $lang_module, $module_info, $module_name, $module_data, $db;
	$xtpl = new XTemplate( 'main.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_info['module_theme'] );
	$xtpl->assign( 'LANG', $lang_module );
	$link_url=nv_url_rewrite(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name , true);
	$xtpl->assign( 'LINKURL', $link_url );
	$i = 0;
	foreach( $allowed as $current_voting )
	{
		if( !empty( $id ) )
		{
			if( $id == $current_voting['vid'] )
			{
				nv_view_voting( $current_voting, $xtpl, $module_name );
				//voting result
				$voting = voting_result_main( $current_voting );
			}
		}
		elseif( $i == 0 )
		{
			nv_view_voting( $current_voting, $xtpl, $module_name );
			//voting result

			$sql = 'SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . '_rows WHERE vid = ' . $current_voting['vid'] . ' ORDER BY id ASC';
			$result = $db->query( $sql );

			$totalvote = 0;
			$vrow = array( );

			while( $row2 = $result->fetch( ) )
			{
				$totalvote += (int)$row2['hitstotal'];
				$vrow[] = $row2;
			}

			$pubtime = nv_date( 'l - d/m/Y H:i', $row2['publ_time'] );
			$lang = array(
				'total' => $lang_module['voting_total'],
				'counter' => $lang_module['voting_counter'],
				'publtime' => $lang_module['voting_pubtime']
			);
			$voting = array(
				'question' => $current_voting['question'],
				'total' => $totalvote,
				'pubtime' => $pubtime,
				'row' => $vrow,
				'lang' => $lang,
			);
		}
		//voting mới nhất
		$link = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '/' . $current_voting['alias'] . '-' . $current_voting['vid'];
		$xtpl->assign( 'LINKNEW', $link );
		$title = $current_voting['question'];
		$xtpl->assign( 'TITILENEW', $title );
		$xtpl->parse( 'main.loopvotingnew' );
		$i++;
	}

	//voting bình chọn nhiều nhất
	foreach( $listhithot as $row )
	{
		$link = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . $row['alias'] . '-' . $row['vid'];
		$xtpl->assign( 'LINKNEW', $link );
		$title = $row['question'];
		$xtpl->assign( 'TITILENEW', $title );
		$xtpl->parse( 'main.loopvotinghithot' );
	}

	//voting nổi bật
	foreach( $listhot as $row )
	{
		$link = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . $row['alias'] . '-' . $row['vid'];
		$xtpl->assign( 'LINKNEW', $link );
		$title = $row['question'];
		$xtpl->assign( 'TITILENEW', $title );
		$xtpl->parse( 'main.loopvotinghot' );
	}

	//result
	$xtpl->assign( 'VOTINGQUESTION', $voting['question'] );
	$xtpl->assign( 'VOTINGTIME', $voting['pubtime'] );

	if( !empty( $voting['note'] ) )
	{
		$xtpl->assign( 'VOTINGNOTE', $voting['note'] );
		$xtpl->parse( 'main.note' );
	}
	if( isset( $voting['row'] ) )
	{
		$a = 1;
		$b = 0;
		foreach( $voting['row'] as $voting_i )
		{
			if( $voting['total'] )
			{
				$width = ($voting_i['hitstotal'] / $voting['total']) * 100;
				$width = round( $width, 2 );
			}
			else
			{
				$width = 0;
			}

			if( $width )
			{
				++$b;
			}

			$xtpl->assign( 'VOTING', $voting_i );
			$xtpl->assign( 'BG', (($b % 2 == 1) ? 'background-color: rgb(0, 102, 204);' : '') );
			$xtpl->assign( 'ID', $a );
			$xtpl->assign( 'WIDTH', $width );
			$xtpl->assign( 'TOTAL', $voting['total'] );
			if( $voting_i['title'] )
			{
				$xtpl->parse( 'main.result' );
			}
			++$a;
		}
	}
	$xtpl->parse( 'main' );
	return $xtpl->text( 'main' );
}

/**
 * voting_result_main()
 *
 * @param mixed $voting
 * @return
 */
function voting_result_main( $current_voting )
{
	global $lang_module, $module_info, $module_name, $module_data, $db;
	$sql = 'SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . '_rows WHERE vid = ' . $current_voting['vid'] . ' ORDER BY id ASC';
	$result = $db->query( $sql );

	$totalvote = 0;
	$vrow = array( );

	while( $row2 = $result->fetch( ) )
	{
		$totalvote += (int)$row2['hitstotal'];
		$vrow[] = $row2;
	}

	$pubtime = nv_date( 'l - d/m/Y H:i',$row2['publ_time'] );
	$lang = array(
		'total' => $lang_module['voting_total'],
		'counter' => $lang_module['voting_counter'],
		'publtime' => $lang_module['voting_pubtime']
	);
	$voting = array(
		'question' => $current_voting['question'],
		'total' => $totalvote,
		'pubtime' => $pubtime,
		'row' => $vrow,
		'lang' => $lang,
	);
	return $voting;
}

/**
 * voting_result()
 *
 * @param mixed $voting
 * @return
 */
function voting_result( $voting )
{
	global $module_info, $global_config;

	$xtpl = new XTemplate( 'result.voting.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_info['module_theme'] );
	$xtpl->assign( 'PUBLTIME', $voting['pubtime'] );
	$xtpl->assign( 'LANG', $voting['lang'] );
	$xtpl->assign( 'VOTINGQUESTION', $voting['question'] );

	if( !empty( $voting['note'] ) )
	{
		$xtpl->assign( 'VOTINGNOTE', $voting['note'] );
		$xtpl->parse( 'main.note' );
	}
	if( isset( $voting['row'] ) )
	{
		$a = 1;
		$b = 0;
		foreach( $voting['row'] as $voting_i )
		{
			if( $voting['total'] )
			{
				$width = ($voting_i['hitstotal'] / $voting['total']) * 100;
				$width = round( $width, 2 );
			}
			else
			{
				$width = 0;
			}

			if( $width )
			{
				++$b;
			}

			$xtpl->assign( 'VOTING', $voting_i );
			$xtpl->assign( 'BG', (($b % 2 == 1) ? 'background-color: rgb(0, 102, 204);' : '') );
			$xtpl->assign( 'ID', $a );
			$xtpl->assign( 'WIDTH', $width );
			$xtpl->assign( 'TOTAL', $voting['total'] );
			if( $voting_i['title'] )
			{
				$xtpl->parse( 'main.result' );
			}
			++$a;
		}
	}
	$xtpl->parse( 'main' );
	return $xtpl->text( 'main' );
}
