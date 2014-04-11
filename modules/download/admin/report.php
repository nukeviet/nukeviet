<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 2-9-2010 14:43
 */

if( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );

// Check file error
if( $nv_Request->isset_request( 'linkcheck', 'post' ) )
{
	if( ! defined( 'NV_IS_AJAX' ) ) die( 'Wrong URL' );

	$id = $nv_Request->get_int( 'id', 'post', 0 );

	$query = 'SELECT id, fileupload, linkdirect FROM ' . NV_PREFIXLANG . '_' . $module_data . ' WHERE id=' . $id;
	list( $_id, $fileupload, $linkdirect ) = $db->query( $query )->fetch( 3 );

	if( empty( $_id ) )
	{
		die( 'BAD_' . $id );
	}

	$links = array();

	if( ! empty( $fileupload ) )
	{
		$fileupload = explode( '[NV]', $fileupload );
		$fileupload = array_map( 'trim', $fileupload );
		foreach( $fileupload as $file )
		{
			if( ! empty( $file ) )
			{
				$links[] = NV_MY_DOMAIN . NV_BASE_SITEURL . NV_UPLOADS_DIR . $file;
			}
		}
	}

	if( ! empty( $linkdirect ) )
	{
		$linkdirect = explode( '[NV]', $linkdirect );
		$linkdirect = array_map( 'trim', $linkdirect );
		foreach( $linkdirect as $ls )
		{
			if( ! empty( $ls ) )
			{
				$ls = explode( '<br />', $ls );
				$ls = array_map( 'trim', $ls );

				foreach( $ls as $l )
				{
					if( ! empty( $l ) )
					{
						$links[] = $l;
					}
				}
			}
		}
	}

	if( ! empty( $links ) )
	{
		foreach( $links as $link )
		{
			if( ! nv_is_url( $link ) )
			{
				die( 'NO_' . $id );
			}
			if( ! nv_check_url( $link ) )
			{
				die( 'NO_' . $id );
			}
		}
	}

	die( 'OK_' . $id );
}

//Del
if( $nv_Request->isset_request( 'del', 'post' ) )
{
	if( ! defined( 'NV_IS_AJAX' ) ) die( 'Wrong URL' );

	$id = $nv_Request->get_int( 'id', 'post', 0 );

	$query = 'SELECT COUNT(*) FROM ' . NV_PREFIXLANG . '_' . $module_data . '_report WHERE fid=' . $id;
	$numrows = $db->query( $query )->fetchColumn();
	if( $numrows != 1 )
	{
		die( 'NO' );
	}

	$db->query( 'DELETE FROM ' . NV_PREFIXLANG . '_' . $module_data . '_report WHERE fid=' . $id );
	die( 'OK' );
}

//All del
if( $nv_Request->isset_request( 'alldel', 'post' ) )
{
	$db->query( 'DELETE FROM ' . NV_PREFIXLANG . '_' . $module_data . '_report' );
	die( 'OK' );
}

//List
$page_title = $lang_module['download_report'];

$sql = 'SELECT a.post_time AS post_time, a.post_ip AS post_ip, b.id AS id, b.title AS title, b.catid AS catid FROM ' . NV_PREFIXLANG . '_' . $module_data . '_report a INNER JOIN ' . NV_PREFIXLANG . '_' . $module_data . ' b ON a.fid=b.id ORDER BY a.post_time DESC';
$_array_report = $db->query( $sql )->fetchAll();
$num = sizeof( $_array_report );
if( ! $num )
{
	$contents = "<div style=\"padding-top:15px;text-align:center\">\n";
	$contents .= "<strong>" . $lang_module['report_empty'] . "</strong>";
	$contents .= "</div>\n";
	$contents .= "<meta http-equiv=\"refresh\" content=\"2;url=" . NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_LANG_VARIABLE . "=" . $module_name . "\" />";

	include NV_ROOTDIR . '/includes/header.php';
	echo nv_admin_theme( $contents );
	include NV_ROOTDIR . '/includes/footer.php';
	exit();
}

$listcats = nv_listcats( 0 );
if( empty( $listcats ) )
{
	Header( 'Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=cat&add=1' );
	exit();
}

$array = array();
foreach( $_array_report as $row)
{
	$array[$row['id']] = array(
		'id' => ( int )$row['id'],
		'title' => $row['title'],
		'cattitle' => $listcats[$row['catid']]['title'],
		'catlink' => NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;catid=' . $row['catid'],
		'post_time' => nv_date( 'd/m/Y H:i', $row['post_time'] ),
		'post_ip' => $row['post_ip']
	);
}

$xtpl = new XTemplate( 'report.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file );
$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign( 'GLANG', $lang_global );
$xtpl->assign( 'TABLE_CAPTION', $page_title );

if( ! empty( $array ) )
{
	foreach( $array as $row )
	{
		$xtpl->assign( 'ROW', $row );
		$xtpl->assign( 'EDIT_URL', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;edit=1&amp;id=' . $row['id'] );
		$xtpl->parse( 'main.row' );
	}
}

$xtpl->parse( 'main' );
$contents = $xtpl->text( 'main' );

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme( $contents );
include NV_ROOTDIR . '/includes/footer.php';