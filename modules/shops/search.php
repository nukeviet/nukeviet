<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES., JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 03-05-2010
 */

if( ! defined( 'NV_IS_MOD_SEARCH' ) ) die( 'Stop!!!' );

// Fetch Limit
$db->sqlreset()->select( 'COUNT(*)' )
	->from( $db_config['prefix'] . '_' . $m_values['module_data'] . '_rows' )
	->where( "(" . nv_like_logic( NV_LANG_DATA . '_title', $dbkeyword, $logic ) . "
		OR " . nv_like_logic( 'product_code', $dbkeyword, $logic ) . "
		OR " . nv_like_logic( NV_LANG_DATA . '_bodytext', $dbkeyword, $logic ) . "
		OR " . nv_like_logic( NV_LANG_DATA . '_hometext', $dbkeyword, $logic ) . ")
		AND ( publtime < " . NV_CURRENTTIME . " AND (exptime=0 OR exptime>" . NV_CURRENTTIME . ") )" );

$num_items = $db->query( $db->sql() )->fetchColumn();

$db->select( 'id, ' . NV_LANG_DATA . '_title,' . NV_LANG_DATA . '_alias, listcatid, ' . NV_LANG_DATA . '_hometext, ' . NV_LANG_DATA . '_bodytext' )
	->order( 'id DESC' )
	->limit( $limit )
	->offset( ( $page - 1 ) * $limit );

$tmp_re = $db->query( $db->sql() );

if( $num_items )
{
	$array_cat_alias = array();

	$sql = 'SELECT catid, ' . NV_LANG_DATA . '_alias AS alias FROM ' . $db_config['prefix'] . '_' . $m_values['module_data'] . '_catalogs';
	$array_cat_alias = nv_db_cache( $sql, 'catid', $m_values['module_name'] );
	$array_cat_alias[0] = array( 'alias' => 'Other' );

	$link = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $m_values['module_name'] . '&amp;' . NV_OP_VARIABLE . '=';

	while( list( $id, $tilterow, $alias, $listcatid, $hometext, $bodytext ) = $tmp_re->fetch( 3 ) )
	{
		$content = $hometext . $bodytext;
		$catid = explode( ',', $listcatid );
		$catid = end( $catid );

		$url = $link . $array_cat_alias[$catid]['alias'] . '/' . $alias . '-' . $id . $global_config['rewrite_exturl'];

		$result_array[] = array(
			'link' => $url,
			'title' => BoldKeywordInStr( $tilterow, $key, $logic ),
			'content' => BoldKeywordInStr( $content, $key, $logic )
		);
	}
}