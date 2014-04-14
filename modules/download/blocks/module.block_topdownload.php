<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 3/9/2010 23:25
 */

if( ! defined( 'NV_IS_MOD_DOWNLOAD' ) ) die( 'Stop!!!' );

global $db, $module_name, $module_data, $module_info, $module_file, $lang_module, $list_cats, $global_config;

$path = NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_file;
if( ! file_exists( NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_file . '/block_topdownload.tpl' ) )
{
	$path = NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_file;
}

$db->sqlreset()
	->select( 'catid, title, alias, download_hits' )
	->from( NV_PREFIXLANG . '_' . $module_data )
	->where( 'status=1' )
	->order( 'download_hits DESC' )
	->limit( 5 );
$result = $db->query( $db->sql() );

$xtpl = new XTemplate( 'block_topdownload.tpl', $path );
$xtpl->assign( 'LANG', $lang_module );

$i = 1;
while( $row = $result->fetch() )
{
	$catalias = $list_cats[$row['catid']]['alias'];
	$row['link'] = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $catalias . '/' . $row['alias'] . $global_config['rewrite_exturl'];
	$row['order'] = $i;
	$xtpl->assign( 'loop', $row );
	$xtpl->parse( 'main.loop' );
	++$i;
}

$xtpl->parse( 'main' );
$content = $xtpl->text( 'main' );