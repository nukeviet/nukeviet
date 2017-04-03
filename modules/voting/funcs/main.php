<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 3-6-2010 0:33
 */

if( !defined( 'NV_IS_MOD_VOTING' ) )
{
	die( 'Stop!!!' );
}

$vid = $nv_Request->get_int( 'vid', 'get', 0 );
if( !empty( $vid ) )
{
	$checkss = $nv_Request->get_title( 'checkss', 'get', '' );
	$lid = $nv_Request->get_title( 'lid', 'get', '' );
	$captcha = $nv_Request->get_title( 'captcha', 'get', '' );

	if( $checkss != md5( $vid . NV_CHECK_SESSION ) or $vid <= 0 or $lid == '' )
	{
		header( 'location:' . $global_config['site_url'] );
		exit( );
	}

	$sql = 'SELECT vid, question, acceptcm, active_captcha, groups_view, publ_time, exp_time FROM ' . NV_PREFIXLANG . '_' . $module_data . ' WHERE act=1';
	$list = $nv_Cache->db( $sql, 'vid', 'voting' );

	if( empty( $list ) or !isset( $list[$vid] ) )
	{
		header( 'location:' . $global_config['site_url'] );
		exit( );
	}

	$row = $list[$vid];
	if( (int)$row['exp_time'] < 0 or ((int)$row['exp_time'] > 0 and $row['exp_time'] < NV_CURRENTTIME) )
	{
		header( 'location:' . $global_config['site_url'] );
		exit( );
	}

	if( !nv_user_in_groups( $row['groups_view'] ) )
	{
		header( 'location:' . $global_config['site_url'] );
		exit( );
	}

	$difftimeout = 3600;
	$dir = NV_ROOTDIR . '/' . NV_LOGS_DIR . '/voting_logs';
	$log_fileext = preg_match( '/[a-z]+/i', NV_LOGS_EXT ) ? NV_LOGS_EXT : 'log';
	$pattern = '/^(.*)\.' . $log_fileext . '$/i';
	$logs = nv_scandir( $dir, $pattern );

	if( !empty( $logs ) )
	{
		foreach( $logs as $file )
		{
			$vtime = filemtime( $dir . '/' . $file );

			if( !$vtime or $vtime <= NV_CURRENTTIME - $difftimeout )
			{
				@unlink( $dir . '/' . $file );
			}
		}
	}

	$array_id = explode( ',', $lid );
	$array_id = array_map( 'intval', $array_id );
	$array_id = array_diff( $array_id, array( 0 ) );
	$count = sizeof( $array_id );

	$note = '';

	if( $count )
	{
		if( $row['active_captcha'] and !nv_capcha_txt( $captcha ) )
		{
			die( 'ERROR|' . $lang_global['securitycodeincorrect'] );
		}

		$acceptcm = (int)$row['acceptcm'];
		$logfile = md5( NV_LANG_DATA . $global_config['sitekey'] . $client_info['ip'] . $vid ) . '.' . $log_fileext;

		if( file_exists( $dir . '/' . $logfile ) )
		{
			$timeout = filemtime( $dir . '/' . $logfile );
			$timeout = ceil( ($difftimeout - NV_CURRENTTIME + $vtime) / 60 );
			$note = sprintf( $lang_module['timeoutmsg'], $timeout );
		}
		elseif( $count <= $acceptcm )
		{
			$in = implode( ',', $array_id );
			$sql = 'UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_rows SET hitstotal = hitstotal+1 WHERE vid =' . $vid . ' AND id IN (' . $in . ')';
			$db->query( $sql );
			file_put_contents( $dir . '/' . $logfile, '', LOCK_EX );
			$note = $lang_module['okmsg'];
		}
		else
		{
			$note = ($acceptcm > 1) ? sprintf( $lang_module['voting_warning_all'], $acceptcm ) : $lang_module['voting_warning_accept1'];
		}
	}

	$sql = 'SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . '_rows WHERE vid = ' . $vid . ' ORDER BY id ASC';
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
		'question' => $row['question'],
		'total' => $totalvote,
		'pubtime' => $pubtime,
		'row' => $vrow,
		'lang' => $lang,
		'note' => $note
	);

	$contents = voting_result( $voting );

	include NV_ROOTDIR . '/includes/header.php';
	$is_ajax = $nv_Request->get_int( 'nv_ajax_voting', 'post' );
	if( $is_ajax )
	{
		echo $contents;
	}
	else
	{
		echo nv_site_theme( $contents, false );
	}
	include NV_ROOTDIR . '/includes/footer.php';
}
$id='';
if( isset( $array_op[0] ) and preg_match( '/^([a-zA-Z0-9\-\_]+)\-([\d]+)$/', $array_op[0], $matches ) )
{
	$id = $matches[2];
	$alias = $matches[0];
}

$page_title = $module_info['custom_title'];
$key_words = $module_info['keywords'];

$sql = 'SELECT vid, question, link, acceptcm, active_captcha, groups_view, publ_time, exp_time,alias FROM ' . NV_PREFIXLANG . '_' . $module_data . ' WHERE act=1 ORDER BY publ_time DESC';
$list = $nv_Cache->db( $sql, 'vid', $module_name );

$allowed = array( );
$is_update = array( );

$a = 0;
foreach( $list as $row )
{
	if( $a == 0 and empty( $vid ) )
		$vid = $row['vid'];
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
if( !empty( $is_update ) )
{
	$is_update = implode( ',', $is_update );

	$sql = 'UPDATE ' . NV_PREFIXLANG . '_' . $module_data . ' SET act=0 WHERE vid IN (' . $is_update . ')';
	$db->query( $sql );

	$nv_Cache->delMod( $module_name );
}

//voting bình chọn nhiều nhất
$sql = 'SELECT t1.`vid`,`question`,`alias` FROM ' . NV_PREFIXLANG . '_' . $module_data . ' t1, ' . NV_PREFIXLANG . '_' . $module_data . '_rows t2 WHERE t1.`vid`=t2.`vid` AND `hitstotal`>0 AND act=1 ORDER BY publ_time DESC';
$listhithot = $nv_Cache->db( $sql, 'vid', $module_name );

//voting nổi bật
$sql = 'SELECT t1.`vid`,`question`,`alias` FROM ' . NV_PREFIXLANG . '_' . $module_data . ' t1, ' . NV_PREFIXLANG . '_' . $module_data . '_rows t2 WHERE t1.`vid`=t2.`vid` AND act=1 AND `hot_post`=1 ORDER BY `weight` ASC';
$listhot = $nv_Cache->db( $sql, 'vid', $module_name );
$contents = nv_view_voting_main( $allowed, $id, $listhithot, $listhot );
include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme( $contents );
include NV_ROOTDIR . '/includes/footer.php';
