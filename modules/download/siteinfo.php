<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Apr 20, 2010 10:47:41 AM
 */

if( ! defined( 'NV_IS_FILE_SITEINFO' ) ) die( 'Stop!!!' );

$lang_siteinfo = nv_get_lang_module( $mod );

// Tong so file
$number = $db->query( 'SELECT COUNT(*) FROM ' . NV_PREFIXLANG . '_' . $mod_data . ' where status= 1' )->fetchColumn();
if( $number > 0 )
{
	$siteinfo[] = array( 'key' => $lang_siteinfo['siteinfo_publtime'], 'value' => $number );
}

// Tong so file het han
$number = $db->query( 'SELECT COUNT(*) FROM ' . NV_PREFIXLANG . '_' . $mod_data . ' where status= 0' )->fetchColumn();
if( $number > 0 )
{
	$siteinfo[] = array( 'key' => $lang_siteinfo['siteinfo_expired'], 'value' => $number );
}

// Tong so binh luan duoc dang
$number = $db->query( 'SELECT COUNT(*) FROM ' . NV_PREFIXLANG . '_comments where module=' . $db->quote( $mod ) . ' AND status = 1' )->fetchColumn();
if( $number > 0 )
{
	$siteinfo[] = array( 'key' => $lang_siteinfo['siteinfo_comment'], 'value' => $number );
}

// So binh luan cho duyet
$number = $db->query( 'SELECT COUNT(*) FROM ' . NV_PREFIXLANG . '_comments where module=' . $db->quote( $mod ) . ' AND status = 0' )->fetchColumn();
if( $number > 0 )
{
	$pendinginfo[] = array(
		'key' => $lang_siteinfo['siteinfo_comment_pending'],
		'value' => $number,
		'link' => NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $mod . '&amp;' . NV_OP_VARIABLE . '=comment&amp;status=0'
	);
}

// So file dang cho duyet
$sql = 'SELECT COUNT(*) as numbers FROM ' . NV_PREFIXLANG . '_' . $mod_data . '_tmp';
$array_data = nv_db_cache( $sql, '', $mod );
$number = isset( $array_data[0]['numbers'] ) ? intval( $array_data[0]['numbers'] ) : 0;
if( $number > 0 )
{
	$pendinginfo[] = array(
		'key' => $lang_siteinfo['siteinfo_users_send'],
		'value' => $number,
		'link' => NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $mod . '&amp;' . NV_OP_VARIABLE . '=filequeue'
	);
}

// So bao cao loi duoc gui toi
$number = $db->query( 'SELECT COUNT(*) FROM ' . NV_PREFIXLANG . '_' . $mod_data . '_report' )->fetchColumn();
if( $number > 0 )
{
	$pendinginfo[] = array(
		'key' => $lang_siteinfo['siteinfo_eror'],
		'value' => $number,
		'link' => NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $mod . '&amp;' . NV_OP_VARIABLE . '=report'
	);
}