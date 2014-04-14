<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 3/9/2010 23:25
 */

if( ! defined( 'NV_IS_MOD_DOWNLOAD' ) ) die( 'Stop!!!' );

global $module_name, $lang_module, $module_data, $list_cats, $module_file, $db, $global_config;

$xtpl = new XTemplate( 'block_lastestdownload.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_file );
$xtpl->assign( 'LANG', $lang_module );

$db->sqlreset()
	->select( 'catid, title, alias, uploadtime' )
	->from( NV_PREFIXLANG . '_' . $module_data )
	->where( 'status=1' )
	->order( 'uploadtime DESC' )
	->limit( 5 );
$result = $db->query( $db->sql() );
while( $row = $result->fetch() )
{
	$catalias = $list_cats[$row['catid']]['alias'];
	$row['link'] = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $catalias . '/' . $row['alias'] . $global_config['rewrite_exturl'];
	$row['updatetime'] = date( 'd/m/Y h:i', $row['uploadtime'] );
	$xtpl->assign( 'loop', $row );
	$xtpl->parse( 'main.loop' );
}

$xtpl->parse( 'main' );
$content = $xtpl->text( 'main' );