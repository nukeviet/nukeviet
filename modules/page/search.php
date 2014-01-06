<?php

/**
 * @Project NUKEVIET V3
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2012 VINADES.,JSC. All rights reserved
 * @Createdate 03-05-2010
 */

if( ! defined( 'NV_IS_MOD_SEARCH' ) ) die( 'Stop!!!' );

$db->sqlreset()
	->select( 'COUNT(*)' )
	->from(NV_PREFIXLANG . '_' . $m_values['module_data'])
	->where( 'status=1 AND (' . nv_like_logic( 'title', $dbkeyword, $logic ) . ' OR ' . nv_like_logic( 'bodytext', $dbkeyword, $logic ) . ')' );

$all_page = $db->query( $db->sql() )->fetchColumn();

if( $all_page )
{
	$link = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $m_values['module_name'] . '&amp;' . NV_OP_VARIABLE . '=';

	$db->select( 'id,title,alias,bodytext' )
		->limit( $limit )
		->offset( $page );
	$result = $db->query( $db->sql() );
	while( list( $id, $tilterow, $alias, $content ) = $result->fetch( 3 ) )
	{
		$result_array[] = array(
			'link' => $link . $alias,
			'title' => BoldKeywordInStr( $tilterow, $key, $logic ),
			'content' => BoldKeywordInStr( $content, $key, $logic )
		);
	}
}

?>