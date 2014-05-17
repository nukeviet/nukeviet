<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 12/31/2009 2:29
 */

if( ! defined( 'NV_ADMIN' ) or ! defined( 'NV_MAINFILE' ) or ! defined( 'NV_IS_MODADMIN' ) ) die( 'Stop!!!' );

$allow_func = array( 'main', 'add', 'filequeue', 'report', 'config', 'cat' );

define( 'NV_IS_FILE_ADMIN', true );

/**
 * get_allow_exts()
 *
 * @return
 */
function get_allow_exts()
{
	global $global_config;

	$all_file_ext = nv_parse_ini_file( NV_ROOTDIR . '/includes/ini/mime.ini', true );
	$file_allowed_ext = ( array )$global_config['file_allowed_ext'];

	$exts = array();
	if( ! empty( $file_allowed_ext ) )
	{
		foreach( $file_allowed_ext as $type )
		{
			if( ! empty( $type ) and isset( $all_file_ext[$type] ) )
			{
				foreach( $all_file_ext[$type] as $e => $m )
				{
					if( ! in_array( $e, $global_config['forbid_extensions'] ) and ! in_array( $m, $global_config['forbid_mimes'] ) )
					{
						$exts[$e] = is_array( $m ) ? implode( ', ', $m ) : $m;
					}
				}
			}
		}
	}

	return $exts;
}

/**
 * nv_setcats()
 *
 * @param mixed $list2
 * @param mixed $id
 * @param mixed $list
 * @param integer $m
 * @param integer $num
 * @return
 */
function nv_setcats( $list2, $id, $list, $m = 0, $num = 0 )
{
	++$num;
	$defis = '';
	for( $i = 0; $i < $num; ++$i )
	{
		$defis .= '--';
	}

	if( isset( $list[$id] ) )
	{
		foreach( $list[$id] as $value )
		{
			if( $value['id'] != $m )
			{
				$list2[$value['id']] = $value;
				$list2[$value['id']]['name'] = '|' . $defis . '&gt; ' . $list2[$value['id']]['name'];
				if( isset( $list[$value['id']] ) )
				{
					$list2 = nv_setcats( $list2, $value['id'], $list, $m, $num );
				}
			}
		}
	}
	return $list2;
}

/**
 * nv_listcats()
 *
 * @param mixed $parentid
 * @param integer $m
 * @return
 */
function nv_listcats( $parentid, $m = 0 )
{
	global $db, $module_data;

	$sql = 'SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . '_categories ORDER BY parentid, weight ASC';
	$result = $db->query( $sql );
	$list = array();
	while( $row = $result->fetch() )
	{
		$list[$row['parentid']][] = array(
			'id' => ( int )$row['id'],
			'parentid' => ( int )$row['parentid'],
			'title' => $row['title'],
			'alias' => $row['alias'],
			'description' => $row['description'],
			'groups_view' => ! empty( $row['groups_view'] ) ? explode( ',', $row['groups_view'] ) : array( 6 ),
			'groups_download' => ! empty( $row['groups_download'] ) ? explode( ',', $row['groups_download'] ) : array( 6 ),
			'weight' => ( int )$row['weight'],
			'status' => $row['status'],
			'name' => $row['title'],
			'selected' => $parentid == $row['id'] ? ' selected="selected"': ''
		);
	}

	if( empty( $list ) )
	{
		return $list;
	}

	$list2 = array();
	foreach( $list[0] as $value )
	{
		if( $value['id'] != $m )
		{
			$list2[$value['id']] = $value;
			if( isset( $list[$value['id']] ) )
			{
				$list2 = nv_setcats( $list2, $value['id'], $list, $m );
			}
		}
	}

	return $list2;
}

//Check file
if( $nv_Request->isset_request( 'check', 'post' ) )
{
	if( ! defined( 'NV_IS_AJAX' ) ) die( 'Wrong URL' );

	$url = $nv_Request->get_string( 'url', 'post', '' );
	$is_myurl = $nv_Request->get_int( 'is_myurl', 'post', 0 );

	if( empty( $url ) ) die( $lang_module['file_checkUrl_error'] );

	$url = rawurldecode( $url );

	if( $is_myurl )
	{
		$url = substr( $url, strlen( NV_BASE_SITEURL ) );
		$url = NV_ROOTDIR . '/' . $url;
		if( ! file_exists( $url ) ) die( $lang_module['file_checkUrl_error'] );
	}
	else
	{
		$url = trim( $url );
		$url = nv_nl2br( $url, '<br />' );
		$url = explode( '<br />', $url );
		$url = array_map( 'trim', $url );
		foreach( $url as $l )
		{
			if( ! empty( $l ) )
			{
				if( ! nv_is_url( $l ) ) die( $lang_module['file_checkUrl_error'] );
				if( ! nv_check_url( $l ) ) die( $lang_module['file_checkUrl_error'] );
			}
		}
	}

	die( $lang_module['file_checkUrl_ok'] );
}

//Download file
if( $nv_Request->isset_request( 'fdownload', 'get' ) )
{
	$file = $nv_Request->get_string( 'fdownload', 'get', '' );
	if( ! empty( $file ) )
	{
		$file = substr( $file, strlen( NV_BASE_SITEURL ) );
		$file = NV_ROOTDIR . '/' . $file;

		require_once NV_ROOTDIR . '/includes/class/download.class.php';

		$download = new download( $file, NV_UPLOADS_REAL_DIR );

		$download->download_file();
	}
	exit();
}