<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 03-05-2010
 */

if( ! defined( 'NV_IS_MOD_SEARCH' ) ) die( 'Stop!!!' );

$db->sqlreset()
	->select( 'COUNT(*)' )
	->from( NV_PREFIXLANG . '_' . $m_values['module_data'] . '_rows r')
	->join( 'INNER JOIN ' . NV_PREFIXLANG . '_' . $m_values['module_data'] . '_bodytext c ON (r.id=c.id)' )
	->where('(' . nv_like_logic( 'r.title', $dbkeyword, $logic ) . ' OR ' . nv_like_logic( 'r.hometext', $dbkeyword, $logic ) . ') OR ' . nv_like_logic( 'c.bodytext', $dbkeyword, $logic ) . '	AND r.status= 1' );

$num_items = $db->query( $db->sql() )->fetchColumn();

if( $num_items )
{
	$array_cat_alias = array();
	$array_cat_alias[0] = 'other';

	$sql_cat = 'SELECT catid, alias FROM ' . NV_PREFIXLANG . '_' . $m_values['module_data'] . '_cat';
	$re_cat = $db->query( $sql_cat );
	while( list( $catid, $alias ) = $re_cat->fetch( 3 ) )
	{
		$array_cat_alias[$catid] = $alias;
	}

	$link = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $m_values['module_name'] . '&amp;' . NV_OP_VARIABLE . '=';

	$db->select( 'r.id, r.title, r.alias, r.catid, r.hometext, c.bodytext' )
		->limit( $limit )
		->offset( ( $page - 1 ) * $limit );
	$result = $db->query( $db->sql() );
	while( list( $id, $tilterow, $alias, $catid, $hometext, $bodytext ) = $result->fetch( 3 ) )
	{
		$content = $hometext . $bodytext;

		$url = $link . $array_cat_alias[$catid] . '/' . $alias . '-' . $id . $global_config['rewrite_exturl'];

		$result_array[] = array(
			'link' => $url,
			'title' => BoldKeywordInStr( $tilterow, $key, $logic ),
			'content' => BoldKeywordInStr( $content, $key, $logic )
		);
	}
}