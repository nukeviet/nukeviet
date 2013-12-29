<?php

/**
 * @Project NUKEVIET 3.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2012 VINADES.,JSC. All rights reserved
 * @Createdate 3/9/2010 23:25
 */

if( ! defined( 'NV_IS_MOD_NEWS' ) ) die( 'Stop!!!' );

global $module_data, $module_name, $module_file, $global_array_cat, $lang_module, $my_head, $global_config, $sdr;

$my_head .= "<script type=\"text/javascript\" src=\"" . NV_BASE_SITEURL . "js/ui/jquery.ui.core.min.js\"></script>\n";
$my_head .= "<script type=\"text/javascript\" src=\"" . NV_BASE_SITEURL . "js/ui/jquery.ui.tabs.min.js\"></script>\n";
$my_head .= "	<script type=\"text/javascript\">\n";
$my_head .= "	$(function() {\n";
$my_head .= "		$(\"#tabs_top\").tabs();\n";
$my_head .= "	});\n";
$my_head .= "	</script>\n";

$xtpl = new XTemplate( 'block_newsright.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_file );
$xtpl->assign( 'BASESITE', NV_BASE_SITEURL );
$xtpl->assign( 'LANG', $lang_module );

$sdr->reset()
	->select( 'id, catid, publtime, exptime, title, alias' )
	->from( NV_PREFIXLANG . '_' . $module_data . '_rows' )
	->where( 'status = 1' )
	->order( 'hitstotal DESC' )
	->limit( 5 );

$result = $db->query( $sdr->get() );
if( $result->rowCount() )
{
	$i = 1;
	while( $row = $result->fetch() )
	{
		$row['link'] = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $global_array_cat[$row['catid']]['alias'] . '/' . $row['alias'] . '-' . $row['id'] . $global_config['rewrite_exturl'];
		$row['catname'] = $global_array_cat[$row['catid']]['title'];
		$xtpl->assign( 'topviews', $row );
		$xtpl->parse( 'main.topviews.loop' );
	}
	$xtpl->parse( 'main.topviews' );
}

$sdr->reset()
	->select( '*' )
	->from( NV_PREFIXLANG . '_' . $module_data . '_comments' )
	->where( 'status= 1' )
	->order( 'cid DESC' )
	->limit( 5 );
$result = $db->query( $sdr->get() );

if( $result->rowCount() )
{
	$i = 1;
	while( $row = $result->fetch() )
	{
		list( $catid, $alias ) = $db->query( 'SELECT catid, alias FROM ' . NV_PREFIXLANG . '_' . $module_data . '_rows WHERE id=' . $row['id'] )->fetch( 3 );
		$row['link'] = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $global_array_cat[$catid]['alias'] . '/' . $alias . '-' . $row['id'] . $global_config['rewrite_exturl'];
		$row['catname'] = $global_array_cat[$catid]['title'];
		$row['content'] = nv_clean60( $row['content'], 100 );
		$xtpl->assign( 'topcomment', $row );
		$xtpl->parse( 'main.topcomment.loop' );
	}
	$xtpl->parse( 'main.topcomment' );
}

$xtpl->parse( 'main' );
$content = $xtpl->text( 'main' );

?>